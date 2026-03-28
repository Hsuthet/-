@props(['id', 'headers', 'role' => 'employee'])

<div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-200">
    <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
        <div class="filter-container w-full md:w-auto">
            {{ $filters ?? '' }}
        </div>
    </div>

    {{-- min-w-full を指定し、横揺れを防止 --}}
    <div class="w-full">
        <table id="{{ $id }}" class="w-full text-sm border-collapse display nowrap cell-border">
            <thead class="bg-slate-50 text-slate-700">
                <tr>
                    @foreach($headers as $header)
                        {{-- text-left に変更し、日本語の読みやすさを向上 --}}
                        <th class="border-b border-slate-200 px-4 py-4 text-left font-bold whitespace-nowrap text-[11px] tracking-widest text-slate-500 uppercase">
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
    {{-- Responsive 拡張機能の CSS を追加 --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        /* DataTables デフォルトスタイルの上書き */
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.25rem 2rem 0.25rem 0.75rem;
        }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.4rem 0.75rem;
            margin-left: 0.5rem;
            background-color: #f8fafc;
        }
        table.dataTable.no-footer { border-bottom: 1px solid #e2e8f0 !important; }
        
        /* レスポンシブ用の「+」アイコンの色調整 */
        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before {
            background-color: #4f46e5 !important; 
            border: none !important;
            box-shadow: none !important;
        }
        
        /* ページネーションのカスタマイズ */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #4f46e5 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
        }
        div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    
    table.dataTable {
        width: 100% !important; /* Force 100% width */
        margin: 0 !important;
    }
    </style>
@endpush

@push('scripts')
    {{-- Responsive 拡張機能の JS を追加 --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    
    <script>
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#{{ $id }}')) {
                const table = $('#{{ $id }}').DataTable({
    "language": { "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/ja.json" },
    "pageLength": 10,
    "responsive": false,  
    "scrollX": true,      
    "autoWidth": false,
    "dom": '<"flex flex-col md:flex-row justify-between items-center mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>',
    "columnDefs": [
        { 
            "targets": 0, 
            "orderable": false 
        },
        { "targets": "_all", "className": "px-4 py-4" }
    ]
});

                @if(strtoupper($role) === 'MANAGER')
                    table.search('承認待ち').draw();
                @endif

                // フィルタボタンのイベント
                $(document).on('click', '.filter-btn', function(e) {
                    const $btn = $(this);
                    const searchTerm = $btn.data('search') || '';
                    table.search(searchTerm).draw();

                    // UI更新
                    $('.filter-btn').removeClass('bg-white shadow-sm border border-slate-200 text-indigo-600 font-bold')
                                   .addClass('text-slate-500 font-medium');
                    $btn.addClass('bg-white shadow-sm border border-slate-200 text-indigo-600 font-bold')
                        .removeClass('text-slate-500 font-medium');
                });
            }
        });
    </script>
@endpush