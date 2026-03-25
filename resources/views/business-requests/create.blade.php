<x-app-layout>
    @section('header_title', '業務依頼作成')
    <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-5xl mx-auto">
            
            <div class="bg-white rounded-2xl shadow-lg p-10 border border-gray-200">

                <!-- Title -->
                <h1 class="text-2xl font-bold text-center text-gray-800 mb-8">
                    業務依頼書
                </h1>

                <!-- Step Indicator -->
                <div class="flex justify-center items-center mb-10 text-sm">
                    <div class="flex items-center space-x-4">
                        <span class="text-indigo-600 font-bold border-b-2 border-indigo-600 pb-1">
                            Step 1: 入力
                        </span>
                        <span class="text-gray-400">→</span>
                        <span class="text-gray-400">Step 2: 確認</span>
                        <span class="text-gray-400">→</span>
                        <span class="text-gray-400">Step 3: 完了</span>
                    </div>
                </div>

               <form action="{{ route('business-requests.confirm') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="grid md:grid-cols-2 gap-6 mb-12">
       <div>
    <label class="block text-xs font-semibold text-gray-500 mb-1">依頼番号（自動）</label>
    <input type="text" name="request_number" value="{{ $nextNumber }}"
        class="w-full bg-gray-100 border border-gray-300 rounded-md px-4 py-2 text-sm"
        readonly> </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">作成日</label>
            <input type="text" value="{{ date('Y/m/d') }}"
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
                    value="{{ old('title', session('form_data.title')) }}"
                    class="w-full border-gray-300 rounded-md text-sm @error('title') border-red-500 @enderror" 
                    placeholder="依頼内容の要約を入力" required>
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm mb-2">依頼者名</label>
                    <input type="text" value="{{ auth()->user()?->name }}"
                        class="w-full bg-gray-100 border border-gray-300 rounded-md text-sm" readonly>
                </div>

                <div>
                    <label class="block text-sm mb-2">部署</label>
                    <input type="text" value="{{ Auth::user()->department?->name ?? '所属部署' }}" 
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
                    <option value="" disabled {{ !old('department_id', session('form_data.department_id')) ? 'selected' : '' }}>部署を選択してください</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" 
                            {{ old('department_id', session('form_data.department_id')) == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-2">期日 <span class="text-red-500">*</span></label>
                <input type="date" name="due_date"
                    value="{{ old('due_date', session('form_data.due_date')) }}"
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
                        // check there is array for checkbox
                        $selectedCats = old('categories', session('form_data.categories', []));
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
            <textarea name="content" rows="5"
                class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="詳細な手順や要件を入力してください" required>{{ old('content', session('form_data.content')) }}</textarea>
        </div>

        <div>
            <label class="block text-sm mb-2">特記事項</label>
            <input type="text" name="notes"
                value="{{ old('notes', session('form_data.notes')) }}"
                class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="備考があれば入力">
        </div>
    </div>

     <div class="mb-12">
    <div class="flex items-center justify-between mb-4 border-b pb-2">
        <h3 class="text-sm font-black text-slate-700 tracking-wide flex items-center">
            <i data-lucide="paperclip" class="w-4 h-4 mr-2 text-indigo-500"></i>
            添付ファイル / ATTACHMENTS
        </h3>
        <span class="text-[10px] text-slate-400 font-medium">MAX 10MB per file</span>
    </div>
    
    <div class="relative group">
        {{-- Hidden Input - Triggered by clicking the div --}}
        <input type="file" name="attachments[]" id="file-upload" multiple 
               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png,.dat"
               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-10 text-center bg-slate-50 group-hover:bg-white group-hover:border-indigo-400 group-hover:shadow-xl group-hover:shadow-indigo-50 transition-all duration-300">
            <div class="bg-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-slate-100 group-hover:scale-110 transition-transform">
                <i data-lucide="upload-cloud" class="w-6 h-6 text-indigo-500"></i>
            </div>
            <p class="text-sm font-bold text-slate-700">クリックまたはドラッグ＆ドロップ</p>
            <p class="text-xs text-slate-400 mt-1">PDF, Word, Excel, Image (Max 5 files)</p>
        </div>
    </div>

    {{-- Selected File List --}}
    @if(session('storedFiles'))
        <div class="mt-4 animate-in fade-in slide-in-from-top-2">
            <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-4">
                <div class="flex items-center mb-3">
                    <span class="flex h-2 w-2 rounded-full bg-indigo-500 mr-2"></span>
                    <p class="text-xs font-bold text-indigo-900">選択済みファイル ({{ count(session('storedFiles')) }})</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                   @foreach(session('storedFiles') as $index => $file)
    <div id="file-row-{{ $index }}" class="flex items-center justify-between bg-white p-2.5 rounded-lg border border-indigo-100 shadow-sm">
        <div class="flex items-center overflow-hidden">
            <i data-lucide="file-text" class="w-4 h-4 text-slate-400 mr-2 shrink-0"></i>
            <span class="text-xs font-medium text-slate-600 truncate max-w-[180px]">
                {{ $file['name'] }}
            </span>
        </div>

        {{-- Use a normal button with type="button" to prevent form submission --}}
        <button type="button" 
                onclick="removeFile({{ $index }})"
                class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-md transition-all">
            <i data-lucide="x-circle" class="w-4 h-4"></i>
        </button>
    </div>
@endforeach
                </div>

                <div class="mt-4 pt-3 border-t border-indigo-100 flex items-start gap-2">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-indigo-400 shrink-0 mt-0.5"></i>
                    <p class="text-[10px] text-indigo-400 italic leading-relaxed">
                        新しいファイルを選択すると既存のリストは更新されます。変更が不要な場合はそのまま進んでください。
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

    <div class="flex justify-end space-x-4 border-t pt-8">
        <a href="{{ route('business-requests.requests') }}"
            class="px-6 py-2 border border-gray-400 bg-white text-gray-700 rounded-md hover:bg-gray-100 transition">
            キャンセル
        </a>

        <button type="submit"
            class="px-8 py-2 bg-indigo-600 text-white rounded-md font-semibold shadow hover:bg-indigo-700 transition">
            確認画面へ進む
        </button>
    </div>
</form>
            </div>
        </div>
    </div>
    <script>
function removeFile(index) {
    if (!confirm('このファイルを削除しますか？ (Delete this file?)')) return;

    axios.post("{{ route('file.remove') }}", {
        index: index,
        _token: "{{ csrf_token() }}"
    })
    .then(response => {
        // Remove the file element from the UI without refreshing
        const element = document.getElementById(`file-row-${index}`);
        element.classList.add('opacity-0', 'scale-95'); // Smooth fade out
        setTimeout(() => element.remove(), 300);
    })
    .catch(error => {
        alert('エラーが発生しました。 (An error occurred)');
        console.error(error);
    });
}
</script>
</x-app-layout>