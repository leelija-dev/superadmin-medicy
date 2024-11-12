<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'packagingUnit.class.php';

$Products       = new Products();
$PackagingUnits = new PackagingUnits();
if (isset($_GET["id"])) {
    $showProducts = $Products->showProductsById($_GET["id"]);

    $showPackType = $PackagingUnits->showPackagingUnitById($showProducts[0]['packaging_type']);
    foreach ($showPackType as $row) {
       echo $row["unit_name"];

    }
}
// echo "Hi";

?>