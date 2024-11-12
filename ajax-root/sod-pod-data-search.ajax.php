<?php 
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockOut.class.php';
require_once CLASS_DIR.'stockIn.class.php';

$StockOut = new StockOut;
$StockIn = new StockIn;


// === sod fixd date data fetch =======
if(isset($_GET['sodONDate'])){
    $onDate = $_GET['sodONDate'];
    
    $SodOnDateData = $StockOut->salesOfTheDayRange($onDate, $onDate, $adminId);
    echo json_encode($SodOnDateData);
}


// === sod(sales of the day) fixd date data fetch =======
if(isset($_GET['sodStartDate']) && isset($_GET['sodEndDate'])){
    $strtDt = $_GET['sodStartDate'];
    $endDt = $_GET['sodEndDate'];
    
    $sodOnDateRangeData = $StockOut->salesOfTheDayRange($endDt, $strtDt, $adminId);
    echo json_encode($sodOnDateRangeData);
}



// === pod fixd date data fetch =======
if(isset($_GET['podONDate'])){
    $podOnDate = $_GET['podONDate'];
    
    $podOnDateData = $StockIn->purchaseTodayByDateRange($podOnDate, $podOnDate, $adminId);
    echo json_encode($podOnDateData);
}


// === sod(sales of the day) fixd date data fetch =======
if(isset($_GET['podStartDate']) && isset($_GET['podEndDate'])){
    $podStartDt = $_GET['podStartDate'];
    $podEndDt = $_GET['podEndDate'];
    
    $sodOnDateRangeData = $StockIn->purchaseTodayByDateRange($podStartDt, $podEndDt, $adminId);
    echo json_encode($sodOnDateRangeData);

}
?>