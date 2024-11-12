<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once dirname(dirname(__DIR__)) . '/config/service.const.php';
require_once ROOT_DIR . '_config/sessionCheck.php';
require_once CLASS_DIR . 'encrypt.inc.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once ROOT_DIR  . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';


//  INSTANTIATING CLASS
$HelthCare       = new HealthCare();
$Doctors         = new Doctors();
$Patients        = new Patients();
$IdsGeneration   = new IdsGeneration();
$StockOut        = new StockOut();
$CurrentStock    = new CurrentStock();
$Manufacturur    = new Manufacturer();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['customer-name'] != '' && $_POST['customer-id'] != '') {

        $customerName   = $_POST['customer-name'];
        $patientId      = $_POST['customer-id'];

        $reffby         = $_POST['doctor-name'];
        $billdate       = $_POST['bill-date'];
        $pMode          = $_POST['payment-mode'];

        $totalItems     = $_POST['total-items'];
        $totalQty       = $_POST['total-qty'];
        $totalGSt       = $_POST['total-gst'];
        $totalMrp       = $_POST['total-mrp'];
        $billAmout      = $_POST['bill-amount'];

        $disc = $totalMrp - $billAmout;

        $status = '1';

        // ================ ARRAYS ======================

        $prductId           = $_POST['product-id'];
        $prodName           = $_POST['product-name'];
        $manufId            = $_POST['ManufId'];
        $manufName          = $_POST['ManufName'];
        $itemId             = $_POST['current-stock-item-id'];
        $batchNo            = $_POST['batch-no'];
        $weightage          = $_POST['weightage'];
        $itemWeightage      = $_POST['ItemWeightage'];
        $ItemUnit           = $_POST['ItemUnit'];
        $expDate            = $_POST['exp-date'];
        $mrp                = $_POST['mrp'];
        $ptr                = $_POST['itemPtr'];
        $qtyTp              = $_POST['qtyTp'];
        $qty                = $_POST['qty'];
        $discountPercent    = $_POST['discPercent'];
        $salesMargin        = $_POST['salesMargin'];
        $marginPerItem      = $_POST['marginAmount'];
        $taxable            = $_POST['taxable'];
        $gstparcent         = $_POST['gst'];
        $gstAmountPerItem   = $_POST['gstVal'];
        $amount             = $_POST['amount'];

        // ===================== STOCK OUT AND SALES ITEM BILL GENERATION AREA =========================
        if (isset($_POST['submit'])) {
            $invoiceId = $IdsGeneration->pharmecyInvoiceId();
            $stockOut = $StockOut->addStockOut($invoiceId, $patientId, $reffby, $totalItems, $totalQty, $totalMrp, $disc, $totalGSt, $billAmout, $pMode, $status, $billdate, $addedBy, NOW, $adminId);

            if ($stockOut['success']) {
                for ($i = 0; $i < count($prductId); $i++) {
                    $ItemUnit = preg_replace("/[^a-z-A-Z]/", '', $weightage[$i]);
                    $ItemWeightage = preg_replace("/[^0-9]/", '', $weightage[$i]);

                    if (in_array(strtolower($ItemUnit), LOOSEUNITS)) {

                        $itemSellQty = $qty[$i];

                        $looseCount = intval($qty[$i]);
                        // $wholeCount = intdiv($looseCount, intval($ItemWeightage));
                        $wholeCount = round(($looseCount /$ItemWeightage), 1);
                    } else {
                        $itemSellQty = $qty[$i];

                        $looseCount = 0;
                        $wholeCount = $qty[$i];
                    }
                    echo $wholeCount;

                    $colName = 'id';
                    $productDetails = $CurrentStock->selectByColAndData($colName, intval($itemId[$i]));
                    // print_r($productDetails);

                    foreach ($productDetails as $itemData) {
                        $productID      = $itemData['product_id'];
                        $BatchNo        = $itemData['batch_no'];
                        $ExpairyDate    = $itemData['exp_date'];
                        $Weightage      = $itemData['weightage'];
                        $Unit           = $itemData['unit'];
                        $MRP            = $itemData['mrp'];
                        $PTR            = $itemData['ptr'];
                        $GST            = $itemData['gst'];
                        $LooselyPrice   = $itemData['loosely_price'];
                        $itemQty        = $itemData['qty'];
                        $itemLooseQty   = $itemData['loosely_count'];
                    }


                    $stockOutDetails = $StockOut->addStockOutDetails(intval($invoiceId), intval($itemId[$i]), $prductId[$i], $prodName[$i], $batchNo[$i], $expDate[$i], $ItemWeightage, $ItemUnit, $wholeCount, $looseCount, floatval($mrp[$i]), floatval($ptr[$i]), $discountPercent[$i], $gstparcent[$i], floatval($gstAmountPerItem[$i]), floatval($salesMargin[$i]), floatval($marginPerItem[$i]), floatval($taxable[$i]), floatval($amount[$i]));

                    if ($stockOutDetails) {

                        // =========== AFTER SELL CURREN STOCK CALCULATION AND UPDATE AREA ============= 

                        if (in_array(strtolower($ItemUnit), LOOSEUNITS)) {
                            $updatedLooseCount     = intval($itemLooseQty) - intval($itemSellQty);
                            echo $UpdatedNewQuantity   = $updatedLooseCount / $ItemWeightage;
                        } else {
                            $UpdatedNewQuantity = intval($itemQty) - intval($itemSellQty);
                            $updatedLooseCount = 0;
                        }

                        $updateCurrentStock = $CurrentStock->updateStockOnSell($itemId[$i], $UpdatedNewQuantity, $updatedLooseCount);
                        $redirectUrl = URL . "invoices/print.php?name=sales&id=" . url_enc($invoiceId);
                        // $redirectUrl = URL . "sales.php?&search=" . $invoiceId;
                        header("Location: " .$redirectUrl );
                    }
                }
            }
        }else {
            $errMsg = "Item Details Not Found!";
        }
    }else {
        $errMsg = "Patient Name and ID Not Found!";
    }
}else {
    $errMsg = "Request Not Found!";
}

if (isset($errMsg)) {
    echo $errMsg;
}