<x-app-layout>
    <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-5xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-lg p-10 border border-gray-200">

                <h1 class="text-2xl font-bold text-center mb-8">業務依頼書・連絡書</h1>

                <div class="flex justify-center items-center mb-10 text-sm">
                    <span class="text-gray-400">Step 1: 入力</span>
                    <span class="mx-4 text-gray-400">→</span>
                    <span class="text-indigo-600 font-bold border-b-2 border-indigo-600 pb-1">Step 2: 確認</span>
                    <span class="mx-4 text-gray-400">→</span>
                    <span class="text-gray-400">Step 3: 完了</span>
                </div>

                <div class="space-y-6 text-sm">
                    <div class="grid md:grid-cols-2 gap-6 mb-8 bg-gray-50 p-6 rounded-lg border">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">依頼番号</label>
                            <p class="text-lg font-bold text-indigo-700">{{ $nextNumber }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">作成日</label>
                            <p class="text-sm font-medium">{{ date('Y/m/d') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <strong class="text-gray-600">件名:</strong>
                            <div class="text-base font-semibold mt-1">{{ $data['title'] }}</div>
                        </div>

                        <div>
                            <strong class="text-gray-600">対象部署:</strong>
                            <div class="mt-1">{{ $department->name }}</div>
                        </div>

                        <div>
                            <strong class="text-gray-600">期日:</strong>
                            <div class="mt-1">{{ $data['due_date'] }}</div>
                        </div>

                        <div>
                            <strong class="text-gray-600">業務区分:</strong>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($categories as $cat)
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold">
                                        {{ $cat->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <strong class="text-gray-600">詳細内容:</strong>
                            <div class="bg-gray-50 p-4 rounded border mt-2 whitespace-pre-line text-gray-800 leading-relaxed">
                                {{ $data['content'] }}
                            </div>
                        </div>

                        @if(isset($data['notes']) && $data['notes'])
                            <div>
                                <strong class="text-gray-600 text-red-500">特記事項:</strong>
                                <div class="mt-1 text-red-700 italic">{{ $data['notes'] }}</div>
                            </div>
                        @endif

                        <div>
                            <strong class="text-gray-600">添付ファイル:</strong>
                            <div class="mt-2">
                                @if(count($storedFiles) > 0)
                                    <ul class="space-y-2">
                                        @foreach($storedFiles as $file)
                                            <li class="flex items-center text-indigo-600">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                {{ $file['name'] }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-400 italic">なし</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 border-t pt-8 mt-10">
                    <a href="{{ route('business-requests.create') }}" 
                       class="px-10 py-2 border border-gray-300 bg-white rounded-md text-gray-700 hover:bg-gray-50 transition">
                        戻る
                    </a>

                  <form method="POST" action="{{ route('business-requests.complete') }}">
    @csrf
    
    <input type="hidden" name="target_department_id" value="{{ $data['department_id'] }}">
    
    <input type="hidden" name="request_number" value="{{ $nextNumber }}">
    <input type="hidden" name="title" value="{{ $data['title'] }}">
    <input type="hidden" name="due_date" value="{{ $data['due_date'] }}">
    <input type="hidden" name="content" value="{{ $data['content'] }}">
    <input type="hidden" name="notes" value="{{ $data['notes'] ?? '' }}">

    @foreach($data['categories'] as $catId)
        <input type="hidden" name="categories[]" value="{{ $catId }}">
    @endforeach

    @foreach($storedFiles as $file)
        <input type="hidden" name="attachment_paths[]" value="{{ $file['path'] }}">
        <input type="hidden" name="attachment_names[]" value="{{ $file['name'] }}">
    @endforeach

    <button type="submit" class="bg-indigo-600 text-white px-8 py-2 rounded-md">
        送信する
    </button>
</form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>