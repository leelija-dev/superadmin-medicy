<?php
require_once dirname(dirname(__DIR__)). '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR .'dbconnect.php';
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'stockReturn.class.php';
require_once CLASS_DIR.'idsgeneration.class.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'stockInDetails.class.php';


//  INSTANTIATING CLASS
$HelthCare       = new HealthCare();
$StockReturn     = new StockReturn();
$IdsGeneration    = new IdsGeneration();
$CurrentStock    = new CurrentStock();
$StokInDetails   = new StockInDetails();

if (isset($_POST['stock-return-edit'])) {

    
    $stockReturnId = $_POST['stock-returned-id'];
    $distributorId = $_POST['dist-id'];
    $distributorName = $_POST['dist-name'];

    $returnDate = $_POST['return-date'];
    $returnDate = date("Y-m-d", strtotime($returnDate)); //

    $refundMode = $_POST['refund-mode']; //

    $itemsCount = $_POST['items-qty'];
    $totalReturnQty = $_POST['total-return-qty']; // FREE QUANTITY + RETURN QANTITY

    $returnGst = $_POST['return-gst']; // TOTAL RETURN GST
    $refund    = $_POST['NetRefund'];

    if($_SESSION['ADMIN']){
        $updatedBy = $adminId;
    }else{
        $updatedBy = $employeeId;
    }
    $updatedOn = NOW;
    // ========================== end of array data ==============================

    // echo "<br>Stock return Id : $stockReturnId<br>";
    // echo "<br>Refund Mode : $refundMode<br>";
    // echo "<br>Item qty : $itemsCount";
    // echo "<br>Total return Item Qty (FREE + PAID) : $totalReturnQty<br>";
    // echo "<br>Return Gst : $returnGst";
    // echo "<br>NET Refund Amount: $refund";
    // echo "<br>Updated By : $updatedBy";
    // echo "<br><br>";


    // ================ STOCK RETURN DATA UPDATE  BLOCK ==================
    $stockReturnEditUpdate = $StockReturn->stockReturnEditUpdate(intval($stockReturnId), intval($itemsCount), intval($totalReturnQty), floatval($returnGst), $refundMode, floatval($refund), $updatedBy, $updatedOn);
    // $stockReturnEditUpdate = true;

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
        $editReturnItemsIds = $_POST['stock-return-details-item-id'];
        $updatedDetailIdDiff = array_diff($PrvRtrnItemIdArray, $editReturnItemsIds);
    }


    //===================== DELETE DATA ACTION AREA ======================;
    foreach ($updatedDetailIdDiff as $deleteItemId) {

        $prevStokReturnDetailsData = $StockReturn->showStockReturnDetailsById($deleteItemId);
        

        foreach ($prevStokReturnDetailsData as $returnData) {
            $StokInDetailsId = $returnData['stokIn_details_id'];
            $unit = $returnData['unit'];
            $returnQty = $returnData['return_qty'];
            $returnFQty = $returnData['return_free_qty'];
            $totalReturnQTY = intval($returnQty) + intval($returnFQty);

            $itemUnit = preg_replace('/[0-9]/', '', $unit);
            $itemWeightage = preg_replace('/[a-z]/', '', $unit);

            if ($itemUnit == 'tablets' || $itemUnit == 'capsules') {
                $returnQty = intval($totalReturnQTY) * intval($itemWeightage);
            } else {
                $returnQty = $totalReturnQTY;
            }

            $currenStockData = $CurrentStock->showCurrentStocByStokInDetialsId($StokInDetailsId);

            foreach ($currenStockData as $currenStockData) {
                $CurrentItemQTY = $currenStockData['qty'];
                $CurrentLooselyCount = $currenStockData['loosely_count'];

                if ($itemUnit == 'tablets' || $itemUnit == 'capsules') {
                    $updatedLooseCount = intval($CurrentLooselyCount) + intval($returnQty);
                    $updatedQty = intdiv($updatedLooseCount, $itemWeightage);
                } else {
                    $updatedLooseCount = 0;
                    $updatedQty = intval($CurrentItemQTY) + intval($returnQty);
                }

                $updateCurrentStock = $CurrentStock->updateStockByStockInDetailsId(intval($StokInDetailsId), intval($updatedQty), intval($updatedLooseCount));

                $attribute = 'id';
                $deleteStockReturnDetails = $StockReturn->deleteStockByTableData($attribute, $deleteItemId);
            }
        }
    }

    // =============================== eof diff data block ===================================
    // echo "<br><br><br>////////// ====== edit start area ====== \\\\\\\\\\\\\\";
    
    // ============================== update data block start ============================
    
    if ($editReturnItemsIds != null) {
        $stockInDetailsItemId = $_POST['stock-in-details-item-id'];
        for ($i = 0; $i < count($editReturnItemsIds) && $i < count($stockInDetailsItemId); $i++) {

            // echo "edit return item id : "; print_r($editReturnItemsIds);
            // echo "stock in details item id : "; print_r($stockInDetailsItemId);

            $stockReturnDetailsItemId = $editReturnItemsIds[$i];
            // $stockInDetailsItemId = $_POST['stock-in-details-item-id'];
            $productId = $_POST['productId'];
            $productName = $_POST['productName'];
            $batchNo = $_POST['batchNo'];
            $expDate = $_POST['expDate'];
            $setof = $_POST['setof'];
            $updatedItemUnit = preg_replace('/[0-9]/', '', $setof[$i]);
            $updatedItemWeightage = preg_replace('/[a-z]/', '', $setof[$i]);
            $mrp = $_POST['mrp'];
            $ptr = $_POST['ptr'];
            $gstParcent = $_POST['gst'];
            $discountParcent = $_POST['disc'];
            $editReturnQTY = $_POST['return-qty'];
            $editReturnFQty = $_POST['return-free-qty'];
            $PerItemsRefundAmount = $_POST['refund-amount'];  // Per items REFUND AMOUNT

            // echo "<br>STOK RETURN DETAILS ID : $stockReturnDetailsItemId";
            // echo "<br>Stock in details item ID : $stockInDetailsItemId[$i] => $i";
            // echo "<br><br>Product Id : $productId[$i]";
            // echo "<br>Product Name : $productName[$i]";
            // echo "<br>Batch No : $batchNo[$i]";
            // echo "<br>Exp Date : $expDate[$i]";
            // echo "<br>Pack of : $setof[$i]";
            // echo "<br>Item Unit of : $updatedItemUnit";
            // echo "<br>Item Weightage of : $updatedItemWeightage";
            // echo "<br><br>MRP : $mrp[$i]";
            // echo "<br>PTR : $ptr[$i]";
            // echo "<br>GST Percent per Items : $gstParcent[$i]";
            // echo "<br>Discount Percent per Items : $discountParcent[$i]";
            // echo "<br>RETURN QUANTITY per Items : $editReturnQTY[$i]";
            // echo "<br>RETURN FREE QUANTITY per Items : $editReturnFQty[$i]";
            // echo "<br>Per item Refund Amount : $PerItemsRefundAmount[$i]<br>";

            $totalUpdatedReturnQty = intval($editReturnQTY[$i]) + intval($editReturnFQty[$i]);

            // echo "<br>total updated return qty : $totalUpdatedReturnQty";
            // print_r($stockReturnEditUpdate);
            // $stockReturnEditUpdate = true;

            if ($stockReturnEditUpdate) {
            
                // echo "hello";

                $prevStokReturnDetailsData = $StockReturn->showStockReturnDetailsById($stockReturnDetailsItemId);
                
                foreach ($prevStokReturnDetailsData as $prevReturnData) {
                    $returnQty = $prevReturnData['return_qty'];
                    $returnFreeQty = $prevReturnData['return_free_qty'];
                    $totalPrevReturn = intval($returnQty) + intval($returnFreeQty);
                }

                $itemRetundQtyDiff = $totalPrevReturn - $totalUpdatedReturnQty;
                // echo "<br><br>return difference : $itemRetundQtyDiff";


                //===================== update calculation area ===============================

                $CurrentStockData = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($stockInDetailsItemId[$i])); //
                // ech"<br><br>current stock data on stock in edit id => ";
                // print_r($CurrentStockData);

                $currentQty = $CurrentStockData->qty;
                $currentLooseQty = $CurrentStockData->loosely_count;
                

                if ($updatedItemUnit == 'tablets' || $updatedItemUnit == 'capsules') {
                    $updatedLooseQty = intval($currentLooseQty) + (intval($itemRetundQtyDiff) * intval($updatedItemWeightage));
                    $updatedLooseQty = intval($updatedLooseQty);
                    $updatedItemWeightage = intval($updatedItemWeightage);
                    $updatedQty = intdiv($updatedLooseQty, $updatedItemWeightage);
                } else {
                    $updatedLooseQty = 0;
                    $updatedQty = intval($currentQty) + (intval($itemRetundQtyDiff));
                }

                // echo "<br><br>Current stock unit : $updatedItemUnit";
                // echo "<br>current qantity after update : $updatedQty";
                // echo "<br>current Loose qantity after update : $updatedLooseQty";
                // echo "<br><br>//===============================\\<br><br>";

                // echo "<br>current stock update item id : $stockInDetailsItemId[$i]";
                // echo "<br>updated qty for current stock : $updatedQty";
                // echo "<br>updated loose qty for current stock : $updatedLooseQty";
                // echo "<br>updated by : $updatedBy";
                // echo "<br>Updated on : $updatedOn";
                // echo "<br>Exp Date : $expDate[$i]";
                // echo "<br>Pack of : $setof[$i]";
                // echo "<br>Item Unit of : $updatedItemUnit";
                // echo "<br>Item Weightage of : $updatedItemWeightage";
                // echo "<br><br>MRP : $mrp[$i]";

                //========= current stock update function call ============
                $CurrentStockUpdate = $CurrentStock->updateStockByReturnEdit(intval($stockInDetailsItemId[$i]), intval($updatedQty), intval($updatedLooseQty), $updatedBy, $updatedOn);  //updating current stock after edit purchase return
                
                // echo "<br><br>stock return details item id : $stockReturnDetailsItemId"; echo gettype($stockReturnDetailsItemId);
                // echo "<br>edit stock return qty : $editReturnQTY[$i]"; echo gettype($editReturnQTY[$i]);
                // echo "<br>edit stock return free qty : $editReturnFQty[$i]"; echo gettype($editReturnFQty[$i]);
                // echo "<br>edit refund amount : $PerItemsRefundAmount[$i]"; echo gettype($PerItemsRefundAmount[$i]);
                // echo "<br>Updated by : $updatedBy"; echo gettype($updatedBy);
                // echo "<br>Updated on : $updatedOn"; echo gettype($updatedOn);
                // echo "<br><br><br>";

                //========= stock return details update function call ============
                $stockReturnDetailsEdit = $StockReturn->stockReturnDetailsEditUpdate(intval($stockReturnDetailsItemId), intval($editReturnQTY[$i]), intval($editReturnFQty[$i]), floatval($PerItemsRefundAmount[$i]), $updatedBy, $updatedOn);  //updating stock return details table
                
                #################### data fetching and updating end ########################

            }
        }
    }
}



$healthCareDetailsByAdminId = json_decode($HelthCare->showhealthCare($adminId));
$healthCareDetailsByAdminId = $healthCareDetailsByAdminId->data;
    // print_r($healthCareDetailsByAdminId);

    $healthCareName     = $healthCareDetailsByAdminId->hospital_name;
    $healthCareAddress1 = $healthCareDetailsByAdminId->address_1;
    $healthCareAddress2 = $healthCareDetailsByAdminId->address_2;
    $healthCareCity     = $healthCareDetailsByAdminId->city;
    $healthCarePIN      = $healthCareDetailsByAdminId->pin;
    $healthCarePhno     = $healthCareDetailsByAdminId->hospital_phno;
    $healthCareApntbkNo = $healthCareDetailsByAdminId->appointment_help_line;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Return</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/test-bill.css">

</head>

<body>
    <div class="custom-container">
        <div class="custom-body <?php if ($refundMode != 'Credit') {
                                    echo "paid-bg";
                                } ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="<?= SITE_IMG_PATH ?>logo-p.jpg" alt="Medicy">
                    </div>
                    <div class="col-sm-8">
                        <h4 class="text-start my-0"><?php echo $healthCareName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareAddress1 . ', ' . $healthCareAddress2 . ', ' . $healthCareCity . ', ' . $healthCarePIN; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -8px; margin-bottom: 0px;">
                            <small><?php echo 'M: ' . $healthCarePhno . ', ' . $healthCareApntbkNo; ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-dark">
                        <p class="my-0"><b>Return Bill</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id:
                                #<?php echo $stockReturnId; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Refund Mode:
                                <?php echo $refundMode; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Return Date: <?php echo $returnDate; ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <hr class="my-0" style="height:1px; background: #000000; border: #000000;">
            <div class="row my-0">
                <div class="col-sm-6 my-0">
                    <p><small><b>Patient: </b>
                            <?php echo $distributorName; ?></small></p>
                    <!-- <p style="margin-top: -5px; margin-bottom: 0px;"><small>M:
                            <?php //echo 7699753019; echo ', Test date: 241544';
                            ?></small></p> -->
                </div>
                <div class="col-sm-6 my-0 text-end">
                    <p style="margin-top: -3px; margin-bottom: 0px;"><small><b>Bill Date: </b>
                            <?php echo $returnDate; ?></small></p>
                </div>

            </div>
            <hr class="my-0" style="height:1px;">

            <div class="row">
                <!-- table heading -->
                <div class="col-sm-1 ">
                    <small><b>Sl</b></small>
                </div>
                <div class="col-sm-2 ">
                    <small><b>Name</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Batch</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Packing</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Exp.</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>MRP</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>PTR</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>GST%</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>Disc%</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Return</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>Refund</b></small>
                </div>
                <!--/end table heading -->
            </div>

            <hr class="my-0" style="height:1px;">

            <div class="row">
                <?php

                if($editReturnItemsIds != null){
                    for ($i = 0; $i < count($editReturnItemsIds); $i++) {
                        $slno = $i + 1;
    
                        if ($slno > 1) {
                            echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                        }
    
                        echo '
                        <div class="col-sm-1 ">
                            <small>' . $slno . '</small>
                        </div>
                        <div class="col-sm-2 ">
                            <small>' . substr($productName[$i], 0, 15) . '</small>
                        </div>
                        <div class="col-sm-1">
                            <small>' . strtoupper($batchNo[$i]) . '</small>
                        </div>
                        <div class="col-sm-1">
                            <small>' . $setof[$i] . '</small>
                        </div>
                        <div class="col-sm-1">
                            <small>' . $expDate[$i] . '</small>
                        </div>
                        <div class="col-sm-1 text-end">
                            <small>' . $mrp[$i] . '</small>
                        </div>
                        <div class="col-sm-1 text-end">
                            <small>' . $ptr[$i] . '</small>
                        </div>
                        <div class="col-sm-1 text-end">
                            <small>' . $gstParcent[$i] . '</small>
                        </div>
                        <div class="col-sm-1 text-end">
                            <small>' . $discountParcent[$i] . '</small>
                        </div>
                        <div class="col-sm-1">
                            <small>' . $editReturnQTY[$i] . '(' . $editReturnFQty[$i] . 'F)' . '</small>
                        </div>
                        <div class="col-sm-1 text-end">
                            <small>' . $PerItemsRefundAmount[$i] . '</small>
                        </div>';
                    }
                }else{
                    echo'
                    <div class="col-sm-12">
                        <small style="text-align: center;"> ITEM DELETED </small>
                    </div>'; 
                }
                ?>

            </div>
            <!-- </div> -->

            <!-- </div> -->
            <div class="footer">
                <hr calss="my-0" style="height: 1px;">

                <!-- table total calculation -->
                <div class="row my-0">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>CGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $returnGst / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>SGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $returnGst / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total GST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo floatval($returnGst); ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row text-end">
                            <small class="pt-0 mt-0">Total Items <b><?php echo $itemsCount; ?></b> & Total Units
                                <b><?php echo $totalReturnQty; ?></b></small>
                        </div>
                        <div class="row text-end mt-1">
                            <h5 class="mb-0 pb-0">Total Refund: <b>₹<?php echo floatval($refund); ?></b></h5>

                        </div>

                    </div>

                </div>



            </div>
            <hr style="height: 1px; margin-top: 2px;">
        </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <button class="btn btn-primary shadow mx-2" onclick="backToMain()">Go Back</button>
        <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
    </div>
    </div>
</body>
<script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>

<script>
    function backToMain() {
        window.location.href = "<?= URL ?>stock-return.php";
    }
</script>

</html>