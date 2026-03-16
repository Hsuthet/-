@props(['id', 'headers', 'role' => 'employee'])

<div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
    <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
        {{-- Unified Filter Buttons Container --}}
        <div class="inline-flex rounded-xl shadow-sm border border-slate-200 p-1 bg-slate-50 filter-buttons" id="{{ $id }}_filters">
            
            @if(strtoupper($role) === 'EMPLOYEE')
                <button type="button" data-search="" 
                    class="filter-btn px-5 py-1.5 bg-white shadow-sm border border-slate-200 text-blue-600 rounded-lg text-sm font-bold">
                    すべて
                </button>

                <button type="button" data-search="{{ Auth::user()->name }}" 
                    class="filter-btn px-5 py-1.5 text-slate-500 text-sm font-medium hover:text-slate-700">
                    自分の依頼
                </button>

                <button type="button" data-search="承認待ち" 
                    class="filter-btn px-5 py-1.5 text-slate-500 text-sm font-medium hover:text-slate-700">
                    承認待ち
                </button>

            @elseif(strtoupper($role) === 'MANAGER')
                <button type="button" data-search="承認待ち" 
                    class="filter-btn px-5 py-1.5 bg-white shadow-sm border border-slate-200 text-blue-600 rounded-lg text-sm font-bold">
                    承認待ち
                </button>

                <button type="button" data-search="" 
                    class="filter-btn px-5 py-1.5 text-slate-500 text-sm font-medium hover:text-slate-700">
                    すべて
                </button>

                <button type="button" data-search="承認済み" 
                    class="filter-btn px-5 py-1.5 text-slate-500 text-sm font-medium hover:text-slate-700">
                    承認済み
                </button>
            @endif
        </div>
    </div>

    <div class="overflow-x-auto">
        <table id="{{ $id }}" class="w-full text-sm text-left border-collapse border border-slate-200 display nowrap">
            <thead class="bg-slate-50 text-slate-700 uppercase">
                <tr>
                    @foreach($headers as $header)
                        <th class="border-b border-slate-200 px-4 py-4 text-center font-bold whitespace-nowrap text-[11px] tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="text-slate-600 divide-y divide-slate-100 bg-white">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <style>
        .filter-btn { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; border: 1px solid transparent; }
        /* Hide default search box if you want to rely only on buttons */
        .dataTables_wrapper .dataTables_filter { margin-bottom: 1.5rem; }
        table.dataTable { border-collapse: collapse !important; border: none !important; width: 100% !important; }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Check if DataTable is already initialized to prevent errors
            if (!$.fn.DataTable.isDataTable('#{{ $id }}')) {
                const table = $('#{{ $id }}').DataTable({
                    "language": { "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/ja.json" },
                    "pageLength": 10,
                    "scrollX": true,
                    "autoWidth": false,
                    "dom": 'lfrtip' 
                });

                // Set initial filter for Manager
                @if(strtoupper($role) === 'MANAGER')
                    table.search('承認待ち').draw();
                @endif

                // CORRECTED CLICK HANDLER
                $('#{{ $id }}_filters').on('click', '.filter-btn', function(e) {
                    e.preventDefault();
                    const $btn = $(this);
                    const searchTerm = $btn.data('search') || ''; // Default to empty string if data-search is empty
                    
                    // 1. CLEAR existing search and APPLY new search
                    // Using table.search(val) affects the global search box
                    table.search(searchTerm).draw();

                    // 2. UI Class Update
                    const $container = $btn.closest('.filter-buttons');
                    $container.find('.filter-btn')
                        .removeClass('bg-white shadow-sm border border-slate-200 text-blue-600 font-bold')
                        .addClass('text-slate-500 font-medium');

                    $btn.addClass('bg-white shadow-sm border border-slate-200 text-blue-600 font-bold')
                        .removeClass('text-slate-500 font-medium');
                });
            }
        });
    </script>
@endpush