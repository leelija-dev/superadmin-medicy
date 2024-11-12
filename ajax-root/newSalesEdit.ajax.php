<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once dirname(__DIR__) . '/config/service.const.php';

require_once ROOT_DIR . '_config/sessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . "doctors.class.php";
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';

$StockOut = new StockOut();
$Products = new Products();
$CurrentStock = new CurrentStock();
$Manufacturer = new Manufacturer();
$StockInDetails = new StockInDetails;


$StockOutDetaislId = $_POST['stock_out_details_id'];
$itemId = $_POST['Stock_out_item_id'];
$table = 'id';

////////////// STOCK OUT DATA AND SALES ITEM DATA FETCH AREA \\\\\\\\\\\\\\\\\

// // //==================== ITEM DETAILS FROM STOK OUT DETAILS TABLE =====================
$stockOutItemDetails = $StockOut->stokOutDetailsDataOnTable($table, $StockOutDetaislId);
foreach ($stockOutItemDetails as $selsItemData) {
    $stockOutDetailsId                = $selsItemData['id'];
    $stockOutDetailsInvoiceId         = $selsItemData['invoice_id'];
    $stockOutDetailsItemId            = $selsItemData['item_id'];
    $stockOutDetailsProductId         = $selsItemData['product_id'];
    $stockOutDetailsItemName          = $selsItemData['item_name'];
    $stockOutDetailsBatchNo           = $selsItemData['batch_no'];
    $stockOutDetailsExpDate           = $selsItemData['exp_date'];
    $stockOutDetailsItemWeatage       = $selsItemData['weightage'];
    $stockOutDetailsItemUnit          = $selsItemData['unit'];
    $stockOutDetailsItemQty           = $selsItemData['qty'];
    $stockOutDetailsLooselyCount      = $selsItemData['loosely_count'];
    $stockOutDetailsMrp               = $selsItemData['mrp'];
    $stockOutDetailsPtr               = $selsItemData['ptr'];
    $stockOutDetailsDiscount          = $selsItemData['discount'];
    $stockOutDetailsGst               = $selsItemData['gst'];
    $stockOutDetailsGstAmount         = $selsItemData['gst_amount'];
    $stockOutSellMargin               = $selsItemData['sales_margin'];
    $stockOutDetailsMargin            = $selsItemData['profit_margin'];
    $stockOutDetailsItemTaxableAmount = $selsItemData['taxable'];
    $stockOutDetailsamount            = $selsItemData['amount'];


    // if ($stockOutDetailsItemUnit == 'tab' || $stockOutDetailsItemUnit == 'cap') {
    //     $sellQty = $stockOutDetailsLooselyCount;
    // } else {
    //     $sellQty = $stockOutDetailsItemQty;
    // }

    if (in_array(strtolower(trim($stockOutDetailsItemUnit)), LOOSEUNITS)) {
        $sellQty = $stockOutDetailsLooselyCount;
    } else {
        $sellQty = $stockOutDetailsItemQty;
    }
}

// // //================== AVAILIBILITY CHECK FROM CURRENT STOCK ====================
$currentStockData = $CurrentStock->showCurrentStocById($stockOutDetailsItemId);
foreach ($currentStockData as $currenStock) {

    $stockInDetailsId = $currenStock['stock_in_details_id'];
    // $currentStockUnit = $currenStock['unit'];

    // if ($currentStockUnit == 'Tablets' || $currentStockUnit == 'Capsules') {
    //     $currentStockAvailibility = $currenStock['loosely_count'];
    // } else {
    //     $currentStockAvailibility = $currenStock['qty'];
    // }

    if (in_array(strtolower(trim($currenStock['unit'])), LOOSEUNITS)) {
        $currentStockAvailibility = $currenStock['loosely_count'];
    } else {
        $currentStockAvailibility = $currenStock['qty'];
    }

    $currentStockPtr = $currenStock['ptr'];
}


// // // ============================== MANUFACTURUR DETAILS ===================================

        // =========== edit req flag key check ==========
        $prodCheck = json_decode($Products->productExistanceCheck($stockOutDetailsProductId));
        if($prodCheck->status == 1){
            $editReqFlag = 0;
        }else{
            $editReqFlag = '';
        }

    $prodDetails = json_decode($Products->showProductsByIdOnUser($stockOutDetailsProductId, $adminId, $editReqFlag));
    $prodDetails = $prodDetails->data;

    if (isset($prodDetails[0]->product_composition)) {
        $composition = $prodDetails[0]->product_composition;
    } else {
        $composition = '';
    }

    if (isset($prodDetails[0]->manufacturer_id)) {
        $manufData = json_decode($Manufacturer->showManufacturerById($prodDetails[0]->manufacturer_id));
        if($manufData->status){
            $manufId = $manufData->data->id;
            $manufName = $manufData->data->name;
        }else{
            $manufId = '';
            $manufName = '';
        }
    } else {
        $manufId = '';
        $manufName = '';
    }



// ============= stock out purchase cost calculation area ==================
$stockInDetailsData = $StockInDetails->stockInDetailsById($stockInDetailsId);

foreach($stockInDetailsData as $stockInDetailsData){
    $stockInAmount = $stockInDetailsData['amount'];
    $stockInQty = $stockInDetailsData['qty'];
    $stockInFreeQty = $stockInDetailsData['free_qty'];
    $itemWeightage = $stockInDetailsData['weightage'];

    if (in_array(strtolower($stockInDetailsData['unit']), LOOSEUNITS)) {
        $perItemCost = floatval($stockInAmount) / ((intval($stockInQty) + intval($stockInFreeQty)) * intval($itemWeightage));
        
    } else {

        $perItemCost = floatval($stockInAmount) / (intval($stockInQty) + intval($stockInFreeQty));
        
    }

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
    "packOf"                    =>  $stockOutDetailsItemWeatage . $stockOutDetailsItemUnit,
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
    "purchaseCost"              =>  $perItemCost,
    "saleMargin"                =>  $stockOutSellMargin,
    "margin"                    =>  $stockOutDetailsMargin,
    "taxable"                   =>  $stockOutDetailsItemTaxableAmount,
    "paybleAmount"              =>  $stockOutDetailsamount
);


$stockOutDetailsDataArry = json_encode($stockOutDetailsDataArry);

if ($itemId == true) {
    echo $stockOutDetailsDataArry;
} else {
    echo 0;
}
