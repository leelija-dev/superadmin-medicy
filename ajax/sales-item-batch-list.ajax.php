<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR."dbconnect.php";
require_once CLASS_DIR.'search.class.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR.'products.class.php';

$CurrentStock = new CurrentStock();
$Manufacturer = new Manufacturer();
$Search       = new Search();
$Products     = new Products();


$searchBatch = FALSE;

if(isset($_GET['prodId'])){
    $productID = $_GET['prodId'];
    // echo $productID;
    // $batch = $_GET['batch-no'];
    // $qty = $_GET['qty'];
    // $flag = $_GET['checkFlag'];

    $ProductBatchData = $CurrentStock->showCurrentStocByProductId($productID, $adminId);
    $ProductBatchData = json_decode($ProductBatchData);
    // print_r($ProductBatchData);
}

if($ProductBatchData != ''){
    // echo "<h5 style='padding-left: 12px ; padding-top: 5px ;'><a>".$serchR."</a></h5>";
    ?>
<div class="row mx-2 p-1 text-muted border-bottom" style="max-width: 20rem;">
    <!-- <div class="col-md-5">Preoduct</div> -->
    <div class="col-md-6">Batch no</div>
    <div class="col-md-6">Stock</div>
</div>
<?php
    foreach($ProductBatchData as $itemData){
        // print_r($itemData);
        $productId  = $itemData->product_id;
        $id = $itemData->id;

        $prodNameFetch = $Products->showProductsById($productId);
        foreach($prodNameFetch as $productData){
            $prodName = $productData['name'];
        }

        $prodBatch   = $itemData->batch_no;
        $qantity   = $itemData->qty;
        $looseQty   = $itemData->loosely_count;
        $weightage   = $itemData->weightage;
        $unit        = $itemData->unit;
        $packOf      = $weightage.'/'.$unit;
        ?>
            <div class="row mx-2 p-1 border-bottom searched-list" id="<?php echo $productId ?>" value="<?php echo $prodBatch ?>" value1="<?php echo $id ?>" onclick="stockDetails('<?php echo $productId ?>','<?php echo $prodBatch ?>', '<?php echo $id ?>', this.id, this.value, this.value1);">
                <!-- <div class="col-md-5"><?php echo $prodName ?></div> -->
                <div class="col-md-6"><?php echo $prodBatch ?></div>
                <div class="col-md-6"><?php echo $qantity;
                if($looseQty > 0){
                    echo "($looseQty)";
                }else
                echo "" ?></div>
            </div> 
<?php

    }
}
else{
    echo "Result Not Found";
}
?>