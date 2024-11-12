<?php
require_once dirname(__DIR__) . '/config/constant.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'employee.class.php';



$Admin = new Admin;
$Employee = new Employees;

// =========== admin contact number existance check =============

if (isset($_POST['checkContact'])) {

    $checkContact = $_POST['checkContact'];
    
    $admCol = 'mobile_no';
    $checkAdminContactExistance = json_decode($Admin->checkAdminDataExistance($admCol, $checkContact));

    
    $empCol = 'emp_contact_no';
    $checkEmpContactExistance = json_decode($Employee->selectEmpByColData($empCol, $checkContact));

    if ($checkAdminContactExistance->status == '1' || $checkEmpContactExistance->status == '1') {
        echo '1';
    } else {
        echo '0';
    }
}


// =========== admin email existance check =============

if (isset($_POST['chekEmailExistance'])) {
    $checkEmail = $_POST['chekEmailExistance'];

    $admCol = 'email';
    $checkAdminMailExistance = json_decode($Admin->checkAdminDataExistance($admCol, $checkEmail));

    
    $empCol1 = 'emp_email';
    $checkEmpMailExistance = json_decode($Employee->selectEmpByColData($empCol1, $checkEmail));
    

    if ($checkAdminMailExistance->status == '1' || $checkEmpMailExistance->status == '1') {
        echo '1';
    } else {
        echo '0';
    }
}


// =========== admin username existance check =============

if (isset($_POST['chekUsrnmExistance'])) {
    $checkUsername = $_POST['chekUsrnmExistance'];

    $admCol = 'username';
    $checkAdminUsrnmExistance = json_decode($Admin->checkAdminDataExistance($admCol, $checkUsername));

    $empCol1 = 'emp_username';
    $checkEmpUsrnmExistance = json_decode($Employee->selectEmpByColData($empCol1, $checkUsername));

    
    if ($checkAdminUsrnmExistance->status == '1' || $checkEmpUsrnmExistance->status == 1) {
        echo '1';
    } else {
        echo '0';
    }
}


