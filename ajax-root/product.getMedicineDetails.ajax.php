<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . "itemUnit.class.php";

$Products       = new Products();
$PackagingUnits = new PackagingUnits();
$ItemUnit       = new ItemUnit;

// prodReqStatus
// oldProdReqStatus
// ============= get product name =================
if (isset($_GET["pName"])) {

    $prodData = json_decode($Products->showProductsById($_GET["pName"]));
    if ($prodData->status) {
        $prodDataDetails = $prodData->data;
        // print_r($prodDataDetails);
        if (isset($prodDataDetails->edit_request_flag)) {
            $editReqFlag = '1';
            $prodReqStatus = '';
            $oldProdReqStatus = '';
        } else {
            $editReqFlag = '';
            $prodReqStatus = '';
            $oldProdReqStatus = '';
        }

        $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["pName"], $adminId, $editReqFlag, $prodReqStatus, $oldProdReqStatus));
        $showProducts = $showProducts->data;
        // print_r($showProducts);
        foreach ($showProducts as $row) {
            // print_r($row);
            echo $row->name;
        }
    }
}

// ================ get power ===============
if (isset($_GET["power"])) {

    $prodData = json_decode($Products->showProductsById($_GET["power"]));
    if ($prodData->status) {
        $prodDataDetails = $prodData->data;
        // print_r($prodDataDetails);
        if (isset($prodDataDetails->edit_request_flag)) {
            $editReqFlag = '1';
            $prodReqStatus = '';
            $oldProdReqStatus = '';
        } else {
            $editReqFlag = '';
            $prodReqStatus = '';
            $oldProdReqStatus = '';
        }

        $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["power"], $adminId, $editReqFlag, $prodReqStatus, $oldProdReqStatus));
        // print_r($showProducts);
        if ($showProducts->status) {
            $showProducts = $showProducts->data;
            echo $showProducts[0]->power;
        }
    }
}
// echo "Hi";

// ======================= weightage ========================
if (isset($_GET["weightage"])) {

    $prodData = json_decode($Products->showProductsById($_GET["weightage"]));
    if ($prodData->status) {
        $prodDataDetails = $prodData->data;
        // print_r($prodDataDetails);
        if (isset($prodDataDetails->edit_request_flag)) {
            $editReqFlag = '1';
            $prodReqStatus = '';
            $oldProdReqStatus = '';
        } else {
            $editReqFlag = '';
            $prodReqStatus = '';
            $oldProdReqStatus = '';
        }

        $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["weightage"], $adminId, $editReqFlag, $prodReqStatus, $oldProdReqStatus));
        $showProducts = $showProducts->data;
        // print_r($showProducts);
        // $showWeightage = $Products->showProductsById($showProducts[0]['packaging_type']);
        // print_r($showPackType);
        foreach ($showProducts as $row) {
            echo $row->unit_quantity;
        }
    }
}

// ========================= unit ==============================
if (isset($_GET["unit"])) {

    $prodData = json_decode($Products->showProductsById($_GET["unit"]));
    if ($prodData->status) {
        $prodDataDetails = $prodData->data;
        // print_r($prodDataDetails);
        if (isset($prodDataDetails->edit_request_flag)) {
            $editReqFlag = '1';
            $prodReqStatus = '';
            $oldProdReqStatus = '';
        } else {
            $editReqFlag = '';
            $prodReqStatus = '';
            $oldProdReqStatus = '';
        }

        $showProducts = json_decode($Products->showProductsByIdOnUser($_GET["unit"], $adminId, $editReqFlag, $prodReqStatus, $oldProdReqStatus));
        $showProducts = $showProducts->data;

        foreach ($showProducts as $row) {
            $unitId =  $row->unit;

            echo $ItemUnit->itemUnitName($unitId);
        }
    }
}
