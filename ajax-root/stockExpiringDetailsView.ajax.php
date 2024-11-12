<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'idsgeneration.class.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'stockIn.class.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'productsImages.class.php';
require_once CLASS_DIR.'distributor.class.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR.'packagingUnit.class.php';

//Classes Initilizing
// $Patients       =   new Patients();
$IdGeneration   =   new IdGeneration();
$currentStock   =   new CurrentStock();
$StockIn        =   new StockIn();
$StockInDetail  =   new StockInDetails();
$Product        =   new Products();
$ProductImages  =   new ProductImages();
$distributor    =   new Distributor();
$manufacturer   =   new Manufacturer();
$packagUnit     =   new PackagingUnits();


if (isset($_GET['stokInDetialId'])) {
    $stokInDetialId =  $_GET['stokInDetialId'];
    $showStock = $currentStock->showCurrentStocByStokInDetialsId($stokInDetialId);
    // print_r($showStock);
    // echo count($showStock);

    foreach ($showStock as $curntStk) {
        $productId      =  $curntStk['product_id'];
    }

    $prodcutDetails = $Product->showProductsById($productId);
    // echo "<br><br>"; print_r($prodcutDetails);

    $manufDetails = $manufacturer->showManufacturerById($prodcutDetails[0]['manufacturer_id']);
    // $manufDetails = $manufacturer->showManufacturer();
    // echo "<br><br>"; print_r($manufDetails);

    // $distributorDetails = $distributor->showDistributorById($showStock[0]['distributor_id']);
    // echo "<br><br>"; print_r($distributorDetails);


    $image = $ProductImages->showImageById($productId);
    // print_r($image);
    if ($image[0][2] != NULL) {
        $productImage = $image[0][2];
    } else {
        $productImage = 'medicy-default-product-image.jpg';
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/bootstrap 5/bootstrap.css">
    <title>Product Details</title>

    <link href="../../assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Sweet Alert Js  -->
    <script src="../../js/sweetAlert.min.js"></script>

    <!-- Core plugin JavaScript-->
    <!-- <script src="../../assets/jquery-easing/jquery.easing.min.js"></script> -->
    <!-- <script src="../../js/ajax.custom-lib.js"></script> -->
    <!-- <script src="../../js/jquery.prettyPhoto.js"></script>
    <script src="../../js/jquery.vide.js"></script> -->

    <script src="../../js/contact-us-js/jquery.min.js"></script>
    <script src="../../js/contact-us-js/jquery.validate.min.js"></script>

    <!-- Custom scripts for all pages-->
    <!-- <script src="../js/sb-admin-2.js"></script> -->

    <!-- <script src="../vendor/product-table/jquery.dataTables.js"></script>
    <script src="../vendor/product-table/dataTables.bootstrap4.js"></script> -->

    <!-- Bootstrap core JavaScript-->
    <script src="../../assets/jquery/jquery.min.js"></script>
    <!-- <script src="../../js/bootstrap-js-4/bootstrap.bundle.min.js"></script> -->

    <!-- Custom JS -->
    <script src="../js/custom-js.js"></script>
    <script src="../js/ajax.custom-lib.js"></script>
</head>

<body>
    <div class="container-fluid d-flex justify-content-center mt-2">
        <div class="container-fluid">
            <div class="row p-4 justify-content-left">
                <div class="col-sm-5 justify-content-center">
                    <div class="text-center border d-flex justify-content-center">
                        <img src="../../images/product-image/<?php echo $productImage ?>" class="img-fluid rounded" alt="...">
                        <!-- <hr class="hl justify-content-center" style="color: black;"> -->
                    </div>
                </div>
                <!-- <div class="col-sm-1 justify-content-center">
                 <div class="vl justify-content-center"></div> 
                </div> -->
                <div class="col-sm-7 justify-content-center" flex>
                    <h3><?php echo $prodcutDetails[0]['name']; ?></h3>
                    <h7>[<?php echo $prodcutDetails[0]['product_composition']; ?>]</h6>
                        <h5><?php echo $manufDetails[0]['name']; ?></h6>
                </div>
                <!-- <div class="col-sm-1 justify-content-center">

                </div> -->
                <!-- <div class="col-sm-2 justify-content-center">
                    <button class="button btn-danger" id="<?php echo $productId ?>" onclick="delAll(this.id)">Delete All</button>
                </div> -->
            </div>
            <div class="d-flex justify-content-top">
                <hr class="text-center w-100" style="height: 2px; color:black">
            </div>
            <?php
            $slNo = 1;
            foreach ($showStock as $stock) {
                // print_r($stock);
                $batchNo = $stock['batch_no'];
                $distId = $stock['distributor_id'];
                $currentStock = $stock['qty'];
                $looseStock = $stock['loosely_count'];

                //===============distributor details=============
                $distributorDetails = $distributor->showDistributorById($distId);
                // echo "<br><br>";
                // print_r($distributorDetails);
                $distName = $distributorDetails[0]['name'];

                $stokInDetailsCol1 = 'product_id';
                $stokInDetailsCol2 = 'batch_no';
                // ================ stok in detials ==================
                $stockInData = $StockInDetail->showStockInDetailsByTable($stokInDetailsCol1, $stokInDetailsCol2, $productId,  $batchNo);
                // echo "<br><br>";
                // print_r($stockInData);
                foreach ($stockInData as $stockData) {
                    $purchaseDate = $stockData['added_on'];
                    $purchaseDate = date("d/m/Y", strtotime($purchaseDate));
                    $mfd = $stockData['mfd_date'];
                    $expDate = $stockData['exp_date'];
                    $purchaseQTY = $stockData['qty'];
                    $freeQTY = $stockData['free_qty'];
                    $MRP = $stockData['mrp'];
                    $PTR = $stockData['ptr'];
                    $gstParecent = $stockData['gst'];
                    $GST = $stockData['gst_amount'];
                    $customString1 = '(';
                    $customString2 = '%)';
                    $GST = $GST . $customString1 . $gstParecent . $customString2;
                    $discountParcent = $stockData['discount'];
                    $discountAmount = ($PTR * $discountParcent) / 100;
                    $discount = $discountAmount . $customString1 . $discountParcent . $customString2;
                    $basePrice = $stockData['d_price'];
                    $purchaseAmount = $basePrice * $stockData['qty'];

                    $ProductWeightage = $stockData['weightage'];
                    $productUnit = $stockData['unit'];

                    $packagingDetail = $ProductWeightage . " " . $productUnit . " / ";
                }


                //=================== Packaging Detials ===================
                $packagingType = $prodcutDetails[0]['packaging_type'];

                $packagignData = $packagUnit->showPackagingUnitById($packagingType);
                $pacakagingUnitName = $packagignData[0]['unit_name'];



                // ================== product details ======================
            ?>
                <div class="row mt-2 justify-content-center" flex id="<?php echo 'table-row-' . $slNo ?>">
                    <div class="col-12 ps-2">
                        <div class="row p-4">
                            <div class="col-6">

                                <strong>Distributor Name: </strong><span><?php echo $distName ?></span><br>
                                <strong>Batch No: </strong><span><?php echo $batchNo ?></span><br>
                                <strong>Purchase Date: </strong><span><?php echo $purchaseDate ?></span><br>
                                <strong>MFD: </strong><span><?php echo $mfd ?></span><br>
                                <strong>Exp Date: </strong><span><?php echo $expDate ?></span><br>
                                <strong>Packaging Details : </strong><span><?php echo $packagingDetail . $pacakagingUnitName ?></span><br>
                                <strong>Purchase Quantity: </strong><?php echo $purchaseQTY . " " . $pacakagingUnitName ?></span><br>
                                <strong>Free Quantity: </strong><span><?php echo $freeQTY ?></span><br>

                            </div>

                            <div class="col-6">

                                <strong>MRP: </strong><span><?php echo $MRP ?></span><br>
                                <strong>PTR: </strong><span><?php echo $PTR ?></span><br>
                                <strong>GST: </strong><span><?php echo $GST ?></span><br>
                                <strong>Discount: </strong><span><?php echo $discount ?></span><br>
                                <strong>Base Price: </strong><span><?php echo $basePrice ?></span><br>
                                <strong>Purchase Amount: </strong><span><?php echo $purchaseAmount ?></span><br>
                                <strong>Current Stock: </strong><span><?php echo $currentStock ?></span><br>
                                <strong>Loose Stock: </strong><span><?php echo $looseStock ?></span><br>
                                <strong>Row Serial No: </strong><span><?php echo $slNo ?></span><br>

                            </div>

                            <!-- <div class="col-1">
                                <button class="button btn-danger" id="<?php echo 'table-row-' . $slNo ?>" value1="<?php echo $productId ?>" value2="<?php echo $batchNo ?>" onclick="customDelete('<?php echo 'table-row-' . $slNo ?>','<?php echo $productId ?>','<?php echo $batchNo ?>',this.id, this.value1,this.value2)">Delete</button>
                            </div> -->

                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-top">
                    <hr class="text-center w-100" style="height: 2px; color:black">
                </div>
            <?php
                $slNo++;
            }
            ?>
        </div>
    </div>
</body>
</html>