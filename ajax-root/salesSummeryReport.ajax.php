<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin logged in or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'utility.class.php';

$StockOut = new StockOut;
$StockInDetails = new StockInDetails;
$Distributor = new Distributor;
$Products = new Products;
$Employees = new Employees;
$Utility = new Utility;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['dataArray'])) {


    $dataArray = $_GET['dataArray'];
    $dataArray = json_decode($dataArray);

    $additionalFilter1 = $dataArray->additionalFilter1;
    $dateGroupFilter = $dataArray->datefilter;

    $searchOnString = $dataArray->searchOn;

    $startDt = $dataArray->startDt;
    $convertedStarDt =new DateTime($startDt);
    $endDt = $dataArray->endDt;
    $convertedEndDt =new DateTime($endDt);
    $filterBy = $dataArray->filterBy;

    
    $searchArray = explode(',', $searchOnString);
    $searchString = '';
    $count = 0;

    for ($i = 0; $i < count($searchArray); $i++) {
        $searchString = $searchString . "'" . $searchArray[$i] . "'";
        $count++;
        if ($count != count($searchArray)) {
            $searchString = $searchString . ", ";
        }
    }

    if ($filterBy == 'ICAT') {
        $stockOutDataReport = $StockOut->stockOutReportOnItemCategory($additionalFilter1, $dateGroupFilter, $searchString, $startDt, $endDt, $adminId);
    }

    if($filterBy == 'PM'){
        $stockOutDataReport = $StockOut->stockOutReportOnPaymentMode($additionalFilter1, $dateGroupFilter, $searchString, $startDt, $endDt, $adminId);
    }

    if($filterBy == 'STF'){
        $stockOutDataReport = $StockOut->stockOutReportOnAddedBy($additionalFilter1, $dateGroupFilter, $searchString, $startDt, $endDt, $adminId);
    }

    print_r($stockOutDataReport);

} else {
    echo json_encode(['status' => false, 'message' => 'Invalid request']);
}
?>
