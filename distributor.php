<?php
// require_once dirname(__DIR__) . '/config/constant.php';
require_once 'config/constant.php';

require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'distributor.class.php';


//Class Initilizing
$Distributor = new Distributor();

$showDistributor = json_decode($Distributor->showDistributor());
$showDistributor = $showDistributor->data;

$showDistRequest  = json_decode($Distributor->showDistRequest());
$countDistRequest = 0;
if (!empty($showDistRequest->data)) {
    $countDistRequest = count($showDistRequest->data);
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

    <title>Distributor of <?= $healthCareName ?> | <?= SITE_NAME ?> </title>

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

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="row">

                        <!-- Show Distributor -->
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header d-flex justify-content-end">
                                    <button type="button" class="btn btn-sm btn-primary mr-5" data-toggle="modal" data-target="#req-distributor" onclick="distRequest()">
                                        Request</i><span class="badge badge-danger position-absolute top-3 start-100 translate-middle"><?= $countDistRequest ?></span>
                                    </button>
                                    <button class="btn btn-sm btn-primary shadow-none" data-toggle="modal" data-target="#add-distributor" onclick="addDistributor()">
                                        Add new
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>SL.</th>
                                                    <th>Name</th>
                                                    <th>Contact</th>
                                                    <th>Area PIN</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (is_array($showDistributor)) {
                                                    foreach ($showDistributor as $rowDistributor) {
                                                        $distributorId      = $rowDistributor->id;
                                                        $distributorName    = $rowDistributor->name;
                                                        $distributorPhno    = $rowDistributor->phno;
                                                        $distributorPin     = $rowDistributor->area_pin_code;
                                                        $distributorStatus  = $rowDistributor->status;
                                                        $isNew              = $rowDistributor->new;
                                                        $isDelete           = $rowDistributor->del_req;

                                                        $statusLabel = '';
                                                        $statusColor = '';
                                                        switch ($distributorStatus) {
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
                                                        $delReq   = ($isDelete == 1) ? '<span class="badge badge-pill badge-danger position-absolute top-0 start-50 translate-middle-x">Delete</span>' : '';
                                                        echo '<tr>
                                                                <td>' . $distributorId . '</td>
                                                                <td>' . $distributorName . ' ' . $newBadge . '</td>
                                                                <td>' . $distributorPhno . '</td>
                                                                <td>' . $distributorPin . '</td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-sm btn-secondary dropdown-toggle bg-white border-0 " type="button" id="statusDropdown' . $distributorId . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: ' . $statusColor . ';">
                                                                            ' . $statusLabel . '
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="statusDropdown' . $distributorId . '">
                                                                            <a class="dropdown-item" href="#" onclick="updateStatus(' . $distributorId . ', 2, this)">Disabled</a>
                                                                            <a class="dropdown-item" href="#" onclick="updateStatus(' . $distributorId . ', 0, this)">Pending</a>
                                                                            <a class="dropdown-item" href="#" onclick="updateStatus(' . $distributorId . ', 1, this)">Active</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <a class="mx-1" data-toggle="modal" data-target="#distributorModal" onclick="distViewAndEdit(' . $distributorId . ')"><i class="fas fa-edit"></i></a>
                                                                    ';
                                                        if ($delReq) {
                                                            echo '<a class="mx-1" data-toggle="modal" data-target="#deleteRequest" onclick=deleteRequestEmp(' . $distributorId . ')><i class="far fa-trash-alt"></i>' . $delReq . '</a>';
                                                        } else {
                                                            echo '<a class="mx-1" id="delete-btn" data-id="' . $distributorId . '"><i class="far fa-trash-alt"></i>' . $delReq . '</a>';
                                                        }
                                                        echo '</td>
                                                               </tr>';
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /end Show Distributor -->
                        <!-- id="delete-btn" data-id="' . $distributorId . '" -->
                    </div>

                </div>
                <!-- /.container-fluid -->

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
    <div class="modal fade" id="distributorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View and Edit Distributor Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body distributorModal">
                    <!-- Details Appeare Here by Ajax  -->
                </div>
            </div>
        </div>
    </div>
    <!--/end Manufacturer View and Edit Modal -->


    <!-- Manufacturer View and Edit Modal -->
    <div class="modal fade" id="add-distributor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Distributor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body add-distributor">
                    <!-- Details Appeare Here by Ajax  -->
                </div>
            </div>
        </div>
    </div>
    <!--/end Manufacturer View and Edit Modal -->

    <!-- show distributor request data  -->
    <div class="modal fade" id="req-distributor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Distributor Request Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body req-distributor">
                    <!-- Details Appeare Here by Ajax  -->
                </div>
            </div>
        </div>
    </div>
    <!-- end distributor request data  -->

    <!-- for delete request  -->
    <div class="modal fade" id="deleteRequest" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Request Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body deleteRequest">

                </div>
            </div>
        </div>
    </div>
    <!-- end for delete request -->

    <!-- Requested Distributor View Modal -->
    <div class="modal fade" id="reqDistributorModal" tabindex="-1" role="dialog" aria-labelledby="reqDistributorModalLabel" data-toggle="modal" data-target="#req-distributor">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reqDistributorModalLabel">Distributor Request Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body reqDistributorModal">
                    <!-- Details Appeare Here by Ajax  -->
                </div>
            </div>
        </div>
    </div>
    <!-- Requested Distributor View Modal -->

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

    <!-- Sweet Alert Js  -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?= PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script>

    <script>
        const addDistributor = () => {

            var parentLocation = window.location.origin + window.location.pathname;

            $.ajax({
                url: "components/distributor-add.php",
                type: "POST",
                data: {
                    urlData: parentLocation
                },
                success: function(response) {
                    let body = document.querySelector('.add-distributor');
                    body.innerHTML = response;
                },
                error: function(error) {
                    console.error("Error: ", error);
                }
            });
        }

        const distRequest = () => {
            var parentLocation = window.location.origin + window.location.pathname;
            $.ajax({
                url: "components/distributor-request.php",
                type: "POST",
                data: {
                    urlData: parentLocation
                },
                success: function(response) {
                    let body = document.querySelector('.req-distributor');
                    body.innerHTML = response;
                },
                error: function(error) {
                    console.error("Error: ", error);
                }
            });
            // $("#req-distributor").modal("hide");
            // location.reload();
        }

        const deleteRequestEmp = (distId) => {
            var parentLocation = window.location.origin + window.location.pathname;
            $.ajax({
                url: "components/distributor-DeleteReq.php",
                type: "POST",
                data: {
                    urlData: parentLocation,
                    distId: distId
                },
                success: function(response) {
                    let body = document.querySelector('.deleteRequest');
                    body.innerHTML = response;
                },
                error: function(error) {
                    console.log('error:', error);
                }
            })
        }

        // for cancel distributer delete request 
        const cancelDeleteReqEmp = (DistId) => {
            console.log(DistId);
            var parentLocation = window.location.origin + window.location.pathname;
            $.ajax({
                url: "ajax/distributor.DelReqCancel.ajax.php",
                type: "POST",
                data: {
                    urlData: parentLocation,
                    distId: DistId
                },
                success: function(response) {
                    if (response) {
                        location.reload();
                    } else {
                        console.log('not canceled');
                    }
                },
                error: function(error) {
                    console.log('error:', error);
                }
            })
        }
        //View and Edit Manufacturer function
        distViewAndEdit = (distributorId) => {
            let ViewAndEdit = distributorId;
            let url = "ajax/distributor.View.ajax.php?Id=" + ViewAndEdit;
            $(".distributorModal").html(
                '<iframe width="99%" height="530px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } // end of viewAndEdit function

        //update distributor status//
        function updateStatus(distributorId, newStatus) {

            if (confirm('Are you sure you want to change the status?')) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/distributorStatus.update.ajax.php',
                    data: {
                        distributorId: distributorId,
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
        } // end distributor status //

        //update distributor Request status//
        function updateReqStatus(distributorId, newStatus) {

            if (confirm('Are you sure you want to change the status?')) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/distributorReqStatus.update.ajax.php',
                    data: {
                        distributorId: distributorId,
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
            // location.reload();
        } // end distributor Request status //

        //delete distributor
        $(document).ready(function() {
            $(document).on("click", "#delete-btn", function() {

                swal({
                        title: "Are you sure?",
                        text: "Want to Delete This Distributor?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {

                            distributorId = $(this).data("id");
                            btn = this;

                            $.ajax({
                                url: "ajax/distributor.Delete.ajax.php",
                                type: "POST",
                                data: {
                                    id: distributorId
                                },
                                success: function(data) {
                                    if (data == 1) {
                                        $(btn).closest("tr").fadeOut()
                                        swal("Deleted", "Distributor Has Been Deleted",
                                            "success");
                                        location.reload();
                                    } else {
                                        swal("Failed", data, "error");
                                    }
                                }
                            });

                        }
                        return false;
                    });

            })

        })

        const deleteReq = (t) => {
            let distributorId = t;
            swal({
                    title: "Are you sure?",
                    text: "Want to Delete This Distributor?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        distributorId = t;
                        btn = this;

                        $.ajax({
                            url: "ajax/distributorReq.Delete.ajax.php",
                            type: "POST",
                            data: {
                                id: distributorId
                            },
                            success: function(data) {
                                if (data == 1) {
                                    $(btn).closest("tr").fadeOut()
                                    swal("Deleted", "Distributor Has Been Deleted",
                                        "success");
                                    location.reload();
                                } else {
                                    swal("Failed", data, "error");
                                }
                            }
                        });

                    }
                })
        }

        function getRequestedData(requestId) {
            $('#req-distributor').modal('toggle')
            $('#reqDistributorModal').modal('toggle')

            // alert(requestId);
            $.ajax({
                url: "ajax/distributor-request.view.ajax .php",
                type: "POST",
                data: {
                    requestId: requestId
                },
                success: function(response) {
                    let body = document.querySelector('.reqDistributorModal');
                    body.innerHTML = response;
                },
                error: function(error) {
                    alert('error:', error);
                }
            })

            // Close the current active modal
            // $('#req-distributor').modal('hide');

            // Open the new modal
            // $('#reqDistributorModal').modal('show');
        }

          
        document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('#distributor-address');
    const label = textarea.nextElementSibling;

    // Trigger floating label when focused
    textarea.addEventListener('focus', function() {
        textarea.classList.add('focused');
    });

    // Remove floating label when textarea loses focus and is empty
    textarea.addEventListener('blur', function() {
        if (textarea.value === '') {
            textarea.classList.remove('focused');
        }
    });
});

    </script>


</body>

</html>