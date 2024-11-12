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

    $prodData = json_decode($Products->showProductsById($_GET["id"]));
    if ($prodData->status) {
        $prodDataDetails = $prodData->data;
        // print_r($prodDataDetails);
        if (isset($prodDataDetails->edit_request_flag)) {
            $editReqFlag = '1';
        } else {
            $editReqFlag = '';
        }

        $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["id"], $adminId, $editReqFlag));
        $showProducts = $showProducts->data;
        // print _r($showProducts);

        foreach($showProducts as $prodData){
            $gstPercent = $prodData->gst;
            echo $gstPercent;
        }
        
    }
}



if (isset($_GET["stockgst"])) {
    $showProducts = $StockInDetails->showStockInDetailsByPId($_GET["stockgst"]);
    echo $showProducts[0]['gst'];
}


?>