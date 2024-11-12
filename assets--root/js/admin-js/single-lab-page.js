function refreshPage() {
    parent.location.reload();
}

/// OPENING ADD / EDIT MODAL
// function addEditNewTestModal(ts) {
//     const modalLabel = document.getElementById('addEditTestDataModelLabel');
//     const modal = new bootstrap.Modal(document.getElementById('addEditTestDataModel'));
//     const isAddNew = ts.id === 'add-new-test';
//     const modalTitle = isAddNew ? 'ADD NEW TEST DETAILS' : 'EDIT TEST DATA';
//     const categoryId = ts.getAttribute(isAddNew ? 'data-id' : 'cat-id');
//     const testId = isAddNew ? '' : `&testId=${ts.getAttribute('test-id')}`;
//     const url = `ajax/${isAddNew ? 'add-new' : 'edit'}-labTestData.ajax.php?catId=${categoryId}${testId}`;

//     modalLabel.textContent = modalTitle;
//     document.querySelector(".add-new-test-data-modal").innerHTML = `<iframe width="100%" height="500px" frameborder="0" allowtransparency="true" src="${url}"></iframe>`;
//     modal.show();
// }


function addEditNewTestModal(ts) {
    const isAddNew = ts.id === 'add-new-test';
    const categoryId = ts.getAttribute(isAddNew ? 'data-id' : 'cat-id');
    const testId = isAddNew ? '' : `&testId=${ts.getAttribute('test-id')}`;
    const url = `${isAddNew ? 'add-new' : 'edit'}-labTestData.php?catId=${categoryId}${testId}`;

    // Redirect to the URL instead of showing it in a modal
    window.location.href = url;
}

