<?php
// require_once dirname(__DIR__).'/config/constant.php';
require_once realpath(dirname(dirname(__DIR__)). '/config/constant.php');
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'doctor.category.class.php';

$updateDocCatId = $_GET['docCatId'];
$docCatName = $_GET['docCatName'];
$docCatDesc = $_GET['docCatDdsc'];

$DoctorCategory = new DoctorCategory();

$updateDocCateory = $DoctorCategory->updateDocCateory($docCatName, $docCatDesc, /*Last Variable for id which one you want to update */$updateDocCatId);
if ($updateDocCateory) {
    echo'<div class="alert alert-success fade show" role="alert">
    <strong>Success!</strong> Changes Has Saved Successfully..
</div>';
}else {
    echo'<div class="alert alert-warning fade show" role="alert">
    <strong>Failed!</strong> Updation Failed!
</div>';
}

?>