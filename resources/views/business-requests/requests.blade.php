<x-app-layout>
    @section('header_title', '依頼管理')

    <div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-600 p-2.5 rounded-xl shadow-lg shadow-indigo-200">
                    <i data-lucide="layers" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">送信済みの依頼一覧</h1>
                    <p class="text-sm text-slate-500">システム全体の依頼状況を確認・管理できます。</p>
                </div>
            </div>
            
            <a href="{{ route('business-requests.create') }}" 
               class="inline-flex items-center justify-center px-6 py-2.5 bg-indigo-600 rounded-xl font-bold text-white text-sm shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all duration-200">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                新規依頼作成
            </a>
        </div>

        {{-- Filter Control Bar --}}
        <div class="bg-slate-50 p-2 rounded-2xl border border-slate-200 flex flex-wrap items-center justify-between gap-4">
            {{-- Left: View Toggle (All vs My) --}}
            <div class="inline-flex bg-slate-200/50 p-1 rounded-xl">
                <button type="button" 
                        data-table="requestsTable" 
                        data-search-type="user"
                        data-search-value="" 
                        class="filter-btn active-toggle px-6 py-2 rounded-lg text-sm font-bold transition-all duration-200 bg-white shadow-sm text-indigo-600">
                    全ての依頼
                </button>
                <button type="button" 
                        data-table="requestsTable" 
                        data-search-type="user"
                        data-search-value="{{ Auth::user()->name }}" 
                        class="filter-btn px-6 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-indigo-600 transition-all duration-200">
                    自分の依頼
                </button>
            </div>

            {{-- Right: Status Dropdown --}}
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">絞り込み:</span>
                <x-table-status-filter 
                    tableId="requestsTable" 
                    columnIndex="5"
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

        {{-- Table Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            @php 
                $headers = ['管理番号', '案件詳細', '区分', '期限', '添付', 'ステータス', 'アクション']; 
                $statusMap = [
                    'PENDING'   => ['label' => '承認待ち', 'class' => 'bg-amber-50 text-amber-700 border-amber-100'],
                    'APPROVED'  => ['label' => '承認済み', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100'],
                    'REJECTED'  => ['label' => '却下',     'class' => 'bg-rose-50 text-rose-700 border-rose-100'],
                    'WORKING'   => ['label' => '作業中',   'class' => 'bg-blue-50 text-blue-700 border-blue-100'],
                    'COMPLETED' => ['label' => '完了',     'class' => 'bg-slate-50 text-slate-700 border-slate-100'], 
                ];
            @endphp

            <x-data-table id="requestsTable" :headers="$headers" :showFilters="false">
                @foreach($requests as $req)
                    @php 
                        $status = $statusMap[$req->status] ?? ['label' => $req->status, 'class' => 'bg-slate-100 text-slate-700 border-slate-200']; 
                        $isOverdue = \Carbon\Carbon::parse($req->due_date)->isPast() && !in_array($req->status, ['APPROVED', 'WORKING', 'COMPLETED']);
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors border-b border-slate-100">
                        <td class="px-4 py-5 text-center">
                            <span class="font-mono text-[11px] font-bold bg-slate-100 px-2 py-1 rounded text-slate-600 uppercase tracking-tighter">
                                {{ $req->request_number }}
                            </span>
                        </td>
                        <td class="px-4 py-5">
                            <div class="group">
                                <p class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $req->title }}</p>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <span class="inline-flex items-center text-[10px] text-slate-400 font-medium">
                                        <i data-lucide="user" class="w-3 h-3 mr-1"></i> {{ $req->user?->name }}
                                    </span>
                                    <span class="text-slate-200">|</span>
                                    <span class="text-[10px] text-slate-400">{{ $req->created_at->format('Y/m/d') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-5 text-center">
                            <div class="flex flex-wrap gap-1 justify-center">
                                @foreach($req->categories as $category)
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded text-[10px] font-bold border border-slate-200/50">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-5 text-center">
                            <span class="text-[11px] font-bold {{ $isOverdue ? 'text-rose-600 bg-rose-50 px-2 py-1 rounded-lg border border-rose-100' : 'text-slate-600' }}">
                                {{ $req->due_date }}
                            </span>
                        </td>
                        <td class="px-4 py-5 text-center">
                           @if($req->attachments->isNotEmpty())
                                <div class="flex justify-center -space-x-1.5">
                                    @foreach($req->attachments->take(3) as $file)
                                        <div class="h-7 w-7 rounded-full border-2 border-white bg-indigo-50 flex items-center justify-center text-indigo-500 shadow-sm" title="{{ $file->file_name }}">
                                            <i data-lucide="paperclip" class="w-3 h-3"></i>
                                        </div>
                                    @endforeach
                                </div>
                           @else
                                <span class="text-slate-300">－</span>
                           @endif
                        </td>
                        <td class="px-4 py-5 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold border shadow-sm {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('business-requests.show', $req->id) }}" class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </a>
                                @if($req->status === 'PENDING' && $req->user_id === auth()->id())
                                    <a href="{{ route('business-requests.edit', $req->id) }}" class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-data-table>
        </div>
    </div>
</x-app-layout>