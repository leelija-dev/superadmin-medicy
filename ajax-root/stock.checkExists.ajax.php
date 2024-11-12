<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR."dbconnect.php";
require_once CLASS_DIR.'currentStock.class.php';

$CurrentStock = new CurrentStock();

//=================== CHECKING STOCK EXISTANCE ===================
if (isset($_GET["Pid"])) {
    $prodId = $_GET["Pid"];
    $batchNo = $_GET["batchNo"];

    $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($prodId, $batchNo);
    if ($stock != NULL) {
        // print_r($stock);
        echo 1;
    }else {
        echo 0;
    }
}
// echo "Hi";

?>