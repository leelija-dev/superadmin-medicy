<?php
require_once dirname(__DIR__) . '/config/constant.php';

// Check if a specific session variable exists to determine if the user is logged in
if (isset($_SESSION['SUPERADMINLOGGEDIN'])) {
    header("Location: ".ADM_URL);
    exit;
}

require_once CLASS_DIR .'dbconnect.php';
require_once CLASS_DIR.'admin.class.php';
require_once CLASS_DIR.'subscription.class.php';
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'idsgeneration.class.php';

$admin          = new Admin;
$Subscription   = new Subscription;
$HealthCare     = new HealthCare;
$IdGenerate     = new IdsGeneration;

$userExists = false;
$emailExists = false;
$diffrentPassword = false;


if (isset($_POST['register'])) {
    $Fname      = $_POST['fname'];
    $Lname      = $_POST['lname'];
    $username   = $_POST['user-name'];
    $email      = $_POST['email'];
    $mobNo      = $_POST['mobile-number'];
    $password   =  $_POST['password'];
    $cpassword  = $_POST['cpassword'];
    

    $currentDate = new DateTime(TODAY);

    // Add 30 days to the current date
    $expiry = $currentDate->modify('+365 days')->format('Y-m-d');

    $adminId  = $IdGenerate->generateAdminId();
    $clinicId = $IdGenerate->generateClinicId($adminId);    


    $checkUser = $admin->echeckUsername($username);
    if($checkUser > 0){
        $userExists = true;
    }else{
        $userExists = false;
        $checkMail = $admin->echeckEmail($email);
        if($checkMail > 0){
            $emailExists = true;
        }else {
            $emailExists = false;
            if($password == $cpassword){
                $diffrentPassword = false;
                $register = $admin->registration($adminId, $Fname, $Lname, $username, $password, $email, $mobNo, $expiry, NOW);
                // print_r($register);
                if ($register) {
                    
                    $subscribed = $Subscription->createSubscription($adminId, 1, NOW, $expiry, 0);
                    if ($subscribed === true) {
                        // echo 'True';
                        $addToClinicInfo = $HealthCare->addClinicInfo($clinicId, $adminId, NOW);
                        if ($addToClinicInfo) {
                            header("Location: login.php");
                            exit;
                        }else{
                            $errMsg = "Clinic Info Can't Added!";
                        }
                    }else {
                        $errMsg =  $subscribed;
                    }
                }
            }else {
                $diffrentPassword = true;
            }
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
    <link href="<?php echo PLUGIN_PATH ?>/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <title>Medicy Health Care - Admin Registration</title>

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>register.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>/custom/password-show-hide.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <main>

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                    </div>

                    <form class="user" action="register.php" method="post">
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="text" class="form-control " id="fname" name="fname"
                                    maxlength="20" placeholder="First Name">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control " id="lname" name="lname"
                                    maxlength="20" placeholder="Last Name">
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control " id="user-name" name="user-name"
                                maxlength="24" placeholder="Username">
                        </div>

                        <div class="form-group">
                            <input type="email" class="form-control " id="email" name="email"
                                maxlength="80" placeholder="Email Address" onfocusout="verifyEmail()">
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control " id="mobile-number"
                                name="mobile-number" placeholder="Mobile Number" onfocusout="validateMobileNumber()" maxlength="9" required>
                        </div>

                        <div class="form-group row">
                            <div class="form-group col-sm-6 mb-3 mb-sm-0">
                                <input type="password" class="form-control " id="password" name="password" maxlength="12" placeholder="Password" required oninput="showToggleBtn('password','toggleBtn1')">
                                <i class="fas fa-eye " id="toggleBtn1" style="display:none;font-size:1.2rem;right:26px;" onclick="togglePassword('password','toggleBtn1')"></i>
                            </div>
                            <div class="form-group col-sm-6 mb-3 mb-sm-0">
                                <input type="password" class="form-control " id="cpassword" name="cpassword" maxlength="12" placeholder="Repeat Password" required oninput="showToggleBtn('cpassword','toggleBtn2')">
                                <i class="fas fa-eye " id="toggleBtn2" style="display:none;font-size:1.2rem;right:26px;" onclick="togglePassword('cpassword','toggleBtn2')"></i>
                            </div>
                        </div>
                        <?php

                                    if($emailExists){
                                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Sorry!</strong> Given Email Already Exists.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>';
                                    }

                                    if($userExists){
                                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Sorry!</strong> Username Already Exists.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>';
                                    }

                                    if($diffrentPassword){
                                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Sorry!</strong> Password Does not match.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>';
                                    }

                                    if (isset($errMsg)) {
                                        echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                        <strong>Please Contact Support. </strong> $errMsg
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                          <span aria-hidden='true'>&times;</span>
                                        </button>
                                      </div>";
                                    }
                                ?>

                        <button class="btn btn-primary btn-user btn-block" type="submit" name="register">Register
                            Account</button>
                        <!-- <hr> -->
                        <!-- <a href="index.html" class="btn btn-google btn-user btn-block">
                                    <i class="fab fa-google fa-fw"></i> Register with Google
                                </a>
                                <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                    <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                                </a> -->
                    </form>
                    <!-- <hr> -->
                    <div class="text-center" style="margin-top:15px;">
                        <a class="small" href="forgot-password.html">Reset Password</a>
                    </div>
                    <div class="text-center">
                        <a class="small" href="login-superAdmin.php">Already have an account? Login!</a>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- custom script for register.php -->
    <script src="<?= JS_PATH ?>adminRegistration.js"></script>
    <script src="<?= JS_PATH ?>password-show-hide.js"></script>


    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <script src="assets/js/sweetalert2/sweetalert2.all.min.js"></script>

</body>

</html>