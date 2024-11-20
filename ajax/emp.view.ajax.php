<?php

require_once dirname(__DIR__) . '/config/constant.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'empRole.class.php';
require_once CLASS_DIR . 'rbacController.class.php';

$empId = $_GET['employeeId'];

$employees   = new Employees();
$desigRole   = new Emproles();
$RBAC        = new RbacController();

$showEmployee = $employees->empDisplayById($empId);
$showDesignation = $desigRole->designationRoleCheckForLogin();
$showDesignation = json_decode($showDesignation, true);
$permissionDetails = json_decode($RBAC->selectPermissionTableDetails());

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
    $showEmployee = json_decode($showEmployee);

    if ($showEmployee !== null) {
        $empId        = $showEmployee->emp_id;
        $empUsername  = $showEmployee->emp_username;
        $empFirstName = $showEmployee->fname;
        $empLastName  = $showEmployee->lname;
        $empPermission= $showEmployee->permission_id;
        $empRole      = $showEmployee->emp_role;
        $empEmail     = $showEmployee->emp_email;
        $empAddress   = $showEmployee->emp_address;
        $empContact   = $showEmployee->contact;
        $empPermission = $showEmployee->permission_id;
        $empPermissionArray = explode(',', $empPermission);
        // echo $empPermission;
    }
    ?>

    <form>
        <div class="row">
            <div class="col-md-6">
                <input type="hidden" id="empId" name="nm_option" value="<?php echo $empId; ?>">
                <div class="col-md-12">
                    <div>
                        <label class="mb-0 mt-1" for="emp-name">First Name:</label>
                        <input type="text" class="form-control" id="empFirstName" value="<?php echo $empFirstName; ?>">
                    </div>
                    <div>
                        <label class="mb-0 mt-1" for="emp-name">Last Name:</label>
                        <input type="text" class="form-control" id="empLastName" value="<?php echo $empLastName; ?>">
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="mb-0 mt-1" for="emp-username">Employee Username:</label>
                    <input type="text" class="form-control" id="empUsername" value="<?php echo $empUsername; ?>">
                </div>

                <div class="col-md-12">
                    <label class="mb-0 mt-1" for="emp-mail">Employee Mail:</label>
                    <input type="text" class="form-control" id="empEmail" value="<?php echo $empEmail; ?>">
                </div>

                <div class="col-md-12">
                    <label for="contact" class="mb-0 mt-1">Contact:</label>
                    <input type="text" class="form-control" name='contact' id="contact" minlength="10" maxlength="10"
                        value="<?php echo $empContact; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-12">
                    <label class="mb-0 mt-1" for="emp-address">Full Address:</label>
                    <textarea class="form-control" name="empAddress" id="empAddress"
                        rows="1"><?php echo $empAddress; ?></textarea>
                </div>

                <div class="col-md-12">
                    <label class="mb-0 mt-1" for="emp-role">Employee Role:</label>
                    <select class="form-control" name="emp-role" id="emp-role" required>
                        <option value="role1">Choose role..</option>
                        <?php 
                            foreach ($showDesignation as $desig) { 
                                $selected = ($empRole == $desig['id']) ? 'selected' : '';
                            ?>
                        <option value="<?php echo $desig['id']; ?>" <?php echo $selected; ?>>
                            <?php echo htmlspecialchars($desig['desig_name']); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="mt-2 mb-n2" for="emp-address">Access Permission</label>
                    <div class="p-3" style="height: 165px; overflow-y: auto;" data-spy="scroll">
                        <?php
                            if ($permissionDetails->status == 1) {
                                foreach ($permissionDetails->data as $permission) {
                                    $checked = in_array($permission->permission_id, $empPermissionArray) ? 'checked' : '';
                        ?>
                        <input type="checkbox" id="permission" name="permissions[]"
                            value="<?php echo $permission->permission_id; ?>" <?php echo $checked; ?>>
                        <?php echo htmlspecialchars($permission->permissions); ?><br>
                        <?php
                                }
                            } else {
                                echo 'No permissions found.';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="reportUpdate" id="reportUpdate">
            <!-- Ajax Update Reporet Goes Here -->
        </div>
        <div class="d-md-flex justify-content-md-end mt-5 mr-3">
            <button type="button" class="btn btn-sm btn-primary" onclick="editEmp()">Save changes</button>
        </div>
    </form>

    <script>
    let request;
    function editEmp() {
        let empId = $("#empId").val();
        let empUsername = document.getElementById("empUsername").value;
        let empFName = document.getElementById("empFirstName").value;
        let empLName = document.getElementById("empLastName").value;
        let empEmail = document.getElementById("empEmail").value;
        let empContact = document.getElementById("contact").value;
        let empAddress = document.getElementById("empAddress").value;
        let empRole = document.getElementById("emp-role").value;

        let selectedPermissions = [];
        let permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]:checked');
        permissionCheckboxes.forEach((checkbox) => {
            selectedPermissions.push(checkbox.value);
        });

        //  empPermission is separated by comma
        let empPermission = selectedPermissions.join(",");

        // console.log('empId-', empId, 'empUsername-', empUsername, 'empFName-', empFName, 'empLName-', empLName, 'empEmail-', empEmail, 'empContact-', empContact, 'empAddress-', empAddress, 'empRole-', empRole, 'empPermission-', empPermission);

        let url = "emp.edit.ajax.php?empId=" + encodeURIComponent(empId) +
            "&empUsername=" + encodeURIComponent(empUsername) +
            "&empfName=" + encodeURIComponent(empFName) +
            "&empLName=" + encodeURIComponent(empLName) +
            "&empEmail=" + encodeURIComponent(empEmail) +
            "&empContact=" + encodeURIComponent(empContact) +
            "&empAddress=" + encodeURIComponent(empAddress) +
            "&empRole=" + encodeURIComponent(empRole) +
            "&empPermission=" + encodeURIComponent(empPermission);

        request = new XMLHttpRequest();
        request.open('GET', url, true);

        // Handle the response after the request is sent
        request.onreadystatechange = getEditUpdates;
        request.send(null);
    }

    function getEditUpdates() {
        if (request.readyState == 4) {
            if (request.status == 200) {
                var xmlResponse = request.responseText;

                var reportUpdateElement = document.getElementById('reportUpdate');
                if (reportUpdateElement) {
                    reportUpdateElement.innerHTML = xmlResponse;
                    setTimeout(function() {
                        reportUpdateElement.innerHTML = '';
                    }, 3000);
                } else {
                    console.error("Element with ID 'reportUpdate' not found.");
                }
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

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>


</body>

</html>