<?php
include_once dirname(__DIR__) . "/config/constant.php";
require_once ROOT_DIR . '_config/registrationSessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'admin.class.php';

$Admin = new Admin;


if (isset($_POST['otpsubmit'])) {

    $key = $_SESSION['verify_key'];
    $admId = $adminId;
    $status = 0;

    $chkOtp = $_POST['otpsubmit'];

    if ($chkOtp == $key) {
        $status = 1;

        $admStatusUpdate = $Admin->updateAdminStatus($admId, $status);
        

        if ($admStatusUpdate['result']) {
            session_destroy();

            echo 1;

        } else {

            session_destroy();

            echo $admStatusUpdate['message'];
        }

    } else {
        echo 2;
    }
}

?>


