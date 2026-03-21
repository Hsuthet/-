{{-- resources/views/components/filter-button.blade.php --}}
@props(['tableId', 'label', 'search' => '', 'active' => false])

<button 
    type="button" 
    data-table="{{ $tableId }}"
    data-search="{{ $search }}"
    class="filter-btn px-5 py-1.5 rounded-lg text-sm transition-all duration-200 
    {{ $active ? 'bg-white shadow-sm border border-slate-200 text-blue-600 font-bold active-filter' : 'text-slate-500 font-medium hover:text-slate-700' }}"
>
    {{ $label }}
</button>