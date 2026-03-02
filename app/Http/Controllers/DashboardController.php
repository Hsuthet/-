<?php

namespace App\Http\Controllers;

use App\Models\BusinessRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // Fetch counts for the summary cards
        $stats = [
            'pending'     => BusinessRequest::where('status', 'pending_approval')->count(),
            'in_progress' => BusinessRequest::where('status', 'in_progress')->count(),
            'draft'       => BusinessRequest::where('status', 'draft')->count(),
        ];

        // Fetch the most recent requests to show in the table
        $recentRequests = BusinessRequest::latest()->take(10)->get();

        return view('dashboard', compact('stats', 'recentRequests'));
    }
}