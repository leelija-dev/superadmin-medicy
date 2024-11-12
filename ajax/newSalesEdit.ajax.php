<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR."doctors.class.php";
require_once CLASS_DIR.'stockOut.class.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'manufacturer.class.php';

$StockOut = new StockOut();
$Products = new Products();
$CurrentStock = new CurrentStock();
$Manufacturer = new Manufacturer();


$StockOutDetaislId = $_POST['stock_out_details_id'];
$itemId = $_POST['Stock_out_item_id'];
$table = 'id';

////////////// STOCK OUT DATA AND SALES ITEM DATA FETCH AREA \\\\\\\\\\\\\\\\\

// // //==================== ITEM DETAILS FROM STOK OUT DETAILS TABLE =====================
$stockOutItemDetails = $StockOut->stokOutDetailsDataOnTable($table, $StockOutDetaislId);
foreach($stockOutItemDetails as $selsItemData){
    $stockOutDetailsId = $selsItemData['id'];
    $stockOutDetailsInvoiceId = $selsItemData['invoice_id'];
    $stockOutDetailsItemId = $selsItemData['item_id'];
    $stockOutDetailsProductId = $selsItemData['product_id'];
    $stockOutDetailsItemName = $selsItemData['item_name'];
    $stockOutDetailsBatchNo = $selsItemData['batch_no'];
    $stockOutDetailsExpDate = $selsItemData['exp_date'];
    $stockOutDetailsItemWeatage = $selsItemData['weightage'];
    $stockOutDetailsItemUnit = $selsItemData['unit'];
    $stockOutDetailsItemQty = $selsItemData['qty'];
    $stockOutDetailsLooselyCount = $selsItemData['loosely_count'];
    $stockOutDetailsMrp = $selsItemData['mrp'];
    $stockOutDetailsPtr = $selsItemData['ptr'];
    $stockOutDetailsDiscount = $selsItemData['discount'];
    $stockOutDetailsGst = $selsItemData['gst'];
    $stockOutDetailsGstAmount = $selsItemData['gst_amount'];
    $stockOutDetailsMargin = $selsItemData['margin'];
    $stockOutDetailsItemTaxableAmount = $selsItemData['taxable'];
    $stockOutDetailsamount = $selsItemData['amount'];


    if($stockOutDetailsItemUnit == 'tab' || $stockOutDetailsItemUnit == 'cap'){
        $sellQty = $stockOutDetailsLooselyCount;
    }else{
        $sellQty = $stockOutDetailsItemQty;
    }
}

// // //================== AVAILIBILITY CHECK FROM CURRENT STOCK ====================
$currentStockData = $CurrentStock->showCurrentStocById($stockOutDetailsItemId);
foreach($currentStockData as $currenStock){
    $currentStockUnit = $currenStock['unit'];

    if($currentStockUnit == 'tablets' || $currentStockUnit == 'capsules'){
        $currentStockAvailibility = $currenStock['loosely_count'];
    }else{
        $currentStockAvailibility = $currenStock['qty'];
    }

    $currentStockPtr = $currenStock['ptr'];
}


// // // ============================== MANUFACTURUR DETAILS ===================================
$prodDetails = $Products->showProductsById($stockOutDetailsProductId);
$composition = $prodDetails[0]['product_composition'];

$manufData = $Manufacturer->showManufacturerById($prodDetails[0]['manufacturer_id']);
foreach($manufData as $manufData){
    $manufId = $manufData['id'];
    $manufName = $manufData['name'];
}

// // //////////////////////\\\\\\\\\\\\\\\\\\\\\\\\================///////////////////////\\\\\\\\\\\\\\\\\\\\\\
$stockOutDetailsDataArry = array(
    "stockOutDetailsId"         =>  $stockOutDetailsId,
    "invoiceId"                 =>  $stockOutDetailsInvoiceId,
    "itemId"                    =>  $stockOutDetailsItemId,
    "productId"                 =>  $stockOutDetailsProductId,
    "productName"               =>  $stockOutDetailsItemName,
    "manufId"                   =>  $manufId,                   
    "manufName"                 =>  $manufName,
    "productComposition"        =>  $composition,
    "batchNo"                   =>  $stockOutDetailsBatchNo,
    "packOf"                    =>  $stockOutDetailsItemWeatage.$stockOutDetailsItemUnit,
    "itemWeatage"               =>  $stockOutDetailsItemWeatage,
    "itemUnit"                  =>  $stockOutDetailsItemUnit,
    "expDate"                   =>  $stockOutDetailsExpDate,
    "qantity"                   =>  $stockOutDetailsItemQty,  
    "looseCount"                =>  $stockOutDetailsLooselyCount,
    "availableQty"              =>  $currentStockAvailibility,
    "sellQty"                   =>  $sellQty,
    "Mrp"                       =>  $stockOutDetailsMrp,
    "Ptr"                       =>  $currentStockPtr,
    "dicPercent"                =>  $stockOutDetailsDiscount,
    "gstPercent"                =>  $stockOutDetailsGst,
    "gstAmount"                 =>  $stockOutDetailsGstAmount,
    "margin"                    =>  $stockOutDetailsMargin,
    "taxable"                   =>  $stockOutDetailsItemTaxableAmount,
    "paybleAmount"              =>  $stockOutDetailsamount
);


$stockOutDetailsDataArry = json_encode($stockOutDetailsDataArry);

if ($itemId == true) {
    echo $stockOutDetailsDataArry;
    // echo $StockOutDetaislId;
    // echo $itemId;
} else {
    echo 0;
}
