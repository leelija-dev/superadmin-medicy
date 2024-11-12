<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR  . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'salesReturn.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';

// require_once '../../../php_control/idsgeneration.class.php';

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
    if (isset($_POST['sales-return'])) {

        // ---------- NON ARRAY ELEMENTS -----------
        $invoice        = $_POST['invoice'];
        $invoiceId = str_replace("#", '', $invoice);
        
        $patientData = $StockOut->stockOutDisplayById($invoice);
        // print_r($patientData);
        if ($patientData[0]['customer_id'] == 'Cash Sales') {
            $patientId = 'Cash Sales';
            $patientName = 'Cash Sales';
            $contactNumber = "";
        } else {
            $patient = $Patients->patientsDisplayByPId($patientData[0]['customer_id']);
            $patient = json_decode($patient);
            $patientId = $patientData[0]['customer_id'];
            $patientName = $patient->name;
            $contactNumber = $patient->phno;
        }

        $billDate       = $_POST['purchased-date'];
        $billDate       = date('Y-m-d', strtotime($billDate));
        $returnDate     = $_POST['return-date'];
        $items          = $_POST['total-items'];
        $totalQtys      = $_POST['total-qty'];
        $gstAmount      = $_POST['gst-amount'];
        $refundAmount   = $_POST['refund-amount'];
        $refundMode     = $_POST['refund-mode'];
        $status = "1";
        $addedBy = $employeeId;
        $addedOn = NOW;
        $adminId;

        // echo "<br> Invoice No / id : $invoiceId & Data type : "; echo gettype($invoiceId);
        // echo "<br> Patient Id : $patientId & Data type : "; echo gettype($patientId); 
        // echo "<br> Bill Date : $billDate & Data type : "; echo gettype($billDate); 
        // echo "<br> Return Date : $returnDate & Data type : "; echo gettype($returnDate); 
        // echo "<br> Items : $items & Data type : "; echo gettype($items); 
        // echo "<br> Total Items qty : $totalQtys & Data type : "; echo gettype($totalQtys); 
        // echo "<br> Total Gst Amount : $gstAmount & Data type : "; echo gettype($gstAmount);
        // echo "<br> Refund amount : $refundAmount & Data type : "; echo gettype($refundAmount);
        // echo "<br> Refund Mode : $refundMode & Data type : "; echo gettype($refundMode);
        // echo "<br> Status : $status & Data type : "; echo gettype($status);
        // echo "<br> Addeb by : $addedBy & Data type : "; echo gettype($addedBy);
        // echo "<br> added on : $addedOn & Data type : "; echo gettype($addedOn);
        // echo "<br> admin id : $adminId & Data type : "; echo gettype($adminId);


        //-------Array elements------------------
        $itemID   = $_POST['itemId'];
        $procutId = $_POST['productId'];
        $batchNo    = $_POST['batchNo'];
        // print_r($batchNo);
        $setOf    = $_POST['setof'];
        $expdates   = $_POST['expDate'];
        $mrp        = $_POST['mrp'];
        $ptr        = $_POST['ptr'];

        $qtys       = $_POST['qty'];

        $disc      = $_POST['disc'];
        $gst        = $_POST['gst'];

        $taxableArray   = $_POST['taxable'];
        $returnQty  = $_POST['return'];
        $perItemRefund    = $_POST['refundPerItem'];

        // --------------------------------------
        $itemWeatage = preg_replace('/[a-z]/', '', $setOf);
        $unitType = preg_replace('/[0-9]/', '', $setOf);


        // --------------------------------------
        // echo "<br><br>";
        // echo "<br> Item id : "; print_r($itemID);
        // echo "<br> Product Id : "; print_r($procutId);
        // echo "<br> Batch no : "; print_r($batchNo);
        // echo "<br> expiry Date : "; print_r($expdates);
        // echo "<br> Stef of : "; print_r($setOf);
        // echo "<br> Unit Type : "; print_r($unitType);
        // echo "<br> Item weatage : "; print_r($itemWeatage);
        // echo "<br> Qantity : "; print_r($qtys);
        // echo "<br> MRP : "; print_r($mrp);
        // echo "<br> PTR : "; print_r($ptr);
        // echo "<br> Discount : "; print_r($disc);
        // echo "<br> GST : "; print_r($gst);
        // echo "<br> Taxable array : "; print_r($taxableArray);
        // echo "<br> Return QTY : "; print_r($returnQty);
        // echo "<br> Refund Amount : "; print_r($perItemRefund);


        $returned = $SalesReturn->addSalesReturn(intval($invoiceId), $patientData[0]['customer_id'], $billDate, $returnDate, intval($items), intval($totalQtys), intval($gstAmount), intval($refundAmount), $refundMode, $status, $addedBy, $addedOn, $adminId);

        if ($returned['result']) {
            // echo "<br>empty add new return edit";
            for ($i = 0; $i < count($itemID); $i++) {


                $unit = $setOf[$i];

                $itemWeatage = preg_replace('/[a-z]/', '', $unit);
                $unitType = preg_replace('/[0-9]/', '', $unit);


                $gstAmount   = floatval($perItemRefund[$i]) - floatval($taxableArray[$i]);
                // ========================= ADD TO SALES RETURN DETAILS =============================
                $addSalesReturndDetails = $SalesReturn->addReturnDetails($invoiceId, $returned['sales_return_id'], $itemID[$i], $procutId[$i], $batchNo[$i], $setOf[$i], $expdates[$i], $mrp[$i], $ptr[$i], $disc[$i], $gst[$i], $gstAmount, $taxableArray[$i], $returnQty[$i], $perItemRefund[$i], $adminId);

                // ============= CURRENT STOCK UPDATE AREA ===========================
                $currentStockDetaisl = $CurrentStock->showCurrentStocById($itemID[$i]);

                foreach ($currentStockDetaisl as $currentStockDetaisl) {
                    $currentStockItemUnit = $currentStockDetaisl['unit'];
                    if ($currentStockItemUnit == 'tablets' || $currentStockItemUnit == 'capsules') {
                        $curretnStockQty = $currentStockDetaisl['loosely_count'];
                        $UpdatedLooseQty = intval($curretnStockQty) + intval(array_shift($_POST['return']));
                        $UpdatedQty = intdiv(intval($UpdatedLooseQty), intval($itemWeatage));
                    } else {
                        $curretnStockQty = $currentStockDetaisl['qty'];
                        $UpdatedQty = intval($curretnStockQty) + intval(array_shift($_POST['return']));
                        $UpdatedLooseQty = 0;
                    }
                }

                // echo "<br>Current Stock item quantity : $curretnStockQty";
                // echo "<br>CURRENT STOCK UPDATED LOOSE QTY : $UpdatedLooseQty";
                // echo "<br>CURRENT STOCK UPDATED QTY : $UpdatedQty";

                // ========================= CURRENT STOCK UPDATE STRING ============================
                // echo $itemID[$i];
                $updateCurrentStock = $CurrentStock->updateStockOnSell($itemID[$i], $UpdatedQty, $UpdatedLooseQty);
            }
        }
    }

    // $healthCareDetailsByAdminId = $HelthCare->showhealthCare($adminId);
    
    // $healthCareDetails = $healthCareDetailsByAdminId;
    
    // $healthCareName     = $healthCareDetails['hospital_name'];
    // $healthCareAddress1 = $healthCareDetails['address_1'];
    // $healthCareAddress2 = $healthCareDetails['address_2'];
    // $healthCareCity     = $healthCareDetails['city'];
    // $healthCarePIN      = $healthCareDetails['pin'];
    // $healthCarePhno     = $healthCareDetails['hospital_phno'];
    // $healthCareApntbkNo = $healthCareDetails['appointment_help_line'];

}
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
    <?php if (isset($_POST['sales-return'])) { ?>
        <div class="custom-container">
            <div class="custom-body">
                <div class="card-body border-bottom border-dark">
                    <div class="row">
                        <div class="col-sm-1">
                            <img class="float-end" style="height: 55px; width: 58px;" src="<?= IMG_PATH ?>logo-p.jpg" alt="Medicy">
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
                            <small><b>Patient: </b> <?php echo $patientName; ?>, Contact:
                                <?php echo $contactNumber; ?>
                            </small>
                        </p>

                    </div>
                    <div class="col-sm-6 my-0">
                        <p class="text-end" style="margin-top: -3px; margin-bottom: 0px;"><small><b>Refered By:</b>
                                <?php echo $patientData[0]['reff_by']; ?></small></p>
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
                        <small><b>Packing</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>Batch</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>Exp.</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>Disc(%)</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>GST(%)</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>P.QTY</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>Return</b></small>
                    </div>
                    <div class="col-sm-1">
                        <small><b>Taxable</b></small>
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
                //$countProduct = count($products);
                // print_r($products);
                // print_r($batchNo);
                // print_r($invoiceId);

                // foreach ($itemID as $itemID) {
                for ($i = 0; $i < count($itemID); $i++) {

                    $slno++;

                    $itemDetails = $CurrentStock->showCurrentStocById($itemID[$i]);
                    $productDetails = $Products->showProductsById($itemDetails[0]['product_id']);
                    $productName = $productDetails[0]['name'];

                    echo '
                                <div class="row">
                                    <div class="col-sm-1 text-center">
                                        <small>' . $slno . '</small>
                                    </div>
                                    <div class="col-sm-2 ">
                                        <small>' . substr($productName, 0, 15) . '</small>
                                    </div>
                    
                                    <div class="col-sm-1">
                                        <small>' . $setOf[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' .  $batchNo[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $expdates[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $disc[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $gst[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $qtys[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1">
                                        <small>' . $returnQty[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1" style="text-align: right;">
                                        <small>' . $taxableArray[$i] . '</small>
                                    </div>
                                    <div class="col-sm-1" style="text-align: right;">
                                        <small>' . $perItemRefund[$i] . '</small>
                                    </div>
                                </div>';
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
                                        <small><b>₹<?php echo floatval($refundAmount); ?></b></small>
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
            <button class="btn btn-primary shadow mx-2" onclick="goBack()">Go Back</button>
            <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
        </div>
        </div>
    <?php
    } else {
    ?>

        <div class="container mt-3">
            <!-- <h2></h2> -->
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
        const goBack = () => {
            window.location.href = '../../sales-returns.php';
        }
    </script>
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
</body>

</html>