<!-- <?php

require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockIn.class.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'stockReturn.class.php';
require_once CLASS_DIR.'distributor.class.php';



$StockIn        = new StockIn();
$StockInDetails = new StockInDetails();
$Products       = new Products();
$CurrentStock   = new CurrentStock();
$StockReturn    = new StockReturn();
$Distributor    = new Distributor();

// getBillList function
if (isset($_GET['dist-id'])) {
?>
    <div class="row mx-2 p-1 text-muted border-bottom" style="max-width: 20rem;">
        <div class="col-md-9">Bill No</div>
    </div>

    <?php
    // $distributorId = $_GET['dist-id'];
    // $col1 = 'distributor_id';
    // $col2 = 'admin_id';
    // $details = $StockIn->stockInColumns($col1, $distributorId, $col2, $adminId);
    
    // foreach ($details as $details) {
        
    //     $billNo = $details['distributor_bill'];
    ?>

        <div class="row mx-2 p-1 border-bottom item-list" onclick="getItemList('<?php echo $distributorId; ?>','<?php echo $billNo; ?>');">
            <div class="col-md-9" style="min-width: 100%;"><?php echo $billNo; ?></div>
        </div>
<?php
//     }
// } else {
    ?>
    <div class="col-md-9" style="min-width: 100%;"><?php echo "No Bill Found!"; ?></div>
    <?php
}
?> -->