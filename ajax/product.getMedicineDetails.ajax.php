<?php

require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
require_once SUP_ADM_DIR .'_config/sessionCheck.php';//check admin loggedin or not //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'packagingUnit.class.php';
require_once CLASS_DIR."itemUnit.class.php";

$Products       = new Products();
$PackagingUnits = new PackagingUnits();
$ItemUnit       = new ItemUnit;

// ============= get product name =================
if (isset($_GET["pName"])) {
    $showProducts = json_decode($Products->showProductsById($_GET["pName"]));
    $showProducts = $showProducts->data;
    // print_r($showProducts);
    foreach ($showProducts as $row) {
       echo $row->name;
    }
}

// ================ get power ===============
if (isset($_GET["power"])) {
    $showProducts = $Products->showProductsById($_GET["power"]);
    // print_r($showProducts);
    echo $showProducts[0]['power'];
}
// echo "Hi";

// ========================= packege Type ====================
if (isset($_GET["pType"])) {
    $showProductsPType = $Products->showProductsById($_GET["pType"]);
    $showPackType = $PackagingUnits->showPackagingUnitById($showProductsPType[0]['packaging_type']);
    // print_r($showPackType);
    foreach ($showPackType as $row) {
       echo '<option value="'.$row["id"].'">'.$row["unit_name"].'</option>';

    }
}

// ========================== packege In ====================
if (isset($_GET["packegeIn"])) {
    $showProductsPackegeIn = $Products->showProductsById($_GET["packegeIn"]);
    $showPackType = $PackagingUnits->showPackagingUnitById($showProductsPackegeIn[0]['packaging_type']);
    foreach ($showPackType as $row) {
       echo $row["unit_name"];
    }
}

// ======================= weightage ========================
if (isset($_GET["weightage"])) {
    $showProducts = json_decode($Products->showProductsById($_GET["weightage"]));
    $showProducts = $showProducts->data;
    // print_r($showProducts);
    // $showWeightage = $Products->showProductsById($showProducts[0]['packaging_type']);
    // print_r($showPackType);
    foreach ($showProducts as $row) {
       echo $row->unit_quantity;
    }
}

// ========================= unit ==============================
if (isset($_GET["unit"])) {
    $showProducts = json_decode($Products->showProductsById($_GET["unit"]));
    $showProducts = $showProducts->data;

    foreach ($showProducts as $row) {
        $unitId =  $row->unit;

        echo $ItemUnit->itemUnitName($unitId);
    }
}
?>