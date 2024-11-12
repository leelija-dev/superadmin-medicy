<?php
require_once dirname(__DIR__) . '/config/constant.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'employee.class.php';



$Admin = new Admin;
$Employee = new Employees;



// =========== employee username existance check =============

if (isset($_POST['empUsrNm'])) {
    $checkUsername = $_POST['empUsrNm'];

    $checkAdminUsrnmExistance = $Admin->echeckUsername($checkUsername);

    $empCol1 = 'emp_username';
    $checkEmpUsrnmExistance = json_decode($Employee->selectEmpByColData($empCol1, $checkUsername));

    if ($checkAdminUsrnmExistance == 1 || $checkEmpUsrnmExistance->status == 1) {
        echo '1';
    } else {
        echo '0';
    }
}


// =========== employee email existance check =============

if (isset($_POST['empEmail'])) {
    $checkEmail = $_POST['empEmail'];

    $checkAdminMailExistance = $Admin->echeckEmail($checkEmail);

    
    $empCol1 = 'emp_email';
    $checkEmpMailExistance = json_decode($Employee->selectEmpByColData($empCol1, $checkEmail));
    // print_r($checkEmpMailExistance);

    if ($checkAdminMailExistance == 1 || $checkEmpMailExistance->status == '1') {
        echo '1';
    } else {
        echo '0';
    }
}




