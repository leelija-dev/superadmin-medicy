<?php 
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');
require_once SUP_ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockOut.class.php';
require_once CLASS_DIR.'stockIn.class.php';
// $CustomerId = isset($_GET['customerId']) ? $_GET['customerId'] : null;
// echo $CustomerId;

$StockOut = new StockOut;
$StockIn  = new StockIn;


// === sod fixd date data fetch =======
if(isset($_GET['sodONDate']) && isset($_GET['customerId'])){
    $onDate = $_GET['sodONDate'];
    // echo $onDate;
    $CustomerId = $_GET['customerId'];
    // echo $CustomerId;
    $SodOnDateData = $StockOut->customerDayRange($onDate,$onDate, $CustomerId);
    echo json_encode($SodOnDateData);
}


// === sod(sales of the day) fixd date data fetch =======
if(isset($_GET['sodStartDate']) && isset($_GET['sodEndDate']) && isset($_GET['customerId'])){
    $strtDt      = $_GET['sodStartDate'];
    // echo $strtDt;
    $endDt       = $_GET['sodEndDate'];
    // echo $endDt;
    $CustomerId  = $_GET['customerId'];
    // echo $CustomerId;
    
    $sodOnDateRangeData = $StockOut->customerDayRange( $strtDt, $endDt, $CustomerId);
    echo json_encode($sodOnDateRangeData);
}



// === pod fixd date data fetch =======
if(isset($_GET['podONDate']) && isset($_GET['customerId'])){
    $podOnDate   = $_GET['podONDate'];
    $CustomerId  = $_GET['customerId'];

    $podOnDateData = $StockIn->customerPurchDayRange($podOnDate, $podOnDate, $CustomerId);
    echo json_encode($podOnDateData);
}


// === sod(sales of the day) fixd date data fetch =======//
if(isset($_GET['podStartDate']) && isset($_GET['podEndDate']) && isset($_GET['customerId'])){
    $podStartDt  = $_GET['podStartDate'];
    $podEndDt    = $_GET['podEndDate'];
    $CustomerId  = $_GET['customerId'];
    
    $sodOnDateRangeData = $StockIn->customerPurchDayRange($podStartDt, $podEndDt, $CustomerId);
    echo json_encode($sodOnDateRangeData);
}

?>