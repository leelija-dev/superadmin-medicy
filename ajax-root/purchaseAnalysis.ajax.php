<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin logged in or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'utility.class.php';


$StockInDetails = new StockInDetails;
$Utility = new Utility;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['dataArray'])) {

    $dataArray = $_GET['dataArray'];
    $dataArray = json_decode($dataArray);

    $searchData = $dataArray->searchOn;
    $searchingDate = $dataArray->searchDate;

    $purchaseAnalysisData = $StockInDetails->purchaseAnalysisReport($searchingDate, $adminId, $searchData);

    print_r($purchaseAnalysisData);

} else {
    echo json_encode(['status' => false, 'message' => 'Invalid request']);
}
?>
