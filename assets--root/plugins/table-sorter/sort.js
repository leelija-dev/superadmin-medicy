document.addEventListener('DOMContentLoaded', function() {
    const tables = document.querySelectorAll('.sortable-table');

    tables.forEach(table => {
        const headers = table.querySelectorAll('th[data-sort]');
        
        headers.forEach(header => {
            header.addEventListener('click', () => {
                const sortType = header.getAttribute('data-sort');
                const columnIndex = Array.from(header.parentElement.children).indexOf(header);
                
                sortTable(columnIndex, sortType, header);
            });
        });

        function sortTable(columnIndex, sortType, clickedHeader) {
            const rows = Array.from(table.querySelectorAll('tbody tr'));
            const isNumber = sortType === 'number';

            rows.sort((rowA, rowB) => {
                const cellA = rowA.children[columnIndex].textContent.trim();
                const cellB = rowB.children[columnIndex].textContent.trim();

                if (isNumber) {
                    return parseFloat(cellA) - parseFloat(cellB);
                } else {
                    return cellA.localeCompare(cellB);
                }
            });

            const sortedClass = isNumber ? 'sorted-numeric' : 'sorted-alpha';
            const isAsc = clickedHeader.classList.contains('sorted-asc');

            table.querySelectorAll('thead th').forEach(header => {
                header.classList.remove('sorted-asc', 'sorted-desc');
            });

            if (isAsc) {
                rows.reverse();
                clickedHeader.classList.remove('sorted-asc');
                clickedHeader.classList.add('sorted-desc');
            } else {
                clickedHeader.classList.remove('sorted-desc');
                clickedHeader.classList.add('sorted-asc');
            }

            clickedHeader.classList.add(sortedClass);

            const tbody = table.querySelector('tbody');
            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
        }
    });
});