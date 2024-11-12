<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'stockIn.class.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'productsImages.class.php';
require_once CLASS_DIR.'distributor.class.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR.'packagingUnit.class.php';

$CurrentStock   = new CurrentStock();
$Patients       =   new Patients();
$StockIn        =   new StockIn();
$StockInDetail  =   new StockInDetails();
$Product        =   new Products();
$ProductImages  =   new ProductImages();
$distributor    =   new Distributor();
$manufacturer   =   new Manufacturer();
$packagUnit     =   new PackagingUnits();


if (isset($_POST['delID'])) {
    $productID =  $_POST['delID'];

    $deleteProductFromCrntStock = $CurrentStock->deleteCurrentStockbyId($productID);
   

    // ===== fetching  stock in details data for adjusting stock in data =======
    $itemDetailsFromStockInDetails = $StockInDetail->showStockInDetailsByPId($productID);

    foreach($itemDetailsFromStockInDetails as $perItemStockInDetaisl){
        $itemDetaislId = $perItemStockInDetaisl['id'];
        $itemQty = $perItemStockInDetaisl['qty'];
        $itemGstAmount = $perItemStockInDetaisl['gst_amount'];
        $itemAmount = $perItemStockInDetaisl['amount'];
        $StockInId = $perItemStockInDetaisl['stokIn_id'];
        
        // echo "<br>$itemDetaislId<br>$itemQty<br>$itemGstAmount<br>$itemAmount<br>$StockInId<br><br>";
        // ======== delete item from stock in detaisl =========
        $deleteFromStocInDetails = $StockInDetail->stockInDeletebyDetailsId($itemDetaislId);
    
        // ====== fetching stock in data for adjustment ========
        $selectStockInData = $StockIn->selectStockInById($StockInId);
        foreach($selectStockInData as $stockIn){
            $itemCount = $stockIn['items'];
            $totalQty = $stockIn['total_qty'];
            $gstAmount = $stockIn['gst'];
            $amount = $stockIn['amount'];

            // echo "<br>$itemCount<br>$totalQty<br>$gstAmount<br>$amount<br>$StockInId<br><br>";
            // =========== adjust stock in data ============
            $updatedItemsCount = intval($itemCount) - 1;
            $updatedTotalQty = intval($totalQty) - intval($itemQty);
            $updatedGstAmount = intval($gstAmount) - intval($itemGstAmount);
            $updatedAmount = intval($amount) - intval($itemAmount);

            // echo "<br>$updatedItemsCount<br>$updatedTotalQty<br>$updatedGstAmount<br>$updatedAmount<br>$StockInId<br><br>";

            if($updatedTotalQty == 0 && $updatedItemsCount == 0){
                $updateStockInData = $StockIn->deleteStock($StockInId);
            } else {
                $updateStockInData = $StockIn->updateStockInOnModifyCurrentStock($StockInId, $updatedItemsCount, $updatedTotalQty, $updatedGstAmount, $updatedAmount, $employeeId, NOW);
            }
        }
    }
    
    if($deleteProductFromCrntStock == true && $deleteFromStocInDetails == true && $updateStockInData == true){
        echo true;
    }else{
        echo false;
    }
}




if (isset($_POST['delItemId'])) {

    $stockInDetailsId =  $_POST['delItemId'];

    // =============== delete form current stock ===============
    $deleteProductStockByBatch = $CurrentStock->deleteCurrentStockbyStockIndetailsId($stockInDetailsId);

    // ============== select stock in detaisl data =============
    $sockInDetaislData = $StockInDetail->showStockInDetailsByStokinId($stockInDetailsId);
    // print_r($sockInDetaislData);
    
    foreach($sockInDetaislData as $sockInDetaislData){
        $StockInId = $sockInDetaislData['stokIn_id'];
        $DetailsItemQty = $sockInDetaislData['qty'];
        $DetailsItemGstAmount = $sockInDetaislData['gst_amount'];
        // echo $DetailsItemGstAmount;
        $DetailsItemAmount = $sockInDetaislData['amount'];
        
        // echo "<br>$itemDetaislId<br>$itemQty<br>$itemGstAmount<br>$itemAmount<br>$StockInId<br><br>";

        // ======== delete item from stock in detaisl =========
        $deleteFromStockInDetails = $StockInDetail->stockInDeletebyDetailsId($stockInDetailsId);
    
        // ====== fetching stock in data for adjustment ========
        $selectStockInData = $StockIn->selectStockInById($StockInId);
        // print_r($selectStockInData);
        foreach($selectStockInData as $stockIn){
            $StockInItemCount = $stockIn['items'];
            $StockInTotalQty = $stockIn['total_qty'];
            $StockInGstAmount = $stockIn['gst'];
            
            $StockInAmount = $stockIn['amount'];

            // echo "<br>$itemCount<br>$totalQty<br>$gstAmount<br>$amount<br>$StockInId<br><br>";
            // =========== adjust stock in data ============
            $updatedStockInItemsCount = intval($StockInItemCount) - 1;
            $updatedStockInTotalQty = intval($StockInTotalQty) - intval($DetailsItemQty);
            $updatedStockInGstAmount = intval($StockInGstAmount) - intval($DetailsItemGstAmount);
            // echo $updatedStockInGstAmount;
            $updatedStockInAmount = intval($StockInAmount) - intval($DetailsItemAmount);

            // echo "<br>$updatedItemsCount<br>$updatedTotalQty<br>$updatedGstAmount<br>$updatedAmount<br>$StockInId<br><br>";

            if($updatedStockInItemsCount == 0 && $updatedStockInTotalQty == 0){
                $updateStockInData = $StockIn->deleteStock($StockInId);
                
            } else {
                $updateStockInData = $StockIn->updateStockInOnModifyCurrentStock($StockInId, $updatedStockInItemsCount, $updatedStockInTotalQty, $updatedStockInGstAmount, $updatedStockInAmount, $employeeId, NOW);
            }
        }
    }

    if($deleteProductStockByBatch == true && $deleteFromStockInDetails == true && $updateStockInData == true){
        echo true;
    }else{
        echo false;
    }
}

?>