<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen flex justify-center items-start">
        <div class="max-w-3xl w-full bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">

            {{-- ヘッダー: タイトルと管理番号 --}}
           {{-- ヘッダー: タイトルと管理番号 --}}
<div class="bg-slate-800 px-8 py-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-slate-400 text-[10px] font-bold tracking-widest uppercase mb-1">承認ポータル</p>
            <h2 class="text-xl font-bold tracking-tight">{{ $request->title }}</h2>
        </div>
        <div class="flex items-center gap-4">
            {{-- ステータスの日本語変換ロジック --}}
            @php
                $statusLabels = [
                    'PENDING'   => '承認待ち',
                    'APPROVED'  => '承認済み',
                    'REJECTED'  => '却下',
                    'WORKING'   => '作業中',
                    'COMPLETED' => '完了',
                ];
                $currentStatus = $request->status;
                $japaneseLabel = $statusLabels[$currentStatus] ?? $currentStatus;
            @endphp

            {{-- ステータスバッジ --}}
            <span class="px-3 py-1 rounded-full text-[10px] font-bold tracking-tighter uppercase border border-white/20 bg-white/10">
                 {{ $japaneseLabel }}
            </span>
            
            <a href="{{ route('business-requests.index') }}" class="text-slate-400 hover:text-white transition">
                <i data-lucide="x" class="w-6 h-6"></i>
            </a>
        </div>
    </div>
</div>

            <div class="p-8">
                {{-- 基本情報グリッド --}}
                <div class="grid grid-cols-2 gap-y-6 gap-x-8 mb-8 pb-8 border-b border-slate-100">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">依頼者</p>
                        <p class="text-sm font-semibold text-slate-700 flex items-center">
                            <i data-lucide="user" class="w-3.5 h-3.5 mr-2 text-slate-400"></i>
                            {{ $request->user?->name ?? '不明' }} 
                            <span class="ml-2 text-xs font-normal text-slate-400">({{ $request->user?->department?->name ?? '部署なし' }})</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">管理番号</p>
                        <p class="text-sm font-mono font-bold text-slate-800">{{ $request->request_number }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">希望納期</p>
                        <p class="text-sm font-semibold text-rose-600 flex items-center">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 mr-2"></i>
                            {{ $request->due_date }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">対象部署</p>
                        <p class="text-sm font-bold text-indigo-700 flex items-center">
                            <i data-lucide="building-2" class="w-3.5 h-3.5 mr-2"></i>
                            {{ $request->targetDepartment?->name ?? '未設定' }}
                        </p>
                    </div>
                    <div class="col-span-2">
                         <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">業務区分</p>
                         <div class="flex flex-wrap gap-2 mt-1">
                            @foreach($request->categories as $category)
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold border border-slate-200">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                         </div>
                    </div>
                </div>

             <div class="mb-8">
    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-widest mb-3 flex items-center">
        <i data-lucide="align-left" class="w-4 h-4 mr-2 text-indigo-500"></i>
        依頼内容詳細
    </h3>

    <div class="bg-slate-50 border border-slate-100 rounded-xl p-6 text-slate-700 leading-relaxed text-sm shadow-inner whitespace-pre-line font-medium">
        {{ trim($request->requestContent?->description ?? $request->content ?? '内容が登録されていません') }}
    </div>
</div>

                {{-- 特記事項 (FIXED & ADDED) --}}
                @php
                    $note = $request->requestContent?->special_note ?? $request->special_note;
                @endphp

                @if($note)
                    <div class="mb-8 bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-lg">
                        <h3 class="text-xs font-bold text-amber-700 uppercase tracking-widest mb-1 flex items-center">
                            <i data-lucide="alert-circle" class="w-4 h-4 mr-2"></i>
                            特記事項
                        </h3>
                        <p class="text-amber-800 text-sm font-medium leading-relaxed whitespace-pre-line">{{ $note }}</p>
                    </div>
                @endif

                {{-- 添付資料 --}}
                @if($request->attachments && $request->attachments->count() > 0)
                <div class="mb-8">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-widest mb-3 flex items-center">
                        <i data-lucide="paperclip" class="w-4 h-4 mr-2 text-slate-400"></i>
                        添付資料
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($request->attachments as $file)
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="flex items-center p-3 border border-slate-100 rounded-lg hover:bg-slate-50 hover:border-indigo-200 transition group bg-white shadow-sm">
                                <i data-lucide="file-text" class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 mr-3"></i>
                                <span class="text-[11px] text-slate-600 group-hover:text-indigo-700 font-bold truncate">{{ $file->original_name }}</span>
                                <i data-lucide="external-link" class="w-3 h-3 ml-auto text-slate-300"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- 意思決定エリア (Alpine.js) --}}
                <div x-data="{ selectedAction: '' }" class="space-y-4 pt-8 border-t border-slate-100">
                    <h3 class="text-center text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">判定を選択してください</h3>
                    
                    <form action="{{ route('business-requests.assign', $request->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" :value="selectedAction">

                        <div class="flex gap-3 max-w-md mx-auto">
                            {{-- 承認ボタン --}}
                            <button type="button" @click="selectedAction = 'approve'" 
                                    :class="selectedAction === 'approve' ? 'bg-emerald-600 ring-4 ring-emerald-100 shadow-md' : 'bg-emerald-500 hover:bg-emerald-600 shadow-sm'"
                                    class="flex-1 text-white py-2.5 rounded-lg font-bold transition-all flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                                <span class="text-xs tracking-wider">承認</span>
                            </button>

                            {{-- 却下ボタン --}}
                            <button type="button" @click="selectedAction = 'reject'" 
                                    :class="selectedAction === 'reject' ? 'bg-rose-600 ring-4 ring-rose-100 shadow-md' : 'bg-rose-500 hover:bg-rose-600 shadow-sm'"
                                    class="flex-1 text-white py-2.5 rounded-lg font-bold transition-all flex items-center justify-center">
                                <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
                                <span class="text-xs tracking-wider">却下</span>
                            </button>
                        </div>

                        {{-- 動的入力フォーム --}}
                        <div class="mt-4">
                            {{-- 承認時：担当者割り当て --}}
                            <div x-show="selectedAction === 'approve'" x-cloak x-transition class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl shadow-inner">
                                <label class="block text-[10px] font-bold text-emerald-700 uppercase tracking-widest mb-2">担当者を割り当ててください</label>
                                <select name="worker_id" class="w-full border-emerald-200 rounded-lg text-xs focus:ring-emerald-500 focus:border-emerald-500 shadow-sm" :required="selectedAction === 'approve'">
                                    <option value="">-- 担当者を選択 --</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 却下時：理由入力 --}}
                            <div x-show="selectedAction === 'reject'" x-cloak x-transition class="p-4 bg-rose-50 border border-rose-100 rounded-xl shadow-inner">
                                <label class="block text-[10px] font-bold text-rose-700 uppercase tracking-widest mb-2 text-center">却下理由を入力してください</label>
                                <textarea name="reason" rows="2" class="w-full border-rose-200 rounded-lg text-xs focus:ring-rose-500 focus:border-rose-500 placeholder-rose-300" placeholder="例：予算不足のため、構成図の修正が必要など..." :required="selectedAction === 'reject'"></textarea>
                            </div>
                        </div>

                        {{-- 確定ボタン --}}
                        <div class="mt-6 flex gap-2" x-show="selectedAction !== ''" x-cloak>
                            <button type="submit" class="flex-[2] bg-slate-800 text-white py-3 rounded-lg text-sm font-bold hover:bg-slate-900 transition-all shadow-md active:scale-[0.98]">
                                <span x-text="selectedAction === 'approve' ? '承認を確定する' : '却下を確定する'"></span>
                            </button>
                            <button type="button" @click="selectedAction = ''" class="flex-1 bg-white border border-slate-200 text-slate-500 py-3 rounded-lg text-xs font-bold hover:bg-slate-50 transition">
                                キャンセル
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>