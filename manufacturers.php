<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'pagination.class.php';

// $page = "manufacturer";

//Class Initilizing
// $Distributor = new Distributor();
$Manufacturer = new Manufacturer();
$Pagination = new Pagination;


$showManufacturer = $Manufacturer->showManufacturer();
// print_r($showManufacturer);
// $showManufacturer = json_decode($showManufacturer);
// print_r($showManufacturer);
$slicedManuf = '';
$paginationHTML = '';
$totalItem = 0;

if (!empty($showManufacturer)) {
    // print_r($showManufacturer);

    if (is_array($showManufacturer)) {
        $response = json_decode($Pagination->arrayPagination($showManufacturer));
        // $slicedManuf = '';
        // $paginationHTML = '';
        $totalItem = $slicedManuf = $response->totalitem;
        // print_r($totalItem);
        if ($response->status == 1) {
            $slicedManuf = $response->items;
            $paginationHTML = $response->paginationHTML;
        }
    } else {
        $totalItem = 0;
        $paginationHTML = '';
    }
} else {
    $totalItem = 0;
    $paginationHTML = '';
}


//alert for form data inserted or failed
if (isset($_GET['return'])) {
    if ($_GET['return'] == "true") {
        echo "<script>alert('Manufacturer Added!');</script>";
    } else {
        echo "<script>alert('Manufacturer Insertion Failed!');</script>";
    }
}

$showManufacturer = $Manufacturer->showRequestManufacturer();
$countManufacturer = 0;
if ($showManufacturer !== null) {
    $manufacturerData = json_decode($showManufacturer, true);
    if (!empty($manufacturerData) && is_array($manufacturerData)) {
        $countManufacturer = count($manufacturerData);
    }
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

    <title>Manufacturers of Medicy Health Care</title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- <link href="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> -->


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

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Manufacturers</h1>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card shadow mb-4">
                                <div class="d-flex justify-content-around">
                                    <div class="col-10">
                                        <h6 class=" col-6 mt-4 m-0 font-weight-bold text-primary">List of Manufacturers : <?= $totalItem ?></h6>
                                    </div>
                                    <div class="card-header d-flex justify-content-end bg-transparent top-1">
                                        <button type="button" class="btn btn-sm p-1 btn-primary" data-toggle="modal" data-target="#req-manufacture" onclick="manufRequest()">
                                            Request</i><span class="badge badge-danger position-absolute top-0 start-100 translate-middle"><?= $countManufacturer ?></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Showing Unit Table -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>SL.</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (is_array($slicedManuf)) {
                                                    foreach ($slicedManuf as $rowManufacturer) {

                                                        $manufacturerId          = $rowManufacturer->id;
                                                        $manufacturerName        = $rowManufacturer->name;
                                                        // $distributorId       = $rowManufacturer['distributor_id'];
                                                        $manufacturerDsc         = $rowManufacturer->dsc;
                                                        $manufacturerStatus      = $rowManufacturer->status;
                                                        $isNew                   = $rowManufacturer->new;

                                                        $statusLabel = '';
                                                        $statusColor = '';
                                                        switch ($manufacturerStatus) {
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
                                                        echo  '<tr>
                                                                <td>' . $manufacturerId . '</td>
                                                                <td>' . $manufacturerName . ' ' . $newBadge . '</td>
                                                                <td>' . $manufacturerDsc . '</td>
                                                                <td> 
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle bg-white border-0 " type="button" id="statusDropdown' . $manufacturerId . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: ' . $statusColor . ';">
                                                                            ' . $statusLabel . '
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="statusDropdown' . $manufacturerId . '">
                                                                            <a class="dropdown-item" href="#" onclick="updateStatus(' . $manufacturerId . ', 2, this)">Disabled</a>
                                                                            <a class="dropdown-item" href="#" onclick="updateStatus(' . $manufacturerId . ', 0, this)">Pending</a>
                                                                            <a class="dropdown-item" href="#" onclick="updateStatus(' . $manufacturerId . ', 1, this)">Active</a>
                                                                        </div>
                                                                    </div>
                                                                 </td>   
                                                                <td>
                                                                    <a class="" data-toggle="modal" data-target="#manufacturerModal" onclick="manufViewAndEdit(' . $manufacturerId . ')"><i class="fas fa-edit"></i></a>

                                                                    <a class="ms-2" id="delete-btn" data-id="'. $manufacturerId .'"><i class="far fa-trash-alt"></i></a>
                                                                </td>
                                                            </tr>';
                                                    }
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

                        <div class="col-md-5">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <form method="post" action="ajax/manufacturer.add.ajax.php">

                                        <div class="col-md-12">
                                            <label class="mb-0" for="manufacturer-name">Manufacturer Name</Address>
                                            </label>
                                            <input class="form-control" id="manufacturer-name" name="manufacturer-name" placeholder="Manufacturer Name" required>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="mb-0" for="manufacturer-name">Manufacturer Mark</Address>
                                            </label>
                                            <input class="form-control" id="manufacturer-short-name" name="manufacturer-short-name" placeholder="Manufacturer Mark" required>
                                        </div>

                                        <div class="col-md-12 mt-2">
                                            <label class="mb-0" for="manufacturer-dsc">Description</Address></label>
                                            <textarea name="manufacturer-dsc" id="manufacturer-dsc" class="form-control" cols="30" rows="3" maxlength="400"></textarea>
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                                            <button class="btn btn-primary me-md-2" name="add-manufacturer" type="submit">Add Manufacturer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
    <div class="modal fade" id="manufacturerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View and Edit Manufacturer Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" onclick="relode()">&times;</span>
                    </button>
                </div>
                <div class="modal-body manufacturerModal">
                    <!-- Details Appeare Here by Ajax  -->
                </div>
            </div>
        </div>
    </div>
    <!--/end Manufacturer View and Edit Modal -->

    <!-- Manufacturer request Modal -->
    <div class="modal fade" id="req-manufacture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Manufacturer Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" onclick="relode()">&times;</span>
                    </button>
                </div>
                <div class="modal-body req-manufacture">
                    <!-- Details Appeare Here by Ajax  -->
                </div>
            </div>
        </div>
    </div>
    <!--/end Manufacturer request Modal -->


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


    <!-- Page level plugins -->
    <!-- <script src="<?= PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script> -->

    <!-- Page level custom scripts -->
    <!-- <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script> -->

    <script>
        //View and Edit Manufacturer function
        const manufViewAndEdit = (manufacturerId) => {
            let ViewAndEdit = manufacturerId;
            let url = "ajax/manufacturer.View.ajax.php?Id=" + ViewAndEdit;
            $(".manufacturerModal").html(
                '<iframe width="99%" height="330px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } // end of viewAndEdit function

        const manufRequest = () => {
            var parentLocation = window.location.origin + window.location.pathname;
            $.ajax({
                url: "components/manufacturer-request.php",
                type: "POST",
                data: {
                    urlData: parentLocation
                },
                success: function(response) {
                    let body = document.querySelector('.req-manufacture');
                    body.innerHTML = response;
                },
                error: function(error) {
                    console.error("Error: ", error);
                }
            });
            // $("#req-distributor").modal("hide");
            // location.reload();
        }

        function updateStatus(manufacturerId, newStatus) {

            if (confirm('Are you sure you want to change the status?')) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/manufactureStatus.update.ajax.php',
                    data: {
                        manufacturerId: manufacturerId,
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
        } // end manufacturer status //

        // manufacturer request status update//
        function updateReqStatus(manufacturerId, newStatus) {

            if (confirm('Are you sure you want to change the status?')) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/manufactureReqStatus.update.ajax.php',
                    data: {
                        manufacturerId: manufacturerId,
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
        } // end manufacturer request status update //

        //delete manufacturer
        $(document).ready(function(){
            $(document).on("click", "#delete-btn", function (){
                
                swal({
                    title: "Are you sure?",
                    text: "Want to Delete This Manufacturer?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        manufId = $(this).data("id");
                        btn = this;

                        $.ajax({
                            url: "ajax/manufacturer.Delete.ajax.php",
                            type: "POST",
                            data: {
                                id: manufId
                            },
                            success: function(response) {
                                alert(response);
                                if (response) {
                                    $(btn).closest("tr").fadeOut()
                                    swal("Deleted", "Manufacturer Has Been Deleted", "success");
                                } else {
                                    swal("Delete Not Possible", response, "warning");
                                }
                            }
                        });

                    }
                    return false;
                });

            })
        })



        

        //========edit modal on close parent location reload==============

        function relode() {
            parent.location.reload();
        }
    </script>


</body>

</html>