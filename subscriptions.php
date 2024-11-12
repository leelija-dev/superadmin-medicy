<?php
// require_once dirname(__DIR__) . '/config/constant.php';
require_once 'config/constant.php';

require_once SUP_ADM_DIR . '_config/sessionCheck.php'; // Check if admin is logged in

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'plan.class.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'pagination.class.php';

$Admin = new Admin;
$Plan = new Plan;
$Subscription = new Subscription;
$Pagination = new Pagination;

$slicedSubscribers = '';
$paginationHTML = '';
$totalItem = 0;

// Fetch subscription data
$subscribersData = json_decode($Subscription->getSubscription());

if ($subscribersData->status) {
    $subscribersList = $subscribersData->data;

    if (is_array($subscribersList)) {
        $response = json_decode($Pagination->arrayPagination($subscribersList));
        $totalItem = $response->totalitem;

        if ($response->status == 1) {
            $slicedSubscribers = $response->items;
            $paginationHTML = $response->paginationHTML;
        }
    }
}

// HTML begins
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>List of Subscribers - Medicy Health Care</title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include SUP_ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include SUP_ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Manufacturers</h1>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-body" >
                                    <!-- Table of Subscribers -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" >
                                            <thead>
                                                <tr>
                                                    <th>Admin Id</th>
                                                    <th>Name</th>
                                                    <th>Username</th>
                                                    <th>Plan Name</th>
                                                    <th>Subscription Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (is_array($slicedSubscribers)) {
                                                    foreach ($slicedSubscribers as $rowSubscribers) {
                                                        $adminId = $rowSubscribers->admin_id;
                                                        $adminData = json_decode($Admin->adminDetails($adminId));

                                                        if ($adminData->status) {
                                                            $adminFname = $adminData->data[0]->fname;
                                                            $adminLname = $adminData->data[0]->lname;
                                                            $adminUsername = $adminData->data[0]->username;
                                                            $adminName = $adminFname . ' ' . $adminLname;

                                                            $planDetails = json_decode($Plan->getPlan($rowSubscribers->plan));
                                                            $planName = $planDetails->data->name;
                                                            $subscriptionStatus = $rowSubscribers->status;
                                                ?>
                                                            <tr>
                                                                <td><?= $adminId; ?></td>
                                                                <td><?= $adminName; ?></td>
                                                                <td><?= $adminUsername; ?></td>
                                                                <td><?= $planName; ?></td>
                                                                <td><?= $subscriptionStatus; ?></td>
                                                                <td><i class="fas fa-eye" onclick="getDetails('<?= $adminId; ?>')"></i></td>
                                                            </tr>
                                                <?php
                                                        }
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='6' class='text-center'>No data available</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <?= $paginationHTML ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <?php include_once SUP_ROOT_COMPONENT . 'footer-text.php'; ?>
                <!-- End of Footer -->
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->

        <!-- Admin Details Modal -->
        <div class="modal fade" id="adminDetailsModal" tabindex="-1" role="dialog" aria-labelledby="adminDetailsLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="adminDetailsLabel">Customer Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" onclick="reload()">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- IFrame to load content -->
                        <iframe id="adminDetailsIframe" src="" frameborder="0" style="width: 100%; height: 600px;"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Bootstrap core JavaScript -->
        <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
        <!-- Sweet Alert JS -->
        <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
        <!-- Core plugin JavaScript -->
        <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>
        <!-- Custom scripts for all pages -->
        <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

        <script>
            function getDetails(adminId) {
                // Open the admin details modal
                $('#adminDetailsModal').modal('show');
              
                // i frame to show admin details
                let iframeSrc = 'ajax/customer_data_details.ajax.php?adminId=' + adminId;
                $('#adminDetailsIframe').attr('src', iframeSrc);
            }



            // Reload parent location on modal close
            function reload() {
                parent.location.reload();
            }
        </script>
</body>

</html>