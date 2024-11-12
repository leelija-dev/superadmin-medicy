<?php

require_once dirname(__DIR__) . '/config/constant.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'empRole.class.php';

$designId = $_GET['designationId'];
$designation= new Emproles();
$showDesignation = $designation->desigShowID($designId);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>lab-test.css">

</head>

<body class="mx-2">

    <?php
    $showDesignation = json_decode($showDesignation);

    if ($showDesignation !== null) {
       $desigName = $showDesignation->desig_name;
    }
    ?>

    <form>
    <input type="hidden" id="designId" name="nm_option" value="<?php echo $designId; ?>">
        <div class="form-group">
            <label for="" class="col-form-label">Designation Name:</label>
            <input type="text" class="form-control" id="desigName" value="<?php echo $desigName; ?>">
        </div>
        <div class="reportUpdate" id="reportUpdate">
            <!-- Ajax Update Reporet Goes Here -->
        </div>
        <div class="d-md-flex justify-content-md-end">
            <button type="button" class="btn btn-sm btn-primary" onclick="editEmp()">Save changes</button>
        </div>
    </form>

    <script>
        function editEmp() {
            let designId = $("#designId").val();
            let desigName = document.getElementById("desigName").value;
            
            let url = "emprole.edit.ajax.php?designId=" + escape(designId) + "&desigName=" + escape(desigName);

            request.open('GET', url, true);

            request.onreadystatechange = getEditUpdates;

            request.send(null);
        }

        function getEditUpdates() {
            if (request.readyState == 4) {

                if (request.status == 200) {
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
        } 
    </script>

    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?php echo PLUGIN_PATH ?>bootstrap-5.0.2/js/bootstrap.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>bootstrap-5.0.2/js/bootstrap.min.js"></script>


    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>


</body>

</html>