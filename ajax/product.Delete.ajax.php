<?php

require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');
require_once SUP_ADM_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . "products.class.php";
require_once CLASS_DIR . "request.class.php";
require_once CLASS_DIR . "packagingUnit.class.php";
require_once CLASS_DIR . "productsImages.class.php";
require_once CLASS_DIR . "manufacturer.class.php";
require_once CLASS_DIR . "currentStock.class.php";

$Products       = new Products();
$CurrentStock   = new CurrentStock();


$Request       = new Request();
$ProductImages = new ProductImages;


// $productTableId = $_POST['id'];
$productId      = $_POST['productId'];
$table          = $_POST['table'];
$oldProductId   = $_POST['oldProdId'];

$deleteProduct  = $Request->deleteProductOnTable($productId, $table);

if ($table == 'product_request' && !empty($oldProductId)) {
    $prductData = json_decode($Products->showProductsById($oldProductId));
    $editReqFlag = $prductData->data->edit_request_flag;

    if (intval($editReqFlag) > 0) {
        $editReqFlag--;
    } else {
        $editReqFlag = 0;
    }
    $col = 'edit_request_flag';
    $updateProduct = $Products->updateOnColData($col, $editReqFlag, $oldProductId);
}


$checkImg = json_decode($ProductImages->showImagesByProduct($productId));
if ($checkImg->status) {
    $deleteProductImg = $ProductImages->deleteImageByPID($productId);
} else {
    $deleteProductImg = true;
}




if ($deleteProduct && $deleteProductImg) {
    echo true;
} else {
    echo false;
}
