<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockInDetails.class.php';

$StockInDetails = new StockInDetails();

//get mfd date
if (isset($_GET["stock-mfd"])) {
    // echo "error";
    $id = $_GET["stock-mfd"];
    $stock = $StockInDetails->stockInDetailsById($id);
    echo $stock[0]['mfd_date'];
    // print_r($stock);
}

//get expiry date
if (isset($_GET["stock-exp"])) {
    // echo "error";
    $id = $_GET["stock-exp"];
    $stock = $StockInDetails->stockInDetailsById($id);
    echo $stock[0]['exp_date'];
    // print_r($stock);
}


//get waitage
if (isset($_GET["weightage"])) {
    $id = $_GET["weightage"];
    $stock = $StockInDetails->stockInDetailsById($id);
    if ($stock > 0) {
        echo $stock[0]['weightage'].' '.$stock[0]['unit'];
    }
}


//get unit
// if (isset($_GET["unit"])) {
//     $id = $_GET["unit"];
//     $stock = $StockInDetails->showStockInDetailsByStokinId($id);
//     if ($stock > 0) {
//         echo $stock[0]['unit'];
//     }
// }


//get mrp
if (isset($_GET["mrp"])) {
    $id = $_GET["mrp"];
    $stock = $StockInDetails->stockInDetailsById($id);
    if ($stock > 0) {
        echo $stock[0]['mrp'];
    }
}


//get gst
if (isset($_GET["gst"])) {
    $id = $_GET["gst"];
    $stock = $StockInDetails->stockInDetailsById($id);
    if ($stock > 0) {
        echo $stock[0]['gst'];
    }
}


//get ptr
if (isset($_GET["ptr"])) {
    $id = $_GET["ptr"];
    $stock = $StockInDetails->stockInDetailsById($id);
    if ($stock > 0) {
        echo $stock[0]['ptr'];
    }
}


//get discount
if (isset($_GET["discount"])) {
    $id = $_GET["discount"];
    $stock = $StockInDetails->stockInDetailsById($id);
    if ($stock > 0) {
        echo $stock[0]['discount'];
    }
}

//get base price per item
if (isset($_GET["dprice"])) {
    $id = $_GET["dprice"];
    $stock = $StockInDetails->stockInDetailsById($id);
    if ($stock > 0) {
        echo $stock[0]['d_price'];
    }
}

//get gst amount
if (isset($_GET["gstAmountUrl"])) {
    $id = $_GET["gstAmountUrl"];
    $stock = $StockInDetails->stockInDetailsById($id);
    if ($stock > 0) {
        echo $stock[0]['gst_amount'];
    }
}

if (isset($_GET["taxable"])) {
    $id = $_GET["taxable"];
    $stock = $StockInDetails->stockInDetailsById($id);
    if ($stock > 0) {
        foreach($stock as $stockInData){
            $itemPtr = $stockInData['ptr'];
            $discPercent = $stockInData['discount'];
            $qty = $stockInData['qty'];
            $taxable = (floatval($itemPtr) - (floatval($itemPtr) * floatval($discPercent)/100)) * $qty;
            echo $taxable;
        }
    }
}

//return gst amount calculation
if (isset($_GET["gstAmountPerQuantity"])) {
    $id = $_GET["gstAmountPerQuantity"];
    $stock = $StockInDetails->stockInDetailsById($id);
    if ($stock > 0) {
        $totalGst =  $stock[0]['gst_amount'];
        $purchaseQTY = $stock[0]['qty'];
        $gstPerUnit = $totalGst/$purchaseQTY;
        echo number_format($gstPerUnit,2);
    }
}

// get amount
if (isset($_GET["amount"])) {
    $id = $_GET["amount"];
    $stock = $StockInDetails->stockInDetailsById($id);
    if ($stock > 0) {
        echo $stock[0]['amount'];
    }
}


// get purchased qty
if (isset($_GET["purchased-qty"])) {
    $id = $_GET["purchased-qty"];
    // echo $id;
    $stock = $StockInDetails->stockInDetailsById($id);
    if ($stock > 0) {
        echo $stock[0]['qty'];
    }
}


// get free-qty
if (isset($_GET["free-qty"])) {
    $id = $_GET["free-qty"];
    $stock = $StockInDetails->stockInDetailsById($id);
    if ($stock > 0) {
        echo $stock[0]['free_qty'];
    }
}

//get net-buy-qty
if (isset($_GET["net-buy-qty"])) {
    $id = $_GET["net-buy-qty"];
    $stock = $StockInDetails->stockInDetailsById($id);
    if ($stock > 0) {
        $fqty =  $stock[0]['free_qty'];
        $qty = $stock[0]['qty'];;
        echo $fqty+$qty;
    }
}


if (isset($_GET["refund-amount"])) {
    $stock = $StockInDetails->stockInDetailsById($_GET["refund-amount"]);
    if ($stock > 0) {
        $amount =  $stock[0]['amount'];
        $totalQty = $stock[0]['qty'] + $stock[0]['free_qty'];
        echo $eachCost = $amount / $totalQty;
    }
}

?>

