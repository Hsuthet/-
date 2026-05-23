<x-app-layout>
    @section('header_title', 'ダッシュボード')

    @php
    $user = auth()->user();

    $statusConfig = [
    'PENDING' => [
    'color' => 'amber',
    'icon' => 'clock',
    'label' => '承認待ち'
    ],
    'APPROVED' => [
    'color' => 'blue',
    'icon' => 'check-circle',
    'label' => '承認済み'
    ],
    'WORKING' => [
    'color' => 'indigo',
    'icon' => 'play-circle',
    'label' => '作業中'
    ],
    'COMPLETED' => [
    'color' => 'emerald',
    'icon' => 'check-check',
    'label' => '完了'
    ],
    'REJECTED' => [
    'color' => 'rose',
    'icon' => 'x-circle',
    'label' => '却下'
    ],
    ];
    @endphp

    <div class="min-h-screen rounded-[2rem] bg-gradient-to-br from-slate-50 via-white to-indigo-50/40 p-4 md:p-6 space-y-8">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}
        <div class="flex items-center justify-between">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-3xl bg-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <i data-lucide="layout-dashboard" class="w-7 h-7 text-white"></i>
                </div>

                <div>

                    <h1 class="text-3xl font-black tracking-tight text-slate-800">
                        @if($user->role === 'admin')
                        管理者ダッシュボード
                        @elseif($user->role === 'manager')
                        マネージャーダッシュボード
                        @else
                        社員ダッシュボード
                        @endif
                    </h1>

                    <p class="text-sm text-slate-500 mt-1">
                        業務依頼の状況と最新アクティビティを確認できます。
                    </p>

                </div>

            </div>

        </div>

        {{-- ===================================================== --}}
        {{-- ADMIN / MANAGER OVERVIEW --}}
        {{-- ===================================================== --}}
        @if($user->role === 'admin' || $user->role === 'manager')

        <div>

            <div class="mb-5">
                <h2 class="text-xl font-black text-slate-800">
                    全体依頼状況
                </h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-5">

                @php
                $requestStats = [
                [
                'label' => '総依頼数',
                'value' => $adminStats['total_requests'],
                'icon' => 'layers',
                'color' => 'slate'
                ],
                [
                'label' => '承認待ち',
                'value' => $adminStats['pending'],
                'icon' => 'clock',
                'color' => 'amber'
                ],
                [
                'label' => '承認済み',
                'value' => $adminStats['approved'],
                'icon' => 'check-circle',
                'color' => 'blue'
                ],
                [
                'label' => '作業中',
                'value' => $adminStats['working'],
                'icon' => 'play-circle',
                'color' => 'indigo'
                ],
                [
                'label' => '完了',
                'value' => $adminStats['completed'],
                'icon' => 'check-check',
                'color' => 'emerald'
                ],
                [
                'label' => '却下',
                'value' => $adminStats['rejected'],
                'icon' => 'x-circle',
                'color' => 'rose'
                ],
                ];
                @endphp

                @foreach($requestStats as $stat)

                <div class="group relative overflow-hidden rounded-3xl border border-slate-100 bg-white/90 backdrop-blur p-5 shadow-sm hover:-translate-y-1 hover:shadow-xl transition-all duration-300">

                    <div class="absolute top-0 right-0 w-24 h-24 rounded-full bg-{{ $stat['color'] }}-100 blur-3xl opacity-30"></div>

                    <div class="relative z-10">

                        <div class="flex items-center justify-between mb-5">

                            <div class="w-12 h-12 rounded-2xl bg-{{ $stat['color'] }}-50 flex items-center justify-center">

                                <i data-lucide="{{ $stat['icon'] }}" class="w-6 h-6 text-{{ $stat['color'] }}-600">
                                </i>

                            </div>

                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-[10px] font-black tracking-wider
                                        bg-{{ $stat['color'] }}-50
                                        text-{{ $stat['color'] }}-700
                                        border border-{{ $stat['color'] }}-100">

                                {{ $stat['label'] }}

                            </span>

                        </div>

                        <h3 class="text-3xl font-black text-slate-800 tabular-nums">
                            {{ $stat['value'] }}
                        </h3>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

        @endif

        {{-- ===================================================== --}}
        {{-- ADMIN USER STATS --}}
        {{-- ===================================================== --}}
        @if($user->role === 'admin')

        <div>

            <div class="mb-5">
                <h2 class="text-xl font-black text-slate-800">
                    ユーザー統計
                </h2>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">

                @php
                $userStats = [
                [
                'label' => '全ユーザー',
                'value' => $adminStats['users'],
                'icon' => 'users',
                'color' => 'slate'
                ],
                [
                'label' => '管理者',
                'value' => $adminStats['admins'],
                'icon' => 'shield-check',
                'color' => 'rose'
                ],
                [
                'label' => '従業員',
                'value' => $adminStats['employees'],
                'icon' => 'user',
                'color' => 'emerald'
                ],
                [
                'label' => 'マネージャー',
                'value' => $adminStats['managers'],
                'icon' => 'briefcase',
                'color' => 'indigo'
                ],
                ];
                @endphp

                @foreach($userStats as $stat)

                <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm hover:shadow-lg transition-all">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-xs font-bold tracking-wider text-slate-400 uppercase">
                                {{ $stat['label'] }}
                            </p>

                            <h3 class="text-3xl font-black text-slate-800 mt-2">
                                {{ $stat['value'] }}
                            </h3>

                        </div>

                        <div class="w-14 h-14 rounded-2xl bg-{{ $stat['color'] }}-50 flex items-center justify-center">

                            <i data-lucide="{{ $stat['icon'] }}" class="w-7 h-7 text-{{ $stat['color'] }}-600">
                            </i>

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

        @endif

        {{-- ===================================================== --}}
        {{-- MANAGER APPROVAL STATUS --}}
        {{-- ===================================================== --}}
        @if($user->role === 'manager')

        <div>

            <div class="mb-5">
                <h2 class="text-xl font-black text-slate-800">
                    承認対象依頼
                </h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">

                @php
                $managerApprovalStats = [
                [
                'label' => '承認待ち',
                'value' => $managerStats['pending_approvals'] ?? 0,
                'icon' => 'clock',
                'color' => 'amber'
                ],
                [
                'label' => '承認済み',
                'value' => $managerStats['approved_requests'] ?? 0,
                'icon' => 'check-circle',
                'color' => 'blue'
                ],
                [
                'label' => '却下',
                'value' => $managerStats['rejected_requests'] ?? 0,
                'icon' => 'x-circle',
                'color' => 'rose'
                ],
                [
                'label' => '完了',
                'value' => $managerStats['completed_requests'] ?? 0,
                'icon' => 'check-check',
                'color' => 'emerald'
                ],
                ];
                @endphp

                @foreach($managerApprovalStats as $stat)

                <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm hover:shadow-lg transition-all">

                    <div class="flex items-center justify-between mb-4">

                        <div class="w-11 h-11 rounded-2xl bg-{{ $stat['color'] }}-50 flex items-center justify-center">

                            <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5 text-{{ $stat['color'] }}-600">
                            </i>

                        </div>

                        <span class="text-xs font-black text-{{ $stat['color'] }}-600">
                            {{ $stat['label'] }}
                        </span>

                    </div>

                    <h3 class="text-3xl font-black text-slate-800">
                        {{ $stat['value'] }}
                    </h3>

                </div>

                @endforeach

            </div>

        </div>

        @endif

        {{-- ===================================================== --}}
        {{-- EMPLOYEE STATS --}}
        {{-- ===================================================== --}}
        @if($user->role === 'employee')

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- MY REQUEST --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">

                <div class="flex items-center justify-between mb-6">

                    <div>
                        <h2 class="text-xl font-black text-slate-800">
                            自分の依頼
                        </h2>
                    </div>

                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <i data-lucide="clipboard-list" class="w-6 h-6 text-blue-600"></i>
                    </div>

                </div>

                <div class="grid grid-cols-2 gap-4">

                    @php
                    $myRequestStats = [
                    [
                    'label' => '承認待ち',
                    'value' => $stats['my_requests_pending'],
                    'icon' => 'clock',
                    'color' => 'amber'
                    ],
                    [
                    'label' => '承認済み',
                    'value' => $stats['my_requests_approved'],
                    'icon' => 'check-circle',
                    'color' => 'blue'
                    ],
                    [
                    'label' => '完了',
                    'value' => $stats['my_requests_completed'],
                    'icon' => 'check-check',
                    'color' => 'emerald'
                    ],
                    [
                    'label' => '却下',
                    'value' => $stats['my_requests_rejected'],
                    'icon' => 'x-circle',
                    'color' => 'rose'
                    ],
                    ];
                    @endphp

                    @foreach($myRequestStats as $stat)

                    <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-xs font-bold text-slate-400">
                                    {{ $stat['label'] }}
                                </p>

                                <h3 class="text-2xl font-black text-slate-800 mt-2">
                                    {{ $stat['value'] }}
                                </h3>

                            </div>

                            <div class="w-11 h-11 rounded-xl bg-{{ $stat['color'] }}-50 flex items-center justify-center">

                                <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5 text-{{ $stat['color'] }}-600">
                                </i>

                            </div>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

            {{-- ASSIGNED TASK --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">

                <div class="flex items-center justify-between mb-6">

                    <div>
                        <h2 class="text-xl font-black text-slate-800">
                            担当タスク
                        </h2>
                    </div>

                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center">
                        <i data-lucide="briefcase" class="w-6 h-6 text-indigo-600"></i>
                    </div>

                </div>

                <div class="grid grid-cols-3 gap-4">

                    @php
                    $assignedStats = [
                    [
                    'label' => '承認済み',
                    'value' => $stats['assigned_approved'],
                    'icon' => 'check-circle',
                    'color' => 'blue'
                    ],
                    [
                    'label' => '作業中',
                    'value' => $stats['assigned_working'],
                    'icon' => 'play-circle',
                    'color' => 'indigo'
                    ],
                    [
                    'label' => '完了',
                    'value' => $stats['assigned_completed'],
                    'icon' => 'check-check',
                    'color' => 'emerald'
                    ],
                    ];
                    @endphp

                    @foreach($assignedStats as $stat)

                    <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4 text-center">

                        <div class="w-11 h-11 mx-auto rounded-xl bg-{{ $stat['color'] }}-50 flex items-center justify-center mb-3">

                            <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5 text-{{ $stat['color'] }}-600">
                            </i>

                        </div>

                        <p class="text-xs font-bold text-slate-400">
                            {{ $stat['label'] }}
                        </p>

                        <h3 class="text-2xl font-black text-slate-800 mt-2">
                            {{ $stat['value'] }}
                        </h3>

                    </div>

                    @endforeach

                </div>

            </div>

        </div>

        @endif

        {{-- ===================================================== --}}
        {{-- RECENT ACTIVITIES --}}
        {{-- ===================================================== --}}
        <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">

            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/60">

                <div class="flex items-center gap-3">

                    <div class="w-2 h-6 rounded-full bg-indigo-500"></div>

                    <div>
                        <h2 class="text-xl font-black text-slate-800">
                            最新アクティビティ
                        </h2>
                    </div>

                </div>

            </div>

            <div class="divide-y divide-slate-50">

                @forelse($recentTasks as $task)

                @php
                $config = $statusConfig[$task->status] ?? [
                'color' => 'slate',
                'icon' => 'help-circle',
                'label' => $task->status
                ];
                @endphp

                <div class="flex items-center justify-between px-6 py-5 hover:bg-slate-50 transition-all">

                    <div class="flex items-center gap-4">

                        <div class="w-12 h-12 rounded-2xl bg-{{ $config['color'] }}-50 flex items-center justify-center">

                            <i data-lucide="{{ $config['icon'] }}" class="w-5 h-5 text-{{ $config['color'] }}-600">
                            </i>

                        </div>

                        <div>

                            <p class="font-bold text-slate-800">
                                {{ $task->title }}
                            </p>

                        </div>

                    </div>

                    <div class="flex items-center gap-3">

                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-black border
                                bg-{{ $config['color'] }}-50
                                text-{{ $config['color'] }}-700
                                border-{{ $config['color'] }}-100">

                            {{ $config['label'] }}

                        </span>

                        <a href="{{ route('business-requests.show', $task->id) }}" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-indigo-100 flex items-center justify-center transition-all">

                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-500">
                            </i>

                        </a>

                    </div>

                </div>

                @empty

                <div class="py-20 text-center">

                    <i data-lucide="inbox" class="w-14 h-14 text-slate-200 mx-auto mb-4">
                    </i>

                    <p class="text-sm font-bold text-slate-400">
                        データがありません
                    </p>

                </div>

                @endforelse

            </div>

        </div>

    </div>

</x-app-layout>
 