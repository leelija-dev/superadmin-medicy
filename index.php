<?php
require_once 'config/constant.php';
require_once '_config/sessionCheck.php';
// exit;

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'labAppointments.class.php';


$appoinments       = new Appointments();
$CurrentStock      = new CurrentStock();
$StockOut          = new StockOut();
$StockIn           = new StockIn();
$StockInDetails    = new StockInDetails();
$Distributor       = new Distributor;
$Patients          = new Patients;


$totalAppointments = $appoinments->appointmentsDisplay();
$totalAppointments = json_decode($totalAppointments);

if ($totalAppointments->status) {
    $totalAppointmentsCount = count($totalAppointments->data);
} else {
    $totalAppointmentsCount = 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
  
    <title>Medicy Health Care - Admin Portal</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH; ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH; ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH; ?>custom/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>custom-dashboard.css">

    <script src="<?php echo JS_PATH; ?>ajax.custom-lib.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include SUP_ROOT_COMPONENT . "sidebar.php"; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar-->
                <?php include SUP_ROOT_COMPONENT . "topbar.php"; ?>
                <!-- End of Tobbar-->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- CONTENT USER DATA ROW -->

                    <!-- Content Row -->
                    <!-- ================ THIRD ROW ================ -->
                    <div class="row">

                        <!-- Expiring in 3 Months Card -->
                        <div class="col-xl-4 col-md-4 mb-4">
                            <?php require_once SUP_ROOT_COMPONENT . "expiring.php"; ?>
                        </div>

                        <!----------- Sales of the day card ----------->
                        <div class="col-xl-4 col-md-4 mb-4">
                            <?php require_once SUP_ROOT_COMPONENT . "salesoftheday.php"; ?>
                        </div>

                        <!----------- Purchase today card ----------->
                        <div class="col-xl-4 col-md-4 mb-4">
                            <?php require_once SUP_ROOT_COMPONENT . "purchasedToday.php"; ?>
                        </div>

                    </div>

                    <!-- ================ FORTH ROW ROW ================ -->
                    <div class="row">

                        <div class="col-xl-6 col-md-6 mb-4">
                            <?php require_once SUP_ROOT_COMPONENT . "mostsolditems.php"; ?>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <?php require_once SUP_ROOT_COMPONENT . "lesssolditems.php"; ?>
                        </div>
                        <br>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <?php require_once SUP_ROOT_COMPONENT . "mostvisitedcustomer.php"; ?>
                        </div>
                        <br>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <?php require_once SUP_ROOT_COMPONENT . "highestpurchasedcustomer.php"; ?>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once SUP_ROOT_COMPONENT . "mopdByAmount.php"; ?>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once SUP_ROOT_COMPONENT . "mopdByItems.php"; ?>
                        </div>

                    </div>

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH; ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH; ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH; ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH; ?>sb-admin-2.min.js"></script>

    <!-- ======== CUSTOM JS FOR INDEX PAGE ======= -->
    <script src="<?php echo PLUGIN_PATH; ?>chartjs-4.4.0/updatedChart.js"></script>


    <script src="<?php echo JS_PATH; ?>index.js"></script>
</body>

</html>