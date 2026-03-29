<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\RequestStatusChanged;

class RequestApprovalController extends Controller
{
    public function approveForm(BusinessRequest $request)
    {
        $employees = User::where('role','employee')
            ->where('department_id',$request->target_department_id)
            ->get();

        return view('business-requests.approve',compact('request','employees'));
    }

   // Change $request to $businessRequest
public function approve(Request $req, BusinessRequest $businessRequest) 
{
    $req->validate([
        'worker_id' => 'required'
    ]);

    $businessRequest->update([
        'status' => 'APPROVED',
        'worker_id' => $req->worker_id,
        'approved_at' => now(),
        'approver_id' => auth::id(), // Use helper auth()->id() or Auth::id()
    ]);

    // Send notification to the owner of the request
    $businessRequest->user->notify(new RequestStatusChanged($businessRequest));

    return redirect()->route('business-requests.index');
}
public function reject(Request $req, BusinessRequest $businessRequest)
{
    $req->validate(['reason' => 'required|string']);

    // 1. Update the main request status
    $businessRequest->update(['status' => 'REJECTED']);

    // 2. Record the detailed approval/rejection log
    $businessRequest->approvals()->create([
        'approver_id' => auth::id(),
        'approval_status' => 'REJECTED',
        'rejection_reason' => $req->reason, // This now saves correctly!
        'approved_at' => now(),
    ]);

    return redirect()->route('business-requests.index')->with('success', '依頼を却下しました。');
}

public function assign(Request $request, BusinessRequest $businessRequest)
{
    if ($request->action === 'approve') {
        $request->validate(['worker_id' => 'required|exists:users,id']);
        
        $businessRequest->update([
            'status' => 'APPROVED',
            'worker_id' => $request->worker_id,
        ]);
        return redirect()->route('business-requests.index')->with('success', '依頼を承認し、担当者を割り当てました。');
    
    } 

    if ($request->action === 'reject') {
        $request->validate(['reason' => 'required|string|max:500']);
        
        $businessRequest->update([
            'status' => 'REJECTED',
            'rejection_reason' => $request->reason,
        ]);
        $message = '依頼を却下しました。';
        $statusType = 'error';
       return redirect()->route('business-requests.requests')->with($statusType, $message);
    }
}
}
