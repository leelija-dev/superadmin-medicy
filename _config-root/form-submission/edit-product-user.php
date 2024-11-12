<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; // Check if admin is logged in

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';
require_once CLASS_DIR . 'productCategory.class.php';
require_once CLASS_DIR . 'gst.class.php';
require_once CLASS_DIR . 'utility.class.php';

// Objects Initialization
$Products           = new Products();
$Request            = new Request();
$Manufacturer       = new Manufacturer();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();
$ProductImages      = new ProductImages();
$ItemUnit           = new ItemUnit();
$ProductCategory    = new ProductCategory();
$Gst                = new Gst();
$Utility            = new Utility;


// Fetch data
$showManufacturer   = json_decode($Manufacturer->showManufacturerWithLimit());
$showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();
$showPackagingUnits = $PackagingUnits->showPackagingUnits();
$itemUnits          = $ItemUnit->showItemUnits();
$prodCategoryList   = json_decode($ProductCategory->selectAllProdCategory())->data;
$gstDetails         = json_decode($Gst->seletGst())->data;
$ticketNo           = $Utility->ticketNumberGenerator();

// addedBy based on session
// $addedBy = ($_SESSION['ADMIN']) ? $adminId : $employeeId;


// ========= data processing gose hear ===========

if (isset($_POST['update-product'])) {

    $productId  =   $_POST['product-id'];
    $oldProdId = $productId;
    $tableName = $_POST['table-name'];
    // echo $tableName;
    $productName      = $_POST['product-name'];
    $productCategory = $_POST['product-category']; // like : allopathy, drugs,  cosmetics etc.
    $packagingIn    = $_POST['packeging-type']; // strip, bottle, tubes etc.
    $quantity = $_POST['qantity']; // e.g. 10,20,100 etc.
    $unit = $_POST['unit']; // e.g. tablet, capsule, syrup etc.
    $medicinePower = $_POST['medicine-power']; // e.g. 5, 10, 25, 50, 500 etc.
    $mrp = $_POST['mrp'];
    $gstPercent = $_POST['gst'];
    $hsnoNumber = $_POST['hsno-number'];
    $comp1 = $_POST['comp-1'];
    $comp2 = $_POST['comp-2'];
    $unitId = $_POST['unitType'];
    $manufId = $_POST['manufId'];

    // echo $manufId;
    // ==================== for img ===================== //
    $imageName        = $_FILES['img-files']['name'];
    $tempImgName       = $_FILES['img-files']['tmp_name'];

    $imageArrayCount = count($imageName);
    $tempImageNameArrayCount = count($tempImgName);

    // =========== product edit description section =========
    $productData = json_decode($Products->showProductsByIdOnTableNameAdminId($productId, $adminId, $tableName));
    if ($productData->status) {
        $newProdAddFlag = []; // used to check new product add is needed or update old request.
        $oldProdData = $productData->data;
        // print_r($oldProdData);

        if ($productName != $oldProdData->name) {
            $nameEdit = 'Name edited. ';
            array_push($newProdAddFlag, 1);
        } else {
            array_push($newProdAddFlag, 0);
            $nameEdit = '';
        }

        if ($productCategory != $oldProdData->type) {
            $categoryEdit = 'Product Category Edited. ';
            array_push($newProdAddFlag, 2);
        } else {
            array_push($newProdAddFlag, 0);
            $categoryEdit = '';
        }

        if ($packagingIn != $oldProdData->packaging_type) {
            $packegeEdit = 'Package Type Edited. ';
            array_push($newProdAddFlag, 3);
        } else {
            array_push($newProdAddFlag, 0);
            $packegeEdit = '';
        }

        if ($quantity != $oldProdData->unit_quantity) {
            $medQtyEdit = 'Medicine Qantity Edited. ';
            array_push($newProdAddFlag, 4);
        } else {
            array_push($newProdAddFlag, 0);
            $medQtyEdit = '';
        }

        if ($unit != $oldProdData->unit) {
            $unitEdit = 'Unit Edited.';
            array_push($newProdAddFlag, 5);
        } else {
            array_push($newProdAddFlag, 0);
            $unitEdit = '';
        }

        if ($medicinePower != $oldProdData->power) {
            $medPowerEdit = 'Medicine Power Edited. ';
            array_push($newProdAddFlag, 6);
        } else {
            array_push($newProdAddFlag, 0);
            $medPowerEdit = '';
        }

        if ($mrp != $oldProdData->mrp) {
            $mrpEdit = 'MRP Edited. ';
            array_push($newProdAddFlag, 7);
        } else {
            array_push($newProdAddFlag, 0);
            $mrpEdit = '';
        }

        if ($gstPercent != $oldProdData->gst) {
            $gstEdit = 'GST Edited. ';
            array_push($newProdAddFlag, 8);
        } else {
            array_push($newProdAddFlag, 0);
            $gstEdit = '';
        }

        if ($hsnoNumber != $oldProdData->hsno_number) {
            $hsnEdit = 'HSN Number Edited. ';
            array_push($newProdAddFlag, 9);
        } else {
            array_push($newProdAddFlag, 0);
            $hsnEdit = '';
        }


        // Check if images exist for the product
        $images = json_decode($ProductImages->showImageById($productId));
        if (!$images->status) {
            $images = json_decode($ProductImages->showImageByPrimay($productId, $adminId));
        }


        if (!empty($imageName[0])) {
            if ($images->status) {
                $imgEdit = 'Image Edited.';
                array_push($newProdAddFlag, 10);
            } else {
                $imgEdit = 'Image Edited.'; 
                array_push($newProdAddFlag, 10); // Same result for both conditions here
            }
        } else {
            if ($images->status) {
                $imgEdit = ''; 
                array_push($newProdAddFlag, 0); // Empty string if status is true but imageName[0] is empty
            } else {
                $imgEdit = ''; 
                array_push($newProdAddFlag, 0); // Empty string if both status is false and imageName[0] is empty
            }
        }
        

        $description = $nameEdit . $categoryEdit . $packegeEdit . $medQtyEdit . $unitEdit . $medPowerEdit . $mrpEdit . $gstEdit . $hsnEdit . $imgEdit;


        if(isset($productData->old_prod_flag)){
            echo 'old prod flag found';
        }
    }



    $imageDataTuple = json_encode(['imageNmArray' => $imageName, 'tempImageNmArray' => $tempImgName, 'imgArrayCount' => $imageArrayCount, 'tempImgArrayCount' => $tempImageNameArrayCount, 'addedBy' => $addedBy, 'adminId' => $adminId]); // createing image data tupel ------


    // ------------- image update function ----------------------
    function imageUpdate($imageDataTuple, $productId, $Request)
    {
        try {
            $imageDataTuple = json_decode($imageDataTuple);

            $imageName = $imageDataTuple->imageNmArray;
            $tempImgName = $imageDataTuple->tempImageNmArray;

            $imageArrayCount = $imageDataTuple->imgArrayCount;
            $tempImageNameArrayCount = $imageDataTuple->tempImgArrayCount;

            $addedBy = $imageDataTuple->addedBy;
            $adminId = $imageDataTuple->adminId;

            for ($i = 0, $j = 0; $i < $imageArrayCount && $j < $tempImageNameArrayCount; $i++, $j++) {
                ////////// RANDOM 12DIGIT STRING GENERATOR FOR IMAGE NAME PRIFIX \\\\\\\\\\\\\
                $imgStatus = 1;

                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';

                for ($k = 0; $k < 9; $k++) {
                    $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }

                ////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                //===== Main Image 
                $image          = $imageName[$i];
                $tempImage        = $tempImgName[$j];

                $extention = substr($image, -4);
                $imageFileName = substr($image, 0, -4);

                $imageFile  =   $imageFileName . '-' . $randomString . $extention;
                $imgFolder     = PROD_IMG . $imageFile;

                move_uploaded_file($tempImage, $imgFolder);
                $image         = addslashes($imageFile);

                $addImagesRequest = $Request->addImageRequest($productId, $image, $addedBy, NOW, $adminId, $imgStatus);

                if (!$addImagesRequest) {
                    throw new Exception("Failed to add image for product ID: $productId");
                }
            }
            return true;
        } catch (Exception $e) {
            return error_log("Error in imageUpadate function: " . $e->getMessage());
        }
    } // image function bracket end 

    // --------------- product edit request code gose hear --------------------
    if($tableName == 'products'){
        // add new data to product request table
        $randNum = rand(1, 999999999999);
        $newProductId = 'PR' . $randNum;
        $status = 1;
        $oldProdFlag = 1;

        $addProductRequest = $Request->addOldProductRequest($ticketNo, $oldProdId, $newProductId, $productName, $comp1, $comp2, $productCategory, $packagingIn, $quantity, $unitId, $unit,  $manufId, $medicinePower, $mrp, $gstPercent, $hsnoNumber, $description, $ADDEDBY, NOW, $ADMINID, $status, $oldProdFlag);
        
        $addUpadteProductRequestStatus = json_decode($addProductRequest);
    }

    if($tableName == 'product_request'){
        $editProdId = $oldProdData->product_id;

        if(isset($oldProdData->old_prod_flag)){
            if($oldProdData->old_prod_flag == 1){
                $oldProdFlag = 1;
            }else{
                $oldProdFlag = 0;
            }
        }else{
            $oldProdFlag = 0;
        }
        $status = 1;
        
        // update old data in product request 
        $updateProductRequest = $Request->editUpdateProductRequest($editProdId, $productName, $comp1, $comp2, $productCategory, $packagingIn, $quantity, $unitId, $medicinePower, $mrp, $gstPercent, $hsnoNumber, $description, $ADDEDBY, NOW, $status, $oldProdFlag, $ADMINID);

        $addUpadteProductRequestStatus = json_decode($updateProductRequest);
    }

    if($addUpadteProductRequestStatus->status){
        if(in_array(10, $newProdAddFlag)){
            if(in_array(1, $newProdAddFlag) || in_array(3, $newProdAddFlag) || in_array(4, $newProdAddFlag) || in_array(6, $newProdAddFlag) || in_array(7, $newProdAddFlag)){
                $imageAddReq = imageUpdate($imageDataTuple, $newProductId, $Request);
                $editRequest = $addUpadteProductRequestStatus->status;
            }else{
                $imageAddReq = imageUpdate($imageDataTuple, $oldProdId, $Request);
                $editRequest = $addUpadteProductRequestStatus->status;
            }
        }else{
            $imageAddReq = true;
            $editRequest = $addUpadteProductRequestStatus->status;
        }
    }

    /*
    $prodDataFromProducts = json_decode($Products->showProductsById($productId));
    // print_r($prodDataFromProducts);
    if ($prodDataFromProducts->status) {
        // echo 'hello 1';
        if (isset($prodDataFromProducts->data->edit_request_flag)) {
            // echo 'hello 2';
            if ($prodDataFromProducts->data->edit_request_flag == 0) {
                // echo 'hello 2-2';
                $oldProdFlag = 1;
                $prodReqStatus = 0;

                $randNum = rand(1, 999999999999);
                $newProductId = 'PR' . $randNum;

                echo $description;

                if (preg_match("/Name edited. /", $                                 ) || preg_match("/Medicine Qantity Edited. /", $description) /*|| preg_match("/Unit Edited./", $description)) {
                    $productId = $newProductId;
                } else {
                    $productId = $oldProdId;
                }

                $addOldProdEditRequest = $Request->addOldProductRequest($ticketNo, $oldProdId, $productId, $productName, $comp1, $comp2, $productCategory, $packagingIn,  $quantity, $unitId, $unit, $manufId, $medicinePower, $mrp, $gstPercent, $hsnoNumber, $description, $addedBy, NOW, $adminId, $prodReqStatus, $oldProdFlag);

                $addOldProdEditRequest = json_decode($addOldProdEditRequest);

                $editRqstFlgData = intval($prodDataFromProducts->data->edit_request_flag);

                if ($addOldProdEditRequest->status) {
                    // echo "$description";
                    $col = 'edit_request_flag';
                    $editRqstFlgData += 1;
                    $updateProduct = $Products->updateOnColData($col, $editRqstFlgData, $productId);

                    $editRequest = $addOldProdEditRequest->status;

                    if (preg_match("/Image Edited./", $description)) {
                        $imageUpdate = imageUpdate($imageDataTuple, $productId, $Request);
                        // print_r($imageUpdate);
                        if ($imageUpdate) {
                            $addImagesRequest = $imageUpdate;
                        }
                    } else {
                        $addImagesRequest = true;
                    }
                }
            }else{
                
                    // echo "check 3";
                    $oldProdFlag = 1;
                    $prodReqStatus = 0;
    
                    $randNum = rand(1, 999999999999);
                    $newProductId = 'PR' . $randNum;
    
                    if (preg_match("/Name edited. /", $description) || preg_match("/Medicine Qantity Edited. /", $description) /*|| preg_match("/Unit Edited./", $description)) {
                        $productId = $newProductId;
                    } else {
                        $productId = $oldProdId;
                    }
    
                    $addOldProdEditRequest = $Request->addOldProductRequest($ticketNo, $oldProdId, $productId, $productName, $comp1, $comp2, $productCategory, $packagingIn,  $quantity, $unitId, $unit, $manufId, $medicinePower, $mrp, $gstPercent, $hsnoNumber, $description, $addedBy, NOW, $adminId, $prodReqStatus, $oldProdFlag);
    
                    $addOldProdEditRequest = json_decode($addOldProdEditRequest);
    
                    $editRqstFlgData = intval($prodDataFromProducts->data->edit_request_flag);
    
                    if ($addOldProdEditRequest) {
                        $col = 'edit_request_flag';
                        $editRqstFlgData += 1;
                        $updateProduct = $Products->updateOnColData($col, $editRqstFlgData, $oldProdId);
    
                        $editRequest = true;
    
                        if (preg_match("/Image Edited./", $description)) {
                            $imageUpdate = imageUpdate($imageDataTuple, $productId, $Request);
                            // print_r($imageUpdate);
                            if ($imageUpdate) {
                                $addImagesRequest = true;
                            }
                        } else {
                            $addImagesRequest = true;
                        }
                    } else {
                        $editRequest = false;
                    }
                
            }
        } else {
            $selectFromProdReqTable = json_decode($Request->selectProductById($productId, $adminId));
            // print_r($selectFromProdReqTable);
            if ($selectFromProdReqTable->status) {
                echo 'status 1';
                $selectFromProdReqTable = $selectFromProdReqTable->data;
                // echo "check 2";
                // print_r($selectFromProdReqTable);
                $productId = $selectFromProdReqTable->product_id;
                $prodReqStatus = 0;
                $oldProdFlag = 1;

                $randNum = rand(1, 999999999999);
                $newProductId = 'PR' . $randNum;

                if (preg_match("/Name edited. /", $description) || preg_match("/Medicine Qantity Edited. /", $description) /*|| preg_match("/Unit Edited./", $description)) {
                    $productId = $newProductId;

                    $editRequest = $Request->addOldProductRequest($ticketNo, $oldProdId, $productId, $productName, $comp1, $comp2, $productCategory, $packagingIn,  $quantity, $unitId, $unit, $manufId, $medicinePower, $mrp, $gstPercent, $hsnoNumber, $description, $addedBy, NOW, $adminId, $prodReqStatus, $oldProdFlag);

                    $editRequest = json_decode($editRequest);

                } else {

                    $editRequest = $Request->editUpdateProductRequest($productId, $productName, $comp1, $comp2, $productCategory, $packagingIn, $quantity, $unit, $medicinePower, $mrp, $gstPercent, $hsnoNumber, $description, $addedBy, NOW, $prodReqStatus, $oldProdFlag, $adminId);

                    $editRequest = json_decode($editRequest);
                }

                if ($editRequest->status) {
                    // echo "check 2";
                    $editRequest = true;

                    if (preg_match("/Image Edited./", $description)) {
                        $imageUpdate = imageUpdate($imageDataTuple, $productId, $Request);
                        // print_r($imageUpdate);
                        if ($imageUpdate) {
                            $addImagesRequest = true;
                        }
                    } else {
                        $addImagesRequest = true;
                    }
                } else {
                    $editRequest = false;
                }
            } 
            
            // else {

            //     // echo "check 3";
            //     $oldProdFlag = 1;
            //     $prodReqStatus = 0;

            //     $randNum = rand(1, 999999999999);
            //     $newProductId = 'PR' . $randNum;

            //     if (preg_match("/Name edited. /", $description) || preg_match("/Medicine Qantity Edited. /", $description) /*|| preg_match("/Unit Edited./", $description)) {
            //         $productId = $newProductId;
            //     } else {
            //         $productId = $oldProdId;
            //     }

            //     $addOldProdEditRequest = $Request->addOldProductRequest($ticketNo, $oldProdId, $productId, $productName, $comp1, $comp2, $productCategory, $packagingIn,  $quantity, $unitId, $unit, $manufId, $medicinePower, $mrp, $gstPercent, $hsnoNumber, $description, $addedBy, NOW, $adminId, $prodReqStatus, $oldProdFlag);

            //     $addOldProdEditRequest = json_decode($addOldProdEditRequest);

            //     $editRqstFlgData = intval($prodDataFromProducts->data->edit_request_flag);

            //     if ($addOldProdEditRequest) {
            //         $col = 'edit_request_flag';
            //         $editRqstFlgData += 1;
            //         $updateProduct = $Products->updateOnColData($col, $editRqstFlgData, $oldProdId);

            //         $editRequest = true;

            //         if (preg_match("/Image Edited./", $description)) {
            //             $imageUpdate = imageUpdate($imageDataTuple, $productId, $Request);
            //             // print_r($imageUpdate);
            //             if ($imageUpdate) {
            //                 $addImagesRequest = true;
            //             }
            //         } else {
            //             $addImagesRequest = true;
            //         }
            //     } else {
            //         $editRequest = false;
            //     }
            // }
        }
    } else {
        $checkProdRqst = json_decode($Request->selectProductData($productId));
        // print_r($checkProdRqst->data);
        $prodReqStatus = 0;
        $oldProdFlag = $checkProdRqst->data->old_prod_flag;

        $randNum = rand(1, 999999999999);
        $newProductId = 'PR' . $randNum;

        if (preg_match("/Name edited. /", $description) || preg_match("/Medicine Qantity Edited. /", $description) /*|| preg_match("/Unit Edited./", $description)) {
            $productId = $newProductId;

            $addNewProduct = $Request->addOldProductRequest($ticketNo, $oldProdId, $productId, $productName, $comp1, $comp2, $productCategory, $packagingIn,  $quantity, $unitId, $unit, $manufId, $medicinePower, $mrp, $gstPercent, $hsnoNumber, $description, $addedBy, NOW, $adminId, $prodReqStatus, $oldProdFlag);

            $addNewProduct = json_decode($addNewProduct);
            $editRequest = $addNewProduct->status;

        } else {

            $productId = $oldProdId;

            $editRequest = $Request->editUpdateProductRequest($productId, $productName, $comp1, $comp2, $productCategory, $packagingIn, $quantity, $unit, $medicinePower, $mrp, $gstPercent, $hsnoNumber, $description, $addedBy, NOW, $prodReqStatus, $oldProdFlag, $adminId);

            $editRequest = json_decode($editRequest);
            // print_r($editRequest);
            $editRequest = $editRequest->status;
        }

        if (preg_match("/Image Edited./", $description)) {
            $imageUpdate = imageUpdate($imageDataTuple, $productId, $Request);

            if ($imageUpdate) {
                $addImagesRequest = true;
            }
        } else {
            $addImagesRequest = true;
        }
    }
    
    // print_r($editRequest);

     */

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

        if ($editRequest) {
            if ($imageAddReq) {
                
        ?>
                <script>
                    swal("Success", "Product updated request sent successfully!", "success").then((value) => {
                        parent.location.reload();
                    });
                </script>
            <?php
            } else {
            ?>
                <script>
                    swal("Error", "Product updatation failed!", "error").then((value) => {
                        parent.location.reload();
                    });
                </script>
    <?php
            }
        }
    }

    ?>

    </body>

    </html>