<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'doctors.class.php';
require_once CLASS_DIR.'doctor.category.class.php';


$docId = $_GET['docId'];

$doctors = new Doctors();
$showDoctor = $doctors->showDoctorById($docId);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>lab-test.css">

</head>

<body class="mx-2">

    <?php
        foreach ($showDoctor as $doctor) {
            $docId = $doctor['doctor_id'];
            $docRegNo = $doctor['doctor_reg_no'];
            $docName = $doctor['doctor_name'];
            $docSplz = $doctor['doctor_specialization'];
            $docDegree = $doctor['doctor_degree'];
            $docAlsoWith = $doctor['also_with'];
            $docAddress = $doctor['doctor_address'];
            $docEmail = $doctor['doctor_email'];
            $docPhno = $doctor['doctor_phno'];
        }
    ?>

    <form>
        <input type="hidden" id="docId" name="nm_option" value="<?php echo $docId;?>">
        <div class="form-group">
            <label for="doc-name" class="col-form-label">Doctor Name:</label>
            <input type="text" class="form-control" id="doc-name" value="<?php echo $docName; ?>">
        </div>
        <div class="form-group">
            <label for="doc-reg-no" class="col-form-label">Doctor Reg. No:</label>
            <input type="text" class="form-control" id="doc-reg-no" value="<?php echo $docRegNo; ?>">
        </div>
        <div class="form-group">
            <label for="doc-splz" class="col-form-label">Doctor Specialization:</label>
                <select class="form-control my-2" name="" id="doc-splz">
                    <?php
                    //Variable Creay=ted to Show Doctor Names
                    $docSpecialization = $docSplz;

                    //initilizing Doctors Category
                    $doctorCategory = new DoctorCategory();
                    $docSplz = $doctorCategory->showDoctorCategoryById($docSpecialization);
                    foreach($docSplz as $docSplzShow){
                        $docCategoryId = $docSplzShow['doctor_category_id'];
                        $docCategoryName = $docSplzShow['category_name'];
                    
                        echo '<option value="'.$docCategoryId.'">'.$docCategoryName.'</option>';
                    }
                    $docCategory = $doctorCategory->showDoctorCategory();

                    foreach ($docCategory as $showDocCategory) {
                        $docCategoryId = $showDocCategory['doctor_category_id'];
                        $docCategoryName = $showDocCategory['category_name'];

                        echo '<option value="'.$docCategoryId.'">'.$docCategoryName.'</option>';
                    }

                    ?>
            </select>
            <!-- <input type="text" class="form-control" value="<?php // echo $docSpecialization; ?>"> -->
        </div>
        <div class="form-group">
            <label for="doc-degree" class="col-form-label">Doctor Degree:</label>
            <input type="text" class="form-control" id="doc-degree" value="<?php echo $docDegree; ?>">
        </div>
        <div class="form-group">
            <label for="doc-with" class="col-form-label">Doctor Also With:</label>
            <input type="text" class="form-control" id="doc-with" value="<?php echo $docAlsoWith; ?>">
        </div>
        <div class="form-group">
            <label for="doc-email" class="col-form-label">Doctor Email:</label>
            <input type="text" class="form-control" id="doc-email" value="<?php echo $docEmail; ?>">
        </div>
        <div class="form-group">
            <label for="doc-phno" class="col-form-label">Doctor Contact Number:</label>
            <input type="text" class="form-control" id="doc-phno" value="<?php echo $docPhno; ?>">
        </div>
        <div class="form-group">
            <label for="doc-address" class="col-form-label">Doctor Address:</label>
            <textarea class="form-control" id="doc-address" rows="3"><?php echo $docAddress; ?></textarea>
        </div>

        <div class="reportUpdate" id="reportUpdate">
            <!-- Ajax Update Reporet Goes Here -->
        </div>
        <div class="d-md-flex justify-content-md-end">
            <button type="button" class="btn btn-sm btn-primary" onclick="editDoc()">Save changes</button>
        </div>
    </form>

    <script>
    function editDoc() {
        let docId = $("#docId").val();
        let docName = document.getElementById("doc-name").value;
        let docRegNo = document.getElementById("doc-reg-no").value;
        let docSpecialization = document.getElementById("doc-splz").value;
        let docDegree = document.getElementById("doc-degree").value;
        let docAlsoWith = document.getElementById("doc-with").value;
        let docEmail = document.getElementById("doc-email").value;
        let docPhno = document.getElementById("doc-phno").value;
        let docAddress = document.getElementById("doc-address").value;

        let url = "doctors.edit.ajax.php?docId=" + escape(docId) + "&docName=" + escape(docName) + "&docRegNo=" +
            escape(docRegNo) + "&docSpecialization=" + escape(docSpecialization) + "&docDegree=" + escape(docDegree) + "&docAlsoWith=" + escape(docAlsoWith)+ "&docEmail=" + escape(docEmail) + "&docPhno=" + escape(docPhno) + "&docAddress=" + escape(docAddress);
        
        request.open('GET', url, true);
 
        request.onreadystatechange = getEditUpdates;

        request.send(null);
    }

    function getEditUpdates() {
        if (request.readyState == 4) {
            // alert("Hello");

            if (request.status == 200) {
                // alert("Hello");

                var xmlResponse = request.responseText;
                // alert(xmlResponse);

                document.getElementById('reportUpdate').innerHTML = xmlResponse;
            } else if (request.status == 404) {
                alert("Request page doesn't exist");
            } else if (request.status == 403) {
                alert("Request page doesn't exist");
            } else {
                alert("Error: Status Code is " + request.statusText);
            }
        }
    } //eof
    </script>

    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <!-- <script src="<?php echo PLUGIN_PATH ?>bootstrap/js/bootstrap.bundle.min.js"></script> -->
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.min.js"></script>


    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>


</body>

</html>