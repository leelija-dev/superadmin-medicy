<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR."dbconnect.php";
require_once CLASS_DIR.'patients.class.php';

$Patients = new Patients();

if (isset($_GET["name"])) {
   $patient =  $Patients->patientsDisplayByPId($_GET["name"]);
   $patient = json_decode($patient);
   
   echo $patient->name;
}

if (isset($_GET["id"])) {
   $patient =  $Patients->patientsDisplayByPId($_GET["patient_id"]);
   $patient = json_decode($patient);

   echo $patient->patient_id;
}

?>