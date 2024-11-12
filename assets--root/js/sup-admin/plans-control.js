document.addEventListener("DOMContentLoaded", function() {
    // Add feature
    document.getElementById('addFeature').addEventListener('click', function() {
        var featureDiv = document.createElement('div');
        featureDiv.className = 'form-group';
        featureDiv.innerHTML = `
            <div class="d-flex my-2 feature-row">
                <input type="text" class="form-control form-control-sm" name="features[]" placeholder="Feature" required>
                <button type="button" class="btn btn-sm btn-danger remove-feature rounded-right">
                    <i class="far fa-times-circle"></i>
                </button>
            </div>`;
        
        document.getElementById('feature-container').appendChild(featureDiv);
    });

    // Remove feature using event delegation
    document.getElementById('feature-container').addEventListener('click', function(e) {
        if (e.target && e.target.matches('.remove-feature, .remove-feature *')) {
            e.target.closest('.form-group').remove();
        }
    });
});





function addEditPlan(event) {
    event.preventDefault();
    const planId = document.getElementById('plan-id').value;
    const planName = document.getElementById('plan-name').value.trim();
    const planDuration = document.getElementById('plan-duration').value.trim();
    const planPrice = document.getElementById('plan-price').value.trim();
    const planStatus = document.getElementById('plan-status').value;
    const permissions = document.querySelectorAll('input[name="permissions[]"]:checked');
    const features = document.querySelectorAll('input[name="features[]"]');
    const flag = document.getElementById('flag-data').value;
    
    // Validation
    if (!planName) {
        Swal.fire('Error', 'Please enter the Plan Name.', 'info');
        return;
    }

    if (!planDuration) {
        Swal.fire('Error', 'Please enter the Plan Duration.', 'info');
        return;
    }

    if (!planPrice) {
        Swal.fire('Error', 'Please enter the Plan Price.', 'info');
        return;
    }

    if (!planStatus) {
        Swal.fire('Error', 'Please select the Plan Status.', 'info');
        return;
    }

    if (permissions.length === 0) {
        Swal.fire('Error', 'Please select at least one Access Permission.', 'info');
        return;
    }

    let validFeatures = true;
    features.forEach(feature => {
        if (!feature.value.trim()) {
            validFeatures = false;
        }
    });

    if (!validFeatures) {
        Swal.fire('Error','Please enter all Feature fields.','info');
        return;
    }

    const form = document.getElementById('plan-form');
    const formData = new FormData(form);

    let accessUrl = '';
    if (flag === '1') {
        accessUrl = 'ajax/plans.add.ajax.php';
    } else if (flag === '2') {
        accessUrl = 'plans.view.update.submit.ajax.php';
    }

    console.log(accessUrl);
    
    $.ajax({
        type: "POST",
        url: accessUrl,
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log(response);
            const successAlert = `<div class="alert alert-primary" role="alert">
                                    <strong>Success</strong> ${flag === '1' ? 'New Plan Added!' : 'Plan Updated!'}
                                  </div>`;
            document.getElementById('reportUpdate').innerHTML = successAlert;
            document.getElementById('plan-form').reset();
        },
        error: function() {
            Swal.fire('Error', 'There was an error processing your request. Please try again.', 'error');
        }
    });
}


function getView(url, paramName, paramValue, modalId) {
    let fullUrl = `${url}?${encodeURIComponent(paramName)}=${encodeURIComponent(paramValue)}`;
    
    // Set the iframe's source to load the content
    $(`#${modalId} iframe`).attr('src', fullUrl);
}


function parentReload(){
    parent.location.reload();
}