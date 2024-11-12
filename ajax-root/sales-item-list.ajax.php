<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . "dbconnect.php";
require_once CLASS_DIR . 'search.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'products.class.php';

$CurrentStock = new CurrentStock();
$Manufacturer = new Manufacturer();
$Products     = new Products;
$ItemUnit     = new ItemUnit;

$searchResult = FALSE;

if (isset($_GET['data'])) {
    $data = $_GET['data'];

    $col = 'admin_id';
    $searchResult = $Products->selectItemLikeForStockInOut($data, $adminId);
    // print_r($searchResult);
}
if ($searchResult && $searchResult['status']) {
    $searchResult = $searchResult['data'];
?>
    <div class="row mx-2 p-1 text-muted border-bottom">
        <div class="col-md-6">Searched For</div>
        <div class="col-md-3">Unit/Pack</div>
        <div class="col-md-3">Stock</div>
    </div>
    <?php
    foreach ($searchResult as $resultRow) {
        $productId  = $resultRow['product_id'];
        $productName = $resultRow['name'];
        $weightage   = $resultRow['unit_quantity'];
        $unit        = $resultRow['unit'];

        $itemUnitName   = $ItemUnit->itemUnitName($unit);
        $packOf         = $weightage . '/' . $itemUnitName;

        $manufacturerName = '';
        if (isset($resultRow['manufacturer_id'])) {
            $manufacturer = json_decode($Manufacturer->showManufacturerById($resultRow['manufacturer_id']), true);
            $manufacturerName = ($manufacturer['status'] == '1') ? $manufacturer['data']['name'] : 'No Manufacturer Data found';
        }

        $power = ($resultRow['power'] != NULL) ? ' | ' . $resultRow['power'] : '';

        $col1 = 'product_id';
        $col2 = 'admin_id';
        $stock = $CurrentStock->showCurrentStocByTwoCol($col1, $productId, $col2, $adminId);
        $stockQty = $looseQty = 0;

        if ($stock) {
            foreach ($stock as $row) {
                $stockQty += $row['qty'];
                $looseQty += $row['loosely_count'];
            }
        }
    ?>
        <div class="row mx-2 p-1 border-bottom searched-list" id="<?php echo $productId ?>" value1="<?php echo $productName ?>" value2="<?php echo $stockQty ?>" onclick="itemsBatchDetails('<?php echo $productId ?>','<?php echo $productName ?>','<?php echo $stockQty ?>');">
            <div class="col-md-6"><?= $productName . $power ?><br>
                <small><?= $manufacturerName ?></small>
            </div>
            <div class="col-md-3"><small><?= $packOf ?></small></div>
            <div class="col-md-3"><small><?= $stockQty . ($looseQty > 0 ? "($looseQty)" : ''); ?> </small></div>
        </div>
<?php
    }
} else {
    echo '
    <div class="row mx-2 p-1 border-bottom searched-list text-danger justify-content-center text-center">
    No data found
</div>

';
}

?>