<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'currentStock.class.php';

$StockInDetails = new StockInDetails();
$currentStock = new CurrentStock();

if (isset($_GET["Pid"])) {

    $productId      = $_GET["Pid"];
    $batchNo        = $_GET["Bid"];
    $qtyTyp         = $_GET["qtype"];
    $mrp            = $_GET["Mrp"];
    $qty            = $_GET["Qty"];
    $discPercent    = $_GET["disc"];

    // echo "<br>Product id : $productId<br>Batch no : $batchNo<br>Qantity Type : $qtyTyp<br>MRP : $mrp<br>Qantity : $qty<br>Discounted Price : $discPrice<br>";

    $stockInMargin = $currentStock->checkStock($productId, $batchNo);

    // print_r($stockInMargin);

    if($mrp == null || $qty == null || $discPercent == null){
        $mrp = 0;
        $qty = 0;
        $disc = 0;
    }

    $mrp = floatval($mrp);
    $qty = intval($qty);
    $discPercent = floatval($discPercent);

    $discPrice = $mrp - ($mrp * $discPercent/100);

    //echo "<br>$mrp<br>$qty<br>$discPrice<br><br>";

    if($qtyTyp == ''){
        $ptr = $stockInMargin[0]['ptr'];
        $margin = ($discPrice * $qty) - ($ptr * $qty);
    }else{
        $ptr = $stockInMargin[0]['ptr'];
        $ptr = floatval($ptr) / intval($stockInMargin[0]['weightage']);
        $discPrice = $discPrice / intval($stockInMargin[0]['weightage']);
        $margin = ($discPrice * $qty) - ($ptr * $qty);
    }
    echo number_format($margin,2);
    // echo $margin;
}

?>