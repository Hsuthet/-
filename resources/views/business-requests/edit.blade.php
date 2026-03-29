<x-app-layout>
    <form id="delete-file-form" action="{{ route('business-requests.file.remove') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="file_id" id="delete-file-id">
</form>

<script>
function deleteFile(fileId) {
    if (confirm('削除しますか？')) {
        const form = document.getElementById('delete-file-form');
        const input = document.getElementById('delete-file-id');
        input.value = fileId;
        form.submit();
    }
}
</script>
    <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-5xl mx-auto">
            
            <div class="bg-white rounded-2xl shadow-lg p-10 border border-gray-200">

                <h1 class="text-2xl font-bold text-center text-gray-800 mb-8">
                    業務依頼書(編集)
                </h1>

               
                     <form action="{{ route('business-requests.update', ['business_request' => $businessRequest->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') 

                    <div class="grid md:grid-cols-2 gap-6 mb-12">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">依頼番号</label>
                            <input type="text" name="request_number" value="{{ $businessRequest->request_number }}"
                                class="w-full bg-gray-100 border border-gray-300 rounded-md px-4 py-2 text-sm"
                                readonly>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">作成日</label>
                            <input type="text" value="{{ $businessRequest->created_at->format('Y/m/d') }}"
                                class="w-full bg-gray-100 border border-gray-300 rounded-md px-4 py-2 text-sm"
                                readonly>
                        </div>
                    </div>

                    <div class="mb-12">
                        <h3 class="text-sm font-bold text-gray-700 mb-4 border-b pb-2">【 依頼者情報 】</h3>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm mb-2">件名 <span class="text-red-500">*</span></label>
                                <input type="text" name="title"
                                    value="{{ old('title', $businessRequest->title) }}"
                                    class="w-full border-gray-300 rounded-md text-sm @error('title') border-red-500 @enderror" 
                                    placeholder="依頼内容の要約を入力" required>
                                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm mb-2">依頼者名</label>
                                    <input type="text" value="{{ $businessRequest->user->name }}"
                                        class="w-full bg-gray-100 border border-gray-300 rounded-md text-sm" readonly>
                                </div>

                                <div>
                                    <label class="block text-sm mb-2">部署</label>
                                    <input type="text" value="{{ $businessRequest->user->department?->name ?? '所属部署' }}" 
                                        class="w-full bg-gray-100 border border-gray-300 rounded-md text-sm" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-12">
                        <h3 class="text-sm font-bold text-gray-700 mb-4 border-b pb-2">【 依頼先情報 】</h3>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm mb-2">対象部署 <span class="text-red-500">*</span></label>
                                <select name="department_id"
                                    class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" 
                                            {{ old('department_id', $businessRequest->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm mb-2">期日 <span class="text-red-500">*</span></label>
                                <input type="date" name="due_date"
                                    value="{{ old('due_date', $businessRequest->due_date) }}"
                                    class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-12">
                        <h3 class="text-sm font-bold text-gray-700 mb-4 border-b pb-2">【 業務内容 】</h3>

                        <div class="mb-6">
                            <label class="block text-sm mb-3">業務区分 <span class="text-red-500">*</span></label>
                            <div class="grid md:grid-cols-4 gap-4">
                                @foreach($categories as $cat)
                                    @php
                                        // show categories from database as array
                                        $dbCats = $businessRequest->categories->pluck('id')->toArray();
                                        $selectedCats = old('categories', $dbCats);
                                    @endphp
                                    <label class="flex items-center space-x-2 bg-gray-50 px-3 py-2 rounded-md border cursor-pointer hover:border-indigo-400">
                                        <input type="checkbox" name="categories[]" value="{{ $cat->id }}"
                                            {{ in_array($cat->id, (array)$selectedCats) ? 'checked' : '' }}
                                            class="rounded text-indigo-600 border-gray-300">
                                        <span class="text-sm">{{ $cat->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                       <div class="mb-6">
                            <label class="block text-sm mb-2">詳細内容 <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="5"
                                class="w-full border-gray-300 rounded-md text-sm"
                                required>{{ old('description', $businessRequest->requestContent?->description) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm mb-2">特記事項</label>
                            <input type="text" name="special_note"
                                {{-- Use curly braces for column names with dashes --}}
                                value="{{ old('special_note', $businessRequest->requestContent?->{'special_note'}) }}"
                                class="w-full border-gray-300 rounded-md text-sm"
                                placeholder="備考があれば入力">
                        </div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<div class="mb-12">
    <h3 class="text-sm font-bold text-gray-700 mb-4 border-b pb-2">【 添付ファイル 】</h3>
    
    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50">
        <input type="file" name="attachments[]" multiple id="file-input"
               class="text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200">

        <div id="new-files-preview" class="mt-4 text-left space-y-2"></div>
    </div>
</div>

<script>
function removeExistingFile(fileId) {
    if (!confirm('このファイルを完全に削除しますか？')) return;

    // Use Axios to delete the file in the background
    axios.post("{{ route('business-requests.file.remove') }}", {
        file_id: fileId,
        _token: "{{ csrf_token() }}"
    })
    .then(response => {
        // Find the element and animate it out
        const row = document.getElementById(`file-row-${fileId}`);
        if (row) {
            row.style.opacity = '0';
            row.style.transform = 'translateX(20px)';
            setTimeout(() => row.remove(), 300);
        }
    })
    .catch(error => {
        console.error(error);
        alert('削除に失敗しました。');
    });
}
</script>

                    <div class="flex justify-end space-x-4 border-t pt-8">
                        <a href="{{ route('business-requests.requests') }}"
                            class="px-6 py-2 border border-gray-400 bg-white text-gray-700 rounded-md hover:bg-gray-100 transition">
                            キャンセル
                        </a>

                        <button type="submit" name="action" value="submit"
                        class="px-8 py-2 bg-green-600 text-white rounded-md font-semibold shadow hover:bg-green-700 transition">
                        更新する
                    </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>