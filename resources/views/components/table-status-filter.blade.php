{{-- resources/views/components/table-status-filter.blade.php --}}
@props([
    'tableId', 
    'options' => [], 
    'placeholder' => '全て',
    'color' => 'indigo'
    
])

<div class="relative min-w-[180px] group">
    {{-- Label Icon (Optional but looks professional) --}}
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <i data-lucide="filter" class="w-4 h-4 text-slate-400 group-hover:text-{{ $color }}-500 transition-colors"></i>
    </div>

    <select 
        data-table="{{ $tableId }}"
        class="table-filter-select block w-full pl-10 pr-10 py-2.5 text-sm bg-white border border-slate-200 text-slate-600 rounded-xl shadow-sm appearance-none cursor-pointer focus:ring-2 focus:ring-{{ $color }}-500/20 focus:border-{{ $color }}-500 hover:border-slate-300 transition-all"
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>

    {{-- Custom Chevron Icon --}}
    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
    </div>
</div>