<?php
require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
require_once SUP_ADM_DIR .'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR."dbconnect.php";
require_once CLASS_DIR.'patients.class.php';

$Patients = new Patients();

if (isset($_GET["name"])) {
   $customer =  $Patients->patientsDisplayByPId($_GET["name"]);
   $customer = json_decode($customer);
   
   echo $customer->name;
}

if (isset($_GET["contact"])) {
   $customer =  $Patients->patientsDisplayByPId($_GET["contact"]);
   $customer = json_decode($customer);

   echo $customer->phno;
}

?>