<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessRequest;
use Illuminate\Support\Facades\Auth;
class WorkerTaskController extends Controller
{
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
    public function updateStatus(Request $request, BusinessRequest $businessRequest)
{
    // Validate the incoming status
    $validated = $request->validate([
        'status' => 'required|in:WORKING,COMPLETED',
    ]);

    // Update the status
    $businessRequest->update([
        'status' => $validated['status']
    ]);

    $message = $validated['status'] === 'WORKING' 
        ? '作業を開始しました。' 
        : '作業を完了としてマークしました。';

    return back()->with('success', $message);
}
public function myTasks()
{
    $tasks = BusinessRequest::where('worker_id', auth::id()) // Assigned to me
        ->whereIn('status', ['APPROVED', 'WORKING', 'COMPLETED']) // Show all active/done statuses
        ->orderBy('due_date', 'asc')
        ->get();

    return view('business-requests.my_tasks', compact('tasks'));
}
}