<?php

require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'stockOut.class.php';

$Products   = new Products;
$StockOut   = new StockOut;


//// ============ most sold items ==================
if (isset($_GET['mostSoldProdId'])) {
    $productId =  $_GET['mostSoldProdId'];
    $productId = json_decode($productId);
    $prodName = array();
    for($i = 0; $i<count($productId); $i++){
        $proData = $Products->showProductsById($productId[$i]);
        array_push($prodName, $proData[0]['name']);
    }
    $prodName = json_encode($prodName);
    echo $prodName;
}



if (isset($_GET['mostSoldByDt'])) {
    $mostSoldDate = $_GET['mostSoldByDt'];
    $byDateMostStoldItems = $StockOut->mostSoldStockOutDataGroupByDt($mostSoldDate, $adminId);
    echo json_encode($byDateMostStoldItems);
}//mostSoldStockOutDataGroupByDtRng



if (isset($_GET['mostSoldStarDate']) && isset($_GET['mostSoldEndDate'])) {
    $mostSoldStartDate = $_GET['mostSoldStarDate'];
    $mostSoldEndDate = $_GET['mostSoldEndDate'];
    $mostStoldItemsByDateRange = $StockOut->mostSoldStockOutDataGroupByDtRng($mostSoldStartDate, $mostSoldEndDate, $adminId);
    echo json_encode($mostStoldItemsByDateRange);
}



//// ============= less sold items ===================
if (isset($_GET['lessSoldProdId'])) {
    $productId =  $_GET['lessSoldProdId'];
    $productId = json_decode($productId);
    $prodName = array();
    for($i = 0; $i<count($productId); $i++){
        $proData = $Products->showProductsById($productId[$i]);
        array_push($prodName, $proData[0]['name']);
    }
    $prodName = json_encode($prodName);
    echo $prodName;
}



if (isset($_GET['lessSoldChkDt'])) {
    $searchDt = $_GET['lessSoldChkDt'];
    $mostStoldItemsByDate = $StockOut->lessSoldStockOutDataGroupByDt($searchDt, $adminId);
    echo json_encode($mostStoldItemsByDate);
    // echo $daysDiff;
}


if (isset($_GET['lessSoldStartDt']) && isset($_GET['lessSoldEndDt'])) {
    $startDt = $_GET['lessSoldStartDt'];
    $endDt = $_GET['lessSoldEndDt'];
    $dateRangeMostStoldItems = $StockOut->lessSoldStockOutDataGroupByDtRng($startDt, $endDt, $adminId);
    echo json_encode($dateRangeMostStoldItems);
    // echo $daysDiff;
}

?>