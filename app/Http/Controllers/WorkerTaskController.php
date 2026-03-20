<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessRequest;
use Illuminate\Support\Facades\Auth;
class WorkerTaskController extends Controller
{
    // TaskController.php

public function index()
{
    // English: Fetch only tasks assigned to the current user
    $tasks = BusinessRequest::where('worker_id', auth::id()) 
        ->whereIn('status', ['APPROVED', 'WORKING', 'COMPLETED'])
        ->orderByRaw("FIELD(status, 'WORKING', 'APPROVED', 'COMPLETED')") // Priority to active work
        ->get();

    return view('business-requests.my_tasks', compact('tasks'));
}

    public function start(BusinessRequest $businessRequest)
    {
        if ($businessRequest->status !== 'APPROVED') {
            return back()->with('error', 'Request not approved yet.');
        }

        $businessRequest->update([
            'status' => 'WORKING',
        ]);

        return back()->with('success', 'Work started.');
    }

    public function complete(BusinessRequest $businessRequest)
    {
        $businessRequest->update([
            'status' => 'COMPLETE'
        ]);

        return back()->with('success', 'Task completed.');
    }
   public function updateStatus(Request $request, $id)
{
    $task = BusinessRequest::findOrFail($id);

    // English: Strict security check - Only the assigned worker can trigger this
    if ($task->worker_id !== auth::id()) {
        return back()->with('error', 'この作業の担当者ではないため、操作を完了できません。');
    }

    $validated = $request->validate([
        'status' => 'required|in:WORKING,COMPLETED',
    ]);

    $task->update(['status' => $validated['status']]);

    $message = $validated['status'] === 'WORKING' 
        ? '作業を開始しました。' 
        : '作業を完了しました。';

    return back()->with('success', $message);
}

// public function myTasks()
// {
//     $tasks = BusinessRequest::where('worker_id', auth::id()) // Assigned to me
//         ->whereIn('status', ['APPROVED', 'WORKING', 'COMPLETED']) // Show all active/done statuses
//         ->orderBy('due_date', 'asc')
//         ->get();

//     return view('business-requests.my_tasks', compact('tasks'));
// }
}