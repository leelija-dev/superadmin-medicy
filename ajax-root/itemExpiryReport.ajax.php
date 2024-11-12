<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin logged in or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'utility.class.php';


$CurrentStock = new CurrentStock;
$Utility = new Utility;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['dataArray'])) {

    $dataArray = $_GET['dataArray'];
    $dataArray = json_decode($dataArray);

    $searchOn = $dataArray->searchOn;

    $itemEmpiryData = $CurrentStock->itemExpiryDataDetails($dataArray->startDt, $dataArray->endDt, $adminId, $searchOn);

    print_r($itemEmpiryData);
} else {
    echo json_encode(['status' => false, 'message' => 'Invalid request']);
}
?>
