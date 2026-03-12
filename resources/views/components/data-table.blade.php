@props(['id', 'headers', 'role' => 'employee'])

<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
    <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
        <div class="inline-flex rounded-lg shadow-sm border border-gray-200 p-1 bg-gray-50 filter-buttons">
            @if(strtoupper($role) === 'EMPLOYEE')
                <button type="button" onclick="filterTable(event, '{{ $id }}', '')" class="filter-btn active px-5 py-1.5 bg-white shadow-sm border border-gray-200 text-blue-600 rounded-md text-sm font-bold">すべて</button>
                <button type="button" onclick="filterTable(event, '{{ $id }}', '{{ Auth::user()->name }}')" class="filter-btn px-5 py-1.5 text-gray-500 text-sm font-medium hover:text-gray-700">自分の依頼</button>
                <button type="button" onclick="filterTable(event, '{{ $id }}', '承認待ち')" class="filter-btn px-5 py-1.5 text-gray-500 text-sm font-medium hover:text-gray-700">承認待ち</button>
            @elseif(strtoupper($role) === 'MANAGER')
                <button type="button" onclick="filterTable(event, '{{ $id }}', '承認待ち')" class="filter-btn active px-5 py-1.5 bg-white shadow-sm border border-gray-200 text-blue-600 rounded-md text-sm font-bold">承認待ち</button>
                <button type="button" onclick="filterTable(event, '{{ $id }}', '')" class="filter-btn px-5 py-1.5 text-gray-500 text-sm font-medium hover:text-gray-700">すべて</button>
                <button type="button" onclick="filterTable(event, '{{ $id }}', '承認済み')" class="filter-btn px-5 py-1.5 text-gray-500 text-sm font-medium hover:text-gray-700">承認済み</button>
            @endif
        </div>
    </div>

    <div class="overflow-x-auto">
        <table id="{{ $id }}" class="w-full text-sm text-left border-collapse border border-gray-200 display nowrap">
            <thead class="bg-gray-50 text-gray-700 uppercase">
                <tr>
                    @foreach($headers as $header)
                        <th class="border-b border-gray-200 px-4 py-3 text-center font-bold whitespace-nowrap">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="text-gray-600 divide-y divide-gray-100 bg-white">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <style>
        .filter-btn { transition: all 0.2s; }
        #{{ $id }}_wrapper .dataTables_filter { margin-bottom: 1rem; }
        #{{ $id }} td { white-space: nowrap; vertical-align: middle; }
    </style>
@endpush

@push('scripts')
    {{-- 1. Load DataTables ONLY (jQuery is already in app.blade) --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    
    <script>
        // Define function in global scope so onclick can find it
        window.filterTable = function(event, tableId, searchTerm) {
            if (event) event.preventDefault();
            
            // Get the DataTable instance
            var table = $('#' + tableId).DataTable();
            
            // Apply search and redraw
            table.search(searchTerm).draw();

            // Update UI Button Classes
            const btn = event.currentTarget;
            const $container = $(btn).closest('.filter-buttons');
            
            $container.find('.filter-btn').removeClass('bg-white shadow-sm border text-blue-600 font-bold')
                                          .addClass('text-gray-500 font-medium');

            $(btn).addClass('bg-white shadow-sm border text-blue-600 font-bold')
                  .removeClass('text-gray-500 font-medium');
        };

        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#{{ $id }}')) {
                var table = $('#{{ $id }}').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/ja.json"
                    },
                    "pageLength": 10,
                    "scrollX": true,
                    "autoWidth": false,
                    "dom": 'lfrtip' 
                });

                // Default filter logic for Manager
                @if(strtoupper($role) === 'MANAGER')
                    table.search('承認待ち').draw();
                @endif
            }
        });
    </script>
@endpush