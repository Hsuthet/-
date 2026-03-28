<x-app-layout>
    @section('header_title', 'ダッシュボード')

    @php
        $user = auth()->user();
    @endphp

    <div class="space-y-10 pb-10">

        {{-- ================= ADMIN DASHBOARD ================= --}}
        @if($user->role === 'admin' || $user->role === 'manager')

           {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">
            {{ $user->role === 'admin' ? '管理者ダッシュボード' : 'マネージャーダッシュボード' }}
        </h1>
        <p class="text-slate-500 text-sm mt-1 font-medium italic">
            {{ $user->role === 'admin' ? 'システム全体の状況を確認できます。' : 'チーム全体の業務進捗を確認できます。' }}
        </p>
    </div>

            {{-- Admin Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @php
                    $requestStats = [
                        ['label' => '総依頼数', 'value' => $adminStats['total_requests'], 'icon' => 'layers', 'color' => 'slate'],
                        ['label' => '承認待ち', 'value' => $adminStats['pending'], 'icon' => 'clock', 'color' => 'amber'],
                        ['label' => '承認済み', 'value' => $adminStats['approved'], 'icon' => 'check-circle', 'color' => 'blue'],
                        ['label' => '作業中', 'value' => $adminStats['working'], 'icon' => 'play-circle', 'color' => 'indigo'],
                        ['label' => '完了', 'value' => $adminStats['completed'], 'icon' => 'check-check', 'color' => 'emerald'],
                        ['label' => '却下', 'value' => $adminStats['rejected'], 'icon' => 'x-octagon', 'color' => 'rose'],
                    ];
                @endphp

                @foreach($requestStats as $stat)
                    <div class="group bg-white p-5 rounded-3xl border border-{{ $stat['color'] }}-100 shadow-sm hover:shadow-xl hover:shadow-{{ $stat['color'] }}-500/10 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute -right-2 -top-2 opacity-5 group-hover:opacity-10 transition-opacity">
                            <i data-lucide="{{ $stat['icon'] }}" class="w-20 h-20 text-{{ $stat['color'] }}-600"></i>
                        </div>
                        <div class="relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-{{ $stat['color'] }}-50 flex items-center justify-center mb-4">
                                <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5 text-{{ $stat['color'] }}-600"></i>
                            </div>
                            <p class="text-[11px] text-{{ $stat['color'] }}-600/80 font-black uppercase tracking-widest">{{ $stat['label'] }}</p>
                            <h3 class="text-3xl font-black text-slate-800 mt-1 tabular-nums">{{ $stat['value'] }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- User Roles Grid --}}
            @if($user->role === 'admin')
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-10">
                @php
                    $userRoles = [
                        ['label' => '全ユーザー', 'value' => $adminStats['users'], 'icon' => 'users', 'color' => 'indigo', 'desc' => 'Total registered'],
                        ['label' => '管理者', 'value' => $adminStats['admins'], 'icon' => 'shield-check', 'color' => 'rose', 'desc' => 'Full access'],
                        ['label' => '従業員', 'value' => $adminStats['employees'], 'icon' => 'user-plus', 'color' => 'emerald', 'desc' => 'Standard users'],
                        ['label' => 'マネージャー', 'value' => $adminStats['managers'], 'icon' => 'briefcase', 'color' => 'purple', 'desc' => 'Approval role'],
                    ];
                @endphp

                @foreach($userRoles as $role)
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5 hover:border-{{ $role['color'] }}-200 transition-colors group">
                        <div class="w-14 h-14 rounded-2xl bg-{{ $role['color'] }}-50 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <i data-lucide="{{ $role['color'] == 'rose' ? 'shield-alert' : $role['icon'] }}" class="w-7 h-7 text-{{ $role['color'] }}-600"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $role['label'] }}</p>
                            <div class="flex items-baseline gap-2">
                                <h3 class="text-2xl font-black text-slate-800">{{ $role['value'] }}</h3>
                                <span class="text-[10px] text-slate-400 font-medium lowercase italic">{{ $role['desc'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

        {{-- ================= EMPLOYEE / MANAGER DASHBOARD ================= --}}
        @else

            {{-- Employee Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight">社員ーダッシュボード</h1>
                    <p class="text-slate-500 text-sm mt-1 font-medium italic">
                        <i data-lucide="sparkles" class="w-4 h-4 inline-block mr-1 text-amber-400"></i>
                        本日の業務状況とリクエストの進捗を確認しましょう。
                    </p>
                </div>

               
            </div>

            {{-- Personal Stats Grid (Styled like Admin Stats) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php

                
                    $personalStats = [
                        ['label' => '作業中', 'value' => $stats['assigned_working'], 'icon' => 'play-circle', 'color' => 'indigo'],
                        ['label' => '承認済み', 'value' => $stats['assigned_approved'], 'icon' => 'check-circle', 'color' => 'blue'],
                        ['label' => '承認待ち', 'value' => $stats['my_pending_approvals'], 'icon' => 'clock', 'color' => 'amber'],
                        ['label' => '完了済み', 'value' => $stats['my_completed'], 'icon' => 'check-check', 'color' => 'emerald'],
                    ];
                @endphp

                @foreach($personalStats as $stat)
                    <div class="group bg-white p-6 rounded-3xl border border-{{ $stat['color'] }}-100 shadow-sm hover:shadow-xl hover:shadow-{{ $stat['color'] }}-500/10 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                        {{-- Background Pattern --}}
                        <div class="absolute -right-2 -top-2 opacity-5 group-hover:opacity-10 transition-opacity">
                            <i data-lucide="{{ $stat['icon'] }}" class="w-20 h-20 text-{{ $stat['color'] }}-600"></i>
                        </div>

                        <div class="relative z-10">
                            <div class="w-12 h-12 rounded-2xl bg-{{ $stat['color'] }}-50 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i data-lucide="{{ $stat['icon'] }}" class="w-6 h-6 text-{{ $stat['color'] }}-600"></i>
                            </div>
                            <p class="text-[11px] text-{{ $stat['color'] }}-600/80 font-black uppercase tracking-widest">{{ $stat['label'] }}</p>
                            <h3 class="text-3xl font-black text-slate-800 mt-1 tabular-nums">{{ $stat['value'] }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>

        @endif

        {{-- ================= SHARED ACTIVITY FEED (Used by Both) ================= --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mt-8">
            <div class="px-6 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-5 bg-indigo-500 rounded-full"></div>
                    <h2 class="font-black text-slate-800 tracking-tight text-sm">最新のアクティビティ </h2>
                </div>
                <a href="{{ $user->role === 'admin' ? route('business-requests.requests') : route('business-requests.my_tasks') }}" 
                   class="text-[10px] font-bold text-indigo-600 hover:text-indigo-700 uppercase tracking-widest">全て見る</a>
            </div>

            <div class="divide-y divide-slate-50">
                @php
                   $tasksSource = ($user->role === 'admin' || $user->role === 'manager') 
                           ? $recentRequests 
                           : $recentTasks;
                @endphp

                @forelse($tasksSource as $task)
                    @php
                        $statusConfig = [
                            'PENDING'   => ['color' => 'amber',   'icon' => 'clock',        'label' => '承認待ち'],
                            'APPROVED'  => ['color' => 'blue',    'icon' => 'check-circle',  'label' => '承認済み'],
                            'WORKING'   => ['color' => 'indigo',  'icon' => 'play-circle',   'label' => '作業中'],
                            'COMPLETED' => ['color' => 'emerald', 'icon' => 'check-check',   'label' => '完了'],
                            'REJECTED'  => ['color' => 'rose',    'icon' => 'x-circle',      'label' => '却下'],
                        ];
                        $config = $statusConfig[$task->status] ?? ['color' => 'slate', 'icon' => 'help-circle', 'label' => $task->status];
                    @endphp

                    <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50/80 transition-colors group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-{{ $config['color'] }}-50 flex items-center justify-center shrink-0 border border-{{ $config['color'] }}-100/50 group-hover:scale-110 transition-transform">
                                <i data-lucide="{{ $config['icon'] }}" class="w-5 h-5 text-{{ $config['color'] }}-600"></i>
                            </div>

                            <div>
                                <p class="text-sm font-bold text-slate-800 line-clamp-1 group-hover:text-indigo-600 transition-colors">
                                    {{ $task->title }}
                                </p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-bold text-slate-400 flex items-center">
                                        <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
                                        {{ $task->created_at->format('Y/m/d') }}
                                    </span>
                                    <span class="text-slate-200 text-[10px]">•</span>
                                    <span class="text-[10px] text-slate-400 font-medium">Updated {{ $task->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border
                                bg-{{ $config['color'] }}-50 text-{{ $config['color'] }}-700 border-{{ $config['color'] }}-100 shadow-sm">
                                {{ $config['label'] }}
                            </span>
                            <a href="{{ route('business-requests.show', $task->id) }}" class="p-2 text-slate-300 hover:text-indigo-600 transition-colors">
                                <i data-lucide="chevron-right" class="w-5 h-5"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center">
                        <i data-lucide="inbox" class="w-12 h-12 text-slate-200 mx-auto mb-3"></i>
                        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">データがありません</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>