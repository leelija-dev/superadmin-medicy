<?php
// require_once dirname(__DIR__) . '/config/constant.php';
require_once 'config/constant.php';

require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'packagingUnit.class.php';

$page = "pack-unit";

//Class Initilization
$PackagingUnits = new PackagingUnits();

$showPackagingRequest = json_decode($PackagingUnits->showPackagingRequest());
$countPackagingRequest = 0;
if (is_object($showPackagingRequest) && property_exists($showPackagingRequest, 'data')) {
    $countPackagingRequest = is_array($showPackagingRequest->data) ? count($showPackagingRequest->data) : 0;
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

                <!-- =========================== Packaging of Units Content =========================== -->
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="card shadow mb-4">
                        <!-- Page Heading -->
                        <h1 class="h3 m-3 text-gray-800">Packaging Units</h1>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="card m-2">
                                    <div class="card-header d-flex justify-content-end bg-transparent top-1">
                                        <button type="button" class="btn btn-sm p-1 btn-primary" data-toggle="modal" data-target="#req-packagingUnit" onclick="packRequest()">
                                            Request</i><span class="badge badge-danger position-absolute top-0 start-100 translate-middle"><?= $countPackagingRequest ?></span>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <!-- Showing Unit Table -->
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

                                                    $showPackagingUnits = $PackagingUnits->showPackagingUnits();
                                                    foreach ($showPackagingUnits as $rowPackagingUnits) {
                                                        $unitId     = $rowPackagingUnits['id'];
                                                        $unitName   = $rowPackagingUnits['unit_name'];
                                                        $packStatus = $rowPackagingUnits['status'];
                                                        $isNew      = $rowPackagingUnits['new'];

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
                                                        $newBadge = ($isNew == 1) ? '<span class="badge badge-pill badge-info position-absolute ml-2 top-0 start-50 translate-middle-x">New</span>' : '';
                                                        echo '<tr>
                                                                <td>' . $unitId . '</td>
                                                                <td>' . $unitName . '' . $newBadge . '</td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle bg-white border-0 " type="button" id="statusDropdown' . $unitId . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: ' . $statusColor . ';">
                                                                            ' . $statusLabel . '
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="statusDropdown' . $unitId . '">
                                                                            <a class="dropdown-item" href="#" onclick="updateStatus(' . $unitId . ', 2, this)">Disabled</a>
                                                                            <a class="dropdown-item" href="#" onclick="updateStatus(' . $unitId . ', 0, this)">Pending</a>
                                                                            <a class="dropdown-item" href="#" onclick="updateStatus(' . $unitId . ', 1, this)">Active</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <a class="mx-1" data-toggle="modal" data-target="#unitModal" onclick="unitViewAndEdit(' . $unitId . ')"><i class="fas fa-edit"></i></a>
    
                                                                    <a class="mx-1" id="delete-btn" data-id="' . $unitId . '"><i class="far fa-trash-alt"></i></a>
                                                                </td>
                                                            </tr>';
                                                    }
                                                    ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="card m-2">
                                    <div class="card-body">
                                        <form method="post" action="ajax/packagingUnit.add.ajax.php">

                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="unit-name">Unit Name</Address></label>
                                                <input class="form-control" id="unit-name" name="uni-name" placeholder="Unit Name" required>
                                            </div>


                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                                                <button class="btn btn-primary me-md-2" name="add-unit" type="submit">Add
                                                    Unit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
                <!-- =========================== End of Packaging of Units Content =========================== -->



            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once SUP_ROOT_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

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
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
        //View and Edit Packaging Unit function
        // unitViewAndEdit = (unitId) => {
        //     let ViewAndEdit = unitId;
        //     console.log(ViewAndEdit);
        //     let url = "ajax/packagingUnit.view.ajax.php?Id=" + ViewAndEdit;
        //     $(".unitModal").html(
        //         '<iframe width="99%" height="120rem" frameborder="0" allowtransparency="true" src="' +
        //         url + '"></iframe>');
        // } // end of viewAndEdit function

        const unitViewAndEdit = (unitId) => {
            let ViewAndEdit = unitId;
            let url = "ajax/packagingUnit.view.ajax.php?Id=" + ViewAndEdit;
            $(".unitModal").html(
                '<iframe width="99%" height="130px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } 

        const packRequest = () => {
            var parentLocation = window.location.origin + window.location.pathname;
            $.ajax({
                url: "components/packagingUnit-request.php",
                type: "POST",
                data: {
                    urlData: parentLocation
                },
                success: function(response) {
                    let body = document.querySelector('.req-packagingUnit');
                    body.innerHTML = response;
                },
                error: function(error) {
                    console.error("Error: ", error);
                }
            });
            // $("#req-distributor").modal("hide");
            // location.reload();
        }
        //update Packaging Unit status//
        function updateStatus(unitId, newStatus) {

            if (confirm('Are you sure you want to change the status?')) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/packagingUnitStatus.update.ajax.php',
                    data: {
                        unitId: unitId,
                        newStatus: newStatus
                    },
                    success: function(response) {
                        console.log(response);
                        location.reload();
                    },
                    error: function(error) {
                        console.error('Error updating status:', error);
                    }
                });
            }
        } // end Packaging Unit status //

        //update Packaging Unit Request status//
        function updatePackReqStatus(unitId, newStatus) {

            if (confirm('Are you sure you want to change the status?')) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/packUnitReqStatus.update.ajax.php',
                    data: {
                        unitId: unitId,
                        newStatus: newStatus
                    },
                    success: function(response) {
                        console.log(response);
                        location.reload();
                    },
                    error: function(error) {
                        console.error('Error updating status:', error);
                    }
                });
            }
        } // end Packaging Unit Request status //

        //delete unit

        $(document).ready(function() {
            $(document).on("click", "#delete-btn", function() {
                //if (confirm("Are You Sure want to delete?"))
                unitid = $(this).data("id");
                btn = this;
                swal({
                        title: "Are you sure?",
                        text: "Want to Delete This Manufacturer?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: "ajax/packagingUnit.Delete.ajax.php",
                                type: "POST",
                                data: {
                                    id: unitid
                                },
                                success: function(response) {
                                    if (response.includes("1")) {
                                        $(btn).closest("tr").fadeOut()
                                    } else {
                                        $("#error-message").html("Deletion Field !!!").slideDown();
                                        $("success-message").slideUp();
                                    }

                                }
                            });

                        }
                        return false;
                    })
            });

        });

        // ..............delete Request pack Unit........... 
        const deleteReq = (t) => {
            let unitid = t;
            btn = this;
            swal({
                    title: "Are you sure?",
                    text: "Want to Delete This Manufacturer?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "ajax/packagingUnitReq.Delete.ajax.php",
                            type: "POST",
                            data: {
                                id: unitid
                            },
                            success: function(response) {
                                if (response.includes("1")) {
                                    $(btn).closest("tr").fadeOut()
                                    location.reload();
                                } else {
                                    $("#error-message").html("Deletion Field !!!").slideDown();
                                    $("success-message").slideUp();
                                }

                            }
                        });

                    }
                    return false;
                })
        }


        //========================== on edit modal cloase page reload ======================
        function pageReload() {
            parent.location.reload();
        }
    </script>


</body>

</html>