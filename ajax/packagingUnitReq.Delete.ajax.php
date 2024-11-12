<?php
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'packagingUnit.class.php';


$unitId = $_POST['id'];

print_r($unitId);

$PackagingUnits       = new PackagingUnits();
$deleteUnit = $PackagingUnits->deletePackRequest($unitId);

if ($deleteUnit) {
    echo 1;
}else {
    echo 0;
}


?>