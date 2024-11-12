<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'doctors.class.php';


$docId = $_GET['doctor_id'];

$Doctors = new Doctors();
$showDoctor = $Doctors->showDoctorNameById($docId);
$showDoctor = json_decode($showDoctor);

if ($showDoctor->status == 1) {
    echo $showDoctor->data->doctor_name;
} else {
    echo "Not Found!";
}

?>