@props(['id', 'headers'])

<div class="py-4">
    <table id="{{ $id }}" class="display w-full text-sm text-left border-collapse border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                @foreach($headers as $header)
                    <th class="border border-gray-300 px-4 py-3">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    
    <script>
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#{{ $id }}')) {
                $('#{{ $id }}').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/ja.json" // Japanese language pack
                    }
                });
            }
        });
    </script>
@endpush