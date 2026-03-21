@props([
    'name' => 'status',
    'options' => [],
    'placeholder' => '全てのステータス',
    'color' => 'indigo'
])

<form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2">
    {{-- Carry over other existing query parameters (like search or page) --}}
    @foreach(request()->except($name) as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

    <div class="relative">
        <select name="{{ $name }}" onchange="this.form.submit()" 
                class="bg-white border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-{{ $color }}-500 focus:border-{{ $color }}-500 block p-2.5 pr-10 shadow-sm transition-all appearance-none cursor-pointer hover:border-slate-300 min-w-[160px]">
            <option value="">{{ $placeholder }}</option>
            @foreach($options as $value => $label)
                <option value="{{ $value }}" {{ request($name) == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        
        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
            <i data-lucide="chevron-down" class="w-4 h-4"></i>
        </div>
    </div>
    
    @if(request($name))
        <a href="{{ url()->current() }}" 
           class="flex items-center justify-center w-9 h-9 rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-100 transition-colors shadow-sm border border-rose-100"
           title="フィルターをクリア">
            <i data-lucide="filter-x" class="w-4 h-4"></i>
        </a>
    @endif
</form>

{{-- Usage example


<x-status-filter 
    name="status" 
    placeholder="全てのステータス"
    color="indigo"
    :options="[
        'APPROVED'  => '承認済み (Approved)',
        'WORKING'   => '作業中 (Working)',
        'COMPLETED' => '完了 (Completed)',
    ]" 
/> --}}