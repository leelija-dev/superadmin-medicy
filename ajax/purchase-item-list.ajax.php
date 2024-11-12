<style>
    .searched-list:hover {
        background: #3e059b26;
        cursor: pointer;
    }
</style>

<?php

require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'search.class.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR.'packagingUnit.class.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'itemUnit.class.php';


$CurrentStock = new CurrentStock();
$Manufacturer = new Manufacturer();
$Search       = new Search();
$PackagingUnits = new PackagingUnits();
$Products = new Products;
$ItemUnit = new ItemUnit;

// require_once '../../employee/config/dbconnect.php';

$searchResult = null;
if (isset($_GET['data'])) {
    $data = $_GET['data'];
    // echo $data;
    // echo "<br>",$adminId;
    $col = 'admin_id';
    $resultData = $Products->selectItemLike($data);
    // print_r($resultData);
}

if ($resultData) {

    // echo "<h5 style='padding-left: 12px ; padding-top: 5px ;'><a>".$searchResult."</a></h5>";
?>
    <div class="row border-bottom border-primary small mx-0 mb-2">
        <div class="col-md-4">Searched For</div>
        <div class="col-md-4">Composition</div>
        <div class="col-md-2">Unit/Pack</div>
        <div class="col-md-2">Stock</div>
    </div>
    <?php
    foreach ($resultData as $resultRow) {

        $productId      = $resultRow['product_id'];
        $productName    = $resultRow['name'];
        $pComposition   = $resultRow['comp_1'];
        $pComposition2  = $resultRow['comp_2'];
        $weightage      = $resultRow['unit_quantity'];
        $unit           = $resultRow['unit'];
        $unitDetials    = $ItemUnit->itemUnitName($unit);
        // echo $unitDetials;
        $packagingType  = $resultRow['packaging_type'];
        $packDetails    = $PackagingUnits->showPackagingUnitById($packagingType);
        foreach ($packDetails as $packData) {
            $packageType = $packData['unit_name'];
        }
        $packOf      = $weightage . $unitDetials . '/' . $packageType;
        $manufacturerId = $resultRow['manufacturer_id'];
        $manufacturer = json_decode($Manufacturer->showManufacturerById($manufacturerId));

        $manufacturerName = ($manufacturer->status)? $manufacturer->data->name : 'no data found';

        // foreach ($manufacturer as $row) {
        //     $manufacturerName = $row['name'];
        // }

        $power = '';
        $power       = $resultRow['power'];
        if ($power != NULL) {
            $power = ' | ' . $resultRow['power'];
        }

        if ($unit == "tablets" || $unit == "capsules") {
            $unitType = 'loosely_count';
            $stock = $CurrentStock->showCurrentStocByUnit($productId, $unitType);
        } else {
            $unitType = 'qty';
            $stock = $CurrentStock->showCurrentStocByUnit($productId, $unitType);
        }


        $stockQty = 0;
        $looseQty = 0;

        if ($stock != NULL) {
            foreach ($stock as $row) {
                $stockQty += $row['qty'];
                $looseQty += $row['loosely_count'];
            }
        }

    ?>
        <div class="row mx-0 py-2 border-bottom p-row item-list" id="listed-items" tabindex="0" onclick="getDtls('<?php echo $productId ?>');">
            <div class="col-md-4"><?php echo $productName, $power ?><br>
                <small><?php echo $manufacturerName ?></small>
            </div>
            <div class="col-md-4">
                <small><?= $pComposition ?></small>
                <br>
                <small><?= $pComposition2 ?></small>
            </div>
            <div class="col-md-2"><small><?= $packOf ?></small></div>
            <div class="col-md-2"><small><?php echo $stockQty;
                                            if ($looseQty > 0) {
                                                echo "($looseQty)";
                                            }
                                            echo "" ?> </small></div>
        </div>
<?php

    }

} else {
    echo '<div class="row border-bottom border-primary small mx-0 mb-2">
    <label style="color: red;"><b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"Product Not Found / Check Spelling";</b></label>
    </div>';
}
?>