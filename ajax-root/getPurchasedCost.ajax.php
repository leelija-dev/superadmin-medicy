<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'currentStock.class.php';


$StockInDetails = new StockInDetails();
$CurrentStock = new CurrentStock();


if (isset($_GET["currentItemId"])) {

    $qtyTyp         = $_GET["qtype"];
    $qty            = $_GET["Qty"];
    $currentStockId = $_GET['currentItemId'];

    $currentStockData = $CurrentStock->selectByColAndData('id', $currentStockId);
    
    $stockInData = $StockInDetails->stockInDetailsById($currentStockData[0]['stock_in_details_id']);
    $purchaseCost = floatval($stockInData[0]['base']) + (floatval($stockInData[0]['base'])*($currentStockData[0]['gst']/100));
    
    if ($qtyTyp == "Loose") {
        $eachPurchaseCost = $purchaseCost / $stockInData[0]['weightage'];
        $totalPurchaseCost = $eachPurchaseCost * $qty;
        echo round($totalPurchaseCost,2);

    }elseif($qtyTyp == "Pack"){
        $currentQty = $qty / $stockInData[0]['weightage'];
        $totalPurchaseCost = $purchaseCost * $currentQty;
        echo round($totalPurchaseCost,2);
    }else {
        $totalPurchaseCost = intval($qty)*floatval($purchaseCost);
        echo round($totalPurchaseCost,2);
    }
}



// if (isset($_GET["currentItemId"])) {
//     $qtyTyp         = $_GET["qtype"];
//     $qty            = floatval($_GET["Qty"]);
//     $currentStockId = $_GET['currentItemId'];

//     $currentStockData = $CurrentStock->selectByColAndData('id', $currentStockId);
//     $stockInData = $StockInDetails->stockInDetailsById($currentStockData[0]['stock_in_details_id']);

//     $gstFactor = floatval($currentStockData[0]['gst']) / 100;
//     $purchaseCost = floatval($stockInData[0]['base']) * (1 + $gstFactor);

//     // Calculate total purchase cost based on quantity type
//     switch ($qtyTyp) {
//         case "Loose":
//             $eachPurchaseCost = $purchaseCost / floatval($stockInData[0]['weightage']);
//             $totalPurchaseCost = $eachPurchaseCost * $qty;
//             break;

//         case "Pack":
//             $currentQty = $qty / floatval($stockInData[0]['weightage']);
//             $totalPurchaseCost = $purchaseCost * $currentQty;
//             break;

//         default:
//             $totalPurchaseCost = $qty * $purchaseCost;
//             break;
//     }

//     echo round($totalPurchaseCost, 2);
// }

?>