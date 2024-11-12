function createPaginationControls(totalRows, currentPage, rowsPerPage, onPageChange) {
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    const paginationControls = document.createElement('div');
    paginationControls.className = 'd-flex align-items-center justify-content-center';

    // Previous button
    const prevButton = document.createElement('button');
    prevButton.innerHTML = '&#8592;'; // Left arrow
    prevButton.disabled = currentPage === 1;
    prevButton.className = 'btn btn-link text-decoration-none text-skyblue';
    prevButton.addEventListener('click', () => {
        if (currentPage > 1) {
            onPageChange(currentPage - 1);
        }
    });
    paginationControls.appendChild(prevButton);

    // If there are more than 3 pages, use "1 of n" format
    if (totalPages > 3) {
        const pageNumber = document.createElement('span');
        pageNumber.textContent = `${currentPage} of ${totalPages}`;
        pageNumber.className = 'mx-1 fw-bold text-primary';
        paginationControls.appendChild(pageNumber);
    } else {
        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageNumber = document.createElement('span');
            pageNumber.textContent = i;
            pageNumber.className = `mx-1 ${i === currentPage ? 'fw-bold text-primary' : 'text-skyblue'}`;
            pageNumber.style.cursor = 'pointer';
            pageNumber.addEventListener('click', () => {
                onPageChange(i);
            });
            paginationControls.appendChild(pageNumber);
        }
    }

    // Next button
    const nextButton = document.createElement('button');
    nextButton.innerHTML = '&#8594;'; // Right arrow
    nextButton.disabled = currentPage === totalPages;
    nextButton.className = 'btn btn-link text-decoration-none text-skyblue';
    nextButton.addEventListener('click', () => {
        if (currentPage < totalPages) {
            onPageChange(currentPage + 1);
        }
    });
    paginationControls.appendChild(nextButton);

    return paginationControls;
}
