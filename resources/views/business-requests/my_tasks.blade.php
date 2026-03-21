<x-app-layout>
    @section('header_title', '担当作業 ')

    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex items-center space-x-3">
            <div class="bg-indigo-600 p-2 rounded-lg shadow-md">
                <i data-lucide="briefcase" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800">あなたに割り当てられた作業</h1>
                <p class="text-sm text-slate-500">担当者として対応が必要な依頼の一覧です。</p>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden border-t-4 border-t-indigo-500">
            @php
                $headers = ['依頼番号', '件名・内容', '依頼者', '所属部署', '期限', 'ステータス', '操作'];
            @endphp

            <x-data-table id="tasksTable" :headers="$headers" role="employee" :showFilters="true">
                @foreach($tasks as $task)
                    <tr class="hover:bg-indigo-50/30 transition border-b border-slate-100">
                        {{-- 1. Request Number --}}
                        <td class="px-4 py-4 text-center font-bold text-slate-700">{{ $task->request_number }}</td>
                        
                        {{-- 2. Title & Description --}}
                        <td class="px-4 py-4">
                            <span class="block font-bold text-slate-900">{{ $task->title }}</span>
                            @if($task->requestContent?->description)
                                <span class="text-[11px] text-slate-400 line-clamp-1">
                                    {{ Str::limit($task->requestContent->description, 50) }}
                                </span>
                            @endif
                        </td>
                        
                        {{-- 3. Requester --}}
                        <td class="px-4 py-4 font-medium text-slate-700 text-sm">{{ $task->user?->name }}</td>
                        
                        {{-- 4. Department --}}
                        <td class="px-4 py-4 text-xs text-slate-500">{{ $task->user?->department?->name }}</td>
                        
                        {{-- 5. Due Date --}}
                        <td class="px-4 py-4 text-center">
                            @php 
                                $isOverdue = \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'COMPLETED';
                            @endphp
                            <span class="px-2 py-1 rounded text-[10px] font-bold {{ $isOverdue ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                {{ $task->due_date }}
                            </span>
                        </td>

                        {{-- 6. Task Status --}}
                        <td class="px-4 py-4 text-center">
                            @if($task->status === 'WORKING')
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-bold border border-blue-200">作業中</span>
                            @elseif($task->status === 'APPROVED')
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-bold border border-amber-200">承認済み</span>
                            @elseif($task->status === 'COMPLETED')
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-bold border border-emerald-200">完了</span>
                            @else
                                <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-[10px] font-bold border border-slate-200">{{ $task->status }}</span>
                            @endif
                        </td>
                        
                        {{-- 7. Action Button --}}
                        <td class="px-4 py-4 text-center">
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-2">
                                {{-- Always show View Details --}}
                                <a href="{{ route('business-requests.show', $task->id) }}" 
                                   class="flex items-center justify-center w-9 h-9 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition shadow-sm"
                                   title="詳細を見る">
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                </a>

                                @if($task->status === 'APPROVED')
                                    <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="WORKING">
                                        <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-700 shadow-sm transition flex items-center">
                                            <i data-lucide="play" class="w-3 h-3 mr-1"></i> 作業開始
                                        </button>
                                    </form>
                                @elseif($task->status === 'WORKING')
                                    <form action="{{ route('tasks.update-status', $task->id) }}" method="POST" onsubmit="return confirm('作業を完了しますか？');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="COMPLETED">
                                        <button type="submit" class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-emerald-700 shadow-sm transition flex items-center">
                                            <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i> 完了にする
                                        </button>
                                    </form>
                                @elseif($task->status === 'COMPLETED')
                                    {{-- Optional: Show a "Finished" icon or just leave the Detail button --}}
                                    <span class="text-slate-400">
                                        <i data-lucide="check-check" class="w-5 h-5"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-data-table>

            {{-- Empty State --}}
            @if($tasks->isEmpty())
                <div class="p-12 text-center">
                    <div class="bg-slate-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="inbox" class="w-8 h-8 text-slate-300"></i>
                    </div>
                    <p class="text-slate-500 font-medium">現在、担当している作業はありません。</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>