<x-app-layout>
    @section('header_title', '担当作業')

    <div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-600 p-2.5 rounded-xl shadow-lg shadow-indigo-200">
                    <i data-lucide="briefcase" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">あなたに割り当てられた作業</h1>
                    <p class="text-sm text-slate-500">担当者として対応が必要な依頼の一覧です。</p>
                </div>
            </div>
        </div>

        {{-- Filter Control Bar --}}
        <div class="bg-slate-50 p-2 rounded-2xl border border-slate-200 flex flex-wrap items-center justify-between gap-4">
            {{-- Left: Task Context Title --}}
            <div class="px-4">
                <span class="text-sm font-bold text-slate-600">タスクフィルター</span>
            </div>

            {{-- Right: Status Dropdown (Using the same component) --}}
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">ステータス:</span>
                <x-table-status-filter 
    tableId="tasksTable" 
    :options="[
        '承認済み' => '承認済み (Approved)',
        '作業中'   => '作業中 (Working)',
        '完了'     => '完了 (Completed)',
    ]" 
/>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            @php
                $headers = ['依頼番号', '件名・内容', '依頼者', '期限', 'ステータス', '操作'];
                $statusMap = [
                    'APPROVED'  => ['label' => '承認済み', 'class' => 'bg-amber-50 text-amber-700 border-amber-100'],
                    'WORKING'   => ['label' => '作業中',   'class' => 'bg-blue-50 text-blue-700 border-blue-100'],
                    'COMPLETED' => ['label' => '完了',     'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100'],
                ];
            @endphp

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
                        
                        {{-- 2. Title & Description --}}
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
                        
                        {{-- 4. Due Date --}}
                        <td class="px-4 py-5 text-center">
                            <span class="text-[11px] font-bold {{ $isOverdue ? 'text-rose-600 bg-rose-50 px-2 py-1 rounded-lg border border-rose-100' : 'text-slate-600' }}">
                                {{ $task->due_date }}
                            </span>
                        </td>

                        {{-- 5. Status Badge --}}
                        <td class="px-4 py-5 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold border shadow-sm {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        
                        {{-- 6. Action Buttons --}}
                        <td class="px-4 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- View Detail --}}
                                <a href="{{ route('business-requests.show', $task->id) }}" 
                                   class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                                   title="詳細を見る">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>

                                {{-- Status Transition Buttons --}}
                                @if($task->status === 'APPROVED')
                                    <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="WORKING">
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-[10px] font-bold hover:bg-blue-700 shadow-md shadow-blue-100 transition-all flex items-center">
                                            <i data-lucide="play" class="w-3 h-3 mr-1.5"></i> 作業開始
                                        </button>
                                    </form>
                                @elseif($task->status === 'WORKING')
                                    <form action="{{ route('tasks.update-status', $task->id) }}" method="POST" onsubmit="return confirm('作業を完了しますか？');">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="COMPLETED">
                                        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-[10px] font-bold hover:bg-emerald-700 shadow-md shadow-emerald-100 transition-all flex items-center">
                                            <i data-lucide="check-circle" class="w-3 h-3 mr-1.5"></i> 完了
                                        </button>
                                    </form>
                                @elseif($task->status === 'COMPLETED')
                                    <div class="bg-slate-100 p-2 rounded-lg text-slate-400">
                                        <i data-lucide="check-check" class="w-4 h-4"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-data-table>

            {{-- Empty State --}}
            @if($tasks->isEmpty())
                <div class="p-16 text-center">
                    <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                        <i data-lucide="inbox" class="w-10 h-10 text-slate-300"></i>
                    </div>
                    <h3 class="text-slate-800 font-bold">作業はありません</h3>
                    <p class="text-slate-500 text-sm mt-1">現在、新しく割り当てられた作業は見つかりませんでした。</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>