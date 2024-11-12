<?php      
# RD #
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR."stockReturn.class.php";
require_once CLASS_DIR."distributor.class.php";
require_once CLASS_DIR."products.class.php";
require_once CLASS_DIR."stockIn.class.php";
require_once CLASS_DIR."stockInDetails.class.php";
require_once CLASS_DIR."currentStock.class.php";

$StockReturnEdit        =   new StockReturn();
$DistributorDetails     =   new Distributor();
$ProdcutDetails         =   new Products();
$StockIn                =   new StockIn();
$StockInDetails         =   new StockInDetails();
$CurrentStock           =   new CurrentStock();


$EditId = $_POST['EditId'];

//==========================fetching data from stock return details table========================

$stockReturnDetailsData = $StockReturnEdit->showStockReturnDetailsById($EditId);

foreach($stockReturnDetailsData as $stockReturn){
    $Id                     =  $stockReturn['id'];
    $StockReturnId          =  $stockReturn['stock_return_id'];
    $stokinDetialsItemID    =  $stockReturn['stokIn_details_id'];
    $ProductId              =  $stockReturn['product_id'];
    $BatchNo                =  $stockReturn['batch_no'];
    $ExpDate                =  $stockReturn['exp_date'];
    $Unit                   =  $stockReturn['unit'];
    $PurchaseQTY            =  $stockReturn['purchase_qty'];
    $FreeQTY                =  $stockReturn['free_qty'];
    $MRP                    =  $stockReturn['mrp'];
    $PTR                    =  $stockReturn['ptr'];
    $GST                    =  $stockReturn['gst'];
    $discount               =  $stockReturn['disc'];
    $ReturnQTY              =  $stockReturn['return_qty'];
    $ReturnFreeQTY          =  $stockReturn['return_free_qty'];
    $RefundAmount           =  $stockReturn['refund_amount'];

   
}

$stockReturnData = json_encode($stockReturnDetailsData);
//==========================fetching data from stock_return table=================================
$stockReturn = json_decode($StockReturnEdit->showStockReturnById($StockReturnId));
$stockReturn = $stockReturn->data;

foreach($stockReturn as $stocks){
    $DistributorId   =   $stocks->distributor_id;
    $ReturnDate      =   $stocks->return_date;
    $RefundMode      =   $stocks->refund_mode;
    $Items           =   $stocks->items;
    $TotalQTY        =   $stocks->total_qty;
    $GSTamount       =   $stocks->gst_amount;
    $refundMode      =   $stocks->refund_mode;
    $NetRefundAmount =   $stocks->refund_amount;
}

//$stockReturn = json_encode($stockReturn);
//===============================================================================================

//==========================fetching data from distributor table==================================
$distributorDetails = json_decode($DistributorDetails ->showDistributorById($DistributorId));
$distributorDetails = $distributorDetails->data;

foreach($distributorDetails as $distributor){
    $distributorName    =   $distributor->name;
    $distributorId      =   $distributor->id;
}

// $distributorDetails = json_encode($distributorDetails);
//===============================================================================================

//==========================fetching data from products table=====================================
$productDetails = json_decode($ProdcutDetails -> showProductsById($ProductId));
$productDetails = $productDetails->data;

foreach($productDetails as $products){
    $productName    =   $products->name;
}

$productDetails = json_encode($productDetails);
//===============================================================================================

//================================fetchin data from current stock================================
$currentStockDetails = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($stokinDetialsItemID));

// print_r()
if($currentStockDetails != null){
    // foreach($currentStockDetails as $currentStock){
        $currentStockQty   =   $currentStockDetails->qty;
    // }
}else{
    $currentStockQty = 0;
}
 
$currentStockDetails = json_encode($currentStockDetails);
//===============================================================================================

$stockReturnDetailsDataArry = array(
                                    "StokReturnDetailsId"       =>  $Id,
                                    "stock_return_id"           =>  $StockReturnId,
                                    "stock_in_details_item_id"  =>  $stokinDetialsItemID,

                                    "distributor_name"          =>  $distributorName,
                                    "distributor_id"            =>  $distributorId,
                                    
                                    "product_id"                =>  $ProductId,
                                    "product_Name"              =>  $productName,
                                    "batch_no"                  =>  $BatchNo,

                                    "return_date"               =>  $ReturnDate,
                                    "exp_date"                  =>  $ExpDate,
                                    "unit"                      =>  $Unit,
                                    
                                    "purchase_qty"              =>  $PurchaseQTY,
                                    "free_qty"                  =>  $FreeQTY,
                                    "mrp"                       =>  $MRP,
                                    "ptr"                       =>  $PTR,
    
                                    "gst"                       =>  $GST,
                                    "disParcent"                =>  $discount,
                                    "return_qty"                =>  $ReturnQTY,
                                    "return_free_qty"           =>  $ReturnFreeQTY,
                                    "per_item_refund"           =>  $RefundAmount,

                                    "net_refund_amount"         =>  $NetRefundAmount,

                                    "current_stock_qty"         =>  $currentStockQty,
                                    
                                    "refund_mode"               =>  $RefundMode);
                             
$stockReturnDetailsDataArry = json_encode($stockReturnDetailsDataArry);

if($stockReturnDetailsDataArry == true){
    echo $stockReturnDetailsDataArry;
    
}else{
    echo 0;
}

?>