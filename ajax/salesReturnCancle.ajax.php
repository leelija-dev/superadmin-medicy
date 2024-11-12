<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'salesReturn.class.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'stockOut.class.php';
require_once CLASS_DIR.'currentStock.class.php';


// classes initiating 
$SalesReturn    = new SalesReturn();
$Patients       = new Patients();
$products       = new Products();
$stockOut       = new StockOut();
$currentStock   = new CurrentStock();

if (isset($_POST['id'])) {

    $added_by = $_SESSION['employee_username'];
    $SalesReturnId = $_POST['id'];
    $status = "0";

    $salesRetunTableData = $SalesReturn->salesReturnByID($SalesReturnId);
    $invoiceId = $salesRetunTableData[0]['invoice_id'];

    $updateStatus = $SalesReturn->updateStatus($SalesReturnId, $status, $added_by);
    $updateStatus = true;

    if ($updateStatus == true) {

        $attribute = "sales_return_id";
        $data = $SalesReturnId;

        //fetch data from sales return details table
        $selectReturnDetails = $SalesReturn->selectSalesReturnList($attribute, $data);

        foreach ($selectReturnDetails as $selectReturnDetails) {

            $salesReturnDetailsId = $selectReturnDetails['id'];
            $curretnStockItemId = $selectReturnDetails['item_id'];
            $productId = $selectReturnDetails['product_id'];;
            $batchNo = $selectReturnDetails['batch_no'];
            $returnsQty = $selectReturnDetails['return_qty'];

            $checkCurrentSotck = $currentStock->showCurrentStocById($curretnStockItemId);
            foreach ($checkCurrentSotck as $checkCurrentSotck) {
                $currentStockQty = $checkCurrentSotck['qty'];
                $currentStockLooselyCount =  $checkCurrentSotck['loosely_count'];
            }

            $table1 = 'sales_return_id';
            $table2 = 'item_id';
            $salesReturnDetailsData = $SalesReturn->seletReturnDetailsBy($table1, $SalesReturnId, $table2, $curretnStockItemId);
            foreach ($salesReturnDetailsData as $salesReturnDetailsData) {
                $itemSetOf = $salesReturnDetailsData['weatage'];
                $itemUnit = preg_replace("/[0-9]/", "", $itemSetOf);
                $itemWeatage = preg_replace("/[a-z]/", "", $itemSetOf);
                $returnsQty = $salesReturnDetailsData['return_qty'];

                if ($itemUnit == 'tablets' || $itemUnit == 'capsules') {
                    $looselyCount = $returnsQty;
                } else {
                    $wholeCount = $returnsQty;
                }

                if ($itemUnit == 'tablets' || $itemUnit == 'capsules') {
                    $updatedLooselyCount = intval($currentStockLooselyCount) - intval($looselyCount);
                    $updatedQty = intdiv($updatedLooselyCount, $itemWeatage);
                }else{
                    $updatedQty = intval($currentStockQty) - intval($wholeCount);
                    $updatedLooselyCount = 0;
                }
                

                $stockUpdate = $currentStock->updateStockOnSell($curretnStockItemId, $updatedQty, $updatedLooselyCount);

                // sales return update. set return qty  0 and refund amount 0;
                // $setReturnQty = 0;
                // $setRefundAmount = 0;
                // $updateSalesRetunDetails = $SalesReturn->updateSalesReturnOnReturnCancel($salesReturnDetailsId, $setReturnQty, $setRefundAmount);

                $deleteReturnDetails = $SalesReturn->deleteSalesReturnDetaislById($salesReturnDetailsId);
            }
        }
    }

    //==========================================
    if ($stockUpdate == true) {
        echo 1;
    }else{
        echo 0;
    }
}
