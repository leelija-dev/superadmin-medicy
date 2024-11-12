<?php

require_once realpath(dirname(dirname(dirname(__DIR__))) . '/config/constant.php');
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';

$Products      = new Products();
$ProductImages = new ProductImages();
$Unit = new MeasureOfUnits();
$Session = new SessionHandler();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update New Product Data</title>
    <script src="<?php echo JS_PATH ?>sweetAlert.min.js"></script>
</head>

<body>
    <?php

    if (isset($_POST['update-new-product-data'])) {

        $productId          = $_POST['product-id'];
        $prodName           = $_POST['product-name'];
        $prodCategory       = $_POST['product-catagory'];
        $prodPackageType    = $_POST['packeging-type'];
        $prodPower          = $_POST['medicine-power'];
        $prodUnit           = $_POST['unit'];
        $prodQantityPerUnit = $_POST['qantity-unit'];
        $prodMrp            = $_POST['mrp'];
        $prodGst            = $_POST['gst-percent'];
        $prodHSNO           = $_POST['hsno-number'];

        // echo "<br>PRODUCT NAME : $prodName";
        // echo "<br>PRODUCT HSNO NUMBER : $hsnoNumber";
        // echo "<br>PRODUCT CATAGORY : $prodCategory";
        // echo "<br>PRODUCT POWER : $medicinePower";
        // echo "<br>PRODUCT UNIT : $qantityUnit";
        // echo "<br>PRODUCT UNIT TYPE : $packegingUnit";
        // echo "<br>PRODUCT PACKAGING TYPE : $packegingType";
        // echo "<br>MRP : $mrp";
        // echo "<br>GST : $gst<br>";

        //update data in products table 
        $updateProduct = $Products->updateProductByUser($productId, $prodName, $prodCategory, $prodPackageType, $prodPower, $prodUnit, $prodQantityPerUnit, $prodMrp, $prodGst, $prodHSNO, $employeeId, NOW);

        if($updateProduct['status']){

    ?>
            <script>
                swal("Success", "Product Added!", "success")
                    .then((value) => {
                        parent.location.reload();
                    });
            </script>
        <?php
        } else {
        ?>
            <script>
                swal("Error", "Product Not Added!", "error")
                    .then((value) => {
                        parent.location.reload();
                    });
            </script>
    <?php
        }
    }

    ?>

</body>

</html>