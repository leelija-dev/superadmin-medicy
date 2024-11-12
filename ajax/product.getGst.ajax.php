<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'gst.class.php';


$Products       = new Products();
$StockInDetails = new StockInDetails();
$Gst = new Gst;


if (isset($_GET["id"])) {
    $showProducts = json_decode($Products->showProductsById($_GET["id"]));
    $showProducts = $showProducts->data;
    $gstId = $showProducts[0]->gst;

    // echo $gstId;
    // 
    $col = 'id';
    $gstData = json_decode($Gst->seletGstByColVal($col, $gstId));
    if($gstData->status){
        $gstData = $gstData->data;

        echo $gstData[0]->percentage;
    } else {
        echo null;
    }
    
}



if (isset($_GET["stockgst"])) {
    $showProducts = $StockInDetails->showStockInDetailsByPId($_GET["stockgst"]);
    echo $showProducts[0]['gst'];
}


?>