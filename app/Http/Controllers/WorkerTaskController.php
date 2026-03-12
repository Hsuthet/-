<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessRequest;

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
}