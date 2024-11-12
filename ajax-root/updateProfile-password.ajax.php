<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

$Admin = new Admin;
$Employees = new Employees;


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $oldPassword = $_POST['old-password'];
    $newPassword = $_POST['new-password'];
    $cnfPassword = $_POST['cnf-password'];

    $result = [];

    if ($_SESSION['ADMIN']) {
        $oldAdminPass = $adminPass;
        $x_password = pass_dec($oldAdminPass, ADMIN_PASS);

        if ($oldPassword === $x_password) {
            if ($newPassword === $cnfPassword) {
                $adminPassUpdate = $Admin->updateAdminPassword($newPassword, $adminId);
                $result = ['status' => true, 'message' => 'Password changed successfully!'];
            } else {
                $result = ['status' => false, 'message' => 'Inputed password dosenot matched!'];
            }
        } else {
            $result = ['status' => false, 'message' => 'Wrong Old password inputed!'];
        }
    } else {

        $oldEmpPass = $empPass;
        $x_password = pass_dec($oldEmpPass, EMP_PASS);

        if ($oldPassword === $x_password) {

            if ($newPassword === $cnfPassword) {

                $empPassUpdate = $Employees->updateEmployeePassword($newPassword, $employeeId, $adminId);
                // print_r($empPassUpdate);

                if ($empPassUpdate['result']) {
                    $result = ['status' => true, 'message' => 'Password changed successfully!'];
                } else {
                    $result = ['status' => false, 'message' => 'Update fail! Internal server error.'];
                }
            } else {
                $result = ['status' => false, 'message' => 'Inputed password dosenot matched!'];
            }
        } else {
            $result = ['status' => false, 'message' => 'Password Updation Failed!'];
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link href="<?php echo CSS_PATH ?>login.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>/custom/password-show-hide.css" rel="stylesheet">
</head>

<body>


    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>

    <?php
    if (!empty($result)) {
        $status = $result['status'];
        $message = htmlspecialchars($result['message'], ENT_QUOTES, 'UTF-8');
        $profileUrl = htmlspecialchars(LOCAL_DIR . 'profile.php', ENT_QUOTES, 'UTF-8');

        if ($status) {
    ?>
            <script>
                Swal.fire({
                    title: "Success",
                    text: <?php echo json_encode($message); ?>,
                    icon: "success",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "<?php echo $profileUrl; ?>";
                    }
                });
            </script>
        <?php
        } else {
        ?>
            <script>
                Swal.fire({
                    title: "Alert",
                    text: <?php echo json_encode($message); ?>,
                    icon: "error",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "<?php echo $profileUrl; ?>";
                    }
                });
            </script>
    <?php
        }
    }
    ?>





    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.min.js"></script>
    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
    <script src="<?= JS_PATH ?>password-show-hide.js"></script>


</body>

</html>