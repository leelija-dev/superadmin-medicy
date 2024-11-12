<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'empRole.class.php';
require_once CLASS_DIR . 'rbacController.class.php';

$empId = $_GET['employeeId'];

$employees = new Employees();
$showEmployee = json_decode($employees->empDisplayById($empId));
$desigRole = new Emproles();
$RBAC          = new RbacController;


$empRoleList = json_decode($RBAC->selectRolesTableDetails());

$permissionDetails = json_decode($RBAC->selectPermissionTableDetails());

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" type="text/css" />
    <link href="<?php echo CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>lab-test.css">

</head>

<body class="mx-2">

    <?php
    // print_r($showEmployee);

    if ($showEmployee !== null) {
        $empId = $showEmployee->emp_id;
        $empUsername = $showEmployee->emp_username;
        $empRoleId = $showEmployee->emp_role;
        // $empName = $showEmployee->emp_name;
        $fName = $showEmployee->fname;
        $lName = $showEmployee->lname;
        $empRolDetails = json_decode($RBAC->selectRolesTableDetails($empRoleId));
        // print_r($empRolDetails);

        if ($empRolDetails->status) {
            $empRole = $empRolDetails->data[0]->desig_name ?? '';
        }

        $empEmail = $showEmployee->emp_email;
        $empContact = $showEmployee->contact;

        $empAddress = $showEmployee->emp_address;

        $accesPermission = $showEmployee->permission_id;
        $empPermissionArray = explode(',', $accesPermission);
    }



    ?>

    <form>
        <input type="hidden" id="empId" name="nm_option" value="<?php echo $empId; ?>">

        <div class="container">
            <div class="row">
                <!-- First Column -->
                <div class="col-lg-6 col-12 mb-3">
                    <div class="form-group">
                        <label for="fname" class="col-form-label">Employee First Name:</label>
                        <input type="text" class="form-control" id="fname" value="<?php echo $fName; ?>">
                    </div>

                    <div class="form-group">
                        <label for="lname" class="col-form-label">Employee Last Name:</label>
                        <input type="text" class="form-control" id="lname" value="<?php echo $lName; ?>">
                    </div>

                    <div class="form-group">
                        <label for="empUsername" class="col-form-label">Employee Username:</label>
                        <input type="text" class="form-control" id="empUsername" value="<?php echo $empUsername; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="empEmail" class="col-form-label">Employee Email:</label>
                        <input type="email" class="form-control" id="empEmail" value="<?php echo $empEmail; ?>">
                    </div>

                    <div class="form-group">
                        <label for="contact" class="col-form-label">Contact:</label>
                        <input type="tel" class="form-control" id="contact" value="<?php echo $empContact; ?>" pattern="\d{10}" minlength="10" maxlength="10" required>
                    </div>
                </div>

                <!-- Second Column -->
                <div class="col-lg-6 col-12 mb-3">
                    <div class="form-group">
                        <label for="empAddress" class="col-form-label">Employee Address:</label>
                        <input type="text" class="form-control" id="empAddress" value="<?php echo $empAddress; ?>">
                    </div>
                    <div class="form-group">
                        <label for="empRole" class="col-form-label">Employee Role:</label>
                        <select class="form-control" name="empRole" id="empRole" required>
                            <option value="<?php echo $empRoleId; ?>"><?php echo $empRole; ?></option>
                            <?php foreach ($empRoleList->data as $role) {
                            ?>
                                <option value="<?php echo $role->id; ?>"><?php echo $role->desig_name; ?></option>
                            <?php
                            } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label" for="permissions">Access Permissions:</label>
                        <div class="p-3" style="height: 165px; overflow-y: auto;">
                            <?php
                            $count = 0;
                            foreach ($permissionDetails->data as $permissionData) {
                                if (in_array($permissionData->permission_id, $permissionArray)) {
                                    $count++;
                                    $isChecked = in_array($permissionData->permission_id, $empPermissionArray) ? 'checked' : '';
                            ?>
                                    <div class="row d-flex">
                                        <label>
                                            <input type="checkbox" name="permissions[]" id="permission-id-<?= $count; ?>" value="<?= $permissionData->permission_id; ?>" <?= $isChecked; ?>>
                                            <?= $permissionData->permissions; ?>
                                        </label>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-primary w-100" onclick="editEmp()">Save changes</button>
                    </div>

                </div>
            </div>

            <div class="row mt-2">

            </div>
        </div>
    </form>





    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>

    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>
    <script src="<?php echo JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>
    <script src="<?php echo JS_PATH ?>custom/employees.js"></script>

</body>

</html>