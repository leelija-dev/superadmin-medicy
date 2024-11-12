<?php
##########################################################################################################
#                                                                                                        #
#                                      Stock In Edit Page                              (RD)              #
#                                                                                                        #
##########################################################################################################
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR."stockInDetails.class.php";
require_once CLASS_DIR."stockIn.class.php";
require_once CLASS_DIR."products.class.php";
require_once CLASS_DIR."manufacturer.class.php";
require_once CLASS_DIR."packagingUnit.class.php";

$StockIn = new StockIn();
$StockInDetails = new StockInDetails();
$Products  = new Products();
$Manufacturer = new Manufacturer();
$Packaging = new PackagingUnits();

if(isset($_POST['blNo'])){

    $prodId = $_POST['pId'];
    $billNo = $_POST['blNo'];
    $batchNo = $_POST['bhNo'];

    $purchaseDetail = $StockInDetails->stokInDetials($prodId, $billNo, $batchNo);

    foreach($purchaseDetail as $purchase){
        $purchaseId = $purchase['id'];
        $productId = $purchase['product_id']; 
        $distBillNo = $purchase['distributor_bill'];
        $prodBatchNo = $purchase['batch_no'];
        $prodMfdDate = $purchase['mfd_date'];
        $prodExpDate = $purchase['exp_date'];
        
        $prodWeightage = $purchase['weightage'];
        $prodUnit = $purchase['unit'];
        $QTY = $purchase['qty'];
        $freeQTY = $purchase['free_qty'];
        $looseCount = $purchase['loosely_count'];
        $MRP = $purchase['mrp'];
        $PTR = $purchase['ptr'];
        $discunt = $purchase['discount'];
        $base = $purchase['base'];
        $GST = $purchase['gst'];
        $gstAmount = $purchase['gst_amount'];
        $margin = $purchase['margin'];
        $amount = $purchase['amount'];
    }

    $productDetails = json_decode( $Products->showProductsById($productId));
    $productDetails = $productDetails->data;
    foreach($productDetails as $products){
        $prodName = $products->name;
        $manufID = $products->manufacturer_id;
        $packagingTyp = $products->packaging_type;
        $power = $products->power;
    }


    $ManufDetails = json_decode($Manufacturer->showManufacturerById($manufID));
    $ManufDetails = $ManufDetails->data;
    // foreach($ManufDetails as $manuf){
        $manufName = $ManufDetails->name;
    // }

    // $manufName = str_replace()
    

    $packagingDetails = $Packaging->showPackagingUnitById($packagingTyp);
    foreach($packagingDetails as $packageType){
        $packType = $packageType['unit_name'];
    }


    $purchaseDetialArray = array(
        "purchaseId"    => $purchaseId,
        "productId"     => $productId,
        "productName"   => $prodName,
        "manufId"       => $manufID,
        "manufacturer"  => $manufName,
        "billNo"        => $distBillNo,
        "batchNo"       => $prodBatchNo,
        "mfdDate"       => $prodMfdDate,
        "expDate"       => $prodExpDate,
        "weightage"     => $prodWeightage,
        "unit"          => $prodUnit,
        "power"         => $power,
        "packageType"   => $packType,
        "qty"           => $QTY,
        "FreeQty"       => $freeQTY,
        "looseQty"      => $looseCount,
        "mrp"           => $MRP,
        "ptr"           => $PTR,
        "disc"          => $discunt,
        "baseAmount"    => $base,    
        "gst"           => $GST,
        "GstAmount"     => $gstAmount,
        "mrgn"          => $margin,
        "amnt"          => $amount
    );


    $purchaseDetialArray = json_encode($purchaseDetialArray);

    echo $purchaseDetialArray;
}
