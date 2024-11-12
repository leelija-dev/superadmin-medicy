<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'distributor.class.php';


$distributorId        = $_GET['id'];
$distributorName      = $_GET['name'];
$distributorPhno      = $_GET['phno'];
$distributorEmail     = $_GET['email'];
$distributorAddress   = $_GET['address'];
$distributorAreaPIN   = $_GET['pin'];
$distributorDsc       = $_GET['dsc'];

$Distributor = new Distributor();


$showDistributor    = $Distributor->showDistributorById($distributorId);
$showDistributor    = json_decode($showDistributor);

if (isset($showDistributor->status) && $showDistributor->status == 1) {
    $data = $showDistributor->data;
    if (!empty($data)) {

        if( $data->name != $distributorName){
            $distNameEdit = 'Name Edited. ';
        }else{
            $distNameEdit = '';
        }
        if($data->address != $distributorAddress){
            $distAddrEdit = 'Address Edited.';
        }else{
            $distAddrEdit = '';
        }
        if($data->area_pin_code != $distributorAreaPIN){
            $distPinEdit = 'AreaPin Edited. ';
        }else{
            $distPinEdit = '';
        }
        if($data->phno != $distributorPhno){
            $distPhoneEdit = 'Phone Number Edited. ';
        }else{
            $distPhoneEdit = "";            
        }
        if($data->email != $distributorEmail){
            $distEmailEdit = 'Email Edited. ';
        }else{
            $distEmailEdit = '';
        }
        if($data->dsc != $distributorDsc){
            $distDscEdit = 'Description Edited. ';
        }else{
            $distDscEdit = '';
        }
        $reqDescription = $distNameEdit . $distAddrEdit . $distPinEdit . $distPhoneEdit . $distEmailEdit . $distDscEdit;
    }
}



$updateDist = $Distributor->insertRequestDist($distributorId ,$distributorName, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $reqDescription,  NOW, $adminId);

//check if the data has been updated or not
if($updateDist){
   echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">
   <strong>Success!</strong> Distributor Has Been Request Succesfully!
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>';
}else {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Failed!</strong> Distributor Request Failed!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

?>