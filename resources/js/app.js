import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

  $(document).ready(function() {
    // We store filters in an object keyed by table ID so they don't interfere
    let activeFilters = {};

    function getTableInstance(tableId) {
        if (!activeFilters[tableId]) {
            activeFilters[tableId] = { user: "", status: "" };
        }
        return $('#' + tableId).DataTable();
    }

    // Handle Status Dropdown (Dynamic)
    $(document).on('change', '.table-filter-select', function() {
        const tableId = $(this).data('table'); // Gets 'requestsTable' or 'tasksTable'
        const table = getTableInstance(tableId);
        
        activeFilters[tableId].status = $(this).val() || "";
        
        // Combine filters for this specific table
        const combinedSearch = activeFilters[tableId].user + " " + activeFilters[tableId].status;
        table.search(combinedSearch.trim()).draw();
    });

    // Handle All/My Toggle (Dynamic)
    $(document).on('click', '.filter-btn', function() {
        const $btn = $(this);
        const tableId = $btn.data('table');
        const table = getTableInstance(tableId);

        activeFilters[tableId].user = $btn.data('search-value') || "";

        // UI Update logic
        $btn.parent().find('.filter-btn')
            .removeClass('active-toggle bg-white shadow-sm text-indigo-600 font-bold')
            .addClass('text-slate-600 font-medium');
        $btn.addClass('active-toggle bg-white shadow-sm text-indigo-600 font-bold')
            .removeClass('text-slate-600 font-medium');

        const combinedSearch = activeFilters[tableId].user + " " + activeFilters[tableId].status;
        table.search(combinedSearch.trim()).draw();
    });
});