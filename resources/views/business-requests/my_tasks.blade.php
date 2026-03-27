<x-app-layout>
    @php
        $user = auth()->user();
        $isManager = $user->role === 'manager' || $user->role === 'admin';
        
        // Define headers - add "Worker" column if Manager
        $headers = ['依頼番号', '件名・内容', '依頼者'];
        if ($isManager) { $headers[] = '担当者'; }
        $headers = array_merge($headers, ['期限', 'ステータス', '操作']);

        $statusMap = [
            'PENDING'   => ['label' => '承認待ち', 'class' => 'bg-amber-50 text-amber-700 border-amber-100'],
            'APPROVED'  => ['label' => '承認済み', 'class' => 'bg-blue-50 text-blue-700 border-blue-100'],
            'WORKING'   => ['label' => '作業中',   'class' => 'bg-indigo-50 text-indigo-700 border-indigo-100'],
            'COMPLETED' => ['label' => '完了',     'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100'],
            'REJECTED'  => ['label' => '却下',     'class' => 'bg-rose-50 text-rose-700 border-rose-100'],
        ];
    @endphp

    @section('header_title', $isManager ? '全担当作業管理' : '担当作業')

    <div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-600 p-2.5 rounded-xl shadow-lg shadow-indigo-200">
                    <i data-lucide="briefcase" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                        {{ $isManager ? '全従業員の担当作業一覧' : '割り当てられた作業' }}
                    </h1>
                    <p class="text-sm text-slate-500">
                        {{ $isManager ? 'チーム全体のタスク進捗状況をリアルタイムで監視しています。' : '担当者として対応が必要な依頼の一覧です。' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Filter Control Bar --}}
        <div class="bg-slate-50 p-2 rounded-2xl border border-slate-200 flex flex-wrap items-center justify-between gap-4">
            <div class="px-4">
                <span class="text-sm font-bold text-slate-600">タスクフィルター</span>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">ステータス:</span>
                <x-table-status-filter 
                    tableId="tasksTable" 
                    columnIndex="{{ $isManager ? 5 : 4 }}"
                    :options="[
                        '承認済み' => '承認済み ',
                        '作業中'   => '作業中',
                        '完了'     => '完了',
                    ]" 
                />
            </div>
        </div>

        {{-- Table Section --}}
 <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden border-t-4 border-t-indigo-500">
            <x-data-table id="tasksTable" :headers="$headers" role="employee" :showFilters="false">
                @foreach($tasks as $task)
                    @php 
                        $status = $statusMap[$task->status] ?? ['label' => $task->status, 'class' => 'bg-slate-100 text-slate-700 border-slate-200']; 
                        $isOverdue = \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'COMPLETED';
                    @endphp

                    <tr class="hover:bg-slate-50/80 transition-colors border-b border-slate-100">
                        {{-- 1. Request Number --}}
                        <td class="px-4 py-5 text-center">
                            <span class="font-mono text-[11px] font-bold bg-slate-100 px-2 py-1 rounded text-slate-600 uppercase tracking-tighter">
                                {{ $task->request_number }}
                            </span>
                        </td>
                        
                        {{-- 2. Title --}}
                        <td class="px-4 py-5">
                            <div class="group">
                                <p class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $task->title }}</p>
                                @if($task->requestContent?->description)
                                    <p class="text-[11px] text-slate-400 line-clamp-1 mt-1 font-medium">
                                        {{ Str::limit($task->requestContent->description, 50) }}
                                    </p>
                                @endif
                            </div>
                        </td>
                        
                        {{-- 3. Requester --}}
                        <td class="px-4 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700">{{ $task->user?->name }}</span>
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-tight">{{ $task->user?->department?->name }}</span>
                            </div>
                        </td>

                        {{-- 4. Worker (Manager Only) --}}
                        @if($isManager)
                        <td class="px-4 py-5">
                            <div class="flex items-center gap-2">
                                
                                <span class="text-xs font-medium text-slate-600">{{ $task->worker?->name ?? '未割り当て' }}</span>
                            </div>
                        </td>
                        @endif
                        
                        {{-- 5. Due Date --}}
                        <td class="px-4 py-5 text-center">
                            <span class="text-[11px] font-bold {{ $isOverdue ? 'text-rose-600 bg-rose-50 px-2 py-1 rounded-lg border border-rose-100' : 'text-slate-600' }}">
                                {{ $task->due_date }}
                            </span>
                        </td>

                        {{-- 6. Status --}}
                        <td class="px-4 py-5 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold border shadow-sm {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        
                        {{-- 7. Actions --}}
                        <td class="px-4 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('business-requests.show', $task->id) }}" 
                                   class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </a>

                                {{-- Only show buttons if current user is the actual worker --}}
                                @if($task->worker_id === $user->id)
                                    @if($task->status === 'APPROVED')
                                        <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="WORKING">
                                            <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-blue-700 transition-all">
                                                作業開始
                                            </button>
                                        </form>
                                    @elseif($task->status === 'WORKING')
                                        <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="COMPLETED">
                                            <button type="submit" class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-emerald-700 transition-all">
                                                完了
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-data-table>

            @if($tasks->isEmpty())
                <div class="p-16 text-center">
                    <i data-lucide="inbox" class="w-10 h-10 text-slate-300 mx-auto mb-4"></i>
                    <h3 class="text-slate-800 font-bold">作業はありません</h3>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>