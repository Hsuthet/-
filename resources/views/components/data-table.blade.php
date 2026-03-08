@props(['id', 'headers', 'role' => 'REQUESTER'])

<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
    <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
       <div class="inline-flex rounded-lg shadow-sm border border-gray-200 p-1 bg-gray-50 filter-buttons">
    @if(strtoupper($role) === 'REQUESTER')
        <button onclick="filterTable(event, '{{ $id }}', '')" class="filter-btn active px-5 py-1.5 bg-white shadow-sm border border-gray-200 text-blue-600 rounded-md text-sm font-bold">すべて</button>
        <button onclick="filterTable(event, '{{ $id }}', '{{ Auth::user()->name }}')" class="filter-btn px-5 py-1.5 text-gray-500 text-sm font-medium hover:text-gray-700">自分の依頼</button>
        <button onclick="filterTable(event, '{{ $id }}', '承認待ち')" class="filter-btn px-5 py-1.5 text-gray-500 text-sm font-medium hover:text-gray-700">承認待ち</button>
    @elseif(strtoupper($role) === 'APPROVER')
        <button onclick="filterTable(event, '{{ $id }}', '承認待ち')" class="filter-btn active px-5 py-1.5 bg-white shadow-sm border border-gray-200 text-blue-600 rounded-md text-sm font-bold">承認待ち</button>
        <button onclick="filterTable(event, '{{ $id }}', '')" class="filter-btn px-5 py-1.5 text-gray-500 text-sm font-medium hover:text-gray-700">すべて</button>
        <button onclick="filterTable(event, '{{ $id }}', '承認済み')" class="filter-btn px-5 py-1.5 text-gray-500 text-sm font-medium hover:text-gray-700">承認済み</button>
    @endif
</div>
    </div>

    <div class="overflow-x-auto">
        <table id="{{ $id }}" class="w-full text-sm text-left border-collapse border border-gray-200 display nowrap">
            <thead class="bg-gray-50 text-gray-700 uppercase">
                <tr>
                    @foreach($headers as $header)
                        {{-- Added whitespace-nowrap to prevent header text wrapping --}}
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

{{-- CSS & Scripts --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <style>
        /* Modern styling for DataTable UI */
        .dataTables_wrapper .dataTables_length select {
            padding-right: 2rem !important;
            border-radius: 6px;
        }
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            padding: 4px 12px;
        }
        /* Keep all cells in a single line */
        #{{ $id }} td {
            white-space: nowrap;
            vertical-align: middle;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // DataTable initialize
            if (!$.fn.DataTable.isDataTable('#{{ $id }}')) {
                $('#{{ $id }}').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/ja.json"
                    },
                    "pageLength": 10,
                    "scrollX": true,
                    "autoWidth": false
                });
            }
        });

        // Filter Function
        function filterTable(event, tableId, searchTerm) {
            // prevent Page reload 
            if (event) event.preventDefault();

            // call DataTable instance 
            var table = $('#' + tableId).DataTable();
            table.search(searchTerm).draw();

            // UI Styling change
            const container = event.target.closest('.filter-buttons');
            if (container) {
                container.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('bg-white', 'shadow-sm', 'border', 'text-blue-600', 'font-bold');
                    btn.classList.add('text-gray-500', 'font-medium');
                });
                event.target.classList.add('bg-white', 'shadow-sm', 'border', 'text-blue-600', 'font-bold');
                event.target.classList.remove('text-gray-500', 'font-medium');
            }
        }
    </script>
@endpush