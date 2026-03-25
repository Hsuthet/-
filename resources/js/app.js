import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

$(document).ready(function() {
    // 1. Handle Status Dropdown (Exact Column Search)
   $(document).on('change', '.table-filter-select', function() {
        const $select = $(this);
        const tableId = $select.data('table');
        const colIndex = $select.data('column');
        const searchValue = $select.val();
        
        // Use $.fn.dataTable.isDataTable to prevent errors
        if ($.fn.dataTable.isDataTable(`#${tableId}`)) {
            const table = $(`#${tableId}`).DataTable();

            if (!searchValue) {
                table.column(colIndex).search('').draw();
            } else {
                // We use a looser regex that allows for potential surrounding whitespace 
                // but still ensures the word itself is an exact match.
                // This handles cases where Blade adds hidden \n or spaces.
                const regex = `^\\s*${searchValue}\\s*$`;
                table.column(colIndex).search(regex, true, false).draw();
            }
        } else {
            console.error(`Table with ID #${tableId} is not initialized as a DataTable.`);
        }
    });

    // 2. Handle All/My Toggle (Global Search)
    $(document).on('click', '.filter-btn', function() {
        const $btn = $(this);
        const tableId = $btn.data('table');
        const searchValue = $btn.data('search-value') || "";
        const table = $(`#${tableId}`).DataTable();

        // UI Update: Active Classes
        $btn.siblings('.filter-btn')
            .removeClass('active-toggle bg-white shadow-sm text-indigo-600 font-bold')
            .addClass('text-slate-600 font-medium');
        
        $btn.addClass('active-toggle bg-white shadow-sm text-indigo-600 font-bold')
            .removeClass('text-slate-600 font-medium');

        // Global search for the user name
        table.search(searchValue).draw();
    });
});