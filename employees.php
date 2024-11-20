<?php
require_once 'config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR   . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once SUP_ADM_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR   . 'employee.class.php';
require_once CLASS_DIR   . 'encrypt.inc.php';
require_once CLASS_DIR   . 'utility.class.php';
require_once CLASS_DIR   . 'empRole.class.php';
require_once CLASS_DIR   . 'rbacController.class.php';




$Utility     = new Utility;
$employees   = new Employees();
$desigRole   = new Emproles();
$RBAC        = new RbacController();


$currentUrl = $Utility->currentUrl();
$showEmployees = $employees->employeesDisplay();
// $adminId = url_dec($_GET['customerId']);
$adminId = isset($_GET['customerId']) ? url_dec($_GET['customerId']) : null;
$showEmployees = $employees->employeesDisplay($adminId);
$showDesignation = $desigRole->designationRoleCheckForLogin();
$showDesignation = json_decode($showDesignation, true);
// print_r($showDesignation);
$permissionDetails = json_decode($RBAC->selectPermissionTableDetails());

if (isset($_POST['add-emp']) == true) {


    // $empName      = $_POST['emp-name'];
    $firstName    = $_POST['fname'];
    $lastName     = $_POST['lname'];
    $empUsername  = $_POST['emp-username'];
    $empMail      = $_POST['emp-mail'];
    $empContact   = $_POST['contact'];
    $empRole      = $_POST['emp-role'];
    $empPass      = $_POST['emp-pass'];
    $empCPass     = $_POST['emp-cpass'];
    $empAddress   = $_POST['emp-address'];
    $empPermission = isset($_POST['permissions']) ? $_POST['permissions'] : [];
    $permissions = !empty($empPermission) ? implode(',', $empPermission) : '';

    echo 'fname-'.$firstName.'lname-'. $lastName.'empUser-'.$empUsername.'mail-'.$empMail.'contact-'.$empContact.'role-'.$empRole.'pass-'.$empPass.'cpass-'.$empCPass.'add-'.$empAddress.'permission-'.$permissions;
    
    if ($empPass === $empCPass) {
        $wrongPasword = false;

        $addEmployee = $employees->addEmp($adminId, $empUsername, $firstName, $lastName, $empRole, $permissions, $empMail, $empContact, $empAddress, $empPass);

        if ($addEmployee) {
            $Utility->redirectURL($currentUrl, 'SUCCESS', 'Employee Added Successfuly  !');
        } else {
            echo "<script>alert('Employee Insertion Failed!')</script>";
        }
    } else {
        echo "<script>alert('Password Did Not Matched!')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Medicy Employees</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/employees.css">
    <style>
        #toggle {
            /* position: absolutte;
            top: 25%;
            left: 200px; */
            position: relative;
            float: right;
            transform: translateY(-115%);
            width: 30px;
            height: 30px;
            background: url(img/hide-password.png);
            /* background-color: black; */
            background-size: cover;
            cursor: pointer;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include SUP_ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include SUP_ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Employees</h1>

                    <!-- DataTales Example -->

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="d-flex">
                                <h6 class="m-0 font-weight-bold text-primary">Employees List</h6>
                                <?php
                                if (isset($_GET['action'])) {
                                    if (isset($_GET['msg'])) {
                                        $message = htmlspecialchars($_GET['msg']); 
                                        echo "<p id='message' class='mt-n2 text-center px-5 py-3 rounded w-75 bg-success text-white ' style='position:absolute; margin-left:120px;'><strong>$message</strong></p>";
                                        echo "<script>
                                                setTimeout(function() {
                                                    document.getElementById('message').style.display = 'none';
                                                }, 3000);
                                            </script>";
                                    }
                                }
                                ?>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bd-example-modal-lg">Add New Employee</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Email</th>
                                            <th>Start date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        //employeesDisplay function initilized to feth employees data
                                        // $table = 'admin_id';
                                        // $showEmployees = $employees->selectEmpByCol($table, $adminId);
                                        if (!$adminId) {
                                            if (!empty($showEmployees)) {

                                                foreach ($showEmployees as $showEmployees) {
                                                    $empId = $showEmployees['emp_id'];
                                                    $empUsername = $showEmployees['emp_username'];
                                                    $empName = $showEmployees['fname'].' '.$showEmployees['lname'];
                                                    $empRoleId = $showEmployees['emp_role'];
                                                    print_r($empRoleId);
                                                    $empRolData = $desigRole->designationRoleID($adminId, $empRoleId);
                                                    // print_r($empRolData);
                                                    $empRolDatas = json_decode($empRolData, true);
                                                    $empRole = '';
                                                    if (is_array($empRolDatas))
                                                        $empRole    = $empRolDatas['desig_name'];

                                                    $empMail = $showEmployees['emp_email'];
                                                    // $emp['employee_password'];
                                                    // $emp[''];

                                                    echo '<tr>
                                                        <td>' . $empId . '</td>
                                                        <td>' . $empUsername . '</td>
                                                        <td>' . $empName . '</td>
                                                        <td>' . $empRole . '</td>
                                                        <td>' . $empMail . '</td>
                                                        <td>2011/04/25</td>
                                                        <td>
                                                            <a class="text-primary" onclick="viewAndEdit(' . $empId . ')" title="Edit" data-toggle="modal" data-target="#empViewAndEditModal"><i class="fas fa-edit"></i></a>
    
                                                            <a class="delete-btn" data-id="' . $empId . '"  title="Delete"><i class="far fa-trash-alt"></i></a>
                                                        </td>
                                                    </tr>';
                                                }
                                            }
                                        } else {
                                            if (!empty($showEmployees)) {

                                                foreach ($showEmployees as $showEmployees) {
                                                    $empId = $showEmployees['emp_id'];
                                                    $empUsername = $showEmployees['emp_username'];
                                                    $empName = $showEmployees['fname'].' '.$showEmployees['lname'];
                                                    $empRoleId = $showEmployees['emp_role'];
                                                    $empRolData = $desigRole->designationRoleID($empRoleId);
                                                    $empRolDatas = json_decode($empRolData, true);
                                                    $empRole = '';
                                                    if ($empRolDatas['status'] == '1')
                                                        $designationData = $empRolDatas['data'];
                                                    $empRole    = $designationData['desig_name'] ? $designationData['desig_name'] : '';

                                                    $empMail = $showEmployees['emp_email'];
                                                    // $emp['employee_password'];
                                                    // $emp[''];

                                                    echo '<tr>
                                                        <td>' . $empId . '</td>
                                                        <td>' . $empUsername . '</td>
                                                        <td>' . $empName . '</td>
                                                        <td>' . $empRole . '</td>
                                                        <td>' . $empMail . '</td>
                                                        <td>2011/04/25</td>
                                                        <td>
                                                            <a class="text-primary" onclick="viewAndEdit(' . $empId . ')" title="Edit" data-toggle="modal" data-target="#empViewAndEditModal"><i class="fas fa-edit"></i></a>
    
                                                            <a class="delete-btn" data-id="' . $empId . '"  title="Delete"><i class="far fa-trash-alt"></i></a>
                                                        </td>
                                                    </tr>';
                                                }
                                            }
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
                <!--Entry Section-->
                <div class="col" style="margin: 0 auto; width:98%;">
                    <div class="card shadow mb-4">

                    </div>
                    <!-- ...........modal start........ -->
                    <div class="modal fade bd-example-modal-lg " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add New Employee</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="" method="post">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <div>
                                                    <label class="mb-0 mt-1" for="emp-name">First Name:</label>
                                                    <input class="form-control" type="text" name="fname" id="fname" maxlength="30" required>
                                                    </div>
                                                    <div>
                                                    <label class="mb-0 mt-1" for="emp-name">Last Name:</label>
                                                    <input class="form-control" type="text" name="lname" id="lname" maxlength="30" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-username">Employee Username:</label>
                                                    <input class="form-control" type="text" name="emp-username" id="emp-username" maxlength="12" required>
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-mail">Employee Mail:</label>
                                                    <input class="form-control" type="email" name="emp-mail" id="emp-mail" maxlength="100" required>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="contact" class="mb-0 mt-1">Contact:</label>
                                                    <input type="text" class="form-control" name='contact' id="contact" minlength="10" maxlength="10">
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-role">Employee Role:</label>
                                                    <select class="form-control" name="emp-role" id="emp-role" required>
                                                        <option value="role1">Choose role..</option>
                                                        <?php foreach ($showDesignation as $desig) { ?>
                                                            <option value="<?php echo $desig['id']; ?>"><?php echo $desig['desig_name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-address">Full Address:</label>
                                                    <textarea class="form-control" name="emp-address" id="emp-address" cols="30" rows="1" maxlength="255"></textarea>
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-pass">Password:</label>
                                                    <input class="form-control" type="password" name="emp-pass" id="emp-pass" maxlength="12" required>
                                                    <div id="toggle" onclick="showHide('emp-pass');"></div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-conf-pass">Confirm Password:</label>
                                                    <input class="form-control" type="password" name="emp-cpass" id="emp-conf-pass" maxlength="12" required>
                                                    <div id="toggle" onclick="showHide('emp-conf-pass');"></div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="mt-2 mb-n2" for="emp-address">Access Permission</label>
                                                    <div class="p-3" style="height: 165px; overflow-y: auto;" data-spy="scroll">
                                                        <?php
                                                       if ($permissionDetails->status == 1) {
                                                        foreach ($permissionDetails->data as $permission) {
                                                            echo '<input type="checkbox" name="permissions[]" value="' . $permission->permission_id . '">' . htmlspecialchars($permission->permissions) . '<br>';
                                                        }
                                                    } else {
                                                        echo 'No permissions found.';
                                                    }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2 me-md-2"> -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button class="btn btn-success me-md-2" type="submit" name="add-emp">Add Now</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- ...........modal end........ -->
                    </div>
                </div>
                <!--End Entry Section-->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include SUP_ROOT_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Emp Edit and View Modal -->
    <div class="modal fade bd-example-modal-lg" id="empViewAndEditModal" tabindex="-1" role="dialog" aria-labelledby="empViewAndEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="min-width: 900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="empViewAndEditModalLabel">Employee Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body viewnedit">
                    <!-- MODAL CONTENT GOES HERE BY AJAX -->
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="refreshPage()">Update</button>
                </div> -->
            </div>
        </div>
    </div>
    <!-- Emp Edit and View Modal End -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Custom Javascript -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>
    <script>
        viewAndEdit = (empId) => {
            let employeeId = empId;
            let url = "ajax/emp.view.ajax.php?employeeId=" + employeeId;
            $(".viewnedit").html('<iframe width="99%" height="440px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } // end of viewAndEdit function
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo JS_PATH ?>demo/datatables-demo.js"></script>


    <script>
        $(document).ready(function() {
            $(document).on("click", ".delete-btn", function() {

                if (confirm("Are you want delete data?")) {
                    empId = $(this).data("id");
                    //echo $empDelete.$this->conn->error;exit;

                    btn = this;
                    $.ajax({
                        url: "ajax/employee.Delete.ajax.php",
                        type: "POST",
                        data: {
                            id: empId
                        },
                        success: function(response) {

                            if (response == 1) {
                                $(btn).closest("tr").fadeOut()
                            } else {
                                // $("#error-message").html("Deletion Field !!!").slideDown();
                                // $("success-message").slideUp();
                                alert(response);
                            }

                        }
                    });
                }
                return false;

            })

        })
    </script>
    <script>
        function showHide(fieldId) {
            const password = document.getElementById(fieldId);
            const toggle = document.getElementById('toggle');

            if (password.type === 'password') {
                password.setAttribute('type', 'text');
                // toggle.classList.add('hide');
            } else {
                password.setAttribute('type', 'password');
                // toggle.classList.remove('hide');
            }
        }
    </script>


</body>

</html>