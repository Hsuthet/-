@props([
    'fromName' => 'from',
    'toName' => 'to',
    'fromValue' => request('from'),
    'toValue' => request('to')
])

<form method="GET" class="flex items-center gap-2">
    
    {{-- From Date --}}
    <input 
        type="date" 
        name="{{ $fromName }}" 
        value="{{ $fromValue }}"
        class="border border-slate-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-indigo-500 outline-none"
    >

    <span class="text-xs text-slate-400">〜</span>

    {{-- To Date --}}
    <input 
        type="date" 
        name="{{ $toName }}" 
        value="{{ $toValue }}"
        class="border border-slate-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-indigo-500 outline-none"
    >

    {{-- Submit --}}
    <button type="submit"
        class="px-3 py-1.5 bg-indigo-600 text-white text-xs rounded-lg hover:bg-indigo-700 transition">
        適用
    </button>

</form>