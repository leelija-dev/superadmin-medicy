<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'currentStock.class.php';

$CurrentStock       = new CurrentStock();

if (isset($_GET["id"])) {
    $stock = $CurrentStock->showCurrentStocByPId($_GET["id"]);
        echo $stock[0]['loosely_price'];
        // print_r($stock);
}
// echo "Hi";

?>