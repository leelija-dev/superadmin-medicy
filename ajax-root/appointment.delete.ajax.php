<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'appoinments.class.php';
require_once CLASS_DIR . 'patients.class.php';


$appointmentId = $_POST['id'];
// $adminId = $_SESSION['adminId'];
// print_r($appointmentId);
$appointments = new Appointments();
$Patients     = new Patients;

$appointmentsData = json_decode($appointments->appointmentsDisplay($ADMINID));

if ($appointmentsData->status == 1 && !empty($appointmentsData->data)) {
    foreach ($appointmentsData->data as $appointment) {
        if ($appointment->appointment_id == $appointmentId) { // Find the matching appointment
            $patientId = $appointment->patient_id;
            $appointmentDate = $appointment->appointment_date;
            // print_r($patientId);
            break;
        }
    }
}

$patientData = $Patients->patientsDisplayById($patientId);
if (!empty($patientData) && is_array($patientData)) {
    foreach ($patientData as $patient) {
        $visited = $patient['visited'];
        // print_r($visited);
    }
}

$currentDate = date('Y-m-d');
// print_r($currentDate);
// exit;
if($appointmentDate >= $currentDate){

   $apntDel = $appointments->deleteAppointmentsById($appointmentId);
    // echo 1;
   if($apntDel){
    $updateVisitTime = $Patients->decreasePatientsVisitTime($patientId, $visited);
    echo 1; // Success
   }else{
    echo 'Cannot delete appointments ';
   }
}else{
    echo 'Cannot delete past appointments';
}

// $apntDel = $appointments->deleteAppointmentsById($appointmentId);

// if ($apntDel) {
//     echo 1;
// }else {
//     echo 0;
// }


?>