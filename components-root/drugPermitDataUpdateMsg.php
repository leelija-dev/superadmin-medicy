<div class="col-12 alert alert-success alert-dismissible fade show" role="alert" id="drugPermit-update-success">
    <strong>Success!</strong> Your documents is uploaded successfully!.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="d-none col-12 alert alert-danger alert-dismissible fade show" role="alert" id="drugPermit-update-fail">
    <strong>Fail!</strong> Updation Fail.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="d-none col-12 alert alert-danger alert-dismissible fade show" role="alert" id="drugPermit-fail-move">
    <strong>Fail!</strong> Fail to move upload file.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="d-none col-12 alert alert-primary alert-dismissible fade show" role="alert" id="drugPermit-fild-not-set">
    <strong>Fail!</strong> Fail to move upload file or both file is not submitted. Please submit all require data.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="d-none col-12 alert alert-info alert-dismissible fade show" role="alert" id="drugPermit-form-not-submitted">
    <strong>Fail!</strong> Form not submitted.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<script>
    const alterDivControlFun = (val) =>{
        if(val == '1'){
            document.getElementById('drugPermit-update-success').classList.remove('d-none');
            document.getElementById('drugPermit-update-fail').classList.add('d-none');
            document.getElementById('drugPermit-fail-move').classList.add('d-none');
            document.getElementById('drugPermit-fild-not-set').classList.add('d-none');
            document.getElementById('drugPermit-form-not-submitted').classList.add('d-none');
        }

        if(val == '00'){
            document.getElementById('drugPermit-update-success').classList.add('d-none');
            document.getElementById('drugPermit-update-fail').classList.remove('d-none');
            document.getElementById('drugPermit-fail-move').classList.add('d-none');
            document.getElementById('drugPermit-fild-not-set').classList.add('d-none');
            document.getElementById('drugPermit-form-not-submitted').classList.add('d-none');
        }

        if(val == '01'){
            document.getElementById('drugPermit-update-success').classList.add('d-none');
            document.getElementById('drugPermit-update-fail').classList.add('d-none');
            document.getElementById('drugPermit-fail-move').classList.remove('d-none');
            document.getElementById('drugPermit-fild-not-set').classList.add('d-none');
            document.getElementById('drugPermit-form-not-submitted').classList.add('d-none');
        }

        if(val == '10'){
            document.getElementById('drugPermit-update-success').classList.add('d-none');
            document.getElementById('drugPermit-update-fail').classList.add('d-none');
            document.getElementById('drugPermit-fail-move').classList.add('d-none');
            document.getElementById('drugPermit-fild-not-set').classList.remove('d-none');
            document.getElementById('drugPermit-form-not-submitted').classList.add('d-none');
        }

        if(val == '11'){
            document.getElementById('drugPermit-update-success').classList.add('d-none');
            document.getElementById('drugPermit-update-fail').classList.add('d-none');
            document.getElementById('drugPermit-fail-move').classList.add('d-none');
            document.getElementById('drugPermit-fild-not-set').classList.add('d-none');
            document.getElementById('drugPermit-form-not-submitted').classList.remove('d-none');
        }
    }


</script>