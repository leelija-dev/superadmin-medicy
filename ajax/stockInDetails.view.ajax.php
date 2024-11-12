<?php
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR."stockIn.class.php";
require_once CLASS_DIR."stockInDetails.class.php";
require_once CLASS_DIR."distributor.class.php";
require_once CLASS_DIR."products.class.php";


// CLASS INITIATING
$StockIn        = new StockIn();
$StockInDetails = new StockInDetails();
$Distributor    = new Distributor();
$Products       = new Products();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <!-- <link href="../css/sb-admin-2.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <style>
    .container-fluid {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

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

</head>

<body class="mx-0">

    <!-- start container-fluid -->
    <div class="container-fluid">
        <?php
        if ($_GET['distBill']) {

            $StockIn = $StockIn->showStockInById($_GET['distBill']);
            // print_r($StockIn[0]);

            $distributor = json_decode($Distributor->showDistributorById($StockIn[0][1]));
            $distributor = $distributor->data;
            // print_r();

        ?>
        <div class="row">
            <div class="col-6 col-sm-4">
                <p><b> Distribubtor: </b><?php echo $distributor[0]->name; ?></p>
                <p><b> Dist. Bill No: </b><?php echo $StockIn[0][2]; ?></p>
            </div>
            <div class="col-6 col-sm-4">
                <p><b> Bill Date: </b><?php echo $StockIn[0][5]; ?></p>
                <p><b> Due Date: </b><?php echo $StockIn[0][6]; ?></p>
            </div>
            <div class="col-6 col-sm-4">
                <p><b> Payment Mode: </b><?php echo $StockIn[0][7]; ?></p>
                <p>Action</p>
            </div>
        </div>
<hr>
        <div class="table-responsive my-3">

            <table class="table table-sm table-hover" style="font-size:0.9rem;">
                <thead class="bg-primary text-light">
                    <tr>
                        <th>SL.</th>
                        <th>Item Name</th>
                        <th>Batch</th>
                        <th>Exp.</th>
                        <th>Weatage</th>
                        <th>Unit</th>
                        <th>Qty.</th>
                        <th>F.Qty</th>
                        <th>Disc.</th>
                        <th>Base</th>
                        <th>GST</th>
                        <th>PTR</th>
                        <th>Margin</th>
                        <th>MRP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                $sl = 0;
                $qty = 0;
                $gst = 0;
                $amount = 0;

                $items = $StockInDetails->showStockInDetailsById($_GET['distBill']);
                // print_r($items);
                foreach ($items as $item) {
                    $sl     += 1;
                    $qty    += $item['qty'];
                    $gst    += $item['gst'];
                    $amount += $item['ptr'];

                    $product = json_decode($Products->showProductsById($item['product_id']));
                    $product = $product->data;
                    // print_r($product);

                    $pName = $product[0]->name;
                    
                    echo "<tr>
                            <th scope='row'>".$sl."</th>
                            <td>".$pName."</td>
                            <td>".$item['batch_no']."</td>
                            <td>".$item['exp_date']."</td>
                            <td>".$item['weightage']."</td>
                            <td>".$item['unit']."</td>
                            <td>".$item['qty']."</td>
                            <td>".$item['free_qty']."</td>
                            <td>".$item['discount']."%</td>
                            <td>".$item['base']."</td>
                            <td>".$item['gst']."</td>
                            <td>".$item['ptr']."</td>
                            <td>".$item['margin']."</td>
                            <td>".$item['mrp']."</td>
                          </tr>";
                }
                ?>
                </tbody>
            </table>
        </div>

        <div class="row summary rounded align-middle">
            <div class="col-6 col-sm-3">Items: <?php echo count($items);?></div>
            <div class="col-6 col-sm-3">Quantity: <?php echo $qty;?></div>
            <div class="col-6 col-sm-3">GST: <?php echo $StockIn[0]['gst'];?></div>
            <div class="col-6 col-sm-3">Amount: <?php echo $StockIn[0]['amount'];?></div>

        </div>

        <?php
        }
        ?>
    </div>
    <!-- end container-fluid -->


    <!-- Bootstrap core JavaScript-->
    <!-- <script src="../vendor/jquery/jquery.min.js"></script> -->
    <!-- <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->

    <!-- Bootstrap Js -->
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <!-- <script src="../../js/bootstrap-js-5/bootstrap.min.js"></script> -->

    <!-- Core plugin JavaScript-->
    <!-- <script src="../vendor/jquery-easing/jquery.easing.min.js"></script> -->

    <!-- Custom scripts for all pages-->
    <!-- <script src="../js/sb-admin-2.min.js"></script> -->

</body>

</html>