<?php
include_once dirname(__DIR__) . "/config/constant.php";
require_once ROOT_DIR . '_config/registrationSessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';

require_once dirname(__DIR__ ) . '/PHPMailer/PHPMailer.php';
require_once CLASS_DIR . 'utility.class.php';

$PHPMailer		= new PHPMailer();
$Utility        = new Utility;


echo "chek resend";
print_r($$_SESSION);

if (isset($_POST['resendRestOtp'])) {

    echo "chek resend";
    print_r($_SESSION);
/*
	if ($_SESSION['ADM_PASS_RECOVERY'] == true) {
		// print_r($_SESSION);

		$verifyKey  	= strip_tags(trim($Otp));
		$firstName 		= strip_tags(trim($admFname));
		$txtEmail 		= strip_tags(trim($admEmail));
		$userNm 		= strip_tags(trim($admUsrNm));

	} elseif ($_SESSION['EMP_PASS_RECOVERY'] == true) {
		// print_r($_SESSION);

		$verifyKey  	= strip_tags(trim($Otp));
		$firstName 		= strip_tags(trim($empFname));
		$txtEmail 		= strip_tags(trim($empEmail));
		$userNm 		= strip_tags(trim($empUsrNm));

	}

	$sess_arr	= array('passRecoverySession', 'fisrt-name', 'email', 'vkey');
	$Utility->delSessArr($sess_arr);

	header("location: pass-reset.php");

	/*
	$msgBody = $msgBody = "Hello $firstName,

	We're delighted to welcome you back!
	 
	Just a friendly reminder: you can log in using either your username or your email address. Please make sure to remember your password to avoid the hassle of the recovery process.
	<br>
	<br>
	Your Username: $userNm
	<br>
	<br>
	Your Password Reset Otp: $verifyKey
	
	<br>
	<br>
	Remember, it's important not to share your username or OTP with anyone else.
	
	Best regards,
	Medicy";

	/*===================================================================================================
	|									    send mail for password reset								|
	===================================================================================================*//*

	try {
		$PHPMailer->IsSendmail();
		$PHPMailer->IsHTML(true);
		$PHPMailer->Host        = gethostname();
		$PHPMailer->SMTPAuth    = true;
		$PHPMailer->Username    = SITE_EMAIL;
		$PHPMailer->Password    = SITE_EMAIL_P;
		$PHPMailer->From        = SITE_EMAIL;
		$PHPMailer->FromName    = SITE_NAME;
		$PHPMailer->Sender      = SITE_EMAIL;
		$PHPMailer->addAddress($txtEmail, $firstName);
		$PHPMailer->Subject     = "Password recover Verification OTP - " . SITE_NAME;
		$PHPMailer->Body        = $msgBody;

		if (!$PHPMailer->send()) {
			echo "Message could not be sent to customer. Mailer Error:-> {$PHPMailer->ErrorInfo}<br>";
		} else {
			echo 'mail sent';
			header("location: pass-reset.php");
		}

		$PHPMailer->clearAllRecipients();
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error:-> {$PHPMailer->ErrorInfo}";
	} 
	*/

}else {
	session_destroy();
}




