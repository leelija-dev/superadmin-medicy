<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin logged in or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'salesReturn.class.php';
require_once CLASS_DIR . 'utility.class.php';


$StockIn = new StockIn;
$StockInDetails = new StockInDetails;
$StockOut = new StockOut;
$SalesReturn = new SalesReturn;
$Utility = new Utility;


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['dataArray'])) {

    

    $dataArray = $_GET['dataArray'];
    $dataArray = json_decode($dataArray);

    $searchFilter = $dataArray->reportOn;
    $startDate = $dataArray->startDate;
    $endDate = $dataArray->endDate;
    $filterOn = 2;

    if($searchFilter == 'P'){
        $creditTransactionReport = $StockIn->purchaseOnPaymentMode($filterOn, $startDate, $endDate, $adminId);
    }

    if($searchFilter == 'S'){
        $creditTransactionReport = $StockOut->salesOnPaymentMode($filterOn, $startDate, $endDate, $adminId);
    }

    print_r($creditTransactionReport);

} else {
    echo json_encode(['status' => false, 'message' => 'Invalid request']);
}
?>
