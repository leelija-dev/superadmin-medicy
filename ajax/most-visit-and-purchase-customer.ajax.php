<?php

require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'stockOut.class.php';


$Patients   = new Patients;
$StockOut   = new StockOut;


//// ============ customer details fetch area ==================
if (isset($_GET['customerId'])) {
    $customerId =  $_GET['customerId'];
    $customerId = json_decode($customerId);
    $customerNameArray = array();
    for($i = 0; $i<count($customerId); $i++){
        if($customerId[$i] == 'Cash Sales'){
            array_push($customerNameArray, $customerId[$i]);
        }else{
            $customerData = $Patients->patientsDisplayByPId($customerId[$i]);
            $customerData = json_decode($customerData);
            array_push($customerNameArray, $customerData->name);
            // print_r($customerData);
        }
    }
    $customerNameArray = json_encode($customerNameArray);
    echo $customerNameArray;
}


// ==== for most visit customer data fetch ======

if (isset($_GET['mostVstCstmrByDt'])) {
    $dtPicker=  $_GET['mostVstCstmrByDt'];
    $mostVisitedCustomerOnDate = $StockOut->mostVisitedCustomerOnDate($adminId, $dtPicker);
    // print_r($mostVisitedCustomerOnDate);
    echo json_encode($mostVisitedCustomerOnDate);  
    // echo $dtPicker;
}


if (isset($_GET['mostVisitStartDt']) && isset($_GET['mostVisitEndDt'])) {
    $startDate = $_GET['mostVisitStartDt'];
    $endDate = $_GET['mostVisitEndDt'];
    $mostVisitCustomerDateRange = $StockOut->mostVisitCustomersByDateRange($startDate, $endDate, $adminId);
   
    echo json_encode($mostVisitCustomerDateRange); 
}



// === for most purchase customer data fetch ==========

if (isset($_GET['mostPrchsCstmrByDt'])) {
    $dtPicker=  $_GET['mostPrchsCstmrByDt'];
    $mostPurchaseCustomerOnDate = $StockOut->mostPurchaseCustomerByDate($adminId, $dtPicker);
    // print_r($mostVisitedCustomerOnDate);
    echo json_encode($mostPurchaseCustomerOnDate);  
}


if (isset($_GET['mostPurchaseStartDt']) && isset($_GET['mostPurchaseEndDt'])) {
    $startDate = $_GET['mostPurchaseStartDt'];
    $endDate = $_GET['mostPurchaseEndDt'];
    $mostPurchaseCustomerDateRange = $StockOut->mostPurchaseCustomerByDateRange($startDate, $endDate, $adminId);
   
    echo json_encode($mostPurchaseCustomerDateRange); 
}


?>