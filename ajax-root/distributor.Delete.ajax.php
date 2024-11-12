<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'distributor.class.php';


$distributorId = $_POST['id'];

$Distributor       = new Distributor();
$deleteDist = $Distributor-> deleteDist($distributorId);

if ($deleteDist) {
    echo 1;
}else {
    echo 0;
}


?>