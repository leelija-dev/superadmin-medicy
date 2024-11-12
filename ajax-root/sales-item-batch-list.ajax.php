<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . "dbconnect.php";
require_once CLASS_DIR . 'search.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'products.class.php';

// Check if product ID is set
if (!isset($_GET['prodId'])) {
    echo "Result Not Found";
    exit; // Return early
}

$productID = $_GET['prodId'];

$CurrentStock = new CurrentStock();
$Manufacturer = new Manufacturer();
$Search       = new Search();
$Products     = new Products();

// Fetch product batch data
$ProductBatchData = $CurrentStock->selectBatch($productID, $adminId);
$ProductBatchData = json_decode($ProductBatchData);

// Check if product batch data is available
if (!$ProductBatchData) {
    echo "Result Not Found";
    exit; // Return early
}
?>

<div class="row mx-2 p-1 text-muted border-bottom" style="max-width: 20rem;">
    <div class="col-md-6">Batch no</div>
    <div class="col-md-6">Stock</div>
</div>

<?php foreach ($ProductBatchData as $itemData): ?>
    <?php
    $productId = $itemData->product_id;
    $id = $itemData->id;
    $prodBatch = $itemData->batch_no;
    $qantity = $itemData->qty;
    $looseQty = $itemData->loosely_count;
    $weightage = $itemData->weightage;
    $unit = $itemData->unit;
    $packOf = $weightage . '/' . $unit;

    // Fetch product name
    $prodNameFetch = $Products->showProductsById($productId);
    $prodNameFetch = json_decode($prodNameFetch, true);
    $prodName = isset($prodNameFetch['status']) && $prodNameFetch['status'] == '1' ? $prodNameFetch['data']['name'] : 'No Data Found';
    ?>

    <div class="row mx-2 p-1 border-bottom searched-list" style="max-width: 20rem;" id="<?= $productId ?>" value="<?= $prodBatch ?>" value1="<?= $id ?>" onclick="stockDetails('<?= $productId ?>','<?= $prodBatch ?>', '<?= $id ?>', this.id, this.value, this.value1);">
        <div class="col-md-6"><?= $prodBatch ?></div>
        <div class="col-md-6"><?= $qantity . ($looseQty > 0 ? "($looseQty)" : "") ?></div>
    </div>
<?php endforeach; ?>
