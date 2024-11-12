<?php

$maxPurchasedDistAmount = json_decode($StockIn->maxPurchasedDistAmount());
$totalAmount = '';
$distributorName        = '';

if ($maxPurchasedDistAmount->status != 0) {
    $maxPurchasedDistAmount = $maxPurchasedDistAmount->data;
    $totalAmount = $maxPurchasedDistAmount->total;

    $distributorName = $Distributor->distributorName($maxPurchasedDistAmount->distributor_id);
}

?>

<div class="card border-left-info shadow border-right-info h-100 py-2 pending_border animated--grow-in">
    <div class="card-body pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    most purchaed distributor by amount</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800" id="mopdByAmount-info-div">
                    <i class="fas fa-rupee-sign"></i>
                    <label type="text" id="salesAmount" name="salesAmount"><?= $totalAmount; ?></label>
                    <br>
                    <label type="text" id="distName" name="distName"><?= $distributorName; ?></label>
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800" id="mopdByAmount-no-data-div" style="display: none;">
                    <label for="no-data">NO DATA FOUND</label>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var mopdByAmountData = <?php echo json_encode($totalAmount); ?>;

    if (mopdByAmountData != null) {
        document.getElementById("mopdByAmount-no-data-div").style.display = 'none';
        document.getElementById("mopdByAmount-info-div").style.display = 'block';
    } else {
        document.getElementById("mopdByAmount-no-data-div").style.display = 'block';
        document.getElementById("mopdByAmount-info-div").style.display = 'none';
    }
</script>