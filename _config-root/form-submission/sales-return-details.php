<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'salesReturn.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'patients.class.php';

require_once CLASS_DIR . 'encrypt.inc.php';

// $invoiceId = $_GET['id'];
// echo $invoiceId;

//  INSTANTIATING CLASS
$SalesReturn     = new SalesReturn;
$StockOut        = new StockOut;
$Products        = new Products();
$ItemUnit        = new ItemUnit();
$PackagingUnits  = new PackagingUnits();
$Manufacturer    = new Manufacturer();
$Patients        = new Patients;
$ClinicInfo  = new HealthCare;
// echo $healthCareLogo;


if (isset($_GET['id'])) {

    $returnId = url_dec($_GET['id']);
    // echo $returnId."<br>";
    $salesReturnData  = $SalesReturn->selectSalesReturn('id', $returnId);
    // print_r($salesReturnData);
    // echo "<br>";

    foreach ($salesReturnData as $salesReturnData) {
        $invoiceId      = $salesReturnData['invoice_id'];
        // echo $invoiceId;
        $customerId     = $salesReturnData['patient_id'];
        // $reffby         = $salesReturnData['reff_by'];
        $refundAmount       = $salesReturnData['refund_amount'];
        $totalGSt       = $salesReturnData['gst_amount'];
        // $billAmout      = $salesReturnData['amount'];
        $refundMode          = $salesReturnData['refund_mode'];
        $billdate       = $salesReturnData['bill_date'];
        $returnDate     = $salesReturnData['return_date'];


        $salesReturnDetails = $SalesReturn->selectSalesReturnList('sales_return_id ', $returnId);
        // print_r($salesReturnDetails);
    }
}

if ($customerId != 'Cash Sales') {
    $patient = json_decode($Patients->patientsDisplayByPId($customerId));

    $patientName = $patient->name;
    $patientPhno = $patient->phno;
    $patientAge  = $patient->age;

    // $patientElement = "<p style='margin-top: -3px; margin-bottom: 0px;'><small><b>Patient: </b>  $patientName, <b>Age:</b> $patientAge </small></p><p style='margin-top: -5px; margin-bottom: 0px;'><small><b>M:</b> $patientPhno </small></p>"";
} else {
    // $patientElement = "<p style='margin-top: -3px; margin-bottom: 0px;'><small><b>Patient: </b>  $customerId</small></p>"";
    $patientName = 'Cash Sales';
    $patientPhno = '';
    $patientAge = '';
}



$selectClinicInfo = json_decode($ClinicInfo->showHealthCare($adminId));
// print_r($selectClinicInfo->data);
$pharmacyLogo = $selectClinicInfo->data->logo;
$pharmacyName = $selectClinicInfo->data->hospital_name;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Lab Test Bill</title>
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/sell-return-bill.css">

</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?php if ($refundMode != 'Credit') {
                                    echo "paid-bg";
                                } ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <!-- <img class="float-end" style="height: 55px; width: 58px;" src="<?= LOCAL_DIR . $pharmacyLogo ?>" -->
                        <img class="float-end" style="height: 55px; width: 58px;" src="<?= $healthCareLogo ?>"
                            alt="Medicy">
                    </div>
                    <div class="col-sm-8">
                        <h4 class="text-start my-0"><?php echo $healthCareName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareAddress1 . ', ' . $healthCareAddress2 . ', ' . $healthCareCity . ', ' . $healthCarePin; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -6px; margin-bottom: 0px;">
                            <small><?php echo 'M: ' . $healthCarePhno . ', ' . $healthCareApntbkNo; ?></small>
                        </p>
                        <p class="m-0" style="font-size: 0.850em;"><small><b>GST ID :</b></small><?php echo $gstinData?>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-dark">
                        <p class="my-0"><b>Invoice</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id:
                                #<?php echo $invoiceId; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Payment:
                                <?php echo $refundMode; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Date: <?php echo $billdate; ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <!-- <hr class="my-0" style="height:0px; background: #000000; border: #000000;"> -->
            <!-- <div class="row my-0">
                <div class="col-sm-6 ms-4 my-0">
                    <p class="text-start" style="margin-top: -3px; margin-bottom: 0px;"><small><b>Refered By:</b>
                            <?php echo $reffby; ?></small></p>
                </div>
            </div> -->
            <hr class="my-0" style="height:1px;opacity:1;">

            <table class="table">
                <thead>
                    <tr>
                        <th class="pt-1 pb-1" scope="col"><small>SL.</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Name</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Manuf.</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Batch</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Exp.</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Unit</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Buy Qty</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Ret.Qty</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Rate</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Disc(%)</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>GST(%)</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Refund</small></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                $slno = 0;
                $totalMrp = 0;
                $subTotal = floatval(00.00);
                foreach ($salesReturnDetails as $index => $detail) {
                    // print_r($detail);
                    //=========================
                    $checkTable = json_decode($Products->productExistanceCheck($detail['product_id']));

                    if ($checkTable->status == 1) {
                        $table = 'products';
                    } else {
                        $table = 'product_request';
                    }
                    //=========================

                    $productResponse = json_decode($Products->showProductsByIdOnTableName($detail['product_id'], $table));

                    $product = $productResponse->data;
                    // print_r($product);

                    $packQty = $product->unit_quantity;

                    if (isset($product->manufacturer_id)) {
                        $manuf = json_decode($Manufacturer->manufacturerShortName($product->manufacturer_id));

                        $manufacturerName = $manuf->status == 1 ? $manuf->data : '';
                    } else {
                        $manufacturerName = '';
                    }


                    $itemunit = $ItemUnit->itemUnitName($product->unit);
                    $packUnit = $PackagingUnits->packagingTypeName($product->packaging_type);

                    $weatage = "$itemunit of $packUnit";

                    $slno++;
                    // if ($slno > 1) {
                    //     echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                    // }

                    $col1 = 'invoice_id';
                    $col2 = 'item_id';
                    $stockOutData = $StockOut->stokOutDetailsDataByTwoCol($col1, $detail['invoice_id'], $col2, $detail['item_id']);
                    // print_r($stockOutData);

                    if($stockOutData[0]['loosely_count'] != 0){
                        $purchasedQty = $stockOutData[0]['loosely_count'];
                    }else{
                        $purchasedQty = $stockOutData[0]['qty'];
                    }

                    // ================== TOTAL MRP CALCULATION AREA =======================
                    $totalMrp = floatval($totalMrp) + floatval($detail['mrp']);

                    $isLastRow = $index === count($salesReturnDetails) - 1;
                    // Add border style only if it's not the last row
                    $borderStyle = $isLastRow ? 'border-bottom: transparent;' : 'border-bottom: #dfdfdf;';

                   echo ' <tr style="'.$borderStyle.'">
                        <th scope="row" class="pt-1 pb-1"><small>' . $slno . '</small> </th>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $product->name . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $manufacturerName . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['batch_no'] . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['exp'] . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['weatage'] . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $purchasedQty . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['return_qty'] . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['ptr'] . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['disc'] . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['gst'] . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['refund_amount'] . '</small></td>
                    </tr>';
                }
                ?>
                </tbody>
            </table>


            <div class="footer">
                <hr calss="my-0" style="height: 1px; margin-bottom:0;opacity:1;">
                <!-- table total calculation -->

                <div class="row my-0">
                    <div class="col-5">
                        <div class="row m-2">
                            <div class="col-2 ms-4">
                                <b><small>Patient </small></b><br>
                                <!-- <b><small>Age</small></b><br> -->
                                <b><small>Contact</small></b>
                            </div>
                            <div class="col-9">
                                <p class="text-start mb-0"><small><?= ' :   ' . $patientName; ?></small></p>
                                <!-- <p class="text-start mb-0"><small><?= ' :   ' . $patientAge; ?></small></p> -->
                                <p class="text-start"><small><?= ' :   ' . $patientPhno; ?></small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-7 border-start border-dark">
                        <div class="row mt-3">
                            <div class="col-sm-10">
                                <p class="text-end mb-0"><b>Total Refund :</b></p>
                            </div>
                            <div class="col-sm-2">
                                <p class="mb-0 me-3"><b><?= $refundAmount; ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr style="height: 1px; margin-top:0;opacity: 1;">
            </div>
        </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button>
        <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
    </div>
    </div>
</body>
<script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>

</html>