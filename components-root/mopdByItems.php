<?php

$maxItemPurchase = json_decode($StockIn->selectDistOnMaxItems($adminId));

$NosOfPurchased     = '';
$distNameOnMaxItem  = '';

if($maxItemPurchase->status != 0){
    $maxItemPurchase = $maxItemPurchase->data;
    $NosOfPurchased    = $maxItemPurchase->number_of_purchases ;
    $distNameOnMaxItem = $Distributor->distributorName($maxItemPurchase->distributor_id);
}

?>

<div class="card border-left-info shadow border-right-info h-100 py-2 pending_border animated--grow-in">
    <div class="card-body pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    most purchaed distributor by times</div>
                <div class="mb-0 font-weight-bold text-gray-800" id="mopdByItems-info-div">
                    <label type="text" id="itemCount" name="itemCount"><?= $NosOfPurchased; ?> Times</label><br>
                    <label type="text" id="distName" name="distName"><?= $distNameOnMaxItem; ?></label>
                </div>
                <div class="mb-0 font-weight-bold text-gray-800" id="mopdByItems-no-data-div">
                    <label for="no-data">NO DATA FOUND</label>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var mopdByItemData = <?php echo json_encode($maxItemPurchase); ?>;
    
    if(mopdByItemData != null){
        document.getElementById("mopdByItems-no-data-div").style.display = 'none';
        document.getElementById("mopdByItems-info-div").style.display = 'block';
    }else{
        document.getElementById("mopdByItems-no-data-div").style.display = 'block';
        document.getElementById("mopdByItems-info-div").style.display = 'none';
    }
</script>