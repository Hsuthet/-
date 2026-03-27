import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// --- DataTable Helpers ---
$(document).ready(function() {
    // 1. Handle Status Dropdown (Exact Column Search)
    $(document).on('change', '.table-filter-select', function() {
        const $select = $(this);
        const tableId = $select.data('table');
        const colIndex = $select.data('column');
        const searchValue = $select.val();
        
        if ($.fn.dataTable.isDataTable(`#${tableId}`)) {
            const table = $(`#${tableId}`).DataTable();

            if (!searchValue) {
                table.column(colIndex).search('').draw();
            } else {
                // regex handles potential surrounding whitespace from Blade templates
                const regex = `^\\s*${searchValue}\\s*$`;
                table.column(colIndex).search(regex, true, false).draw();
            }
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

        table.search(searchValue).draw();
    });
});

// --- Smart Flash Message Handler ---
document.addEventListener('DOMContentLoaded', function() {
    const flashMessage = document.getElementById('flash-message');
    
    if (flashMessage) {
        // Function to dismiss the message
        const dismissMessage = () => {
            flashMessage.classList.add('opacity-0', '-translate-y-4');
            setTimeout(() => flashMessage.remove(), 500);
        };

        // Auto-hide after 4 seconds (extended slightly for better readability)
        const autoHideTimer = setTimeout(dismissMessage, 4000);

        // Manual-hide if user clicks the message (or a close button)
        flashMessage.addEventListener('click', () => {
            clearTimeout(autoHideTimer); // Stop the auto-hide timer
            dismissMessage();
        });
    }
});