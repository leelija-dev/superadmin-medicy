<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin logged in or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'salesReturn.class.php';
require_once CLASS_DIR . 'utility.class.php';


$StockOut = new StockOut;
$SalesReturn = new SalesReturn;
$Utility = new Utility;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['dataArray'])) {

    

    $dataArray = $_GET['dataArray'];
    $dataArray = json_decode($dataArray);

    $searchFilter = $dataArray->filterBy;
    $startDate = $dataArray->startDt;
    $endDate = $dataArray->endDt;
    $searchOn = $dataArray->searchOn;
    $flag = $dataArray->searchFlag;

    if($searchFilter == 'sales-table'){
        $merginDataByItem = $StockOut->salesMarginDataFetch($startDate, $endDate, $adminId, $flag, $searchOn);
    }

    if($searchFilter == 'sales-return-table'){
        $merginDataByItem = $SalesReturn->salesReturnMarginDataFetch($startDate, $endDate, $adminId, $flag, $searchOn);
    }

    print_r($merginDataByItem);

} else {
    echo json_encode(['status' => false, 'message' => 'Invalid request']);
}
?>
