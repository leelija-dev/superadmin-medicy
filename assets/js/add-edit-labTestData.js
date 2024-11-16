import { editors, initializeEditorsForTextareas } from './ckEditor-module.js';
import { initializeReportTextEditor } from './text-report-editor.js';

document.addEventListener('DOMContentLoaded', () => {
    initializeEditorsForTextareas(); // Initialize editors for existing textareas
});

document.getElementById('update-test-data-btn').addEventListener('click', updateTestData);
// document.getElementById('add-test-btn').addEventListener('click', () => saveSingleFieldFormat(false));


(async () => {
    const newTextarea = document.getElementById('report-text-format-field');

    if (newTextarea) {
        // Destroy the existing editor instance if it exists
        if (editors[newTextarea.id]) {
            await editors[newTextarea.id].destroy();
            delete editors[newTextarea.id];
        }

        // Initialize the editor
        initializeReportTextEditor(newTextarea);
    }
})();


function updateTestData() {

    var formatForm = document.getElementById('dynamic-row-container').closest('form'); // Get the closest form element

    if (formatForm) {
        // Get all inputs, textareas, and select elements inside the form
        var fields = formatForm.querySelectorAll('input, textarea, select');

        // Convert NodeList to an array (optional, for easier manipulation)
        var fieldsArray = Array.from(fields);

        // Flag to track whether 'report-text-format' field exists
        var reportTextFormatExists = false;

        reportTextFormatExists = fieldsArray.some(field => field.name === 'report-text-format');

        // Check if the field exists
        if (reportTextFormatExists) {
            saveTextFormatReport();
        } else {
            saveSingleFieldFormat(true);
        }
    }
}


function saveTextFormatReport() {
    const formData = new FormData();

    const catId = document.getElementById('parent-category-id').value;
    const testId = document.getElementById('test-id').value;
    const testNm = document.getElementById('test-name').value.trim();
    const testPrc = document.getElementById('test-price').value.trim();

    // Get CKEditor instances if they exist
    const editorInstanceDsc = editors['test-description'];
    const dsc = editorInstanceDsc ? editorInstanceDsc.getData().trim() : '';

    const editorInstancePrep = editors['test-process'];
    const prep = editorInstancePrep ? editorInstancePrep.getData().trim() : '';

    const reportTextFormatField = editors['report-text-format-field'];
    const reportText = reportTextFormatField ? reportTextFormatField.getData().trim() : '';

    // Append data to FormData
    formData.append('category_id', catId);
    formData.append('test_id', testId);
    formData.append('test_name', testNm);
    formData.append('test_price', testPrc);
    formData.append('test_dsc', dsc);
    formData.append('test_prep', prep);
    formData.append('report-text-format', reportText);

    $.ajax({
        url: 'ajax/add-edit-labtest-data.ajax.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success(response) {
            
            try {
                const jsonResponse = JSON.parse(response);
                if (jsonResponse.status === true) {
                    // Show success alert
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: jsonResponse.message
                    })
                        .then(() => {
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
                console.log('Error parsing JSON response:', e);
                handleUnexpectedError();
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Network Error',
                text: 'There was an issue connecting to the server. Please try again.'
            });
        }
    });
}


function saveSingleFieldFormat(isUpdate = false) {

    let paramBoxes = document.getElementsByClassName('param-box');
    let allFields = [];

    const action = isUpdate ? 'update' : 'add';
    const submittedForm = document.getElementById('test-details-form');
    const formData = new FormData(submittedForm);


    const catId = document.getElementById('parent-category-id').value;
    const testId = document.getElementById('test-id').value;
    const testNm = document.getElementById('test-name').value.trim();
    const testPrc = document.getElementById('test-price').value.trim();
    const dsc = editors['test-description'].getData().trim();
    const prep = editors['test-process'].getData().trim();

    // const editorInstanceDsc = editors['test-description'];
    // const dsc = editorInstanceDsc ? editorInstanceDsc.getData().trim() : '';
    // const editorInstancePrep = editors['test-process'];
    // const prep = editorInstancePrep ? editorInstancePrep.getData().trim() : '';


    if (!testNm || !testPrc) {
        Swal.fire('Error', `Add ${!testNm ? 'Test name' : 'price'}!`, 'info');
        return;
    }

    const paramId = [];
    const paramName = [];
    const paramUnit = [];
    const standardRangeId = [];
    const childRange = [];
    const adultMaleRange = [];
    const adultFemalRange = [];
    const generalRange = [];


    Array.from(paramBoxes).forEach(function (box) {
        // Initialize an array to hold fields for the current paramBox
        let paramBoxFields = [];

        // Select all input and textarea elements within the current param-box
        let fields = box.querySelectorAll('input, textarea');

        fields.forEach(function (field) {
            // Push each field's name and value as an array into paramBoxFields
            paramBoxFields.push([field.name, field.value]);
        });

        // Push the array of fields for this paramBox into allFields
        allFields.push(paramBoxFields);
    });

    allFields.forEach(function (eachParambox, index) {
        const boxNo = index + 1;
        
        eachParambox.forEach(function (eachField) {
            if (eachField[0] === 'param-name') {
                var parameterName = eachField[1];
                paramName.push(parameterName);

            }
            if (eachField[0] === 'param-unit') {
                var parameterUnit = eachField[1];
                paramUnit.push(parameterUnit);
            }

            if (!parameterName || !parameterUnit) {
                let errorMessage = 'Please enter ';
            
                if (!parameterName && !parameterUnit) {
                    errorMessage += 'parameter name and unit';
                } else if (!parameterName) {
                    errorMessage += 'parameter name';
                } else if (!parameterUnit) {
                    errorMessage += 'parameter unit';
                }
            
                errorMessage += ' or delete the parameter.';
                Swal.fire('Error', errorMessage, 'error');
                return;
            }

        })
        

        const testParamId = document.getElementById(`test-param-id-${boxNo}`).value.trim();
        const rangeAddEditId = document.getElementById(`standard-range-id-${boxNo}`).value.trim();

        // Get data from CKEditor if it's initialized
        const child = editors[`child-unit-data-${boxNo}`].getData().trim(); // (*key)
        const adultMale = editors[`adult-male-data-${boxNo}`].getData().trim(); // (*key)
        const adultFemale = editors[`adult-female-data-${boxNo}`].getData().trim(); // (*key)
        const general = editors[`general-range-data-${boxNo}`].getData().trim(); // (*key)

        paramId.push(testParamId);
        standardRangeId.push(rangeAddEditId);
        childRange.push(child);
        adultMaleRange.push(adultMale);
        adultFemalRange.push(adultFemale);
        generalRange.push(general);
    })

    // console.log(paramId);
    // console.log(paramName);
    // console.log(paramUnit);
    // console.log(standardRangeId);
    // console.log(childRange);
    // console.log(adultMaleRange);
    // console.log(adultFemalRange);
    // console.log(generalRange);
    


    formData.append('action', action);
    formData.append('param-row-count', allFields.length);
    formData.append('lab-cat-id', catId);
    formData.append('lab-test-id', testId);
    formData.append('lab-test-name', testNm);
    formData.append('lab-test-price', testPrc);
    formData.append('lab-test-dsc', dsc);
    formData.append('lab-test-process', prep);

    formData.append('testParamEditId', JSON.stringify(paramId));
    formData.append('paramName', JSON.stringify(paramName));
    formData.append('paramUnit', JSON.stringify(paramUnit));
    formData.append('rangeAddEditId', JSON.stringify(standardRangeId));
    formData.append('childRange', JSON.stringify(childRange));
    formData.append('adultMaleRange', JSON.stringify(adultMaleRange));
    formData.append('adultFemaleRange', JSON.stringify(adultFemalRange));
    formData.append('generalRange', JSON.stringify(generalRange));


    $.ajax({
        url: 'ajax/add-edit-labtest-data.ajax.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success(response) {
            // console.log(response);
            try {
                const jsonResponse = JSON.parse(response);
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
    })
}