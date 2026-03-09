<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @php
                $userRole = Auth::user()->role;
            @endphp

            {{-- ၁။ Requester View (依頼者) --}}
            @if($userRole === 'employee')
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">依頼者用一覧画面</h1>
                    <a href="{{ route('business-requests.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-md font-bold shadow-md hover:bg-blue-700">＋ 新規作成</a>
                </div>
                
                @php $headers = ['依頼番号', '件名', '依頼区分', '依頼者', '所属部署', '依頼日', '期限', '添付', '担当者', 'ステータス', '操作']; @endphp
                
                <x-data-table id="reqTable" :headers="$headers" role="requester">
                    @foreach($requests as $req)
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="border border-gray-300 px-3 py-4 text-center">{{ $req->request_number }}</td>
                            <td class="border border-gray-300 px-3 py-4 font-medium">{{ $req->title }}</td>
                           <td class="px-4 py-2 min-w-[150px]">
                                <div class="flex flex-row gap-2">
                                    @foreach($req->categories as $category)
                                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs whitespace-nowrap">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="border border-gray-300 px-3 py-4">{{ $req->user?->name }}</td>
                            <td class="border border-gray-300 px-3 py-4">{{ $req->user?->department?->name }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-center">{{ $req->created_at->format('Y/m/d') }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-center font-bold text-red-600">{{ $req->due_date }}</td>
                           <td class="border border-gray-300 px-3 py-4 text-center">
    <div class="flex flex-col gap-1 items-center">
        @forelse($req->attachments as $file)
            <a href="{{ asset('storage/' . $file->file_path) }}" 
               target="_blank" {{-- new browser tab  --}}
               class="text-blue-600 hover:text-blue-800 flex items-center gap-1 text-xs"
               title="{{ $file->file_name }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                </svg>
                <span class="truncate max-w-[80px]">{{ $file->file_name }}</span>
            </a>
        @empty
            <span class="text-gray-400">－</span>
        @endforelse
    </div>
</td>
                            <td class="border border-gray-300 px-3 py-4">{{ $req->worker?->name ?? '未定' }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-center">
                                <span class="bg-orange-400 text-white px-3 py-1 rounded-full text-xs">承認待ち</span>
                            </td>
                           <td class="border border-gray-300 px-3 py-4 text-center">
                                <div class="flex space-x-1 justify-center">
                                    {{-- Detail Button --}}
                                    <a href="{{ route('business-requests.show', $req->id) }}" class="border border-gray-400 px-2 py-1 rounded text-xs hover:bg-gray-100">詳細</a>

                                    {{-- Edit Button (Only allow if status is PENDING/DRAFT - Optional logic) --}}
                                    <a href="{{ route('business-requests.edit', $req->id) }}" class="border border-blue-400 text-blue-600 px-2 py-1 rounded text-xs hover:bg-blue-50">編集</a>

                                    {{-- Delete Button (Using Form for security) --}}
                                    <form action="{{ route('business-requests.destroy', $req->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="border border-red-400 text-red-600 px-2 py-1 rounded text-xs hover:bg-red-50">削除</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-data-table>

            {{-- ၂။ Approver View (承認者) --}}
            @elseif($userRole === 'manager')
                <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">承認者用一覧画面</h1>
                @php $headers = ['依頼番号', '件名', '依頼者', '依頼部署', '対象部署', '依頼日', '期限', '添付', 'ステータス', '操作']; @endphp
                
                <x-data-table id="appTable" :headers="$headers" role="approver">
                   @foreach($managerRequests as $req)
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="border border-gray-300 px-3 py-4 text-center">{{ $req->request_number }}</td>
                            <td class="border border-gray-300 px-3 py-4 font-medium">{{ $req->title }}</td>
                            <td class="border border-gray-300 px-3 py-4">{{ $req->user?->name }}</td>
                            <td class="border border-gray-300 px-3 py-4">{{ $req->user?->department?->name }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-center">{{ $req->targetDepartment?->name }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-center">{{ $req->created_at->format('Y/m/d') }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-center">{{ $req->due_date }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-center">あり</td>
                            <td class="border border-gray-300 px-3 py-4 text-center">
                                <span class="bg-orange-400 text-white px-4 py-1 rounded-md text-xs">承認待ち</span>
                            </td>
                            <td class="border border-gray-300 px-3 py-4 text-center">
                                <a href="#" class="border border-gray-400 px-3 py-1 rounded text-xs shadow-sm">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </x-data-table>

            {{-- ၃။ Worker View (担当者) --}}
            @elseif($userRole === 'employee')
                <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">担当者用一覧画面</h1>
                @php $headers = ['依頼番号', '件名', '依頼区分', '依頼者', '期限', 'ステータス', '操作']; @endphp
                
                <x-data-table id="workTable" :headers="$headers" role="worker">
                   @foreach($workerTasks as $req)
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="border border-gray-300 px-3 py-4 text-center">{{ $req->request_number }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-blue-600 underline font-medium">{{ $req->title }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-center">{{ $req->category_name }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-center">{{ $req->user?->name }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-center">{{ $req->due_date }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-center">
                                @if($req->status === 'WORKING')
                                    <span class="bg-blue-600 text-white px-4 py-1 rounded text-xs font-bold">作業中</span>
                                @else
                                    <span class="bg-yellow-400 text-gray-800 px-4 py-1 rounded text-xs font-bold">未着手</span>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-3 py-4 text-center">
                                <div class="flex space-x-1 justify-center">
                                    <a href="#" class="border border-gray-400 px-2 py-1 rounded text-xs">詳細</a>
                                    @if($req->status === 'WORKING')
                                        <button class="border border-gray-400 px-2 py-1 rounded text-xs">完了報告</button>
                                    @else
                                        <button class="bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">作業開始</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-data-table>
            @endif

        </div>
    </div>


    @if(session('success'))
        <div id="success-alert" class="fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 transition-opacity duration-500">
            {{ session('success') }}
        </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('success-alert');
            if (alert) {
                
                setTimeout(function() {
                    alert.style.opacity = '0';                                 
                    setTimeout(function() {
                        alert.remove();
                    }, 500); 
                }, 3000); 
            }
        });
    </script>
    @endpush
</x-app-layout>