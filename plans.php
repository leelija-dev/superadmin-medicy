<?php
// require_once dirname(__DIR__) . '/config/constant.php';
require_once 'config/constant.php';

require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'plan.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'rbacController.class.php';


$Plan = new Plan();
$Pagination = new Pagination;
$RbacController = new RbacController;

// plans details
$plans = json_decode($Plan->allPlans());
if ($plans->status == 1) {
    $plans = $plans->data;
}

$permissionDetailsData = json_decode($RbacController->selectPermissionTableDetails());
$permissionDetails = $permissionDetailsData->data;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Plans of Medicy Health Care</title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include SUP_ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include SUP_ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class=" col-6 font-weight-bold text-primary">List of Plans</h6>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#staticBackdrop">
                                Add
                            </button>
                        </div>

                        <div class="card-body">
                            <!-- Showing Unit Table -->
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Plan Id</th>
                                            <th>Name</th>
                                            <th>Duration</th>
                                            <th>Price</th>
                                            <th>Sold</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($plans as $eachPlan): ?>
                                            <tr>
                                                <td><?= $eachPlan->id ?></td>
                                                <td><?= $eachPlan->name ?></td>
                                                <td><?= $eachPlan->duration ?></td>
                                                <td><?= $eachPlan->price ?></td>
                                                <td></td> <!-- Add the sold value here -->
                                                <td>
                                                    <?php
                                                    $statusLabel = '';
                                                    $statusColor = '';
                                                    switch ($eachPlan->status) {
                                                        case 2:
                                                            $statusLabel = 'Disabled';
                                                            $statusColor = 'danger';
                                                            break;
                                                        case 0:
                                                            $statusLabel = 'Pending';
                                                            $statusColor = 'warning';
                                                            break;
                                                        case 1:
                                                            $statusLabel = 'Active';
                                                            $statusColor = 'primary';
                                                            break;
                                                        default:
                                                            $statusLabel = 'Disabled';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="badge badge-<?= $statusColor ?>"><?= $statusLabel ?></span>
                                                </td>
                                                <td>
                                                    <span class="text-primary cursor-pointer" data-toggle="modal" data-target="#planModal" onclick="getView('ajax/plans.view.update.ajax.php', 'id', <?= $eachPlan->id ?>, 'planModal')">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                    <a class="ms-2" id="delete-btn" data-id="<?= $eachPlan->id ?>"><i class="far fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <!-- / Card -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!--/ .End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Plan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="parentReload()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="add-alert">
                    </div>

                    <form onsubmit="addEditPlan(event)" id="plan-form">
                        <div class="row mx-0">
                            <input type="hidden" name="plan-id" id="plan-id" value="">
                            <div class="col-12 py-1">
                                <input type="text" class="form-control" id="plan-name" name="plan-name" placeholder="Plan Name" required>
                            </div>
                            <div class="col-12 py-1">
                                <input type="text" class="form-control" id="plan-duration" name="plan-duration" placeholder="Plan Duration" required>
                            </div>
                            <div class="col-6 py-1">
                                <input type="number" class="form-control" id="plan-price" name="plan-price" placeholder="Price" required>
                            </div>
                            <div class="col-6 py-1">
                                <select class="form-control" id="plan-status" name="plan-status" required>
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="0">Deactive</option>
                                    <option value="1">Active</option>
                                </select>
                            </div>

                            <div class="col-12 py-1">
                                <label class="mt-2 mb-n2" for="permissions">Access Permission</label>
                                <div class="p-3 mt-1" style="height: 165px; overflow-y: auto;" data-spy="scroll">
                                    <?php $count = 0; ?>
                                    <?php foreach ($permissionDetails as $permissionData) : $count++; ?>
                                        <div class="row d-flex">
                                            <label>
                                                <input type="checkbox" name="permissions[]" id="permission-id-<?= $count; ?>" value="<?= $permissionData->permission_id; ?>"> <?= $permissionData->permissions; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div id="feature-container" class="col-12 py-1 mt-1">
                                <div class="form-group">
                                    <div class="d-flex my-2 feature-row">
                                        <input type="text" class="form-control form-control-sm" name="features[]" placeholder="Feature" required>
                                        <button type="button" class="btn btn-sm btn-danger remove-feature rounded-right">
                                            <i class="far fa-times-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="button" class="btn btn-sm btn-primary w-100" id="addFeature">
                                    Add Feature <i class="fas fa-plus-circle"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mt-2 reportUpdate" id="reportUpdate">
                            <!-- Ajax Update Report Goes Here -->
                        </div>
                        <input type="hidden" name="flag-data" id="flag-data" value="1">
                        <div class="mt-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-primary">Add</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Manufacturer View and Edit Modal -->
    <!-- Modal Structure -->
    <div class="modal fade" id="planModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View and Edit Plan Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="parentReload()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Iframe to load content dynamically -->
                    <iframe id="planModalIframe" width="100%" height="400px" frameborder="0" allowtransparency="true"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Sweet Alert Js  -->
    <!-- <script src="<?= JS_PATH ?>sweetAlert.min.js"></script> -->
    <!-- <script src="<?= JS_PATH ?>sweetalrt2.all.js"></script> -->



    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>

    <script src="<?= JS_PATH ?>sup-admin/plans-control.js"></script>

</body>

</html>