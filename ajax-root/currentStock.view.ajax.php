<?php

// echo $_GET['currentStockId'];
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';

//Classes Initilizing
// $Patients       =   new Patients();
$IdGeneration   =   new IdsGeneration();
$CurrentStock   =   new CurrentStock();
$StockIn        =   new StockIn();
$StockInDetail  =   new StockInDetails();
$Product        =   new Products();
$ProductImages  =   new ProductImages();
$distributor    =   new Distributor();
$manufacturer   =   new Manufacturer();
$packagUnit     =   new PackagingUnits();


if (isset($_GET['currentStockId'])) {
    $productId =  $_GET['currentStockId'];
    // echo $productId;
    $editReqFlag = $_GET['editReqFlag'];


    // ================= PRODUCT CURRENT STOCK IN QTY ============
    $showStock = json_decode($CurrentStock->showCurrentStockByPIdAndAdmin($productId, $adminId));
    // print_r($showStock);

    if ($showStock->status) {
        $showStock = $showStock->data;
        // print_r($showStock);
        $overallCurrentStock = 0;
        foreach ($showStock as $currentQty) {
            $currentQty = $currentQty->qty;
            $overallCurrentStock += $currentQty;
        }
    }

    if ($overallCurrentStock == null) {
        $overallCurrentStock = 0;
    }

    //========================================
    $checkProduct = json_decode($Product->productExistanceCheck($productId));
    if ($checkProduct->status) {
        $flag = 1;
    } else {
        $flag = '';
    }
    //=========================================

    $prodcutDetails = json_decode($Product->showProductsByIdOnUser($productId, $adminId, $flag));
    $prodcutDetails = $prodcutDetails->data;

    // print_r($prodcutDetails);

    if(isset($prodcutDetails[0]->comp_1)){
        $prodComp1 = $prodcutDetails[0]->comp_1;
    }else{
        $prodComp1 = '';
    }

    if(isset($prodcutDetails[0]->comp_1)){
        $prodComp2 = $prodcutDetails[0]->comp_2;
    }else{
        $prodComp2 = '';
    }


    if (isset($prodcutDetails[0]->manufacturer_id)) {
        $manufDetails = json_decode($manufacturer->showManufacturerById($prodcutDetails[0]->manufacturer_id));
        if($manufDetails->status){
            $manufDetails = $manufDetails->data;
            $manufName = $manufDetails->name;
        }else{
            $manufName = '';
        }
    }else{
        $manufName = '';
    }


    // print_r($manufDetails);

    $image = json_decode($ProductImages->showImageById($productId));
    $image = $image->data;
    // print_r($image);

    if ($image != null) {
        $productImage = $image[0]->image;
    } else {
        $productImage = 'default-product-image/medicy-default-product-image.jpg';
    }

    // ================= PRODUCT TOTAL STOCK IN QTY ==============
    $StockinQty = $StockInDetail->showStockInDetailsByPId($productId);
    // print_r($StockinQty); echo "<br><br>";
    if ($StockinQty != null) {
        $overallStockInQTY = 0;
        foreach ($StockinQty as $stockinQ) {
            $purchaseQty = $stockinQ['qty'];
            $freeQty = $stockinQ['free_qty'];
            $totalQ = intval($purchaseQty) + intval($freeQty);
            $overallStockInQTY += $totalQ;
        }
    }
    if ($StockinQty == null) {
        $overallStockInQTY = 0;
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <title>Product Details</title>

    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Sweet Alert Js  -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

    <!-- Core plugin JavaScript-->
    <!-- <script src="../../assets/jquery-easing/jquery.easing.min.js"></script> -->
    <!-- <script src="../../js/ajax.custom-lib.js"></script> -->
    <!-- <script src="../../js/jquery.prettyPhoto.js"></script>
    <script src="../../js/jquery.vide.js"></script> -->

    <script src="<?= JS_PATH ?>contact-us-js/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>contact-us-js/jquery.validate.min.js"></script>

    <!-- Custom scripts for all pages-->
    <!-- <script src="../js/sb-admin-2.js"></script> -->

    <!-- <script src="../vendor/product-table/jquery.dataTables.js"></script>
    <script src="../vendor/product-table/dataTables.bootstrap4.js"></script> -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <!-- <script src="../../js/bootstrap-js-4/bootstrap.bundle.min.js"></script> -->

    <!-- Custom JS -->
    <script src="<?= JS_PATH ?>custom-js.js"></script>
    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
</head>

<body>
    <div class="container-fluid d-flex justify-content-center mt-2">
        <div class="container-fluid">
            <div class="row p-4 justify-content-left">
                <div class="col-sm-3 justify-content-center">
                    <div class="text-center border d-flex justify-content-center">
                        <img src="<?= PROD_IMG_PATH ?><?php echo $productImage ?>" class="img-fluid rounded" alt="...">
                        <!-- <hr class="hl justify-content-center" style="color: black;"> -->
                    </div>
                </div>
                <!-- <div class="col-sm-1 justify-content-center">
                 <div class="vl justify-content-center"></div> 
                </div> -->
                <div class="col-sm-6 justify-content-center" flex>
                    <h3><?php echo $prodcutDetails[0]->name; ?></h3>
                    <h7><?php echo $prodComp1; ?></h7>
                    <h7><?php echo $prodComp2; ?></h7>
                    <h5><?php echo $manufName; ?></h5>
                </div>
                <div class="col-sm-1 justify-content-center">

                </div>

                <div class="col-sm-2 justify-content-center">
                    <!-- <button class="button btn-danger" id="<?php echo $productId ?>" value1="<?php echo $overallStockInQTY ?>" value2="<?php echo $overallCurrentStock ?>" onclick="delAll('<?php echo $productId ?>', '<?php echo $overallStockInQTY ?>', '<?php echo $overallCurrentStock ?>', this.id, this.value1, this.value2)">Delete All</button> -->
                </div>

            </div>

            <!-- <div class="d-flex justify-content-top">
                <hr class="text-center w-100" style="height: 2px; color:black">
            </div> -->

            <?php
            $slNo = 1;
            foreach ($showStock as $stock) {
                // print_r($stock);
                $stokInID = $stock->stock_in_details_id;
                $batchNo = $stock->batch_no;
                $distId = $stock->distributor_id;
                $currentStock = $stock->qty;
                $looseStock = $stock->loosely_count;

                //===============distributor details=============
                $distributorDetails = $distributor->showDistributorById($distId);
                $distributorDetails = json_decode($distributorDetails, true);

                if (isset($distributorDetails['status']) && $distributorDetails['status'] == '1') {
                    $data     = $distributorDetails['data'];
                    $distName = $data['name'];
                }
                // $distName = $distributorDetails[0]['name'];

                $stokInDetailsCol1 = 'product_id';
                $stokInDetailsCol2 = 'batch_no';
                // ================ stok in detials ==================
                $stockInData = $StockInDetail->showStockInDetailsByTable($stokInDetailsCol1, $stokInDetailsCol2, $productId,  $batchNo);

                foreach ($stockInData as $stockData) {
                    // print_r($stockData);

                    $stockInId = $stockData['stokIn_id'];
                    $stockInDate = $StockIn->stockInByAttributeByTable('id', $stockInId);
                    $purchaseDate = $stockInDate[0]['added_on'];
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
                    $rate = round(floatval($stockData['d_price'])+(floatval($stockData['d_price'])*intval($gstParecent)/100),2);
                    $purchaseAmount = floatval($rate) * intval($stockData['qty']);

                    $ProductWeightage = $stockData['weightage'];
                    $productUnit = $stockData['unit'];

                    $packagingDetail = $ProductWeightage . " " . $productUnit . " / ";

                    $totalStockinQty = intval($purchaseQTY) + intval($freeQTY);

                    
                    // $overallQTY = 0;
                }

                //=================== Packaging Detials ===================
                $packagingType = $prodcutDetails[0]->packaging_type;
                $packagignData = json_decode($packagUnit->showPackagingUnitById($packagingType));
                $pacakagingUnitName = $packagignData->data->unit_name;

                // ================== product details ======================




            ?>
                <div class="d-flex justify-content-top">
                    <hr class="text-center w-100" style="height: 2px; color:black">
                </div>
                <div class="row mt-2 justify-content-center" flex id="<?php echo 'table-row-' . $slNo ?>">
                    <div class="col-12 ps-2">
                        <div class="row p-4">
                            <div class="col-6">

                                <strong>Distributor Name: </strong><span><?php echo $distName ?></span><br>
                                <strong>Batch No: </strong><span><?php echo $batchNo ?></span><br>
                                <strong>Purchase Date: </strong><span><?php echo $purchaseDate ?></span><br>
                                
                                <strong>Exp Date: </strong><span><?php echo $expDate ?></span><br>
                                <strong>Packaging Details : </strong><span><?php echo $packagingDetail . $pacakagingUnitName ?></span><br>
                                <strong>Purchase Quantity: </strong><?php echo $purchaseQTY . " " . $pacakagingUnitName ?></span><br>
                                <strong>Free Quantity: </strong><span><?php echo $freeQTY ?></span><br>

                            </div>

                            <div class="col-5">

                                <strong>MRP: </strong><span><?php echo $MRP ?></span><br>
                                <strong>Discount: </strong><span><?php echo $discount ?></span><br>
                                <strong>PTR: </strong><span><?php echo $PTR ?></span><br>
                                <!-- <strong>GST Amount/</strong><strong><?php echo $pacakagingUnitName ?> : </strong> -->
                                <!-- <span><?php echo $perItemGst ?></span> -->
                                <!-- <br> -->
                                
                                <strong>Rate : </strong><span><?php echo $rate ?></span><br>
                                <strong>Purchase Amount: </strong><span><?php echo $purchaseAmount ?></span><br>
                                <strong>TOTAL GST: </strong><span><?php echo $GST ?></span><br>
                                <strong>Current Stock: </strong><span><?php echo $currentStock ?></span><br>
                                <strong>Loose Stock: </strong><span><?php echo $looseStock ?></span><br>
                                <!-- <strong>Row Serial No: </strong><span><?php echo $slNo ?></span><br> -->
                            </div>

                            <div class="col-1">
                                <!-- <button class="button btn-danger" onclick="customDelete('<?php echo $stokInID ?>', '<?php echo $currentStock ?>','<?php echo 'table-row-' . $slNo ?>','<?php echo $totalStockinQty ?>')">Delete</button> -->
                            </div>

                        </div>
                    </div>
                </div>
            <?php
                $slNo++;
            }
            ?>
        </div>
    </div>
</body>

<script>
    // ============================ DELETE ALL STOCK DATA ================================
    const delAll = (id, value1, value2) => {
        // alert(id);
        // alert(value1);
        // alert(value2);
        let stokInQty = value1;
        let currentQty = value2;

        if (stokInQty != currentQty) {
            swal({
                icon: 'error',
                title: 'Oops...',
                text: 'Some customer have this product'
            })
        }

        if (stokInQty == currentQty) {
            swal({
                    title: "Are you sure?",
                    text: "Want to Delete This Data?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        // alert(id)
                        $.ajax({
                            url: "currentStock.delete.ajax.php",
                            type: "POST",
                            data: {
                                delID: id
                            },
                            success: function(response) {
                                // alert(response);
                                if (response == true) {
                                    swal(
                                        "Deleted",
                                        "Product Has Been Deleted",
                                        "success"
                                    ).then(function() {
                                        parent.location.reload();
                                    });

                                } else {
                                    swal("Failed", "Product Deletion Failed!",
                                        "error");
                                }

                            }
                        });
                    }
                    return false;
                });
        }

    }

    // ====================== DELTE PERTICULER STOCK DATA =======================

    const customDelete = (id, currentStockQty, tableRowNo, stockinQty) => {

        // alert(id);
        // alert(currentStockQty);
        // alert(stockinQty);
        // alert(tableRowNo);

        // let btnId = document.getElementById(itemId);
        let row = document.getElementById(tableRowNo);

        if (currentStockQty != stockinQty) {
            swal({
                icon: 'error',
                title: 'Oops...',
                text: 'Some customer have this product'
            })
        }

        if (currentStockQty == stockinQty) {
            swal({
                    title: "Are you sure?",
                    text: "Want to Delete This Data?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        // alert(value1);
                        // alert(value2);
                        $.ajax({
                            url: "currentStock.delete.ajax.php",
                            type: "POST",
                            data: {
                                delItemId: id,
                            },
                            success: function(response) {
                                console.log(response);
                                if (response == true) {
                                    swal(
                                        "Deleted",
                                        "Manufacturer Has Been Deleted",
                                        "success"
                                    ).then(function() {
                                        row.parentNode.removeChild(row);
                                        // $(id).closest("tr").fadeOut()
                                    });

                                } else {
                                    swal("Failed", response,
                                        "error");
                                }

                                // row.parentNode.removeChild(row);

                            }
                        });
                    }
                    return false;
                });
        }

    }
</script>

</html>