<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once  CLASS_DIR . 'hospital.class.php';
require_once  CLASS_DIR . 'stockOut.class.php';
require_once  CLASS_DIR . 'patients.class.php';
require_once  CLASS_DIR . 'products.class.php';
require_once  CLASS_DIR . 'doctors.class.php';
require_once  CLASS_DIR . 'salesReturn.class.php';
require_once  CLASS_DIR . 'currentStock.class.php';
require_once  CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


//  INSTANTIATING CLASS
$HelthCare       = new HealthCare();
$StockOut        = new StockOut();
$Patients        = new Patients();
$Products        = new Products();
$Doctors         = new Doctors();
$SalesReturn     = new SalesReturn();
$CurrentStock    = new CurrentStock();
$StockInDetails  = new StockInDetails();

// $IdGeneration    = new IdGeneration();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['sales-return-edit'])) {

        $returnId        = $_POST['salesreturn-id'];
        $returnDate     = $_POST['return-date'];
        $totalQtys      = $_POST['total-qty'];
        $refundMode     = $_POST['refund-mode'];

        //======================== ARRAY DATA ===================================
        $returnItemId   = $_POST['return-Item-Id'];
        $products   = $_POST['productId'];
        $batchNo    = $_POST['batchNo'];
        $expdates   = $_POST['expDate'];
        $weatage    = $_POST['setof'];
        $purchase_qtys       = $_POST['p-qty'];
        $current_qty = $_POST['current-qty'];
        $mrp        = $_POST['mrp'];
        $discs      = $_POST['disc'];
        $gst        = $_POST['gst'];
        $taxableAmount   = $_POST['taxable'];
        $retnQty  = $_POST['return'];
        $billAmount = $_POST['refund'];
        //print_r($refunds);
        $invoice        = $_POST['invoice'];
        $billDate       = $_POST['purchased-date'];
        $billDate       = date('Y-m-d', strtotime($billDate));
        $items          = $_POST['total-items'];
        $gstAmount      = $_POST['gst-amount'];
        $totalRefundAmount   = $_POST['refund-amount'];
        $invoiceId      = str_replace("#", '', $invoice);
        $sold           = $StockOut->stockOutDisplayById($invoiceId);
        $customerId     = $sold[0]['customer_id'];
        // ============== PATIENT DETAILS DATA ==============================
        if ($customerId == 'Cash Sales') {
            $patientNm = 'Cash Sell';
            $patientPNo = '';
            
        } else {
            
            $patient        = $Patients->patientsDisplayByPId($customerId);
            if ($patient != null) {
                $patient        = json_decode($patient);
                $patientNm = $patient->name;
                $patientPNo = $patient->phno;
            }
        }

        $allowedUnits = ["tablets", "tablet", "capsules", "capsule"];

        //=====================================================================

        // fetching sales return data from sales return tabel------------------
        $checkStockOutReturn = $SalesReturn->salesReturnByID($returnId);
        // echo "<br><br>Prev salres return : "; print_r($checkStockOutReturn);
        $prevItemCount = $checkStockOutReturn[0]['items'];
        // echo "<br><br>$prevItemCount";

        $updatedGstAmount = $gstAmount;
        $updatedTotalRefund = $totalRefundAmount;
        $updatedItemCount = $items;

        if ($prevItemCount != $items) {
            $table1 = 'sales_return_id';
            $seletReturnDetails = $SalesReturn->selectSalesReturnList($table1, $returnId);

            $returnDetailsId = [];
            foreach ($seletReturnDetails as $seletReturnDetails) {
                array_push($returnDetailsId, $seletReturnDetails['id']);
            }
            $returnEditDiff = array_diff($returnDetailsId, $returnItemId);
            $countItems = count($returnEditDiff);

            foreach ($returnEditDiff as $diffId) {
                $table2 = 'id';
                $seletReturnDetails = $SalesReturn->selectSalesReturnList($table2, $diffId);
                // echo "<br><br> Diff return details : "; print_r($seletReturnDetails);
                foreach ($seletReturnDetails as $seletReturnDetails) {
                    $refund = $seletReturnDetails['refund_amount'];
                    $taxable = $seletReturnDetails['taxable'];
                    $returnGstAmount = floatval($refund) - floatval($taxable);
                }
                $updatedGstAmount = floatval($updatedGstAmount) + floatval($returnGstAmount);
                $updatedTotalRefund = floatval($updatedTotalRefund) + floatval($refund);
            }
        } else {
            $countItems = 0;
        }
        $updatedItemCount = intval($items) + intval($countItems);

        // ========= update total qty count ================
        $totalPrevReturnQty = 0;
        for ($i = 0; $i < count($returnItemId); $i++) {
            $col = 'id';
            $checkSalesReturnDetails = $SalesReturn->selectSalesReturnList($col, $returnItemId[$i]);

            $totalPrevReturnQty = intval($totalPrevReturnQty) + intval($checkSalesReturnDetails[0]['return_qty']);
        }

        $fetchCol = 'id';
        $salesReturnData = $SalesReturn->selectSalesReturn($fetchCol, $returnId);

        $updatedTotalQty = (intval($salesReturnData[0]['total_qty']) - intval($totalPrevReturnQty)) + intval($totalQtys);

        // echo "<br>Sales Return ID : "; print_r($returnId);
        // echo "<br>Return Date : "; print_r($returnDate);
        // echo "<br>Items : "; print_r($updatedItemCount);
        // echo "<br>Total QTY : "; print_r($updatedTotalQty);
        // echo "<br>Refund Mode : "; print_r($refundMode);


        $salesReturnData = $SalesReturn->updateSalesReturn(intval($returnId), $returnDate, intval($updatedItemCount), intval($updatedTotalQty), floatval($updatedGstAmount), floatval($updatedTotalRefund), $refundMode, $employeeId, NOW);
        // ----------------------------------------------------------------------------------------
        // now check and update stock return details table with edit data ----------------------

        if ($salesReturnData) {

            foreach ($returnItemId as $returnItemId) {

                $salesReturnId =  array_shift($_POST['return-Item-Id']);
                $productID =  array_shift($_POST['productId']);
                $batchN =  array_shift($_POST['batchNo']);
                $expDate = array_shift($_POST['expDate']);
                $unit =  array_shift($_POST['setof']);
                $itemUnit =  preg_replace('/[0-9]/', '', $unit);
                $itemWeatage =  preg_replace('/[a-z-A-Z]/', '', $unit);
                $MRP = array_shift($_POST['mrp']);;
                $discountPercent =  array_shift($_POST['disc']);
                $gstPercent =  array_shift($_POST['gst']);
                $Taxable =  array_shift($_POST['taxable']);
                $returnQty =  array_shift($_POST['return']);
                $refundAmount =  array_shift($_POST['refund']);
                $updatedGstAmount = floatval($refundAmount) - floatval($Taxable);


                // echo "<br><br>";
                // echo "<br>Sales return Id : $salesReturnId";
                // echo "<br>Product Id : $productID";
                // echo "<br>Batch no : $batchN";
                // echo "<br>Unit : $unit";
                // echo "<br>Item Unit type : $itemUnit";
                // echo "<br>Item weatage : $itemWeatage";
                // echo "<br>MRP : $MRP";
                // echo "<br>Discount percent : $discountPercent";
                // echo "<br>GST percent : $gstPercent";
                // echo "<br>Taxable : $Taxable";

                // echo "<br>Return QTY : $returnQty";
                // echo "<br>Refund Amount : $refundAmount";


                $table3 = 'id';
                $checkSalesReturn = $SalesReturn->selectSalesReturnList($table3, $returnItemId);
                foreach ($checkSalesReturn as $prevReturnData) {
                    $itemId = $prevReturnData['item_id'];
                    $prevReturnQty = $prevReturnData['return_qty'];
                }

                $returnDiff = intval($returnQty) - intval($prevReturnQty);

                //============= fetching current stock data for update current stock ==========
                $stock = $CurrentStock->showCurrentStocById($itemId);
                // echo "<br><br>Current stock details==";
                // print_r($stock);
                foreach ($stock as $crntStock) {
                    $crntQTY = $crntStock['qty'];
                    $crntLQTY = $crntStock['loosely_count'];
                }

                // if ($itemUnit == 'Tablets' || $itemUnit == 'Capsules') 
                if (in_array(strtolower($itemUnit), $allowedUnits)){
                    $updatedLooseQty = intval($crntLQTY) + (intval($returnDiff));
                    $updatedQty = intdiv($updatedLooseQty, $itemWeatage);
                } else {
                    $updatedQty = intval($crntQTY) + (intval($returnDiff));
                    $updatedLooseQty = 0;
                }


                $successUpdateReturn = $SalesReturn->updateSalesReturnDetails(intval($salesReturnId), floatval($updatedGstAmount), floatval($Taxable), intval($returnQty), floatval($refundAmount), $employeeId, NOW);

                // ============ UPDATING CURRENT STOCK ================

                // echo "<br><br>$itemId";
                // echo "<br>$updatedQty";
                // echo "<br>$updatedLooseQty";

                if ($successUpdateReturn) {
                    $CurrentStock->updateStockOnSell($itemId, $updatedQty, $updatedLooseQty);
                }
            }
            $totalRefundAmount = $totalRefundAmount;
        }

    }
}


// header("Location: sales-return-details.php?id=".url_enc($returnId));
// $redirectUrl = URL."invoices/sales-return-invoice.php?id=".url_enc($returnId);
$redirectUrl = URL."invoices/print.php?name=salesReturn&id=".url_enc($returnId);
header("Location: " .$redirectUrl );
exit;
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Lab Test Bill</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/test-bill.css">

    <style>
        body {
            overscroll-behavior-y: contain;
        }
    </style>
</head>


<body>
    <?php if (isset($_POST['sales-return-edit'])) { ?>
        <div class="custom-container">
            <div class="custom-body">
                <div class="card-body border-bottom border-dark">
                    <div class="row">
                        <div class="col-sm-1">
                            <img class="float-end" style="height: 55px; width: 58px;" src="<?= SITE_IMG_PATH ?>logo-p.jpg" alt="Medicy">
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
                            <p class="my-0"><b>Return Bill</b></p>
                            <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id:
                                    <?php echo $invoice; ?></small></p>
                            <p style="margin-top: -5px; margin-bottom: 0px;"><small>Return Mode:
                                    <?php echo $refundMode; ?></small>
                            </p>
                            <p style="margin-top: -5px; margin-bottom: 0px;"><small>Return Date:
                                    <?php echo $returnDate; ?></small>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- <hr class="my-0" style="height:1px; background: #000000; border: #000000;"> -->
                <div class="row my-0">
                    <div class="col-sm-6 my-0">
                        <p style="margin-top: -3px; margin-bottom: 0px;">
                            <small><b>Patient: </b> <?php echo $patientNm; ?>, Contact:
                                <?php echo $patientPNo; ?>
                            </small>
                        </p>

                    </div>
                    <div class="col-sm-6 my-0">
                        <p class="text-end" style="margin-top: -3px; margin-bottom: 0px;"><small><b>Refered By:</b>
                                <?php echo $sold[0]['reff_by']; ?></small></p>
                        <p class="text-end" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php //if($doctorReg != NULL){echo 'Reg: '.$doctorReg; } 
                                    ?></small>
                        </p>
                    </div>

                </div>
                <hr class="my-0" style="height:1px;">

                <div class="row">
                    <!-- table heading -->
                    <div class="col-sm-1 text-center">
                        <small><b>SL.</b></small>
                    </div>
                    <div class="col-sm-2 ">
                        <small><b>Name</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>Batch</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>Unit</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>Exp.</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>MRP</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>Disc(%)</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>GST(%)</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>Purchase</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>Return</b></small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small><b>Refunds</b></small>
                    </div>
                    <!--/end table heading -->
                </div>

                <hr class="my-0" style="height:1px;">

                <!-- <div class="row"> -->
                <?php
                $slno = 0;
                $subTotal = floatval(00.00);
                //print_r()

                foreach ($products as $product) {
                    $slno++;
                    $i = 0;

                    if ($slno > 1) {
                        echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                    }

                    $chkExistance = json_decode($Products->productExistanceCheck($product));
                    if($chkExistance->status){
                        $edtRqstFlg = 1;
                    }else{
                        $edtRqstFlg = '';
                    }

                    $showProducts = json_decode($Products->showProductsByIdOnUser($product, $adminId, $edtRqstFlg));
                    $showProducts = $showProducts->data;

                    echo '
                                <div class="row">
                                    <div class="col-sm-1 text-center">
                                        <small>' . $slno . '</small>
                                    </div>
                                    <div class="col-sm-2 ">
                                        <small>' . substr($showProducts[0]->name, 0, 15) . '</small>
                                    </div>
                    
                                    <div class="col-sm-1">
                                        <small>' . array_shift($batchNo) . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . array_shift($weatage) . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . array_shift($expdates) . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . array_shift($mrp) . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . array_shift($discs) . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . array_shift($gst) . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . array_shift($purchase_qtys) . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . array_shift($retnQty) . '</small>
                                    </div>
                                    <div class="col-sm-1 text-end">
                                        <small>' . array_shift($billAmount) . '</small>
                                    </div>
                                </div>';
                    $i++;
                }
                ?>

                <!-- </div> -->
                <!-- </div> -->

                <!-- </div> -->
                <div class="footer border-top border-bottom border-dark mt-4">
                    <!-- <hr calss="my-0" style="height: 1px;"> -->

                    <!-- table total calculation -->
                    <div class="row my-2 ">
                        <div class="col-4"></div>
                        <div class="col-4 border-end border-dark">
                            <div class="row">
                                <div class="col-8 text-end">
                                    <p style="margin-top: -5px; margin-bottom: 0px;"><small>CGST:</small></p>
                                </div>
                                <div class="col-4 text-end">
                                    <p style="margin-top: -5px; margin-bottom: 0px;">
                                        <small>₹<?php echo (float)$gstAmount / 2; ?></small>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 text-end">
                                    <p style="margin-top: -5px; margin-bottom: 0px;"><small>SGST:</small></p>
                                </div>
                                <div class="col-4 text-end">
                                    <p style="margin-top: -5px; margin-bottom: 0px;">
                                        <small>₹<?php echo (float)$gstAmount / 2; ?></small>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 text-end">
                                    <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total GST:</small></p>
                                </div>
                                <div class="col-4 text-end">
                                    <p style="margin-top: -5px; margin-bottom: 0px;">
                                        <small>₹<?php echo floatval($gstAmount); ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="row">
                                <div class="col-8 text-end">
                                    <p style="margin-top: -5px; margin-bottom: 0px;"><small>Items: </small></p>
                                </div>
                                <div class="col-4 text-end">
                                    <p style="margin-top: -5px; margin-bottom: 0px;">
                                        <small><?php echo $items; ?></small>
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-8 text-end">
                                    <p style="margin-top: -5px; margin-bottom: 0px;"><small>Refund:</small></p>
                                </div>
                                <div class="col-4 text-end">
                                    <p style="margin-top: -5px; margin-bottom: 0px;">
                                        <small><b>₹<?php echo floatval($totalRefundAmount); ?></b></small>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>



                </div>
                <!-- <hr style="height: 1px; margin-top: 2px;"> -->
            </div>
        </div>
        <div class="justify-content-center print-sec d-flex my-5">
            <!-- <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button> -->
            <button class="btn btn-primary shadow mx-2" onclick="backToMain()">Go Back</button>
            <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
        </div>
        </div>
    <?php
    } else {
    ?>

        <div class="container mt-3">
            <h2></h2>
            <div class="mt-4 p-5 bg-primary text-white rounded text-center">
                <h1>Refresh Not Allowed</h1>
                <p> You Can Find Your Generated Return Bills on <a href="/medicy.in/pharmacist/sales-returns.php" class="text-light">Returns</a> Page. Refresh is Not Allowed on This
                    Page.
                </p>
            </div>
        </div>



    <?php
    }
    ?>
    <script>
        // if (window.history.replaceState) {
        //     window.history.replaceState(null, null, window.location.href);
        // }
    </script>
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
    <script>
        const backToMain = () => {
            window.location.href = '../../sales-returns.php';
        }
    </script>
</body>

</html>