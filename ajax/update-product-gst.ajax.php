<?php 
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'gst.class.php';

$Product = new Products;
$Gst = new Gst;


if($_SERVER["REQUEST_METHOD"] === "POST"){

    $gstPercent = $_POST['gstPercetn'];
    $prodId = $_POST['prodId'];

    $colParcent = 'percentage';
    $gstData= json_decode($Gst->seletGstByColVal($colParcent, $gstPercent));
    $gstData = $gstData->data;
    $gstid = $gstData[0]->id;

    $col = 'gst';
    $updateProductGst = $Product->updateProductValuebyCol($prodId, $col, $gstid, $employeeId, NOW, $adminId);

    $updateProductGst = json_decode($updateProductGst);

    print_r($updateProductGst);
    // if($updateProductGst->status){
    //     echo '0';
    // } else {
    //     echo '1';
    // }
}



?>