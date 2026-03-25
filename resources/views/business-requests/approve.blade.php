<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen flex justify-center items-start">
        <div class="max-w-4xl w-full bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">

            {{-- Header: Refined, Document Style --}}
             <div class="h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-500"></div>
            <div class="bg-white border-b border-slate-100 px-10 py-8">
                
                <div class="flex justify-between items-start">
                    <div class="space-y-1.5 min-w-0">
                        <div class="flex items-center gap-2.5">
                            <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border border-indigo-100">
                                REQUEST ID: {{ $request->request_number }}
                            </span>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-950 tracking-tight leading-snug truncate">
                            {{ $request->title }}
                        </h2>
                    </div>

                    <div class="flex items-center gap-3">
                        @php
                            $statusConfig = [
                                'PENDING'   => ['label' => '承認待ち', 'color' => 'bg-amber-50 text-amber-700 border-amber-100'],
                                'APPROVED'  => ['label' => '承認済み', 'color' => 'bg-emerald-50 text-emerald-700 border-emerald-100'],
                                'REJECTED'  => ['label' => '却下', 'color' => 'bg-rose-50 text-rose-700 border-rose-100'],
                                'WORKING'   => ['label' => '作業中', 'color' => 'bg-sky-50 text-sky-700 border-sky-100'],
                                'COMPLETED' => ['label' => '完了', 'color' => 'bg-slate-100 text-slate-700 border-slate-200'],
                            ];
                            $config = $statusConfig[$request->status] ?? ['label' => $request->status, 'color' => 'bg-slate-100 text-slate-700 border-slate-200'];
                        @endphp

                        <span class="px-3.5 py-1.5 rounded-full text-[11px] font-bold border {{ $config['color'] }}">
                             {{ $config['label'] }}
                        </span>
                        
                        <a href="{{ route('business-requests.my_tasks') }}" class="p-2 rounded-xl hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-10">
                {{-- Info Bar: Metadata with Icons --}}
                <div class="flex items-center gap-12 mb-10 pb-6 border-b border-slate-100">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">依頼者</label>
                        <p class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                             {{-- <div class="w-6 h-6 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-[11px] font-bold text-slate-500">
                                {{ mb_substr($request->user?->name ?? '?', 0, 1) }}
                            </div> --}}
                            {{ $request->user?->name ?? '不明' }}
                        </p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">希望納期</label>
                        <p class="text-sm font-bold text-rose-600 flex items-center gap-1.5">
                            <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                            {{ $request->due_date }}
                        </p>
                    </div>
                    <div class="space-y-1 ml-auto text-right">
                        <label class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">対象部署</label>
                        <p class="text-sm font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-100">
                            {{ $request->targetDepartment?->name ?? '未設定' }}
                        </p>
                    </div>
                </div>

                {{-- Content Sections --}}
                <div class="space-y-10">
                    {{-- 依頼内容 --}}
                    <section>
                        <h3 class="flex items-center text-xs font-bold text-slate-800 uppercase tracking-[0.15em] mb-4">
                            <span class="w-8 h-[1.5px] bg-indigo-400 mr-3"></span>
                            依頼内容詳細
                        </h3>
                        <div class="bg-slate-50 border border-slate-100 rounded-3xl p-8 text-slate-800 leading-relaxed text-[15px] whitespace-pre-line shadow-inner ring-1 ring-white/10">
                            {{ trim($request->requestContent?->description ?? $request->content ?? '内容が登録されていません') }}
                        </div>
                    </section>

                    {{-- 特記事項 & 添付资料 (Side-by-side if both exist) --}}
                    <div class="grid grid-cols-2 gap-8 items-start">
                        {{-- 特記事項 --}}
                        @php $note = $request->requestContent?->special_note ?? $request->special_note; @endphp
                        @if($note)
                        <section class="bg-amber-50/70 border border-amber-100/70 rounded-2xl p-6 h-full">
                            <h3 class="text-xs font-bold text-amber-700 uppercase tracking-widest mb-2.5 flex items-center">
                                <i data-lucide="sticky-note" class="w-4 h-4 mr-2"></i>
                                特記事項
                            </h3>
                            <p class="text-amber-900/80 text-sm font-medium leading-relaxed whitespace-pre-line">{{ $note }}</p>
                        </section>
                        @endif

                        {{-- 添付資料 --}}
                        @if($request->attachments && $request->attachments->count() > 0)
                        <section class="h-full">
                            <h3 class="flex items-center text-xs font-bold text-slate-800 uppercase tracking-widest mb-3">
                                <i data-lucide="paperclip" class="w-4 h-4 text-slate-400 mr-2"></i>
                                添付資料 ({{ $request->attachments->count() }})
                            </h3>
                            <div class="space-y-2.5">
                                @foreach($request->attachments as $file)
                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="flex items-center justify-between p-3.5 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 hover:shadow-sm transition-all group">
                                        <div class="flex items-center min-w-0">
                                            <i data-lucide="file" class="w-4 h-4 text-slate-400 group-hover:text-indigo-600"></i>
                                            <span class="ml-3 text-xs font-semibold text-slate-600 group-hover:text-indigo-900 truncate">{{ $file->original_name }}</span>
                                        </div>
                                        <i data-lucide="external-link" class="w-3.5 h-3.5 text-slate-300 group-hover:text-indigo-400 ml-2"></i>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                        @endif
                    </div>
                </div>

                {{-- Unified Decision/Approval Form Section (Beautiful & Small Buttons) --}}
                <div x-data="{ selectedAction: '' }" class="mt-12 pt-10 border-t border-slate-100 max-w-2xl mx-auto">
                    <form action="{{ route('business-requests.assign', $request->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" :value="selectedAction">

                        {{-- Small & Centered Choice Buttons --}}
                        <div class="flex items-center justify-center gap-3 mb-8">
                            {{-- Approve --}}
                            <button type="button" @click="selectedAction = 'approve'" 
                                    :class="selectedAction === 'approve' ? 'bg-sky-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                    class="text-xs px-6 py-2 rounded-full font-bold transition-all flex items-center justify-center tracking-wider">
                                <i data-lucide="check" class="w-3.5 h-3.5 mr-1.5"></i>
                                承認
                            </button>

                            {{-- Reject --}}
                            <button type="button" @click="selectedAction = 'reject'" 
                                    :class="selectedAction === 'reject' ? 'bg-rose-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                    class="text-xs px-6 py-2 rounded-full font-bold transition-all flex items-center justify-center tracking-wider">
                                <i data-lucide="ban" class="w-3.5 h-3.5 mr-1.5"></i>
                                却下
                            </button>
                        </div>

                        {{-- Soft & Beautiful Dynamic Input Form --}}
                        <div class="space-y-4">
                            <div x-show="selectedAction === 'approve'" x-cloak x-transition class="p-6 bg-white border border-slate-100 rounded-3xl shadow-lg shadow-sky-50/50">
                                <label class="block text-[10px] font-bold text-sky-800 uppercase tracking-widest mb-3 text-center">作業担当者を指定してください</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i data-lucide="user-plus" class="w-4 h-4 text-slate-400"></i>
                                    </div>
                                    <select name="worker_id" class="w-full border-slate-100 bg-slate-50/50 rounded-xl text-xs py-3 pl-11 focus:ring-sky-500 focus:border-sky-500 shadow-inner" :required="selectedAction === 'approve'">
                                        <option value="">担当者を選択してください...</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div x-show="selectedAction === 'reject'" x-cloak x-transition class="p-6 bg-white border border-slate-100 rounded-3xl shadow-lg shadow-rose-50/50">
                                <label class="block text-[10px] font-bold text-rose-800 uppercase tracking-widest mb-3 text-center">却下理由</label>
                                <textarea name="reason" rows="3" class="w-full border-slate-100 bg-slate-50/50 rounded-xl text-xs focus:ring-rose-500 focus:border-rose-500 placeholder-rose-300 shadow-inner" placeholder="依頼者への修正指示を入力してください..." :required="selectedAction === 'reject'"></textarea>
                            </div>
                        </div>

                        {{-- Confirmation Action --}}
                        <div class="mt-8 flex items-center justify-center gap-3" x-show="selectedAction !== ''" x-cloak>
                             <button type="submit" class="bg-indigo-600 text-white text-[11px] px-8 py-2.5 rounded-full font-bold hover:bg-indigo-700 transition-all shadow-lg active:transform active:scale-95 tracking-wide">
                                <span x-text="selectedAction === 'approve' ? '承認を確定する' : '却下を確定する'"></span>
                            </button>
                            <button type="button" @click="selectedAction = ''" class="text-slate-400 text-[10px] font-bold hover:text-rose-600 transition">
                                キャンセル
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>