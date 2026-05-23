<x-app-layout>

@php
$statusConfig = [
    'PENDING'   => ['label'=>'承認待ち', 'bg'=>'bg-amber-50', 'text'=>'text-amber-700', 'bar'=>'bg-amber-400'],
    'APPROVED'  => ['label'=>'承認済み', 'bg'=>'bg-emerald-50', 'text'=>'text-emerald-700', 'bar'=>'bg-emerald-500'],
    'REJECTED'  => ['label'=>'却下', 'bg'=>'bg-rose-50', 'text'=>'text-rose-700', 'bar'=>'bg-rose-500'],
    'WORKING'   => ['label'=>'作業中', 'bg'=>'bg-sky-50', 'text'=>'text-sky-700', 'bar'=>'bg-sky-500'],
    'COMPLETED' => ['label'=>'完了', 'bg'=>'bg-slate-100', 'text'=>'text-slate-600', 'bar'=>'bg-slate-400'],
];

$config = $statusConfig[$businessRequest->status];

$specialNote = $businessRequest->requestContent?->special_note ?? $businessRequest->special_note;
@endphp

<div class="min-h-screen bg-[#f4f6f9] py-6 px-4">

    {{-- CONTAINER --}}
    <div class="max-w-[1180px] mx-auto grid grid-cols-12 gap-5 items-start">

        {{-- MAIN --}}
        <div class="col-span-12 xl:col-span-8">

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">

                {{-- TOP BAR --}}
                <div class="h-1 {{ $config['bar'] }}"></div>

                {{-- HEADER --}}
                <div class="px-6 py-5 border-b border-slate-100">

                    <div class="flex items-start justify-between gap-4">

                        <div class="min-w-0">

                            <div class="flex items-center gap-2 mb-3">

                                <span class="px-2 py-1 rounded-md bg-slate-100 text-slate-500 text-[10px] font-bold tracking-widest uppercase">
                                    #{{ $businessRequest->request_number }}
                                </span>

                                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $config['bar'] }}"></div>

                                    <span class="text-[10px] font-black tracking-wider">
                                        {{ $config['label'] }}
                                    </span>
                                </div>

                            </div>

                            <h1 class="text-[26px] font-black tracking-tight text-slate-900 leading-snug">
                                {{ $businessRequest->title }}
                            </h1>

                        </div>

                    </div>

                    {{-- INFO --}}
                    <div class="grid grid-cols-3 gap-3 mt-5">

                        <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                            <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">
                                申請者
                            </p>

                            <p class="mt-1 text-sm font-bold text-slate-800">
                                {{ $businessRequest->user?->name }}
                            </p>
                        </div>

                        <div class="bg-blue-50 rounded-xl px-4 py-3 border border-blue-100">
                            <p class="text-[10px] text-blue-400 font-bold tracking-widest uppercase">
                                部署
                            </p>

                            <p class="mt-1 text-sm font-bold text-blue-700">
                                {{ $businessRequest->targetDepartment?->name }}
                            </p>
                        </div>

                        <div class="bg-rose-50 rounded-xl px-4 py-3 border border-rose-100">
                            <p class="text-[10px] text-rose-400 font-bold tracking-widest uppercase">
                                納期
                            </p>

                            <p class="mt-1 text-sm font-black text-rose-600">
                                {{ $businessRequest->due_date }}
                            </p>
                        </div>

                    </div>

                </div>

                {{-- BODY --}}
                <div class="p-6 space-y-7">

                    {{-- CONTENT --}}
                    <section>

                        <div class="flex items-center gap-3 mb-3">

                            <h3 class="text-[10px] font-black tracking-[0.2em] uppercase text-slate-400">
                                依頼詳細
                            </h3>

                            <div class="flex-1 h-px bg-slate-100"></div>

                        </div>

                        <div class="text-[14px] leading-[2] text-slate-700 whitespace-pre-line">
                            {{ $businessRequest->requestContent?->description ?? $businessRequest->content }}
                        </div>

                    </section>

                    {{-- SPECIAL NOTE --}}
                    @if($specialNote)
                    <section class="rounded-2xl border border-amber-200 bg-gradient-to-r from-amber-50 to-orange-50 overflow-hidden">

                        <div class="flex items-start gap-4 p-5">

                            <div class="w-10 h-10 rounded-xl bg-amber-100 border border-amber-200 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5 text-amber-600"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M13 16h-1v-4h-1m1-4h.01M12 8v.01" />
                                </svg>
                            </div>

                            <div class="flex-1">

                                <div class="mb-2">
                                    <p class="text-[10px] uppercase tracking-[0.2em] font-black text-amber-500">
                                        重要事項
                                    </p>

                                    <h4 class="text-sm font-black text-slate-800 mt-1">
                                        特記事項
                                    </h4>
                                </div>

                                <p class="text-[14px] leading-[1.9] text-slate-700 whitespace-pre-line">
                                    {{ $specialNote }}
                                </p>

                            </div>

                        </div>

                    </section>
                    @endif

                    {{-- ATTACHMENTS --}}
                   {{-- 添付ファイル --}}
{{-- 添付ファイル --}}
@if($businessRequest->attachments->count())
<section class="rounded-xl border border-slate-200 bg-slate-50/50 p-3">
    {{-- HEADER (Slimmed) --}}
    <div class="flex items-center gap-2 mb-2">
        <h3 class="text-[9px] font-black tracking-[0.1em] text-slate-400 uppercase whitespace-nowrap">
            添付ファイル ({{ $businessRequest->attachments->count() }})
        </h3>
        <div class="h-px flex-1 bg-slate-200/60"></div>
    </div>

    {{-- FILE GRID (Changed from space-y-2 to grid) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        @foreach($businessRequest->attachments as $file)
            @php
                $ext = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                $fileColor = match($ext) {
                    'pdf' => 'bg-rose-500',
                    'xls', 'xlsx' => 'bg-emerald-500',
                    'doc', 'docx' => 'bg-blue-500',
                    'ppt', 'pptx' => 'bg-orange-500',
                    default => 'bg-slate-500'
                };
            @endphp

            <a href="{{ asset('storage/'.$file->file_path) }}"
               target="_blank"
               class="group flex items-center gap-3 bg-white border border-slate-200 hover:border-indigo-300 hover:shadow-sm rounded-lg px-3 py-1.5 transition-all">
                
                {{-- MINIFIED ICON --}}
                <div class="shrink-0 w-7 h-9 rounded {{ $fileColor }} flex items-center justify-center relative overflow-hidden shadow-sm">
                    <span class="text-[8px] font-black text-white uppercase relative z-10">
                        {{ $ext }}
                    </span>
                    {{-- Fold Effect --}}
                    <div class="absolute top-0 right-0 w-2 h-2 bg-white/20 rotate-45 translate-x-1 -translate-y-1"></div>
                </div>

                {{-- INFO (Condensed) --}}
                <div class="min-w-0 flex-1">
                    <p class="text-[12px] font-bold text-slate-700 truncate group-hover:text-indigo-600 transition leading-none">
                        {{ $file->original_name }}
                    </p>
                    <p class="text-[9px] text-slate-400 font-medium mt-0.5 leading-none">
                        ダウンロード
                    </p>
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-300 group-hover:text-indigo-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 12l-4-4m4 4l4-4M4 20h16" />
                </svg>
            </a>
        @endforeach
    </div>
</section>
@endif

                </div>

            </div>

        </div>

        {{-- SIDEBAR --}}
        <div class="col-span-12 xl:col-span-4">

            <div x-data="{action:''}"
                 class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm sticky top-5">

                <div class="flex items-center gap-2 mb-5 pb-4 border-b border-slate-100">

                    <div class="w-2 h-2 rounded-full {{ $config['bar'] }}"></div>

                    <h3 class="text-sm font-black text-slate-800">
                        承認操作
                    </h3>

                </div>

                <form method="POST"
                      action="{{ route('business-requests.assign',$businessRequest->id) }}"
                      class="space-y-3">

                    @csrf

                    <input type="hidden" name="action" :value="action">

                    {{-- APPROVE --}}
                    <button type="button"
                            @click="action='approve'"
                            :class="action==='approve'
                            ? 'bg-emerald-600 border-emerald-600 text-white'
                            : 'bg-white border-slate-200 text-slate-700 hover:border-emerald-500 hover:text-emerald-600'"
                            class="w-full h-[54px] rounded-xl border-2 font-black text-sm tracking-wider transition-all flex items-center justify-center gap-2">

                        ✓ 承認する

                    </button>

                    {{-- REJECT --}}
                    <button type="button"
                            @click="action='reject'"
                            :class="action==='reject'
                            ? 'bg-rose-600 border-rose-600 text-white'
                            : 'bg-white border-slate-200 text-slate-700 hover:border-rose-500 hover:text-rose-600'"
                            class="w-full h-[54px] rounded-xl border-2 font-black text-sm tracking-wider transition-all flex items-center justify-center gap-2">

                        ✕ 却下する

                    </button>

                    {{-- DYNAMIC --}}
                    <div x-show="action !== ''"
                         x-transition
                         class="pt-4 space-y-4">

                        <template x-if="action==='approve'">

                            <div>
                                <label class="block text-[10px] font-black tracking-widest text-slate-400 uppercase mb-2">
                                    担当者
                                </label>

                                <select name="worker_id"
                                        class="w-full rounded-xl border-slate-200 text-sm py-3 focus:ring-emerald-500">

                                    <option value="">担当者を選択</option>

                                    @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">
                                        {{ $emp->name }}
                                    </option>
                                    @endforeach

                                </select>
                            </div>

                        </template>

                        <template x-if="action==='reject'">

                            <div>
                                <label class="block text-[10px] font-black tracking-widest text-slate-400 uppercase mb-2">
                                    却下理由
                                </label>

                                <textarea name="reason"
                                          rows="4"
                                          class="w-full rounded-xl border-slate-200 text-sm focus:ring-rose-500"
                                          placeholder="理由を入力してください"></textarea>
                            </div>

                        </template>

                        <button class="w-full h-[52px] rounded-xl bg-slate-900 hover:bg-indigo-600 text-white font-black text-sm tracking-widest transition-all">
                            確定する
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</x-app-layout>