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
require_once CLASS_DIR . 'stockReturn.class.php';
require_once CLASS_DIR . "itemUnit.class.php";
require_once CLASS_DIR . 'hospital.class.php';


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
$ClinicInfo  = new HealthCare;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['stock-in'])) {

        $distributorId        = intval($_POST['distributor-id']);
        $distributorName      = $_POST['distributor-name'];

        $distributorDetial = json_decode($distributor->showDistributorById($distributorId));
        $distributorDetial = $distributorDetial->data;
        // print_r($distributorDetial);
        // foreach ($distributorDetial as $distDeta) {
        $distAddress        = $distributorDetial->address;
        $distPIN            = $distributorDetial->area_pin_code;
        $distContact        = $distributorDetial->phno;
        // }

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
        echo $totalGst;
        $amount             = $_POST['netAmount'];
        $BatchNo            = $_POST['batchNo'];
        // $MFDCHECK           = $_POST['mfdDate'];
        $expDate            = $_POST['expDate'];

        $editReqFlag        = $_POST['edit-req-flag'];
        // print_r($editReqFlag);

        $addStockIn = $StockIn->addStockIn($distributorId, $distributorBill, $items, $totalQty, $billDate, $dueDate, $paymentMode, $totalGst, $amount, $employeeId, NOW, $adminId);
        // print_r($addStockIn);
        // exit;
        if ($addStockIn["result"]) {

            $stokInid = intval($addStockIn['stockIn_id']);

            foreach ($_POST['productId'] as $productId) {
                $batchNo            = array_shift($_POST['batchNo']);
                // $mfdDate            = array_shift($_POST['mfdDate']);
                $expDate            = array_shift($_POST['expDate']);

                $weightage          = array_shift($_POST['weightage']);
                $unit               = array_shift($_POST['unit']);
                // $pack               = array_shift($_POST['packagingin']);
                $qty                = array_shift($_POST['qty']);
                $freeQty            = array_shift($_POST['freeQty']);
                $looselyCount       = '';
                $mrp                = array_shift($_POST['mrp']);
                $ptr                = array_shift($_POST['ptr']);
                $discount           = array_shift($_POST['discount']);
                $dprice               = array_shift($_POST['dprice']);
                $gst                = array_shift($_POST['gst']);
                $gstPerItem         = array_shift($_POST['gstPerItem']);
                $margin             = '';
                $amount             = array_shift($_POST['billAmount']);
                $looselyPrice       = '';

                $base = ($dprice * $qty) / ($qty + $freeQty);

                if (in_array(strtolower(trim($unit)), LOOSEUNITS)) {
                    $looselyCount = $weightage * ($qty + $freeQty);
                    $looselyPrice = ($mrp * $qty) / ($weightage * $qty);
                } else {
                    $looselyCount = $looselyPrice = 0;
                }

                $addStockInDetails = $StockInDetails->addStockInDetails($stokInid, $productId, $distributorBill, $batchNo, $expDate, intval($weightage), trim($unit), intval($qty), intval($freeQty), intval($looselyCount), floatval($mrp), floatval($ptr), intval($discount), floatval($dprice), intval($gst), floatval($gstPerItem), floatval($base), floatval($amount), $employeeId, NOW);

                // stockIn_Details_id

                if ($addStockInDetails["result"]) {

                    $stokInDetailsId = $addStockInDetails["stockIn_Details_id"];

                    $totalQty = intval($qty) + intval($freeQty);   // buy qantity + free qty

                    // ============ ADD TO CURRENT STOCK ============ 
                    $addCurrentStock = $CurrentStock->addCurrentStock($stokInDetailsId, $productId, $batchNo, $expDate, $distributorId, intval($looselyCount), floatval($looselyPrice), intval($weightage), trim($unit), intval($totalQty), floatval($mrp), floatval($ptr), intval($gst), $employeeId, NOW, $adminId);
                }
            } //eof foreach


            // $preparedData = url_enc(json_encode(['stockIn_Id' => $addStockIn['stockIn_id']]));
            // header('Location: purchase-invoice.php?data='.$preparedData);
            // $redirectUrl = URL."invoices/purchase-invoice.php?data='.$preparedData";
            $redirectUrl  = URL . "purchase-details.php?search=" . $distributorBill;
            header('Location: ' . $redirectUrl);
            exit;
        } else {
            $error = $addStockIn["error"];
            echo "Insert failed. Error: " . $error;
        }
    }
} // post request method entered
