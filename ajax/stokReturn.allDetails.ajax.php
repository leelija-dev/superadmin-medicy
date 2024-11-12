<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockReturn.class.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'currentStock.class.php';

$StokReturnDetails = new StockReturn();
$StokInDetails = new StockInDetails();
$CurrentStock = new CurrentStock();

// ===================== CURRENT PURCHASE QTY ======================

if (isset($_GET['current-stock-qty'])) {
    $stockInDetailsId = $_GET['current-stock-qty'];
    // echo $stockInDetailsId;
    $stokInDetails = $StokInDetails->showStockInDetailsByStokinId($stockInDetailsId);
    $purchaseQty = $stokInDetails[0]['qty'];
    $freeQty = $stokInDetails[0]['free_qty'];
    // echo $freeQty;
    $stockReturnDetails = $StokReturnDetails->showStockReturnDataByStokinId($stockInDetailsId);
    // print_r($stockReturnDetails);
    if($stockReturnDetails == null){
        $ReturnQty = 0;
        $ReturnFQty = 0;
    }else{
        $ReturnQty = $stockReturnDetails[0]['return_qty'];
        $ReturnFQty = $stockReturnDetails[0]['return_free_qty'];
    }

    $currentData = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($stockInDetailsId));

    echo ($currentData->qty - ($freeQty - $ReturnFQty) );
    // echo $stockInDetailsId;
}


// ======================== CURRENT FREE QTY ======================

if (isset($_GET['current-free-qty'])) {
    $stockInDetailsId = $_GET['current-free-qty'];

    $stokInDetails = $StokInDetails->showStockInDetailsByStokinId($stockInDetailsId);
    $purchaseFQty = $stokInDetails[0]['free_qty'];

    $stockReturnDetails = $StokReturnDetails->showStockReturnDataByStokinId($stockInDetailsId);
    // print_r($stockReturnDetails);
    // $currentData = $CurrentStock->showCurrentStocByStokInDetialsId($stockInDetailsId);

    if($stockReturnDetails == null){
        $ReturnFQty = 0;
    }else{
        $ReturnFQty = $stockReturnDetails[0]['return_free_qty'];
    }

    echo ($purchaseFQty - $ReturnFQty);
}

?>