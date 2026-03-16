{{-- resources/views/components/filter-button.blade.php --}}
@props(['tableId', 'label', 'search' => '', 'active' => false])

<button 
    type="button" 
    onclick="filterTable(event, '{{ $tableId }}', '{{ $search }}')"
    class="filter-btn px-5 py-1.5 rounded-md text-sm transition-all {{ $active ? 'bg-white shadow-sm border border-slate-200 text-blue-600 font-bold' : 'text-slate-500 font-medium hover:text-slate-700' }}"
>
    {{ $label }}
</button>