// const xmlhttp = new XMLHttpRequest();

// Function to open the Add/Edit Category modal
function openAddNewCategoryModal(ts) {
    const isAddMode = ts.id === 'add-category-btn';
    const modalTitle = document.getElementById('testCategoryAddEdit'); 
    const url = isAddMode
        ? 'ajax/add-new-labTestCategory.ajax.php'
        : `ajax/edit-labTestCategory.ajax.php?catId=${ts.getAttribute('data-id')}`;

    modalTitle.innerHTML = isAddMode ? 'Add Category' : 'Edit Category';

    const modal = new bootstrap.Modal(document.getElementById('addEditTestCategory'));
    modal.show();

    $(".add-new-test-category-modal").html(`<iframe width="100%" height="500px" frameborder="0" allowtransparency="true" src="${url}"></iframe>`);
}


function refreshPage(){
    parent.location.reload();
}

// for lab category modal open 
function openCategoryModal($categoryId) {
    let url = "ajax/single-lab-category.view.ajax.php?categoryId=" + $categoryId;

    fetch(url)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok " + response.statusText);
            }
            return response.text();
        })
        .then((data) => {
            // Assuming there is an element with the class "docViewAndEditModal"
            let modalElement = document.querySelector(".catagoryViewModal");
            if (modalElement) {
                modalElement.innerHTML = data;
            } else {
                Swal.fire("Error", "Modal element not found", "error");
            }
        })
        .catch((error) => {
            Swal.fire("Error", "Fetch operation error!", "error");
            console.error(
                "There has been a problem with your fetch operation:",
                error
            );
        });
}

//for lab test list modal open
function openSingleTestModal($testCatId) {
    let url = "ajax/single-test.view.ajax.php?sigleTestId=" + $testCatId;

    fetch(url)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok " + response.statusText);
            }
            return response.text();
        })
        .then((data) => {
            // Assuming there is an element with the class "docViewAndEditModal"
            let modalElement = document.querySelector(".catagoryViewModal");
            if (modalElement) {
                modalElement.innerHTML = data;
            } else {
                Swal.fire("Error", "Modal element not found", "error");
            }
        })
        .catch((error) => {
            Swal.fire("Error", "Fetch operation error!", error);
            console.error(
                "There has been a problem with your fetch operation:",
                error
            );
        });
}

