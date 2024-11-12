
<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'currentStock.class.php';

$StockInDetails = new StockInDetails();
$currentStock = new CurrentStock();


//======================== QUANTITY CALCULETION =========================

if (isset($_GET["qtyId"])) {
    
    $productId  = $_GET["qtyId"];
    $batchNo    = $_GET["Bid"];

    $stockInQantity = $currentStock->checkStock($productId, $batchNo);
    echo $stockInQantity[0]['qty'];

}
?>