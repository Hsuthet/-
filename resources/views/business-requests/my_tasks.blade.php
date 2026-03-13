<x-app-layout>
    @section('header_title', '担当作業 (My Assigned Tasks)')

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
                // Detailed headers to match the rich content below
                $headers = ['依頼番号', '件名・内容', '依頼者', '所属部署', '期限', 'ステータス', '操作'];
            @endphp

            <x-data-table id="tasksTable" :headers="$headers" role="employee">
                @foreach($tasks as $task)
                    <tr class="hover:bg-indigo-50/30 transition border-b border-slate-100">
                        {{-- 1. Request Number --}}
                        <td class="px-4 py-4 text-center font-bold text-slate-700">{{ $task->request_number }}</td>
                        
                        {{-- 2. Title & Description Snippet --}}
                        <td class="px-4 py-4">
                            <span class="block font-bold text-slate-900">{{ $task->title }}</span>
                            @if($task->requestContent?->description)
                                <span class="text-[11px] text-slate-400 line-clamp-1">
                                    {{ Str::limit($task->requestContent->description, 50) }}
                                </span>
                            @endif
                        </td>
                        
                        {{-- 3. Requester Name --}}
                        <td class="px-4 py-4 font-medium text-slate-700 text-sm">
                            {{ $task->user?->name }}
                        </td>
                        
                        {{-- 4. Department --}}
                        <td class="px-4 py-4 text-xs text-slate-500">
                            {{ $task->user?->department?->name }}
                        </td>
                        
                        {{-- 5. Due Date (With Overdue check) --}}
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
                            @if($task->status === 'WORKING' || $task->status === 'APPROVED')
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-bold border border-blue-200">作業中</span>
                            @elseif($task->status === 'COMPLETED')
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-bold border border-emerald-200">完了</span>
                            @else
                                <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-[10px] font-bold border border-slate-200">未着手</span>
                            @endif
                        </td>
                        
                        {{-- 7. Action Button --}}
                        <td class="px-4 py-4 text-center">
                            <a href="{{ route('business-requests.show', $task->id) }}" 
                               class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-indigo-700 shadow-sm transition inline-flex items-center">
                                <i data-lucide="external-link" class="w-3 h-3 mr-1.5"></i>
                                内容確認・報告
                            </a>
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
                    <p class="text-slate-400 text-xs mt-1">新しい作業が割り当てられるまでお待ちください。</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>