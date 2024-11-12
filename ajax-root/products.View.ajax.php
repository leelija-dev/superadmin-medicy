<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'measureOfUnit.class.php';
require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR.'packagingUnit.class.php';


$productTId = $_GET['id'];

//class initilization
$Products       = new Products();
$Manufacturer   = new Manufacturer();
$MeasureOfUnits = new MeasureOfUnits();
$PackagingUnits = new PackagingUnits();

$showPackagingUnits = $PackagingUnits->showPackagingUnits();


$showProduct = $Products->showProductsByTId($productTId);
foreach ($showProduct as $rowProduct) {
    $id             = $rowProduct['id'];
    $product        = $rowProduct['product_id'];
    $manufacturerId = $rowProduct['manufacturer_id'];
    $productName    = $rowProduct['name'];
    $productPower   = $rowProduct['power'];
    $productUnit    = $rowProduct['unit'];
    $packagingType  = $rowProduct['packaging_type'];
    // echo $packagingType.'<br>';
    $productUnitqty = $rowProduct['unit_quantity'];
    $productDsc     = $rowProduct['dsc'];
    $productMrp     = $rowProduct['mrp'];
    $productGst     = $rowProduct['gst'];
    // echo $productUnit;
}

// $showManufacturer = $Manufacturer->showManufacturerById($manufacturerId);

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
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Fontawsome Link -->
    <link rel="stylesheet" href="../../css/font-awesome.css">

    <!-- Custom styles for this template -->
    <!-- <link href="../css/sb-admin-2.min.css" rel="stylesheet"> -->

    <link rel="stylesheet" href="../../css/bootstrap 5/bootstrap.css">


    <!--Custom CSS -->
    <link href="../css/custom/add-products.css" rel="stylesheet">



</head>

<body>

    <!-- Page Wrapper
    <div id="wrapper"> -->


    <!-- Content Wrapper -->
    <!-- <div id="content-wrapper" class="d-flex flex-column"> -->

    <!-- Main Content -->
    <!-- <div id="content"> -->

    <!-- Begin Page Content -->
    <!-- <div class="container-fluid"> -->

    <!-- Page Heading -->
    <!-- <h1 class="h3 mb-2 text-gray-800"> Add Product</h1> -->

    <!-- Add Product -->
    <div class="card">
        <div class="card-body">
            <form method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <input type="hidden" id="productTId" value="<?php echo $id; ?>">
                            <label class="mb-0 mt-1" for="product-name">Product Name</Address></label>
                            <input class="form-control" id="product-name" name="product-name" placeholder="Product Name"
                                value="<?php echo $productName;?>" required>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="mb-0 mt-1" for="medicine-power">Medicine Power</label>
                            <input class="form-control" type="text" name="medicine-power" id="medicine-power"
                                placeholder="Enter Medicine Power" value="<?php echo $productPower;?>">
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="manufacturer">Manufacturer:</label>
                            <select class="form-control" name="manufacturer" id="manufacturer">
                                <option value="" disabled selected>Select Manufacturer</option>
                                <?php
                                                    $showManufacturer = json_decode($Manufacturer->showManufacturer());
                                                    foreach ($showManufacturer as $rowManufacturer) {
                                                        $manufId   = $rowManufacturer->id;
                                                        $manufName = $rowManufacturer->name;
                                                        echo '<option ';
                                                         if($manufacturerId == $manufId){echo "selected";};
                                                         echo ' value="'.$manufId.'">'.$manufName.'</option>';
                                                    }
                                                ?>
                            </select>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="product-descreption">Product Description</label>
                            <textarea class="form-control" name="product-descreption" id="product-descreption" cols="30"
                                rows="3"><?php echo $productDsc;?></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">

                        <!-- Product Image Row  -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="border p-1 rounded">
                                    <div class="image-area rounded">
                                        <h6>Upload Product Image</h6>
                                        <div class="icon">
                                            <i class="fa fa-file-image-o" aria-hidden="true"></i>
                                        </div>

                                        <span class="upload-img-span1"><small>Drag & Drop</small></span>
                                        <span class="upload-img-span"><small>Or <span
                                                    class="browse">Browse</span></small></span>
                                        <input id="product-image" name="product-image" type="file" hidden>
                                        <span class="upload-img-type"><small><i>Formats: JPG, JPEG &
                                                    PNG</i></small></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="border border-primary rounded " style="height: 70px">

                                </div>
                                <div class="border border-primary rounded mt-2" style="height: 70px">

                                </div>
                            </div>
                        </div>
                        <!--/End Product Image Row  -->

                        <!-- Price Row -->
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label class="mb-0 mt-1" for="packaging-unit">Packaging Type</label>
                                <select class="form-control" name="packaging-type" id="packaging-type">
                                    <?php

                                        $showPackagingUnit = $PackagingUnits->showPackagingUnitById($packagingType);
                                            if($packagingType != NULL){
                                                echo "<option value=".$showPackagingUnit[0][1]." selected>".$showPackagingUnit[0][1]."</option>";
                                            }else{
                                                echo "<option value='' selected disabled>Select Packaging Type</option>";

                                            }
                                            foreach ($showPackagingUnits as $rowPackagingUnits) {
                                                // echo $rowPackagingUnits["id"].'-'.$packagingType.'<br>'; 

                                                echo '<option value='.$rowPackagingUnits["id"].'>'.$rowPackagingUnits["unit_name"].'</option>';
                                            }
                                            ?>
                                </select>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="mb-0 mt-1" for="unit-quantity">Unit Quantity</label>
                                <input type="number" class="form-control" name="unit-quantity" id="unit-quantity"
                                    placeholder="Enter Unit" value="<?php echo $productUnitqty;?>">
                            </div>

                            <div class=" col-md-6 mt-3">
                                <label class="mb-0 mt-1" for="unit">Select Unit</label>
                                <select class="form-control" name="unit" id="unit">
                                    <?php
                                            $showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();
                                            foreach ($showMeasureOfUnits as $rowUnit) {
                                                
                                                // echo '<option value="'.$rowUnit['full_name'].'">'..'</option>';

                                                echo '<option ';
                                                    if($productUnit == $rowUnit['full_name']){echo "selected";};
                                                    echo ' value="'.$rowUnit['full_name'].'">'.$rowUnit['short_name'].'</option>';
                                            }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!--/End Price Row -->

                        <!-- Price Row -->
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="mb-0 mt-1" for="mrp">MRP ₹</label>
                                <input type="number" class="form-control" name="mrp" id="mrp" placeholder="Enter MRP"
                                    onkeyup="getMarginMrp(this.value)" value="<?php echo $productMrp;?>">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="mb-0 mt-1" for="gst">GST %</label>
                                <select name="gst" id="gst" class="form-control" onchange="getMarginGst(this.value)">
                                    <?php if ($productGst != NULL) {
                                        echo '<option value="'.$productGst.'">'.$productGst.'</option>';
                                        } else{
                                            echo '<option value="" selected disabled>Select GST</option>';
                                        }
                                        ?>
                                        <option value="0">0</option>
                                        <option value="5">5</option>
                                        <option value="12">12%</option>
                                        <option value="18">18%</option>
                                        <option value="28">28%</option>

                                </select>
                            </div>
                        </div>
                        <!--/End Price Row -->

                    </div>
                </div>
                <div id="reportUpdate">

                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                    <button class="btn btn-primary me-md-2" name="add-product" id="add-btn" type="button"
                        onclick="updateProduct();">Update</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /end Add Product  -->
    <!-- </div> -->
    <!-- </div> -->
    <!-- </div> -->

    <script src="../js/custom/add-products.js"></script>
    <script>
    var todayDate = new Date();
    var date = todayDate.getDate();
    var month = todayDate.getMonth() + 1;
    var year = todayDate.getFullYear();

    if (date < 10) {
        date = '0' + date;
    }
    if (month < 10) {
        month = '0' + month;
    }
    var todayFullDate = year + "-" + month + "-" + date;
    // console.log(todayFullDate);
    document.getElementById('date-picker').setAttribute('min', todayFullDate);
    </script>


    <script>
    // function getMarginMrp(value) {
    //     const mrp  = parseFloat(value);
    //     const ptr  = parseFloat(document.getElementById("ptr").value);
    //     const disc = parseFloat(document.getElementById("default-discount").value);

    //     // const margin = mrp - (disc / 100 * mrp);
    //     // console.log(sellingPrice);
    //     // document.getElementById("margin").value = margin;
    // }


    //calculating profit only after entering MRP
    function getMarginMrp(value) {
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
    </script>
    <script>
    function updateProduct() {
        // alert('Working');
        // let Id        = $("#distributorId").val();
        let Id              = document.getElementById("productTId").value;
        let name            = document.getElementById("product-name").value;
        // alert(name);
        let power           = document.getElementById("medicine-power").value;
        let dsc             = document.getElementById("product-descreption").value;
        let manuf           = document.getElementById("manufacturer").value;
        let packaging       = document.getElementById("packaging-type").value;
        let unitQty         = document.getElementById("unit-quantity").value;
        let unit            = document.getElementById("unit").value;
        let mrp             = document.getElementById("mrp").value;
        let gst             = document.getElementById("gst").value;




        // let url = "distributor.Edit.ajax.php?id=" + escape(Id) + "&name=" + escape(name) + "&power=" + escape(power) + "&manuf=" + escape(manuf) + "&dsc=" + escape(dsc) + "&unit-qty=" + escape(unit-qty) + "&unit=" + escape(dsc) + "&mrp=" + escape(mrp) + "&ptr=" + escape(ptr) + "&gst=" + escape(gst) + "&unit=" + escape(profit);
        let url = "products.Edit.ajax.php";
        let data = "id=" + escape(Id) + "&name=" + escape(name) + "&power=" + escape(power) + "&dsc=" + escape(dsc) +  "&manuf=" + escape(manuf) + "&packaging=" + escape(packaging) + "&unit-qty=" + escape(unitQty) + "&unit=" + escape(unit) + "&mrp=" + escape(mrp) + "&gst=" + escape(gst);
        // let data = "id="+escape(Id)+"&name="+escape(name);

        request.open('POST', url, true);
        request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        request.onreadystatechange = getEditUpdates;

        request.send(data);
    } //eof editDist

    function getEditUpdates() {
        if (request.readyState == 4) {
            if (request.status == 200) {
                var xmlResponse = request.responseText;
                document.getElementById('reportUpdate').innerHTML = xmlResponse;
            } else if (request.status == 404) {
                alert("Request page doesn't exist");
            } else if (request.status == 403) {
                alert("Request page doesn't exist");
            } else {
                alert("Error: Status Code is " + request.statusText);
            }
        }
    } //eof getEditUpdates
    </script>

    <script src="../js/ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="../../js/bootstrap-js-5/bootstrap.js"></script>
    <script src="../../js/bootstrap-js-5/bootstrap.min.js"></script>


    <!-- Core plugin JavaScript-->
    <!-- <script src="../vendor/jquery-easing/jquery.easing.min.js"></script> -->

    <!-- Custom scripts for all pages-->
    <!-- <script src="../js/sb-admin-2.min.js"></script> -->

</body>

</html>