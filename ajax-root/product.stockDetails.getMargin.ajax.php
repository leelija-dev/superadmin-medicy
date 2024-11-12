<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'currentStock.class.php';

$StockInDetails = new StockInDetails();
$CurrentStock = new CurrentStock();



// old calculation
// if (isset($_GET["Pid"])) {

//     $productId      = $_GET["Pid"];
//     $batchNo        = $_GET["Bid"];
//     $qtyTyp         = $_GET["qtype"];
    
//     $mrp            = $_GET["Mrp"];
//     $qty            = $_GET["Qty"];
//     $discPercent    = $_GET["disc"];

//     $taxableAmount  = $_GET["taxable"];
//     $sellAmount     = $_GET['sellAmount'];

//     $currentStockId = $_GET['currentItemId'];


//     $col = 'id';
//     $currentStockData = $CurrentStock->selectByColAndData($col, $currentStockId);

//     $stockInData = $StockInDetails->stockInDetailsById($currentStockData[0]['stock_in_details_id']);

//     if($mrp == null || $qty == null || $discPercent == null){
//         $mrp = 0;
//         $qty = 0;
//         $disc = 0;
//     }

//     $mrp = floatval($mrp);
//     $qty = intval($qty);
//     $discPercent = floatval($discPercent);

//     $discPrice = $mrp - ($mrp * $discPercent/100);

//     // Common assignments
//     $stockInQty = ($qtyTyp == 'others') ? intval($stockInData[0]['qty']) + intval($stockInData[0]['free_qty'])  : $stockInData[0]['loosely_count'];
//     $stockInAmount = $stockInData[0]['amount'];
//     $ptrPerItem = $stockInData[0]['ptr'];
//     $perItemBasePrice = $stockInData[0]['d_price'];
//     $purchasedGstPaid = $stockInData[0]['gst_amount'];
//     $perQtyStockInAmount = floatval($stockInAmount) / $stockInQty;

//     // Sell GST calculation
//     $sellGstAmount = floatval($taxableAmount) * (floatval($stockInData[0]['gst']) / 100);

//     // Purchased amount on sell qty
//     $pAmntOnSellQty = ($stockInAmount / $stockInQty) * intval($qty);

//     // Paid purchased GST amount per item
//     $paidPurchasedGstAmountPerItem = $purchasedGstPaid / $stockInQty;

//     // Margin calculation
//     $margin = (floatval($sellAmount) - $pAmntOnSellQty) - ($sellGstAmount - ($paidPurchasedGstAmountPerItem *   $qty));

//     // Output formatted margin
//     echo round($margin, 2);

// }



// optimized calculation
if (isset($_GET["Pid"])) {
    $productId      = $_GET["Pid"];
    $batchNo        = $_GET["Bid"];
    $qtyTyp         = $_GET["qtype"];
    $mrp            = $_GET["Mrp"] ?? 0;
    $qty            = $_GET["Qty"] ?? 0;
    $discPercent    = $_GET["disc"] ?? 0;
    $taxableAmount  = $_GET["taxable"];
    $sellAmount     = $_GET['sellAmount'];
    $currentStockId = $_GET['currentItemId'];

    // Fetch stock in details data using current stock data
    $currentStockData = $CurrentStock->selectByColAndData('id', $currentStockId);
    $stockInData = $StockInDetails->stockInDetailsById($currentStockData[0]['stock_in_details_id']);

    // Convert inputs to appropriate types
    $mrp = floatval($mrp);
    $qty = intval($qty);
    $discPercent = floatval($discPercent);

    // Calculate discount price
    $discPrice = $mrp - ($mrp * $discPercent / 100);

    // stock in quantity and per item base price
    $stockInQty = ($qtyTyp == 'others') 
        ? intval($stockInData[0]['qty']) + intval($stockInData[0]['free_qty']) 
        : intval($stockInData[0]['loosely_count']);
    $perQtyStockInAmount = floatval($stockInData[0]['amount']) / $stockInQty;

    // Calculate GST on selling price
    $sellGstAmount = floatval($taxableAmount) * (floatval($stockInData[0]['gst']) / 100);

    // Calculate purchased amount on sold quantity
    $pAmntOnSellQty = $perQtyStockInAmount * $qty;

    // Calculate paid GST per item
    $paidPurchasedGstAmountPerItem = floatval($stockInData[0]['gst_amount']) / $stockInQty;

    // Calculate margin
    $margin = (floatval($sellAmount) - $pAmntOnSellQty) - ($sellGstAmount - ($paidPurchasedGstAmountPerItem * $qty));

    // return mergin
    echo round($margin, 2);
}

?>