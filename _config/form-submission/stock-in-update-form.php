<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'stockReturn.class.php';
require_once CLASS_DIR . 'salesReturn.class.php';

$StockIn = new StockIn();
$StockInDetails = new StockInDetails();
$CurrentStock = new CurrentStock();
$distributor = new Distributor();
$Session = new SessionHandler();
$Products = new Products();
$Manufacturer = new Manufacturer();
$PackagingUnits = new PackagingUnits();
$StockOut = new StockOut();
$StcokReturn = new StockReturn();
$SalesReturn = new SalesReturn();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {

        $stockIn_Id         = $_POST['stok-in-id'];
        $prevDistId         = $_POST['prev-distributor-id'];
        $distributorId      = $_POST['updated-distributor-id'];

        $distributorDetial = json_decode($distributor->showDistributorById($distributorId));
        $distributorDetial = $distributorDetial->data;
        foreach ($distributorDetial as $distDeta) {
            $distributorName      = $distDeta->name;
            $distAddress          = $distDeta->address;
            $distPIN              = $distDeta->area_pin_code;
            $distContact          = $distDeta->phno;
        }

        $distPrevBillNo     = $_POST['prev-distributor-bill'];
        $distributorBill    = $_POST['distributor-bill'];

        $Items              = $_POST['items'];
        $items              = count($_POST['productId']);
        $totalQty           = $_POST['total-qty'];
        $billDate           = date_create($_POST['bill-date-val']);
        $billDate           = date_format($billDate, "d-m-Y");
        $dueDate            = date_create($_POST['due-date-val']);
        $dueDate            = date_format($dueDate, "d-m-Y");
        $paymentMode        = $_POST['payment-mode-val'];
        $pMode              = $paymentMode;
        $totalGst           = $_POST['totalGst'];
        $amount             = $_POST['netAmount'];
        $addedBy            = $employeeId;



        $BatchNo            = $_POST['batchNo'];
        $purchaseId         = $_POST['purchaseId'];
        $mfdDate            = $_POST['mfdDate'];
        $expDate            = $_POST['expDate'];

        $crrntDt = date("d-m-Y");

        // =========== array data ===============
        $product_ids = $_POST['productId'];
        $batch_no = $_POST['batchNo'];
        $mfd_date = $_POST['mfdDate'];
        $exp_date = $_POST['expDate'];
        $set_of = $_POST['setof'];
        $item_weightage = $_POST['weightage'];
        $item_unit = $_POST['unit'];
        $item_qty = $_POST['qty'];
        $item_free_qty = $_POST['freeQty'];
        $item_mrp = $_POST['mrp'];
        $item_ptr = $_POST['ptr'];
        $item_gst = $_POST['gst'];
        $gstAmount_perItem = $_POST['gstPerItem'];
        $baseAmount_perItem = $_POST['base'];
        $discountPercent = $_POST['discount'];
        $marginAmount_perItem = $_POST['margin'];
        $billAmount_perItem = $_POST['billAmount'];


        // echo "<br>Stock in id : $stockIn_Id<br>";
        // echo "Previous Distributor id : $prevDistId<br>";
        // echo "Updated Distributor id : $distributorId<br>";
        // echo "Distributor previous bill no :  $distPrevBillNo<br>";
        // echo "Updated Distributo bill no : $distributorBill<br>";
        // echo "items count : $Items<br>";
        // echo "Total qantity : $totalQty<br>";
        // echo "Bill date : $billDate<br>";
        // echo "Due date : $dueDate<br>";
        // echo "Payment mode : $pMode<br>";
        // echo "total Gst : $totalGst<br>";
        // echo "net amount : $amount<br>";
        // echo "added by : $addedBy<br>";

        // echo "<br><br> ================== array data ================== ";
        // echo "<br>Product id array : "; print_r($product_ids);
        // echo "<br>Batch number array : "; print_r($batch_no);
        // echo "<br>mfd date array : "; print_r($mfd_date);
        // echo "<br>exp date array : "; print_r($exp_date);
        // echo "<br>set of array : "; print_r($set_of);
        // echo "<br>item weatage array : "; print_r($item_weightage);
        // echo "<br>item unit array : "; print_r($item_unit);
        // echo "<br>item qty array : "; print_r($item_qty);
        // echo "<br>item free qty array : "; print_r($item_free_qty);
        // echo "<br>item mrp array : "; print_r($item_mrp);
        // echo "<br>item ptr array : "; print_r($item_ptr);
        // echo "<br>item gst array : "; print_r($item_gst);
        // echo "<br>gst amount array : "; print_r($gstAmount_perItem);
        // echo "<br>base amount array : "; print_r($baseAmount_perItem);
        // echo "<br>discount percent array : "; print_r($discountPercent);
        // echo "<br>margin amount array : "; print_r($marginAmount_perItem);
        // echo "<br>bill amount array : "; print_r($billAmount_perItem);
        // echo "<br><br>";




        // ==== check data =====
        $stockInAttrib = 'id';
        $seleteStockinData = $StockIn->stockInByAttributeByTable($stockInAttrib, $stockIn_Id);
        // print_r($seleteStockinData);
        if ($seleteStockinData[0]['distributor_bill'] != $distributorBill) {
            foreach ($seleteStockinData as $seleteStockinData) {
                $table1 = 'distributor_bill';
                $table2 = 'id';
                $updateBillNumber = $StockInDetails->updateStockInDetailsByTableData($table1, $table2, $distributorBill, $seleteStockinData['id']);
            }
        }


        //========== updating stock in table data ===============
        $updateStockIn = $StockIn->updateStockIn($stockIn_Id, $distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $totalGst, $amount, $employeeId, NOW);


        /* updated iitem id array */
        $updatedItemIdsArray = $_POST['purchaseId'];

        /* previous added items details array */
        $PrevStockInDetailsCheck = $StockInDetails->showStockInDetailsByStokId($stockIn_Id);

        /* storing ids of previous added items in a empty array */
        $prevStokInItemIdArray = [];
        foreach ($PrevStockInDetailsCheck as $StokInids) {
            array_push($prevStokInItemIdArray, $StokInids['id']);
        }

        /* checking difference between two array to point deleted items */
        $ItemArrayIdsDiff = array_diff($prevStokInItemIdArray, $updatedItemIdsArray);


        if ($ItemArrayIdsDiff != '') {
            $ItemNotDeleteCount = 0;
            $WholeNotDeletedQty = 0;
            $WholeNotDeletedGstAmount = 0;
            $WholeNotDeletedPrice = 0;
            foreach ($ItemArrayIdsDiff as $deleteItemId) {

                // === **** $deleteItemId => StockInDetailsItemId **** === 
                $currentStockData = $CurrentStock->showCurrentStocByStokInDetialsId($deleteItemId);
                $currentStockItemId = $currentStockData[0]['id'];

                // 1. first check stock out data, if stock out != item id, delete data else show alert massage
                $table = 'item_id';
                $stockOutDataCheck = $StockOut->stokOutDetailsDataOnTable($table, $currentStockItemId);

                if ($stockOutDataCheck != null) {
                    $ItemNotDeleteCount = intval($ItemNotDeleteCount) + 1;
                    $stockInDetailsData = $StockInDetails->showStockInDetailsByStokinId($deleteItemId);

                    foreach ($stockInDetailsData as $itemDetails) {
                        $itemTotalQty = intval($itemDetails['qty']) + intval($itemDetails['free_qty']);
                        $itemGstAmount = $itemDetails['gst_amount'];
                        $itemPrice = $itemDetails['amount'];
                    }

                    $WholeNotDeletedQty = intval($WholeNotDeletedQty) + intval($itemTotalQty);
                    $WholeNotDeletedGstAmount = floatval($WholeNotDeletedGstAmount) + floatval($itemGstAmount);
                    $WholeNotDeletedPrice = floatval($WholeNotDeletedPrice) + floatval($itemPrice);

                    echo '<script>';
                    echo 'Swal.fire({
                                    title: "Warning!",
                                    text: "Some item / items cannot be deleted as it was sold.",
                                    icon: "warning",
                                    });';
                    echo '</script>';
                } else {

                    /// deleting from current stock \\\\==============
                    $CurrentStockTable = 'stock_in_details_id';
                    $deleteFromCurrentStock = $CurrentStock->deleteByTabelData($CurrentStockTable, $deleteItemId);

                    // delete from stock in details===================
                    $deleteStockInDetails = $StockInDetails->stockInDeletebyDetailsId($deleteItemId);
                }
            }

            ///////////////////////////////////////////////// check this area again \\\\\\\\\\\\\\
            // update stock in data according item details.
            $stockInAttribute = 'id';
            $seleteStockinData = $StockIn->stockInByAttributeByTable($stockInAttribute, $stockIn_Id);
            foreach ($seleteStockinData as $stockInData) {
                $itemsCount = $stockInData['items'];
                $totalQty = $stockInData['total_qty'];
                $gstAmount = $stockInData['gst'];
                $wholeAmount = $stockInData['amount'];
            }

            $updatedItemsCount = intval($itemsCount) + intval($ItemNotDeleteCount);
            $updatedTotalQty = intval($totalQty) + intval($WholeNotDeletedQty);
            $updatedGstAmt = floatval($gstAmount) + floatval($WholeNotDeletedGstAmount);
            $updatedAmt = floatval($wholeAmount) + floatval($WholeNotDeletedPrice);


            /* update stock in data */
            $updateStockIn = $StockIn->updateStockIn($stockIn_Id, $distributorId, $distributorBill, $updatedItemsCount, $updatedTotalQty, $billDate, $dueDate, $paymentMode, $updatedGstAmt, $updatedAmt, $employeeId, NOW);
            ///////////////////////// check this area again \\\\\\\\\\\\\\\\\\\\\\\\\\\\
        }



        // =========== add of updated stock in details and current stock data ==============
        $count = count($updatedItemIdsArray);
        for ($i = 0; $i < count($updatedItemIdsArray); $i++) {
            if ($updatedItemIdsArray[$i] == '') {

                // add new data
                // echo "<br><br> === new data === ";
                // echo "<br>" . $i . "-> updated item id array : $updatedItemIdsArray[$i]<br>";
                // echo "$product_ids[$i]<br>";
                // echo "$distributorBill<br>";
                // echo "$batch_no[$i]<br>";
                // echo "$mfd_date[$i]<br>";
                // echo "$exp_date[$i]<br>";
                // echo "$set_of[$i]<br>";
                // echo "$item_weightage[$i]<br>";
                // echo "$item_unit[$i]<br>";
                // echo "$item_qty[$i]<br>";
                // echo "$item_free_qty[$i]<br>";
                // echo "$item_mrp[$i]<br>";
                // echo "$item_ptr[$i]<br>";
                // echo "$item_gst[$i]<br>";
                // echo "$gstAmount_perItem[$i]<br>";
                // echo "$baseAmount_perItem[$i]<br>";
                // echo "$discountPercent[$i]<br>";
                // echo "$marginAmount_perItem[$i]<br>";
                // echo "$billAmount_perItem[$i]<br>";

                $item_total_qty = intval($item_qty[$i]) + intval($item_free_qty[$i]);
                // echo "<br>item total qty check : $item_total_qty";
                if ($item_unit[$i] == 'tablets' || $item_unit[$i] == 'capsules') {
                    $item_loose_qty = intval($item_total_qty) * intval($item_weightage);
                    $item_loose_price = floatval($item_mrp[$i]) / intval($item_weightage[$i]);
                } else {
                    $item_loose_qty = 0;
                    $item_loose_price = 0;
                }

                /* add new data to Stock in Details */
                $addToStockInDetails = $StockInDetails->addStockInDetails($stockIn_Id, $product_ids[$i], $distributorBill, $batch_no[$i], $mfd_date[$i], $exp_date[$i], $item_weightage[$i], $item_unit[$i], $item_qty[$i], $item_free_qty[$i], $item_loose_qty, $item_mrp[$i], $item_ptr[$i], $discountPercent[$i], $baseAmount_perItem[$i], $item_gst[$i], $gstAmount_perItem[$i], $marginAmount_perItem[$i], $billAmount_perItem[$i], $employeeId, NOW, $adminId);

                $stockInDetailsId = $addToStockInDetails['stockIn_Details_id'];

                /* add new data to current stock */
                $addToCurrentStock = $CurrentStock->addCurrentStock($stockInDetailsId, $product_ids[$i], $batch_no[$i], $exp_date[$i], $distributorId, $item_loose_qty, $item_loose_price, $item_weightage[$i], $item_unit[$i], $item_total_qty, $item_mrp[$i], $item_ptr[$i], $item_gst[$i], $addedBy, NOW, $adminId);
            } else {

                // update old data
                // echo "<br><br> === old data ===";
                // echo "<br>" . $i . "-> updated item id array : $updatedItemIdsArray[$i]<br>";
                // echo "Product id : $product_ids[$i]<br>";
                // echo "$distributorBill<br>";
                // echo "$batch_no[$i]<br>";
                // echo "$mfd_date[$i]<br>";
                // echo "$exp_date[$i]<br>";
                // echo "$set_of[$i]<br>";
                // echo "$item_weightage[$i]<br>";
                // echo "$item_unit[$i]<br>";
                // echo "$item_qty[$i]<br>";
                // echo "$item_free_qty[$i]<br>";
                // echo "$item_mrp[$i]<br>";
                // echo "$item_ptr[$i]<br>";
                // echo "$item_gst[$i]<br>";
                // echo "$gstAmount_perItem[$i]<br>";
                // echo "$baseAmount_perItem[$i]<br>";
                // echo "$discountPercent[$i]<br>";
                // echo "$marginAmount_perItem[$i]<br>";
                // echo "$billAmount_perItem[$i]<br>";

                /* update old item data */

                // check data difference by id;
                $stockInDetailsById = $StockInDetails->showStockInDetailsByStokinId($updatedItemIdsArray[$i]);
                foreach ($stockInDetailsById as $stockInDetaislData) {
                    $prevStockInItemQty = intval($stockInDetaislData['qty']) + intval($stockInDetaislData['free_qty']);
                }

                $itemQty = $item_qty[$i];
                $itemFreeQty = $item_free_qty[$i];
                $updatedQty = (intval($itemQty) + intval($itemFreeQty)) - intval($prevStockInItemQty);

                if ($item_unit[$i] == 'tablets' || $item_unit[$i] == 'capsules') {
                    $updatedStockInLooseQty = intval($updatedQty) * intval($item_weightage[$i]);
                } else {
                    $updatedStockInLooseQty = 0;
                }

                // echo "<br><br><br>prev stock in item qty : $prevStockInItemQty";
                // echo "<br>prem item qty : $prevStockInItemQty";
                // echo "<br>updated item qty : $updatedQty";
                // echo "<br>updated item loose qty : $updatedStockInLooseQty";

                // update to current stock data
                $currentStockItmeDetails = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($updatedItemIdsArray[$i]));

                if ($currentStockItmeDetails != null) {
                    $itemId = $currentStockItmeDetails->id;
                    $Loose_Qty = intval($currentStockItmeDetails->loosely_count);
                    $item_Qty = intval($currentStockItmeDetails->qty);
                }


                if ($item_unit[$i] == 'tablets' || $item_unit[$i] == 'capsules') {
                    $updated_Loose_Qty = intval($Loose_Qty) + intval($updatedStockInLooseQty);
                    $updated_item_qty = intdiv($updated_Loose_Qty, $item_weightage[$i]);
                } else {
                    $updated_Loose_Qty = 0;
                    $updated_item_qty = intval($item_Qty) + intval($updatedQty);
                }

                // echo "<br>current stock item id : $itemId";
                // echo "<br>updated current item qty : $updated_item_qty";
                // echo "<br>updated current item loose qty : $updated_Loose_Qty";


                /* update to current stock */
                $updateCurrentStockItemData = $CurrentStock->updateCurrentStockByStockInId($updatedItemIdsArray[$i], $product_ids[$i], $batch_no[$i], $exp_date[$i], $distributorId, $updated_Loose_Qty, $updated_item_qty, $item_ptr[$i], $addedBy);

                if ($item_unit[$i] == 'tablets' || $item_unit[$i] == 'capsules') {
                    $stockInLooseCount = (intval($item_qty[$i]) + intval($item_free_qty[$i])) * intval($item_weightage[$i]);
                } else {
                    $stockInLooseCount = 0;
                }

                // ======= need to check this data ============

                //===========

                $updatedStockInDetails = $StockInDetails->updateStockInDetailsById(intval($updatedItemIdsArray[$i]), $product_ids[$i], $distributorBill, $batch_no[$i], $mfd_date[$i], $exp_date[$i], intval($item_weightage[$i]), $item_unit[$i], intval($item_qty[$i]), intval($item_free_qty[$i]), intval($stockInLooseCount), floatval($item_mrp[$i]), floatval($item_ptr[$i]), intval($discountPercent[$i]), floatval($baseAmount_perItem[$i]), intval($item_gst[$i]), floatval($gstAmount_perItem[$i]), floatval($marginAmount_perItem[$i]), floatval($billAmount_perItem[$i]), $addedBy, NOW);


                /* multiple table update area as bellow data are contain multiple row of same item ids. */

                /* UPDATE STOCK_OUT_DETAILS TABLE AREA */
                $stockOutDetaislTable = 'item_id';
                $checkStocOutDetails = $StockOut->stokOutDetailsDataOnTable($stockOutDetaislTable, $itemId);
                // update on stock out details table
                if (!empty($checkStocOutDetails)) {
                    for ($j = 0; $j < count($checkStocOutDetails); $j++) {
                        $updateStockOutDetailslData = $StockOut->updateStockOutDetaisOnStockInEdit($itemId, $batch_no[$i], $exp_date[$i], $employeeId, NOW);
                    }
                } // END OF STOCK OUT DETAILS UPDATE


                /* UPDDATE SALES_RETURN_DETAILS */ // check this qarry
                $salesReturnDetaislTable = 'item_id';
                $salesReturnDetailsData = $SalesReturn->selectSalesReturnList($salesReturnDetaislTable, $itemId);

                // 1st. check sales return details table have current access data or not
                if (!empty($salesReturnDetailsData)) {
                    for ($l = 0; $l < count($salesReturnDetailsData); $l++) {
                        $salesReturnDetailsUpdate = $SalesReturn->updateSalesReturnOnStockInUpdate(intval($itemId), $batch_no[$i], $exp_date[$i], $addedBy, NOW);
                    }
                }


                /* update on stock return details tabel */ //( check this qarry )
                // 1st. check stock return table have current access data or not
                $table1 = 'stockin_id';
                $data1 = $stockIn_Id;
                // updated table where dist id = $prevDistId, and dist bill number =  $distPrevBillNo;
                $updateStockReturn = $StcokReturn->updateStockReturnOnEditStockIn($table1, $data1, $distributorId, $distributorBill, $addedBy);

                $selectStockReturnData = json_decode($StcokReturn->stockReturnFilter($table1, $data1));
                $selectStockReturnData = $selectStockReturnData->data;

                if (!empty($selectStockReturnData)) {
                    $stockReturnId = $selectStockReturnData[0]->id;
                }

                // update stock return details table
                $stockReturnDetailsData = $StcokReturn->showStockReturnDataByStokinId($updatedItemIdsArray[$i]);

                if (!empty($stockReturnDetailsData)) {
                    for ($m = 0; $m < count($stockReturnDetailsData); $m++) {
                        // update stock return details by $stockReturnTabelData[0]['id'] and $itemId,
                        $updateStockReturn = $StcokReturn->stockReturnDetailsEditByStockInDetailsId($updatedItemIdsArray[$i], $product_ids[$i], $batch_no[$i], $exp_date[$i], $item_weightage[$i] . $item_unit[$i], $item_qty[$i], $item_free_qty[$i], $item_mrp[$i], $item_ptr[$i], $discountPercent[$i], $item_gst[$i], $addedBy);
                    }
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Medicine Purchase Bill</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap-purchaseItem.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/purchase-bill.css">

    <style type="text/css">
        @page {
            size: landscape;
        }
    </style>

    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">

    <!-- Include SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>
</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?php if ($pMode != 'Credit') {
                                    if ($dueDate == $crrntDt) {
                                        echo "paid-bg";
                                    }
                                } ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="<?= SITE_IMG_PATH ?>logo-p.jpg" alt="Medicy">
                    </div>
                    <div class="col-sm-8">
                        <h4 class="text-start my-0"><?php echo $distributorName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $distAddress . ', PIN - ' . $distPIN; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -8px; margin-bottom: 0px;">
                            <small><?php echo 'Contact No : ' . $distContact  ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-dark">
                        <p class="my-0"><b>Stock In Invoice</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id:
                                <?php echo $distributorBill; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Payment Mode: <?php echo $pMode; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill Date: <?php echo $billDate; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Due Date: <?php echo $dueDate; ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <hr class="my-0" style="height:1px; background: #000000; border: #000000;">

            <hr class="my-0" style="height:1px;">

            <div class="row">
                <!-- table heading -->
                <div class="col-sm-1 text-center" style="width: 3%;">
                    <small><b>SL.</b></small>
                </div>
                <div class="col-sm-1" hidden>
                    <small><b>P Id</b></small>
                </div>
                <div class="col-sm-1" style="width: 12%;">
                    <small><b>P Name</b></small>
                </div>
                <div class="col-sm-1" style="width: 12%;">
                    <small><b>Manuf.</b></small>
                </div>
                <div class="col-sm-1" style="width: 8%;">
                    <small><b>Packing</b></small>
                </div>
                <div class="col-sm-1" style="width: 10%;">
                    <small><b>Batch</b></small>
                </div>
                <div class="col-sm-1" style="width: 5%;">
                    <small><b>MFD.</b></small>
                </div>
                <div class="col-sm-1" style="width: 5%">
                    <small><b>Exp.</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 5%;">
                    <small><b>QTY</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 5%;">
                    <small><b>F.QTY</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 7%;">
                    <small><b>MRP</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 7%;">
                    <small><b>PTR</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 5%;">
                    <small><b>Disc(%)</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 5%;">
                    <small><b>GST(%)</b></small>
                </div>
                <div class="col-sm-1b text-end" style="width: 10%;">
                    <small><b>Amount</b></small>
                </div>
                <!--/end table heading -->
            </div>

            <hr class="my-0" style="height:1px;">

            <div class="row">
                <?php
                $slno = 0;
                $itemIds    = $_POST['productId'];
                $itemBillNo    = $_POST['distributor-bill'];
                $distributorId = $distributorId;
                $totalGst = 0;
                $totalMrp = 0;
                $billAmnt = 0;
                // $stokInId = $stokInid;

                $count = count($itemIds);

                $itemDetials = $StockInDetails->showStockInDetailsByStokId($_POST['stok-in-id']);
                // print_r($itemDetials);

                foreach ($itemDetials as $itemsData) {
                    $slno++;



                    $prodId = $itemsData['product_id'];

                    $productDetails = json_decode($Products->showProductsById($prodId));
                    $productDetails = $productDetails->data;
                    // print_r($productDetails);

                    foreach ($productDetails as $pData) {
                        // print_r($pData);
                        $pname = $pData->name;
                        $pManfId = $pData->manufacturer_id;
                        $pType  = $pData->packaging_type;
                        $pQTY = $pData->unit_quantity;
                        $pUnit = $pData->unit;
                    }

                    $packagingData = $PackagingUnits->showPackagingUnitById($pType);
                    foreach ($packagingData as $packData) {
                        $unitNm = $packData['unit_name'];
                    }


                    $manufDetails = json_decode($Manufacturer->showManufacturerById($pManfId));
                    $manufDetails = $manufDetails->data;
                    // print_r($manufDetails);
                    // foreach ($manufDetails as $manufData) {
                        $manufName = $manufDetails->short_name;
                    // }



                    $batchNo = $itemsData['batch_no'];
                    $MfdDate = $itemsData['mfd_date'];
                    $ExpDate = $itemsData['exp_date'];
                    $qty = $itemsData['qty'];
                    $FreeQty = $itemsData['free_qty'];
                    $Mrp = $itemsData['mrp'];
                    $Ptr = $itemsData['ptr'];
                    $discPercent = $itemsData['discount'];
                    $gstPercent = $itemsData['gst'];
                    $Amount = $itemsData['amount'];

                    $gstAmnt =  $itemsData['gst_amount'];

                    $totalGst = $totalGst + $gstAmnt;

                    $totalMrp = $totalMrp + ($Mrp * $qty);
                    $billAmnt = $billAmnt + $Amount;

                    $cGst = $sGst = number_format($totalGst / 2, 2);

                    // $sGst = ;
                    // $itemQty  = $qty[$i];
                    // $mrpOnQty = $mrp[$i];
                    // $mrpOnQty = $mrpOnQty * $itemQty;

                    if ($slno > 1) {
                        echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                    }
                ?>
                    <div class="col-sm-1 text-center" style="width: 3%;">
                        <small> <?php echo "$slno" ?></small>
                    </div>
                    <div class="col-sm-1b " hidden>
                        <small><?php echo "$prodId" ?></small>
                    </div>
                    <div class="col-sm-1b" style="width: 12%;">
                        <small><?php echo "$pname" ?></small>
                    </div>
                    <div class="col-sm-1b" style="width: 12%;">
                        <small><?php echo "$manufName" ?></small>
                    </div>
                    <div class="col-sm-1b" style="width: 8%;">
                        <small><?php echo $pQTY . $pUnit, "/", $unitNm ?></small>
                    </div>
                    <div class="col-sm-1b" style="width: 10%;">
                        <small><?php echo "$batchNo" ?></small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 5%;">
                        <small><?php echo "$MfdDate" ?></small>
                    </div>
                    <div class="col-sm-1 text-center" style="width: 5%;">
                        <small><?php echo "$ExpDate" ?></small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 5%;">
                        <small><?php echo "$qty" ?></small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 5%;">
                        <small><?php echo "$FreeQty" ?></small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 7%;">
                        <small><?php echo "$Mrp" ?></small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 7%;">
                        <small><?php echo "$Ptr" ?></small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 5%;">
                        <small><?php echo "$discPercent%" ?></small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 5%;">
                        <small><?php echo "$gstPercent%" ?></small>
                    </div>
                    <div class="col-sm-1b text-end" style="width: 10%;">
                        <small><?php echo "$Amount" ?></small>
                    </div>
                <?php
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
                                    <!-- <small>₹<?php echo $totalGSt / 2; ?></small> -->
                                    <small>₹<?php echo "$cGst" ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>SGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <!-- <small>₹<?php echo $totalGst / 2; ?></small> -->
                                    <small>₹<?php echo "$cGst" ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total GST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <!-- <small>₹<?php echo floatval($totalGSt); ?></small> -->
                                    <small>₹<?php echo "$totalGst" ?></small>
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
                                    <!-- <small><b>₹<?php echo floatval($totalMrp); ?></b></small> -->
                                    <small>₹<?php echo "$totalMrp" ?></small>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>You Saved:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <!-- <small>₹<?php echo $totalMrp - $billAmnt; ?></small> -->
                                    <small>₹<?php echo $totalMrp - $billAmnt ?></small>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Net Amount:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <!-- <small><b>₹<?php echo floatval($billAmout); ?></b></small> -->
                                    <small><b>₹<?php echo "$billAmnt" ?></b></small>
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
            <hr style="height: 1px; margin-top: 2px;">
        </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <!-- <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button> -->
        <button class="btn btn-primary shadow mx-2" onclick="back()">Add New</button>
        <button class="btn btn-secondary shadow mx-2" style="background-color: #e7e7e7; color: black;" onclick="goBack('<?php echo $stockIn_Id ?>','<?php echo $itemBillNo ?>')">Go Back</button>
        <button class="btn btn-primary shadow mx-2" style="background-color: #4CAF50;" onclick="window.print()">Print Bill</button>
    </div>
    </div>

</body>
<script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
<script>
    const back = () => {
        window.location.replace("<?= URL ?>stock-in.php")
    }

    const goBack = (id, value) => {
        console.log(id);
        console.log(value);
        location.href = `<?= URL ?>stock-in-edit.php?edit=${value}&editId=${id}`;
    }
</script>

</html>