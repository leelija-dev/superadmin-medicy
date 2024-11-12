<?php
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'manufacturer.class.php';
$Manufacturer       = new Manufacturer();

$manufacturerId = $_POST['id'];
// echo $manufacturerId;

$deleteManufacturerReq = $Manufacturer->deleteRequestManufacturer($manufacturerId);

if ($deleteManufacturerReq) {
   echo $deleteManufacturerReq;
}else {
    echo 0;
}


?>