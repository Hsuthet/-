<?php

namespace App\Http\Controllers;

use App\Models\BusinessRequest;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BusinessRequestController extends Controller
{
    // 1. LIST: View all requests
   // BusinessRequestController.php

public function index()
{
    $user = Auth::user();

    // default empty collections
    $requests = collect();
    $workerTasks = collect();
    $managerRequests = collect();

    // Common relationships
    $relations = [
        'categories',
        'user.department',
        'targetDepartment',
        'attachments',
        'worker'
    ];

    // EMPLOYEE
    if ($user->role === 'employee') {

        // Requests created by employee
        $requests = BusinessRequest::with($relations)
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Tasks assigned to employee
        $workerTasks = BusinessRequest::with($relations)
            ->where('worker_id', $user->id)
            ->latest()
            ->get();
    }

    // MANAGER
    elseif ($user->role === 'manager') {

        $managerRequests = BusinessRequest::with($relations)
            ->where('status', 'PENDING')
            ->latest()
            ->get();
    }

    // ADMIN (optional)
    elseif ($user->role === 'admin') {

        $requests = BusinessRequest::with($relations)
            ->latest()
            ->get();
    }

    return view('business-requests.index', compact(
        'requests',
        'workerTasks',
        'managerRequests'
    ));
}

    // 2. CREATE: Show the input form
    public function create()
    {
        $categories = Category::all();
        $departments = Department::all(); 
        $nextNumber = $this->generateNextNumber();
        $user = Auth::user()->load('department');

        return view('business-requests.create', compact('categories', 'departments', 'nextNumber', 'user'));
    }

    // 3. CONFIRM: Validation and Preview
    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'request_number' => 'required',
            'title'          => 'required|string',
            'department_id'  => 'required',
            'due_date'       => 'required|date',
            'content'        => 'required|string', // create blade's description
            'categories'     => 'required|array',
            'notes'          => 'nullable|string', // create blade's special note
            'attachments.*'  => 'nullable|file|max:5120',
        ]);

        // File temporary storage logic
        $storedFiles = session('storedFiles', []);
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('temp', 'public'); 
                $storedFiles[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path
                ];
            }
            session(['storedFiles' => $storedFiles]);
        }

        // Save data to session for 'Back' button or late processing
        session(['form_data' => $request->except('attachments'), 'notes' => $request->notes]);

        return view('business-requests.confirm', [
            'data'        => $validated,
            'department'  => Department::find($request->department_id),
            'categories'  => Category::whereIn('id', $request->categories)->get(),
            'storedFiles' => $storedFiles,
            'nextNumber'  => $request->request_number,
        ]);
    }

    public function store(Request $request)
{
    // 1. Validation 
    $validated = $request->validate([
        'title'         => 'required|string|max:255',
        'description'   => 'required|string',
        'attachments.*' => 'nullable|file|max:5120',
    ]);

   
    return DB::transaction(function () use ($request) {
        
        $businessRequest = BusinessRequest::create([
            'request_number' => 'REQ-' . time(), 
            'title'          => $request->title,
            'user_id'        => Auth::id(),
            'department_id'  => $request->department_id,
            'due_date'       => $request->due_date,
            'status'         => 'PENDING',
        ]);

        
        $businessRequest->requestContent()->create([
            'description'  => $request->description,
            'special_note' => $request->special_note,
        ]);

        
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
               
                $path = $file->store('business_attachments', 'public');

           
                $businessRequest->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()->route('business-requests.index')->with('success', '依頼を作成しました');
    });
}

    // 4. COMPLETE: Actual database saving
    public function complete(Request $request)
    {
        return DB::transaction(function () use ($request) {
            // A. Create main record
            $businessRequest = BusinessRequest::create([
                'request_number' => $request->request_number,
                'user_id'        => Auth::id(),
                'department_id'  => $request->department_id,
                'title'          => $request->title,
                'due_date'       => $request->due_date,
                'status'         => 'PENDING',
            ]);

            // B. Create request_contents record (Mapped correctly)
            $businessRequest->requestContent()->create([
                'description'  => $request->content, // from hidden input 'content'
                'special_note' => $request->notes,   // from hidden input 'notes'
            ]);

            // C. Attach Categories
            if ($request->has('categories')) {
                $businessRequest->categories()->sync($request->categories);
            }

            // D. Move files from temp to permanent storage
            if ($request->has('attachment_paths')) {
                foreach ($request->attachment_paths as $index => $tempPath) {
                    $newName = str_replace('temp/', 'attachments/', $tempPath);
                    Storage::disk('public')->move($tempPath, $newName);

                    $businessRequest->attachments()->create([
                        'file_path' => $newName,
                        'file_name' => $request->attachment_names[$index],
                    ]);
                }
            }

            // Clear sessions
            session()->forget(['form_data', 'storedFiles', 'notes']);

            return redirect()->route('business-requests.index')->with('success', '依頼を登録しました。');
        });
    }

   
public function edit(BusinessRequest $businessRequest)
{
   
    if ($businessRequest->user_id !== Auth::id()) {
        return redirect()->route('business-requests.index')->with('error', '修正は許可されません。');
    }

    $categories = Category::all();
    $departments = Department::all();
  
    $businessRequest->load(['requestContent', 'categories', 'attachments']);

    return view('business-requests.edit', compact('businessRequest', 'categories', 'departments'));
}


public function update(Request $request, BusinessRequest $businessRequest)
{
    // 1. Validation 
    $validated = $request->validate([
        'title'          => 'required|string',
        'department_id'  => 'required|exists:departments,id',
        'due_date'       => 'required|date',
        'description'    => 'required|string', 
        'special_note'   => 'nullable|string',
        'categories'     => 'required|array',
        'attachments.*'  => 'nullable|file|max:5120',
    ]);

    return DB::transaction(function () use ($request, $businessRequest) {
        
        // 2. Main Table (business_requests) 
        $businessRequest->update([
            'title'         => $request->title,
            'department_id' => $request->department_id,
            'due_date'      => $request->due_date,
        ]);

        // 3. Related Content Table (request_contents)  Update 
       
        $businessRequest->requestContent()->updateOrCreate(
            ['request_id' => $businessRequest->id],
            [
                'description'  => $request->description,
                'special_note' => $request->special_note,
            ]
        );

        // 4. Categories (Pivot table) sync
        
        $businessRequest->categories()->sync($request->categories);

        // 5. Attachment 
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $businessRequest->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()->route('business-requests.index');
    });
}
    public function show(BusinessRequest $businessRequest) 
    {
        return view('business-requests.show', ['request' => $businessRequest->load(['requestContent', 'categories', 'attachments'])]);
    }

    public function destroy(BusinessRequest $businessRequest)
    {
        $businessRequest->delete(); // Cascading delete should be set in migration
        return redirect()->route('business-requests.index')->with('success', '依頼を削除しました');
    }

    private function generateNextNumber()
    {
        $year = date('y'); 
        $lastRequest = BusinessRequest::orderBy('id', 'desc')->first();

        if ($lastRequest) {
            $lastId = (int) substr($lastRequest->request_number, 3);
            $nextId = str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextId = '0001';
        }
        return $year . '-' . $nextId;
    }
}