<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';

$Products = new Products();


if (isset($_POST['id'])) {
print($_POST);

// $updateProduct = $Products->updateProduct($_POST['id'], $_POST['manufacturer'], $_POST['product-name'], $_POST['product-composition'], $_POST['medicine-power'],  $_POST['product-descreption'], $_POST['packaging-type'], $_POST['unit-quantity'], $_POST['unit'], $_POST['mrp'], $_POST['gst'], $_POST['added-by'], NOW);

if ($updateProduct == TRUE) {
   return true;
}

}
