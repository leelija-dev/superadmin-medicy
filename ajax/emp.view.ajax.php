<?php

require_once dirname(dirname(__DIR__)) . '/config/constant.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'employee.class.php';

$empId = $_GET['employeeId'];

$employees = new Employees();
$showEmployee = $employees->empDisplayById($empId);

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
    $showEmployee = json_decode($showEmployee);

    if ($showEmployee !== null) {
        $empId = $showEmployee->emp_id;
        $empUsername = $showEmployee->emp_username;
        $empName = $showEmployee->emp_name;
        $empRole = $showEmployee->emp_role;
        $empEmail = $showEmployee->emp_email;
        $empAddress = $showEmployee->emp_address;
        $empContact = $showEmployee->contact;
    }
    // print_r($showEmployee);
    // foreach ($showEmployee as $Employee) {
    //     print_r($Employee);
    //     $empId = $Employee['emp_id'];
    //     $empUsername = $Employee['emp_username'];
    //     $empName = $Employee['emp_name'];
    //     $empRole = $Employee['emp_role'];
    //     $empEmail = $Employee['emp_email'];
    //     $empAddress = $Employee['emp_address'];
    // }
    ?>

    <form>
        <input type="hidden" id="empId" name="nm_option" value="<?php echo $empId; ?>">
        <div class="form-group">
            <label for="" class="col-form-label">Employee Username:</label>
            <input type="text" class="form-control" id="empUsername" value="<?php echo $empUsername; ?>">
        </div>
        <div class="form-group">
            <label for="" class="col-form-label">Employee Name:</label>
            <input type="text" class="form-control" id="empName" value="<?php echo $empName; ?>">
        </div>
        <div class="form-group">
            <label for="" class="col-form-label">Employee Role:</label>
            <input type="text" class="form-control" id="empRole" value="<?php echo $empRole; ?>">
        </div>
        <div class="form-group">
            <label for="" class="col-form-label">Employee Email:</label>
            <input type="text" class="form-control" id="empEmail" value="<?php echo $empEmail; ?>">
        </div>
        <div class="form-group">
            <label for="" class="col-form-label">Contact:</label>
            <input type="text" class="form-control" id="contact" value="<?php echo $empContact; ?>" minlength="10" maxlength="10">
        </div>
        <div class="form-group">
            <label for="empAddress" class="col-form-label">Employee Address:</label>
            <textarea class="form-control" name="empAddress" id="empAddress" rows="4"><?php echo $empAddress; ?></textarea>
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
            let empId = $("#empId").val();
            let empUsername = document.getElementById("empUsername").value;
            let empName = document.getElementById("empName").value;
            let empRole = document.getElementById("empRole").value;
            let empEmail = document.getElementById("empEmail").value;
            let empContact = document.getElementById("contact").value;
            // console.log(editTestCategoryDsc);
            let url = "emp.edit.ajax.php?empId=" + escape(empId) + "&empUsername=" + escape(empUsername) + "&empName=" + escape(empName) + "&empRole=" + escape(empRole) + "&empContact=" + escape(empContact) + "&empEmail=" + escape(empEmail);
            // console.log(url);
            // alert('Working');
            // $("#reportUpdate").html('<iframe width="99%" height="40px" frameborder="0" allowtransparency="true" src="'+url+'"></iframe>');
            // alert("Hello");
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