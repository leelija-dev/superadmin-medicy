<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR."dbconnect.php";
require_once SUP_ADM_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR."encrypt.inc.php";
require_once CLASS_DIR."doctors.class.php";
require_once CLASS_DIR.'stockOut.class.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR."patients.class.php";


$Doctors        = new Doctors();
$StockOut       = new StockOut();
$Products       = new Products();
$Patients       = new Patients();
$Manufacturer   = new Manufacturer();



if ($_GET['id']) {
    $billId     = url_dec($_GET['id']);
    $stockOut   = $StockOut->stockOutDisplayById($billId);

    $invoiceId      = $stockOut[0]['invoice_id'];
    $patientId      = $stockOut[0]['customer_id'];

    // $patientName = $patientId;

    // echo $patientId; exit;
    if ($patientId != 'Cash Sales') {
        $patientName = json_decode($Patients->patientsDisplayByPId($patientId));
        $patientName = $patientName->name;
    }


    $reffby         = $stockOut[0]['reff_by'];
    $items          = $stockOut[0]['items'];
    $temQtys        = $stockOut[0]['qty'];
    $totalMrp       = $stockOut[0]['mrp'];
    $totalGSt       = $stockOut[0]['gst'];
    $billAmout      = $stockOut[0]['amount'];
    $pMode          = $stockOut[0]['payment_mode'];
    $billdate       = $stockOut[0]['bill_date'];
    

    $details = $StockOut->stockOutDetailsDisplayById($billId);
    $countStockOut = count($details);
    
    $stockOutDetails = $StockOut->stockOutDetailsDisplayById($billId);
    
    //=============== doctor data =================
    $doctor = $Doctors->showDoctors();
    $doctor = json_decode($doctor, true);
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
        <link href="<?= CSS_PATH ?>sb-admin-2.css" rel="stylesheet">
        <!-- <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.css"> -->

        <!-- Custom CSS  -->
        <link rel="stylesheet" href="<?= CSS_PATH ?>update-sales.css">

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
                        <!-- mb-md-5 -->
                        <div class="card ">
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
                                                    <input type="date" class="bill-date" id="bill-dt" value="<?php echo $billdate; ?>" onchange="getDate(this.value)">
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
                                                    <input type="text" class="customer-search" id="customer" placeholder="Customer Name/Mobile" value="<?php echo $patientName; ?>" onkeyup="getCustomer(this.value)">

                                                    <div id="customer-list"></div>
                                                </div>
                                                <div class="col-md-4 col-4" onclick="counterBill()">
                                                    <div class="rounded counter-bill">
                                                        Counter Bill <i class="fas fa-plus-circle"></i></div>
                                                    <div class="contact-box">
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
                                                    <select class="doctor-select" id="doctor-select">
                                                        <option value="<?php echo $reffby; ?>" selected disabled><?php echo $reffby; ?></option>
                                                        <?php
                                                        if($doctor && $doctor['status'] == 1 && !empty($doctor))
                                                        foreach ($doctor['data'] as $doc) {
                                                            // print_r($row);
                                                            echo $doctorName = $doc['doctor_name'];
                                                            echo '<option value="' . $doctorName . '" style="color: black;">' . $doctorName . '</option>';
                                                        }
                                                        ?>
                                                    </select>

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

                                                        <option value="Cash" <?php if ($pMode == "Cash") {
                                                                                    echo "selected";
                                                                                } ?>> Cash</option>
                                                        <option value="Credit" <?php if ($pMode == "Credit") {
                                                                                    echo "selected";
                                                                                } ?>> Credit
                                                        </option>
                                                        <option value="UPI" <?php if ($pMode == "UPI") {
                                                                                echo "selected";
                                                                            } ?>>
                                                            UPI</option>
                                                        <option value="Card" <?php if ($pMode == "Card") {
                                                                                    echo "selected";
                                                                                } ?>> Card</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <form id='sales-edit-form'>
                                    <div class="row ">
                                        <div class="d-none col-md-1 mt-3 col-6">
                                            <label for="">Invoice Number</label><br>
                                            <input class="sale-inp" type="text" id="invoice-id" readonly>
                                        </div>

                                        <div class="d-none col-md-1 mt-3 col-6">
                                            <label for="">Stock Out Details Id</label><br>
                                            <input class="sale-inp" type="text" id="stock-out-details-id" readonly>
                                        </div>

                                        <div class="d-none col-md-1 mt-3 col-6">
                                            <label for="">Item Id</label><br>
                                            <input class="sale-inp" type="text" id="item-id" readonly>
                                        </div>

                                        <div class="col-md-3 mt-3 col-12">
                                            <label for="">Item Name</label><br>
                                            <input type="any" id="product-id" class="d-none">
                                            <input type="text" id="search-Item" class="sale-inp-item" onkeyup="searchItem(this.value)">
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Batch</label><br>
                                            <input class="sale-inp" type="text" id="batch-no" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Unit/Pack</label><br>
                                            <input class="sale-inp" type="text" id="weightage" readonly>
                                        </div>

                                        <div class="d-none col-md-1 mt-3 col-6">
                                            <label for="">Item Weatage</label><br>
                                            <input class="sale-inp" type="text" id="item-weightage" readonly>
                                        </div>

                                        <div class="d-none col-md-1 mt-3 col-6">
                                            <label for="">Item Unit</label><br>
                                            <input class="sale-inp" type="text" id="item-unit" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Expiry</label><br>
                                            <input class="sale-inp" type="text" id="exp-date" readonly>

                                        </div>
                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">MRP</label><br>
                                            <input class="sale-inp" type="text" id="mrp" readonly>
                                        </div>

                                        <div class="d-none col-md-1 mt-3 col-6">
                                            <!-- Available qty on batch no -->
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Availability</label><br>
                                            <input class="sale-inp" type="text" id="aqty">
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Qty.</label><br>
                                            <input class="sale-inp" type="number" id="qty" onkeyup="onQty(this.value)">
                                        </div>

                                        <div class="d-none col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Typ Chk.</label><br>
                                            <input class="sale-inp" type="text" id="type-check" disabled>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Disc%</label><br>
                                            <input class="sale-inp" type="any" id="disc" onkeyup="ondDisc(this.value)">

                                        </div>
                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">D.Price</label><br>
                                            <input class="sale-inp" type="any" id="dPrice" readonly>

                                        </div>
                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">GST%</label><br>
                                            <input class="sale-inp" type="text" id="gst" readonly>
                                        </div>

                                        <div class="d-none col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Taxable</label><br>
                                            <input class="sale-inp" type="text" id="taxable" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-12">
                                            <label for="">Amount</label><br>
                                            <input class="sale-inp" type="any" id="amount" readonly>

                                        </div>

                                    </div>

                                    <div class="d-flex col-md-12">
                                        <div class="p-2 bg-light col-md-6" id="searched-items" style="max-height: 15rem; max-width: 100%; overflow: auto; transition: 30ms; box-shadow: 0 5px 8px rgb(0 0 6 / 28%); display: none;">
                                        </div>

                                        <div class="p-2 bg-light col-md-3" id="select-batch" style="max-height: 7rem; max-width: 30rem; margin-left: 19rem; overflow: auto; display: none; transition: 30ms; box-shadow: 0 5px 8px rgb(0 0 6 / 28%);">
                                        </div>
                                    </div>



                                    <div id="exta-details">
                                        <div class=" row mt-4">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12 col-12 d-flex">
                                                        <label for="">Manf:</label><br>
                                                        <input class="d-none sale-inp" type="any" id="manuf" style="border-width: 0px;" readonly >
                                                        <input class="sale-inp" type="any" id="manufName" style="border-width: 0px; width:30rem; margin-top: -.6rem; word-wrap: break-word;" readonly>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 d-flex" style="word-wrap: break-word;">
                                                        <label for="" style="margin-top: 5px;">Content:</label>
                                                        <input class="sale-inp" type="textarea" id="productComposition" style="border-width: 0px;  width: 30rem; word-wrap: break-word;" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="d-none row mt-3">
                                                    <div class="col-md-4 col-6 mb-4 d-flex">
                                                        <label for="" >Loose Stock:</label>
                                                        <input class="sale-inp" type="any" id="loose-stock" style="border-width: 0px;" readonly>
                                                    </div>
                                                    <div class="col-md-4 col-6 mb-4 d-flex" >
                                                        <label for="" >Loose Price:</label>
                                                        <input class="sale-inp" type="any" id="loose-price" style="border-width: 0px;" readonly>
                                                    </div>
                                                </div>
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
                                                        <button type='button' class="btn btn-sm btn-primary w-100" onclick="addSummary()"><i class="fas fa-check-circle"></i>Add</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </form>




                            </div>
                            <!-- /end Add Product  -->

                            <!-- card mt-md-5 -->
                            <div class=" mb-4  summary">
                                <div class="card-body fisrt-card-body">
                                    <form action="_config/form-submission/new-sell-edit.php" method="post">
                                        <div>
                                            <div class="table-responsive">
                                                <table class="table item-table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col"><input type="number" value="<?php echo $countStockOut; ?>" id="dynamic-id" style="width: 2rem;" class="d-none"></th>
                                                            <th scope="col"><input type="number" value="<?php echo $countStockOut; ?>" id="serial-control" style="width: 2rem;" class="d-none"></th>
                                                            <!-- <th scope="col"></th> -->
                                                            <th scope="col">Item Name</th>
                                                            <th scope="col" hidden>Stock out item details id</th>
                                                            <th scope="col">Batch</th>
                                                            <th scope="col">Unit/Pack</th>
                                                            <th scope="col">Expiry</th>
                                                            <th scope="col">MRP</th>
                                                            <th scope="col">Disc%</th>
                                                            <th scope="col">GST%</th>
                                                            <th scope="col">Qty.</th>
                                                            <th scope="col">Taxable</th>
                                                            <th scope="col">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="item-body">
                                                        <?php
                                                        $slno = 0;
                                                        // foreach ($details as $detail && $stockOutDetails as $stockOutData) 
                                                        for ($i = 0; $i < count($details) &&  $i < count($stockOutDetails); $i++) {

                                                            // print_r($details[$i]);
                                                            // echo "<br><br>";
                                                            // print_r($stockOutDetails[$i]);
                                                            // echo "<br><br>";

                                                            $mrp = $details[$i]['mrp'];

                                                            $itemUnit = $details[$i]['unit'];
                                                            $itemWeatage = $details[$i]['weightage'];
                                                            //========================
                                                            if ($itemUnit == 'tab' || $itemUnit == 'cap') {
                                                                $qty = $details[$i]['loosely_count'];
                                                                $MRP = floatval($mrp) / intval($itemWeatage);
                                                                $billAmountPerItem = floatval($MRP) * intval($qty);

                                                                if ((intval($qty) % intval($itemWeatage)) == 0) {
                                                                    $qtyType = 'Pack';
                                                                } else {
                                                                    $qtyType = 'Loose';
                                                                }
                                                            } else {
                                                                $qty = $details[$i]['qty'];
                                                                $billAmountPerItem = floatval($mrp) * intval($qty);

                                                                $qtyType = '';
                                                            }
                                                            //=======================
                                                            $table = 'product_id';
                                                            $productData = $Products->showProductsById($stockOutDetails[$i]['product_id']);

                                                            //=======================
                                                            $slno = $slno + 1;
                                                            // echo $slno;
                                                            $discPercetn = $details[$i]['discount'];
                                                            $discPrice = (floatval($mrp) - (floatval($mrp) * floatval($discPercetn)/100));
                                                            $discPrice = number_format($discPrice, 2);
                                                        ?>

                                                            <tr id="table-row-<?php echo $slno; ?>">
                                                                <td style="color: red;">
                                                                    <i class="fas fa-trash" onclick="deleteItem(<?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)"></i>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)">
                                                                <?php echo $slno; ?>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)">

                                                                    <input class="summary-items" type="text" name="product-name[]" value="<?php echo $productData[0]['name']; ?>" readonly style="width: 12rem;"> 

                                                                    <input type="text" name="product-id[]" value="<?php echo $stockOutDetails[$i]['product_id']; ?>" class="d-none">

                                                                    <input type="text" name="item-id[]" value="<?php echo $details[$i]['item_id']; ?>" class="d-none">

                                                                    <input type="text" name="Manuf[]" value="<?php echo $productData[0]['manufacturer_id']; ?>" class="d-none">

                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)" class="d-none">

                                                                    <input class="summary-items" type="text" name="stockOut-details-id[]" value="<?php echo $stockOutDetails[$i]['id']; ?>" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)">

                                                                    <input class="summary-items" type="text" name="batch-no[]" value="<?php echo $details[$i]['batch_no']; ?>" readonly style="width: 6rem;">
                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)">

                                                                    <input class="summary-items" type="text" name="weightage[]" value="<?php echo $details[$i]['weightage'].$itemUnit ; ?>" readonly>

                                                                    <input class="d-none summary-items" type="text" name="ItemUnit[]" value="<?php echo $itemUnit; ?>" readonly>

                                                                    <input class="d-none summary-items" type="text" name="ItemPower[]" value="<?php echo $itemWeatage; ?>" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="exp-date[]" value="<?php echo $details[$i]['exp_date']; ?>" readonly style="width: 4rem;">
                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="mrp[]" value="<?php echo $mrp; ?>" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="disc[]" value="<?php echo $details[$i]['discount']; ?>" readonly>

                                                                    <input class="d-none summary-items" type="text" name="dPrice[]" value="<?php echo $discPrice; ?>" readonly >
                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="gst[]" value="<?php echo $details[$i]['gst']; ?>%" readonly>

                                                                    <input type="text" style="width: 3rem;" name="gst-amount[]" value="<?php echo $details[$i]['gst_amount']; ?>" class="d-none">
                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="qty[]" value="<?php echo $qty; ?>" readonly>

                                                                    <input class="d-none summary-items" type="text" name="qty-type[]" value="<?php echo $qtyType; ?>" readonly  >
                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="taxable[]" value="<?php echo $details[$i]['taxable']; ?>" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="amount[]" value="<?php echo $details[$i]['amount']; ?>" readonly>
                                                                </td>

                                                                <!-------- extra data --------->

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)"  class="d-none">
                                                                    <input class="summary-items" type="text" name="extra1[]" value="" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)"  class="d-none">
                                                                    <input class="summary-items" type="text" name="extra2[]" value="" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)"  class="d-none">
                                                                    <input class="summary-items" type="text" name="ptr[]" value="<?php echo $stockOutDetails[$i]['ptr']; ?>" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $stockOutDetails[$i]['id']; ?>, <?php echo $details[$i]['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $details[$i]['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $details[$i]['amount'] ?>)"  class="d-none">
                                                                    <input class="summary-items" type="text" name="margin[]" value="<?php echo $stockOutDetails[$i]['margin']; ?>" readonly>
                                                                </td>

                                                            </tr>

                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>

                                            </div>

                                            <div class="listed-sumary p-3 text-light rounded">
                                                <div class="row mb-3">
                                                    <div class="col-md-2 col-6 mb-3 d-flex">
                                                        Items: <input class="sumary-inp" id="items" value="<?php echo $countStockOut; ?>" type="text" name="total-items">
                                                    </div>


                                                    <div class="col-md-2 col-6  mb-3 d-flex">
                                                        Quantity: <input class="sumary-inp" id="final-qty" value="<?php echo $temQtys; ?>" type="text" name="total-qty">
                                                    </div>
                                                    <div class="col-md-2 col-6  mb-3 d-flex">
                                                        GST: <input class="sumary-inp" id="total-gst" value="<?php echo $totalGSt; ?>" type="text" name="total-gst">
                                                    </div>
                                                    <div class="col-md-3 col-6  mb-3 d-flex">
                                                        Total: <input class="sumary-inp" id="total-price" value="<?php echo $totalMrp; ?>" type="text" name="total-mrp">
                                                    </div>

                                                    <div class="col-md-3 d-flex">
                                                        Payable: <input class="sumary-inp" id="payable" value="<?php echo $billAmout; ?>" type="any" name="bill-amount">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-2 col-6  mb-3 b-right d-flex">
                                                        <span>
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                                                        <input class="sumary-inp" id="final-bill-date" type="text" name="bill-date" value="<?php echo $billdate; ?>">
                                                    </div>

                                                    <div class="col-md-3 col-6  mb-3 b-right d-flex">
                                                        <span>
                                                            <i class="fas fa-user"></i>
                                                        </span>
                                                        <input class="sumary-inp" type="text" id="customer-name" name="customer-name" value="<?php echo $patientName; ?>" readonly>

                                                        <input class="d-none" type="text" id="customer-id" name="customer-id" value="<?php echo $patientId; ?>">

                                                        <input class="d-none" type="text" id="invoice-id" name="invoice-id" value="<?php echo $invoiceId; ?>">

                                                    </div>

                                                    <div class="col-md-3 col-8  mb-3 b-right d-flex">
                                                        <span>
                                                            <i class="fas fa-stethoscope"></i>
                                                        </span>
                                                        <input class="sumary-inp" type="text" id="final-doctor-name" name="final-doctor-name" value="<?php echo $reffby; ?>" readonly>
                                                    </div>

                                                    <div class="  col-md-2 col-4  mb-3 b-right d-flex">
                                                        <span>
                                                            <i class="fas fa-wallet"></i>
                                                        </span>
                                                        <input class="sumary-inp" type="text" id="final-payment" name="payment-mode" value="<?php echo $pMode; ?>" readonly>
                                                    </div>

                                                    <div class="col-md-2  mb-3">
                                                        <div class="d-md-flex justify-content-end">
                                                            <button type="submit" name="update" class="btn btn-sm btn-primary w-100" id="update-sales-btn">Update Bill</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
        <div class="modal fade" id="add-customer-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria- ="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria- ="true">&times;</span>
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

        <!-- Bootstrap core JavaScript-->
        <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
        <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
        <!-- Core plugin JavaScript-->
        <!-- <script src="../assets/jquery-easing/jquery.easing.min.js"></script> -->

        <!-- Custom scripts for all pages-->
        <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
        <!-- <script src="../js/ajax.custom-lib.js"></script> -->
        <!-- <script src="../js/sweetAlert.min.js"></script> -->
        <script src="<?= JS_PATH ?>update-sales.js"></script>



        <script>
            const datePick = () => {
                console.log("Clicked");
                document.getElementById("bill-date").focus();
            }
        </script>

    </body>


    </html>

<?php
}
?>