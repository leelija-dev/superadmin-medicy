<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once dirname(dirname(__DIR__)) . '/config/service.const.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'stockReturn.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';


//  INSTANTIATING CLASS
$HelthCare        = new HealthCare();
$StockReturn      = new StockReturn();
$IdsGeneration    = new IdsGeneration();
$CurrentStock     = new CurrentStock();
$StokInDetails    = new StockInDetails();

if (isset($_POST['stock-return-edit'])) {


    $stockReturnId      = $_POST['stock-returned-id'];
    $distributorId      = $_POST['dist-id'];
    $distributorName    = $_POST['dist-name'];
    $returnDate         = date("Y-m-d", strtotime($_POST['return-date']));
    $refundMode         = $_POST['refund-mode'];
    $itemsCount         = $_POST['items-qty'];
    $totalReturnQty     = $_POST['total-return-qty']; // FREE QUANTITY + RETURN QANTITY
    $returnGst          = $_POST['return-gst']; // TOTAL RETURN GST
    $refund             = $_POST['NetRefund'];

    $updatedBy = ($_SESSION['ADMIN']) ? $adminId : $employeeId;

    // $allowedUnits = LOOSEUNITS;
    // ========================== end of array data ==============================

    // ================ STOCK RETURN DATA UPDATE  BLOCK ==================
    $stockReturnEditUpdate = $StockReturn->stockReturnEditUpdate(intval($stockReturnId), intval($itemsCount), intval($totalReturnQty), floatval($returnGst), $refundMode, floatval($refund), $updatedBy, NOW);

    // ======================== ITEMS RETURN DIFFERENCE CHECK ==========================
    $PrvRtrnItemIdArray = [];
    $stockReturnDataFetch = $StockReturn->showStockReturnDetails($stockReturnId);

    foreach ($stockReturnDataFetch as $returnData) {
        $ItemId = $returnData['id'];
        array_push($PrvRtrnItemIdArray, $ItemId);       // PREVIOUS ITEMS RETURN DETAISL IDS
    }


    $editReturnItemsIds = null; // PREVIOUS ITEMS RETURN DETAISL IDS

    if (empty($_POST['stock-return-details-item-id'])) {
        $updatedDetailIdDiff = $PrvRtrnItemIdArray;
    } else {
        $editReturnItemsIds  = $_POST['stock-return-details-item-id'];
        $updatedDetailIdDiff = array_diff($PrvRtrnItemIdArray, $editReturnItemsIds);
    }


    //===================== DELETE DATA ACTION AREA ======================;
    foreach ($updatedDetailIdDiff as $deleteItemId) {

        $prevStokReturnDetailsData = $StockReturn->showStockReturnDetailsById($deleteItemId);


        foreach ($prevStokReturnDetailsData as $returnData) {
            $StokInDetailsId  = $returnData['stokIn_details_id'];
            $returnQty        = $returnData['return_qty'];
            $returnFQty       = $returnData['return_free_qty'];
            $totalReturnQTY   = intval($returnQty) + intval($returnFQty);
            $unit             = $returnData['unit'];
            $itemUnit         = preg_replace('/[0-9]/', '', $unit);
            $itemWeightage    = preg_replace('/[a-z-A-Z]/', '', $unit);


            if (in_array(strtolower(trim($itemUnit)), LOOSEUNITS)) {
                $returnQty = intval($totalReturnQTY) * intval($itemWeightage);
            } else {
                $returnQty = $totalReturnQTY;
            }

            $currenStockData = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($StokInDetailsId));

            $CurrentItemQTY = $currenStockData->qty;
            $CurrentLooselyCount = $currenStockData->loosely_count;

            if (in_array(strtolower(trim($itemUnit)), LOOSEUNITS)) {
                $updatedLooseCount  = intval($CurrentLooselyCount) + intval($returnQty);
                $updatedQty         = intdiv($updatedLooseCount, $itemWeightage);
            } else {
                $updatedLooseCount  = 0;
                $updatedQty         = intval($CurrentItemQTY) + intval($returnQty);
            }

            $updateCurrentStock = $CurrentStock->updateStockByStockInDetailsId(intval($StokInDetailsId), intval($updatedQty), intval($updatedLooseCount));

            $attribute = 'id';
            $deleteStockReturnDetails = $StockReturn->deleteStockByTableData($attribute, $deleteItemId);
        }
    }

    // ============================== update data block start ============================

    if ($editReturnItemsIds != null) {
        $stockInDetailsItemId = $_POST['stock-in-details-item-id'];
        for ($i = 0; $i < count($editReturnItemsIds) && $i < count($stockInDetailsItemId); $i++) {

            $stockReturnDetailsItemId = $editReturnItemsIds[$i];
            $productId              = $_POST['productId'];
            $productName            = $_POST['productName'];
            $batchNo                = $_POST['batchNo'];
            $expDate                = $_POST['expDate'];
            $setof                  = $_POST['setof'];
            $updatedItemUnit        = preg_replace('/[0-9]/', '', $setof[$i]);
            $updatedItemWeightage   = preg_replace('/[a-z-A-Z]/', '', $setof[$i]);
            $mrp                    = $_POST['mrp'];
            $ptr                    = $_POST['ptr'];
            $gstParcent             = $_POST['gst'];
            $discountParcent        = $_POST['disc'];
            $editReturnQTY          = $_POST['return-qty'];
            $editReturnFQty         = $_POST['return-free-qty'];
            $PerItemsRefundAmount   = $_POST['refund-amount'];  // Per items REFUND AMOUNT


            $totalUpdatedReturnQty = intval($editReturnQTY[$i]) + intval($editReturnFQty[$i]);


            if ($stockReturnEditUpdate) {

                $prevStokReturnDetailsData = $StockReturn->showStockReturnDetailsById($stockReturnDetailsItemId);

                foreach ($prevStokReturnDetailsData as $prevReturnData) {
                    $returnQty          = $prevReturnData['return_qty'];
                    $returnFreeQty      = $prevReturnData['return_free_qty'];
                    $totalPrevReturn    = intval($returnQty) + intval($returnFreeQty);
                }

                $itemRetundQtyDiff = $totalPrevReturn - $totalUpdatedReturnQty;

                //===================== update calculation area ===============================
                $CurrentStockData = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($stockInDetailsItemId[$i]));
                $currentQty         = $CurrentStockData->qty;
                $currentLooseQty    = $CurrentStockData->loosely_count;

            
                if (in_array(strtolower(trim($updatedItemUnit)), LOOSEUNITS)){
                    $updatedLooseQty = intval($currentLooseQty) + (intval($itemRetundQtyDiff) * intval($updatedItemWeightage));

                    $updatedQty = intdiv($updatedLooseQty, $updatedItemWeightage);
                } else {
                    $updatedLooseQty = 0;
                    $updatedQty      = intval($currentQty) + (intval($itemRetundQtyDiff));
                }

                //========= current stock update function call ============
                $CurrentStockUpdate = $CurrentStock->updateStockByReturnEdit(intval($stockInDetailsItemId[$i]), intval($updatedQty), intval($updatedLooseQty), $updatedBy, NOW);  //updating current stock after edit purchase return


                //========= stock return details update function call ============
                $stockReturnDetailsEdit = $StockReturn->stockReturnDetailsEditUpdate(intval($stockReturnDetailsItemId), intval($editReturnQTY[$i]), intval($editReturnFQty[$i]), floatval($PerItemsRefundAmount[$i]), $updatedBy, NOW);  //updating stock return details table

                #################### data fetching and updating end ########################

            }
        }
    }
}

$response = url_enc(json_encode(['stock_return_id' => $stockReturnId]));
// header("Location: " . URL . "stock-return-invoice.php?data=" . $response);
$redirectUrl = URL."invoices/purchase-return-invoice.php?data=" . $response;
header("Location: $redirectUrl");
exit;
