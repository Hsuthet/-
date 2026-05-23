<?php

namespace App\Http\Controllers;

use App\Models\BusinessRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ================= STATUS COUNTS (GLOBAL FOR PIE CHART) =================
        $statusTypes = ['PENDING', 'APPROVED', 'WORKING', 'COMPLETED', 'REJECTED'];

        $statusCounts = collect($statusTypes)->mapWithKeys(function ($status) {
            return [
                $status => BusinessRequest::where('status', $status)->count(),
            ];
        });

        $chartLabels = $statusCounts->keys();
        $chartData = $statusCounts->values();

        /*
        |--------------------------------------------------------------------------
        | Admin / Manager Global Stats
        |--------------------------------------------------------------------------
        */
        $adminStats = [];

        if (
            $user->role === 'admin' ||
            $user->role === 'manager' ||
            $user->role === 'employee'
        ) {

            $adminStats = [

                // Request Stats
                'total_requests' => BusinessRequest::count(),

                'pending' => BusinessRequest::where('status', 'PENDING')->count(),

                'approved' => BusinessRequest::where('status', 'APPROVED')->count(),

                'working' => BusinessRequest::where('status', 'WORKING')->count(),

                'completed' => BusinessRequest::where('status', 'COMPLETED')->count(),

                'rejected' => BusinessRequest::where('status', 'REJECTED')->count(),

                // User Stats
                'users' => User::count(),

                'admins' => User::where('role', 'admin')->count(),

                'employees' => User::where('role', 'employee')->count(),

                'managers' => User::where('role', 'manager')->count(),
            ];
        }


    $managerStats = [
        'pending_approvals' => BusinessRequest::where('target_department_id', $user->department_id)
            ->where('status', 'PENDING')
            ->count(),

        'approved_requests' => BusinessRequest::where('target_department_id', $user->department_id)
            ->where('status', 'APPROVED')
            ->count(),

        'rejected_requests' => BusinessRequest::where('target_department_id', $user->department_id)
            ->where('status', 'REJECTED')
            ->count(),

        'completed_requests' => BusinessRequest::where('target_department_id', $user->department_id)
            ->where('status', 'COMPLETED')
            ->count(),
    ];



        /*
        |--------------------------------------------------------------------------
        | Personal Stats (Used by Admin / Manager / Employee)
        |--------------------------------------------------------------------------
        */
        $stats = [

            /*
            |--------------------------------------------------------------------------
            | My Requests
            |--------------------------------------------------------------------------
            */
            'my_requests_pending' => BusinessRequest::where('user_id', $user->id)
                ->where('status', 'PENDING')
                ->count(),

            'my_requests_approved' => BusinessRequest::where('user_id', $user->id)
                ->where('status', 'APPROVED')
                ->count(),

            'my_requests_completed' => BusinessRequest::where('user_id', $user->id)
                ->where('status', 'COMPLETED')
                ->count(),

            'my_requests_rejected' => BusinessRequest::where('user_id', $user->id)
                ->where('status', 'REJECTED')
                ->count(),

            /*
            |--------------------------------------------------------------------------
            | Assigned Tasks
            |--------------------------------------------------------------------------
            */
            'assigned_approved' => BusinessRequest::where('worker_id', $user->id)
                ->where('status', 'APPROVED')
                ->count(),

            'assigned_working' => BusinessRequest::where('worker_id', $user->id)
                ->where('status', 'WORKING')
                ->count(),

            'assigned_completed' => BusinessRequest::where('worker_id', $user->id)
                ->where('status', 'COMPLETED')
                ->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | My Requests List
        |--------------------------------------------------------------------------
        */
        $myRequests = BusinessRequest::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Assigned Tasks List
        |--------------------------------------------------------------------------
        */
        $assignedTasks = BusinessRequest::where('worker_id', $user->id)
            ->whereIn('status', [
                'APPROVED',
                'WORKING',
                'COMPLETED',
            ])
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Activities
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'admin' || $user->role === 'manager') {

            $recentTasks = BusinessRequest::with(['user', 'worker'])
                ->latest()
                ->take(5)
                ->get();

        } else {

            $recentTasks = BusinessRequest::where(function ($query) use ($user) {

                $query->where('user_id', $user->id)
                    ->orWhere('worker_id', $user->id);

            })
                ->latest()
                ->take(5)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Daily Chart Data
        |--------------------------------------------------------------------------
        */
        $dailyStats = collect(range(0, 6))->map(function ($days) use ($user) {

            $date = now()->subDays($days);

            return [

                'day' => $date->format('m/d'),

                'count' => BusinessRequest::where('worker_id', $user->id)
                    ->where('status', 'COMPLETED')
                    ->whereDate('updated_at', $date)
                    ->count(),
            ];

        })->reverse();

        $chartLabels = collect(range(6, 0))->map(function ($i) {
            return now()->subDays($i)->format('m/d');
        });

        if ($user->role === 'admin') {

            $chartData = collect(range(6, 0))->map(function ($i) {
                return BusinessRequest::whereDate('created_at', now()->subDays($i))->count();
            });

        } elseif ($user->role === 'manager') {

            $chartData = collect(range(6, 0))->map(function ($i) use ($user) {
                return BusinessRequest::where('worker_id', $user->id)
                    ->where('status', 'COMPLETED')
                    ->whereDate('updated_at', now()->subDays($i))
                    ->count();
            });

        } else {

            $chartData = collect(range(6, 0))->map(function ($i) use ($user) {
                return BusinessRequest::where('user_id', $user->id)
                    ->where('status', 'COMPLETED')
                    ->whereDate('updated_at', now()->subDays($i))
                    ->count();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */
        return view('dashboard', compact(
            'adminStats',
            'managerStats',
            'stats',
            'myRequests',
            'assignedTasks',
            'recentTasks',
            'chartLabels',
            'chartData'
        ));
    }
}
 