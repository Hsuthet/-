<x-app-layout>
    <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-3xl mx-auto bg-white shadow-lg border border-gray-300 rounded-sm">
            
            <div class="bg-gray-50 px-8 py-4 border-b border-gray-200 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">依頼内容の確認</h1>
                <a href="{{ route('business-requests.index') }}" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</a>
            </div>

            <div class="p-10 space-y-8 text-gray-700">
                <p class="text-sm mb-6">下記の内容で依頼が作成されています。</p>

                <section>
                    <h2 class="font-bold border-b pb-2 mb-4 text-sm tracking-widest text-gray-900">【依頼情報】</h2>
                    <div class="grid grid-cols-1 gap-y-2 ml-4">
                        <div class="flex">
                            <span class="w-32 text-sm text-gray-600">依頼番号：</span>
                            <span class="text-sm font-mono">{{ $request->request_number }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm text-gray-600">依頼日：</span>
                            <span class="text-sm">{{ $request->created_at->format('Y/m/d') }}</span>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="font-bold border-b pb-2 mb-4 text-sm tracking-widest text-gray-900">【依頼者情報】</h2>
                    <div class="grid grid-cols-1 gap-y-2 ml-4">
                        <div class="flex">
                            <span class="w-32 text-sm text-gray-600">件名：</span>
                            <span class="text-sm font-bold">{{ $request->title }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm text-gray-600">依頼者名：</span>
                            <span class="text-sm">{{ $request->user->name }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm text-gray-600">依頼者部署：</span>
                            <span class="text-sm">{{ $request->user->department->name ?? '未設定' }}</span>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="font-bold border-b pb-2 mb-4 text-sm tracking-widest text-gray-900">【依頼先情報】</h2>
                    <div class="grid grid-cols-1 gap-y-4 ml-4">
                        <div class="flex items-center">
                            <span class="w-32 text-sm text-gray-600">対象部署：</span>
                            <span class="px-4 py-1 border border-gray-300 bg-gray-50 text-sm rounded">{{ $request->targetDepartment->name ?? '未設定' }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-32 text-sm text-gray-600">期日：</span>
                            <span class="px-4 py-1 border border-gray-300 bg-gray-50 text-sm rounded">{{ $request->due_date }}</span>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="font-bold border-b pb-2 mb-4 text-sm tracking-widest text-gray-900">【業務内容】</h2>
                    <div class="ml-4 space-y-4">
                        <div class="flex flex-wrap gap-4">
                            @foreach(\App\Models\Category::all() as $category)
                                <div class="flex items-center space-x-2">
                                    <div class="w-4 h-4 border flex items-center justify-center {{ $request->categories->contains($category->id) ? 'bg-green-600 border-green-600' : 'bg-white border-gray-300' }}">
                                        @if($request->categories->contains($category->id))
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        @endif
                                    </div>
                                    <span class="text-sm {{ $request->categories->contains($category->id) ? 'text-gray-900' : 'text-gray-400' }}">{{ $category->name }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div>
                            <span class="text-sm text-gray-600 block mb-2">詳細内容：</span>
                            <div class="text-sm leading-relaxed text-gray-800 ml-4">
                                {{ $request->requestContent->description ?? 'なし' }}
                            </div>
                        </div>

                        <div>
                            <span class="text-sm text-gray-600 block mb-2 text-yellow-600">特記事項：</span>
                            <div class="text-sm text-gray-800 ml-4">
                                {{ $request->requestContent->special_note ?? 'なし' }}
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="font-bold border-b pb-2 mb-4 text-sm tracking-widest text-gray-900">【添付ファイル】</h2>
                    <div class="ml-4">
                        @forelse($request->attachments as $file)
                            <div class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded text-sm text-gray-700">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="hover:underline flex-1">
                                    {{ $file->file_name }}
                                </a>
                               
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 italic">なし</p>
                        @endforelse
                    </div>
                </section>

                <div class="flex justify-end pt-4">
    @php
        // Define the translations
        $statusMap = [
            'PENDING'     => '承認待ち',
            'APPROVED'    => '承認済み',
            'REJECTED'    => '却下',
            'WORKING'     => '作業中',
            'COMPLETED'   => '完了済み',
        ];

        $currentStatus = $request->status;
        $label = $statusMap[$currentStatus] ?? $currentStatus; // Fallback to original if not found
    @endphp

    <div class="inline-flex items-center px-4 py-1 rounded border 
        {{ $currentStatus === 'PENDING' ? 'bg-yellow-50 border-yellow-400 text-yellow-700' : '' }}
        {{ $currentStatus === 'APPROVED' ? 'bg-green-50 border-green-400 text-green-700' : '' }}
        {{ $currentStatus === 'REJECTED' ? 'bg-red-50 border-red-400 text-red-700' : '' }}
        {{ !in_array($currentStatus, ['PENDING', 'APPROVED', 'REJECTED']) ? 'bg-gray-50 border-gray-300 text-gray-600' : '' }}
        text-xs font-bold">
        ステータス：{{ $label }}
    </div>
</div>

                <div class="flex justify-center space-x-4 border-t pt-10">
                    <a href="{{ route('business-requests.index') }}" class="px-10 py-2 border border-gray-300 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm transition">
                        戻る
                    </a>
                    
                    @if($request->status === 'PENDING' && Auth::user()->role === 'manager')
                        <form action="{{ route('business-requests.updateStatus', $request->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="px-10 py-2 bg-indigo-700 hover:bg-indigo-800 text-white text-sm transition shadow-sm font-bold">
                                依頼を承認する
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>