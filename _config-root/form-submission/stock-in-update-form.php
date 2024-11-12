<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once dirname(dirname(__DIR__)) . '/config/service.const.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'encrypt.inc.php';
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
require_once CLASS_DIR . 'hospital.class.php';


$StockIn        = new StockIn();
$StockInDetails = new StockInDetails;
$CurrentStock   = new CurrentStock();
$distributor    = new Distributor();
$Session        = new SessionHandler();
$Products       = new Products();
$Manufacturer   = new Manufacturer();
$PackagingUnits = new PackagingUnits();
$StockOut       = new StockOut();
$StcokReturn    = new StockReturn();
$SalesReturn    = new SalesReturn();
$ClinicInfo     = new HealthCare;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {

        $stockIn_Id         = $_POST['stok-in-id'];                 // stock in id
        $prevDistId         = $_POST['prev-distributor-id'];        // previous distributor id fetched from database        
        $distributorId      = $_POST['updated-distributor-id'];     // updated distributo id 

        // distribut details fetched-----
        $distributorDetial = json_decode($distributor->showDistributorById($distributorId));
        $distributorDetial = $distributorDetial->data;
        // print_r($distributorDetial);
        $distributorName      = $distributorDetial->name;
        $distAddress          = $distributorDetial->address;
        $distPIN              = $distributorDetial->area_pin_code;
        $distContact          = $distributorDetial->phno;


        $distPrevBillNo     = $_POST['prev-distributor-bill'];      // previous distributor bill no
        $distributorBill    = $_POST['distributor-bill'];           // updated bill number

        $Items              = $_POST['items'];                      // updated ites array
        $items              = count($_POST['productId']);           // count of items from array
        $totalQty           = $_POST['total-qty'];                  // totar qantity of items
        $billDate           = date_create($_POST['bill-date-val']);
        $billDate           = date_format($billDate, "d-m-Y");      // bill date
        $dueDate            = date_create($_POST['due-date-val']);
        $dueDate            = date_format($dueDate, "d-m-Y");       // due date
        $paymentMode        = $_POST['payment-mode-val'];           // payment mode
        $pMode              = $paymentMode;
        $totalGst           = $_POST['totalGst'];                   // total gst amount
        $amount             = $_POST['netAmount'];                  // net payble amount                   


        // =========== array data ===============
        $product_ids            = $_POST['productId'];          // product id array
        $batch_no               = $_POST['batchNo'];            // products batch number array
        $exp_date               = $_POST['expDate'];            // items expiary date array
        $set_of                 = $_POST['setof'];              // item setof array (weitage / type)
        $item_weightage         = $_POST['weightage'];          // item weightage array
        $item_unit              = $_POST['unit'];               // item unit array
        $item_qty               = $_POST['qty'];                // item qantity array
        $item_free_qty          = $_POST['freeQty'];            // item free qantity array
        $item_mrp               = $_POST['mrp'];                // item mrp array
        $item_ptr               = $_POST['ptr'];                // item ptr array
        $item_gst               = $_POST['gst'];                // item gst percentage array
        $gstAmount_perItem      = $_POST['gstPerItem'];         // per item gst amount array
        $discountPercent        = $_POST['discount'];           // per item discout percent array
        $discPrice              = $_POST['d_price'];            // per item discoutned price array
        // $marginAmount_perItem   = $_POST['margin'];             // per item margin percent array
        $billAmount_perItem     = $_POST['billAmount'];         // per item net amount array 


        // ==== check data =====
        $stockInAttrib = 'id';
        $seleteStockinData = $StockIn->stockInByAttributeByTable($stockInAttrib, $stockIn_Id); // fetching stock in data by previous stockin id
        if ($seleteStockinData[0]['distributor_bill'] != $distributorBill) {
            foreach ($seleteStockinData as $seleteStockinData) {
                $table1 = 'distributor_bill';
                $table2 = 'id';
                $updateBillNumber = $StockInDetails->updateStockInDetailsByTableData($table1, $table2, $distributorBill, $seleteStockinData['id']);  // updating distributor bill number whether it is changed or not
            }
        }


        //========== updating stock in table data ===============
        $updateStockIn = $StockIn->updateStockIn($stockIn_Id, $distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $totalGst, $amount, $employeeId, NOW);   // updating stock in data whethe it is edited or not

        /* updated iitem id array */
        $updatedItemIdsArray = $_POST['purchaseId'];        // item id array after stok in edit update.

        /* previous added items details array */ // fetching previous stock in details id by stock in id
        $PrevStockInDetailsCheck = $StockInDetails->showStockInDetailsByStokId($stockIn_Id);

        /* storing ids of previous added items in a empty array */
        $prevStokInItemIdArray = [];   // empty array for storing previous purchased item ids
        foreach ($PrevStockInDetailsCheck as $StokInids) {
            array_push($prevStokInItemIdArray, $StokInids['id']);
        }

        /* checking difference between two array to point deleted items */
        $ItemArrayIdsDiff = array_diff($prevStokInItemIdArray, $updatedItemIdsArray);


        if (!empty($ItemArrayIdsDiff)) {

            $ItemNotDeleteCount = 0;
            $WholeNotDeletedQty = 0;
            $WholeNotDeletedGstAmount = 0;
            $WholeNotDeletedPrice = 0;

            foreach ($ItemArrayIdsDiff as $deleteItemId) {

                // === **** $deleteItemId => StockInDetailsItemId **** === 
                // fetching current stock in data using stock in details id
                $currentStockData = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($deleteItemId));
                $currentStockItemId = $currentStockData->id;

                // 1. first check stock out data, if stock out != item id, delete data else show alert massage
                $table = 'item_id';
                $stockOutDataCheck = $StockOut->stokOutDetailsDataOnTable($table, $currentStockItemId);

                if ($stockOutDataCheck != null) {
                    $ItemNotDeleteCount = intval($ItemNotDeleteCount) + 1;
                    $stockInDetailsData = $StockInDetails->stockInDetailsById($deleteItemId);

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
                    // if stock out data null, then delete data from current stock
                    $CurrentStockTable = 'stock_in_details_id';
                    $deleteFromCurrentStock = $CurrentStock->deleteByTabelData($CurrentStockTable, $deleteItemId);

                    // delete from stock in details===================
                    $deleteStockInDetails = $StockInDetails->stockInDeletebyDetailsId($deleteItemId);
                }
            }


            // update stock in data according item details.
            $stockInAttribute = 'id';
            $seleteStockinData = $StockIn->stockInByAttributeByTable($stockInAttribute, $stockIn_Id);
            foreach ($seleteStockinData as $stockInData) {
                $itemsCount     = $stockInData['items'];
                $totalQty       = $stockInData['total_qty'];
                $gstAmount      = $stockInData['gst'];
                $wholeAmount    = $stockInData['amount'];
            }

            $updatedItemsCount  = intval($itemsCount) + intval($ItemNotDeleteCount);
            $updatedTotalQty    = intval($totalQty) + intval($WholeNotDeletedQty);
            $updatedGstAmt      = floatval($gstAmount) + floatval($WholeNotDeletedGstAmount);
            $updatedAmt         = floatval($wholeAmount) + floatval($WholeNotDeletedPrice);


            /* update stock in data */
            $updateStockIn = $StockIn->updateStockIn($stockIn_Id, $distributorId, $distributorBill, $updatedItemsCount, $updatedTotalQty, $billDate, $dueDate, $paymentMode, $updatedGstAmt, $updatedAmt, $employeeId, NOW);
            ///////////////////////// check this area again \\\\\\\\\\\\\\\\\\\\\\\\\\\\
        }



        // =========== add of updated stock in details and current stock data ==============
        $count = count($updatedItemIdsArray);
        for ($i = 0; $i < count($updatedItemIdsArray); $i++) {
            if ($updatedItemIdsArray[$i] == '') {     // if updated item id array is blank



                $item_total_qty = intval($item_qty[$i]) + intval($item_free_qty[$i]);
                // echo "<br>item total qty check : $item_total_qty";

                // if ($item_unit[$i] == 'Tablets' || $item_unit[$i] == 'Capsules')
                if (in_array(strtolower($item_unit[$i]), LOOSEUNITS)) {
                    $item_loose_qty = intval($item_total_qty) * intval($item_weightage[$i]);
                    $item_loose_price = floatval($item_mrp[$i]) / intval($item_weightage[$i]);
                } else {
                    $item_loose_qty = 0;
                    $item_loose_price = 0;
                }

                // echo $item_loose_qty;


                $base = (floatval($discPrice[$i]) * intval($item_qty[$i])) / (intval($item_qty[$i]) + intval($item_free_qty[$i]));

                /* add new data to Stock in Details */
                $addToStockInDetails = $StockInDetails->addStockInDetails($stockIn_Id, $product_ids[$i], $distributorBill, $batch_no[$i], $exp_date[$i], $item_weightage[$i], $item_unit[$i], $item_qty[$i], $item_free_qty[$i], $item_loose_qty, $item_mrp[$i], $item_ptr[$i], $discountPercent[$i], $discPrice[$i], $item_gst[$i], $gstAmount_perItem[$i], $base, $billAmount_perItem[$i], $employeeId, NOW);

                $stockInDetailsId = $addToStockInDetails['stockIn_Details_id'];

                /* add new data to current stock */
                $addToCurrentStock = $CurrentStock->addCurrentStock($stockInDetailsId, $product_ids[$i], $batch_no[$i], $exp_date[$i], $distributorId, $item_loose_qty, $item_loose_price, $item_weightage[$i], $item_unit[$i], $item_total_qty, $item_mrp[$i], $item_ptr[$i], $item_gst[$i], $addedBy, NOW, $adminId);
            } else {

                /* update old item data */

                // check data difference by id;
                $stockInDetailsById = $StockInDetails->stockInDetailsById($updatedItemIdsArray[$i]); // fetching previous stock in detaisl data
                foreach ($stockInDetailsById as $stockInDetaislData) {
                    $prevStockInItemQty = intval($stockInDetaislData['qty']) + intval($stockInDetaislData['free_qty']);
                }

                $itemQty = $item_qty[$i];
                $itemFreeQty = $item_free_qty[$i];
                $updatedQty = (intval($itemQty) + intval($itemFreeQty)) - intval($prevStockInItemQty);

                if (in_array(strtolower($item_unit[$i]), LOOSEUNITS)) {
                    $updatedStockInLooseQty = intval($updatedQty) * intval($item_weightage[$i]);
                } else {
                    $updatedStockInLooseQty = 0;
                }

                // fetching current stock data
                $currentStockItmeDetails = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($updatedItemIdsArray[$i]));

                if ($currentStockItmeDetails != null) {
                    $itemId = $currentStockItmeDetails->id;
                    $Loose_Qty = intval($currentStockItmeDetails->loosely_count);
                    $item_Qty = intval($currentStockItmeDetails->qty);
                }


                if (in_array(strtolower($item_unit[$i]), LOOSEUNITS)) {
                    $updated_Loose_Qty = intval($Loose_Qty) + intval($updatedStockInLooseQty);
                    $updated_item_qty = intdiv($updated_Loose_Qty, $item_weightage[$i]);
                } else {
                    $updated_Loose_Qty = 0;
                    $updated_item_qty = intval($item_Qty) + intval($updatedQty);
                }


                /* update to current stock */
                $updateCurrentStockItemData = $CurrentStock->updateCurrentStockByStockInId($updatedItemIdsArray[$i], $product_ids[$i], $batch_no[$i], $exp_date[$i], $distributorId, $updated_Loose_Qty, $updated_item_qty, $item_ptr[$i], $addedBy);

                if (in_array(strtolower($item_unit[$i]), LOOSEUNITS)) {
                    $stockInLooseCount = (intval($item_qty[$i]) + intval($item_free_qty[$i])) * intval($item_weightage[$i]);
                } else {
                    $stockInLooseCount = 0;
                }



                // update to stock in details ======================================
                $base = (floatval($discPrice[$i]) * intval($item_qty[$i])) / (intval($item_qty[$i]) + intval($item_free_qty[$i]));

                $updatedStockInDetails = $StockInDetails->updateStockInDetailsById(intval($updatedItemIdsArray[$i]), $product_ids[$i], $distributorBill, $batch_no[$i], $exp_date[$i], intval($item_weightage[$i]), $item_unit[$i], intval($item_qty[$i]), intval($item_free_qty[$i]), intval($stockInLooseCount), floatval($item_mrp[$i]), floatval($item_ptr[$i]), intval($discountPercent[$i]), floatval($discPrice[$i]), intval($item_gst[$i]), floatval($gstAmount_perItem[$i]), floatval($base), floatval($billAmount_perItem[$i]), $addedBy, NOW);


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
                $stockReturnDetailsData = json_decode($StcokReturn->showStockReturnDataByStokinId($updatedItemIdsArray[$i]));

                if ($stockReturnDetailsData->status == 1) {
                    $stockReturnDetailsData = $stockReturnDetailsData->data;

                    for ($m = 0; $m < count($stockReturnDetailsData); $m++) {
                        // update stock return details by $stockReturnTabelData[0]['id'] and $itemId,
                        $updateStockReturn = $StcokReturn->stockReturnDetailsEditByStockInDetailsId($updatedItemIdsArray[$i], $product_ids[$i], $batch_no[$i], $exp_date[$i], $item_weightage[$i] . $item_unit[$i], $item_qty[$i], $item_free_qty[$i], $item_mrp[$i], $item_ptr[$i], $discountPercent[$i], $item_gst[$i], $addedBy);
                    }
                }
            }
        }


        // exit;
        // $preparedData = url_enc(json_encode(['stockIn_Id' => $stockIn_Id]));
        // header('Location: purchase-invoice.php?data='.$preparedData);
        // $redirectUrl  = URL . "invoices/purchase-invoice.php?data=" . $preparedData;
        $redirectUrl  = URL . "purchase-details.php?search=" . $distributorBill;
        header('Location: ' . $redirectUrl);
        exit;
    }
}
