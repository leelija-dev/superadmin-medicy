<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'doctors.class.php';



$doctorId = $_GET['doctor_shift'];



$doctors = new Doctors();

$showTiming = $doctors->doctorsTimingByDoctor($doctorId);



// if($doctor!=""){

    foreach($showTiming as $showTimingDetails){

        $days = $showTimingDetails['days'];

        $shift = $showTimingDetails['shift'];

        // echo $days , $shift;

        echo'<option value='.$days.'-'.$shift.'>'. $days.'   ('. $shift.')</option>';

    }

// }

                                 

?>