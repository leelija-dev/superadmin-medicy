import { editors, initializeEditorsForTextareas } from './ckEditor-module.js';

document.addEventListener('DOMContentLoaded', () => {
    initializeEditorsForTextareas(); // Initialize editors for existing textareas
});

document.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener('click', function (event) {
        if (event.target && event.target.id === 'add-new-param-btn') {
            addDynamicRow();
        }
        if (event.target && event.target.id === 'remove-last-param-btn') {
            removeLastRow();
        }

        const globalFlag = document.getElementById('global-flag');
        if (globalFlag.value == 2) {
            // if (event.target && event.target.id === 'remove-last-param-btn') {
            //     removeLastRow();
            // }

            if (event.target.closest('.add-old-heading-btn')) {
                const index = event.target.closest('.add-old-heading-btn').getAttribute('data-index');
                addDynamicHeader(index);
            }

            if (event.target.classList.contains('fa-trash')) {
                removeLastHeader(event.target);
            }

            if (event.target && event.target.id === 'remove-header-btn') {
                delPrevParam(event.target);
            }

            if (event.target && event.target.id === 'del-prev-param') {

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                  }).then((result) => {
                      if (result.isConfirmed) {
                        delPrevParam(event.target);
                    }
                  });
            }
        }

    });
});



// Function to add a new dynamic row
function addDynamicRow() {
    const dynamicRowContainer = document.getElementById('dynamic-row-container');
    // const paramRowCount = document.getElementById('param-count');
    const initialRowIndex = document.getElementById('initial-row-index');
    // let rowIndex = parseInt(document.getElementById('initial-row-index').value) + 1;
    let paramBoxes = document.getElementsByClassName('param-box');
    let paramBoxesLength = (paramBoxes.length) + 1;

    // let rowIndex = parseInt(initialRowIndex.value) + 1;
    let rowIndex = paramBoxesLength;

    // paramRowCount.value = rowIndex;

    const newRow = document.createElement('div');
    newRow.className = 'row px-3 pt-3 mb-3 bg-light rounded param-box';
    newRow.id = `dynamic-row-${rowIndex}`;
    newRow.innerHTML = `
    <div class="text-right w-100">
        <input class="d-none" type="number" id="prev-header-count-${rowIndex}" value="0">
            <button type="button" class="btn btn-sm btn-primary add-heading-btn" data-index="${rowIndex}">
                <i class="fas fa-plus-circle" style="cursor:pointer; margin-right:5px;"></i> Add Field
            </button>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-6">
                <input type="hidden" id="test-param-id-${rowIndex}" value="">
                <label for="param-name-${rowIndex}" class="form-label">Parameter Name</label>
                <input type="text" id="param-name-${rowIndex}" name="param-name" class="form-control" placeholder="Enter parameter name" autocomplete="off" required>
            </div>
            <div class="col-6">
                <label for="param-unit-${rowIndex}" class="form-label">Enter Unit</label>
                <input type="text" id="param-unit-${rowIndex}" name="param-unit" class="form-control" placeholder="Enter unit" autocomplete="off" required>
            </div>
        </div>
    </div>
    
        <div id="dynamic-head-container-${rowIndex}" class="col-12 mb-3 mt-3">
            <!-- Dynamically added headers will appear here -->
        </div>
    <div class="col-12">
        <input type="hidden" id="standard-range-id-${rowIndex}" value="0">
        <div class="row d-flex">
            <div class="col-sm-6">
                <label for="child-unit-data-${rowIndex}" class="form-label">Range for Child</label>
                <textarea id="child-unit-data-${rowIndex}" name="child_range[]" class="form-control" autocomplete="off" rows="3" required></textarea>
            </div>
            <div class="col-sm-6">
                <label for="adult-male-data-${rowIndex}" class="form-label">Range for Adult Male</label>
                <textarea id="adult-male-data-${rowIndex}" name="adult_male_range[]" class="form-control" autocomplete="off" rows="3" required></textarea>
            </div>
        </div>
        <div class="row d-flex">
            <div class="col-sm-6">
                <label for="adult-female-data-${rowIndex}" class="form-label">Range for Adult Female</label>
                <textarea id="adult-female-data-${rowIndex}" name="adult_female_range[]" class="form-control" autocomplete="off" rows="3" required></textarea>
            </div>
            <div class="col-sm-6">
                <label for="general-range-data-${rowIndex}" class="form-label">General Range</label>
                <textarea id="general-range-data-${rowIndex}" name="general_range[]" class="form-control" autocomplete="off" rows="3" required></textarea>
            </div>
        </div>
    </div>`;
    dynamicRowContainer.appendChild(newRow);

    // Bind event listeners dynamically for the added row
    bindDynamicEvents(rowIndex);
    initializeEditorsForTextareas();
}

// Function to remove the last row
function removeLastRow() {

    const dynamicRowContainer = document.getElementById('dynamic-row-container');
    const paramRowCount = document.getElementById('param-count');
    const initialRowIndex = document.getElementById('initial-row-index');
    let rowIndex = parseInt(initialRowIndex.value) + 1;

    if (rowIndex > parseInt(initialRowIndex.value)) {
        dynamicRowContainer.removeChild(dynamicRowContainer.lastChild);
        rowIndex--;
        paramRowCount.value = rowIndex;
    } else {
        Swal.fire('Alert', 'No row left to delete', 'info');
    }
}

function bindDynamicEvents(index) {
    const addHeaderBtn = document.querySelector(`.add-heading-btn[data-index="${index}"]`);
    addHeaderBtn.addEventListener('click', function () {
        addDynamicHeader(index);
    });
}




// Function to add a new dynamic header to a specific row
function addDynamicHeader(index) {
    const dynamicHeadContainer = document.getElementById(`dynamic-head-container-${index}`);
    const headerCount = document.getElementById(`prev-header-count-${index}`);
    const headerIndex = parseInt(headerCount.value) + 1;

    const newHeader = document.createElement('div');
    newHeader.className = 'form-group';
    newHeader.id = `header-${index}-${headerIndex}`;
    newHeader.innerHTML = `
        <div class="row mt-1 align-items-left">
            <input type="hidden" id="param-header-id-${index}-${headerIndex}" name="param_header_id_${index}[]" value="0">
            <input type="text" id="param-header-${index}-${headerIndex}" name="param_header_name_${index}[]" class="form-control" placeholder="Enter header" autocomplete="off" required>
            <i class="fas fa-trash text-danger mt-2" head-index="${index}" header-index="${headerIndex}" style="cursor: pointer; position: absolute; margin-left: 150px;" onclick=""></i>
        </div>`;

    dynamicHeadContainer.appendChild(newHeader);
    headerCount.value = headerIndex;
}

function delPrevParam(element) {
    const elementTitle = element.title;
    const testId = element.getAttribute('test-id');

    const paramId = element.getAttribute('param-id');
    const rangeId = element.getAttribute('range-id');
    const headId = element.getAttribute('head-id')
    const rowElement = element.closest('.row.mb-3');

    const formData = new FormData();
    formData.append('title', elementTitle);
    formData.append('test-id', testId);

    formData.append('test-param-id', paramId);
    formData.append('test-param-range-id', rangeId);
    formData.append('head-id', headId);
    formData.append('action', 'delete');


    // AJAX request
    $.ajax({
        url: 'ajax/add-edit-labtest-data.ajax.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success(response) {
            console.log(response);
            try {
                const jsonResponse = JSON.parse(response);

                if (jsonResponse.status === true) {
                    // Success case
                    if (rowElement) {
                        rowElement.remove();
                    } else {
                        Swal.fire("Error", "Row element not found!", "error");
                    }
                }

                // Displaying the alert regardless of success or failure
                const alertType = jsonResponse.status ? 'success' : 'error';
                Swal.fire({
                    icon: alertType,
                    title: jsonResponse.status ? 'Success' : 'Error',
                    text: jsonResponse.message
                }).then(() => {
                    if (jsonResponse.status) {
                        window.location.reload();
                    }
                });

            } catch (e) {
                console.log(e);
                handleUnexpectedError();
            }
        },
        error: handleUnexpectedError
    });

}







// Function to remove a specific dynamic header from any position in the row
function removeLastHeader(event) {
    const headIndex = event.getAttribute('head-index');
    const headerIndex = event.getAttribute('header-index');

    const dynamicHeadContainer = document.getElementById(`dynamic-head-container-${headIndex}`);
    const lastHeader = document.getElementById(`header-${headIndex}-${headerIndex}`);

    if (lastHeader) {
        dynamicHeadContainer.removeChild(lastHeader);
    }
    // else {
    //     console.error(`Header with id header-${headIndex}-${headerIndex} not found.`);
    // }

    // const headerCount = document.getElementById(`prev-header-count-${headIndex}`);
    // headerCount.value = Math.max(0, parseInt(headerCount.value) - 1);
}



// Function to bind events dynamically after each row is added




// on data add
// if (globalFlag.value == 1) {
// }

// on data edit


/// form submission area













// // Start observing the document for changes
// observer.observe(document.body, {
//     childList: true,
//     subtree: true
// });



function handleUnexpectedError() {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'An unexpected error occurred. Please try again later.'
    })
}