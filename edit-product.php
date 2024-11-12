<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';
require_once CLASS_DIR . 'gst.class.php';
require_once CLASS_DIR . 'productCategory.class.php';
require_once CLASS_DIR . 'request.class.php';


//objects Initilization
$Products           = new Products();
$Manufacturer       = new Manufacturer();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();
$ProductImages      = new ProductImages();
$ItemUnit           = new ItemUnit();
$GST                = new Gst;
$ProductCategory    = new ProductCategory;
$Request            = new Request;



$showManufacturer   = json_decode($Manufacturer->showManufacturerWithLimit());
$showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();
$showPackagingUnits = $PackagingUnits->showPackagingUnits();
$itemUnits          = $ItemUnit->showItemUnits();

$gstData            = json_decode($GST->seletGst());
$gstData            = $gstData->data;

$Category = json_decode($ProductCategory->selectAllProdCategory());
$Category = $Category->data;

// echo ADM_URL.'product-request-lsit.php';
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
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Fontawsome Link -->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= CSS_PATH ?>font-awesome.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!--Custom CSS -->
    <link href="<?php echo CSS_PATH ?>custom/add-products.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">

    <!-- <link href="<?= PLUGIN_PATH ?>choices/assets/styles/choices.min.css" rel="stylesheet" /> -->

    <!-- sweetAlert link -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>


</head>

<body id="page-top">
    <?php
    if (isset($_GET['id'])) {

        $ticketNo = $_GET['id'];
        $tableName = $_GET['table'];

        // ===================== Fetching Product Details =====================

        $product = json_decode($Products->showProductsByIdOnTableName($ticketNo, $tableName));
        
        if ($product->status) {

            // print_r($product);
            $product = $product->data;

            // product id ----- 
            $productId = $product->product_id;

            // product name ====
            $productName    = $product->name;

            // HSN number =======
            $hsnNumber = $product->hsno_number;
            // echo $hsnNumber;

            // prod category ======
            $prodCategory = $product->type;

            // packaging type =========
            $packTypeId = $product->packaging_type;

            // product composition =====
            if (isset($product->comp_1)) {
                $comp1          = $product->comp_1;
            } else {
                $comp1          = '';
            }


            if (isset($product->comp_2)) {
                $comp2          = $product->comp_2;
            } else {
                $comp2          = '';
            }


            // manufacturer =====
            if (isset($product->manufacturer_id)) {
                $manufData = json_decode($Manufacturer->showManufacturerById($product->manufacturer_id));
                if ($manufData->status) {
                    $manufacturerId = $manufData->data->id;
                    $manufacturerName = $manufData->data->name;
                } else {
                    $manufacturerId = '';
                    $manufacturerName = '';
                }
            } else {
                $manufacturerId = '';
                $manufacturerName = '';
            }

            // quantity ======
            $qty            = $product->unit_quantity;

            // quantity unit (e.g. piceses, ml, gm etc)======
            if (isset($product->unit_id)) {
                $qtyUnit        = $product->unit_id;
            } else {
                $qtyUnit = '';
            }


            $itemUnit       = $product->unit;
            $packagingType  = $product->packaging_type;
            $type           = $product->type;
            $power          = $product->power;

            // product description
            if (isset($product->dsc)) {
                $dsc            = $product->dsc;
            } else {
                $dsc  = '';
            }

            //product edit request description
            if (isset($product->req_dsc)) {
                $requestDsc            = $product->req_dsc;
            } else {
                $requestDsc  = '';
            }
            // echo $requestDsc;

            $mrp            = $product->mrp;
            $gst            = $product->gst;


            $prodReqStatus = $product->prod_req_status ?? '';
            $oldProdFlag = $product->old_prod_flag ?? '';
            $oldProdId = $oldProdFlag ? ($product->old_prod_id ?? '') : '';

            if(!empty($oldProdId)){
                $col = 'product_id';
                $data = $oldProdId;
                $productDataFromProductsTable = json_decode($Products->showProductsByTable($col, $data));
                // print_r($productDataFromProductsTable);
                $editReqFlagData = $productDataFromProductsTable->data[0]->edit_request_flag;
            }else{
                $editReqFlagData = 0;
            }

           
            $admin_id       = $product->admin_id;

            $images = json_decode($ProductImages->showImagesByProduct($productId));
            // print_r($images);

            $allImg = array();
            $allImgId = array();

            if ($images->status == 1 && !empty($images->data)) {
                foreach ($images->data as $image) {
                    $allImg[] = $image->image;
                    $allImgId[] = $image->id;
                }
            } else {
                $allImg[] = "default-product-image/medicy-default-product-image.jpg";
            }
        }

    ?>

        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">
                    <!-- Add Product -->
                    <div class="card shadow mb-4 h-100">
                        <div class="card-body">
                            <form action="_config\form-submission\edit-update-product.php" method="post" enctype="multipart/form-data">
                                <div class="d-flex flex-wrap">

                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="border p-1 rounded">
                                                    <div class="row h-100 mt-2 justify-content-center">
                                                        <?php foreach ($allImg as $index => $imagePath) : ?>
                                                            <div class="col-2 border m-1 p-0">
                                                                <img src="<?= PROD_IMG_PATH ?><?php echo $imagePath; ?>" id="img-<?php echo $index; ?>" onclick="setImg(this.id)" class=" ob-cover h-100" alt="...">

                                                                <?php foreach ($allImgId as $idIndex => $imageID) : ?>
                                                                    <?php if ($idIndex === $index) : ?>
                                                                        <input class="form-check-input mt-5 ml-n5" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                                                        <button type="button" class="btn-close position-absolute rounded border bg-danger text-white mt-n3 ml-n3" aria-label="Close" onclick="closeImage('<?php echo $imageID; ?>', '<?php echo $imagePath; ?>', <?php echo $index; ?>)">x</button>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <div id="img-div">
                                                    <div class="container-fluid" id="img-container">
                                                        <input type="file" name="img-files[]" id="img-file-input" accept=".jpg,.png" onchange="preview()" multiple>
                                                        <label for="img-file-input" id="img-container-label">Choose Images &nbsp;<i class="fas fa-upload"></i></label>
                                                        <p id="num-of-files">No files chosen</p>
                                                        <div>
                                                            <div id="images">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/End Product Image Row  -->
                                        <br>
                                        <!-- <div class="row"> -->
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <!-- <button class="btn btn-danger mr-3" id="reset" type="button">Reset</button> -->
                                            <button class="btn btn-primary" name="update-product" id="update-btn" type="submit">Update</button>
                                        </div>

                                        <!-- </div> -->
                                    </div>

                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <lebel>Prodcut Name:</lebel>
                                                <input class="c-inp w-100 p-1" id="product-id" name="product-id" placeholder="Product Id" value="<?= $productId ?>" required hidden>
                                                <input class="c-inp w-100 p-1" id="ticket-no" name="ticket-no" value="<?= $ticketNo ?>" required hidden>
                                                <input class="c-inp w-100 p-1" id="old-product-id" name="old-product-id" value="<?= $oldProdId ?>" hidden>
                                                <input class="c-inp w-100 p-1" id="product-name" name="product-name" placeholder="Product Name" value="<?= $productName ?>" required>
                                            </div>
                                        </div>

                                        <div class="row d-flex mt-1">
                                            <div class="col-12">
                                                <lebel>Prodcut Composition:</lebel>
                                            </div>
                                            <div class="col-6">
                                                <input class="c-inp w-100 p-1" id="product-composition1" name="product-composition1" placeholder="Product Composition 1" value="<?= $comp1  ?>" required>
                                            </div>
                                            <div class="col-6">
                                                <input class="c-inp w-100 p-1" id="product-composition2" name="product-composition2" placeholder="Product Composition 2" value="<?= $comp2  ?>" required>
                                            </div>
                                        </div>

                                        <div class="row d-flex mt-1">
                                            <div class="col-4">
                                                <lebel>HSN Number</lebel>
                                                <input class="c-inp w-100 p-1" id="hsn-number" name="hsn-number" placeholder="HSN Number" value="<?php echo $hsnNumber  ?>" required>
                                            </div>

                                            <div class="col-4">
                                                <lebel>Item Type:</lebel>
                                                <select class="c-inp p-1 w-100" name="product-category" id="product-category" required>
                                                    <option value="" disabled selected>Prodcut Category</option>
                                                    <?php
                                                    foreach ($Category as $category) {
                                                    ?>
                                                        <option <?= $prodCategory == $category->id ? 'selected' : ''; ?> value="<?php echo $category->id ?>">
                                                            <?php echo $category->name ?>
                                                        </option>';
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-4">
                                                <lebel>Pack Type:</lebel>
                                                <select class="c-inp p-1 w-100" name="packaging-type" id="packaging-type">
                                                    <option value="" disabled selected>Packaging Type</option>
                                                    <?php
                                                    foreach ($showPackagingUnits as $packType) {
                                                    ?>
                                                        <option <?= $packagingType == $packType['id'] ? 'selected' : ''; ?> value="<?= $packType['id'] ?>">
                                                            <?= $packType['unit_name'] ?>
                                                        </option>';
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="row mt-1">
                                            <div class="col-md-3">
                                                <lebel>Power</lebel>
                                                <input class="c-inp w-100 p-1" type="text" name="medicine-power" id="medicine-power" placeholder="Enter Medicine Power" value="<?= $power ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <lebel>Quantity</lebel>
                                                <input type="number" class="c-inp p-1 w-100" name="quantity" id="quantity" placeholder="Enter Quantity" value="<?= $qty ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <lebel>Item Unit</lebel>
                                                <select class="c-inp p-1 w-100" name="qty-unit" id="qty-unit" required>
                                                    <option value="" disabled selected>Select Unit</option>
                                                    <?php
                                                    foreach ($showMeasureOfUnits as $rowUnit) {
                                                    ?>
                                                        <option <?= $qtyUnit == $rowUnit['id'] ? 'selected' : ''; ?> value="<?= $rowUnit['id']; ?>">
                                                            <?= $rowUnit['short_name']; ?></option>';
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <lebel>Pack Unit</lebel>
                                                <select class="c-inp p-1 w-100" name="item-unit" id="item-unit" required>
                                                    <option value="" disabled selected>Packaging Unit</option>
                                                    <?php
                                                    foreach ($itemUnits as $eachUnit) {
                                                    ?>
                                                        <option <?= $itemUnit == $eachUnit['id'] ? 'selected' : ''; ?> value="<?= $eachUnit['id']; ?>">
                                                            <?= $eachUnit['name']; ?></option>';
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>


                                        <!-- manufacturer row -->

                                        <div class="row mt-1">
                                            <div class="col-md-12">
                                                <lebel>Manufacturer:</lebel>
                                                <input type="text" name="manufacturer" id="manufacturer" class="c-inp w-100 p-1" value="<?= $manufacturerId ?>" disable hidden>

                                                <input type="text" name="manufacturer-id" id="manufacturer-id" value="<?= $manufacturerName ?>" class="c-inp w-100 p-1" required>

                                                <div class="p-2 bg-light col-md-12 c-dropdown" id="manuf-list" style="display: none;">
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
                                        </div>
                                        <!--/End Manufacturer Row -->

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <lebel>MRP</lebel>
                                                <input type="number" class="c-inp w-100 p-1" name="mrp" id="mrp" placeholder="Enter MRP" onkeyup="getMarginMrp(this.value)" step="0.01" value="<?= $mrp; ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <lebel>GST%</lebel>
                                                <select class="c-inp w-100 p-1" name="gst" id="gst" onchange="getMarginGst(this.value)" required>
                                                    <option value="" disabled selected>GST</option>
                                                    <?php
                                                    foreach ($gstData as $gstData) {
                                                    ?>
                                                        <option <?= $gst == $gstData->percentage ? 'selected' : ''; ?> value="<?= $gstData->percentage; ?>">
                                                            <?= $gstData->percentage; ?></option>';
                                                    <?php
                                                    }
                                                    ?>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-12">
                                                <textarea class="c-inp w-100 p-1" name="product-description" id="product-description" placeholder="Product Description" cols="30" rows="2" required><?= $dsc ?></textarea>
                                            </div>
                                            <br>
                                            <div class="col-md-12 d-none">
                                                <textarea class="c-inp w-100 p-1" name="product-req-description" id="product-req-description" placeholder="Product Description" cols="30" rows="2"><?= $requestDsc ?></textarea>
                                            </div>
                                        </div>

                                        <div class="row mt-1 d-none">

                                            <div class="col-md-3">
                                                <input type="text" name="edit-req-flag-data" id="edit-req-flag-data" value="<?php echo  $editReqFlagData; ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <input type="text" name="prod-req-status" id="prod-req-status" value="<?php echo  $prodReqStatus; ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <input type="text" name="old-prod-flag" id="old-prod-flag" value="<?php echo $oldProdFlag; ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <input type="text" name="table-info" id="table-info" value="<?php echo $tableName; ?>">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
                <!-- /end Add Product  -->
            <?php
        }
            ?>
            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Bootstrap core JavaScript-->
        <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
        <!-- <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.js"></script> -->

        <!-- Custom scripts for all pages-->
        <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
        <script src="<?= JS_PATH ?>custom/add-products.js"></script>
        <!-- Sweet Alert Js  -->
        <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

        <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>




        <script>
            //calculating profit only after entering MRP
            function getMarginMrp(value) {
                this.value = parseFloat(this.value).toFixed(2);
                const mrp = parseFloat(value);
                const ptr = parseFloat(document.getElementById("ptr").value);
                const gst = parseFloat(document.getElementById("gst").value);

                var profit = (mrp - ptr);

                profit = parseFloat(profit - ((gst / 100) * ptr));

                document.getElementById("profit").value = profit.toFixed(2);
            }


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
            }
            //image selection//
        </script>

        <script>
            $(document).on("click", ".back", function() {
                var backFile = $(this).parents().find(".back-file");
                backFile.trigger("click");
            });
            $('.back-file').change(function(e) {
                $(".back-img-field").hide();
                $("#back-preview").show();


                var fileName = e.target.files[0].name;
                $("#back-file").val(fileName);

                var reader = new FileReader();
                reader.onload = function(e) {
                    // get loaded data and render thumbnail.
                    document.getElementById("back-preview").src = e.target.result;
                };
                // read the image file as a data URL.
                reader.readAsDataURL(this.files[0]);
            });
        </script>

        <script>
            $(document).on("click", ".side", function() {
                var SideFile = $(this).parents().find(".side-file");
                SideFile.trigger("click");
            });
            $('.side-file').change(function(img) {
                $(".side-img-field").hide();
                $("#side-preview").show();


                var sideImgName = img.target.files[0].name;
                $("#side-file").val(sideImgName);

                var reader = new FileReader();
                reader.onload = function(img) {
                    // get loaded data and render thumbnail.
                    document.getElementById("side-preview").src = img.target.result;
                };
                // read the image file as a data URL.
                reader.readAsDataURL(this.files[0]);
            });
        </script>

</body>

</html>