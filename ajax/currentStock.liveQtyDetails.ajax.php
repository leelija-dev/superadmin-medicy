<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'currentStock.class.php';

$CurrentStock = new CurrentStock();

if (isset($_GET['currentQTY'])) {
    $stockInDetailsId = $_GET['currentQTY'];

    $currentQty = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($stockInDetailsId));
    if($currentQty == null){
        $qty = 0;
    }else{
        $qty = $currentQty->qty;
    }
    echo $qty;
}

?>