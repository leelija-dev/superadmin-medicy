<?php
$checkDt = date('Y-m');

$currentStockData = $CurrentStock->showCurrentStockbyAdminId($adminId);
$currentStockDataForJS = json_encode($currentStockData);

$looseItemMrp = 0;
$nonLoseItemMrp = 0;
$totalItemMrp = 0;


$totalPTRofCurrentStock = 0;
$netCurrentMrp = 0;
$netCurrentPtr = 0;
$netSalesMargin = 0;

if ($currentStockData != null) {
    foreach ($currentStockData as $currentItemData) {

        /*====================== Current Stock By MRP ========================*/
        if (in_array(strtolower($currentItemData['unit']), LOOSEUNITS)) {
            $totalItemMrp += $currentItemData['loosely_price'] * $currentItemData['loosely_count'];
        } else {
            $totalItemMrp += floatval($currentItemData['mrp']) * intval($currentItemData['qty']);
        }
        /*--------------------End of Current Stock By MRP --------------------*/

        /*====================== Current Stock By PTR ========================*/
        $itemStockIndetailsData = $StockInDetails->stockInDetailsById($currentItemData['stock_in_details_id']);

        foreach ($itemStockIndetailsData as $itemStockIndetailsData) {

            $itemBasePrice  = $itemStockIndetailsData['base'];
            $weightage      = $itemStockIndetailsData['weightage'];
            $itemGst        = $itemStockIndetailsData['gst'];
            $freeqty        = $itemStockIndetailsData['free_qty'];
            $qty            = $itemStockIndetailsData['qty'];
            $totalLooseQty  = $itemStockIndetailsData['loosely_count'];

            if (in_array(strtolower($itemStockIndetailsData['unit']), LOOSEUNITS)) {

                $totalQty = $freeqty + $qty;

                // total purchased ptr without gst
                $totalPtr = $totalQty * $itemBasePrice;

                // total purchased price (without gst) is divided into total purchased lose qty
                $currentPTRofLQty = $totalPtr / $totalLooseQty;

                // current ptr of current quantity (without gst)
                $currentQTYPTR = $currentItemData['loosely_count'] * $currentPTRofLQty;

                // added gst into currentQTYPTR 
                $totalPTRofCurrentStock += floatval($currentQTYPTR) + (floatval($currentQTYPTR) * (floatval($itemGst) / 100));

            } else {
                $currentQTYPTR = $itemBasePrice * $currentItemData['qty'];
                $totalPTRofCurrentStock += floatval($currentQTYPTR) + (floatval($currentQTYPTR) * (floatval($itemGst) / 100));
            }
        }
        /*--------------------End of Current Stock By PTR --------------------*/

        $netSalesMargin = floatval($totalItemMrp) - floatval($totalPTRofCurrentStock);  // calculating total margin
    }
}



$currentStockExpItemData = $CurrentStock->showExpStockForStocksummaryCard($checkDt, $adminId);
// print_r($currentStockExpItemData);

$netCurrentMrpOfExpItems = 0;
$netCurrentPtrOfExpItems = 0;
$netCurrentMarginOfExpItems = 0;
if ($currentStockExpItemData != null) {
    foreach ($currentStockExpItemData as $currentExpItemData) {

        if (in_array(strtolower($currentExpItemData['unit']), LOOSEUNITS)) {
            $perQtyMrp = floatval($currentExpItemData['mrp']) / floatval($currentExpItemData['weightage']);
            $expItemMrp = floatval($perQtyMrp) * intval($currentExpItemData['loosely_count']);
        } else {
            $expItemMrp = floatval($currentItemData['mrp']) * intval($currentItemData['qty']);
        }

        $netCurrentMrpOfExpItems = floatval($netCurrentMrpOfExpItems) + floatval($expItemMrp); // calculating total mrp in curnt stock

        $itemStockIndetailsData = $StockInDetails->showStockInDetailsByStokinId($currentExpItemData['stock_in_details_id']);

        foreach ($itemStockIndetailsData as $itemStockIndetailsData) {
            $itemBasePrice = $itemStockIndetailsData['base'];
            $itemGst = $itemStockIndetailsData['gst'];
            $itemPtr = floatval($itemBasePrice) + (floatval($itemBasePrice) * (floatval($itemGst) / 100));

            if (in_array(strtolower($itemStockIndetailsData['unit']), LOOSEUNITS)) {
                $perQtyPtr = floatval($itemPtr) / intval($itemStockIndetailsData['weightage']);
                $perItemPtr = floatval($perQtyPtr) * intval($currentItemData['loosely_count']);
            } else {
                $perItemPtr = floatval($itemPtr) * intval($currentItemData['qty']);
            }

            $netCurrentPtrOfExpItems = floatval($netCurrentPtrOfExpItems) + floatval($perItemPtr);  // calculating total ptr in curnt stock
        }
        $netCurrentMarginOfExpItems = floatval($netCurrentMrpOfExpItems) - floatval($netCurrentPtrOfExpItems);  // calculating total margin
    }
}


?>
<div class="mb-4">
    <div class="card border-top-primary shadow-sm pending_border animated--grow-in">
        <div class="card-body">
            <div class="text-decoration-none">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Stock details
                        </div>
                        <div class="table-responsive" id="stocksummary-data-table">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Stock</th>
                                        <th scope="col">By MRP</th>
                                        <th scope="col">By PTR</th>
                                        <th scope="col">Margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="current-stock-data" onclick="goToStockCheck(this.id, '<?= URL; ?>')">
                                        <th scope="row">Current</th>
                                        <td><?php echo number_format($totalItemMrp, 2); ?></td>
                                        <td><?php echo number_format($totalPTRofCurrentStock, 2); ?></td>
                                        <td><?php echo number_format($netSalesMargin, 2); ?></td>

                                    </tr>
                                    <tr id="expiry-stock-data" onclick="goToStockCheck(this.id, '<?= URL; ?>')">
                                        <th scope="row">Expired</th>
                                        <td><?php echo number_format($netCurrentMrpOfExpItems, 2); ?></td>
                                        <td><?php echo number_format($netCurrentPtrOfExpItems, 2); ?></td>
                                        <td><?php echo number_format($netCurrentMarginOfExpItems, 2); ?></td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" id="stocksummary-no-data-found-div">
                            <label style="color: red;">no data found</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var chkCurrentStockData = JSON.stringify(<?php echo $currentStockDataForJS; ?>);

    var chkCurrentStockExpData = JSON.stringify(<?php echo json_encode($currentStockExpItemData) ?>);

    if (chkCurrentStockData != null && chkCurrentStockExpData != null) {
        document.getElementById('stocksummary-data-table').style.display = 'block';
        document.getElementById('stocksummary-no-data-found-div').style.display = 'none';
    } else {
        document.getElementById('stocksummary-data-table').style.display = 'none';
        document.getElementById('stocksummary-no-data-found-div').style.display = 'block';
    }
</script>