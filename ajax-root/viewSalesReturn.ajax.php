<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'salesReturn.class.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'stockOut.class.php';
require_once CLASS_DIR.'packagingUnit.class.php';


// classes initiating 
$SalesReturn    = new SalesReturn();
$Patients       = new Patients();
$products       = new Products();
$StockOut       = new StockOut();
$Packeging      = new PackagingUnits();


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['invoice'])) {

        $SalesReturnid = $_GET['id'];
        $invoiceID = $_GET['invoice'];
        $activeStatus = '1';
        // echo "Sales return id : ",$SalesReturnid,"<br>Invoice id : ",$invoiceID;
        $returnBill = $SalesReturn->salesReturnByID($SalesReturnid);
        // echo "<br>";
        // print_r($returnBill); echo "Sales return Data from sales return table <br><br>";

        $patientId = $returnBill[0]['patient_id'];

        if ($patientId == "Cash Sales") {
            $patientName = "Cash Sales";
        } else {
            $patientName = $returnBill[0]['patient_id'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        .summary {
            margin-top: auto;
            min-height: 3rem;
            background: #af3636;
            align-items: center;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <p><b>Invoice No:</b> <span>#<?php echo $returnBill[0]['invoice_id']; ?></span></p>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <?php
                if ($patientId == "Cash Sales") {
                    $patientName = "Cash Sales";
                } else {
                    $patientId = $returnBill[0]['patient_id'];
                    $patient = $Patients->patientsDisplayByPId($patientId);
                    $patient = json_decode($patient);
                    $patientName = $patient->name;
                }

                ?>
                <p><b>Patient Name:</b> <?php echo $patientName; ?></p>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <p><b>Return Date:</b> <span><?php echo $returnBill[0]['return_date']; ?></span></p>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <p><b>Refund Mode:</b> <span><?php echo $returnBill[0]['refund_mode']; ?></span></p>
            </div>
        </div>
        <hr>
        <div class="table-responsive" style="min-height: 30vh;">
            <table class="table table-sm text-dark">
                <thead class="bg-primary text-light">
                    <tr>
                        <th>Invoice</th>
                        <th>Item</th>
                        <th>Batch</th>
                        <th>Exp Date</th>
                        <th>Weatage</th>
                        <th>Purchase Qty</th>
                        <th>Disc</th>
                        <th>GST</th>
                        <th>Return</th>
                        <th>taxable</th>
                        <th>Refund</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    //================ Invoice Details ===============
                    $table = 'sales_return_id';
                    $SalesReturnDetials = $SalesReturn->selectSalesReturnList($table, $SalesReturnid);
                    // print_r($SalesReturnDetials);
                    foreach ($SalesReturnDetials as $returnData) {
                        $itemId = $returnData['item_id'];
                        $weatage = $returnData['weatage'];
                        $discount = $returnData['disc'];; 
                        $gst = $returnData['gst'];
                        $returnqty = $returnData['return_qty'];
                        $taxable = $returnData['taxable'];
                        $refundAmount = $returnData['refund_amount'];
                        $returnedAt = $returnData['updated_on'];

                        $col1 = 'invoice_id';
                        $col2 = 'item_id';
                        $invoiceDetials = $StockOut->stokOutDetailsDataByTwoCol($col1, $invoiceID, $col2, $itemId);
                        foreach ($invoiceDetials as $invoiceData) {
                            $itemName = $invoiceData['item_name'];
                            $batchNo  = $invoiceData['batch_no'];
                            $exp_date = $invoiceData['exp_date'];
                            if($invoiceData['loosely_count'] == 0){
                                $purchaseQty = $invoiceData['qty'];
                            }else{
                                $purchaseQty = $invoiceData['loosely_count'];
                            }
                        }

                        $salesReturnData = json_decode($SalesReturn->selectReturnDetailsByColsAndTime($col1, $invoiceID, $col2, $itemId, $returnedAt));
                        // print_r($salesReturnData);
                        if($salesReturnData->status == 1){
                            $salesReturnItemDetails = $salesReturnData->data;
                            $salesRtn = 0; 
                    
                            foreach ($salesReturnItemDetails as $slsRtn) {
                                $salesRtn = intval($salesRtn) + intval($slsRtn->return_qty);
                            }
                            $purchaseQty =  (intval($purchaseQty) - intval($salesRtn));
                        }


                        if($invoiceData['loosely_count'] != 0){
                            $purchaseQty = $purchaseQty.'(L)';
                        }

                    ?>
                        <tr>
                            <th><?php echo $invoiceID ?></th>
                            <th><?php echo $itemName ?></th>
                            <th><?php echo $batchNo ?></th>
                            <th><?php echo $exp_date ?></th>
                            <th><?php echo $weatage ?></th>
                            <th><?php echo $purchaseQty ?></th>
                            <th><?php echo $discount ?></th>
                            <th><?php echo $gst ?></th>
                            <th><?php echo $returnqty ?></th>
                            <th><?php echo $taxable ?></th>
                            <th><?php echo $refundAmount ?></th>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="row p-2 p-md-0 summary rounded align-middle">
            <div class="col-6 col-sm-3">Items: <?php echo $returnBill[0]['items']; ?></div>
            <div class="col-6 col-sm-3">Quantity: <?php echo $returnBill[0]['items']; ?></div>
            <div class="col-6 col-sm-3">GST: <?php echo $returnBill[0]['gst_amount']; ?></div>
            <div class="col-6 col-sm-3">Amount: <?php echo $returnBill[0]['refund_amount']; ?></div>

        </div>

    </div>
</body>

</html>