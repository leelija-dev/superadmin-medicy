<?php
include_once dirname(__DIR__) . "/config/constant.php";
require_once ROOT_DIR . '_config/registrationSessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'admin.class.php';

require_once CLASS_DIR . 'idsgeneration.class.php';

require_once dirname(__DIR__ ) . '/PHPMailer/PHPMailer.php';
require_once CLASS_DIR . 'utility.class.php';


$PHPMailer		= new PHPMailer();
$Utility        = new Utility;

$IdGenerate     = new IdsGeneration;

$Admin = new Admin;


if (isset($_POST['resendOtp'])) {

   
    $OTP  = $IdGenerate->otpGgenerator();

    $_SESSION['verify_key'] = $OTP;

    $verificationKey = $OTP;


	$fname = $_SESSION['first-name'];
	$email = $_SESSION['email'];
	$userName = $_SESSION['username'];


	$verifyKey  	= strip_tags(trim($_SESSION['verify_key']));
	$firstName 		= strip_tags(trim($_SESSION['first-name']));
	$txtEmail 		= strip_tags(trim($_SESSION['email']));
	$userNm 		= strip_tags(trim($_SESSION['username']));


	$sess_arr	= array('vkey', 'newCustomerSess', 'fisrt-name', 'last-name', 'profession');
	$Utility->delSessArr($sess_arr);


	$msgBody = "Dear $firstName,
			<br>
			Welcome to Medicy! We're thrilled to have you on board.
			<br>
			Just a friendly reminder: you can use either your username or your email address as your login credentials. 
			<br>
			Your Username is - $userNm,
			<br>
			To ensure the security of your account, please use the following One-Time Password (OTP) for verification:
			<br>
			<br>
			<b>Your Verification OTP: $verifyKey</b>
			<br>
			<br>
			Please enter this code to complete the registration process. If you didn't sign up for an account on Medicy, please ignore this email.
			<br>
			Thank you for choosing Medicy. We look forward to providing you with the best Healthcare System experience.";

	/*===================================================================================================
	|									    send mail to new customer									|
	====================================================================================================*/

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
		$PHPMailer->Subject     = "Account Verification OTP - " . SITE_NAME;
		$PHPMailer->Body        = $msgBody;

		if (!$PHPMailer->send()) {
			echo "Message could not be sent to customer. Mailer Error:-> {$PHPMailer->ErrorInfo}<br>";
		} else {
			echo '1';
		}
		$PHPMailer->clearAllRecipients();
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error:-> {$PHPMailer->ErrorInfo}";
	}
} else {
	session_destroy();
}

