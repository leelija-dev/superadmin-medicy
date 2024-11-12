<?php
require_once dirname(__DIR__) . '/config/constant.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'currentStock.class.php';

$Stockin = new StockIn();
$StockInDetails = new StockInDetails();
$CurrentStock = new CurrentStock();

$stockInId  = $_POST['DeleteId'];

$selectStockInData = $Stockin->selectStockInById($stockInId);
// print_r($selectStockInData);
$purchaseQty = $selectStockInData[0]['total_qty'];

$totalCurrentQtyChk = 0;
$selectStockInDetails = $StockInDetails->showStockInDetailsByStokId($stockInId);
foreach ($selectStockInDetails as $stockInDetails) {
    $stockInDetailsId = $stockInDetails['id'];
    $table = 'stock_in_details_id';
    $selectCurrentStockData = $CurrentStock->showCurrentStocByStokInDetialsId($stockInDetailsId);
    // print_r($selectCurrentStockData);

    $currentQty = $selectCurrentStockData->qty;
    $totalCurrentQtyChk = intval($totalCurrentQtyChk) + intval($currentQty);
}


if (intval($purchaseQty) == intval($totalCurrentQtyChk)) {

    $selectStockInDetails = $StockInDetails->showStockInDetailsByStokId($stockInId);
    foreach ($selectStockInDetails as $stockInDetails) {
        $stockInDetailsId = $stockInDetails['id'];
        // echo "<br>$stockInDetailsId";

        // delete form current stock
        $table = 'stock_in_details_id';
        $deleteFromCurrentStock = $CurrentStock->deleteCurrentStockbyStockIndetailsId($stockInDetailsId);

        // delete from stock in details
        $deleteStockInDetails = $StockInDetails->stockInDeletebyDetailsId($stockInDetailsId);
    }
}

if ($deleteFromCurrentStock == true && $deleteStockInDetails == true) {
    $deleteFromStockIn = $Stockin->deleteStock($stockInId);
}



if ($deleteFromStockIn) {
    echo true;
} else {
    echo false;
}
