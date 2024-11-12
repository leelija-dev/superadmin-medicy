<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; 

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR.'_config/healthcare.inc.php';

require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';


$Distributor        = new Distributor();
$showDistributor    = json_decode($Distributor->showDistributor());
$showDistributor = $showDistributor->data;

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
    <!-- <link rel="stylesheet" href="../css/font-awesome-6.1.1-pro.css"> -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/stock-return-item.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">


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
                    <!-- <h1 class="h3 mb-2 text-gray-800"> Purchase Return</h1> -->

                    <!-- Add Product -->
                    <div class="card shadow mb-3">
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-2 col-12 ">

                                    <label class="mb-1 mt-3" for="distributor-name">Distributor :</label>
                                    <input type="text" name="" id="distributor-name" class="upr-inp" placeholder="Select Distributor">


                                    <div class="p-2 bg-light col-md-6 c-dropdown" id="distributor-list">
                                        <?php if (!empty($showDistributor)): ?>
                                        <div class="lists" id="lists">
                                            <?php foreach ($showDistributor as $eachDistributor) {?>
                                            <div class="p-1 border-bottom list" id="<?= $eachDistributor->id ?>"
                                                onclick="setDistributor(this)">
                                                <?= $eachDistributor->name ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                </div>

                                <!-- <div class="col-md-2 col-12 ">
                                    <label for="product-name" class="mb-1 mt-3">Select Bill No.</label>
                                    <input class="upr-inp mb-1" id="select-bill-no" name="select-bill-no" placeholder="Search Bill" onkeyup="getItemList(this.value)" autocomplete="off" readonly>
                                    !-- onchange="getDtls(this);" --
                                    <div class="p-2 bg-light" id="select-bill" style="margin-left: 0rem;box-shadow: 0 5px 10px rgb(0 0 0 / 30%); transition: 3.3s ease; overflow: auto; display: none;  max-width: 100%; min-width: 100%; position: absolute; z-index: 100;">
                                    </div>
                                    <input type="text" id="bill-no" hidden>
                                </div> -->

                                <div class="col-md-8 col-12 ">
                                    <label for="product-name" class="mb-1 mt-3">Product Name</label>
                                    <input class="upr-inp mb-1" id="product-name" name="product-name" placeholder="Search Product" onkeyup="searchItem(this.value)" autocomplete="off">
                                    <!-- onchange="getDtls(this);" -->
                                    <div class="p-2 bg-light " id="product-select">
                                        <div class="m-0 text-danger text-center">
                                            <b> Select Distributor First </b>
                                        </div>
                                    </div>
                                    <input class="d-none" type="text" id="product-id">
                                </div>

                                <div class="col-md-2 col-12 mt-2 mt-md-0 mx-auto">
                                    <label class="mb-1 mt-3" for="return-mode">Return Mode :</label>
                                    <select class="upr-inp" name="return-mode" id="return-mode" onchange="setMode(this.value)">
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
                            </div>


                            <form id='stock-return-item-data'>
                                <div class="row">
                                    <div class="col-md-6 col-12 ">
                                        <div class="row">
                                            <div class="d-none col-md-6 col-12">
                                                <label class="mb-1 mt-3" for="select-item-div">Selected Item Div</label>
                                                <input class="upr-inp mb-1" id="select-item-div" readonly>
                                            </div>

                                            <div class="d-none col-md-6 col-12">
                                                <label class="mb-1 mt-3" for="stokInDetailsId">Stock In Detaisl Id :</label>
                                                <input class="upr-inp mb-1" id="stokInDetailsId" readonly>
                                            </div>
                                           
                                            <div class="col-md-6 col-12">
                                                <label class="mb-1 mt-3" for="bill-number">Bill Number :</label>
                                                <input class="upr-inp mb-1" id="bill-number" readonly>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <label class="mb-1 mt-3" for="batch-number">Batch Number :</label>
                                                <input class="upr-inp mb-1" id="batch-number" readonly>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <label class="mb-1 mt-3" for="bill-date">Purchase Date :</label>
                                                <input class="upr-inp mb-1" id="bill-date" readonly>
                                            </div>

                                            <div class="col-md-4 col-12">
                                                <label class="mb-1 mt-3" for="mfd-date">MFD</label>
                                                <input class="upr-inp mb-1" type="text" id="mfd-date" readonly>
                                            </div>

                                            <div class="col-md-4 col-12">
                                                <label class="mb-1 mt-3" for="exp-date">Expiry</label>
                                                <input class="upr-inp mb-1" type="text" id="exp-date" readonly>
                                            </div>
                                            
                                        </div>

                                        <div class="col-12 mt-3">
                                            <label for="exampleFormControlTextarea1">Description</label>
                                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                        </div>

                                    </div>

                                    <div class="col-md-6 col-12 mt-3">
                                        <!-- first row  -->
                                        <div class="row">
                                            <!-- <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="exp-date">Expiry</label>
                                            <input class="upr-inp" type="text" id="exp-date" readonly>
                                        </div> -->

                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="scheme">Weatage</label>
                                                <input type="any" class="upr-inp" name="weatage" id="weatage" readonly>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="unit"> Unit</label>
                                                <input type="text" class="upr-inp" id="unit" value="" readonly>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="mrp">MRP</label>
                                                <input type="text" class="upr-inp" name="mrp" id="mrp" readonly>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="ptr">PTR</label>
                                                <input type="text" class="upr-inp" name="ptr" id="ptr" onkeyup="getBillAmount()" readonly>
                                            </div>
                                        </div>
                                        <!-- first row end  -->
                                        <!-- second row  -->
                                        <div class="row mt-md-2">
                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="discount">Disc% </label>
                                                <input type="text" class="upr-inp" name="discount" id="discount" value="" readonly>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="gst">GST%</label>
                                                <input type="text" class="upr-inp" name="gst" id="gst" readonly>
                                            </div>

                                            <div class="d-none col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="gstAmountPerQty">GST Amount Per Quantity</label>
                                                <input type="text" class="upr-inp" name="gstAmountPerQty" id="gstAmountPerQty" readonly>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="taxable">Base Price</label>
                                                <input type="any" class="upr-inp" name="base" id="base" readonly>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="taxable">Taxable</label>
                                                <input type="any" class="upr-inp" name="taxable" id="taxable" readonly>
                                            </div>

                                            <!-- <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="mrp">MRP</label>
                                            <input type="text" class="upr-inp" name="mrp" id="mrp" readonly>
                                        </div> -->


                                        </div>
                                        <!-- end second row  -->

                                        <!-- third row  -->
                                        <div class="row mt-md-2">
                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="amount">Amount</label>
                                                <input type="any" class="upr-inp" name="amount" id="amount" readonly>
                                            </div>
                                            <div class="d-none col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="purchased-qty">Buy Qty: </label>
                                                <input type="text" class="upr-inp" name="purchased-qty" id="purchased-qty">
                                            </div>

                                            <div class="d-none col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="free-qty">Free Qty:</label>
                                                <input type="text" class="upr-inp" name="free-qty" id="free-qty">
                                            </div>
                                            <div class="d-none col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="net-buy-qty">Net Buy Qty:</label>
                                                <input type="text" class="upr-inp" name="net-buy-qty" id="net-buy-qty">
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="current-purchase-qty">Qty:</label>
                                                <input type="text" class="upr-inp" name="current-purchase-qty" id="current-purchase-qty">
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="current-free-qty">Free Qty:</label>
                                                <input type="text" class="upr-inp" name="current-free-qty" id="current-free-qty">
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="current-qty">Live Net Qty:</label>
                                                <input type="text" class="upr-inp" name="current-qty" id="current-qty">
                                            </div>
                                        </div>
                                        <!-- ent fo third row -->

                                        <!-- Fourth row 
                                    <div class="row mt-md-2">
                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="current-purchase-qty">Live Qty:</label>
                                            <input type="text" class="upr-inp" name="current-purchase-qty" id="current-purchase-qty">
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="current-free-qty">Live Free Qty:</label>
                                            <input type="text" class="upr-inp" name="current-free-qty" id="current-free-qty">
                                        </div>
                                            <div class="col-md-3 col-6">
                                            <label class="mb-0 mt-1" for="current-qty">Live Net Qty:</label>
                                            <input type="text" class="upr-inp" name="current-qty" id="current-qty">
                                        </div> 
                                    </div> -->
                                        <!-- end fourth row  -->

                                        <!-- fifth row  -->
                                        <div class="row mt-md-2">
                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="return-qty">Return Qty:</label>
                                                <input type="text" class="upr-inp focus-border" id="return-qty" value="" name="return-qty" onkeyup="getRefund(this.value);">
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="return-free-qty">Return F.Qty:</label>
                                                <input type="text" class="upr-inp focus-border" name="return-free-qty" id="return-free-qty" value="" onkeyup="checkFQty(this.value);">
                                            </div>

                                            <div class="d-none col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="return-gst-amount">Return GST Amount </label>
                                                <input type="text" class="upr-inp focus-border" name="return-gst-amount" id="return-gst-amount" value="">
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <label class="mb-0 mt-1" for="refund-amount">Refund:</label>
                                                <input type="text" class="upr-inp focus-border" name="refund-amount" id="refund-amount" readonly>
                                            </div>

                                            <div class="col-md-3 col-6 mt-auto text-right">
                                                <button type="button" class="btn btn-primary w-100 " onclick="addData()">Add
                                                    <i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>
                    <!-- /end Add Product  -->

                    <!--=========================== Show Bill Items ===========================-->
                    <div class="card shadow mb-4">
                        <form action="_config\form-submission\stock-return-form.php" method="post">
                            <div class="card-body stock-in-summary">
                                <div class="table-responsive">

                                    <table class="table item-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col" class="">
                                                    <input type="number" value="0" id="dynamic-id" style="width: 1rem;" hidden>
                                                </th>
                                                <th scope="col" class="">
                                                    <input type="number" value="0" id="serial-control" style="width: 1rem;" hidden>
                                                </th>
                                                <th scope="col" hidden></th>
                                                <th scope="col" hidden></th>
                                                <th scope="col" hidden>StockInDetailsId</th>
                                                <th scope="col">Items</th>
                                                <th scope="col">Batch</th>
                                                <th scope="col">Exp</th>
                                                <th scope="col">Unit</th>
                                                <th scope="col">P.Qty.</th>
                                                <th scope="col">Free</th>
                                                <th scope="col">MRP</th>
                                                <th scope="col">PTR</th>
                                                <th scope="col">Disc%</th>
                                                <th scope="col">GST%</th>
                                                <th scope="col">Ret Qty</th>
                                                <th scope="col">Ret F.Qty</th>
                                                <th scope="col">Refund</th>

                                            </tr>
                                        </thead>
                                        <tbody id="dataBody">


                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="p-3 m-2  font-weight-bold text-light purchase-items-summary rounded">
                                <div class="row ">
                                    <div class="col-md-3 col-6 mb-3 d-flex justify-content-start">
                                        <p>Distributor :
                                            <input class="summary-inp w-60" type="text" id="dist-name" name="dist-name" readonly style="margin-left: 0rem;">
                                            <input class="summary-inp w-60" name="dist-id" id="dist-id" type="text" hidden readonly>
                                            <input class="d-none summary-inp w-60" name="dist-bill-no" id="dist-bill-no" type="text" readonly>
                                        </p>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3 d-flex justify-content-start">
                                        <p>Return Date : <input class="summary-inp w-6r" name="return-date" id="return-date" type="text" value="<?=  date("d-m-Y") ?>" readonly>
                                        </p>
                                    </div>

                                    <div class="col-md-3 col-6 mb-3  d-flex justify-content-start">
                                        <p>Items : <input class="summary-inp w-6r" name="items-qty" id="items-qty" type="text" value="0" readonly></p>
                                    </div>

                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>Refund Mode : <input class="summary-inp w-6r" name="refund-mode" id="refund-mode" type="text" readonly> </p>
                                    </div>

                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>Qty : <input class="summary-inp w-65" name="total-return-qty" id="total-return-qty" type="text" value="0" readonly> </p>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>GST : <input class="summary-inp w-65" name="return-gst-val" id="return-gst-val" type="text" value="0" readonly> </p>
                                    </div>
                                    <div class="col-md-3 mb-2 col-6 mb-2 d-flex justify-content-start">
                                        <p>Net : <input class="summary-inp w-65" name="refund" id="refund" type="text" value="0" readonly> </p>
                                    </div>
                                    <div class="col-md-3 mb-2 col-6 text-right">
                                        <button class="btn btn-sm btn-primary" style="width: 50%;" type="submit" name="stock-return">Save</button>
                                    </div>

                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start" hidden>
                                        <p hidden>StockIn Id : <input class="summary-inp w-6r" name="stockInId" id="stockInId" type="text" readonly> </p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--=========================== Show Bill Items ===========================-->


                </div>
                <!-- /.container-fluid -->
                <!-- End of Main Content -->

                <!-- Footer -->
                <?php include_once SUP_ROOT_COMPONENT.'footer-text.php'; ?>
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

        <!-- Custom scripts for all pages-->
        <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
        <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
        <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
        <script src="<?= JS_PATH ?>purchase-return-item.js"></script>

</body>

</html>