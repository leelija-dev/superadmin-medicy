const masterTable = document.getElementById('master-table');
const responseTable = document.getElementById('respnse-table-name');
const adminId = document.getElementById('user-id');
const ticketNo = document.getElementById('ticket-no');

const adminUsername = document.getElementById('user-name');
const msgSender = document.getElementById('msg-sender');
const email = document.getElementById('email');
const msgTitle = document.getElementById('msg-title');
const contact = document.getElementById('contact-no');

const inputedDocument = document.getElementById('fileInput1');
const oldFileData = document.getElementById('db-file-data-holder');

const queryResponse = document.getElementById('query-responce');
const masterUrl = document.getElementById('master-url');


$(document).ready(function() {
    const ticketNo = $('#ticket-number').val();
    const queryTable = $('#query-table').val();
    let dataFetchFlag = '1';  

    if (!ticketNo || !queryTable) {
        Swal.fire({ 
            icon: 'warning',
            title: 'Input Missing',
            text: 'Ticket number or Query table is missing!'
        });
        return;
    }

    $.ajax({
        url: 'ajax/ticket-query-request-fetch.ajax.php', 
        type: 'POST',  
        data: {
            ticketNo: ticketNo,
            tableIdentity: queryTable,
        },
        success: function(response) {
            var jsonResponse = JSON.parse(response);
            if(jsonResponse.status){
                console.log(jsonResponse.masterTable);
                
                $('#master-table').val(jsonResponse.masterTable);
                $('#respnse-table-name').val(jsonResponse.responseTable);
                
                $('#db-file-data-holder').val(jsonResponse.data.attachment);
                console.log(jsonResponse); 
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({ 
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while submitting the form. Please try again.'
            });
            console.log('AJAX Error:', status, error);
        }
    });
});




function ticketQueryResponse() {
    const form = document.getElementById('admin-ticket-response'); 
    const submitFormData = new FormData(form);

    // Validate form fields, skipping file inputs
    for (let [key, value] of submitFormData.entries()) {
        if (key !== 'new-file-input' && key !== 'prev-file-input') {
            if (value.trim() === '') { 
                Swal.fire({
                    icon: 'warning',
                    title: 'Alert',
                    text: `Please fill in the ${key} field.`,
                });
                return; // Stop submission if validation fails
            }
        }
    }
    
    // AJAX submission
    $.ajax({
        url: 'ajax/ticket-query-response.ajax.php',
        type: 'POST',
        data: submitFormData,  // Send the FormData directly
        contentType: false,  // Let FormData handle the content type
        processData: false,  // Let FormData handle the data processing
        success: function(response) {
            var jsonResponse = JSON.parse(response);
            console.log(jsonResponse);
            // Check if response status is success
            if (jsonResponse.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: jsonResponse.message,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'requests.php'; // Redirect on success
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: jsonResponse.message,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'requests.php'; // Redirect on error
                    }
                });
            }
            // Optionally, reset form fields (you can modify field names to match your actual form IDs)
            form.reset();
        },
        
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while submitting the form.',
            });
        }
    });
}








//  file upload function (image/pdf)
function takeInputFile(fileInput, fileShowDivId) {
    const file = fileInput.files[0];

    if (file) {
        const filePreview = document.getElementById(fileShowDivId);
        filePreview.innerHTML = ''; // Clear any existing content

        const reader = new FileReader();
        reader.onload = function(e) {
            if (file.type.includes('image')) {
                filePreview.innerHTML = `
                    <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                        <img src="${e.target.result}" style="max-width: 100%; max-height: 12rem;" />
                    </div>`;
            } else if (file.type === 'application/pdf') {
                filePreview.innerHTML = `
                    <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                        <embed src="${e.target.result}" type="application/pdf" style="max-width: 100%; max-height: 12rem;" />
                    </div>`;
            } else {
                filePreview.innerHTML = '<p>Select PDF or JPEG/JPG/PNG files.</p>';
                fileInput.value = ''; // Clear the input
                Swal.fire({
                    icon: 'error',
                    title: 'Alert',
                    text: 'Select PDF or JPEG/JPG/PNG files'
                });
                return;
            }
        };
        reader.readAsDataURL(file);
    }
}





