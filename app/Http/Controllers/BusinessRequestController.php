<?php

namespace App\Http\Controllers;

use App\Models\BusinessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Department;


class BusinessRequestController extends Controller
{
    // LIST: View all requests based on status
  public function index()
{
    
    $requests = BusinessRequest::with(['user.department', 'targetDepartment', 'categories'])
                ->latest()
                ->get();

    return view('business-requests.index', compact('requests'));
}

    // CREATE: Show the form (Requester Creation)
 public function create()
{
    $categories = Category::all();
    $departments = Department::all(); 
    $nextNumber = $this->generateNextNumber();
    $user = Auth::user()->load('department');

    return view('business-requests.create', compact(
        'categories',
        'departments',
        'nextNumber',
        'user',
    ));
}

public function confirm(Request $request)
{
    $nextNumber = $request->request_number;
    // 1. Validation 
    $validated = $request->validate([
        'request_number' => 'required',
        'title' => 'required|string',
        'department_id' => 'required',
        'due_date' => 'required|date',
        'content' => 'required|string',
        'categories' => 'required|array',
        'attachments.*' => 'nullable|file|max:5120',
        
    ]);

    // 2. remove file and save other in form (Serialization error no)
    $formData = $request->except('attachments'); 
    $request->session()->put('form_data', $formData);

    // 3. save file temporary
    $storedFiles = [];
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
            $path = $file->store('temp', 'public'); 
            $storedFiles[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path
            ];
        }
    
    // Session replace with new
        session(['storedFiles' => $storedFiles]);
    } else {
        // ၂။if no new file, check stored.
        $storedFiles = session('storedFiles', []);
    }

    // store other data in session
    $request->session()->put('form_data', $request->except('attachments'));

    // 4. send to View 
    return view('business-requests.confirm', [
        'data' => $formData,
        'department' => \App\Models\Department::find($request->department_id),
        'categories' => \App\Models\Category::whereIn('id', $request->categories)->get(),
        'storedFiles' => $storedFiles,
        'nextNumber' => $nextNumber,
    ]);
}

public function complete(Request $request)
{
    //
    $businessRequest = \App\Models\BusinessRequest::create([
        'request_number' => $request->request_number, // Hidden field 
        'title'          => $request->title,
        'department_id'  => $request->department_id,
        'due_date'       => $request->due_date,
        'content'        => $request->content,
        'notes'          => $request->notes,
        'user_id'        => auth::id(),
        'status'         => 'pending',
    ]);

    if ($request->has('categories')) {
        $businessRequest->categories()->attach($request->categories);
    }

    //remove session after saving
    session()->forget(['form_data', 'storedFiles']);

    return redirect()->route('business-requests.index')->with('success', '依頼を登録しました。');
}


private function generateNextNumber()
{
    $year = date('y'); 

    $lastRequest = \App\Models\BusinessRequest::orderBy('id', 'desc')->first();

    if ($lastRequest) {
        $lastNumber = (int) substr($lastRequest->request_number, 3);
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $nextNumber = '0001';
    }

    return $year . '-' . $nextNumber;
}
    // STORE: Initial creation by Sales/Requester
 public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string',
        'department_id' => 'required',
        'due_date' => 'required|date',
        'content' => 'required|string',
        'categories' => 'required|array',
        'attachments.*' => 'file|max:5120'
    ]);

    // Get department name
    $department = \App\Models\Department::find($validated['department_id']);

    // Get category names
    $categories = \App\Models\Category::whereIn('id', $validated['categories'])->get();

    // Keep files temporarily in session
    session(['form_data' => $validated]);
    session(['notes' => $request->notes]);

    return view('business-requests.confirm', [
        'data' => $validated,
        'department' => $department,
        'categories' => $categories,
        'nextNumber' => $request->next_number,
        'files' => $request->file('attachments')
    ]);
}

//     return redirect()->route('business-requests.index')->with('success', '保存しました');
// }

    // DETAIL: View the specific request details
    public function show(BusinessRequest $businessRequest) {
        return view('business-requests.show', ['request' => $businessRequest]);
    }

    // STATUS UPDATES: Moving the flow forward
   // app/Http/Controllers/BusinessRequestController.php

public function updateStatus(Request $request, BusinessRequest $businessRequest)
{
    // $businessRequest is automatically fetched by Laravel via "Route Model Binding"
    $businessRequest->update([
        'status' => $request->status
    ]);

    return redirect()->back()->with('success', 'Status updated successfully!');
}

public function update(Request $request, BusinessRequest $businessRequest)
{
    $businessRequest->title = $request->title;
    $businessRequest->department_id = $request->department_id;
    $businessRequest->due_date = $request->due_date;
    $businessRequest->status = ($request->action === 'submit') ? 'pending_approval' : 'draft';
    $businessRequest->save();

    // Sync categories (removes old ones, adds new)
    $businessRequest->categories()->sync($request->categories ?? []);

    // Update content
    $businessRequest->requestContent()->update([
        'special_note' => $request->content,
        'description'  => $request->description ?? '',
    ]);

    return redirect()->route('business-requests.index')->with('success', '更新しました');
}
}