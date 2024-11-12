
const addTestAndSubTest = (t) => {
    let url;
    const modalContent = document.querySelector(".add-new-test-data-modal");
    const editNicheDetails = document.getElementById('editNicheDetails');
    const modalSizeId = document.getElementById('modal-sizeId');

    if (!modalContent || !editNicheDetails || !modalSizeId) {
        console.error("Required elements are not found in the DOM.");
        return;
    }

    if (t.id === 'add-testType') {
        url = 'ajax/add-labTestType.ajax.php';
        editNicheDetails.innerHTML = 'Add new test type';
        modalSizeId.classList.add('modal-lg');
        modalSizeId.classList.remove('modal-md');
        updateModalContent(modalContent, url, '380px');
        
    } else if (t.id === 'add-subTest') {
        url = 'ajax/add-subTest.ajax.php';
        editNicheDetails.innerHTML = 'Add subtest details';
        modalSizeId.classList.add('modal-lg');
        modalSizeId.classList.remove('modal-md');
        updateModalContent(modalContent, url, '500px');
    }
}

const updateModalContent = (modalContent, url, height) => {
    modalContent.innerHTML = `<iframe width="99%" height="${height}" frameborder="0" allowtransparency="true" src="${url}"></iframe>`;
}


// for modal open 
function openCategoryModal($categoryId) {
    let url = "ajax/single-lab-catagory.view.ajax.php?categoryId=" + $categoryId;

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
            Swal.fire("Error", "Fetch operation error!", "error");
            console.error(
                "There has been a problem with your fetch operation:",
                error
            );
        });
}
