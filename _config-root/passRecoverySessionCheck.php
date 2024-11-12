<?php
require_once  dirname(__DIR__) . '/config/constant.php';
// Check if a specific session variable exists to determine if the user is logged in
if (!isset($_SESSION['PASS_RECOVERY'])) {
    header("Location: " . ROOT_DIR . "forgetPassword.php");
    exit;
}



if ($_SESSION['PASS_RECOVERY'] && $_SESSION['ADM_PASS_RECOVERY']) {

    $admPassReset       =   true;
    $empPassReset       =   false;
    $admId              =   $_SESSION['ADM_ID'];
    $admFname           =   $_SESSION['ADM_FNAME'];
    $admUsrNm           =   $_SESSION['ADM_USRNM'];
    $admEmail           =   $_SESSION['ADM_EMAIL'];
    $Otp                =   $_SESSION['ADM_OTP'];

} elseif ($_SESSION['PASS_RECOVERY'] && $_SESSION['EMP_PASS_RECOVERY']) {

    $admPassReset   =   false;
    $empPassReset   =   true;
    $empId          =   $_SESSION['EMP_ID'];
    $admIdOfEmp     =   $_SESSION['EMP_ADM_ID'];
    $empFname       =   $_SESSION['EMP_NAME'];
    $empUsrNm       =   $_SESSION['EMP_USRNM'];
    $empEmail       =   $_SESSION['EMP_EMAIL'];
    $Otp            =   $_SESSION['EMP_OTP'];
}
