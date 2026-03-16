<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RequestApprovalController extends Controller
{
    public function approveForm(BusinessRequest $request)
    {
        $employees = User::where('role','employee')
            ->where('department_id',$request->target_department_id)
            ->get();

        return view('business-requests.approve',compact('request','employees'));
    }

    public function approve(Request $req, BusinessRequest $request)
    {
        $req->validate([
            'worker_id' => 'required'
        ]);

        $request->update([
            'status' => 'APPROVED',
            'worker_id' => $req->worker_id
        ]);

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
        
        return redirect()->back()->with('success', '依頼を承認し、担当者を割り当てました。');
    } 

    if ($request->action === 'reject') {
        $request->validate(['reason' => 'required|string|max:500']);
        
        $businessRequest->update([
            'status' => 'REJECTED',
            'rejection_reason' => $request->reason,
        ]);
        $message = '依頼を却下しました。';
        $statusType = 'error';
       return redirect()->route('business-requests.my_requests')->with($statusType, $message);
    }
}
}
