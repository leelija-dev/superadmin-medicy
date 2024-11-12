<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once dirname(__DIR__).'/config/service.const.php';
require_once ROOT_DIR.'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR."dbconnect.php";
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'itemUnit.class.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'stockInDetails.class.php';



$Products       = new Products();
$ItemUnit       = new itemUnit();
$CurrentStock   = new CurrentStock();
$StockInDetails = new StockInDetails();



// ================ get product name =========================
// if (isset($_GET["id"])) {

//     // =========== edit req flag key check ==========
//     $prodCheck = json_decode($Products->productExistanceCheck($_GET["id"]));
//     if($prodCheck->status == 1){
//         $editReqFlag = 0;
//     }else{
//         $editReqFlag = '';
//     }

//     $showProducts = $Products->showProductsByIdOnUser($_GET["id"], $adminId, $editReqFlag);
//     $showProductsData = json_decode($showProducts,true);
//     // print_r($showProductsData);
//     if($showProductsData['status']){
//         $productData  = $showProductsData['data'];
//         $showProducts = $productData[0]['name'];
//     }else{
//         $showProducts = 'Data Not Found';
//     }
//     echo $showProducts;
// }


if (isset($_GET["id"])) {
    $productId = $_GET["id"];

    // Check product existence
    $prodCheck = json_decode($Products->productExistanceCheck($productId));
    $editReqFlag = ($prodCheck->status == 1) ? 0 : '';

    // Fetch product details
    $showProducts = json_decode($Products->showProductsByIdOnUser($productId, $adminId, $editReqFlag), true);

    // fetch and return product name
    $productName = ($showProducts['status']) ? $showProducts['data'][0]['name'] : 'Data Not Found';

    echo $productName;
}



if (isset($_GET["Pid"])) {
    // $productDetails = $Products->showProductsById($_GET["Pid"]);
    echo ($_GET["Pid"]);
}

// ===================== PRODUCT WEIGHTAGE ======================

if (isset($_GET["itemWeightage"])) {
    $productId = $_GET["itemWeightage"];

    // =========== edit req flag key check ==========
    $prodCheck = json_decode($Products->productExistanceCheck($productId));
    if($prodCheck->status == 1){
        $editReqFlag = 0;
    }else{
        $editReqFlag = '';
    }

    $showProducts = json_decode($Products->showProductsByIdOnUser($productId, $adminId, $editReqFlag));
    
    // print_r($showProducts);

    if($showProducts->status){
        $productData  = $showProducts->data;
        $showProducts = $productData[0]->unit_quantity;
    }else{
        $showProducts = 'No Data Found'; 
    }
    // if ($showProducts) {
        // echo $showProducts[0]['unit_quantity'];
    // }
    echo $showProducts;
}

// ============== UNIT ====================

if (isset($_GET["itemUnit"])) {
    $prodId = $_GET["itemUnit"];

    // =========== edit req flag key check ==========
    $prodCheck = json_decode($Products->productExistanceCheck($prodId));
    if($prodCheck->status == 1){
        $editReqFlag = 0;
    }else{
        $editReqFlag = '';
    }

    $showProducts = json_decode($Products->showProductsByIdOnUser($prodId, $adminId, $editReqFlag));
    
    if ($showProducts->status) {
        $productData = $showProducts->data;
        
        echo $ItemUnit->itemUnitName($productData[0]->unit);
    }else{
        echo 'Product Unit Not Found';
    }
    // echo $ItemUnit->itemUnitName($showProducts[0]['unit']);
    // echo $showProducts[0]['unit'];
}

// ======== get curretn stock expiary date =========

if (isset($_GET["exp"])) {

    $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["exp"], $_GET["batchNo"]);
    echo $stock[0]['exp_date'];
    // print_r($stock);
}

// ============== get MRP from current stock ==============

if (isset($_GET["stockmrp"])) {
    $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["stockmrp"], $_GET["batchNo"]);
    echo $stock[0]['mrp'];
}

// ======================= PTR ACCESS FROM CURRENT STOCK =====================

if (isset($_GET["stockptr"])) {
    $stock = $CurrentStock->showCurrentStocById($_GET["currentStockId"]);
    // print_r($stock);
    $stockIn = $StockInDetails->stockInDetailsById($stock[0]['stock_in_details_id']);
    // print_r($stockIn);
    // Calculate the amount to subtract based on the percentage
    $discountAmount = $stock[0]['ptr'] * ($stockIn[0]['discount'] / 100);
    
    // Subtract the amount from the original number
    $result1 = $stock[0]['ptr'] - $discountAmount;
    
    $result1;

    // Calculate the amount to add based on the percentage
    $addAmount = $result1 * ($stockIn[0]['gst'] / 100);
    
    // Add the amount to the original number
    $result = $result1 + $addAmount;
    
    echo number_format($result, 2);
}

// ============ CURRENT STOCK ITEM LOOSE STOCK CHEK BLOCK ===============
if (isset($_GET["looseStock"])) {
    $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["looseStock"], $_GET["batchNo"]);
    foreach ($stock as $stock) {
        // print_r($stock);
        if (in_array(strtolower(trim($stock['unit'])), LOOSEUNITS)) {
            $looseCount = $stock['loosely_count'];
        } else {
            $looseCount = null;
        }
    }
    echo $looseCount;
}

// ========================== CURRENT STOCK AVAILIBILITY CHECK =============================
if (isset($_GET["availibility"])) {

    $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["availibility"], $_GET["batchNo"]);

    foreach($stock as $stock){
        if (in_array(strtolower(trim($stock['unit'])), LOOSEUNITS)) {
            $availibility = $stock['loosely_count'];
        } else {
            $availibility = $stock['qty'];
        }
    }
    echo $availibility;
}
