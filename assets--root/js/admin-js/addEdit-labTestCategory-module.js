import { editors, initializeEditorsForTextareas } from './ckEditor-module.js'; /// edtor object used in this function (follow *key)

// Initialize editors on page load (*key)
document.addEventListener('DOMContentLoaded', () => {
    initializeEditorsForTextareas();
});

const globalFlag = document.getElementById('global-flag');


// Function to handle file input and preview
function takeInputFile(fileInput, fileShowDivId) {
    const file = fileInput.files[0];
    const filePreview = document.getElementById(fileShowDivId);
    filePreview.innerHTML = ''; // Clear any existing content

    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        if (file.type.startsWith('image/')) {
            filePreview.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;" />`;
        } else {
            fileInput.value = ''; // Clear the input
            displayError('Invalid File Type', 'Please select a valid image file (JPEG/JPG/PNG).');
        }
    };
    reader.readAsDataURL(file);
}



// Common function to handle form submission for adding or updating a category
function handleCategorySubmission(isUpdate = false) {
    const catName = document.getElementById('category-name').value.trim();
    const catDescription = editors['category-description']; // (*key)
    const catDesc = catDescription ? catDescription.getData().trim() : '';
    const catImage = document.getElementById('fileInput1');
    
    if (!catName || !catDescription) {
        Swal.fire('Error', `Add ${!catName ? 'category name' : 'category description'}!`);
        return;
    }

    const formData = new FormData();
    if (catImage.files.length > 0) {
        formData.append('lab-cat-img', catImage.files[0]);
    } else if (isUpdate) {
        formData.append('imageData', document.getElementById('prevImgData').value);
    }

    

    formData.append('lab-cat-name', catName);
    formData.append('lab-cat-dsc', catDesc);
    formData.append('flag', isUpdate ? 2 : 1);

    if (isUpdate) {
        formData.append('lab-cat-id', document.getElementById('category-id').value);
    }

    $.ajax({
        url: 'add-edit-labtest-category.ajax.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success(response) {
            // console.log(response);
            try {
                const jsonResponse = JSON.parse(response);
                // console.log('json response'+jsonResponse);
                // Check if the response indicates success
                if (jsonResponse.status === true) {
                    // Show success alert
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: jsonResponse.message
                    }).then(() => {
                        window.location.reload(); // Reload the page on success
                    });
    
                } else {
                    // Failure case: Show error alert
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: jsonResponse.message
                    });
                }
    
            } catch (e) {
                // Handle JSON parsing or other unexpected errors
                handleUnexpectedError();
            }
        },
        error: handleUnexpectedError
    });
}

function handleUnexpectedError() {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'An unexpected error occurred. Please try again later.'
    }).then(() => location.reload());
}

// Alias functions for adding and updating categories
const addNewCategory = () => handleCategorySubmission(false);
const updateCategory = () => handleCategorySubmission(true);

// Common success handler for AJAX requests
// function handleAjaxSuccess(response) {
//     try {
//         const jsonResponse = JSON.parse(response);
//         Swal.fire({
//             icon: jsonResponse.status ? 'success' : 'error',
//             title: jsonResponse.status ? 'Success' : 'Error',
//             text: jsonResponse.message
//         }).then(() => parent.location.reload());
//     } catch (e) {
//         displayError('Error', 'An unexpected error occurred. Please try again later.');
//     }
// }

// // Common error handler for AJAX requests
// function handleAjaxError() {
//     displayError('Error', 'An error occurred while processing your request. Please try again later.');
// }

// // Function to display SweetAlert error messages
// function displayError(title, text) {
//     Swal.fire({
//         icon: 'error',
//         title: title,
//         text: text
//     }).then(() => parent.location.reload());
// }





/////////////////////////////////////////////////////////////////////////////////
// // Add event listeners to your buttons
// on file input
document.getElementById('fileInput1').addEventListener('change', function() {
    takeInputFile(this, 'lab-category-image');
});

// on data add
if(globalFlag.value == 1){
    document.getElementById('add-test-category-btn').addEventListener('click', addNewCategory);
}

// on data edit
if(globalFlag.value == 2){
    document.getElementById('update-test-category-btn').addEventListener('click', updateCategory);
}

