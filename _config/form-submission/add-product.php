<?php
require_once dirname(dirname(dirname(__DIR__))) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'request.class.php';


$Products       = new Products();
$ProductImages  = new ProductImages();
$Unit           = new MeasureOfUnits();
$Session        = new SessionHandler();
$Request        = new Request;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <script src="<?php echo JS_PATH ?>sweetAlert.min.js"></script>
</head>

<body>
    <div>
    </div>
    <?php

    if (isset($_POST['add-product'])) {

        $imagesName         = $_FILES['img-files']['name'];
        $tempImgsName       = $_FILES['img-files']['tmp_name'];

        $imageArrayCaount = count($imagesName);
        $tempImageArrayCaount = count($tempImgsName);

        if ($imageArrayCaount >= 1) {
            if ($imagesName[0] != '') {
                $imageAdded = true;
            } else {
                $imageAdded = false;
            }
        } else {
            $imageAdded = false;
        }


        $productName = $_POST['product-name'];
        // print_r($prodName);
        $productComp1  = $_POST['product-composition-1'];
        $productComp2  = $_POST['product-composition-2'];

        $hsnNumber  = $_POST['hsn-number'];
        $category  = $_POST['item-category'];
        $packagingType  = $_POST['packaging-type'];

        $medicinePower = $_POST['medicine-power'];
        $unitQuantity  = $_POST['quantity'];
        $unit          = $_POST['unit'];
        $itemUnit = $_POST['item-unit'];

        $manufacturerId  = $_POST['manufacturer'];

        $mrp           = $_POST['mrp'];
        $gst           = $_POST['gst'];
        $productDesc   = $_POST['product-descreption'];
        $addedBy       = $supAdminId;

        $verifyStatus = 1;

        //ProductId Generation
        $randNum = rand(1, 999999999999);
        $newProductId = 'PR' . $randNum;


        $imageDataTuple = json_encode(['imageNmArray' => $imagesName, 'tempImageNmArray' => $tempImgsName, 'imgArrayCount' => $imageArrayCaount, 'tempImgArrayCount' => $tempImageArrayCaount, 'addedBy' => $addedBy, 'adminId' => $addedBy, 'productId' => $newProductId]); // createing image data tupel ------


        function addImage($imageDataTuple, $ProductImages)
        {
            try {
                $imageDataTuple = json_decode($imageDataTuple);

                $imagesName = $imageDataTuple->imageNmArray;
                $tempImgsName = $imageDataTuple->tempImageNmArray;

                $imageArrayCount = $imageDataTuple->imgArrayCount;
                $tempImageArrayCount = $imageDataTuple->tempImgArrayCount;

                for ($j = 0; $j < $imageArrayCount && $j < $tempImageArrayCount; $j++) {
                    ////////// RANDOM 12DIGIT STRING GENERATOR FOR IMAGE NAME PREFIX \\\\\\\\\\\\\

                    $imgStatus = 1;

                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $randomString = '';
                    for ($i = 0; $i < 9; $i++) {
                        $randomString .= $characters[rand(0, strlen($characters) - 1)];
                    }

                    $randomString = $randomString;

                    ////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                    //===== Main Image 
                    $imageName        = $imagesName[$j];
                    $tempImageName   = $tempImgsName[$j];

                    $extension = substr($imageName, -4);
                    $imageNameWithoutExtension = substr($imageName, 0, -4);

                    $image         = $imageNameWithoutExtension . '-' . $randomString . $extension;
                    $imgFolder     = PROD_IMG . $image;

                    move_uploaded_file($tempImageName, $imgFolder);
                    $image         = addslashes($image);

                    $addImages = $ProductImages->addImagesBySupAdmin($imageDataTuple->productId, $image, $imgStatus, $imageDataTuple->addedBy, NOW, $imageDataTuple->adminId);
                }

                return $addImages;
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }



        //Insert into products table 
        $addProducts = $Products->addProductBySuperAdmin($newProductId, $productName, $productComp1, $productComp2, $hsnNumber, $category, $packagingType, $medicinePower, $unitQuantity, $unit, $itemUnit, $manufacturerId, $mrp, $gst, $productDesc, $supAdminId, $verifyStatus, NOW);

        $addProducts = json_decode($addProducts);
        if ($addProducts->status) {
            if ($imageAdded) {
                $imageAdd = addImage($imageDataTuple, $ProductImages);
                // echo "<br>add image : $imageAdd<br>";
                if($imageAdd){
                    $addProduct = true;
                }else{
                    $addProduct = false;
                }
            } else {
                $addProduct = true;
            }
        }else{
            $addProduct = false;
        }

       
        if ($addProduct) {
    ?>
            <script>
                swal("Success", "Product Added!", "success")
                    .then((value) => {
                        window.location = '<?php echo ADM_URL ?>add-products.php';
                    });
            </script>
        <?php
        } else {
        ?>
            <script>
                swal("Error", "Product Not Added Properly!", "error")
                    .then((value) => {
                        window.location = '<?php echo ADM_URL ?>add-products.php';
                    });
            </script>
    <?php
        }
    }
    ?>

</body>

</html>