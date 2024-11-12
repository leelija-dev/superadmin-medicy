<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'doctors.class.php';


$docRegNo           = $_POST['docRegNo'];
$docName            = $_POST['docName'];
$docName            = 'Dr. ' . $docName;
$docSpecialization  = $_POST['docSpecialization'];
$docDegree          = $_POST['docDegree'];
$alsoWith           = $_POST['docAlsoWith'];
$docAddress         = $_POST['docAddress'];
$docEmail           = $_POST['docEmail'];
$docPhno            = $_POST['docMob'];


$doctors = new Doctors();


$addDoctorData = $doctors->addDoctor($docRegNo, $docName, $docSpecialization, $docDegree, $alsoWith, $docAddress, $docEmail, $docPhno, $adminId);

// print_r($addDoctorData);

echo $addDoctorData;

?>