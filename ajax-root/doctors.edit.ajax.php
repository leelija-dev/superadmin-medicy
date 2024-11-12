<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'doctors.class.php';

$updateDocId    = $_POST['docId'];
$docName        = $_POST['docName'];
$docRegNo       = $_POST['docRegNo'];
$docDegree      = $_POST['docDegree'];
$docSplz        = $_POST['docSpecialization'];
$docAlsoWith    = $_POST['docAlsoWith'];
$docEmail       = $_POST['docEmail'];
$docPhno        = $_POST['docPhno'];
$docAddress     = $_POST['docAddress'];

$doctors = new Doctors();


$UpdateDoctor = $doctors->updateDoc($docRegNo, $docName, $docSplz, $docDegree, $docAlsoWith, $docAddress, $docEmail, $docPhno, $updateDocId);

print_r($UpdateDoctor);
// echo 2;
?>