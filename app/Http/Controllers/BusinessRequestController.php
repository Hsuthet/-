<?php

namespace App\Http\Controllers;

use App\Models\BusinessRequest;
use App\Models\Category;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BusinessRequestController extends Controller
{
    /**
     * 1. LIST: View requests based on role
     */
   public function index()
{
    $user = Auth::user();

    $relations = [
        'categories',
        'user.department',
        'targetDepartment',
        'attachments',
        'worker',
        'requestContent'
    ];

    $requests = collect();
    $workerTasks = collect();
    $managerRequests = collect();

    if ($user->role === 'admin') {
        // ✅ Admin sees EVERYTHING
        $requests = BusinessRequest::with($relations)->latest()->get();
        $workerTasks = BusinessRequest::with($relations)
            ->where('status', 'APPROVED')
            ->latest()
            ->get();
    }

    elseif ($user->role === 'employee' || $user->role === 'REQUESTER') {
        $requests = BusinessRequest::with($relations)
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $workerTasks = BusinessRequest::with($relations)
            ->where('worker_id', $user->id)
            ->where('status', 'APPROVED')
            ->latest()
            ->get();
    }

    elseif ($user->role === 'manager' || $user->role === 'APPROVER') {
        $managerRequests = BusinessRequest::with($relations)
            ->latest()
            ->get();
    }

    return view('business-requests.index', compact(
        'requests',
        'workerTasks',
        'managerRequests'
    ));
}

    /**
     * 2. CREATE: Show form
     */
   public function create(Request $request)
{
    if (!$request->user() || $request->user()->role !== 'employee') {
        abort(403);
    }

    $categories = Category::all();
    $departments = Department::all(); 
    $nextNumber = $this->generateNextNumber();
    $user = $request->user()->load('department');

    return view('business-requests.create', compact('categories', 'departments', 'nextNumber', 'user'));
}

    /**
     * 3. CONFIRM: Validation and Temporary File Storage
     */
    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'request_number' => 'required',
            'title'          => 'required|string|max:255',
            'department_id'  => 'required|exists:departments,id',
            'due_date'       => 'required|date',
            'content'        => 'required|string',
            'categories'     => 'required|array',
            'notes'          => 'nullable|string',
            'attachments.*'  => 'nullable|file|max:5120',
        ]);

        // File temporary storage logic
        $storedFiles = session('storedFiles', []);
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('temp', 'public'); 
                $storedFiles[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getClientOriginalExtension()
                ];
            }
            session(['storedFiles' => $storedFiles]);
        }

        session(['form_data' => $request->except('attachments')]);

        return view('business-requests.confirm', [
            'data'        => $validated,
            'department'  => Department::find($request->department_id),
            'categories'  => Category::whereIn('id', $request->categories)->get(),
            'storedFiles' => $storedFiles,
            'nextNumber'  => $request->request_number,
        ]);
    }

    /**
     * 4. COMPLETE: Final Database Save
     */
   public function complete(Request $request)
{
    // 1. Authorization check at the very start
    if (!Auth::check() || Auth::user()->role !== 'employee') {
        abort(403);
    }

    // DEBUG TIP: If you still get the error, uncomment the line below to see what's missing:
    // dd($request->all());

    return DB::transaction(function () use ($request) {
        $user = Auth::user();

        // 2. Create main record
        $businessRequest = BusinessRequest::create([
            'request_number'       => $request->request_number, // The culprit for your error
            'user_id'              => $user->id,
            'department_id'        => $user->department_id,
            'target_department_id' => $request->target_department_id,
            'title'                => $request->title,
            'due_date'             => $request->due_date,
            'status'               => 'PENDING',
        ]);

        // 3. Create request_contents
        // Assuming the relationship is 'requestContent' (singular)
        $businessRequest->requestContent()->create([
            'description'  => $request->content,
            'special_note' => $request->notes,
        ]);

        // 4. Attach Categories
        if ($request->has('categories')) {
            $businessRequest->categories()->sync($request->categories);
        }

        // 5. File Management
        if ($request->has('attachment_paths')) {
            foreach ($request->attachment_paths as $index => $tempPath) {
                // Ensure the name exists for this index
                $fileName = $request->attachment_names[$index] ?? 'file_' . $index;
                $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
                
                // Move to permanent storage
                $newPath = 'attachments/' . basename($tempPath);
                
                if (Storage::disk('public')->exists($tempPath)) {
                    Storage::disk('public')->move($tempPath, $newPath);

                    $businessRequest->attachments()->create([
                        'file_path' => $newPath,
                        'file_name' => $fileName,
                        'file_type' => $extension,
                    ]);
                }
            }
        }

        // 6. Clean up sessions
        session()->forget(['form_data', 'storedFiles']);

        return redirect()->route('business-requests.requests')->with('success', '依頼を送信しました。');
    });
}
    public function show(BusinessRequest $businessRequest) 
{
    // 1. Load all necessary relationships
    $businessRequest->load(['requestContent', 'categories', 'attachments', 'user.department', 'targetDepartment']);

    // 2. Fetch only employees belonging to the TARGET department of this request
    $employees = User::where('department_id', $businessRequest->target_department_id)
                     ->where('role', 'employee') 
                     ->get();

    return view('business-requests.show', [
        'request'   => $businessRequest,
        'employees' => $employees // Pass the filtered employees to the view
    ]);
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
        if ($businessRequest->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title'          => 'required|string|max:255',
            'department_id'  => 'required|exists:departments,id',
            'due_date'       => 'required|date',
            'description'    => 'required|string', 
            'categories'     => 'required|array',
        ]);

        return DB::transaction(function () use ($request, $businessRequest) {
            $businessRequest->update([
                'title'         => $request->title,
                'department_id' => $request->department_id,
                'due_date'      => $request->due_date,
            ]);

            $businessRequest->requestContent()->updateOrCreate(
                ['request_id' => $businessRequest->id], // check your FK name here
                [
                    'description'  => $request->description,
                    'special_note' => $request->special_note,
                ]
            );

            $businessRequest->categories()->sync($request->categories);

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

    public function destroy(BusinessRequest $businessRequest)
    {
        // Delete physical files before deleting record
        foreach($businessRequest->attachments as $file) {
            Storage::disk('public')->delete($file->file_path);
        }
        
        $businessRequest->delete();
        return redirect()->route('business-requests.index')->with('success', '依頼を削除しました');
    }

    private function generateNextNumber()
    {
        $year = date('y'); 
        $lastRequest = BusinessRequest::latest('id')->first();

        if ($lastRequest && strpos($lastRequest->request_number, '-') !== false) {
            $parts = explode('-', $lastRequest->request_number);
            $lastId = (int) end($parts);
            $nextId = str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextId = '0001';
        }
        return $year . '-' . $nextId;
    }

   public function myRequests()
{
    // If you want 'All' to show everything in the system:
    $requests = BusinessRequest::with(['user', 'categories'])
        ->latest()
        ->get();

    return view('business-requests.requests', compact('requests'));
}

public function myTasks()
{
    $tasks = BusinessRequest::with(['user.department', 'requestContent'])
        ->where('worker_id', auth::id())
        ->where('status', 'APPROVED') // Workers usually only see approved/active tasks
        ->latest()
        ->get();

    return view('business-requests.my_tasks', compact('tasks'));
}

public function remove(Request $request)
{
    $index = $request->input('index');
    $files = session('storedFiles', []);

    if (isset($files[$index])) {
        // Remove file from session
        unset($files[$index]);
        // Re-index the array to prevent gaps
        session(['storedFiles' => array_values($files)]);
    }

    // Return JSON so the page doesn't refresh
    return response()->json(['success' => true]);
}
}