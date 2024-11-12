<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin logged in or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockReturn.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'salesReturn.class.php';
require_once CLASS_DIR . 'utility.class.php';


$StockIn = new StockIn;
$StockReturn = new StockReturn;
$StockOut = new StockOut;
$SalesReturn = new SalesReturn;
$Utility = new Utility;


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['dataArray'])) {

    $dataArray = $_GET['dataArray'];
    $dataArray = json_decode($dataArray);
    $auditGrouBy = $dataArray->groupBY;
    $startDateFormat1 = $dataArray->startDateFormat1;
    $endDateFormat1 = $dataArray->endDateFormat1;
    $startDateFormat2 = $dataArray->startDateFormat2;
    $endDateFormat2 = $dataArray->endDateFormat2;
    $reportOn = $dataArray->reportOn;
    $reportOnArrayData = explode(',',$reportOn);
    $dataFetchFlag = false;

    $purchaseAuditData = [];
    $stockReturnAuditData =  [];
    $salesAuditData = [];
    $salesReturnAuditData = [];

    foreach($reportOnArrayData as $reportOnData){
        if($reportOnData == 'Purchase'){
            $stockInAuditReport = json_decode($StockIn->stockInAuditFunction($startDateFormat1, $endDateFormat1, $auditGrouBy, $ADMINID));
            if($stockInAuditReport->status){
                $purchaseAuditData = $stockInAuditReport->data;
                $dataFetchFlag = true;
            }
        }
    
        if($reportOnData == 'Purchase Return'){
            $stockReturnReport = json_decode($StockReturn->stockReturnAuditFunction($startDateFormat2, $endDateFormat2, $auditGrouBy, $ADMINID));
            if($stockReturnReport->status){
                $stockReturnAuditData = $stockReturnReport->data;
                $dataFetchFlag = true;
            }
        }

        if($reportOnData == 'Sales'){
            $salesAuditReport = json_decode($StockOut->stockOutAuditFunction($startDateFormat2, $endDateFormat2, $auditGrouBy, $ADMINID));
            if($salesAuditReport->status){
                $salesAuditData = $salesAuditReport->data;
                $dataFetchFlag = true;
            }
        }
    
        if($reportOnData == 'Sales Return'){
            $salesReturnAuditReport = json_decode($SalesReturn->salesReturnAuditFunction($startDateFormat2, $endDateFormat2, $auditGrouBy, $ADMINID));
            if($salesReturnAuditReport->status){
                $salesReturnAuditData = $salesReturnAuditReport->data;
                $dataFetchFlag = true;
            }
        }
    }

    if($dataFetchFlag){
        $returnData =  json_encode(['status' => true, 'stockInData'=>$purchaseAuditData, 'stockReturnData'=>$stockReturnAuditData, 'stockOutData'=>$salesAuditData, 'salesReturnData'=>$salesReturnAuditData]);
    }else{
        $returnData = json_encode(['status' => false, 'message' => 'No data found']);
    }
    print_r($returnData);
} else {
    $returnData = json_encode(['status' => false, 'message' => 'Invalid request']);
    print_r($returnData);
}
?>
