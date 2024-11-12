<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'distributor.class.php';


$distributorId        = $_GET['id'];
$distributorName      = $_GET['name'];
$distributorGSTIN     = $_GET['gstin'];
$distributorPhno      = $_GET['phno'];
$distributorEmail     = $_GET['email'];
$distributorAddress   = $_GET['address'];
$distributorAreaPIN   = $_GET['pin'];
$distributorDsc       = $_GET['dsc'];

$Distributor = new Distributor();

$updateDist = $Distributor->updateDist($distributorName, $distributorGSTIN, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $employeeId, NOW, $distributorId);

//check if the data has been updated or not
if($updateDist){
   echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">
   <strong>Success!</strong> Distributor Has been Updated!
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>';
}else {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Failed!</strong> Distributor Updation Failed!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}


?>