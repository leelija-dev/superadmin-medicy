<?php
// require_once dirname(__DIR__) . '/config/constant.php';
require_once 'config/constant.php';
require_once  SUP_ADM_DIR     . '_config/sessionCheck.php'; //check admin loggedin or not
require_once  SUP_ADM_DIR     . '_config/accessPermission.php';

require_once CLASS_DIR    . 'dbconnect.php';
require_once  SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR    . 'products.class.php';
require_once CLASS_DIR    . 'manufacturer.class.php';
require_once CLASS_DIR    . 'measureOfUnit.class.php';
require_once CLASS_DIR    . 'packagingUnit.class.php';
require_once CLASS_DIR    . 'itemUnit.class.php';
require_once CLASS_DIR    . 'gst.class.php';
require_once  SUP_ADM_DIR . '_config/accessPermission.php';


//objects Initilization
$Products           = new Products();
$Manufacturer       = new Manufacturer();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();
$ItemUnit           = new ItemUnit;
$GST                = new Gst;

$showManufacturer   = $Manufacturer->showManufacturerWithLimit();
$showManufacturer = json_decode($showManufacturer);
// print_r($showManufacturer);
$showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();
$showPackagingUnits = $PackagingUnits->showPackagingUnits();

$prodCategory       = json_decode($Products->productCategory());
$itemUnits          = $ItemUnit->showItemUnits();
$gstData            = json_decode($GST->seletGst());
$gstData            = $gstData->data;
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Add Items</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Fontawsome Link -->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>font-awesome.css">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!--Custom CSS -->
    <!-- <link href="css/add-products.css" rel="stylesheet"> -->
    <link href="<?php echo CSS_PATH ?>custom/add-products.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">

    <!-- css path for bootstrap 5-->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>choices.min.css">

    <link href="<?= PLUGIN_PATH ?>choices/assets/styles/choices.min.css" rel="stylesheet" />

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
                    <h1 class="h3 mb-2 text-gray-800"> Add Product</h1>

                    <!-- Add Product -->
                    <div class="card shadow mb-4" style="min-height: 90vh;">
                        <div class="card-body">
                            <form action="_config\form-submission\add-product.php" enctype="multipart/form-data" method="post" id="add-new-product-details">

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="img-div">
                                                    <div class="container-fluid" id="img-container">
                                                        <input type="file" name="img-files[]" id="img-file-input" accept=".jpg,.png" onchange="preview()" multiple>
                                                        <label class="d-flex justify-content-center" for="img-file-input" id="img-container-label">Choose Images &nbsp;<i class="fas fa-upload"></i></label>
                                                        <p id="num-of-files">No files chosen</p>
                                                        <div>
                                                            <div id="images">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <!-- product data input start hear -->

                                    <div class="col-md-7 mt-2">

                                        <div class="row">
                                            <lebel>Product Name</leble>
                                                <input class="c-inp w-100 p-1" id="product-name" name="product-name" placeholder="Product Name" required>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <lebel>Product Composition 1</leble>
                                                    <input class="c-inp w-100 p-1" id="product-composition-1" name="product-composition-1" placeholder="Product Composition 1" required>
                                            </div>

                                            <div class="col-md-6">
                                                <lebel>Product Composition 2</leble>
                                                    <input class="c-inp w-100 p-1" id="product-composition-2" name="product-composition-2" placeholder="Product Composition 2" required>
                                            </div>

                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <lebel>HSN Code</leble>
                                                    <input class="c-inp w-100 p-1" id="hsn-number" name="hsn-number" placeholder="Enter HSN Code" minlength="6"  maxlength="8" required>
                                            </div>

                                            <div class="col-md-4">
                                                <lebel>Item Category</leble>
                                                    <select class="c-inp p-1 w-100" name="item-category" id="item-category" required>
                                                        <option value="" disabled selected>Select</option>
                                                        <?php
                                                        if ($prodCategory->status == 1 && is_array($prodCategory->data)) {
                                                            $prodCategory = $prodCategory->data;

                                                            foreach ($prodCategory as $category) {
                                                                echo '<option value="' . $category->id . '">' . $category->name . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                            </div>

                                            <div class="col-md-4">
                                                <lebel>Pack Type</leble>
                                                    <select class="c-inp p-1 w-100" name="packaging-type" id="packaging-type" required>
                                                        <option value="" disabled selected>Pack Type</option>
                                                        <?php
                                                        foreach ($showPackagingUnits as $rowPackagingUnits) {
                                                            echo '<option value="' . $rowPackagingUnits['id'] . '">' . $rowPackagingUnits['unit_name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                            </div>
                                        </div>


                                        <div class="row mt-2">

                                            <div class="col-md-3">
                                                <lebel>Power</leble>
                                                    <input class="c-inp w-100 p-1" type="text" name="medicine-power" id="medicine-power" placeholder="Enter med Power" required>
                                            </div>

                                            <div class="col-md-3">
                                                <lebel>Quantity</leble>
                                                    <input class="c-inp w-100 p-1" type="text" name="quantity" id="quantity" placeholder="Enter Quantity" required>
                                            </div>

                                            <div class="col-md-3">
                                                <lebel>Item Unit</leble>
                                                    <select class="c-inp p-1 w-100" name="unit" id="unit" required>
                                                        <option value="" disabled selected>Select Unit</option>
                                                        <?php
                                                        foreach ($showMeasureOfUnits as $rowUnit) {

                                                            echo '<option value="' . $rowUnit['id'] . '">' . $rowUnit['short_name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                            </div>

                                            <div class="col-md-3">
                                                <lebel>Pack Unit</lebel>
                                                <select class="c-inp p-1 w-100" name="item-unit" id="item-unit" required>
                                                    <option value="" disabled selected>Item Unit</option>
                                                    <?php
                                                    foreach ($itemUnits as $eachUnit) {
                                                        echo '<option value="' . $eachUnit['id'] . '">' . $eachUnit['name'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <lebel>Select Manufacturer</leble>
                                                <input type="text" name="manufacturer" id="manufacturer" class="c-inp w-100 p-1" disable hidden>

                                                <input type="text" name="manufacturer-id" id="manufacturer-id" class="c-inp w-100 p-1">

                                                <div class="p-2 bg-light col-md-12 c-dropdown" id="manuf-list">
                                                    <div class="lists" id="lists">
                                                        <?php
                                                        if (!empty($showManufacturer)) {
                                                            foreach ($showManufacturer as $eachManuf) {
                                                                // print_r($eachManuf);
                                                        ?>
                                                                <div class="p-1 border-bottom list" id="<?= $eachManuf->id ?>" onclick="setManufacturer(this)">
                                                                    <?= $eachManuf->name ?>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                    </div>

                                                    <div class="d-flex flex-column justify-content-center mt-1" data-toggle="modal" data-target="#add-manufacturer" onclick="addManufacturer()">
                                                        <button type="button" id="add-manuf-btn" class="text-primary border-0">
                                                            <i class="fas fa-plus-circle"></i>
                                                            Add Now
                                                        </button>
                                                    </div>

                                                <?php
                                                        } else {
                                                ?>
                                                    <p class="text-center font-weight-bold">Manufacturer Not Found!</p>
                                                    <div class="d-flex flex-column justify-content-center" data-toggle="modal" data-target="#add-manufacturer" onclick="addManufacturer()">
                                                        <button type="button" id="add-manuf-btn" class="text-primary border-0 mt-2"><i class="fas fa-plus-circle"></i>
                                                            Add Now</button>
                                                    </div>
                                                <?php
                                                        }
                                                ?>
                                                </div>
                                        </div>



                                        <!-- Price Row -->

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <lebel>Enter MRP</leble>
                                                    <input type="number" class="c-inp w-100 p-1" name="mrp" id="mrp" placeholder="Enter MRP" step="0.01" required>
                                            </div>

                                            <div class="col-md-6">
                                                <lebel>Select GST</leble>
                                                    <select class="c-inp w-100 p-1" name="gst" id="gst" required>

                                                        <option value="" disabled selected>GST%</option>
                                                        <?php
                                                        foreach ($gstData as $gstData) {

                                                            echo '<option value="' . $gstData->id . '" >' . $gstData->percentage . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                            </div>
                                        </div>


                                        <!--/End Price Row -->

                                        <div class="col-md-12 mt-3">
                                            <textarea class="form-control" name="product-descreption" id="product-descreption" cols="30" rows="3" placeholder="Product Description" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-12 d-flex">
                                        <div class="col-md-6 d-flex justify-content-end">
                                            <button class="btn btn-danger mr-3" id="reset" type="reset" onclick="resetImg()"> Reset</button>
                                        </div>

                                        <div class="col-md-6">
                                            <button class="btn btn-primary" name="add-product" id="add-btn" type="submit">Add Product</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /end Add Product  -->
                </div>
                <!-- /.container-fluid -->
                <!-- End of Main Content -->
            </div>
            <!-- End of Content Wrapper -->

            <!-- Footer -->
            <?php include_once SUP_ROOT_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Product modal -->
        <!-- bd-example-modal-lg -->
        <div class="modal fade productModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel">Customize Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body productModalBody">

                    </div>
                </div>
            </div>
        </div>
        <!--/end Product modal -->



        <!-- manufacturer Add Modal -->
        <div class="modal fade" id="add-manufacturer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Manufacturer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body add-manufacturer">
                        <!-- Details Appeare Here by Ajax  -->
                    </div>
                </div>
            </div>
        </div>
        <!--/end Distributor Add Modal -->



        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>



        <!-- Bootstrap core JavaScript-->
        <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
        <!-- <script src="<?php echo JS_PATH ?>>bootstrap-js-5/bootstrap.bundle.min.js"></script> -->
        <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>
        <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
        <script src="<?php echo JS_PATH ?>custom/add-products.js"></script>

        <!-- Sweet Alert Js  -->
        <script src="<?php echo JS_PATH ?>sweetAlert.min.js"></script>



        <script>
            productViewAndEdit = (productId) => {
                // alert("productModalBody");
                let ViewAndEdit = productId;
                let url = "<?php echo SUP_ADM_DIR ?>ajax/products.View.ajax.php?id=" + ViewAndEdit;
                $(".productModalBody").html(
                    '<iframe width="99%" height="520px" frameborder="0" allowtransparency="true" src="' +
                    url + '"></iframe>');
            }
        </script>


        <script>
            /*calculating profit only after entering MRP
        // function getMarginMrp(value) {
        //     this.value = parseFloat(this.value).toFixed(2);

        //     const mrp = parseFloat(value);
        //     const ptr = parseFloat(document.getElementById("ptr").value);
        //     const gst = parseFloat(document.getElementById("gst").value);

        //     var profit = (mrp - ptr);

        //     profit = parseFloat(profit - ((gst / 100) * ptr));

        //     document.getElementById("profit").value = profit.toFixed(2);
        // }


        //calculate after entering PTR
        function getMarginPtr(value) {
            const ptr = parseFloat(value);
            const mrp = parseFloat(document.getElementById("mrp").value);
            const gst = parseFloat(document.getElementById("gst").value);

            var profit = parseFloat(mrp - ptr);

            profit = parseFloat(profit - ((gst / 100) * ptr));

            document.getElementById("profit").value = profit.toFixed(2);
        }

        //calculate after entering GST
        function getMarginGst(value) {
            const gst = parseFloat(value);
            const ptr = parseFloat(document.getElementById("ptr").value);
            const mrp = parseFloat(document.getElementById("mrp").value);

            var profit = parseFloat(mrp - ptr);

            profit = parseFloat(profit - ((gst / 100) * ptr));

            document.getElementById("profit").value = profit.toFixed(2);
        }*/


            //     $(document).on("click", ".back", function() {
            //         var backFile = $(this).parents().find(".back-file");
            //         backFile.trigger("click");
            //     });
            //     $('.back-file').change(function(e) {
            //         $(".back-img-field").hide();
            //         $("#back-preview").show();


            //         var fileName = e.target.files[0].name;
            //         $("#back-file").val(fileName);

            //         var reader = new FileReader();
            //         reader.onload = function(e) {
            //             // get loaded data and render thumbnail.
            //             document.getElementById("back-preview").src = e.target.result;
            //         };
            //         // read the image file as a data URL.
            //         reader.readAsDataURL(this.files[0]);
            //     });
            // 


            //     $(document).on("click", ".side", function() {
            //         var SideFile = $(this).parents().find(".side-file");
            //         SideFile.trigger("click");
            //     });
            //     $('.side-file').change(function(img) {
            //         $(".side-img-field").hide();
            //         $("#side-preview").show();


            //         var sideImgName = img.target.files[0].name;
            //         $("#side-file").val(sideImgName);

            //         var reader = new FileReader();
            //         reader.onload = function(img) {
            //             // get loaded data and render thumbnail.
            //             document.getElementById("side-preview").src = img.target.result;
            //         };
            //         // read the image file as a data URL.
            //         reader.readAsDataURL(this.files[0]);
            //     });
            // 
            //     document.addEventListener('DOMContentLoaded', function() {
            //         var choices = new Choices('#manufacturer', {
            //             allowHTML: true,
            //             removeItemButton: true,
            //         });
            //     });
        </script>

</body>

</html>