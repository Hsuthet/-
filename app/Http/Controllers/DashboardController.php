<?php

namespace App\Http\Controllers;

use App\Models\BusinessRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. 管理者・マネージャー向けデータ（全システム統計）
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
            
            $recentRequests = BusinessRequest::with('user')->latest()->take(5)->get();
        }

        // 2. グラフデータ（過去7日間の自分の完了数）
        $dailyStats = collect(range(0, 6))->map(function($days) use ($user) {
            $date = now()->subDays($days);
            return [
                'day' => $date->format('m/d'),
                'count' => BusinessRequest::where('worker_id', $user->id) // ここが assigned_to でないか確認
                    ->where('status', 'COMPLETED')
                    ->whereDate('updated_at', $date)
                    ->count()
            ];
        })->reverse();

        $chartLabels = $dailyStats->pluck('day');
        $chartData = $dailyStats->pluck('count');

        // 3. パーソナル統計（従業員ロール用）
        // 自分が「作成した」ものか、「担当している（worker_id）」ものかを明確に分ける必要があります
        $stats = [
            // 自分が作成した依頼のステータス
            'my_pending_approvals' => BusinessRequest::where('user_id', $user->id)->where('status', 'PENDING')->count(),
            'my_completed'         => BusinessRequest::where('user_id', $user->id)->where('status', 'COMPLETED')->count(),
            
            // 自分に割り当てられた作業
            'assigned_working'     => BusinessRequest::where('worker_id', $user->id)->where('status', 'WORKING')->count(),
            'assigned_approved'    => BusinessRequest::where('worker_id', $user->id)->where('status', 'APPROVED')->count(),
        ];

        // 4. 最近のタスク（自分に関連するもの全て）
        $recentTasks = BusinessRequest::where(function($query) use ($user) {
                $query->where('user_id', $user->id)    // 自分が作成した
                      ->orWhere('worker_id', $user->id); // または自分が担当
            })
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', get_defined_vars());
    }
}