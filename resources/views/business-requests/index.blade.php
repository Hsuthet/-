<x-app-layout>
    @section('header_title', '依頼一覧 / Requests List')

    <div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @php
            $userRole = Auth::user()->role;
            
            // Centralized Status Design
            $statusMap = [
                'PENDING'   => ['label' => '承認待ち', 'class' => 'bg-amber-100 text-amber-700 border-amber-200'],
                'APPROVED'  => ['label' => '承認済み', 'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
                'REJECTED'  => ['label' => '却下',     'class' => 'bg-rose-100 text-rose-700 border-rose-200'],
                'WORKING'   => ['label' => '作業中',   'class' => 'bg-blue-100 text-blue-700 border-blue-200'],
                'COMPLETED' => ['label' => '完了',     'class' => 'bg-slate-100 text-slate-700 border-slate-200'],
            ];
        @endphp

        {{-- 1. HEADER SECTION --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-600 p-2 rounded-lg shadow-md">
                    @if($userRole === 'employee')
                        <i data-lucide="send" class="w-6 h-6 text-white"></i>
                    @elseif($userRole === 'manager')
                        <i data-lucide="clipboard-check" class="w-6 h-6 text-white"></i>
                    @else
                        <i data-lucide="briefcase" class="w-6 h-6 text-white"></i>
                    @endif
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                        @if($userRole === 'employee') 依頼者用一覧 @elseif($userRole === 'manager') 承認者用一覧 @else 担当者用一覧 @endif
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">システムの進捗状況をリアルタイムで確認できます。</p>
                </div>
            </div>
            
            @if($userRole === 'employee')
            <a href="{{ route('business-requests.create') }}" 
               class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 rounded-xl font-bold text-white text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all duration-200">
                <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i>
                新規依頼作成
            </a>
            @endif
        </div>

        {{-- 2. TABLE SECTION --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden border-t-4 border-t-indigo-500">
            @php 
                $headers = ($userRole === 'manager') 
                    ? ['管理番号', '案件詳細', '依頼者/部署', '対象部署', '期限', 'ステータス', '操作']
                    : ['管理番号', '案件詳細', '区分', '期限', '添付', 'ステータス', '操作'];
                
                $iterationData = match($userRole) {
                    'manager' => $managerRequests,
                    'admin'   => $workerTasks, // Assuming role 'admin' for workers
                    default   => $requests,
                };
            @endphp

            <x-data-table :id="$userRole.'Table'" :headers="$headers" :role="$userRole" :showFilters="true">
                @foreach($iterationData as $req)
                    @php 
                        $status = $statusMap[$req->status] ?? ['label' => $req->status, 'class' => 'bg-slate-100 text-slate-700 border-slate-200']; 
                        $isOverdue = \Carbon\Carbon::parse($req->due_date)->isPast() && !in_array($req->status, ['APPROVED', 'COMPLETED']);
                    @endphp

                    <tr class="hover:bg-indigo-50/30 transition border-b border-slate-100">
                        
                        {{-- Col 1: ID --}}
                        <td class="px-4 py-4 text-center font-bold text-slate-700 text-xs">
                            <span class="font-mono bg-slate-100 px-2 py-1 rounded">
                                {{ $req->request_number }}
                            </span>
                        </td>

                        {{-- Col 2: Title & Details --}}
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900">{{ $req->title }}</span>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] text-slate-400 flex items-center">
                                        <i data-lucide="calendar" class="w-3 h-3 mr-1"></i> {{ $req->created_at->format('Y/m/d') }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- Col 3: Role Specific (Categories or User Info) --}}
                        <td class="px-4 py-4 text-center">
                            @if($userRole === 'manager')
                                <div class="flex flex-col items-center">
                                    <span class="text-xs font-medium text-slate-700">{{ $req->user?->name }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $req->user?->department?->name }}</span>
                                </div>
                            @else
                                <div class="flex flex-wrap gap-1 justify-center max-w-[150px]">
                                    @foreach($req->categories as $category)
                                        <span class="px-2 py-0.5 bg-white border border-slate-200 text-slate-500 rounded text-[10px] font-bold">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        {{-- Col 4: Target Dept (Manager) or Due Date --}}
                        <td class="px-4 py-4 text-center">
                            @if($userRole === 'manager')
                                <span class="text-xs text-slate-600">{{ $req->targetDepartment?->name }}</span>
                            @else
                                <span class="px-2 py-1 rounded text-[10px] font-bold {{ $isOverdue ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    {{ $req->due_date }}
                                </span>
                            @endif
                        </td>

                        {{-- Col 5: Due Date (Manager) or Attachments --}}
                        <td class="px-4 py-4 text-center">
                            @if($userRole === 'manager')
                                <span class="px-2 py-1 rounded text-[10px] font-bold {{ $isOverdue ? 'bg-rose-100 text-rose-700' : 'text-slate-700' }}">
                                    {{ $req->due_date }}
                                </span>
                            @else
                                <div class="flex justify-center">
                                    @if($req->attachments->isNotEmpty())
                                        <div class="flex -space-x-2">
                                            @foreach($req->attachments as $file)
                                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" 
                                                   class="h-7 w-7 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:scale-110 transition-all shadow-sm"
                                                   title="{{ $file->file_name }}">
                                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-slate-300 text-xs">－</span>
                                    @endif
                                </div>
                            @endif
                        </td>

                        {{-- Col 6: Status --}}
                        <td class="px-4 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold border {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>

                        {{-- Col 7: Actions --}}
                        <td class="px-4 py-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('business-requests.show', $req->id) }}" 
                                   class="flex items-center justify-center w-9 h-9 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition shadow-sm" title="詳細">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </a>

                                @if($userRole === 'manager' && $req->status === 'PENDING')
                                    <a href="{{ route('business-requests.approve', $req->id) }}" 
   class="p-2 text-emerald-500 hover:bg-emerald-50 rounded-lg transition border border-emerald-100"
   title="承認">
    <i data-lucide="check-circle" class="w-4 h-4"></i>
</a>
                                @endif

                                @if($userRole === 'employee' && $req->status === 'PENDING')
                                    <a href="{{ route('business-requests.edit', $req->id) }}" 
                                       class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="編集">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                @endif

                                {{-- @if($req->status === 'PENDING')
                                    <form action="{{ route('business-requests.destroy', $req->id) }}" method="POST" class="inline" onsubmit="return confirm('削除しますか？');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif --}}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-data-table>

            @if($iterationData->isEmpty())
                <div class="p-12 text-center">
                    <div class="bg-slate-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="inbox" class="w-8 h-8 text-slate-300"></i>
                    </div>
                    <p class="text-slate-500 font-medium">表示するデータがありません。</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div id="success-alert" class="fixed top-5 right-5 bg-emerald-600 text-white px-6 py-3 rounded-xl shadow-2xl z-50 flex items-center gap-3 animate-bounce-short">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif
</x-app-layout>