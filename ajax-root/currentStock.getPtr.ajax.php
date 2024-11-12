<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'currentStock.class.php';

$CurrentStock       = new CurrentStock();

if (isset($_GET["stockptr"])) {
    $stock = $CurrentStock->showCurrentStocByPId($_GET["stockptr"]);
        echo $stock[0]['ptr'];
        // print_r($stock);
}
// echo "Hi";

?>