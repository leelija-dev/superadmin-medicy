<?php 
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'gst.class.php';

$Products = new Products;
$Gst = new Gst;


if($_SERVER["REQUEST_METHOD"] === "POST"){

    $gstPercent = $_POST['gstPercent'];
    $prodId = $_POST['prodId'];

    
    $productTable = json_decode($Products->selectTableNameByProdId($prodId));
    if($productTable->status){
        $table = $productTable->table;

        $col = 'gst';
        $updateProdcutGst = json_decode($Products->updateProductValuebyTableColName($table, $prodId, $col,
        $gstPercent, $employeeId, NOW, $adminId));
        // print_r($updateProdcutGst);

        if($updateProdcutGst->status){
            echo true;
        } else {
            echo false;
        }
    } else {
        echo false;
    }
}



?>