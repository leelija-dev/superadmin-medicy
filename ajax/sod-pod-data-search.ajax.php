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
// if(isset($_GET['sodONDate'])){
//     $onDate = $_GET['sodONDate'];
//     echo $onDate;
//     $CustomerId = $_GET['customerId'];
//     echo $CustomerId;
//     $SodOnDateData = $StockOut->customerOnDay($onDate, $CustomerId);
//     echo json_encode($SodOnDateData);
// }


// === sod(sales of the day) fixd date data fetch =======
// if(isset($_GET['sodStartDate']) && isset($_GET['sodEndDate']) && isset($_GET['customerId'])){
//     $strtDt      = $_GET['sodStartDate'];
//     // echo $strtDt;
//     $endDt       = $_GET['sodEndDate'];
//     // echo $endDt;
//     $CustomerId  = $_GET['customerId'];
//     // echo $CustomerId;
    
//     $sodOnDateRangeData = $StockOut->customerDayRange( $strtDt, $endDt, $CustomerId);
//     echo json_encode($sodOnDateRangeData);
// }



// === pod fixd date data fetch =======
if(isset($_GET['podONDate'])){
    $podOnDate = $_GET['podONDate'];
    
    $podOnDateData = $StockIn->purchaseTodayByDateRange($podOnDate, $podOnDate, $supAdminId);
    echo json_encode($podOnDateData);
}


// === sod(sales of the day) fixd date data fetch =======
if(isset($_GET['podStartDate']) && isset($_GET['podEndDate'])){
    $podStartDt = $_GET['podStartDate'];
    $podEndDt = $_GET['podEndDate'];
    
    $sodOnDateRangeData = $StockIn->purchaseTodayByDateRange($podStartDt, $podEndDt, $supAdminId);
    echo json_encode($sodOnDateRangeData);

}
// ===customer purches item=== //
if(isset($_GET['podONDate'])){
    $podOnDate = $_GET['podONDate'];
    
    $podOnDateData = $StockIn->customerPurchDayRange($podOnDate, $podOnDate, $CustomerId);
    echo json_encode($podOnDateData);
}

?>