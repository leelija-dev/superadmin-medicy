<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . "stockReturn.class.php";
require_once CLASS_DIR . "distributor.class.php";
require_once CLASS_DIR . "products.class.php";

$PurchaseReturn = new StockReturn();
$DistributorDetils = new Distributor();
$Product = new Products();

$table1 = 'id';
$table2 = 'status';
$data2 = '1';

$checkId = $_POST['Id'];

$checkReturnEdit = $PurchaseReturn->stockReturnByTables($table1, $checkId, $table2, $data2);
// print_r($checkReturnEdit);
if (empty(!$checkReturnEdit)) {
    echo true;
} else {
    echo false;
}
