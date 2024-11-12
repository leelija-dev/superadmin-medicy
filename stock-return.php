<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'stockReturn.class.php';
require_once CLASS_DIR . 'employee.class.php';


$page = "stock-return";

//objects Initilization
// $Products           = new Products();
$Distributor        = new Distributor();
$StockReturn        = new StockReturn();
$Employees          = new Employees();

//function's called
$showDistributor       = $Distributor->showDistributor();
$col = 'admin_id';
$stockReturnLists      = json_decode($StockReturn->stockReturnFilter($col));
if($stockReturnLists->status){
    $stockReturnLists = $stockReturnLists->data;
}
// print_r($stockReturnLists);

$empLists              = $Employees->employeesDisplay();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Medicy Items</title>

    <!-- Custom fonts for this template -->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/return-page.css">

    <!-- Datatable Style CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
                    <div class="d-flex justify-content-between">

                    </div>
                    <!-- Add Product -->
                    <div class="card shadow mb-2">
                        <div class="card-body">
                            <div class="row mb-3 ">
                                <div class="col-md-2 col-12">
                                    <label for="">Filter By:</label>

                                </div>

                                <div class="col-md-3 col-12">
                                    <select class="cvx-inp1" name="added_on" id="added_on" onchange="returnFilter(this)">
                                        <option value="" disabled selected>Select Duration</option>
                                        <option value="T">Today</option>
                                        <option value="Y">yesterday</option>
                                        <option value="LW">Last 7 Days</option>
                                        <option value="LM">Last 30 Days</option>
                                        <option value="LQ">Last 90 Days</option>
                                        <option value="CFY">Current Fiscal Year</option>
                                        <option value="PFY">Previous Fiscal Year</option>
                                        <option value="CR">Custom Range </option>
                                    </select>

                                </div>
                                <div class="col-md-2 col-6">
                                    <select class="cvx-inp1" id="added_by" onchange="returnFilter(this)">
                                        <option value="" disabled selected>Select Staff
                                        </option>
                                        <?php
                                        foreach ($empLists as $emp) {
                                            echo '<option value="' . $emp['emp_id'] . '">' . $emp['emp_username'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2 col-6">
                                    <input class="cvx-inp" type="text" placeholder="Distributor Name" name="distributor_id" id="distributor_id" style="outline: none;" onkeyup="returnFilter(this)">
                                </div>
                                <div class="col-md-2 col-6">
                                    <select class="cvx-inp1" name="refund_mode" id="refund_mode" onchange="returnFilter(this)">
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
                                <div class="col-md-1 col-6 text-right">
                                    <a class="btn btn-sm btn-primary " href="stock-return-item.php"> New <i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                            <!-- ============================= date picker div ================================== -->

                            <div id="hiddenDiv" class="hidden" style="display: none;">
                                <div id=" date-range-container">
                                    <label for="start-date">From Date:</label>
                                    <input type="date" id="from-date" name="from-date">
                                    <label for="end-date">To Date:</label>
                                    <input type="date" id="to-date" name="to-date">
                                    <button class="btn btn-sm btn-primary" id="added_on" value="CR" onclick="getDates(this.id, this.value)" style="height: 2rem;">Find</button>
                                </div>
                            </div>

                            <!-- ============================ eof date picker div ================================ -->

                            <div class="table-responsive" id="filter-table">
                                <table class="table table-sm table-hover" id="<?php if (count($stockReturnLists) > 10) {
                                                                                    echo "dataTable";
                                                                                } ?>" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-light">
                                        <tr>
                                            <th>Return Id</th>
                                            <th>Distributor</th>
                                            <th>Return Date</th>
                                            <th>Entry Date</th>
                                            <th>Entry By</th>
                                            <th>Payment Mode</th>
                                            <th>Amount</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-data">
                                        <?php
                                        foreach ($stockReturnLists as $row) {
                                            $dist = json_decode($Distributor->showDistributorById($row->distributor_id));
                                            $dist = $dist->data;

                                            $col = 'emp_id';
                                            $empData = json_decode($Employees->selectEmpByCol($col, $row->added_by));

                                            $empData = $empData->data;

                                            // print_r($empData);

                                            if (count($dist) > 0) {

                                                $returnDate = date("d-m-Y", strtotime($row->return_date));
                                                $entryDate  = date("d-m-Y", strtotime($row->added_on));

                                                $check = '';
                                                if ($row->status == "cancelled") {
                                                    $check  = 'style="background-color:#ff0000; color:#fff"';
                                                }
                                                echo '<tr ' . $check . '>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItems(' . $row->id . ')" >' . $row->id . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItems(' . $row->id . ')">' . $dist[0]->name . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItems(' . $row->id . ')">' . $returnDate . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItems(' . $row->id . ')">' . $entryDate . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItems(' . $row->id . ')">' . $empData[0]->emp_name . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItems(' . $row->id . ')">' . $row->refund_mode . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItems(' . $row->id . ')">' . $row->refund_amount . '</td>
                                                        <td >
                                                            <a class="text-primary ml-4" id="edit-btn-' . $row->id . '" onclick="editReturnItem(' . $row->id . ', this)"><i class="fas fa-edit" ></i></a>
                                                            <a class="text-danger ml-2" id="cancel-btn-' . $row->id . '" onclick="cancelPurchaseReturn(' . $row->id . ', this)"><i class="fas fa-window-close" ></i></a>
                                                        </td>
                                                    </tr>';
                                            } else {
                                                echo '<tr><td>No Matching Data Found</td></tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!--=========================== Show Bill Items ===========================-->


                </div>
                <!-- /.container-fluid -->
                <!-- End of Main Content -->
            </div>
            <!-- End of Content Wrapper -->

            <!-- Footer -->
            <?php include_once SUP_ROOT_COMPONENT.'footer-text.php'; ?>
            <!-- End of Footer -->

            <!-- go to stock-return-control.js for function contorl -->
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



        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>


        <!-- Bootstrap core JavaScript-->
        <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

        <script src="<?= PLUGIN_PATH ?>product-table/jquery.dataTables.js"></script>
        <script src="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>


        <!-- Page level custom scripts -->
        <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script>

        <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

        <!-- custom script for stock return page -->
        <script src="<?= JS_PATH ?>stock-return-control.js"></script>
</body>

</html>