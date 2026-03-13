<x-app-layout>
    <div class="py-12 bg-slate-100 min-h-screen flex justify-center items-start">
        <div class="max-w-2xl w-full bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">

            {{-- Header: Professional & Subtle --}}
            <div class="bg-[#001a4d] px-8 py-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-blue-200 text-xs font-bold tracking-widest uppercase mb-1">Approval Portal</p>
                        <h2 class="text-xl font-bold">{{ $request->title }}</h2>
                    </div>
                    <a href="{{ route('business-requests.index') }}" class="text-white/50 hover:text-white transition">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </a>
                </div>
            </div>

            <div class="p-8">
                {{-- Info Grid: Fast Scanning --}}
                <div class="grid grid-cols-2 gap-6 mb-8 pb-8 border-b border-slate-100">
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">依頼者 / Requester</p>
                        <p class="text-sm font-semibold text-slate-700 flex items-center">
                            <i data-lucide="user" class="w-3.5 h-3.5 mr-2 text-slate-400"></i>
                            {{ $request->user?->name }} 
                            <span class="ml-2 text-xs font-normal text-slate-400">({{ $request->user?->department?->name }})</span>
                        </p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">管理番号 / Ref No.</p>
                        <p class="text-sm font-mono font-semibold text-slate-700">{{ $request->request_number }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">期限 / Deadline</p>
                        <p class="text-sm font-semibold text-rose-600 flex items-center">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 mr-2"></i>
                            {{ $request->due_date }}
                        </p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-tight">対象部署 / Target Dept</p>
                        <p class="text-sm font-bold text-indigo-700 flex items-center">
                            <i data-lucide="building-2" class="w-3.5 h-3.5 mr-2"></i>
                            {{ $request->targetDepartment?->name ?? '未設定' }}
                        </p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">現在の状態 / Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200 uppercase">
                            Pending Approval
                        </span>
                    </div>
                </div>

                {{-- Description Block: High Readability --}}
                <div class="mb-8">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-widest mb-3 flex items-center">
                        <i data-lucide="align-left" class="w-4 h-4 mr-2 text-indigo-500"></i>
                        依頼内容詳細 (Request Details)
                    </h3>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-slate-600 leading-relaxed text-sm shadow-inner">
                        {!! nl2br(e($request->requestContent->body ?? '内容なし')) !!}
                    </div>
                </div>

                {{-- Attachment Section: Important for Managers --}}
                @if($request->attachments->count() > 0)
                <div class="mb-8">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-widest mb-3">添付資料 (Attachments)</h3>
                    <div class="grid grid-cols-1 gap-2">
                        @foreach($request->attachments as $file)
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="flex items-center p-3 border border-slate-100 rounded-lg hover:bg-indigo-50 hover:border-indigo-200 transition group">
                                <i data-lucide="file-text" class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 mr-3"></i>
                                <span class="text-xs text-slate-600 group-hover:text-indigo-700 font-medium">{{ $file->original_name }}</span>
                                <i data-lucide="external-link" class="w-3 h-3 ml-auto text-slate-300"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Interactive Decision Area --}}
                <div x-data="{ selectedAction: '' }" class="space-y-4 pt-6 border-t border-slate-100">
                    <form action="{{ route('business-requests.assign', $request->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" :value="selectedAction">

                        <div class="flex gap-4">
                            <button type="button" @click="selectedAction = 'approve'" 
                                    :class="selectedAction === 'approve' ? 'bg-emerald-600 ring-4 ring-emerald-100' : 'bg-emerald-500 hover:bg-emerald-600'"
                                    class="flex-1 text-white py-3 rounded-xl font-bold transition-all flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i> 承認する
                            </button>

                            <button type="button" @click="selectedAction = 'reject'" 
                                    :class="selectedAction === 'reject' ? 'bg-rose-600 ring-4 ring-rose-100' : 'bg-rose-500 hover:bg-rose-600'"
                                    class="flex-1 text-white py-3 rounded-xl font-bold transition-all flex items-center justify-center">
                                <i data-lucide="x-circle" class="w-5 h-5 mr-2"></i> 却下する
                            </button>
                        </div>

                        {{-- Conditional Form Sections --}}
                        <div class="mt-6">
                            <div x-show="selectedAction === 'approve'" x-transition class="p-6 bg-slate-50 border border-slate-200 rounded-2xl">
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-3">担当者をアサインしてください</label>
                                <select name="worker_id" class="w-full border-slate-200 rounded-lg text-sm focus:ring-indigo-500" :required="selectedAction === 'approve'">
                                    <option value="">-- 担当者を選択 --</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="selectedAction === 'reject'" x-transition class="p-6 bg-rose-50 border border-rose-100 rounded-2xl">
                                <label class="block text-xs font-bold text-rose-700 uppercase mb-3">却下理由を入力してください</label>
                                <textarea name="reason" class="w-full border-rose-200 rounded-lg text-sm focus:ring-rose-500" placeholder="理由を入力してください..." :required="selectedAction === 'reject'"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3" x-show="selectedAction !== ''">
                            <button type="submit" class="flex-[2] bg-[#001a4d] text-white py-3 rounded-xl font-bold hover:bg-black transition shadow-lg">
                                実行を確定する (Confirm Execution)
                            </button>
                            <button type="button" @click="selectedAction = ''" class="flex-1 bg-white border border-slate-200 text-slate-500 py-3 rounded-xl font-bold hover:bg-slate-50 transition">
                                キャンセル
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>