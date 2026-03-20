<x-app-layout>
    @section('header_title', '自分の依頼')

    <div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section: Styled like Tasks Blade --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-600 p-2 rounded-lg shadow-md">
                    <i data-lucide="send" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">送信済みの依頼一覧</h1>
                    <p class="text-sm text-slate-500 mt-1">作成した依頼の承認状況や進捗をこちらから確認・管理できます。</p>
                </div>
            </div>
            
            <a href="{{ route('business-requests.create') }}" 
               class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 rounded-xl font-bold text-white text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all duration-200">
                <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i>
                新規依頼作成
            </a>
        </div>

        {{-- Table Section: Added Rounded-xl, Shadow, and Top-Accent Border --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden border-t-4 border-t-indigo-500">
            @php 
                $headers = ['管理番号', '案件詳細', '区分', '期限', '添付', 'ステータス', 'アクション']; 

                $statusMap = [
        'PENDING'   => ['label' => '承認待ち', 'class' => 'bg-amber-100 text-amber-700 border-amber-200'],
        'APPROVED'  => ['label' => '承認済み', 'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
        'REJECTED'  => ['label' => '却下',     'class' => 'bg-rose-100 text-rose-700 border-rose-200'],
        'WORKING'   => ['label' => '作業中',   'class' => 'bg-blue-100 text-blue-700 border-blue-200'],
        'COMPLETED' => ['label' => '完了',     'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200'], 
    ];
            @endphp

            <x-data-table id="requestsTable" :headers="$headers" role="employee" :showFilters="true">
                @foreach($requests as $req)
                    @php 
                        $status = $statusMap[$req->status] ?? ['label' => $req->status, 'class' => 'bg-slate-100 text-slate-700 border-slate-200']; 
                        $isOverdue = \Carbon\Carbon::parse($req->due_date)->isPast() && !in_array($req->status, ['APPROVED', 'WORKING']);
                    @endphp

                    <tr class="hover:bg-indigo-50/30 transition border-b border-slate-100">
                        
                        {{-- 1. Request Number --}}
                        <td class="px-4 py-4 text-center font-bold text-slate-700 text-xs">
                            <span class="font-mono bg-slate-100 px-2 py-1 rounded">
                                {{ $req->request_number }}
                            </span>
                        </td>

                        {{-- 2. Title & User Info --}}
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900">
                                    {{ $req->title }}
                                </span>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] text-slate-400 flex items-center">
                                        <i data-lucide="user" class="w-3 h-3 mr-1"></i> {{ $req->user?->name }}
                                    </span>
                                    <span class="text-[10px] text-slate-300">|</span>
                                    <span class="text-[10px] text-slate-400">作成: {{ $req->created_at->format('Y/m/d') }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- 3. Categories --}}
                        <td class="px-4 py-4 text-center">
                            <div class="flex flex-wrap gap-1 justify-center max-w-[150px]">
                                @foreach($req->categories as $category)
                                    <span class="px-2 py-0.5 bg-white border border-slate-200 text-slate-500 rounded text-[10px] font-bold">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        {{-- 4. Due Date --}}
                        <td class="px-4 py-4 text-center">
                            <span class="px-2 py-1 rounded text-[10px] font-bold {{ $isOverdue ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                {{ $req->due_date }}
                            </span>
                        </td>

                        {{-- 5. Attachments --}}
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center">
                                @if($req->attachments->isNotEmpty())
                                    <div class="flex -space-x-2">
                                        @foreach($req->attachments as $file)
                                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" 
                                               class="h-7 w-7 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:scale-110 transition-all shadow-sm"
                                               title="{{ $file->file_name }}">
                                                <i data-lucide="paperclip" class="w-3.5 h-3.5"></i>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-300 text-xs">－</span>
                                @endif
                            </div>
                        </td>

                        {{-- 6. Status Badge --}}
                        <td class="px-4 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold border {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>

                        {{-- 7. Actions --}}
                        <td class="px-4 py-4 text-center">
    <div class="flex items-center justify-center gap-2">

       {{-- Detail (Always visible) --}}
<a href="{{ route('business-requests.show', $req->id) }}" 
   class="flex items-center justify-center w-9 h-9 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition shadow-sm"
   title="詳細">
    <i data-lucide="file-text" class="w-4 h-4"></i>
</a>

{{-- Check if Status is PENDING AND the current user is the owner --}}
@if($req->status === 'PENDING' && $req->user_id === auth()->id())

    {{-- Edit --}}
    <a href="{{ route('business-requests.edit', $req->id) }}" 
       class="flex items-center justify-center w-9 h-9 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition shadow-sm"
       title="編集">
        <i data-lucide="pencil" class="w-4 h-4"></i>
    </a>

    {{-- Delete --}}
    <form action="{{ route('business-requests.destroy', $req->id) }}" 
          method="POST" 
          class="inline"
          onsubmit="return confirm('この依頼を削除しますか？');">
        @csrf
        @method('DELETE')

        <button type="submit"
            class="flex items-center justify-center w-9 h-9 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition shadow-sm"
            title="削除">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
        </button>
    </form>

@endif
    </div>
</td>
                    </tr>
                @endforeach
            </x-data-table>

            {{-- Empty State --}}
            @if($requests->isEmpty())
                <div class="p-12 text-center">
                    <div class="bg-slate-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="inbox" class="w-8 h-8 text-slate-300"></i>
                    </div>
                    <p class="text-slate-500 font-medium">送信済みの依頼はありません。</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>