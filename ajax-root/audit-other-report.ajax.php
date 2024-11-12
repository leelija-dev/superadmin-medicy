<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin logged in or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'utility.class.php';


$Appointments = new Appointments;
$LabBilling = new LabBilling;
$Utility = new Utility;


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['dataArray'])) {

    

    $dataArray = $_GET['dataArray'];
    $dataArray = json_decode($dataArray);
    $auditGrouBy = $dataArray->groupBY;
    $startDate = $dataArray->startDate;
    $endDate = $dataArray->endDate;
    $reportOn = $dataArray->reportOn;
    $reportOnArrayData = explode(',',$reportOn);
    $dataFetchFlag = false;

    $appointmentsData = [];
    $labBookingData = [];

    foreach($reportOnArrayData as $reportOnData){
        if($reportOnData == 'Appointments'){
            $opdAuditReport = json_decode($Appointments->opdAuditDataFetch($startDate, $endDate, $auditGrouBy, $ADMINID));
            if($opdAuditReport->status){
                $appointmentsData = $opdAuditReport->data;
                $dataFetchFlag = true;
            }
        }
    
        if($reportOnData == 'Diagnostics'){
            $diagnosticsAuditReport = json_decode($LabBilling->labBillingDAtaFetch($startDate, $endDate, $auditGrouBy, $ADMINID));
            if($diagnosticsAuditReport->status){
                $labBookingData = $diagnosticsAuditReport->data;
                $dataFetchFlag = true;
            }
        }
    }

    if($dataFetchFlag){
        $returnData =  json_encode(['status' => true, 'data1'=>$appointmentsData, 'data2'=>$labBookingData]);
    }else{
        $returnData = json_encode(['status' => false, 'message' => 'No data found']);
    }
    print_r($returnData);
} else {
    $returnData = json_encode(['status' => false, 'message' => 'Invalid request']);
    print_r($returnData);
}
?>
