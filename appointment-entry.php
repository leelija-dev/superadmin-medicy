<?php
require_once __DIR__.'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php';

require_once CLASS_DIR.'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR.'appoinments.class.php';
require_once CLASS_DIR.'idsgeneration.class.php';

$page = "appointments";

//Classes Initilizing
$appointments   = new Appointments;
$IdsGeneration  = new IdsGeneration;


// print_r($_SESSION['appointment-data']);
if (isset($_SESSION['appointment-data'])) {

    $apntData = $_SESSION['appointment-data'];

    $patientId          = $apntData['patientId'];
    $appointmentDate    = $apntData['appointmentDate']; 
    $patientName        = $apntData['patientName']; 
    $patientGurdianName = $apntData['patientGurdianName']; 
    $patientEmail       = $apntData['patientEmail'];
    $patientPhoneNumber = $apntData['patientPhoneNumber']; 
    $patientAge         = $apntData['patientAge']; 
    $patientWeight      = $apntData['patientWeight']; 
    $gender             = $apntData['gender']; 
    $patientAddress1    = $apntData['patientAddress1']; 
    $patientAddress2    = $apntData['patientAddress2']; 
    $patientPS          = $apntData['patientPS']; 
    $patientDist        = $apntData['patientDist']; 
    $patientPIN         = $apntData['patientPIN']; 
    $patientState       = $apntData['patientState'];
    $patientDoctor      = $apntData['patientDoctor']; 
   
    //appointment id generating
    $healthCareNameTrimed = strtoupper(substr($healthCareName, 0, 2));//first 2 leter oh healthcare center name
    $appointmentDateForId = date("dmy", strtotime($appointmentDate));
    $apntIdStart = "$healthCareNameTrimed$appointmentDateForId";
       
    // Appointment iD Generated
    $appointmentId = $IdsGeneration->appointmentidGeneration($apntIdStart);
   
      
    // Inserting Into Appointments Database
    $addAppointment = $appointments->addFromInternal($appointmentId, $patientId, $appointmentDate, $patientName, $patientGurdianName,$patientEmail, $patientPhoneNumber, $patientAge, intval($patientWeight), $gender, $patientAddress1, $patientAddress2, $patientPS,$patientDist, $patientPIN, $patientState, $patientDoctor, $employeeId, NOW, $adminId);

    //redirect if the insertion has done
    if ($addAppointment) {
        
        unset($_SESSION['appointment-data']);
        echo '<script>alert(Appointment Added!)</script>';
        header("location: appointment-sucess.php?appointmentId=".$appointmentId);
            
    }else{
         echo "New Record Insertion Failed ==>: Query Not Executed.";
       }
   }
?>