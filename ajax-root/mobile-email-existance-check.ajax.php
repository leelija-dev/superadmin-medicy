<?php

require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'appoinments.class.php';
require_once CLASS_DIR.'doctors.class.php';



$Patients   = new Patients;
$Appointments = new Appointments;
$Doctors = new Doctors;


if (isset($_POST['checkMobNo'])){
    $contactNumber = $_POST['checkMobNo'];

   

    $patientCheckCol = 'phno';
    $patientCheck = json_decode($Patients->chekPatientsDataOnColumn($patientCheckCol, $contactNumber, $adminId));

    $appointmentsCheckCol = 'patient_phno';
    $appointmentsCheck = json_decode($Appointments->appointmentsFilter($appointmentsCheckCol, $contactNumber, $adminId));

    $docCheckCol = 'doctor_phno';
    $doctorCheck = json_decode($Doctors->chekDataOnColumn($docCheckCol, $contactNumber, $adminId));
    // print_r($doctorCheck);


    if($patientCheck->status == 1 || $appointmentsCheck->status == 1 || $doctorCheck->status == 1){
        echo 1;
    }else{
        echo 0;
    }
}





if (isset($_POST['email'])){
    $email = $_POST['email'];

   

    $patientCheckCol = 'email';
    $patientCheck = json_decode($Patients->chekPatientsDataOnColumn($patientCheckCol, $email, $adminId));

    $appointmentsCheckCol = 'patient_email';
    $appointmentsCheck = json_decode($Appointments->appointmentsFilter($appointmentsCheckCol, $email, $adminId));

    $docCheckCol = 'doctor_email';
    $doctorCheck = json_decode($Doctors->chekDataOnColumn($docCheckCol, $email, $adminId));
    // print_r($doctorCheck);


    if($patientCheck->status == 1 || $appointmentsCheck->status == 1 || $doctorCheck->status == 1){
        echo 1;
    }else{
        echo 0;
    }
}


?>