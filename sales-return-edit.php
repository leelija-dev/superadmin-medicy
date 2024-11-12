<?php
require_once __DIR__.'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR."doctors.class.php";
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'distributor.class.php';
require_once CLASS_DIR.'measureOfUnit.class.php';
require_once CLASS_DIR.'packagingUnit.class.php';
require_once CLASS_DIR.'salesReturn.class.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'stockOut.class.php';
$page = "sales";



//class Initilization
$Products           = new Products();
$Distributor        = new Distributor();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();
$SalesReturnTable   = new SalesReturn();
$Stockout           = new StockOut();
$patientDetails     = new Patients();


//function's called
$showProducts          = $Products->showProducts();
$showDistributor       = $Distributor->showDistributor();
$showMeasureOfUnits    = $MeasureOfUnits->showMeasureOfUnits();
$showPackagingUnits    = $PackagingUnits->showPackagingUnits();

$InvoiceId = $_GET['invoice'];
$salesReturnId = $_GET['salesReturnId'];

$id = "id";
//echo $salesReturnId;

$StockoutDetails = $Stockout->stockOutDisplayById($InvoiceId);
$refferBy = $StockoutDetails[0]['reff_by'];
$salesReturnTableDetails = $SalesReturnTable->selectSalesReturn($id, $salesReturnId);
$BillDate = $salesReturnTableDetails[0]['bill_date'];

$patientId = $salesReturnTableDetails[0]['patient_id'];

if ($patientId == 'Cash Sales') {
    $patientName = "Cash Sales";
} else {
    $patientData = json_decode($patientDetails->patientsDisplayByPId($patientId));
    $patientName = $patientData->name;
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

    <title>SB Admin 2 - Blank</title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">

    <!-- Custom CSS  -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/stock-in.css">
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include ROOT_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include ROOT_COMPONENT.'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"> Sales Return</h1>

                    <!-- Add Product -->
                    <div class="card shadow mb-5">
                        <div class="card-body">

                            <!--============= select Bill and details =============-->
                            <div class="row">
                                <div class="col-md-2 col-6 mt-3">

                                    <label class="mb-0 mt-2" for="invoice-no">Invoice No.</label>
                                    <input type="text" class="upr-inp" name="invoice-no" id="invoiceID" value="<?php echo $InvoiceId ?>" autocomplete="off" readonly>
                                </div>

                                <div class="  col-md-4 col-6 mt-3">
                                    <label class="mb-0 mt-2" for="invoice-no">Sales Return Id.</label>
                                    <input type="text" class="upr-inp" name="sales-return-id" id="sales-return-id" value="<?php echo $salesReturnId ?>" autocomplete="off" readonly>
                                </div>

                                <div class="col-md-2 col-6 mt-3">
                                    <label class="mb-0 mt-2" for="patient-name">Patient Name.</label>
                                    <input type="text" class="upr-inp" name="patient-name" id="patient-name" value="<?php echo $patientName ?>" placeholder="Select Invoice First." autocomplete="off" readonly>
                                </div>

                                <div class="col-md-2 col-6 mt-3 ">
                                    <label class="mb-0 mt-2" for="bill-date">Bill Date</label>
                                    <input type="text" class="upr-inp" name="" id="bill-date" value="<?php echo $BillDate ?>" autocomplete="off" readonly>
                                </div>

                                <div class="col-md-2 col-6 mt-3 ">
                                    <label class="mb-0 mt-2" for="reff-by">Reffer By</label>
                                    <input type="text" class="upr-inp" id="reff-by" value="<?php echo $refferBy ?>" autocomplete="off" readonly>
                                </div>

                                <div class="col-md-2 col-12 mt-3 ">
                                    <label class="mb-0 mt-2" for="refund-mode">Refund Mode</label>
                                    <select class="upr-inp" id="refund-mode" onchange="getRefundMode(this.value);">
                                        <option value="" selected disabled>Select</option>
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
                                <div class="col-md-2 col-6 mt-3 ">
                                    <label class="mb-0 mt-2" for="bill-date">Return Date</label>
                                    <input type="date" class="upr-inp" name="" id="select-return-date" onchange="getReturnDate(this.value)" autocomplete="off">
                                </div>
                            </div>



                            <!--============= end select Bill and details =============-->

                            <div class="row">
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="items-list" class="mb-0">Product Name</label>
                                    <select id="items-list" class="upr-inp mt-1" onchange="getEditItemDetails(this);">
                                        <option value="" selected disabled>Select Invoice Number First</option>
                                    </select>
                                </div>
                                <div id="bills-list" class="row mt-1 m-0"></div>
                            </div>

                            <form id='sales-return-edit-item-details'>
                                <div class="row">
                                    <div class="d-none col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="sales-return-details-item-id">Sales Return Detaisl Item Id</label>
                                        <input type="text" class="upr-inp" id="sales-return-details-item-id" readonly>
                                    </div>
                                    <div class="d-none col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="current-stock-item-id">Curretn Stock Item Id</label>
                                        <input type="text" class="upr-inp" id="current-stock-item-id" readonly>
                                    </div>
                                    <div class="d-none col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="product-id">Product Id</label>
                                        <input type="text" class="upr-inp" id="product-id" readonly>
                                    </div>
                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="exp-date">Expiry</label>
                                        <input type="text" class="upr-inp" id="exp-date" readonly style="font-size: 0.85rem;">
                                    </div>
                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="unit"> Unit</label>
                                        <input type="text" class="upr-inp" id="unit" value="" readonly>
                                    </div>

                                    <div class="d-none col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="unit-type"> Unit Type</label>
                                        <input type="text" class="upr-inp" id="unit-type" value="" readonly>
                                    </div>

                                    <div class="d-none col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="item-weatage"> Item Weatage</label>
                                        <input type="text" class="upr-inp" id="item-weatage" value="" readonly>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="batch-no">Batch</label>
                                        <input type="text" class="upr-inp" name="batch-no" id="batch-no" readonly>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="mrp">MRP</label>
                                        <input type="text" class="upr-inp" name="mrp" id="mrp" readonly>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="ptr">PTR</label>
                                        <input type="text" class="upr-inp" name="ptr" id="ptr" readonly>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="gst">GST%</label>
                                        <input type="text" class="upr-inp" name="gst" id="gst" readonly>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="discount">Disc% </label>
                                        <input type="text" class="upr-inp" name="discount" id="discount" value="" readonly>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="qty">Sell Qty</label>
                                        <input type="text" class="upr-inp" name="P-qty" id="P-qty" readonly>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="current-qty">Cr Qty</label>
                                        <input type="text" class="upr-inp" name="current-qty" id="current-qty" readonly>
                                    </div>

                                    <div class="d-none col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="prev-rtrn-qty">Prev Rtrn Qty</label>
                                        <input type="text" class="upr-inp" name="prev-rtrn-qty" id="prev-rtrn-qty" readonly>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="return-qty">Return</label>
                                        <input type="number" class="upr-inp" name="return-qty" id="return-qty" value="" onkeyup="getRefund(this.value)" required>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="taxable">Taxable</label>
                                        <input type="any" class="upr-inp" name="taxable" id="taxable" readonly>
                                    </div>

                                    <!-- <div class="col-md-1 col-6 mt-3">
                                    <label class="mb-0 mt-1" for="bill-amount">Amount</label>
                                    <input type="any" class="upr-inp" name="bill-amount" id="bill-amount" readonly required>
                                </div> -->

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="refund">Refund</label>
                                        <input type="any" class="upr-inp" name="refund" id="refund" required readonly>
                                    </div>


                                    <!-- <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                                        <button class="btn btn-primary me-md-2" type="button" onclick="addData()" id="add-btn" style="margin-top: 1rem; height: 2.5rem;">Add <i class="fas fa-plus"></i></button>
                                    </div> -->
                                </div>

                                <div class="row justify-content-end">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                                        <button class="btn btn-primary me-md-2" type="button" onclick="addData()" id="add-btn">Add <i class="fas fa-plus"></i></button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- /end Add Product  -->
                    <!--=========================== Show Bill Items ===========================-->
                    <div class="card shadow mb-4">
                        <form action="_config\form-submission\sales-return-edit.php" method="post">
                            <div class="card-body stock-in-summary">
                                <div class="table-responsive">
                                    <table class="table item-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col" class="d-none">
                                                    <input type="number" value="0" id="dynamic-id" style="width: 1rem;">
                                                </th>
                                                <th scope="col" class="d-none">
                                                    <input type="number" value="0" id="serial-control" style="width: 1rem;">
                                                </th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                                <th scope="col" style="font-size: 0.8rem;">Items</th>
                                                <th scope="col" hidden>Retun Details Item Id</th>
                                                <th scope="col" style="font-size: 0.8rem;">Batch</th>
                                                <th scope="col" style="font-size: 0.8rem;">Exp.</th>
                                                <th scope="col" style="font-size: 0.8rem;">Unit</th>
                                                <th scope="col" style="font-size: 0.8rem;">Sell Qty.</th>
                                                <th scope="col" style="font-size: 0.8rem;">MRP</th>
                                                <th scope="col" style="font-size: 0.8rem;">Disc</th>
                                                <th scope="col" style="font-size: 0.8rem;">GST%</th>
                                                <th scope="col" style="font-size: 0.8rem;">Taxable</th>
                                                <th scope="col" style="font-size: 0.8rem;">Return</th>
                                                <th scope="col" style="font-size: 0.8rem;">Refund</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dataBody">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="  p-3 m-3  font-weight-bold text-light purchase-items-summary rounded">
                                <div class="row ">
                                    <div class="col-md-3 col-6 mb-3 d-flex justify-content-start">
                                        <label for="invoice">Invoice :</label>
                                        <input class="summary-inp w-60" name="invoice" id="invoice" type="text" readonly>

                                        <input class="  summary-inp w-60" name="salesreturn-id" id="salesreturn-id" type="text" value="<?php echo $salesReturnId ?>" readonly>
                                    </div>

                                    <div class="col-md-3 col-6 mb-3 d-flex justify-content-start">
                                        <label for="">Return Date :</label>
                                        <input class="summary-inp w-60" name="return-date" id="return-date" type="text" readonly>
                                        <input class="d-none" name="purchased-date" id="purchased-date" type="text">
                                    </div>
                                    <div class="col-md-2 col-6 mb-3  d-flex justify-content-start">
                                        <p>Items : <input class="summary-inp w-60" name="total-items" id="total-items" type="number" value="0" readonly></p>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>Payment : <input class="summary-inp w-60" name="refund-mode" id="refund-mode-val" type="text" readonly> </p>
                                    </div>

                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>Qty : <input class="summary-inp" name="total-qty" id="total-qty" type="any" value="0" readonly> </p>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>GST : <input class="summary-inp" name="gst-amount" id="gst-amount" type="number" value="0" readonly> </p>
                                    </div>
                                    <div class="col-md-3 mb-2 col-6 mb-2 d-flex justify-content-start">
                                        <p>Refund : <input class="summary-inp" name="refund-amount" id="refund-amount" type="any" value="0" readonly> </p>
                                    </div>
                                    <div class="col-md-2 mb-2 col-6 justify-content-end">
                                        <button class="btn btn-sm btn-primary" id="return-btn" style="width: 100%;" type="submit" name="sales-return-edit">Return</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--=========================== Show Bill Items ===========================-->

                </div>
                <!-- /.container-fluid -->

                <!-- Footer -->
                <?php include_once ROOT_COMPONENT.'footer-text.php'; ?>
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
        <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
        <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
        <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
        <script src="<?= JS_PATH ?>sales-return-edit.js"></script>

        <!-- Include SweetAlert2 JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
        <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>

</body>

</html>