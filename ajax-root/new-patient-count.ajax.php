<?php 

require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'patients.class.php';

$Patients   = new Patients;


/// find new patient by selected date ///
if (isset($_GET['newPatientDt'])) {
    $newPatientDt = $_GET['newPatientDt'];
    $newPatientsByDay = $Patients->newPatientByDay($adminId, $newPatientDt);
    echo json_encode($newPatientsByDay);
    // echo  $newPatientDt;
}



/// find new patient by  date range ///
if (isset($_GET['newPatientStartDate']) && isset($_GET['newPatientEndDate'])) {
    $newStartDate = $_GET['newPatientStartDate'];
    $newEndDate = $_GET['newPatientEndDate'];
    $newPatientsInRangeDate = $Patients->findPatientsInRangeDate($adminId, $newStartDate, $newEndDate);
    // echo $newStartDate;
    echo json_encode($newPatientsInRangeDate);
}

?>
