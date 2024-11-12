<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'manufacturer.class.php';
$Manufacturer       = new Manufacturer();

$manufacturerId = $_POST['id'];
// echo $manufacturerId;

$deleteManufacturer = $Manufacturer->deleteManufacturer($manufacturerId);

if ($deleteManufacturer) {
   echo $deleteManufacturer;
}else {
    echo $deleteManufacturer;
}


?>