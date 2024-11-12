<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'packagingUnit.class.php';

$page = "pack-unit";

//Class Initilization
$PackagingUnits = new PackagingUnits();

$showPackagingRequest = json_decode($PackagingUnits->showPackagingRequest());

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Measure of Unit - Medicy Health Care</title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <link href="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- =========================== Packaging of Units Content =========================== -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>SL. No.</th>
                                    <th>Unit Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (is_object($showPackagingRequest) && property_exists($showPackagingRequest, 'data')) {
                                    $showPackagingRequest = $showPackagingRequest->data;
                                    if (is_array($showPackagingRequest) || is_object($showPackagingRequest)) {
                                    foreach ($showPackagingRequest as $rowPackagingUnits) {
                                        $unitId     = $rowPackagingUnits->pack_id;
                                        $unitName   = $rowPackagingUnits->unit_name;
                                        $packStatus = $rowPackagingUnits->status;

                                        $statusLabel = '';
                                        $statusColor = '';
                                        switch ($packStatus) {
                                            case 2:
                                                $statusLabel = 'Disabled';
                                                $statusColor = 'red';
                                                break;
                                            case 0:
                                                $statusLabel = 'Pending';
                                                $statusColor = '#4e73df';
                                                break;
                                            case 1:
                                                $statusLabel = 'Active';
                                                $statusColor = 'green';
                                                break;
                                            default:
                                                $statusLabel = 'Disabled';
                                                break;
                                        }
                                        echo '<tr>
                                                                <td>' . $unitId . '</td>
                                                                <td>' . $unitName . '</td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle bg-white border-0 " type="button" id="statusDropdown' . $unitId . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: ' . $statusColor . ';">
                                                                            ' . $statusLabel . '
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="statusDropdown' . $unitId . '">
                                                                            <a class="dropdown-item" href="#" onclick="updatePackReqStatus(' . $unitId . ', 2, this)">Disabled</a>
                                                                            <a class="dropdown-item" href="#" onclick="updatePackReqStatus(' . $unitId . ', 0, this)">Pending</a>
                                                                            <a class="dropdown-item" href="#" onclick="updatePackReqStatus(' . $unitId . ', 1, this)">Active</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
    
                                                                    <a class="mx-1" onclick = "deleteReq(' . $unitId . ')" ><i class="far fa-trash-alt"></i></a>
                                                                </td>
                                                            </tr>';
                                    }
                                }
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->
    <!-- <a class="" data-toggle="modal" data-target="#manufacturerModal" onclick="manufViewAndEdit(' . $manufacturerId . ')"><i class="fas fa-edit"></i></a> -->
    </div>
    <!-- End of Page Wrapper -->


    <!-- Manufacturer View and Edit Modal -->
    <div class="modal fade" id="unitModal" tabindex="-1" role="dialog" aria-labelledby="unitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unitModalLabel">View and Edit Units</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="pageReload()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body unitModal">
                    <!-- Details Appeare Here by Ajax  -->
                </div>
            </div>
        </div>
    </div>
    <!--/end Manufacturer View and Edit Modal -->

    <!-- show packtype request    -->
    <div class="modal fade" id="req-packagingUnit" tabindex="-1" role="dialog" aria-labelledby="unitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unitModalLabel">Pack Unit Request Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="pageReload()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body req-packagingUnit">
                    <!-- Details Appeare Here by Ajax  -->
                </div>
            </div>
        </div>
    </div>
    <!-- end packtype request    -->


    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Sweet Alert Js  -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <!-- Sweet Alert Js  -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?= PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script>
    <script>
        //View and Edit Manufacturer function
        unitViewAndEdit = (unitId) => {
            let ViewAndEdit = unitId;
            let url = "ajax/packagingUnit.View.ajax.php?Id=" + ViewAndEdit;
            $(".unitModal").html(
                '<iframe width="99%" height="120rem" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } // end of viewAndEdit function


        //========================== on edit modal cloase page reload ======================
        function pageReload() {
            parent.location.reload();
        }
    </script>


</body>

</html>