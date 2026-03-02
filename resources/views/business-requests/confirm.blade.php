<x-app-layout>
    <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg p-10 border border-gray-200">

                <h1 class="text-2xl font-bold text-center mb-8">
                    業務依頼書・連絡書
                </h1>

                <!-- Step Indicator -->
                <div class="flex justify-center items-center mb-10 text-sm">
                    <span class="text-gray-400">Step 1: 入力</span>
                    <span class="mx-4 text-gray-400">→</span>
                    <span class="text-indigo-600 font-bold border-b-2 border-indigo-600 pb-1">
                        Step 2: 確認
                    </span>
                    <span class="mx-4 text-gray-400">→</span>
                    <span class="text-gray-400">Step 3: 完了</span>
                </div>

                <div class="space-y-6 text-sm">
                    <div class="grid md:grid-cols-2 gap-6 mb-8 bg-gray-50 p-6 rounded-lg border">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">依頼番号</label>
                                    <p class="text-lg font-bold text-indigo-700">{{ $nextNumber }}</p>
                                    <input type="hidden" name="request_number" value="{{ $nextNumber }}">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">作成日</label>
                                    <p class="text-sm font-medium">{{ date('Y/m/d') }}</p>
                                </div>
                     </div>
                    <div>
                        <strong>件名:</strong>
                        <div>{{ $data['title'] }}</div>
                    </div>

                    <div>
                        <strong>対象部署:</strong>
                        <div>{{ $department->name }}</div>
                    </div>

                    <div>
                        <strong>期日:</strong>
                        <div>{{ $data['due_date'] }}</div>
                    </div>

                    <div>
                        <strong>業務区分:</strong>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($categories as $cat)
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs">
                                    {{ $cat->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <strong>詳細内容:</strong>
                        <div class="bg-gray-50 p-4 rounded mt-2 whitespace-pre-line">
                            {{ $data['content'] }}
                        </div>
                    </div>

                    @if(session('notes'))
                        <div>
                            <strong>特記事項:</strong>
                            <div>{{ session('notes') }}</div>
                        </div>
                    @endif

                  <div class="mb-4">
                        <label class="block font-bold">添付ファイル:</label>
                        @if(count($storedFiles) > 0)
                            <ul class="list-disc ml-5">
                                @foreach($storedFiles as $file)
                                    <li>{{ $file['name'] }}</li>
                                    <input type="hidden" name="attachment_paths[]" value="{{ $file['path'] }}">
                                    <input type="hidden" name="attachment_names[]" value="{{ $file['name'] }}">
                                @endforeach
                            </ul>
                        @else
                            <p>なし</p>
                        @endif
                    </div>

                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-4 border-t pt-8 mt-10">

                    <a href="{{ route('business-requests.create') }}" 
                        class="px-6 py-2 border border-gray-400 bg-white rounded-md text-center inline-block">
                            戻る
                    </a>

                    <form method="POST" action="{{ route('business-requests.complete') }}" enctype="multipart/form-data">
                       @csrf

                    <input type="hidden" name="request_number" value="{{ $nextNumber }}">
                <input type="hidden" name="notes" value="{{ session('notes') }}">
                
                    @foreach($data as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                       
                         <button type="submit"
                            class="px-8 py-2 bg-indigo-600 text-white rounded-md">
                            送信する
                        </button>

                    </form>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>