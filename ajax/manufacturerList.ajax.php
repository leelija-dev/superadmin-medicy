<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'manufacturer.class.php';

$distributorId = $_GET['manufacturer_id'];
// $distributorId = 2;

$Manufacturer         = new Manufacturer();
$showManufacturerByDistributorId = $Manufacturer->showManufacturerById($distributorId);

// if($doctor!=""){
    foreach($showManufacturerByDistributorId as $rowManufacturer){
        $manufacturerId   = $rowManufacturer['id'];
        $manufacturerName = $rowManufacturer['name'];
        // echo $days , $shift;
        echo'<option value="'.$manufacturerId.'">'. $manufacturerName.'</option>';
    }
// }
                                 
?>