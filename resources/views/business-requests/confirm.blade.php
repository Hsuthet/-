<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-12">
        <div class="max-w-4xl mx-auto px-4">
            
            {{-- 1. STEPPER SECTION (Moved Outside the Card) --}}
            <div class="flex justify-center items-center mb-10 text-sm">
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-400">Step 1: 入力</span>
                        <span class="text-gray-400">→</span>
                        
                        <span class="text-indigo-600 font-bold border-b-2 border-indigo-600 pb-1">
                            Step 2: 確認
                        </span>
                        <span class="text-gray-400">→</span>
                        <span class="text-gray-400">Step 3: 完了</span>
                    </div>
                </div>

            {{-- 2. MAIN DOCUMENT CARD --}}
            <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200/50 overflow-hidden border border-slate-100 relative">
                
                {{-- Top Accent Bar --}}
                <div class="h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-500"></div>

                {{-- Header Section --}}
                <div class="px-10 py-12 bg-slate-50/50 border-b border-dashed border-slate-200 flex justify-between items-start">
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="px-2 py-0.5 bg-blue-600 text-[10px] font-bold text-white rounded uppercase tracking-wider">草稿レビュー</span>
                            <span class="text-slate-400 text-xs font-medium">業務依頼確認書</span>
                        </div>
                        <h1 class="text-3xl font-black text-slate-800 tracking-tight">内容を確認してください</h1>
                        <p class="text-slate-500 text-sm mt-2">入力内容に間違いがないか、最終確認をお願いします。</p>
                    </div>
                    
                    <div class="text-right">
                        <div class="inline-block p-4 bg-white border border-slate-100 rounded-2xl shadow-sm">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 text-center">依頼番号</p>
                            <p class="text-2xl font-mono font-bold text-blue-600 tracking-tighter">{{ $nextNumber }}</p>
                        </div>
                    </div>
                </div>

                {{-- Form Content Area --}}
                <div class="p-10 relative">
                    
                    {{-- Metadata Summary Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border border-slate-100 rounded-2xl overflow-hidden mb-12 shadow-sm">
                        <div class="p-5 bg-white border-r border-slate-100">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">発行日</label>
                            <p class="text-sm font-bold text-slate-700">{{ date('Y/m/d') }}</p>
                        </div>
                        <div class="p-5 bg-slate-50/30 border-r border-slate-100">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">期限</label>
                            <p class="text-sm font-bold text-rose-600">{{ $data['due_date'] }}</p>
                        </div>
                        <div class="p-5 bg-white">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">対象部署</label>
                            <p class="text-sm font-bold text-blue-600">{{ $department->name }}</p>
                        </div>
                    </div>

                    {{-- Detailed Content Section --}}
                    <div class="space-y-10">
                        {{-- Subject --}}
                        <div class="flex flex-col md:flex-row md:items-center">
                            <div class="w-full md:w-1/4 mb-2 md:mb-0">
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center">
                                    <span class="w-4 h-px bg-slate-300 mr-2"></span> 件名
                                </span>
                            </div>
                            <div class="w-full md:w-3/4">
                                <p class="text-xl font-bold text-slate-800 leading-tight">{{ $data['title'] }}</p>
                            </div>
                        </div>

                        {{-- Categories --}}
                        <div class="flex flex-col md:flex-row md:items-center">
                            <div class="w-full md:w-1/4 mb-2 md:mb-0">
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center">
                                    <span class="w-4 h-px bg-slate-300 mr-2"></span> 業務区分
                                </span>
                            </div>
                            <div class="w-full md:w-3/4 flex flex-wrap gap-2">
                                @foreach($categories as $cat)
                                    <span class="px-3 py-1.5 bg-blue-50 text-blue-700 text-[11px] font-black rounded-lg border border-blue-100">
                                        # {{ $cat->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Main Content --}}
                        <div class="flex flex-col">
                            <div class="mb-4">
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center">
                                    <span class="w-4 h-px bg-slate-300 mr-2"></span> 依頼詳細
                                </span>
                            </div>
                            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm leading-relaxed text-slate-700 whitespace-pre-line text-sm min-h-[150px] relative">
                                {{ $data['content'] }}
                                <div class="absolute bottom-4 right-4 text-slate-100">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor"><path d="M14,10H11V17H14V10M17,10H16V17H17V10M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19V4Z"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Special Notes --}}
                        @if(isset($data['notes']) && $data['notes'])
                            <div class="bg-rose-50 border-l-4 border-rose-400 p-6 rounded-r-2xl">
                                <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1">重要 / Special Notes</p>
                                <p class="text-sm text-rose-800 font-medium">{{ $data['notes'] }}</p>
                            </div>
                        @endif

                        {{-- Attachments --}}
                        <div class="pt-6">
                            <div class="mb-4">
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center">
                                    <span class="w-4 h-px bg-slate-300 mr-2"></span> 添付資料
                                </span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @forelse($storedFiles as $file)
                                    <div class="group flex items-center p-4 bg-slate-50 border border-slate-100 rounded-2xl hover:bg-white hover:border-blue-300 transition-all">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center mr-4 shadow-sm group-hover:text-blue-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600 truncate">{{ $file['name'] }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic bg-slate-50 p-4 rounded-2xl border border-dashed border-slate-200 w-full text-center">添付資料なし</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6">
                        <a href="{{ route('business-requests.create') }}" 
                           class="flex items-center text-sm font-bold text-slate-400 hover:text-blue-600 transition group">
                            <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            修正する 
                        </a>

                       <form method="POST" action="{{ route('business-requests.complete') }}" class="w-full md:w-auto">
                            @csrf
                            {{-- Core Data --}}
                            <input type="hidden" name="request_number" value="{{ $nextNumber }}">
                            <input type="hidden" name="target_department_id" value="{{ $data['department_id'] }}">
                            <input type="hidden" name="title" value="{{ $data['title'] }}">
                            <input type="hidden" name="due_date" value="{{ $data['due_date'] }}">
                            <input type="hidden" name="content" value="{{ $data['content'] }}">
                            <input type="hidden" name="notes" value="{{ $data['notes'] ?? '' }}">

                            {{-- Arrays: Categories --}}
                            @if(isset($data['categories']))
                                @foreach($data['categories'] as $catId)
                                    <input type="hidden" name="categories[]" value="{{ $catId }}">
                                @endforeach
                            @endif

                            {{-- Arrays: Files --}}
                            @foreach($storedFiles as $file)
                                <input type="hidden" name="attachment_paths[]" value="{{ $file['path'] }}">
                                <input type="hidden" name="attachment_names[]" value="{{ $file['name'] }}">
                            @endforeach

                            <button type="submit" 
                                    class="w-full md:w-auto inline-flex items-center justify-center bg-blue-600 text-white px-10 py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95">
                                依頼を確定し、送信する
                                <svg class="w-5 h-5 ml-2 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

           
        </div>
    </div>
</x-app-layout>