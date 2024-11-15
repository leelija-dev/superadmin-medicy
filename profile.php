<?php
require_once 'config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';  //check admin loggedin or not
// require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once SUP_ADM_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'supAdmin.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'empRole.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

// echo $supAdminId;

$Utility    = new Utility;
$superAdmin = new superAdmin;
$employees  = new Employees();
$desigRole  = new Emproles();

$currentUrl = $Utility->currentUrl();

$showEmployees = $employees->employeesDisplay();
$showDesignation = $desigRole->designationRoleCheckForLogin();
$showDesignation = json_decode($showDesignation, true);

$profileDetails = array();
if ($_SESSION['SUPER_ADMIN']) {
    $adminDetails = $superAdmin->supAdminDetails();
    $adminDetails = json_decode($adminDetails);
    if ($adminDetails->status) {
        $adminData = $adminDetails->data;

        foreach ($adminData as $adminData) {
            $firstName = $adminData->fname;
            $lastName  = $adminData->lname;
            $image     = $adminData->adm_img;
            if ($image != null) {
                $imagePath = SUP_ADM_IMG_PATH . $image;
            } else {
                $imagePath = SUP_ADM_IMG_PATH . 'default-human.png';
            }

            $userName  = $adminData->username;
            $email     = $adminData->email;
            $phone     = $adminData->mobile_no;
            $password  = $adminData->password;
            $address   = $adminData->address;
        }
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
                    <!-- <h1 class="h3 mb-2 text-gray-800">Employees</h1> -->

                    <!-- DataTales Example -->

                    <!-- <div class="card shadow mb-4"> -->
                    <div class="card-body">
                        <div class=" d-flex justify-content-center align-items-center">
                            <div class=" profile">
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="w-100 shadow p-3 mb-3 bg-white rounded ">
                                        <h2 class="h5 text-gray "><i class="fas fa-user"></i> <?= $userName ?></h2>
                                    </div>
                                </div>
                                <form class="user shadow p-3 mb-5 bg-white rounded" action="_config/form-submission/profileSetup-form.php" method="post" enctype="multipart/form-data" id="edit-profile">

                                    <div class="p-main d-flex justify-content-start align-items-start flex-wrap ml-3 mt-3">
                                        <div class="ml-3">
                                            <img class="img-uv-view shadow-lg " src="<?= ($image) ? $imagePath : ASSETS_PATH . 'images/undraw_profile.svg' ?>" alt="">
                                            <div class="position-absolute translate-middle ml-5">
                                                <input type="file" style="display:none;" id="img-uv-input" accept=".jpg,.jpeg,.png" name="profile-image" onchange="validateFileType()">
                                                <label for="img-uv-input" class="btn btn-sm btn-success ml-5 mt-n5 rounded-circle border-white"><i class="fas fa-camera"></i></label>
                                                <div class="alert alert-danger d-none" id="err-show" role="alert">
                                                    Only jpg/jpeg and png files are allowed!
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-name">
                                            <h2 class=""><?php echo $firstName . " " . $lastName ?></h2>
                                            <p class="text-primary"><?php echo $email ?></p>
                                        </div>
                                    </div>
                                    <h4>Account</h4>
                                    <hr>
                                    <div class="w-100 p-3 mb-2 ">
                                        <div class="p-pass form-group mb-3 d-flex justify-content-end">
                                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalCenter" onclick="passwordUpdate()" id="passwordChangeBtn">
                                                Password Change
                                            </button>
                                        </div>
                                        <!-- <div class="form-group row mb-3"> -->
                                        <div class="d-flex justify-content-between align-item-between flex-wrap">
                                            <label for="form-control">First Name</label>
                                            <input type="text" class="form-control col-md-6 mb-3" id="fname" name="fname" maxlength="20" value="<?= $firstName; ?>">
                                        </div>
                                        <div class="d-flex justify-content-between align-item-between flex-wrap">
                                            <label for="form-control">Last Name</label>
                                            <input type="text" class="form-control col-md-6 mb-3" id="lname" name="lname" maxlength="20" value="<?= $lastName; ?>">
                                        </div>
                                        <!-- </div> -->
                                        <div class="d-flex justify-content-between align-item-between flex-wrap">
                                            <label for="form-control">Username</label>
                                            <input type="text" class="form-control col-md-6 mb-3" id="user-name" name="user-name" maxlength="24" value="<?= $userName; ?>" disabled>
                                        </div>
                                        <!-- <div class="form-group row"> -->
                                        <div class="d-flex justify-content-between align-item-between flex-wrap">
                                            <label for="form-control">Email</label>
                                            <input type="email" class="form-control col-md-6 mb-3" id="email" name="email" maxlength="80" value="<?= $email; ?>">
                                        </div>
                                        <div class="d-flex justify-content-between align-item-between flex-wrap ">
                                            <label for="from-control">Contact</label>
                                            <input type="number" class="form-control col-md-6 mb-3 " id="mobile-number" name="mobile-number" maxlength="10" value="<?= $phone; ?>" max="9999999999">
                                        </div>
                                        <!-- </div> -->

                                        <div class="d-flex justify-content-between align-item-between flex-wrap ">
                                            <label for="from-control">Address</label>
                                            <textarea class="form-control col-md-6 mb-3" id="exampleFormControlTextarea1" name="address" rows="2"><?= $address; ?></textarea>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-success" type="submit" name="submit">UPDATE</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include SUP_ROOT_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- password change modal  -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Password Change</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body exampleModalCenter">

                </div>
            </div>
        </div>
    </div>
    <!-- password change modal end -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Custom Javascript -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>img-uv/img-uv.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo JS_PATH ?>demo/datatables-demo.js"></script>


    <script>
        function validateFileType() {
            var fileName = document.getElementById("img-uv-input").value;
            // console.log(fileName);
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {} else {
                // alert("Only jpg/jpeg and png files are allowed!");
                document.getElementById("err-show").classList.remove("d-none");
            }
        }

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


    <!-- password modal open -->
    <script>

        const resizeIframeHeight = (defaultHeightPX) => {
            let iframe = document.getElementById('passwordUpdateFrame');
            let contentHeight = iframe.contentWindow.document.body.scrollHeight + 'px';
            // Set default height to 400px
            let newHeight = Math.max(defaultHeightPX, parseInt(contentHeight));
            iframe.style.height = newHeight + 'px';
        }

        passwordUpdate = () => {
            let url = "ajax/super-admin-password-update.ajax.php";
            $('.exampleModalCenter').html(
                '<iframe id="passwordUpdateFrame" style="width: 100%; border: none; overflow: auto; display: block;" frameborder="0" allowtransparency="true" src="' +
                url + '" scrolling="no" onload="resizeIframeHeight(230)"></iframe>');
        }

        function updateButtonContent() {
            var button = document.getElementById("passwordChangeBtn");

            if (window.innerWidth < 784) {
                button.innerHTML = '<span title="Change Password"><i class="fas fa-key"></span>';
                button.onclick = passwordUpdate();
            } else {
                button.innerHTML = 'Password Change';
                button.onclick = passwordUpdate();
            }
        }
        updateButtonContent();
        window.addEventListener("resize", updateButtonContent);
    </script>

</body>

</html>