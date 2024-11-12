<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . "stockIn.class.php";
require_once CLASS_DIR . "stockInDetails.class.php";
require_once CLASS_DIR . "distributor.class.php";
require_once CLASS_DIR . "products.class.php";


// CLASS INITIATING
$StockIn        = new StockIn();
$StockInDetails = new StockInDetails();
$Distributor    = new Distributor();
$Products       = new Products();

if (isset($_GET['id'])) {
    $purchaseId =  $_GET['id'];

    $StockIn = $StockIn->selectStockInById($purchaseId);
    $distributorData = json_decode($Distributor->showDistributorById($StockIn['distributor_id']));
    if ($distributorData->status == 1) {
        $distributor = $distributorData->data;
        $distributorName = $distributor->name;
    } else {
        $distributorName = '';
    }
}

?>

<style>
    .summary {
        margin-top: auto;
        min-height: 5rem;
        background: #af3636;
        align-items: center;
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-6 col-sm-4">
            <p><b> Distribubtor: </b><?= $distributorName; ?></p>
            <p><b> Dist. Bill No: </b><?= $StockIn['distributor_bill']; ?></p>
        </div>
        <div class="col-6 col-sm-4">
            <p><b> Bill Date: </b><?= $StockIn['bill_date']; ?></p>
            <p><b> Due Date: </b><?= $StockIn['due_date']; ?></p>
        </div>
        <div class="col-6 col-sm-4">
            <p><b> Payment Mode: </b><?= $StockIn['payment_mode']; ?></p>
        </div>
    </div>
    <hr>
    <div class="table-responsive">

        <table class="table table-sm table-hover" style="font-size:0.9rem;">
            <thead class="bg-primary text-light">
                <tr>
                    <th>SL.</th>
                    <th>Item Name</th>
                    <th>Batch</th>
                    <th>Exp.</th>
                    <th>Qty.</th>
                    <th>F.Qty</th>
                    <th>MRP</th>
                    <th>PTR</th>
                    <th>D.Price</th>
                    <th>GST</th>
                    <th>Base</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 0;
                $qty = 0;
                $gst = 0;
                $disc = 0;
                $ptr = 0;
                $totalQty = 0;
                $itemAmount = 0;

                $items = $StockInDetails->showStockInDetailsByStokId($purchaseId);
                // print_r($items);
                foreach ($items as $item) {
                    $sl         += 1;
                    $totalQty   += ($item['qty'] + $item['free_qty']);

                    // =========== edit req flag key check ==========
                    $prodCheck = json_decode($Products->productExistanceCheck($item['product_id']));
                    if ($prodCheck->status == 1) {
                        $editReqFlag = 0;
                    } else {
                        $editReqFlag = '';
                    }
                    //===============================================
                    $product = json_decode($Products->showProductsByIdOnUser($item['product_id'], $adminId, $editReqFlag));
                    $product = $product->data;
                    $pName = $product[0]->name;

                    echo "<tr>
                            <th scope='row'>" . $sl . "</th>
                            <td>" . $pName . "<br><small>" . $item['weightage'] . " " . $item['unit'] . "</small></td>
                            <td>" . $item['batch_no'] . "</td>
                            <td>" . $item['exp_date'] . "</td>
                            <td>" . $item['qty'] . "</td>
                            <td>" . $item['free_qty'] . "</td>
                            <td>" . $item['mrp'] . "</td>
                            <td>" . $item['ptr'] . "</td>
                            <td>" . $item['d_price'] . " <small><span class='badge badge-pill badge-primary'>" . $item['discount'] . "%</span></small></td>
                            <td>" . $item['gst'] . "</td>
                            <td>" . $item['base'] . "</td>
                            <td>" . $item['amount'] . "</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="row summary rounded align-middle">
        <div class="col-6 col-sm-3">Items: <?= count($items); ?></div>
        <div class="col-6 col-sm-3">Quantity: <?= $totalQty; ?></div>
        <div class="col-6 col-sm-3">GST: <?= $StockIn['gst']; ?></div>
        <div class="col-6 col-sm-3">Sub Total: <?= $StockIn['amount']; ?></div>

    </div>
</div>