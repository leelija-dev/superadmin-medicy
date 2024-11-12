<?php
##########################################################################################################
#                                                                                                        #
#                                      Stock In Edit Page                              (RD)              #
#                                                                                                        #
##########################################################################################################
require_once dirname(__DIR__) . '/config/constant.php';
require_once dirname(__DIR__) . '/config/service.const.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . "stockInDetails.class.php";
require_once CLASS_DIR . "stockIn.class.php";
require_once CLASS_DIR . "products.class.php";
require_once CLASS_DIR . "currentStock.class.php";
require_once CLASS_DIR . "manufacturer.class.php";
require_once CLASS_DIR . "packagingUnit.class.php";

$StockIn = new StockIn();
$StockInDetails = new StockInDetails();
$Products  = new Products();
$Manufacturer = new Manufacturer();
$Packaging = new PackagingUnits();
$CurrentStock = new CurrentStock;



if (isset($_POST['blNo'])) {

    $prodId = $_POST['pId'];
    $billNo = $_POST['blNo'];
    $batchNo = $_POST['bhNo'];

    // echo "$prodId $billNo $batchNo";
    $purchaseDetail = $StockInDetails->stokInDetials($prodId, $billNo, $batchNo);
    // print_r($purchaseDetail);

    foreach ($purchaseDetail as $purchase) {
        $purchaseId     = $purchase['id'];
        $productId      = $purchase['product_id'];
        $distBillNo     = $purchase['distributor_bill'];
        $prodBatchNo    = $purchase['batch_no'];
        $prodMfdDate    = $purchase['mfd_date'];
        $prodExpDate    = $purchase['exp_date'];

        $prodWeightage  = $purchase['weightage'];
        $prodUnit       = $purchase['unit'];
        $QTY            = $purchase['qty'];
        $freeQTY        = $purchase['free_qty'];
        $looseCount     = $purchase['loosely_count'];
        $MRP            = $purchase['mrp'];
        $PTR            = $purchase['ptr'];
        $discunt        = $purchase['discount'];
        $dPrice         = $purchase['d_price'];
        $GST            = $purchase['gst'];
        $gstAmount      = $purchase['gst_amount'];
        $amount         = $purchase['amount'];

        if (in_array(strtolower(trim($purchase['unit'])), LOOSEUNITS)) {
            $purchasedQty = $purchase['loosely_count'];
        } else {
            $purchasedQty = $purchase['qty'];
        }
    }



    // current stock data fetch on stock in details id 
    $col = 'stock_in_details_id';
    $StockInDetailsId = $purchaseId;
    $currentStockData = $CurrentStock->selectByColAndData($col, $StockInDetailsId);
    foreach ($currentStockData as $currentStockData) {
        // print_r($currentStockData);
        if (in_array(strtolower(trim($currentStockData['unit'])), LOOSEUNITS)) {
            $currentStockQty = $currentStockData['loosely_count'];
        } else {
            $currentStockQty = $currentStockData['qty'];
        }

    }



    // =========== edit req flag key check ==========
    $prodCheck = json_decode($Products->productExistanceCheck($productId));
    if ($prodCheck->status == 1) {
        $editReqFlag = 0;
    } else {
        $editReqFlag = '';
    }
    //========================

    $productDetails = json_decode($Products->showProductsByIdOnUser($productId, $adminId, $editReqFlag));
    if ($productDetails->status) {
        $productDetails = $productDetails->data;
        foreach ($productDetails as $products) {
            $prodName = $products->name;

            if (isset($products->manufacturer_id)) {
                $manufID = $products->manufacturer_id;
            } else {
                $manufID = null;
            }

            $packagingTyp = $products->packaging_type;
            $power = $products->power;
        }
    } else {
        return "no data found!";
    }


    if ($manufID != null) {
        $ManufDetails = json_decode($Manufacturer->showManufacturerById($manufID));
        $ManufDetails = $ManufDetails->data;
        // foreach($ManufDetails as $manuf){
        $manufName = $ManufDetails->name;
        // }
    } else {
        $manufName = '';
    }

    // $manufName = str_replace()


    $packagingDetails = json_decode($Packaging->showPackagingUnitById($packagingTyp));
    if ($packagingDetails->status) {
        $packType = $packagingDetails->data->unit_name;
    } else {
        $packType = '';
    }


    $delFlag = 1; // delete check flag
    

    $purchaseDetialArray = array(
        "purchaseId"        => $purchaseId,
        "productId"         => $productId,
        "productName"       => $prodName,
        "manufId"           => $manufID,
        "manufacturer"      => $manufName,
        "billNo"            => $distBillNo,
        "batchNo"           => $prodBatchNo,
        "mfdDate"           => $prodMfdDate,
        "expDate"           => $prodExpDate,
        "weightage"         => $prodWeightage,
        "unit"              => $prodUnit,
        "power"             => $power,
        "packageType"       => $packType,
        "qty"               => $QTY,
        "FreeQty"           => $freeQTY,
        "looseQty"          => $looseCount,
        "purchasedQty"      => $purchasedQty,
        "currentStockQty"   => $currentStockQty,
        "mrp"               => $MRP,
        "ptr"               => $PTR,
        "disc"              => $discunt,
        "dPrice"        => $dPrice,
        "gst"               => $GST,
        "GstAmount"         => $gstAmount,
        "amnt"              => $amount,
        "delflag"           => $delFlag
    );


    $purchaseDetialArray = json_encode($purchaseDetialArray);

    echo $purchaseDetialArray;
}

?>