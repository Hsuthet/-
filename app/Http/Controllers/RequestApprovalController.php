<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\RequestStatusChanged;

class RequestApprovalController extends Controller
{

    public function approveForm(BusinessRequest $businessRequest)
    {
        $employees = User::where('role', 'employee')
            ->where('department_id', $businessRequest->target_department_id)
            ->get();

        return view('business-requests.approve', [
            'businessRequest' => $businessRequest,
            'employees' => $employees
        ]);
    }

    // ================= APPROVE =================
    public function approve(Request $req, BusinessRequest $businessRequest)
    {
        $req->validate([
            'worker_id' => 'required|exists:users,id'
        ]);

        $businessRequest->update([
            'status' => 'APPROVED',
            'worker_id' => $req->worker_id,
            'approved_at' => now(),
            'approved_by' => auth::id(), // ✅ SAVED HERE
        ]);
dd($businessRequest->fresh());

        $businessRequest->approvals()->create([
            'approved_by' => auth::id(),
            'approval_status' => 'APPROVED',
            'approved_at' => now(),
        ]);

        $businessRequest->user->notify(
            new RequestStatusChanged($businessRequest)
        );

        return redirect()->route('business-requests.index');
    }

    // ================= REJECT =================
  public function reject(Request $req, BusinessRequest $businessRequest)
{
    $req->validate([
        'reason' => 'required|string|max:500'
    ]);

    $businessRequest->update([
        'status' => 'REJECTED',
        'reject_reason' => $req->reason, // ✅ correct column
        'approved_by' => Auth::id(),
        'approved_at' => now(),
    ]);

    return redirect()
        ->route('business-requests.index')
        ->with('success', 'Rejected successfully');
}

    // ================= ASSIGN =================
    public function assign(Request $request, BusinessRequest $businessRequest)
    {
        if ($request->action === 'approve') {

            $request->validate([
                'worker_id' => 'required|exists:users,id'
            ]);

            $businessRequest->update([
                'status' => 'APPROVED',
                'worker_id' => $request->worker_id,
                'approved_by' => auth::id(), // ✅ IMPORTANT FIX
            ]);

            return redirect()
                ->route('business-requests.index')
                ->with('success', '依頼を承認しました。');
        }

        if ($request->action === 'reject') {

            $request->validate([
                'reason' => 'required|string|max:500'
            ]);

            $businessRequest->update([
                'status' => 'REJECTED',
                'reject_reason' => $request->reason,
                'approved_by' => auth::id(), // ✅ IMPORTANT FIX
            ]);

            return redirect()
                ->route('business-requests.index')
                ->with('error', '依頼を却下しました。');
        }
    }
}


