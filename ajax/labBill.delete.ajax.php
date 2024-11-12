<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'labBilling.class.php';


$billId = $_POST['billId'];
$status = $_POST['status'];
// echo $billId.'-'.$status;

// $billId = 1;
// $status = "Cancalled";

$LabBilling = new LabBilling();

$billUpdate = $LabBilling->cancelLabBill($billId, $status);
// echo var_dump($billUpdate);

if ($billUpdate == 1) {
    echo 1;
}else {
    echo 0;
}


?>