<?php 
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
// require_once CLASS_DIR.'measureOfUnit.class.php';
require_once CLASS_DIR.'itemUnit.class.php';


$ItemUnit = new ItemUnit();

$unitId = $_GET['Id'];

$itemUnitName = $ItemUnit->itemUnitName($unitId);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">

</head>

<body class="mx-2">

    <?php
    ?>

    <form>
        <!-- <input type="hidden" id="unitId" value=""> -->
      
            <label for="unit-srt-name" class="form-label mb-0">Unit Name:</label>
            <input type="text" class="form-control" id="item-unit-name" value="<?= $itemUnitName ?>">
    
        <div class="mt-2 reportUpdate" id="reportUpdate">
            <!-- Ajax Update Reporet Goes Here -->
        </div>

        <div class="mt-2 d-flex justify-content-end">
            <button type="button" class="btn btn-sm btn-primary" onclick="editUnit()">Update</button>
        </div>

    </form>

    <script>
    function editUnit() {
        let unitName = document.getElementById("item-unit-name").value;
        let url = "unit.Edit.ajax.php";

        // Set up the request
        request.open('POST', url, true);
        
        // Set the Content-Type header
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Define what happens on successful data submission
        request.onload = function () {
            // Handle response here
            getEditUpdates();
        };

        // Define what happens in case of an error
        request.onerror = function () {
            // Handle error here
            alert('Error occurred while sending request.');
        };

        // Send the request with the data
        request.send('id=' + encodeURIComponent(<?= $unitId;?>) + '&item-unit-name=' + encodeURIComponent(unitName));
    }

    function getEditUpdates() {
        if (request.readyState == 4) {
            if (request.status == 200) {
                var xmlResponse = request.responseText;
                document.getElementById('reportUpdate').innerHTML = xmlResponse;
            } else if (request.status == 404) {
                alert("Request page doesn't exist");
            } else if (request.status == 403) {
                alert("Request page doesn't exist");
            } else {
                alert("Error: Status Code is " + request.statusText);
            }
        }
    } //eof getEditUpdates
    </script>

    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.min.js"></script>

</body>

</html>