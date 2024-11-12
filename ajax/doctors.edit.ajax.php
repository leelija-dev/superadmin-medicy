<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'doctors.class.php';


$updateDocId    = $_GET['docId'];
$docName        = $_GET['docName'];
$docRegNo       = $_GET['docRegNo'];
$docDegree      = $_GET['docDegree'];
$docSplz        = $_GET['docSpecialization'];
$docAlsoWith    = $_GET['docAlsoWith'];
$docEmail       = $_GET['docEmail'];
$docPhno        = $_GET['docPhno'];
$docAddress     = $_GET['docAddress'];



$doctors = new Doctors();
$UpdateDoctor = $doctors->updateDoc($docRegNo, $docName, $docSplz, $docDegree, $docAlsoWith, $docAddress, $docEmail, $docPhno,/*Last VAriable for id which data we wants to update*/$updateDocId);

//check if the data has been updated or not
if($UpdateDoctor){
    echo "<div class='alert alert-primary alert-dismissible fade show' role='alert'>
            <strong>Success</strong> Doctor Data Has been Updated!
        </div>";
}else {
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <strong>Failed</strong> Doctor Data Updation Failed!
        </div>";
}


?>