<?php
require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockIn.class.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'distributor.class.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR.'packagingUnit.class.php';
require_once CLASS_DIR.'stockReturn.class.php';
require_once CLASS_DIR."itemUnit.class.php";

$StockIn = new StockIn();
$StockInDetails = new StockInDetails();
$CurrentStock = new CurrentStock();
$distributor = new Distributor();
$Session = new SessionHandler();
$Products = new Products();
$Manufacturer = new Manufacturer();
$PackagingUnits = new PackagingUnits();
$StcokReturn = new StockReturn();
$ItemUnit       = new ItemUnit;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['stock-in'])) {

        $distributorId        = intval($_POST['distributor-id']);
        $distributorName      = $_POST['distributor-name'];
       
        $distributorDetial = json_decode($distributor->showDistributorById($distributorId));
        $distributorDetial = $distributorDetial->data;
        foreach ($distributorDetial as $distDeta) {
            $distAddress        = $distDeta->address;
            $distPIN            = $distDeta->area_pin_code;
            $distContact        = $distDeta->phno;
        }

        $updtBatchNoArry    = $_POST['batchNo'];
        
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
        $BatchNo            = $_POST['batchNo'];
        $MFDCHECK           = $_POST['mfdDate'];
        $expDate            = $_POST['expDate'];

        $addedBy            = $employeeId;
        $addedOn            = NOW;
        $adminId            = $adminId;
        
        
        $addStockIn = $StockIn->addStockIn($distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $totalGst, $amount, $addedBy, $addedOn, $adminId);
        // print_r($addStockIn);
        // exit;
        if ($addStockIn["result"]) {

            $stokInid = intval($addStockIn['stockIn_id']);

            foreach ($_POST['productId'] as $productId) {
                $batchNo            = array_shift($_POST['batchNo']);
                $mfdDate            = array_shift($_POST['mfdDate']);
                $expDate            = array_shift($_POST['expDate']);

                $weightage          = array_shift($_POST['weightage']);
                $unit               = array_shift($_POST['unit']);
                $pack               = array_shift($_POST['packagingin']);
                $qty                = array_shift($_POST['qty']);
                $freeQty            = array_shift($_POST['freeQty']);
                $looselyCount       = '';
                $mrp                = array_shift($_POST['mrp']);
                $ptr                = array_shift($_POST['ptr']);
                $discount           = array_shift($_POST['discount']);
                $base               = array_shift($_POST['base']);
                $gst                = array_shift($_POST['gst']);
                $gstPerItem         = array_shift($_POST['gstPerItem']);
                $margin             = array_shift($_POST['margin']);
                $amount             = array_shift($_POST['billAmount']);
                $looselyPrice       = '';
                


                $looselyPrice = '';

                if ($unit == "tablets" || $unit == "capsules") {
                    $looselyCount = $weightage * ($qty + $freeQty);
                    $looselyPrice = ($mrp * $qty) / ($weightage * $qty);
                }else{
                    $looselyCount = 0;
                    $looselyPrice = 0;
                }


                $addStockInDetails = $StockInDetails->addStockInDetails($stokInid, $productId, $distributorBill, $batchNo, $mfdDate, $expDate, intval($weightage), $unit, intval($qty), intval($freeQty), intval($looselyCount), floatval($mrp), floatval($ptr), intval($discount), floatval($base), intval($gst), floatval($gstPerItem), floatval($margin), floatval($amount));
                // stockIn_Details_id

                if ($addStockInDetails["result"]) {

                    $stokInDetailsId = $addStockInDetails["stockIn_Details_id"];
 
                    $totalQty = intval($qty) + intval($freeQty);   // buy qantity + free qty

                    // ============ ADD TO CURRENT STOCK ============ 
                    $addCurrentStock = $CurrentStock->addCurrentStock($stokInDetailsId, $productId, $batchNo, $expDate, $distributorId, intval($looselyCount), floatval($looselyPrice), intval($weightage), $unit, intval($totalQty), floatval($mrp), floatval($ptr), intval($gst), $addedBy, $addedOn, $adminId);
                }
            } //eof foreach

        } else {
            $error = $addStockIn["error"];
            echo "Insert failed. Error: " . $error;
        }
    }
} // post request method entered

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
                $subTotal = floatval(00.00);
                $itemIds    = $_POST['productId'];
                $itemBillNo    = $_POST['distributor-bill'];
                $distributorId = $distributorId;
                $itemBatchNo    = $updtBatchNoArry;
                // $stokInId = $stokInid;

                $count = count($itemIds);
                $totalGst = 0;
                $totalMrp = 0;
                $billAmnt = 0;

                for ($i = 0; $i < $count; $i++) {
                    $slno++;

                    $itemDetials = $StockInDetails->stokInDetials($itemIds[$i], $itemBillNo, $itemBatchNo[$i]);
                    // print_r($itemDetials);
                    // echo "<br>";


                    foreach ($itemDetials as $itemsData) {

                        $prodId = $itemsData['product_id'];

                        $productDetails = json_decode($Products->showProductsById($prodId));
                        $productDetails = $productDetails->data;

                        foreach ($productDetails as $pData) {
                            $pname = $pData->name;
                            $pManfId = $pData->manufacturer_id;
                            $pType  = $pData->packaging_type;
                            $pQTY = $pData->unit_quantity;
                            $pUnit = $pData->unit;
                            $pUnitName = $ItemUnit->itemUnitName($pUnit);
                        }

                        $packagingData = $PackagingUnits->showPackagingUnitById($pType);
                        foreach ($packagingData as $packData) {
                            $unitNm = $packData['unit_name'];
                        }


                        $manufDetails = json_decode($Manufacturer->showManufacturerById($pManfId));
                        $manufDetails = $manufDetails->data;

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
                    }

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
                        <small><?php echo $pQTY . $pUnitName, "/ ", $unitNm ?></small>
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
        <button class="btn btn-secondary shadow mx-2" style="background-color: #e7e7e7; color: black;" onclick="goBack('<?php echo $stokInid ?>','<?php echo $itemBillNo ?>')">Go Back</button>
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
        // console.log(id);
        // console.log(value);
        location.href = `<?= URL ?>stock-in-edit.php?edit=${value}&editId=${id}`;
    }
</script>

</html>