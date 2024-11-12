
<?php
include_once dirname(dirname(__DIR__)) . "/config/constant.php";
require_once ROOT_DIR . '_config/passRecoverySessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'employee.class.php';

$Admin = new Admin;
$Employee = new Employees;


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>
</body>

</html>


<?php 

if (isset($_POST['pass-reset'])) {

    // print_r($_SESSION);
    
    $passWord = $_POST['password'];
    
    $chkOtp = $_POST['digit1'] . $_POST['digit2'] . $_POST['digit3'] . $_POST['digit4'] . $_POST['digit5'] . $_POST['digit6'];

    // ========= admin table access =============
    if($admPassReset){
        if ($chkOtp == $Otp) {
        
            $admStatusUpdate = $Admin->updateAdminPassword($passWord, $admId);
    
            if ($admStatusUpdate['result']) {
                handelPassResetSuccess();
                session_destroy();
            } else {
                handelPassResetFailure($admStatusUpdate['message']);
                session_destroy();
            }
        } else {
            handleFailure();
            session_destroy();
        }
    }
    

    // ========= employee table access =============
    if($empPassReset){
        if ($chkOtp == $Otp) {
        
            $empStatusUpdate = $Employee->updateEmployeePassword($passWord, $empId, $admIdOfEmp);
    
            if ($empStatusUpdate['result']) {
                handelPassResetSuccess();
            } else {
                handelPassResetFailure($empStatusUpdate['message']);
            }
    
        } else {
            handleFailure();
        }
    }
}



function handelPassResetSuccess() {

    session_destroy();

    echo '
        <script>
        Swal.fire({
            icon: "success",
            title: "Password Reset Successful",
            showConfirmButton: true,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
          }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "' . LOCAL_DIR . 'login.php";
            }
          });
          </script>';

    exit;
}

function handelPassResetFailure($message) {

    session_destroy();

    echo '
        <script>
        Swal.fire({
            icon: "error",
            title: "'.$message.'",
            showConfirmButton: true,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
          }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "' . LOCAL_DIR . 'forgetPassword.php";
            }
          });
          </script>';

    exit;
}


function handleFailure(){

    session_destroy();

    echo '
        <script>
        Swal.fire({
            icon: "error",
            title: "INVALID OTP",
            showConfirmButton: true,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
          }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "' . LOCAL_DIR . 'forgetPassword.php";
            }
          });
          </script>';

    exit;
}
?>


