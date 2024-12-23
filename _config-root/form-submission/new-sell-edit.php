<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';
require_once CLASS_DIR.'encrypt.inc.php';

require_once CLASS_DIR . "dbconnect.php";
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . "doctors.class.php";
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';


//  INSTANTIATING CLASS
$Doctors         = new Doctors();
$Patients        = new Patients();
$StockOut        = new StockOut();
$CurrentStock    = new CurrentStock();
$Manufacturur    = new Manufacturer();


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $invoiceNo = $_POST['invoice-id'];
    $customerId = $_POST['customer-id'];

    if ($customerId != 'Cash Sales') {
        $patient = $Patients->patientsDisplayByPId($customerId);
        $patient = json_decode($patient);

        $customerName   = $patient->name;
        $patientAge     = 'Age: ' . $patient->age;
        $patientPhno    = 'M: ' . $patient->phno;
    } else {
        $customerId = 'Cash Sales';
        $customerName = 'Cash Sales';
        $patientAge = '';
        $patientPhno = '';
    }

    $customerName = $_POST['customer-name'];
    $doctorName = $_POST['final-doctor-name'];
    $itemsCount  = $_POST['total-items'];
    $totalItemsQantity = $_POST['total-qty'];
    $totalMRP = $_POST['total-mrp'];

    $totalGstAmount = $_POST['total-gst'];
    $netPaybleAmount = $_POST['bill-amount'];

    $paymentMode = $_POST['payment-mode'];
    $billDAte = $_POST['bill-date'];

    $updatedBy        = $employeeId;
    $updatedOn        = NOW;

    $discountAmount = floatval($totalMRP) - floatval($netPaybleAmount);

    // ============== extra data ===================
    $manufId = $_POST['Manuf'];


    $allowedUnits = ["tablets", "tablet", "capsules", "capsule"];
    

    // echo "<br>======= ARRAYS SECTION ==========<br>";
    // ================ ARRAYS ======================

    $stockOutDataId     = $_POST['stockOut-details-id'];
    $itemId             = $_POST['item-id'];
    $prductId           = $_POST['product-id'];
    $prodName           = $_POST['product-name'];
    $batchNo            = $_POST['batch-no'];
    $expDate            = $_POST['exp-date'];

    $weightage          = $_POST['weightage'];
    $ItemUnit           = $_POST['ItemUnit'];
    // print_r($ItemUnit);
    $itemWeightage      = $_POST['ItemPower'];

    $qty                = $_POST['qty'];
    $qtyType            = $_POST['qty-type'];

    $mrp                = $_POST['mrp'];
    $ptrPerItem         = $_POST['ptr'];
    $discParcent        = $_POST['disc'];
    $discPrice          = $_POST['dPrice'];
    $gstparcent         = $_POST['gst'];
    $gstAmountPerItem   = $_POST['gst-amount'];

    $marginPerItem      = $_POST['margin'];
    // $sellMarginPerItem  = $_POST['saleMargin'];
    $sellMarginPerItem = isset($_POST['saleMargin']) ? $_POST['saleMargin'] : [];
    $taxable            = $_POST['taxable'];
    $amount             = $_POST['amount'];


    //========================== STOCK OUT AND SALES EDIT UPDATE AREA ==========================
    if (isset($_POST['update'])) {

        $discountAmount = floatval($totalMRP) - floatval($netPaybleAmount);

        $stockOutUpdate = $StockOut->updateStockOut(intval($invoiceNo), $customerId, $doctorName, intval($itemsCount), intval($totalItemsQantity), floatval($totalMRP), floatval($discountAmount), floatval($totalGstAmount), floatval($netPaybleAmount), $paymentMode, $billDAte, $updatedBy, $updatedOn);

        // $stockOutUpdate = true;

        if ($stockOutUpdate) {

            // =========== DELETE DATA FROM PHARMACY AND STOCK OUT DETAILS TABLE SECTION =============
            $stockOutDetailsIdList = [];
            $stockOutDetails = $StockOut->stockOutDetailsDisplayById($invoiceNo);
            foreach ($stockOutDetails as $stockOutDetails) {
                array_push($stockOutDetailsIdList, $stockOutDetails['id']);
            }

            $stockOutDetailsIdArrayDiff = array_diff($stockOutDetailsIdList, $stockOutDataId);
            $stockOutDetailsIdArrayDiff = array_values($stockOutDetailsIdArrayDiff);

            // echo "<br>STOCK OUT DETAISL ID ARRAY DIFF : "; print_r($stockOutDetailsIdArrayDiff);
            /// =========== SELL UPDATED DELTED PRODUCT UPDATED SECTION ==================
            for ($i = 0; $i < count($stockOutDetailsIdArrayDiff); $i++) {

                $selectFromStockOutDetails = $StockOut->stokOutDetailsDataOnTable('id', $stockOutDetailsIdArrayDiff[$i]);

                foreach ($selectFromStockOutDetails as $stockOutData) {
                    $currenStockItemId = $stockOutData['item_id'];

                    if (in_array(strtolower($stockOutData['unit']), $allowedUnits)){
                        $itemQantity = $stockOutData['loosely_count'];
                    } else {
                        $itemQantity = $stockOutData['qty'];
                    }
                }


                $currenStockData = $CurrentStock->showCurrentStocById($currenStockItemId);
                foreach ($currenStockData as $currenStockData) {
                    // print_r($currenStockData);
                    if (in_array(strtolower($currenStockData['unit']), $allowedUnits)){
                        $currentQty = $currenStockData['loosely_count'];
                        $updatedLooseQty = intval($currentQty) + intval($itemQantity);
                        $updatedQty = intdiv($updatedLooseQty, intval($currenStockData['weightage']));
                    } else {
                        $currentQty = $currenStockData['qty'];
                        $updatedQty = intval($currentQty) + intval($itemQantity);
                        $updatedLooseQty = 0;
                    }
                }
                
                // ****** UPDATE CURRENT STOCK AND DELTE FROM PHAMACY INVOCIE AND STOCK OUT DATA ******
                $updateCurrenStock = $CurrentStock->updateStockOnSell(intval($currenStockItemId), intval($updatedQty), intval($updatedLooseQty));

                $delteFromStockOutDetails = $StockOut->deleteFromStockOutDetailsOnId($stockOutDetailsIdArrayDiff[$i]);
            }

            // ================ UPDATE DATA ON PHARMACY AND STOCK OUT DETAILS TABLE ===========
            for ($i = 0; $i < count($stockOutDataId); $i++) {
                // echo "<br><br><br>";
                if ($stockOutDataId[$i] == '') {

                    $newItemId = $itemId[$i];
                    $newProductId = $prductId[$i];
                    $newProductName = $prodName[$i];
                    $newItemBatchNo = $batchNo[$i];
                    $newItemSetOf = $weightage[$i];
                    $newItemUnit = preg_replace('/[0-9]/', '', $newItemSetOf);
                    $newItemWeatage = preg_replace('/[a-z-A-Z]/', '', $newItemSetOf);
                    $newItemExpDate = $expDate[$i];
                    $newItemMrp = $mrp[$i];
                    $newItemDiscParcent = $discParcent[$i];
                    $newItemGstPercent = $gstparcent[$i];
                    $newItemGstAmount = $gstAmountPerItem[$i];

                    if (in_array(strtolower($newItemUnit), $allowedUnits)) {
                        $newItemQty = intdiv(intval($qty[$i]), intval($newItemWeatage));
                        $newItemLooseQty = $qty[$i];
                    } else {
                        $newItemQty = $qty[$i];
                        $newItemLooseQty = 0;
                    }

                    $newItemQtyType = $qtyType[$i];
                    $newItemTaxable = $taxable[$i];
                    $newItemAmount = $amount[$i];
                    $newItemPtr = $ptrPerItem[$i];
                    $newSellMargin = $sellMarginPerItem[$i];
                    $newItemMargin = $marginPerItem[$i];
                    

                    // =========== ADD NEW DATA ON STOCK OUT DETAILS TABLE =============\
                    $addStockOutDetails = $StockOut->addStockOutDetails(intval($invoiceNo), intval($newItemId), $newProductId, $newProductName, $newItemBatchNo, $newItemExpDate, $newItemWeatage, $newItemUnit, intval($newItemQty), intval($newItemLooseQty), floatval($newItemMrp), floatval($newItemPtr), intval($newItemDiscParcent), intval($newItemGstPercent), floatval($newItemGstAmount), floatval($newSellMargin), floatval($newItemMargin), floatval($newItemTaxable), floatval($newItemAmount));

                    //========== update current stock ==========
                    $currentStockData = $CurrentStock->showCurrentStocById($newItemId);
                    // print_r($currentStockData);
                    foreach ($currentStockData as $currentData) {
                        if (in_array(strtolower($currentData['unit']), $allowedUnits)) {
                            $currentLooseQty = $currentData['loosely_count'];
                            $updatedLooseQty = intval($currentLooseQty) - intval($newItemLooseQty);
                            $updatedCurrentQty = intdiv(intval($updatedLooseQty), intval($currentData['weightage']));
                        } else {
                            $currentQty = $currentData['qty'];
                            $updatedLooseQty = 0;
                            $updatedCurrentQty = intval($currentQty) - intval($item_qty);
                        }
                    }

                    if ($addStockOutDetails) {
                        // =============== update current stock on new items ============
                        $updateCurrentStock = $CurrentStock->updateStockOnSell(intval($newItemId), intval($updatedCurrentQty), intval($updatedLooseQty));
                    }
                }


                if ($stockOutDataId[$i] != '') {
                    $updatedItemId = $itemId[$i];
                    $updatedProductId = $prductId[$i];
                    $updatedProductName = $prodName[$i];
                    $stockOutDetialsId = $stockOutDataId[$i];
                    $updatedBatchNo = $batchNo[$i];
                    $updatedSetOf = $weightage[$i];
                    $updatedItemUnit = preg_replace('/[0-9]/', '', $updatedSetOf);
                    $updatedItemWeatage = preg_replace('/[a-z-A-Z]/', '', $updatedSetOf);
                    $updatedExpDate = $expDate[$i];
                    $updatedMrp = $mrp[$i];
                    $updatedDiscPercent = $discParcent[$i];
                    $updatedDiscPrice = $discPrice[$i];
                    $updatedGst = $gstparcent[$i];
                    $updatedGstAmount = $gstAmountPerItem[$i];

                    if (in_array(strtolower($updatedItemUnit), $allowedUnits))  {
                        $updatedItemQty = intdiv(intval($qty[$i]), intval($updatedItemWeatage));
                        $updatedItemLooseQty = $qty[$i];
                    } else {
                        $updatedItemQty = $qty[$i];
                        $updatedItemLooseQty = 0;
                    }

                    $updatedQtyType = $qtyType[$i];
                    $updatedTaxableAmount = $taxable[$i];
                    $updatedPaybleAmount = $amount[$i];
                    $updatedItemPtr = $ptrPerItem[$i];
                    // $updateSellMargin = $sellMarginPerItem[$i];
                    $updateSellMargin = isset($sellMarginPerItem[$i]) ? $sellMarginPerItem[$i] : 0;
                    $updatedMargin = $marginPerItem[$i];
                    
                    // echo "<br>OLD ITEMS=====";

                    // ======================== UPDATE DATA start ==========================
                    $table = 'id';
                    $selectStockOutDetailsData = $StockOut->stokOutDetailsDataOnTable($table, $stockOutDetialsId);

                    foreach ($selectStockOutDetailsData as $stockOutDataCheck) {
                        if (in_array(strtolower($stockOutDataCheck['unit']), $allowedUnits)) {
                            $stockOutItemLooseCount = $stockOutDataCheck['loosely_count'];
                            $itemCountDiff = intval($stockOutItemLooseCount) - intval($updatedItemLooseQty);
                        } else {
                            $stockOutItemQantity = $stockOutDataCheck['qty'];
                            $itemCountDiff = intval($stockOutItemQantity) - intval($updatedItemQty);
                        }
                    }


                    // ====== update current stock ===========
                    $currentStockData = $CurrentStock->showCurrentStocById($updatedItemId);
                    // echo "<br>"; print_r($currentStockData);
                    foreach ($currentStockData as $currentData) {
                        if (in_array(strtolower($currentData['unit']), $allowedUnits))  {
                            $currentLooseQty = $currentData['loosely_count'];
                            $updatedLooseQty = intval($currentLooseQty) + (intval($itemCountDiff));
                            $updatedCurrentQty = intdiv(intval($updatedLooseQty), intval($currentData['weightage']));
                        } else {
                            $currentQty = $currentData['qty'];
                            $updatedLooseQty = 0;
                            $updatedCurrentQty = intval($currentQty) + (intval($itemCountDiff));
                        }
                    }


                    // ====== update stock out details =======
                    $updateStockOutData = $StockOut->updateStockOutDetaislById(intval($stockOutDetialsId), intval($updatedItemQty), intval($updatedItemLooseQty), intval($updatedDiscPercent), floatval($updateSellMargin), floatval($updatedMargin), floatval($updatedTaxableAmount), floatval($updatedGstAmount), floatval($updatedPaybleAmount), $updatedBy, $updatedOn);

                    if ($updateStockOutData) {
                        // ====== update current stock data ===========
                        $updateCurrentStock = $CurrentStock->updateStockOnSell(intval($updatedItemId), intval($updatedCurrentQty), intval($updatedLooseQty));
                    }
                }
            }
        }

        $redirectUrl = URL . "invoices/print.php?name=sales&id=" . url_enc($invoiceNo);
        header("Location: " .$redirectUrl );
    }
}

exit;

// header("Location: item-invoice-reprint.php?id=".url_enc($invoiceNo));
// $redirectUrl = URL."invoices/sales-invoice.php?id=".url_enc($invoiceNo);
// $redirectUrl = URL . "invoices/print.php?name=sales&id=" . url_enc($invoiceNo);
// header("Location: " .$redirectUrl );
// exit;
?>

<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Sales Bill</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/test-bill.css">

</head>

<body>
    <div class="custom-container">
        <div class="custom-body <?php if ($paymentMode != 'Credit') {
                                    echo "paid-bg";
                                } ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="<?= $healthCareLogo ?>" alt="Medicy">
                    </div>
                    <div class="col-sm-8">
                        <h4 class="text-start my-0"><?php echo $healthCareName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareAddress1 . ', ' . $healthCareAddress2 . ', ' . $healthCareCity . ', ' . $healthCarePin; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -8px; margin-bottom: 0px;">
                            <small><?php echo 'M: ' . $healthCarePhno . ', ' . $healthCareApntbkNo; ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-dark">
                        <p class="my-0"><b>Invoice</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id:
                                <?php echo $invoiceNo; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Payment: <?php echo $paymentMode; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Date: <?php echo $billDAte; ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <hr class="my-0" style="height:1px; background: #000000; border: #000000;">
            <div class="row my-0">
                <div class="col-sm-6 my-0">
                    <p style="margin-top: -3px; margin-bottom: 0px;">
                        <small><b>Patient: </b> <?php echo $customerName . ' ' . $patientAge . ' ' . $patientPhno; ?>
                        </small>
                    </p>

                </div>
                <div class="col-sm-6 my-0">
                    <p class="text-end" style="margin-top: -3px; margin-bottom: 0px;"><small><b>Refered By:</b>
                            <?php echo $doctorName; ?></small></p>
                    <p class="text-end" style="margin-top: -5px; margin-bottom: 0px;">
                        <small><?php //if($doctorReg != NULL){echo 'Reg: '.$doctorReg; } 
                                ?></small>
                    </p>
                </div>

            </div>
            <hr class="my-0" style="height:1px;">

            <div class="row">
              table heading -
                <div class="col-sm-1 text-center">
                    <small><b>SL.</b></small>
                </div>
                <div class="col-sm-2 ">
                    <small><b>Name</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Manuf.</b></small>
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
                    <small><b>QTY</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>MRP</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>Disc(%)</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>GST(%)</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>Amount</b></small>
                </div>
                end table heading -->
            <!-- </div> -->

            <!-- <hr class="my-0" style="height:1px;"> -->

            <!-- <div class="row"> -->
                <!-- <?php /* -->
                $slno = 0;
                $subTotal = floatval(00.00);
                $itemIds    = $_POST['product-id'];
                $count = count($itemIds);
                for ($i = 0; $i < $count; $i++) {
                    $slno++;

                    
                    if ($manufId[$i] != '') {
                        $manufDetail = json_decode($Manufacturur->showManufacturerById($manufId[$i]));
            
                        if ($manufDetail->status) {
                            $manufData = $manufDetail->data;
                            if(isset($manufData->short_name)){
                                $manufSName = $manufData->short_name;
                            }else{
                                $manufSName = '';
                            }
                        } else {
                            $manufSName = '*New Iitem';
                        }
                    } else {
                        $manufSName = '';
                    }


                    // if ($ItemUnit[$i] == 'Tablets' || $ItemUnit[$i] == 'Capsules') 
                    if (in_array(strtolower($ItemUnit[$i]), $allowedUnits)){
                        $unitStamp = $ItemUnit[$i];
                    } else {
                        $unitStamp = '';
                    }



                    if ($slno > 1) {
                        echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                    }

                    echo '<div class="col-sm-1 text-center">
                                    <small>' . $slno . '</small>
                            </div>
                                <div class="col-sm-2 ">
                                    <small>' . $prodName[$i] . '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . $manufSName . '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . $batchNo[$i] . '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . $weightage[$i] . '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . $expDate[$i] . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $qty[$i] . ' ' . $unitStamp . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $mrp[$i] . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $discParcent[$i] . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $gstparcent[$i] . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $amount[$i] . '</small>
                                </div>';

                    // $subTotal = floatval($subTotal + $amount);
                } */
                ?>

             <!-- </div>
            </div> -->

            <!-- </div> 
            <div class="footer">
                <hr calss="my-0" style="height: 1px;">

                table total calculation -->
                <!-- <div class="row my-0">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>CGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $totalGstAmount / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>SGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $totalGstAmount / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total GST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo floatval($totalGstAmount); ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total MRP:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small><b>₹<?php echo floatval($totalMRP); ?></b></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Net Price :</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small><b>₹<?php echo floatval($netPaybleAmount); ?></b></small>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>You Saved:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $totalMRP - $netPaybleAmount; ?></small>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>



            </div>
            <hr style="height: 1px; margin-top: 2px;">
        </div>
    </div> -->
    <!-- <div class="justify-content-center print-sec d-flex my-5"> -->
        <!-- <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button> -->
        <!-- <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button>
        <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
    </div>
    </div>
</body>
<script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>

</html> -->