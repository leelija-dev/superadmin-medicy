<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once dirname(dirname(__DIR__)) . '/config/constant.php';

// require_once dirname(dirname(dirname(__DIR__))) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';

// Require necessary classes
$required_classes = ['products', 'productsImages', 'manufacturer', 'measureOfUnit', 'packagingUnit', 'itemUnit', 'gst', 'productCategory', 'request'];

foreach ($required_classes as $class_name) {
    require_once CLASS_DIR . $class_name . '.class.php';
}


// Objects Initialization
$Products           = new Products();
$Manufacturer       = new Manufacturer();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();
$ProductImages      = new ProductImages();
$ItemUnit           = new ItemUnit();
$GST                = new Gst;
$ProductCategory    = new ProductCategory;
$Request            = new Request;

// Fetch necessary data
$showManufacturer   = json_decode($Manufacturer->showManufacturerWithLimit());
$showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();
$showPackagingUnits = $PackagingUnits->showPackagingUnits();
$itemUnits          = $ItemUnit->showItemUnits();
$gstData            = json_decode($GST->seletGst())->data;
$Category           = json_decode($ProductCategory->selectAllProdCategory())->data;

//======================= product update gose hear ============================
if (isset($_POST['update-product'])) {

    $table              = $_POST['table-info'];
    $ticketNo           = $_POST['ticket-no'];
    // echo $table;
    $productid          = $_POST['product-id'];
    $oldProductId       = $_POST['old-product-id'];
    $productName        = $_POST['product-name'];
    $productComp1       = $_POST['product-composition1'];
    $productComp2       = $_POST['product-composition2'];
    $hsnNumber          = $_POST['hsn-number'];
    $category           = $_POST['product-category'];
    $packagingType      = $_POST['packaging-type'];
    $medicinePower      = $_POST['medicine-power'];
    $quantity           = $_POST['quantity'];
    $qtyUnit            = $_POST['qty-unit'];
    $itemUnit           = $_POST['item-unit'];
    $manufacturerId     = $_POST['manufacturer'];
    $mrp                = $_POST['mrp'];
    $gst                = $_POST['gst'];
    $productDesc        = $_POST['product-description'];
    $productReqDsc      = $_POST['product-req-description'];
    $prodReqStatus      = $_POST['prod-req-status'];
    $oldProdFlag        = $_POST['old-prod-flag'];
    $editReqFlagData    = $_POST['edit-req-flag-data'];
    $imageName          = $_FILES['img-files']['name'];
   
    $tempImgName        = $_FILES['img-files']['tmp_name'];
    $verifyStatus       = 1;


    //---------------- image update function gose hear --------------------

    function imageUpdate($prodId, $imageData, $admId, $ProductImages)
    {
        try {
            $imageData = json_decode($imageData);

            $imageName = $imageData->imageNameArray;
            $tempImgName = $imageData->tempImgNmArray;

            for ($i = 0, $j = 0; $i < count($imageName) && $j < count($tempImgName); $i++, $j++) {
                $imgStatus = 0;
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';
                for ($k = 0; $k < 9; $k++) {
                    $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }

                $image          = $imageName[$i];
                $tempImage      = $tempImgName[$j];
                $extention      = substr($image, -4);
                $imageFileName  = substr($image, 0, -4);


                $imageFile  =   $imageFileName . '-' . $randomString . $extention;
                $imgFolder  = PROD_IMG_DIR . $imageFile;
                move_uploaded_file($tempImage, $imgFolder);
                $image      = addslashes($imageFile);

                $status = 1;

                $addImages = $ProductImages->addImagesBySupAdmin($prodId, $image, $status, $admId, NOW, $admId);

                if (!$addImages) {
                    throw new Exception("Failed to add image for product ID: $prodId");
                }
            }
            return true;
        } catch (Exception $e) {
            return error_log("Error in imageUpadate function: " . $e->getMessage());
        }
    }

    //--------------- image data tuple -------------------
    $imageData = json_encode(['imageNameArray' => $imageName, 'tempImgNmArray' => $tempImgName]);

    // ----------------------- product update call ------------------
    if ($table == 'products') {

        $productOldData = json_decode($Products->showProductsById($productid));

        if ($productOldData->status) {
            $oldProdData = $productOldData->data;
            // print_r($oldProdData);

            if ($productName != $oldProdData->name) {
                $nameEdit = 'Name edited. ';
            } else {
                $nameEdit = '';
            }

            if ($productComp1 != $oldProdData->comp_1) {
                $comp1 = 'Composition 1 edited. ';
            } else {
                $comp1 = '';
            }

            if ($productComp2 != $oldProdData->comp_2) {
                $comp2 = 'Composition 2 edited. ';
            } else {
                $comp2 = '';
            }

            if ($hsnNumber != $oldProdData->hsno_number) {
                $hsnEdit = 'HSN Number Edited. ';
            } else {
                $hsnEdit = '';
            }

            if ($category != $oldProdData->type) {
                $categoryEdit = 'Product Category Edited. ';
            } else {
                $categoryEdit = '';
            }

            if ($packagingType != $oldProdData->packaging_type) {
                $packegeEdit = 'Package Type Edited. ';
            } else {
                $packegeEdit = '';
            }

            if ($medicinePower != $oldProdData->power) {
                $medPowerEdit = 'Medicine Power Edited. ';
            } else {
                $medPowerEdit = '';
            }

            if ($quantity != $oldProdData->unit_quantity) {
                $medQtyEdit = 'Medicine Qantity Edited. ';
            } else {
                $medQtyEdit = '';
            }

            if ($qtyUnit != $oldProdData->unit_id) {
                $unitEdit = 'Item Unit Edited. ';
            } else {
                $unitEdit = '';
            }

            if ($itemUnit != $oldProdData->unit) {
                $unitEdit = 'Pack unit Edited. ';
            } else {
                $unitEdit = '';
            }

            if ($manufacturerId != $oldProdData->manufacturer_id) {
                $manuf = 'Manucaturer Edited. ';
            } else {
                $manuf = '';
            }

            if ($mrp != $oldProdData->mrp) {
                $mrpEdit = 'MRP Edited. ';
            } else {
                $mrpEdit = '';
            }

            if ($gst != $oldProdData->gst) {
                $gstEdit = 'GST Edited. ';
            } else {
                $gstEdit = '';
            }

            if ($productDesc != $oldProdData->dsc) {
                $dscEdit = 'Product description Edited. ';
            } else {
                $dscEdit = '';
            }

            $images = json_decode($ProductImages->showImagesByProduct($oldProductId));

            $imgEdit = (!empty($imageName[0])) ? (($images->status) ? 'Image Edited.' : 'Image Edited.') : (($images->status) ? '' : '');

            $editDescription = $nameEdit . $comp1 . $comp2 . $hsnEdit . $categoryEdit . $packegeEdit . $medPowerEdit . $medQtyEdit . $unitEdit . $unitEdit . $manuf . $mrpEdit . $gstEdit . $dscEdit . $imgEdit;
        }

        // update product in products table ----------------
        // echo "check 1";
        $updateProduct = json_decode($Products->updateProductBySuperAdmin($productid, $productName, $productComp1, $productComp2, $hsnNumber, $category, $packagingType, $medicinePower, $quantity, $qtyUnit, $itemUnit, $manufacturerId, $mrp, $gst, $productDesc, $SUPER_ADMINID, NOW, $verifyStatus));

        if ($updateProduct->status) {
            // echo "check 11";
            $col = 'prod_req_status';
            $data = 0;
            $updateProdRequestTable = json_decode($Request->updateProductRequestTable($ticketNo, $col, $data));
            if (preg_match("/Image Edited./", $editDescription)) {
                if (!empty($imageName[0])) {
                    $updateProduct = imageUpdate($productid, $imageData, $SUPER_ADMINID, $ProductImages);
                } else {
                    $updateProduct = $updateProduct->status;
                }
            } else {
                $updateProduct = $updateProduct->status;
            }
        } else {
            $updateProduct = false;
        }
    }


    if ($table == 'product_request') {
        // echo "added by".$addedBy;
        if ($prodReqStatus == 1 && $oldProdFlag == 0) { // new product add request
            // echo $productReqDsc;
            // echo "check 2";
            $addProductOnRequest = $Products->addProductBySuperAdmin($productid, $productName, $productComp1, $productComp2, $hsnNumber, $category, $packagingType, $medicinePower, $quantity, $qtyUnit, $itemUnit, $manufacturerId, $mrp, $gst, $productDesc, $SUPER_ADMINID, $verifyStatus, NOW);
            $addProductOnRequest = json_decode($addProductOnRequest);
            // print_r($addProductOnRequest);
            if ($addProductOnRequest->status) {
                $col = 'prod_req_status';
                $data = 0;
                $updateProdRequestTable = json_decode($Request->updateProductRequestTable($ticketNo, $col, $data));
                if (preg_match("/Image Edited./", $productReqDsc)) {
                    // echo "check 21";
                    if (!empty($imageName[0])) {
                        // echo "check 22";
                        $updateProduct = imageUpdate($productid, $imageData, $SUPER_ADMINID, $ProductImages);
                    } else {
                        $updateProduct = $addProductOnRequest->status;
                    }
                } else {
                    $updateProduct = $addProductOnRequest->status;
                }
            } else {
                $updateProduct = false;
            }
        } elseif ($prodReqStatus == 0 && $oldProdFlag == 1) { // old product edit request

            if (preg_match("/Name edited. /", $productReqDsc) || preg_match("/Medicine Qantity Edited. /", $productReqDsc) || preg_match("/Unit Edited./", $productReqDsc)) {
                // echo "check 3";
                $addProductOnRequest = $Products->addProductBySuperAdmin($productid, $productName, $productComp1, $productComp2, $hsnNumber, $category, $packagingType, $medicinePower, $quantity, $qtyUnit, $itemUnit, $manufacturerId, $mrp, $gst, $productDesc, $SUPER_ADMINID, $verifyStatus, NOW);
                $addProductOnRequest = json_decode($addProductOnRequest);

                if ($addProductOnRequest->status) {

                    $col = 'prod_req_status';
                    $data = 0;
                    $updateProdRequestTable = json_decode($Request->updateProductRequestTable($ticketNo, $col, $data));

                    if (preg_match("/Image Edited./", $productReqDsc)) {
                        // echo "check 31";
                        if (!empty($imageName[0])) {
                            // echo "check 32";
                            $updateProduct = imageUpdate($productid, $imageData, $SUPER_ADMINID, $ProductImages);
                        } else {
                            $updateProduct = $addProductOnRequest->status;
                        }
                    } else {
                        $updateProduct = $addProductOnRequest->status;
                    }
                } else {
                    $updateProduct = false;
                }
            } else {

                $updateOnProdRequest = $Products->updateProductBySuperAdmin($oldProductId, $productName, $productComp1, $productComp2, $hsnNumber, $category, $packagingType, $medicinePower, $quantity, $qtyUnit, $itemUnit, $manufacturerId, $mrp, $gst, $productDesc, $SUPER_ADMINID, NOW, $verifyStatus);

                $updateOnProdRequest = json_decode($updateOnProdRequest);
                if ($updateOnProdRequest->status) {
                    // echo "check 41";

                    $col = 'prod_req_status';
                    $data = 0;
                    $updateProdRequestTable = json_decode($Request->updateProductRequestTable($ticketNo, $col, $data));

                    if (preg_match("/Image Edited./", $productReqDsc)) {
                        if (!empty($imageName[0])) {
                            // echo "check 42";
                            $updateProduct = imageUpdate($oldProductId, $imageData, $SUPER_ADMINID, $ProductImages);
                        } else {
                            $updateProduct = $updateOnProdRequest->status;
                        }
                    } else {
                        $updateProduct = $updateOnProdRequest->status;
                    }
                } else {
                    $updateProduct = false;
                }
            }
        }
    }


?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
    </head>

    <body>
        <?php

        if ($updateProduct) {
            
        ?>
            <script>
                swal("Success", "Product updated successfully!", "success").then((value) => {
                    parent.location.href = '<?php echo ADM_URL; ?>product-request-lsit.php';
                });
            </script>
        <?php

        } else {
        ?>
            <script>
                swal("Error", "Product updation failed!", "error").then((value) => {
                    parent.location.href = '<?php echo ADM_URL; ?>product-request-lsit.php';
                });
            </script>
    <?php
        }
    }

    ?>
    </body>

    </html>