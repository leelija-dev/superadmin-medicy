<style>
    .searched-list:hover {
        background: #3e059b26;
        cursor: pointer;
    }
</style>

<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; // check admin logged in or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'search.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';

$CurrentStock = new CurrentStock();
$Manufacturer = new Manufacturer();
$Search       = new Search();
$PackagingUnits = new PackagingUnits();
$Products = new Products();
$Request = new Request();
$ItemUnit = new ItemUnit();

$searchResult = null;

if (isset($_GET['data'])) {
    $data = $_GET['data'];
    $resultData = $Products->selectItemLikeForStockInOut($data, $adminId);

    if ($resultData["status"]) {
        $resultData = $resultData['data'];
        ?>
        <div class="row border-bottom border-primary small mx-0 mb-2">
            <div class="col-md-4">Searched For</div>
            <div class="col-md-4">Composition</div>
            <div class="col-md-2">Unit/Pack</div>
            <div class="col-md-2">Stock</div>
        </div>
        <?php
        foreach ($resultData as $resultRow) {
            $productId = $resultRow['product_id'];
            $productName = $resultRow['name'];
            $pComposition1 = $resultRow['comp_1'] ?? '';
            $pComposition2 = $resultRow['comp_2'] ?? '';

            $prodReqStatus = $resultRow['prod_req_status'] ?? '';
            $oldProdFlag = $resultRow['old_prod_flag'] ?? '';
            $editReqFlag = $resultRow['edit_request_flag'] ?? '';

            $weightage = $resultRow['unit_quantity'];
            $unit = $resultRow['unit'];
            $unitDetials = $ItemUnit->itemUnitName($unit);

            $packagingType = $resultRow['packaging_type'];
            $packDetails = json_decode($PackagingUnits->showPackagingUnitById($packagingType));
            $packageType = $packDetails->status ? $packDetails->data->unit_name : '';

            $packOf = $weightage . $unitDetials . '/' . $packageType;

            $manufacturerName = '';
            if (isset($resultRow['manufacturer_id'])) {
                $manufacturerId = $resultRow['manufacturer_id'];
                $manufacturer = json_decode($Manufacturer->showManufacturerById($manufacturerId));
                $manufacturerName = ($manufacturer->status) ? $manufacturer->data->name : 'no data found';
            }

            $power = $resultRow['power'] ? ' | ' . $resultRow['power'] : '';

            $unitType = ($unit == "tablets" || $unit == "capsules") ? 'loosely_count' : 'qty';
            $stock = $CurrentStock->showCurrentStockByUnit($productId, $unitType, $adminId);

            $stockQty = $looseQty = 0;
            if ($stock) {
                foreach ($stock as $row) {
                    $stockQty += $row['qty'];
                    $looseQty += $row['loosely_count'];
                }
            }
            ?>
            <div class="row mx-0 py-2 border-bottom p-row item-list" id="listed-items" tabindex="0" onclick="getDetails('<?php echo $productId ?>', '<?php echo $prodReqStatus ?>', '<?php echo $oldProdFlag ?>', '<?php echo $editReqFlag ?>');">
                <div class="col-md-4"><?php echo $productName, $power ?><br>
                    <small><?php echo $manufacturerName ?></small>
                </div>
                <div class="col-md-4">
                    <small><?= $pComposition1 ?></small>
                    <br>
                    <small><?= $pComposition2 ?></small>
                </div>
                <div class="col-md-2"><small><?= $packOf ?></small></div>
                <div class="col-md-2"><small><?php echo $stockQty . ($looseQty > 0 ? "($looseQty)" : '') ?></small></div>
            </div>
            <input type="text" class="d-none" id="check-none" value="1">
            <?php
        }
    } else {
        echo '<div class="row border-bottom border-primary small mx-0 mb-2">
        <label style="color: red;"><b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"Product Not Found / Check Spelling";</b></label>
        </div>
        <input type="text" class="d-none" id="check-none" value="0">';
    }
}
?>
