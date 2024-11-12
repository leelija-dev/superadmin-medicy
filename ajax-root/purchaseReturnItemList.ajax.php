<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR."stockReturn.class.php";
require_once CLASS_DIR."distributor.class.php";
require_once CLASS_DIR."products.class.php";

$PurchaseReturn = new StockReturn();
$DistributorDetils = new Distributor();
$Product = new Products();

// getBatchList function for geting bill date
if (isset($_GET['return-id'])) {
    $returnId = $_GET['return-id'];
    // echo $returnId;
    $bill =  json_decode($PurchaseReturn->showStockReturnById($returnId));
    if($bill->status){
        $bill = $bill->data;
    }

    $totalQty = $bill[0]->total_qty;
    $dist = json_decode($DistributorDetils->showDistributorById($bill[0]->distributor_id));

    if($dist->status){
        $dist = $dist->data;
    }

    if ($bill != null) {
        // print_r($bill);
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <!-- Custom fonts for this template-->
            <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
            <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

            <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
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

            <!-- start container-fluid -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-3 col-sm-3">
                        <p><b> Distribubtor: </b><?php echo $dist->name; ?></p>
                    </div>
                    <div class="col-3 col-sm-3">
                        <p><b> Return Bill No: </b>#<?php echo $bill[0]->id; ?></p>
                    </div>
                    <div class="col-3 col-sm-3">
                        <p><b> Return Date: </b><?php echo date("d-m-Y", strtotime($bill[0]->return_date)); ?></p>
                    </div>
                    <div class="col-3 col-sm-3">
                        <p><b> Payment Mode: </b><?php echo $bill[0]->refund_mode; ?></p>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-sm table-hover" style="font-size:0.9rem;">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th>SL.</th>
                                <th>Item Name</th>
                                <th>Bill No</th>
                                <th>Batch</th>
                                <th>Exp.</th>
                                <th>Weatage</th>
                                <th>P.Qty</th>
                                <th>Free Qty</th>
                                <th>PTR</th>
                                <th>MRP</th>
                                <th>GST</th>
                                <th>Disc</th>
                                <th>Return Qty</th>
                                <th>Refund</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 0;
                            $rtnqty = 0;
                            $gst = 0;
                            $amount = 0;

                            $items = $PurchaseReturn->showStockReturnDetails($returnId);
                            // print_r($items);
                            // echo "<br><br>";
                            foreach ($items as $item) {

                                $sl     += 1;
                                $rtnqty    = $item['return_qty'];
                                $gst    += $item['gst'];
                                $amount += $item['ptr'];


                                // =========== edit req flag key check ==========
                                $prodCheck = json_decode($Product->productExistanceCheck($item['product_id']));
                                if ($prodCheck->status == 1) {
                                    $editReqFlag = 0;
                                } else {
                                    $editReqFlag = '';
                                }

                                //========================
                                $productData = json_decode($Product->showProductsByIdOnUser($item['product_id'], $adminId, $editReqFlag));

                                if($productData->status){
                                    $productData = $productData->data;
                                }
                                
                                foreach ($productData as $pData) {

                                    $name = $pData->name;
                                    
                                    $string1 = '(';
                                    $string2 = 'F';
                                    $string3 = ')';
                                    
                                }

                            if(empty($item['return_free_qty'])){
                                $rtnFreeQty = '';
                            }else{
                                $rtnFreeQty = ' ('.$item['return_free_qty'].'F)';
                            }
                            
                            echo "<tr>
                                <th scope='row'>" . $sl . "</th>
                                <td>" . $name . "</td>
                                <td>" . $item['dist_bill_no'] . "</td>
                                <td>" . $item['batch_no'] . "</td>
                                <td>" . $item['exp_date'] . "</td>
                                <td>" . $item['unit'] . "</td>
                                <td>" . $item['purchase_qty'] . "</td>
                                <td>" . $item['free_qty'] . "</td>
                                <td>" . $item['ptr'] . "</td>
                                <td>" . $item['mrp'] . "</td>
                                <td>" . $item['gst'] . "%</td>
                                <td>" . $item['disc'] . "%</td>
                                <td>" . $rtnqty . $rtnFreeQty. "</td>
                                <td>" . $item['refund_amount'] . "</td>
                            </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="row summary rounded align-middle">
                    <div class="col-6 col-sm-3">Items: <?php echo count($items); ?></div>
                    <div class="col-6 col-sm-3">Quantity: <?php echo $totalQty; ?></div>
                    <div class="col-6 col-sm-3">GST: <?php echo $bill[0]->gst_amount; ?></div>
                    <div class="col-6 col-sm-3">Amount: <?php echo $bill[0]->refund_amount; ?></div>

                </div>
            </div>
            <!-- end container-fluid -->


            <!-- Bootstrap Js -->
            <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
        </body>

        </html>
    <?php

    } else {
    ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <!-- Custom fonts for this template-->
            <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
            <!-- start container-fluid -->
            <div class="container-fluid">
                <div class="col-md-12">
                    <div class="table-responsive mb-3">

                        <table class="table table-sm table-hover" style="font-size:0.9rem;">
                            <thead class="bg-primary text-light">
                                <tr>
                                    <th style="text-align: center;">NO DATA FOUND</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: center;">ITEM DELETED</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
        </body>

        </html>

<?php
    }
}
?>