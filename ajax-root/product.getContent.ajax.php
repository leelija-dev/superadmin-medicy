<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR.'products.class.php';

$Manufacturer = new Manufacturer();
$Products     = new Products();

// ===================== product content =====================

if (isset($_GET["prodComposition"])) {
    
    $showProducts = $Products->showProductsById($_GET["prodComposition"]);
    $showProducts = json_decode($showProducts,true);
    if(isset($showProducts['status']) && $showProducts['status'] == '1'){
        $data = $showProducts['data'];
        echo $data['comp_1'] . ',' . $data['comp_2'];
    }
    
    // echo $showProducts[0]['comp_1'].', '.$showProducts[0]['comp_1'];
}

?>