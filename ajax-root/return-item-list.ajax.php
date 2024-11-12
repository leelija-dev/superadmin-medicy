<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'stockReturn.class.php';
require_once CLASS_DIR . 'distributor.class.php';



$StockIn        = new StockIn();
$StockInDetails = new StockInDetails();
$Products       = new Products();
$CurrentStock   = new CurrentStock();
$StockReturn    = new StockReturn();
$Distributor    = new Distributor();


// getItemList function
if (isset($_GET['dist-id'])) {
?>
    <div class="row border-bottom border-primary small mx-0 mb-2">
        <div class="col-3 mb-1">Name</div>
        <div class="col-2 mb-1">Batch</div>
        <div class="col-2 mb-1">Expiry</div>
        <div class="col-2 mb-1">MRP</div>
        <div class="col-2 mb-1">PTR</div>
        <div class="col-1 mb-1">Stock</div>
    </div>
    <?php


    $details = json_decode($StockInDetails->stockInDetailsDataForReturnFilter($_GET['dist-id'], $ADMINID));
    // print_r($details);
    if ($details->status) {
        $details = $details->data;

        $rowCoutn = 0;
        foreach ($details as $detail) {
            // print_r($detail);
            $rowCoutn++;

            $stokInDetailsId = $detail->id;
            $stockInId = $detail->stokIn_id;

            $table = 'id';
            $stockInData = $StockIn->stockInByAttributeByTable($table, $stockInId);
            // print_r($stockInData);
            $billDate = $stockInData[0]['bill_date'];

            $billNumber = $detail->distributor_bill;
            $batchNo = $detail->batch_no;
            $productId = $detail->product_id;
            $ptr = $detail->ptr;
            $mrp = $detail->mrp;
            $expDate = $detail->exp_date;
            

            // =========== edit req flag key check ==========
            $prodCheck = json_decode($Products->productExistanceCheck($productId));
            if ($prodCheck->status == 1) {
                $editReqFlag = 0;
            } else {
                $editReqFlag = '';
            }
            //========================

            $product = json_decode($Products->showProductsByIdOnUser($productId, $adminId, $editReqFlag));
            $product = $product->data;
            $productName = $product[0]->name;


            $stoks = $CurrentStock->checkStock($productId, $batchNo);
            // print_r($stoks);
            if ($stoks != null) {
                $qantity = $stoks[0]['qty'];
            } else {
                $qantity = 0;
            }

    ?>

            <div class='row mx-0 py-2 border-bottom p-row item-list' id="divId_<?php echo $rowCoutn; ?>" onclick='getItemDetails(`<?php echo $stockInId; ?>`, `<?php echo $stokInDetailsId; ?>`, `<?php echo $batchNo; ?>`, `<?php echo $productId; ?>`, `<?php echo $productName; ?>`, `<?php echo $billDate; ?>`, `<?php echo $billNumber; ?>`, this)'>
                <div class="col-2 mb-0" hidden><?php echo $stockInId; ?></div>
                <div class="col-2 mb-0" hidden><?php echo $stokInDetailsId; ?></div>
                <div class="col-3 mb-0"><?php echo $productName; ?></div>
                <div class="col-2 mb-0"><?php echo $batchNo; ?></div>
                <div class="col-2 mb-0"><?php echo $expDate; ?></div>
                <div class="col-2 mb-0"><?php echo $mrp; ?></div>
                <div class="col-2 mb-0"><?php echo $ptr; ?></div>
                <div class="col-1 mb-0"><?php echo $qantity; ?></div>
            </div>

<?php


        }
    } else {
        echo '<div class="row border-bottom border-primary small mx-0 mb-2">
            <p>No Items Found</p>
        <small class="text-danger">Please Make sure that you search for correct distributor.</small>
    </div>';
    }
}
?>

<!-- =============================== GO TO purchaseReturnItemList.ajax.php page ======================== -->
<!-- <?php


        // getBatchList function for geting bill date
        // if (isset($_GET['return-id'])) {
        //     $returnId = $_GET['return-id'];


        //     $bill =  $StockReturn->showStockReturnById($returnId);
        //     $dist = $Distributor->showDistributorById($bill[0]["distributor_id"]);


        // 
        ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        Custom fonts for this template
        <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <link rel="stylesheet" href="../../css/bootstrap 5/bootstrap.css">
        <style>
            .container-fluid {
                display: flex;
                flex-direction: column;
                min-height: 50vh;
            }

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

    <body class="mx-0">

       start container-fluid 
        <div class="container-fluid">

            <div class="row">
                <div class="col-3 col-sm-3">
                    <p><b> Distribubtor: </b><?php echo $dist[0]['name']; ?></p>
                </div>
                <div class="col-3 col-sm-3">
                    <p><b> Return Bill No: </b>#<?php echo $bill[0]['id']; ?></p>
                </div>
                <div class="col-3 col-sm-3">
                    <p><b> Return Date: </b><?php echo date("d-m-Y", strtotime($bill[0]["return_date"])); ?></p>
                </div>
                <div class="col-3 col-sm-3">
                    <p><b> Payment Mode: </b><?php echo $bill[0]['refund_mode']; ?></p>
                </div>
            </div>
            <div class="table-responsive mb-3">

                <table class="table table-sm table-hover" style="font-size:0.9rem;">
                    <thead class="bg-primary text-light">
                        <tr>
                            <th>SL.</th>
                            <th>Item Name</th>
                            <th>Batch</th>
                            <th>Exp.</th>
                            <th>Weatage</th>
                            <th>P.Qty</th>
                            <th>Free Qty</th>
                            <th>PTR</th>
                            <th>MRP</th>
                            <th>GST</th>
                            <th>PTR.Amount</th>
                            <th>Return Qty</th>
                            <th>Refund</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 0;
                        $qty = 0;
                        $gst = 0;
                        $amount = 0;

                        $items = $StockReturn->showStockReturnDetails($returnId);
                        //print_r($items);
                        foreach ($items as $item) {
                            $sl     += 1;
                            $qty    += $item['return_qty'];
                            $gst    += $item['gst'];
                            $amount += $item['ptr'];

                            // $product = $Products->showProductsById($item['product_id']);

                            //print_r($product);
                            echo "<tr>
                            <th scope='row'>" . $sl . "</th>
                            <td>" . $product[0][3] . "</td>
                            <td>" . $item['batch_no'] . "</td>
                            <td>" . $item['exp_date'] . "</td>
                            <td>" . $item['unit'] . "</td>
                            <td>" . $item['purchase_qty'] . "</td>
                            <td>" . $item['free_qty'] . "</td>
                            <td>" . $item['ptr'] . "</td>
                            <td>" . $item['mrp'] . "</td>
                            <td>" . $item['gst'] . "%</td>
                            <td>" . $item['purchase_amount'] . "</td>
                            <td>" . $item['return_qty'] . "</td>
                            <td>" . $item['refund_amount'] . "</td>
                          </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="row summary rounded align-middle">
                <div class="col-6 col-sm-3">Items: <?php echo count($items); ?></div>
                <div class="col-6 col-sm-3">Quantity: <?php echo count($items) ?></div>
                <div class="col-6 col-sm-3">GST: <?php echo $bill[0]['gst_amount']; ?></div>
                <div class="col-6 col-sm-3">Amount: <?php echo $bill[0]['refund_amount']; ?></div>

            </div>

        </div>
        <!-- end container-fluid 


        <!-- Bootstrap Js 
        <script src="../../js/bootstrap-js-5/bootstrap.js"></script>
    </body>

    </html>

<?php
// }
?> -->
<!-- ========================== END OF GO TO purchaseReturnItemList.ajax.php page ======================== -->

<?php
//##################################################################################################
#                                                                                                  #
#                                              Sales Returns                                       #
#                                                                                                  #
####################################################################################################

if (isset($_GET['invoice'])) {
    require_once CLASS_DIR . "search.class.php";
    require_once CLASS_DIR . "stockOut.class.php";
    require_once CLASS_DIR . "patients.class.php";

    $Search     = new Search();
    $Patients   = new Patients();
    $StockOut   = new StockOut;

    $table  = 'stock_out';
    $column1 = 'invoice_id';
    $data1   = $_GET['invoice'];
    $column2 = 'admin_id';

    $invoiceDetail = $StockOut->stokOutDataByTwoCol($column1, $data1, $column2, $adminId);
    // print_r($invoiceDetail);

    if (count($invoiceDetail) > 0) {
        foreach ($invoiceDetail as $invoice) {
            if ($invoice['customer_id'] == 'Cash Sales') {
                //$patient = $Patients->patientsDisplayByPId($invoice['customer_id']);
                $patientId = "Cash Sales";
?>
                <div class='invoice-item' onclick="getInvoiceDetails('<?php echo $invoice['invoice_id'] ?>','<?php echo $patientId ?>' );">
                    <p><?php echo $patientId ?></p>
                    <small><span class='text-dark'><?php echo $invoice['invoice_id'] ?></span></small>
                </div>
            <?php

            } else {
                $patient = $Patients->patientsDisplayByPId($invoice['customer_id']);
                $patient = json_decode($patient);
                // print_r($patient);

                $patientId = $patient->patient_id;
            ?>
                <div class='invoice-item' onclick="getInvoiceDetails('<?php echo $invoice['invoice_id'] ?>', '<?php echo $patientId ?>')" ;>
                    <p><?php echo $patient->name ?></p>
                    <small><span class='text-dark'>#<?php echo $invoice['invoice_id'] ?></span> M: <?php echo $patient->phno ?></small>
                </div>
<?php

            }
        }
    } else {
        echo '<div class="invoice-item">
                <p>No Invoice Found</p>
                <small class="text-danger">Please Make sure that you are searching for correct invoice.</small>
            </div>';
    }
}

?>