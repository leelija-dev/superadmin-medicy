<?php 
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'/config/sessionCheck.php';//check admin loggedin or not
require_once '../php_control/appoinments.class.php';
require_once '../php_control/currentStock.class.php';
require_once '../php_control/stockOut.class.php';
require_once '../php_control/stockIn.class.php';
require_once '../php_control/stockInDetails.class.php';


$page = "dashboard";

$appoinments       = new Appointments();
$CurrentStock      = new CurrentStock();
$StockOut          = new StockOut();
$StockIn           = new StockIn();
$StockInDetails    = new StockInDetails();

$totalAppointments = $appoinments->appointmentsDisplay();

$today = date("Y-m-d"); 

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pharmacist - Medicy Health Care</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="../css/custom/custom-form-style.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom-dashboard.css">


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include PORTAL_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Tobbar-->
                <?php include PORTAL_COMPONENT.'topbar.php'; ?>
                <!-- End of Tobbar-->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div> -->

                    <!-- Content Row -->
                    
                    <!-- ================ FIRST ROW ================ -->
                    <div class="row">

                        <!-- Sold By Card  -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once "partials/soldby.php"; ?>
                        </div>

                        <!-- Expiring in 3 Months Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once "partials/expiring.php"; ?>
                        </div>

                        <!----------- Sales of the day card ----------->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once "partials/salesoftheday.php"; ?>
                        </div>
                        
                        <!----------- Purchase today card ----------->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once "partials/purchasedToday.php"; ?>
                        </div>
                       
                    </div>
                    <!-- ================ END FIRST ROW ================ -->
                    
                    <!-- ================== SECOND ROW ================== -->
                    <div class="row">
                        <div class="col-xl-6 col-md-12">
                            <!------------- NEEDS TO COLLECT PAYMENTS -------------->
                            <div class="mb-4">
                                <div class="card border-top-primary pending_border animated--grow-in">
                                    <div class="card-body">
                                        <a class="text-decoration-none" href="#">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div
                                                        class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                        Sales Margin
                                                    </div>
                                                    <div class="table-responsive">

                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Item Name</th>
                                                                    <th scope="col">Pack</th>
                                                                    <th scope="col">MRP</th>
                                                                    <th scope="col">Margin</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <th scope="row">Current</th>
                                                                    <td>00.00</td>
                                                                    <td>00.00</td>
                                                                    <td>00.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Expired</th>
                                                                    <td>00.00</td>
                                                                    <td>00.00</td>
                                                                    <td>00.00</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!------------- END NEEDS TO COLLECT PAYMENTS -------------->
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <!------------- NEEDS TO COLLECT PAYMENTS -------------->
                            <?php require_once "partials/needstocollect.php"; ?>
                            <!------------- END NEEDS TO COLLECT PAYMENTS -------------->

                        </div>

                        <div class="col-xl-3 col-md-6">
                            <!------------- NEEDS TO PAY  -------------->
                            <?php require_once "partials/needtopay.php"; ?>
                            <!------------- END NEEDS TO PAY  -------------->
                        </div>

                    </div>
                    <!-- ================ END SECOND ROW ================ -->

                    <!-- ================ STRAT THIRD ROW ================ -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <!-- Current Stock Quantity & MRP  -->
                            <?php require_once "partials/stock-mrp-ptr.php"; ?>
                        </div>
                        <div class="col-xl-9 col-md-6">
                            <!------------- Stock Summary -------------->
                                <?php require_once "partials/stock-summary.php"; ?>
                            <!------------- end Stock Summary -------------->

                        </div>
                    </div>
                    <!-- ================ END OF THIRD ROW ================ -->
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once PORTAL_COMPONENT.'footer-text.php'; ?>
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
    <script src="../assets/jquery/jquery.min.js"></script>
    <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../assets/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <script>
    $(document).ready(function() {
        // executes when HTML-Document is loaded and DOM is ready
        console.log("document is ready");

        $(".card").hover(
            function() {
                $(this).addClass('shadow').css('cursor', 'pointer');
            },
            function() {
                $(this).removeClass('shadow');
            }
        );

        $(".card-btn").hover(
            function() {
                $(this).addClass('shadow').css('cursor', 'pointer');
            },
            function() {
                $(this).removeClass('shadow');
            }
        );

    });
    </script>

</body>

</html>