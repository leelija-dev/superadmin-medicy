<?php

$maxPurchasedDistAmount = json_decode($StockIn->maxPurchasedDistAmount($ADMINID));

$totalAmount = '';
$distributorName        = '';

if ($maxPurchasedDistAmount->status != 0) {
    $maxPurchasedDistAmount = $maxPurchasedDistAmount->data;
    $totalAmount = $maxPurchasedDistAmount->total;

    $distributorName = $Distributor->distributorName($maxPurchasedDistAmount->distributor_id);
}


//========================================================================
$maxItemPurchase = json_decode($StockIn->selectDistOnMaxItems($ADMINID));

$NosOfPurchased     = '';
$distNameOnMaxItem  = '';

if ($maxItemPurchase->status != 0) {
    $maxItemPurchase = $maxItemPurchase->data;
    $NosOfPurchased    = $maxItemPurchase->number_of_purchases;
    $distNameOnMaxItem = $Distributor->distributorName($maxItemPurchase->distributor_id);
}

?>

<div class="card shadow-sm h-100 py-2 pending_border animated--grow-in">
    <div class="px-3 mt-2">
        <p class="text-xs font-weight-bold text-info text-uppercase mb-1">
            Most purchased distributor
        </p>
        <?php if ($totalAmount > 0): ?> 
            <div class="row">
                <div class="col-12 col-sm-6 col-md-12 col-lg-6 mb-3">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        by amount
                    </div>
                    <div class="mb-0 font-weight-bold text-gray-800">
                        <label type="text" id="distName" name="distName"><?= $distributorName; ?></label>
                        <br>
                        <?= CURRENCY ?>
                        <label type="text" id="salesAmount" name="salesAmount"><?= $totalAmount; ?></label>
                    </div>
                </div>


                <div class="col-12 col-sm-6 col-md-12 col-lg-6 mb-3">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        by items
                    </div>
                    <div class="mb-0 font-weight-bold text-gray-800">
                        <label type="text" id="distName" name="distName"><?= $distNameOnMaxItem; ?></label>
                        <br>
                        <label type="text" id="itemCount" name="itemCount"><?= $NosOfPurchased; ?> Times</label>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="border border-secondary border-dashed py-5 mb-2">
                <h5 class="text-center text-gray-500 font-weight-bold">Purchase Not Found!</h5>
                <p class="small text-center text-decoration-underline mb-2"><a href="<?= URL ?>stock-in.php">Purchase Now</a> </p>
            </div>
        <?php endif; ?>

    </div>
</div>