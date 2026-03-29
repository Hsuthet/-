<x-app-layout>
    @section('header_title', '承認待ち一覧 ')

    <div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @php
            // Centralized Status Design for Managers
            $statusMap = [
    'PENDING'   => ['label' => '承認待ち', 'class' => 'bg-amber-50 text-amber-700 border-amber-100'],
    'APPROVED'  => ['label' => '承認済み', 'class' => 'bg-teal-50 text-teal-700 border-teal-200'],
    'REJECTED'  => ['label' => '却下',     'class' => 'bg-rose-50 text-rose-700 border-rose-100'],
    'WORKING'   => ['label' => '作業中',   'class' => 'bg-blue-50 text-blue-700 border-blue-100'],
    'COMPLETED' => ['label' => '完了',     'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200'], 
];

            $headers = ['管理番号', '案件詳細', '依頼者/部署', '対象部署', '期限','添付', 'ステータス', '操作'];
        @endphp

        {{-- 1. HEADER SECTION --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-600 p-2.5 rounded-xl shadow-lg shadow-indigo-100">
                    <i data-lucide="clipboard-check" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight">承認者用管理一覧</h1>
                    <p class="text-sm text-slate-500 mt-1 font-medium">配下部署からの依頼承認および進捗管理が行えます。</p>
                </div>
            </div>
        </div>
        {{-- 2. FILTER CONTROL BAR (Status Only) --}}
<div class="bg-slate-50 p-2 rounded-2xl border border-slate-200 flex flex-wrap items-center justify-between gap-4">
    {{-- Right: Status Dropdown --}}
      <div class="px-4">
                <span class="text-sm font-bold text-slate-600">タスクフィルター</span>
            </div>
    <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">ステータス:</span>
        <x-table-status-filter 
            tableId="managerTable" 
            columnIndex="6"
            placeholder="全てのステータス"
            :options="[
                '承認待ち' => '承認待ち',
                '承認済み' => '承認済み',
                '作業中'   => '作業中',
                '完了'     => '完了',
                '却下'     => '却下',
            ]" 
        />
    </div>
</div>

        {{-- 2. TABLE SECTION --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden border-t-4 border-t-indigo-500">
            
            <x-data-table id="managerTable" :headers="$headers" role="manager" :showFilters="true">
                @foreach($managerRequests as $req)
                    @php 
                        $status = $statusMap[$req->status] ?? ['label' => $req->status, 'class' => 'bg-slate-100 text-slate-700 border-slate-200']; 
                        $dueDate = \Carbon\Carbon::parse($req->due_date);
                        $isOverdue = $dueDate->isPast() && !in_array($req->status, ['APPROVED', 'COMPLETED', 'REJECTED']);
                    @endphp

                    <tr class="hover:bg-slate-50/80 transition border-b border-slate-100 group">
                        
                        {{-- Col 1: ID --}}
                        <td class="px-4 py-5 text-center">
                            <span class="font-mono bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg text-xs font-bold border border-slate-200">
                                {{ $req->request_number }}
                            </span>
                        </td>

                        {{-- Col 2: Title & Timestamp --}}
                        <td class="px-4 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                                    {{ $req->title }}
                                </span>
                                <span class="text-[10px] text-slate-400 flex items-center mt-1 font-medium">
                                    <i data-lucide="clock-3" class="w-3 h-3 mr-1"></i> 
                                    申請日: {{ $req->created_at->format('Y/m/d H:i') }}
                                </span>
                            </div>
                        </td>

                        {{-- Col 3: Requester Info --}}
                        <td class="px-4 py-5">
                            <div class="flex flex-col items-center text-center">
                                <span class="text-xs font-bold text-slate-700">{{ $req->user?->name ?? 'Unknown' }}</span>
                                <span class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-md mt-1 font-bold">
                                    {{ $req->user?->department?->name ?? '未設定' }}
                                </span>
                            </div>
                        </td>

                        {{-- Col 4: Target Dept --}}
                        <td class="px-4 py-5 text-center">
                            <span class="text-xs font-semibold text-slate-600">
                                {{ $req->targetDepartment?->name ?? '－' }}
                            </span>
                        </td>

                        {{-- Col 5: Due Date with Alert --}}
                        <td class="px-4 py-5 text-center">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black border {{ $isOverdue ? 'bg-rose-50 text-rose-600 border-rose-100 animate-pulse' : 'bg-slate-50 text-slate-600 border-slate-100' }}">
                                {{ $dueDate->format('Y/m/d') }}
                            </span>
                        </td>

                         <td class="px-3 py-4 text-center">
                            <div class="flex flex-col gap-1 items-center">
                                @forelse($req->attachments as $file)
                                    <a href="{{ asset('storage/' . $file->file_path) }}" 
                                       target="_blank"
                                       class="text-indigo-600 hover:text-indigo-800 flex items-center gap-1 text-[10px]"
                                       title="{{ $file->file_name }}">
                                        <i data-lucide="paperclip" class="w-3 h-3"></i>
                                        <span class="truncate max-w-[60px]">{{ $file->file_name }}</span>
                                    </a>
                                @empty
                                    <span class="text-slate-300 text-xs">－</span>
                                @endforelse
                            </div>
                        </td>
                        {{-- Col 6: Status Pill --}}
                        <td class="px-4 py-5 text-center">
    <span class="px-3 py-1 rounded-full text-[10px] font-black border shadow-sm {{ $req->status_config['color'] }}">
        {{ $req->status_config['label'] }}
    </span>
</td>

                        {{-- Col 7: Primary Actions --}}
                        <td class="px-4 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- View Details --}}
                                <a href="{{ route('business-requests.display', $req->id) }}" 
                                   class="flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 text-slate-500 hover:bg-indigo-600 hover:text-white transition-all duration-200 shadow-sm" 
                                   title="詳細表示">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </a>

                                {{-- Quick Approval (Only if Pending) --}}
                                @if(
            $req->status === 'PENDING' &&
            auth()->user()->department_id === $req->target_department_id
        )
                                    <a href="{{ route('business-requests.approve', $req->id) }}" 
                                       class="flex items-center justify-center w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all duration-200 shadow-sm"
                                       title="即時承認">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-data-table>

            @if($managerRequests->isEmpty())
                <div class="p-20 text-center">
                    <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                        <i data-lucide="inbox" class="w-10 h-10 text-slate-200"></i>
                    </div>
                    <h3 class="text-slate-800 font-bold">承認待ちの依頼はありません</h3>
                    <p class="text-slate-400 text-xs mt-1 font-medium">現在、確認が必要なリクエストはすべて処理されています。</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Notification Toast --}}
    @if(session('success'))
        <div id="success-alert" class="fixed bottom-10 right-10 bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl z-50 flex items-center gap-4 animate-in fade-in slide-in-from-bottom-10">
            <div class="bg-emerald-500 p-1.5 rounded-lg">
                <i data-lucide="check" class="w-4 h-4 text-white"></i>
            </div>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif
</x-app-layout>