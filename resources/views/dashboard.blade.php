<x-app-layout>
    @section('header_title', 'ダッシュボード')

    <div class="space-y-8">
        {{-- Welcome Section --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">お疲れ様です、{{ auth()->user()->name }}さん</h1>
                <p class="text-slate-500 text-sm mt-1">本日の業務状況とリクエストの進捗を確認しましょう。</p>
            </div>
            <a href="{{ route('business-requests.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition flex items-center font-bold text-sm">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> 新規作成
            </a>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Card 1: Working (Tasks for the user) --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 text-blue-600">
                    <i data-lucide="play-circle" class="w-12 h-12"></i>
                </div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">進行中の作業</p>
                <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $stats['assigned_working'] }}</h3>
                <p class="text-blue-600 text-[10px] mt-2 font-bold">現在対応中のタスク</p>
            </div>

            {{-- Card 2: Approved / Waiting Start --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 text-amber-600">
                    <i data-lucide="clock" class="w-12 h-12"></i>
                </div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">未着手の作業</p>
                <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $stats['assigned_approved'] }}</h3>
                <p class="text-amber-600 text-[10px] mt-2 font-bold">開始待ちの承認済みタスク</p>
            </div>

            {{-- Card 3: My Pending Requests --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 text-indigo-600">
                    <i data-lucide="send" class="w-12 h-12"></i>
                </div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">自分の申請（承認待ち）</p>
                <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $stats['my_pending_approvals'] }}</h3>
                <p class="text-indigo-600 text-[10px] mt-2 font-bold">マネージャーの確認待ち</p>
            </div>

            {{-- Card 4: Completed Total --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 text-emerald-600">
                    <i data-lucide="check-check" class="w-12 h-12"></i>
                </div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">完了した依頼</p>
                <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $stats['my_completed'] }}</h3>
                <p class="text-emerald-600 text-[10px] mt-2 font-bold">これまでに完了した全件数</p>
            </div>
        </div>

        {{-- Weekly Chart Section --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h2 class="font-bold text-slate-800 flex items-center mb-4">
                <i data-lucide="bar-chart-3" class="w-5 h-5 mr-2 text-indigo-500"></i> 直近7日間の完了数 (Weekly Completion)
            </h2>
            <div class="h-[250px] w-full relative"> {{-- Added relative positioning for chart.js --}}
                <canvas id="completionChart"></canvas>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Side: Recent Tasks List --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h2 class="font-bold text-slate-800 flex items-center">
                        <i data-lucide="list" class="w-5 h-5 mr-2 text-indigo-500"></i> 最近の担当作業
                    </h2>
                    {{-- FIXED LINK BELOW --}}
                    <a href="{{ route('business-requests.my_tasks') }}" class="text-xs text-indigo-600 font-bold hover:underline">すべて表示</a>
                </div>
                
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm divide-y divide-slate-50">
                    @forelse($recentTasks as $task)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition first:rounded-t-2xl last:rounded-b-2xl">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                                    <span class="text-[10px] font-bold">#{{ substr($task->request_number, -3) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $task->title }}</p>
                                    <p class="text-[10px] text-slate-400">期限: {{ $task->due_date }}</p>
                                </div>
                            </div>
                            <div>
                                @if($task->status === 'WORKING')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-[9px] font-bold uppercase">作業中</span>
                                @elseif($task->status === 'APPROVED')
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-lg text-[9px] font-bold uppercase">開始待ち</span>
                                @else
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-[9px] font-bold uppercase">完了</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-sm">現在、表示できる作業はありません。</div>
                    @endforelse
                </div>
            </div>

            {{-- Right Side: Quick Links --}}
            <div class="space-y-4">
                 <h2 class="font-bold text-slate-800 flex items-center px-2">
                    <i data-lucide="zap" class="w-5 h-5 mr-2 text-amber-500"></i> クイックアクセス
                </h2>
                <div class="bg-indigo-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden group">
                    <div class="relative z-10">
                        <h4 class="font-bold text-sm">困ったときは？</h4>
                        <p class="text-indigo-200 text-[11px] mt-2 leading-relaxed">システムの操作方法や不明点がある場合は、IT部門までお問い合わせください。</p>
                        <button class="mt-4 bg-white/10 hover:bg-white/20 border border-white/20 w-full py-2 rounded-xl text-xs font-bold transition">マニュアルを見る</button>
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const el = document.getElementById('completionChart');
            if (el) {
                const ctx = el.getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: '完了したタスク',
                            data: {!! json_encode($chartData) !!},
                            backgroundColor: 'rgba(79, 70, 229, 0.5)', 
                            borderColor: 'rgb(79, 70, 229)',
                            borderWidth: 2,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { 
                                beginAtZero: true,
                                ticks: { stepSize: 1 } // Show whole numbers only
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>