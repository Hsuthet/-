<x-app-layout>
    @section('header_title', '自分の依頼 (My Sent Requests)')

    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-slate-800">送信済みの依頼一覧</h1>
                <p class="text-sm text-slate-500">あなたが作成した依頼の進捗を確認できます。</p>
            </div>
            <a href="{{ route('business-requests.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow hover:bg-indigo-700 transition flex items-center">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> 新規依頼作成
            </a>
        </div>

        {{-- Table Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            @php 
                // Matching the headers exactly to the columns below
                $headers = ['依頼番号', '件名', '依頼区分', '依頼者', '期限', '添付', 'ステータス', '操作']; 
            @endphp
            
            <x-data-table id="requestsTable" :headers="$headers" role="employee">
                @foreach($requests as $req)
                    <tr class="hover:bg-slate-50 border-b border-slate-100 transition-colors">
                        {{-- 1. Request Number --}}
                        <td class="px-3 py-4 text-center font-medium text-slate-600">{{ $req->request_number }}</td>
                        
                        {{-- 2. Title --}}
                        <td class="px-3 py-4 font-bold text-slate-800">{{ $req->title }}</td>
                        
                        {{-- 3. Categories (Badges) --}}
                        <td class="px-3 py-2 min-w-[120px]">
                            <div class="flex flex-wrap gap-1">
                                @foreach($req->categories as $category)
                                    <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded text-[10px] font-bold whitespace-nowrap border border-indigo-100">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        {{-- 4. Requester (CRITICAL: Required for "Jibun no Irai" filter) --}}
                        <td class="px-3 py-4 text-center text-slate-600 text-sm">{{ $req->user?->name }}</td>

                        {{-- 5. Due Date --}}
                        <td class="px-3 py-4 text-center font-bold text-rose-600 text-sm">{{ $req->due_date }}</td>

                        {{-- 6. Attachments (File Links) --}}
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

                        {{-- 7. Status (Colored Badges) --}}
                        <td class="px-3 py-4 text-center">
                            @if($req->status === 'PENDING')
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-bold border border-amber-200">承認待ち</span>
                            @elseif($req->status === 'APPROVED')
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-bold border border-emerald-200">承認済み</span>
                            @elseif($req->status === 'REJECTED')
                                <span class="bg-rose-100 text-rose-700 px-3 py-1 rounded-full text-[10px] font-bold border border-rose-200">却下</span>
                            @else
                                <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-[10px] font-bold border border-slate-200">{{ $req->status }}</span>
                            @endif
                        </td>

                        {{-- 8. Actions --}}
                        <td class="px-3 py-4 text-center">
                            <div class="flex space-x-2 justify-center">
                                <a href="{{ route('business-requests.show', $req->id) }}" 
                                   class="text-indigo-600 hover:bg-indigo-50 p-1.5 rounded-lg transition" 
                                   title="詳細表示">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>

                                @if($req->status === 'PENDING')
                                    <a href="{{ route('business-requests.edit', $req->id) }}" 
                                       class="text-amber-600 hover:bg-amber-50 p-1.5 rounded-lg transition"
                                       title="編集">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>

                                    <form action="{{ route('business-requests.destroy', $req->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:bg-rose-50 p-1.5 rounded-lg transition" title="削除">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-data-table>
        </div>
    </div>
</x-app-layout>