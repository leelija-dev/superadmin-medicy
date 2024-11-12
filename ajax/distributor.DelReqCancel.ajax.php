<?php
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'distributor.class.php';

if(isset($_POST['distId']))
$distributorId = $_POST['distId'];
print_r($distributorId);

$Distributor       = new Distributor();
$distDelReqCancel  = $Distributor-> cancelDeleteReq($distributorId);

if ($distDelReqCancel) {
    echo 1;
}else {
    echo 0;
}


?>