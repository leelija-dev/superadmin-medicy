<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'stockReturn.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'currentStock.class.php';

$StokReturn = new StockReturn();
$StokInDetails = new StockInDetails();
$CurrentStock = new CurrentStock();

// ===================== CURRENT PURCHASE QTY ======================

if (isset($_GET['current-stock-qty'])) {

    $stockInDetailsId = $_GET['current-stock-qty'];
    $stokInDetails = $StokInDetails->showStockInDetailsByStokinId($stockInDetailsId);
    $purchaseQty = $stokInDetails[0]['qty'];
    $purchaseFreeQty = $stokInDetails[0]['free_qty'];
    
    $currentData = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($stockInDetailsId));
    // print_r($currentData);
    
    $totalRtnQty = 0;
    $stockReturnDetails = json_decode($StokReturn->showStockReturnDataByStokinId($stockInDetailsId));
    // echo $stockInDetailsId;

    if ($stockReturnDetails->status == 1) {
        $stockReturnDetails = $stockReturnDetails->data;

        foreach ($stockReturnDetails as $stockReturnDetails) {
            $stockReturnId =  $stockReturnDetails->stock_return_id;

            // check return availibility
            $col1 = 'id';
            $col2 = 'status';
            $checkStockReturn = json_decode($StokReturn->stockReturnByTables($col1, $stockReturnId, $col2, 1));

            if ($checkStockReturn->status) {

                $returnQty = $stockReturnDetails->return_qty;
            } else {
                $returnQty = 0;
            }

            $totalRtnQty = intval($totalRtnQty) + intval($returnQty);
        }

        echo (intval($currentData->qty) - intval($totalRtnQty));
    } else {
        echo intval($currentData->qty) - intval($purchaseFreeQty);
    }
}


// ======================== CURRENT FREE QTY ======================

if (isset($_GET['current-free-qty'])) {
    $stockInDetailsId = $_GET['current-free-qty'];

    $stokInDetails = $StokInDetails->showStockInDetailsByStokinId($stockInDetailsId);
    $purchaseFQty = $stokInDetails[0]['free_qty'];

    $totalFreeRtnQty = 0;
    $stockReturnDetails = json_decode($StokReturn->showStockReturnDataByStokinId($stockInDetailsId));
    // echo $stockInDetailsId;
    if ($stockReturnDetails->status == 1) {
        $stockReturnDetails = $stockReturnDetails->data;

        foreach ($stockReturnDetails as $stockReturnDetails) {
            $stockReturnId =  $stockReturnDetails->stock_return_id;

            // check return availibility
            $col1 = 'id';
            $col2 = 'status';
            $checkStockReturn = json_decode($StokReturn->stockReturnByTables($col1, $stockReturnId, $col2, 1));

            if ($checkStockReturn->status) {

                $returnFreeQty = $stockReturnDetails->return_free_qty;
            } else {
                $returnFreeQty = 0;
            }

            $totalFreeRtnQty = intval($totalFreeRtnQty) + intval($returnFreeQty);
        }

        $currentData = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($stockInDetailsId));
        // print_r($currentData);
        echo ($purchaseFQty - $totalFreeRtnQty);
    } else {
        echo $purchaseFQty;
    }

}
