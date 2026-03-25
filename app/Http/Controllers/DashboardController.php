<?php

namespace App\Http\Controllers;

use App\Models\BusinessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
    $user = Auth::user();
    
    // 1. DATA FOR ADMINS & MANAGERS (Power Users)
    // Both roles now get access to the global $adminStats and $recentRequests
    if ($user->role === 'admin' || $user->role === 'manager') {
        $adminStats = [
            'total_requests' => BusinessRequest::count(),
            'pending'        => BusinessRequest::where('status', 'PENDING')->count(),
            'approved'       => BusinessRequest::where('status', 'APPROVED')->count(),
            'working'        => BusinessRequest::where('status', 'WORKING')->count(),
            'completed'      => BusinessRequest::where('status', 'COMPLETED')->count(),
            'rejected'       => BusinessRequest::where('status', 'REJECTED')->count(),
            'users'          => User::count(),
            'admins'         => User::where('role', 'admin')->count(),
            'employees'      => User::where('role', 'employee')->count(),
            'managers'       => User::where('role', 'manager')->count(),
        ];
        
        // Show all recent requests from everyone
        $recentRequests = BusinessRequest::with('user')->latest()->take(5)->get();
    }

    // 2. DATA FOR EVERYONE (Chart & Personal Stats)
    // Get counts for the last 7 days (personal performance)
    $dailyStats = collect(range(0, 6))->map(function($days) use ($user) {
        $date = now()->subDays($days);
        return [
            'day' => $date->format('m/d'),
            'count' => BusinessRequest::where('worker_id', $user->id)
                ->where('status', 'COMPLETED')
                ->whereDate('updated_at', $date)
                ->count()
        ];
    })->reverse();

    $chartLabels = $dailyStats->pluck('day');
    $chartData = $dailyStats->pluck('count');

    // Personal statistics (used by Employee role or as personal overview for Managers)
    $stats = [
        'my_pending_approvals' => BusinessRequest::where('user_id', $user->id)->where('status', 'PENDING')->count(),
        'assigned_working'     => BusinessRequest::where('worker_id', $user->id)->where('status', 'WORKING')->count(),
        'assigned_approved'    => BusinessRequest::where('worker_id', $user->id)->where('status', 'APPROVED')->count(),
        'my_completed'         => BusinessRequest::where('user_id', $user->id)->where('status', 'COMPLETED')->count(),
    ];

    // Personal recent tasks
    $recentTasks = BusinessRequest::where('worker_id', $user->id)
        ->latest()
        ->take(5)
        ->get();

    // 3. SINGLE RETURN STATEMENT
    // We use get_defined_vars() to pass everything that was created above
    return view('dashboard', get_defined_vars());
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