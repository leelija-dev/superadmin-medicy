<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'packagingUnit.class.php';


$unitId = $_POST['id'];

print_r($unitId);

$PackagingUnits       = new PackagingUnits();
$deleteUnit = $PackagingUnits->deleteUnit($unitId);

if ($deleteUnit) {
    echo 1;
}else {
    echo 0;
}


?>