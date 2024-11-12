<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';//check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR.'dbconnect.php';
require_once SUP_ADM_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'salesReturn.class.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'stockOut.class.php';
require_once CLASS_DIR.'currentStock.class.php';

$SalesReturn   = new SalesReturn();
$Patients      = new Patients();
$stockOut      = new StockOut();
$currentStock  = new CurrentStock();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sales Return || Medicy Healthcare</title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/return-page.css">

    <!-- Data Table CSS  -->
    <link href="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">


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
                    <div class="d-flex justify-content-between">
                        <h5 class="h4 mb-2 text-gray-800"> Return </h4>
                            <a class="btn btn-sm btn-primary mb-3" href="sales-returns-items.php"> News <i class="fas fa-plus"></i></a>
                    </div>
                    <!-- Showing Sell Items  -->
                    <div class="card shadow mb-2">
                        <div class="card-body">
                            <!-- <div class="row mb-3 ">
                                <div class="col-md-1 col-12">
                                    <input class="cvx-inp" type="text" placeholder="Bill No. "
                                        style="width: 5rem;height: 28px;outline: none;">
                                </div>

                                <div class="col-md-3 col-12">
                                    <select class="cvx-inp1" name="payment-mode" id="payment-mode">
                                        <option value="" selected disabled>01/04/2022-31/03/2022 </option>
                                        <option value="Credit">Today</option>
                                        <option value="Cash">yesterday</option>
                                        <option value="UPI">Last 7 Days</option>
                                        <option value="Paypal">Last 30 Days</option>
                                        <option value="Bank Transfer">Last 90 Days</option>
                                        <option value="Credit Card">Current Fiscal Year</option>
                                        <option value="Debit Card">Previous Fiscal Year</option>
                                        <option value="Net Banking">Custom Range </option>
                                    </select>

                                </div>
                                <div class="col-md-2 col-6">
                                    <select class="cvx-inp1" id="distributor-id">
                                        <option value="" disabled selected>Select Staff
                                        </option>
                                        <option value="All">All</option>
                                        <option value="Owner">Owner</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-6">
                                    <input class="cvx-inp" type="text" placeholder="Name / Mobile No."
                                        style="outline: none;">
                                </div>
                                <div class="col-md-2 col-6">
                                    <select class="cvx-inp1" name="payment-mode" id="payment-mode">
                                        <option value="" selected disabled>payment Mode</option>
                                        <option value="Credit">Credit</option>
                                        <option value="Cash">Cash</option>
                                        <option value="UPI">UPI</option>
                                        <option value="Paypal">Paypal</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Debit Card">Debit Card</option>
                                        <option value="Net Banking">Net Banking</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-6">
                                    <input class="cvx-inp" type="text" placeholder="Search By Notes"
                                        style="outline: none;">
                                </div>
                            </div> -->

                            <div class="table-responsive">
                                <table class="table item-table table-sm text-dark" id="dataTable" style="width: 100%;">
                                    <thead class="thead-white bg-primary text-light">
                                        <tr>
                                            <th>Invoice</th>
                                            <th hidden>Sales Return Id</th>
                                            <th>Patient Name</th>
                                            <th>Items</th>
                                            <th>Bill Date</th>
                                            <th>Return Date</th>
                                            <th>Entry By</th>
                                            <th>Amount</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataBody">
                                        <?php

                                        $table1 = 'admin_id';
                                        $table2 = "status";  # fetching those data whose STATUS are 
                                        $data2 = "1";   #  ACTIVE FROM SALES RETURN TABLE

                                        $returns = $SalesReturn->selectSalesReturn($table1, $table2, $data2);
                                        // print_r($returns);

                                        if (count($returns) > 0) {
                                            foreach ($returns as $item) {
                                                //print_r($item); echo "<br><br>"; 
                                                $invoiceId = $item['invoice_id'];
                                                $salesReturnId = $item['id'];
                                                // echo $invoiceId,"<br>";
                                                // echo $salesReturnId;
                                                if ($item['patient_id'] == "Cash Sales") {
                                                    $patientName = "Cash Sales";
                                                } else {
                                                    $patient = json_decode($Patients->patientsDisplayByPId($item['patient_id']));
                                                    //print_r($patient); echo "<br><br>";
                                                    $patientName = $patient->name;
                                                }
                                                echo '<tr>
                                                    <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . $invoiceId . '</td>
                                                    <td hidden>' . $salesReturnId . '</td>
                                                    <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . $patientName . '</td>
                                                    <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . $item['items'] . '</td>
                                                    <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . date('d-m-Y', strtotime($item['bill_date'])) . '</td>
                                                    <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . date('d-m-Y', strtotime($item['return_date'])) . '</td>
                                                    <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . $item['added_by'] . '</td>
                                                    <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' .$invoiceId . ',' . $salesReturnId . ')">' . $item['refund_amount'] . '</td>
                                                    <td>
                                                        <a href="sales-return-edit.php?invoice=' . $invoiceId . '&salesReturnId=' . $salesReturnId . '" class="text-primary ml-4"><i class="fas fa-edit"></i></a>
                                                        <a class="text-danger ml-2" onclick="cancelSalesReturn(this)" id="'.$salesReturnId.'" ><i class="fas fa-window-close"></i></a>
                                                    </td> 
                                                </tr>';
                                            }
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

        <!-- Return View Modal" -->
        <div class="modal fade" id="viewReturnModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Return Items</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="viewReturnModalBody">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal" -->
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

    <!-- Page level plugins -->
    <script src="<?= PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script>

    <script>
        const viewReturnItem = (invoice, id) => {
            // alert(invoice);
            // alert(id);
            var xmlhttp = new XMLHttpRequest();
            let url = `ajax/viewSalesReturn.ajax.php?invoice=${invoice}&id=${id}`;
            xmlhttp.open("GET", url, false);
            xmlhttp.send(null);
            document.getElementById('viewReturnModalBody').innerHTML = xmlhttp.responseText
        }
    </script>

    <script>
        const cancelSalesReturn = (t) => {
            // alert(t.id);
            cancelId = t.id;
            swal({
                    title: "Are you sure?",
                    text: "Do you really cancel theis transaction?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
            .then((willDelete) => {
                if (willDelete) {

                    $.ajax({
                        url: "ajax/salesReturnCancle.ajax.php?",
                        type: "POST",
                        data:{
                            id: cancelId
                        },
                        success: function(response) {
                            if (response.includes('1')) {
                                swal(
                                    "Canceled",
                                    "Transaction Has Been Canceled",
                                    "success"
                                ).then(function() {
                                    $(t).closest("tr").fadeOut()
                                    // window.location.reload();
                                });

                            } else {
                                swal("Failed", "Transaction Deletion Failed!",
                                    "error");
                                $("#error-message").html("Deletion Field !!!")
                                    .slideDown();
                                $("success-message").slideUp();
                            }
                        }
                    });
                }
                return false;
            });
        }
    </script>
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

</body>

</html>