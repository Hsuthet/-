<?php

namespace App\Http\Controllers;

use App\Models\BusinessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // Fetch counts for the summary cards
        // $stats = [
        //     'pending'     => BusinessRequest::where('status', 'pending_approval')->count(),
        //     'in_progress' => BusinessRequest::where('status', 'in_progress')->count(),
        //     'draft'       => BusinessRequest::where('status', 'draft')->count(),
        // ];

        // Fetch the most recent requests to show in the table
        $recentRequests = BusinessRequest::latest()->take(10)->get();

        return view('dashboard', compact( 'recentRequests'));
    }

   public function index()
{
    $user = auth::user();
    

    // Get counts for the last 7 days
    $dailyStats = collect(range(0, 6))->map(function($days) use ($user) {
        $date = now()->subDays($days);
        return [
            'day' => $date->format('m/d'),
            'count' => \App\Models\BusinessRequest::where('worker_id', $user->id)
                ->where('status', 'COMPLETED')
                ->whereDate('updated_at', $date)
                ->count()
        ];
    })->reverse();

    $chartLabels = $dailyStats->pluck('day');
    $chartData = $dailyStats->pluck('count');

    // Statistics for the logged-in user
    $stats = [
        'my_pending_approvals' => \App\Models\BusinessRequest::where('user_id', $user->id)->where('status', 'PENDING')->count(),
        'assigned_working'    => \App\Models\BusinessRequest::where('worker_id', $user->id)->where('status', 'WORKING')->count(),
        'assigned_approved'   => \App\Models\BusinessRequest::where('worker_id', $user->id)->where('status', 'APPROVED')->count(),
        'my_completed'        => \App\Models\BusinessRequest::where('user_id', $user->id)->where('status', 'COMPLETED')->count(),
    ];

    // Get the 5 most recent tasks assigned to me
    $recentTasks = \App\Models\BusinessRequest::where('worker_id', $user->id)
        ->latest()
        ->take(5)
        ->get();

   return view('dashboard', compact('stats', 'recentTasks', 'chartLabels', 'chartData'));
}

    // DashboardController.php
// public function index()
// {
//     $user = auth::user();
//     $query = BusinessRequest::query();

//     // Role-based Data Filtering
//     if ($user->role === 'REQUESTER') {
//         $query->where('user_id', $user->id);
//     } elseif ($user->role === 'APPROVER') {
//         // Show requests sent to their department
//         $query->where('department_id', $user->department_id);
//     } elseif ($user->role === 'WORKER') {
//         // Show tasks assigned to them
//         $query->where('worker_id', $user->id);
//     }

//     $stats = [
//         'draft'     => (clone $query)->where('status', 'DRAFT')->count(),
//         'pending'   => (clone $query)->where('status', 'PENDING')->count(),
//         'working'   => (clone $query)->where('status', 'WORKING')->count(),
//         'completed' => (clone $query)->where('status', 'COMPLETED')->count(),
//     ];

//     $recentRequests = $query->with('user')->latest()->take(5)->get();

//     return view('dashboard', compact('stats', 'recentRequests'));
// }

// public function index()
// {
//     $user = auth::user();
    
//     // Base query
//     $query = BusinessRequest::query();

//     // Data filtering based on logic (Approver sees all for their dept, Requester sees only theirs)
//     if ($user->role === 'APPROVER') {
//         $query->where('department_id', $user->department_id);
//     } else {
//         $query->where('user_id', $user->id);
//     }

//     $stats = [
//         'pending'  => (clone $query)->where('status', 'pending')->count(),
//         'approved' => (clone $query)->where('status', 'approved')->count(),
//         'draft'    => (clone $query)->where('status', 'draft')->count(),
//     ];

//     $recentRequests = $query->with('user')->latest()->take(5)->get();

//     return view('dashboard', compact('stats', 'recentRequests'));
// }
}