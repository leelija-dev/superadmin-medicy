const drugPermitFilePath = document.getElementById('drupPermit-file-path');
const drugPermitForm20 = document.getElementById('drupPermit-form20');
const drugPermitForm21 = document.getElementById('drupPermit-form21');

const validateCheck20 = document.getElementById('validate-form20');
const validateCheck21 = document.getElementById('validate-form21');
const oldform20 = document.getElementById('drupPermit-form20');
const oldform21 = document.getElementById('drupPermit-form21');

const gstinVal = document.getElementById('gstin');
const panVal = document.getElementById('pan');

if (document.getElementById("nav-pan-flag").innerHTML.trim() === '1') {
    // control home menue
    document.getElementById("home").classList.remove("active");
    document.getElementById("home-tab").classList.remove("active");
    document.getElementById("home").classList.add("fade");

    // control menue 1
    document.getElementById("menu1").classList.remove("fade");
    document.getElementById("menu1").classList.add("show", "active");
    document.getElementById("menu1-tab").classList.add("active");

    // control menue 2
    document.getElementById("menu2").classList.remove("active");
    document.getElementById("menu2-tab").classList.remove("active");

    document.getElementById("nav-pan-flag").innerHTML = 'nav-tab';
}


// ==========================================

function validateFileType() {
    var fileName = document.getElementById("img-uv-input").value;
    console.log(fileName);
    var idxDot = fileName.lastIndexOf(".") + 1;
    var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
    if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
        document.getElementById("err-show").classList.add("d-none");
    } else {
        document.getElementById("err-show").classList.remove("d-none");
        // Show current image when error occurs
        document.querySelector('.img-uv-view').src = "<?= $healthCareLogo; ?>";
    }
}


// =================================================

// Trigger file input when preview is clicked
function triggerFileInput(inputId) {
    document.getElementById(inputId).click();
}

// Preview the selected file
function previewFile(input, previewId) {
    const file = input.files[0];
    const reader = new FileReader();

    reader.onloadend = function() {
        const previewElement = document.getElementById(previewId);
        const fileType = file.type;

        if (fileType.includes('image')) {
            previewElement.innerHTML = `<img src="${reader.result}" style="max-width: 100%; max-height: 12rem;">`;
        } else if (fileType === 'application/pdf') {
            previewElement.innerHTML = `<embed src="${reader.result}" style="max-width: 100%; max-height: 12rem;">`;
        } else {
            previewElement.innerHTML = `<p>File type not supported for preview</p>`;
        }
    };

    if (file) {
        reader.readAsDataURL(file);
    }

    if (previewId == 'imagePreviewForm20') {
        document.getElementById('validate-form20').value = '1';
    }

    if (previewId == 'imagePreviewForm21') {
        document.getElementById('validate-form21').value = '1';
    }
}

// Validate fields and adjust required attributes
function validateFields() {
    const gstin = document.getElementById('gstin');
    const pan = document.getElementById('pan');

    if (gstin.value !== '') {
        pan.removeAttribute("required");
        document.getElementById('pan-span').style.display = 'none';
    } else {
        pan.setAttribute("required", "required");
        document.getElementById('pan-span').style.display = 'inline';
    }

    if (pan.value !== '') {
        gstin.removeAttribute("required");
        document.getElementById('gstin-span').style.display = 'none';
    } else {
        gstin.setAttribute("required", "required");
        document.getElementById('gstin-span').style.display = 'inline';
    }
}

// Handle form submission
function submitDrugFormData() {
    // Get other form data
    const gstData = document.getElementById('gstin').value;
    const panData = document.getElementById('pan').value;
    let flagA = 0;
    let flagB = 0;

    if (validateCheck20.value == 0) {
        Swal.fire('Alert', 'Please upload form 20', 'error');
        return;
    }

    if (validateCheck21.value == 0) {
        Swal.fire('Alert', 'Please upload form 21', 'error');
        return;
    }

    if(gstinVal.value == ''){
        if(panVal.value == ''){
            Swal.fire('Alert', 'Please input either GST or PAN number!', 'error');
            return;
        }
    }

    const form20File = document.getElementById('form-20').files[0];
    const form21File = document.getElementById('form-21').files[0];

    // Create a new FormData object
    const formData = new FormData();
    if(validateCheck20.value == 1){
        formData.append('form20', form20File);
        formData.append('flagA', 1);
    }else if(validateCheck20.value == 2){
        formData.append('oldForm20', oldform20.value);
        formData.append('flagA', 2);
    }

    if(validateCheck21.value == 1){
        formData.append('form21', form21File);
        formData.append('flagB', 1);
    }else if(validateCheck21.value == 2){
        formData.append('oldForm21', oldform21.value);
        formData.append('flagB', 2);
    }

    // Append files and data to the FormData object
    formData.append('gstin', gstData);
    formData.append('pan', panData);

    // Send the AJAX request
    $.ajax({
        url: 'ajax/navTab-drug-permit-data-submit.php',
        type: 'POST',
        data: formData,
        processData: false, 
        contentType: false, 
        success: function(response) {
            // console.log(response);
            try {
                const parsedResponse = JSON.parse(response);
                console.log(parsedResponse);
                
                showAlert(parsedResponse.status, parsedResponse.message);
                // // If the submission is successful, reload the page
                //  if (parsedResponse.status === '1') {
                //      setTimeout(function() {
                //          location.reload(); // Reloads the page
                //      }, 1000); // You can adjust the delay as needed
                //  }
            } catch (e) {
                // console.error('Failed to parse response as JSON:', e);
                // console.log('Response:', response);
                showAlert('Error', 'An unexpected error occurred while processing the response.', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            showAlert('Error', 'An error occurred while processing the request. Please try again later.', 'error');
        }
    });
    
    // SweetAlert2 alert handler function
    function showAlert(status, message) {
        const icons = {
            '1': 'success',  // Changed to lowercase for SweetAlert
            '2': 'info',
            false : 'error',     // Changed to lowercase for SweetAlert
            'Failed': 'error'     // Added '3' for failure
        };
    
        // Determine the icon based on the status
        const icon = icons[status] || 'error'; // Default to 'error' if status is not recognized
    
        Swal.fire({
            title: status === '1' ? 'Success' : status === '2' ? 'Info' : 'Failed',
            text: message,
            icon: icon,
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'clinic-setting.php?tab-control';
            }
        });
    }
    
}


if(validateCheck20.value != 0 && validateCheck21.value != 0){
    function setFilePreview(fileName, filePath, previewElementId) {
        var previewElement = document.getElementById(previewElementId);
        var fileExtension = fileName.split('.').pop().toLowerCase();
    
        var fullFilePath = filePath.endsWith('/') ? filePath + fileName : filePath + '/' + fileName;
    
        previewElement.innerHTML = '';
    
        if (fileExtension === 'pdf') {
            var iframe = document.createElement('iframe');
            iframe.src = fullFilePath;
            iframe.style.width = '70%';
            iframe.style.height = '400px'; 
            iframe.style.border = 'none'; 
            previewElement.appendChild(iframe);
        } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
            var img = document.createElement('img');
            img.src = fullFilePath;
            img.style.maxWidth = '70%'; 
            img.style.height = 'auto'; 
            previewElement.appendChild(img);
        } else {
            previewElement.innerHTML = '<span>Unsupported file type</span>';
        }
    }
    
    // Example usage
    setFilePreview(drugPermitForm20.value, drugPermitFilePath.value, 'imagePreviewForm20');
    setFilePreview(drugPermitForm21.value, drugPermitFilePath.value, 'imagePreviewForm21');
}



