<?php
// require_once dirname(__DIR__) . '/config/constant.php';
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');
require_once SUP_ADM_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . "products.class.php";
require_once CLASS_DIR . "quantityUnit.class.php";
require_once CLASS_DIR . "packagingUnit.class.php";
require_once CLASS_DIR . "itemUnit.class.php";
require_once CLASS_DIR . "productsImages.class.php";
require_once CLASS_DIR . "manufacturer.class.php";
require_once CLASS_DIR . "currentStock.class.php";

$Products       = new Products();
$PackagingUnits = new PackagingUnits();
$ItemUnit       = new ItemUnit;
$ProductImages  = new ProductImages();
$Manufacturer   = new Manufacturer();
$CurrentStock   = new CurrentStock();
$QuantityUnit   = new QuantityUnit;



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <!-- new features added -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/product-view-modal.css">
    <style>
        #main-img {
            animation: show .5s ease;
        }

        @keyframes show {
            0% {
                opacity: 0;
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }


        .height-4 {
            height: 3rem;
        }

        .ob-cover {
            width: 100%;
            object-fit: cover;
        }

        #main-img {
            width: 18rem;
            height: 20rem;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_GET['id'])) {
        $productId = $_GET['id'];

        $product        = json_decode($Products->showProductsByIdOnTableName($_GET['id'], $_GET['table']));
        $product        = $product->data;
        // print_r($product);

        //old-prod-id fetch area ------------
        $oldProductId = ($_GET['table'] == 'product_request') ? $product->old_prod_id : '';

        // ====== manuf data area =====
        if (isset($product->manufacturer_id)) {
            $manuf          = json_decode($Manufacturer->showManufacturerById($product->manufacturer_id));
            if ($manuf->status) {
                // print_r($manuf->data);
                $manufName = $manuf->data->name;
            } else {
                $manufName = 'no manufacturer data found';
            }
        } else {
            $manufName = 'no manufacturer data found';
        }


        $itemstock      = $CurrentStock->showCurrentStocByPId($_GET['id']);

        $image          = json_decode($ProductImages->showImagesByProduct($_GET['id']));
        // print_r($image );

        if ($image->status) {
            $image = $image->data;
            foreach ($image as $image) {
                $Images[] = $image->image;
                $productId = $image->product_id;
            }
        } else {
            $Images[] = "default-product-image/medicy-default-product-image.jpg";
        }


        // echo '<script>';
        // echo 'var productId = ' . json_encode($productId) . '; ';
        // echo '</script>';

        $pack = json_decode($PackagingUnits->showPackagingUnitById($product->packaging_type));
        if ($pack->status) {
            $packUnit = $pack->data->unit_name;
        } else {
            $packUnit = '';
        }


        //======== item unit data fetch =======
        if (isset($product->unit_id)) {
            $itemQuantityUnit = json_decode($QuantityUnit->quantityUnitName($product->unit_id));
            // print_r($itemQuantityUnit);

            if ($itemQuantityUnit->status) {
                if (isset($itemQuantityUnit->data->short_name)) {
                    $qantityName = $itemQuantityUnit->data->short_name;
                } else {
                    $qantityName = '';
                }
            } else {
                $qantityName = '';
            }
        } else {
            $qantityName = '';
        }


        $itemUnitName = $ItemUnit->itemUnitName($product->unit);


        if (isset($product->comp_1)) {
            $comp1 = $product->comp_1;
        } else {
            $comp1 = '';
        }


        if (isset($product->comp_2)) {
            $comp2 = $product->comp_2;
        } else {
            $comp2 = '';
        }


        if (isset($product->dsc)) {
            $dsc = $product->dsc;
        } else {
            $dsc = '';
        }

    ?>
        <div class="container-fluid d-flex justify-content-center mt-2">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-4">
                        <div class="">
                            <div class="text-center border d-flex justify-content-center">
                                <img src="<?= PROD_IMG_PATH ?><?php echo $Images[0]; ?>" class="rounded ob-cover animated--grow-in" id="main-img" alt="...">
                            </div>
                            <div class="row height-3 mt-2 justify-content-center">
                                <?php foreach ($Images as $index => $imagePath) : ?>
                                    <div class="col-2 border border-2 m-1 p-0">
                                        <img src="<?= PROD_IMG_PATH ?><?php echo $imagePath; ?>" id="img-<?php echo $index; ?>" onclick="setImg(this.id)" class="rounded ob-cover h-100" alt="...">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="d-flex">
                            <div class="text-start col-7 mb-0 pb-0">
                                <h4><?php echo $product->name; ?></h4>
                                <h7><?php echo $manufName; ?></h7>
                                <h5 class="fs-5 fst-normal">₹ <?php echo $product->mrp; ?><span class="fs-6 fw-light"><small> MRP</small></span></h5>
                                <p class="fst-normal"><?php echo $product->unit_quantity; ?>
                                    <?= $qantityName . ' ' . $itemUnitName ?>/<?php echo $packUnit; ?></p>
                                <p>
                                    <small>
                                        <mark>
                                            Current Stock have :
                                            <?php
                                            if ($itemstock != null) {
                                                $qty = 0;
                                                foreach ($itemstock as $itemQty) {
                                                    $qty = $qty + $itemQty['qty'];
                                                }
                                                echo $qty;
                                                if ($qty == 1) {
                                            ?>
                                                    Unit
                                                <?php
                                                } else {
                                                ?>
                                                    Units
                                                <?php
                                                }
                                            } else {
                                                echo 0;
                                                ?>
                                                Unit
                                            <?php
                                                $qty = 0;
                                            }
                                            ?>
                                        </mark>
                                    </small>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <hr class="text-center w-100" style="height: 2px;">
                            <!-- <hr class="divider d-md-block" style="height: 2px;> -->
                        </div>
                        <div class="text-start">
                            <p>
                                <b>Composition: </b>
                                <br><?= $comp1; ?>
                                <br><?= $comp2; ?>
                            </p>
                        </div>
                        <div class="text-start">
                            <p><b>Description: </b> <br><?php echo $dsc; ?></p>
                        </div>
                    </div>

                    <div class="col-12 col-md-2" id="btn-ctrl-1">
                        <div class="col-md-12 d-flex">
                            <div class="col-sm-6 m-2">
                                <a id="anchor" href="<?= ADM_URL ?>edit-product.php?id=<?php echo $_GET['id']; ?>&table=<?php echo $_GET['table']; ?>"><button class="button1 btn-primary">Edit</button></a>
                            </div>

                            <div class="col-sm-6 m-2">
                                <button class="button1 btn-danger" onclick="del('<?php echo $_GET['id']; ?>', '<?php echo $_GET['table']; ?>', '<?php echo $oldProductId; ?>')">Reject</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row justify-content-center" id='btn-ctrl-2'>
                    <hr class="text-center w-100" style="height: 2px;">
                    <div class="d-flex col-sm-12">
                        <div class="col-md-6">
                            <a id="anchor" href="<?= ADM_URL ?>edit-product.php?id=<?php echo $_GET['id']; ?>&table=<?php echo $_GET['table']; ?>"><button class="button2 btn-primary">Edit</button></a>
                        </div>
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        <div class="col-md-6 d-flex justify-content-end">
                            <button class="button2 btn-danger" onclick="del('<?php echo $_GET['id']; ?>', '<?php echo $_GET['table']; ?>', '<?php echo $oldProductId; ?>')">Reject</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php
    }


    ?>

    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <script>
        const setImg = (id) => {
            img = document.getElementById(id).src;
            document.getElementById("main-img").src = img;
        }

        //========================= Delete Product =========================
        const del = (prodId, table, oldProdId) => {

            const btnID = prodId;
            const tblNm = table;
            const oldProductId = oldProdId;

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "product.Delete.ajax.php",
                        type: "POST",
                        data: {
                            productId: btnID,
                            id: btnID,
                            table: tblNm,
                            oldProdId: oldProductId,
                        },
                        success: function(data) {
                            if (data) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Item has been deleted.",
                                    icon: "success"
                                }).then(() => {
                                    parent.location.reload();
                                });
                            } else {
                                Swal.fire("Failed", "Product Deletion Failed!", "error");
                                $("#error-message").html("Deletion Failed!").slideDown();
                                $("#success-message").slideUp();
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.error("Error:", errorThrown);
                            Swal.fire("Error", "An error occurred while processing your request.", "error");
                        }
                    });
                }
            });
        };
    </script>

    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.min.js"></script>

    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>
</body>

</html>