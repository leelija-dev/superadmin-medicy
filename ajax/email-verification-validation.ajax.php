<?php
require_once dirname(__DIR__) . '/config/constant.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'mailVerification.class.php';


$Admin = new Admin;
$MailVerification = new MailVerification;

$verifyToken = md5(rand());
$status = '0';


if (isset($_POST['chekExistance'])) {
    $checkEmail = $_POST['chekExistance'];

    $checkMailExistance = $Admin->echeckEmail($checkEmail);

    if ($checkMailExistance == 1) {
        echo '0';
    } else {
        $addToVerification = $MailVerification->addVerifyToken($checkEmail, $verifyToken, $status);

        if($addToVerification['status']){
            $verification = $MailVerification->sendMail($checkEmail,$verifyToken);
            print_r($verification);
        }
        // echo '1';
    }
}


