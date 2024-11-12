<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR."doctors.class.php";

$Doctors = new Doctors();

$doctor = $Doctors->showDoctors();
$doctor = json_decode($doctor , true);
// print_r($doctor);
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
    <!-- Custom CSS  -->
    <link href="<?= CSS_PATH ?>new-sales.css" rel="stylesheet">

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
                    <!-- <h1 class="h3 mb-4 text-gray-800">Sell Items</h1> -->

                    <!-- Add Product -->
                    <div class="card mb-md-5">
                        <div class="card-body fisrt-card-body">
                            <div class="bill-head p-3 text-light rounded">
                                <div class="row ">

                                    <div class="col-md-3   b-right date">
                                        <div class="row mt-3 mb-3">
                                            <div class="col-md-3 col-2  circle-bg text-light" onclick="datePick();">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                            <div class="col-md-9 col-10 ">
                                                <label for="">Bill Date</label><br>
                                                <input type="date" class="bill-date" id="bill-date" onchange="getDate(this.value)">

                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-4  b-right customer">

                                        <div class="row mt-3">
                                            <div class="col-md-2 col-2 circle-bg text-light">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <label for="">Customer</label><br>
                                                <input type="text" class="customer-search" id="customer" placeholder="Customer Name/Mobile" onkeyup="getCustomer(this.value)">
                                                <div id="customer-list">

                                                </div>
                                            </div>
                                            <div class="col-md-4 col-4" onclick="counterBill()">
                                                <div class="rounded counter-bill">
                                                    <p d-none d-md-block>Counter Bill</p> <i class="fas fa-plus-circle"></i></div>
                                                <div class="d-none contact-box">
                                                    <span id="contact"></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-3  b-right doctor">

                                        <div class="row mt-3">
                                            <div class="col-md-3 col-2 circle-bg text-light ">
                                                <i class="fas fa-stethoscope"></i>
                                            </div>
                                            <div class="col-md-9 col-10">
                                                <label for="">Doctor</label><br>

                                                <!-- <input class="customer-search" list="datalistOptions" id="doctor-name" placeholder="Doctor Name" onclick="getDoctor(this.value)"> -->

                                                <select class="doctor-select" id="doctor-select">
                                                    <option value="" selected disabled>Select Doctor</option>
                                                    <?php
                                                    if($doctor && $doctor['status'] == 1 && !empty($doctor))
                                                    foreach ($doctor['data'] as $doc) {
                                                        // print_r($row);
                                                        echo $doctorName = $doc['doctor_name'];
                                                        echo '<option value="' . $doctorName . '" style="color: black;">' . $doctorName . '</option>';
                                                    }
                                                    ?>
                                                </select>

                                                <!-- <div class="p-2 bg-light" id="select-doctor" style="max-height: 20rem; max-width: 98%; background: #8584e9;" >
                                                <datalist id="datalistOptions">
                                                    <?php
                                                    if($doctor && $doctor['status'] == 1 && !empty($doctor))
                                                    foreach ($doctor['data'] as $row) {
                                                        echo '<option value="' . $row['doctor_name'] . '">';
                                                    }
                                                    ?>
                                                </datalist> --
                                                </div> -->

                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-2 payment">
                                        <div class="row mt-3">
                                            <div class=" col-md-2 col-2 payment-icon circle-bg">
                                                <i class="fas fa-money-check-alt"></i>
                                            </div>
                                            <div class="col-md-10 col-10 payment-option">
                                                <label for="">Payment Mode</label><br>
                                                <select class="payment-mode" id="payment-mode" onchange="getPaymentMode(this.value)">
                                                    <option value="" selected disabled>Select Payment</option>
                                                    <option value="Cash"> Cash</option>
                                                    <option value="Credit"> Credit</option>
                                                    <option value="UPI"> UPI</option>
                                                    <option value="Card"> Card</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <form id="add-item-details">
                                <div>
                                    <div class="row">
                                        <div class="col-md-3 mt-3 col-12">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Item Name</label><br>
                                            <input type="any" id="product-id" style="display: none;">
                                            <input type="text" id="product-name" class="sale-inp-item" onkeyup="searchItem(this.value)" onkeydown="chekForm()" autocomplete="off">
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Batch</label><br>
                                            <input type="any" id="batch_no" style="display: none;">
                                            <input class="sale-inp" type="text" id="batch-no" readonly>
                                        </div>

                                        <div class="d-none col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Current Stock Item Id</label><br>
                                            <input class="sale-inp" type="text" id="crnt-stck-itm-id" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Unit/Pack</label><br>
                                            <input class="sale-inp" type="text" id="weightage" readonly>
                                        </div>

                                        <div class="d-none col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Weightage</label><br>
                                            <input class="sale-inp" type="text" id="item-weightage" readonly>
                                        </div>

                                        <div class="d-none col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Unit typ</label><br>
                                            <input class="sale-inp" type="text" id="item-unit-type" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Expiry</label><br>
                                            <input class="sale-inp" type="text" id="exp-date" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">MRP</label><br>
                                            <input class="sale-inp" type="text" id="mrp" readonly>
                                        </div>

                                        <div class="d-none col-md-1 mt-3 col-6">
                                            <!-- Available qty on batch no -->
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Availability</label><br>
                                            <input class="sale-inp" type="text" id="aqty">
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Qty.</label><br>
                                            <input class="sale-inp" type="number" id="qty" onkeyup="onQty(this.value)">
                                        </div>

                                        <div class="d-none col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Typ Chk.</label><br>
                                            <input class="sale-inp" type="text" id="type-check" disabled>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem;  font-weight: bold;">Disc%</label><br>
                                            <input class="sale-inp" type="any" id="disc" onkeyup="ondDisc(this.value)">
                                        </div>
                                        <div class="d-none col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">D.Price</label><br>
                                            <input class="sale-inp" type="any" id="dPrice" readonly>
                                        </div>
                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">GST%</label><br>
                                            <input class="sale-inp" type="text" id="gst" readonly>
                                        </div>

                                        <!-- <div class=" col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Taxable Calculate</label><br>
                                            <input class="sale-inp" type="text" id="taxableAmnt" readonly>
                                        </div> -->

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Taxable</label><br>
                                            <input class="sale-inp" type="text" id="taxable" readonly>
                                        </div>
                                        
                                        <div class="col-md-1 mt-3 col-12">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Nt AMT</label><br>
                                            <input class="sale-inp" type="any" id="amount" readonly>
                                        </div>

                                        <div class="d-flex col-md-12">
                                            <div class="p-2 bg-light col-md-6" id="searched-items" style="max-height: 15rem; max-width: 100%; overflow: auto; display: none; transition: 30ms; box-shadow: 0 5px 8px rgb(0 0 6 / 28%);">
                                            </div>

                                            <div class="p-2 bg-light col-md-3" id="searched-batchNo" style="max-height: 7rem; max-width: 30rem; margin-left: 19rem; overflow: auto; display: none; transition: 30ms; box-shadow: 0 5px 8px rgb(0 0 6 / 28%);">
                                            </div>
                                        </div>
                                    </div>



                                    <div id="exta-details">
                                        <div class=" row mt-4">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12 col-12 d-flex">
                                                        <label for="">Manf:</label><br>
                                                        <input class="d-none sale-inp" type="any" id="manuf" style="border-width: 0px;" readonly>
                                                        <input class="sale-inp" type="any" id="manufName" style="border-width: 0px; width:30rem; margin-top: -.6rem; word-wrap: break-word;" readonly>
                                                    </div>

                                                    <div class="col-md-12 d-flex" style="word-wrap: break-word;">
                                                        <label for="" style="margin-top: 5px;">Content:</label>
                                                        <input class="sale-inp" type="textarea" id="productComposition" style="border-width: 0px;  width: 30rem; word-wrap: break-word;" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                                <div class="row mt-3">
                                                    <div class="col-md-4 col-6 mb-4 d-flex">
                                                        <label for="" style="margin-top: 6px;">PTR:</label>
                                                        <input class="sale-inp" type="any" id="ptr" style="border-width: 0px;" readonly>
                                                    </div>

                                                    <div class="col-md-4 col-6 mb-4 d-flex">
                                                        <label for="" style="margin-top: 6px;">Margin:</label>
                                                        <input class="sale-inp" type="any" id="margin" style="border-width: 0px;" readonly>
                                                    </div>

                                                    <div class="col-md-4 col-6 mb-4 d-flex justify-content-end">
                                                        <button type="button" class="btn btn-sm btn-primary w-100" onclick="addSummary()"><i class="fas fa-check-circle"></i>
                                                            Add</button>
                                                    </div>
                                                </div>

                                                <div class="d-none row mt-3">
                                                    <!-- <div class="col-md-4  col-6 mb-4 d-flex">
                                                        <label for="" style="margin-top: 9px;">Qty.Type:</label>
                                                        -- <input class="sale-inp" type="any" id="qty-type" style="border-width: 0px;" readonly> --
                                                        <select class="sale-inp qty-type" id="qty-type" onchange="mrpUpdate(this.value);" disabled>
                                                            <option value="" selected disabled>Select</option>
                                                            <option value="Pack">Pack</option>
                                                            <option value="Loose">Loose</option>
                                                        </select>
                                                    </div> -->
                                                    <div class="col-md-4 col-6 mb-4 d-flex">
                                                        <label for="">Loose Stock:</label>
                                                        <input class="sale-inp" type="any" id="loose-stock" style="border-width: 0px;" readonly>
                                                    </div>
                                                    <div class="col-md-4 col-6 mb-4 d-flex">
                                                        <label for="">Loose Price:</label>
                                                        <input class="sale-inp" type="any" id="loose-price" style="border-width: 0px;" readonly>
                                                    </div>
                                                </div>
                                    
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /end Add Product  -->

                    <div class="card mb-4 mt-md-5 summary">
                        <div class="card-body fisrt-card-body">
                            <h3 class="text-center font-weight-bolder listed-heading">Listed Items For sale</h3>
                            <form action="_config/form-submission/item-invoice.php" method="post">
                                <div>
                                    <div class="table-responsive">
                                        <table class="table item-table">
                                            <thead>
                                                <tr>
                                                    <th scope="col" style="width: .3rem; padding: .5rem;">
                                                        <input type="number" value="0" id="dynamic-id" class="d-none" style="width: 2rem;">
                                                    </th>
                                                    <th scope="col" style="width: .3rem; padding: .5rem;" class="d-none">
                                                        <input type="number" value="0" id="serial-control" style="width: 2rem;">
                                                    </th>
                                                    <th scope="col"></th>
                                                    <th scope="col">Item Name</th>
                                                    <th scope="col" hidden>Item id</th>
                                                    <th scope="col" class="d-none">Manuf Data</th>
                                                    <th scope="col">Batch</th>
                                                    <th scope="col">Unit/Pack</th>
                                                    <th scope="col" class="d-none">Weightage</th>
                                                    <th scope="col" class="d-none">Unit Type</th>
                                                    <th scope="col">Expiry</th>
                                                    <th scope="col" style="text-align: center;">MRP</th>
                                                    <th scope="col" class="d-none">PTR.</th>
                                                    <th scope="col" class="d-none">Qty.Typ</th>
                                                    <th scope="col">Qty.</th>
                                                    <th scope="col">Disc%</th>
                                                    <th scope="col" class="d-none">NET AFTER DISC</th>
                                                    <th scope="col">Taxable</th>
                                                    <th scope="col">GST%</th>
                                                    <th scope="col" class="d-none">GST AMOUNT</th>
                                                    <th scope="col" class="d-none">MARGIN</th>
                                                    <th scope="col">Net Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="item-body">

                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-center">
                                            <h3 id="no-item">No Item Found</h3>
                                        </div>
                                    </div>

                                    <div class="listed-sumary p-3 text-light rounded">
                                        <div class="row mb-3">
                                            <div class="col-md-2 col-6   mb-3 d-flex">
                                                Items: <input class="sumary-inp" id="items" value="00" type="text" name="total-items">
                                            </div>
                                            <div class="col-md-2 col-6  mb-3 d-flex">
                                                Quantity: <input class="sumary-inp" id="final-qty" value="00" type="text" name="total-qty">
                                            </div>
                                            <div class="col-md-2 col-6  mb-3 d-flex">
                                                GST: <input class="sumary-inp" id="total-gst" value="00" type="text" name="total-gst">
                                            </div>
                                            <div class="col-md-3 col-6  mb-3 d-flex">
                                                Total: <input class="sumary-inp" id="total-price" value="00" type="text" name="total-mrp">
                                            </div>

                                            <div class="col-md-3 d-flex">
                                                Payable: <input class="sumary-inp" id="payable" value="00" type="any" name="bill-amount">
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-2 col-6  mb-3 b-right d-flex">
                                                <span>
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                                <input class="sumary-inp" id="final-bill-date" type="text" name="bill-date">
                                            </div>

                                            <div class="col-md-3 col-6  mb-3 b-right d-flex">
                                                <span>
                                                    <i class="fas fa-user"></i>
                                                </span>
                                                <input class="sumary-inp" type="text" id="customer-name" name="customer-name" readonly>
                                                <input class="d-none" type="text" id="customer-id" name="customer-id">
                                            </div>

                                            <div class="col-md-3 col-8  mb-3 b-right d-flex">
                                                <span>
                                                    <i class="fas fa-stethoscope"></i>
                                                </span>
                                                <input class="sumary-inp" type="text" id="final-doctor-name" name="doctor-name" readonly>
                                            </div>
                                            <div class="  col-md-2 col-4  mb-3 b-right d-flex">
                                                <span>
                                                    <i class="fas fa-wallet"></i>
                                                </span>
                                                <input class="sumary-inp" type="text" id="final-payment" name="payment-mode" readonly>
                                            </div>
                                            <div class="col-md-2  mb-3">
                                                <div class="d-md-flex justify-content-end">
                                                    <button type="submit" name="submit" class="btn btn-sm btn-primary w-100" id="new-sell-bill-generate">Generate Bill</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once SUP_ROOT_COMPONENT.'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->


    <!--============= Add New Customer Modal =============-->
    <!-- Modal -->
    <div class="modal fade" id="add-customer-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body add-customer-modal">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!--============= End Add New Customer Modal =============-->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
    
    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
    <script src="<?= JS_PATH ?>new-sales.js"></script>



    <script>
        const datePick = () => {
            console.log("Clicked");
            document.getElementById("bill-date").focus();
        }
    </script>

</body>


</html>