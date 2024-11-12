<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR.'dbconnect.php';
require_once SUP_ADM_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'products.class.php';


$CurrentStock = new CurrentStock();
$Products = new Products();

$thisMonth = date('m');
$thisYear = date('Y');

$modifiedMnth = intval($thisMonth) + intval(2);
if($modifiedMnth > 12){
    $expMnth = $modifiedMnth % 12;
    $expYr = intval($thisYear) + 1;
}else{
    $expMnth = $modifiedMnth;
    $expYr = $thisYear;
}

$expRange = $expMnth.'/'.$expYr;

$showExpiry = $CurrentStock->showStockExpiry(NOW);
// print_r($showExpiry);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Expiring Items</title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.css" rel="stylesheet">

    <!-- Datatable Style CSS -->
    <link href="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include SUP_ROOT_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include SUP_ROOT_COMPONENT.'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <!-- <h1 class="h3 mb-4 text-gray-800">Blank Page</h1> -->

                    <!-- Showing Sell Items  -->
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header pt-3 pb-1 d-flex d-flex justify-content-between">
                            <p class="m-0 font-weight-bold text-primary">Expiring in <?php echo $expRange; ?></p>
                            <div class="d-flex justify-content-end">
                                <!-- <div class="input-group h-75 w-75">
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="input-group h-100 w-100">
                                        <input type="text" class="form-control h-100" placeholder="Expiring In"
                                            aria-label="Expiring In" aria-describedby="exp-search" name="exp">
                                        <div class="input-group-append h-100">
                                            <button style="padding: 0.2rem 0.5rem;"
                                                class="btn btn-sm btn-outline-secondary" type="submit" name="exp-search"
                                                id="exp-search"><i class="fas fa-search"></i></button>
                                        </div>
                                    </form>
                                </div> -->
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Batch</th>
                                            <th>Exp. Date</th>
                                            <th>Qty.</th>
                                            <th>L. Qty.</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($showExpiry as $item) {
                                            $productId    = $item['product_id'];
                                            $productDetails = $Products->showProductsById($productId);
                                            $prodName = $productDetails[0]['name'];
                                            $batch        = $item['batch_no'];
                                            $expDate      = $item['exp_date'];
                                            $qty          = $item['qty'];
                                            $lCount       = $item['loosely_count'];

                                            echo "<tr>
                                                    <td>".$prodName."</td>
                                                    <td>".$batch."</td>
                                                    <td>".$expDate."</td>
                                                    <td>".$qty."</td>
                                                    <td>".$lCount."</td>
                                                    <td>
                                                    <a class='' data-toggle='modal' data-target='#manufacturerModal' onclick='viewSoldList(".$productId.")'><i class='fas fa-edit'></i></a>

                                                    <a class='ms-2' id='delete-btn' data-id=".$productId."><i class='far fa-trash-alt'></i></a>
                                                </td>
                                                </tr>";

                                        }
                                        
                                       ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End of Showing Sell Items  -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once SUP_ROOT_COMPONENT.'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <script src="<?= PLUGIN_PATH ?>product-table/jquery.dataTables.js"></script>
    <script src="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script>

</body>

</html>