<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';

$badge = '';
$match = $_GET['match'];

$Products        = new Products();

if ($match == 'all') {
    $showProducts   = json_decode($Products->prodSearchByMatchForUser($match, $adminId));
} else {
    $showProducts   = json_decode($Products->prodSearchByMatchForUser($match, $adminId));
}


if ($showProducts->status) {
    $showProducts = $showProducts->data;

    foreach ($showProducts as $showProducts) {
        // print_r($showProducts);

        if (property_exists($showProducts, 'comp_1') || property_exists($showProducts, 'comp_2')) {
            $comp1 = $showProducts->comp_1;
            $comp2 = $showProducts->comp_2;
        } else {
            $comp1 = '';
            $comp2 = '';
        }


        if (property_exists($showProducts, 'prod_req_status')) {
            $prodReqStatus = $showProducts->prod_req_status;
        } else {
            $prodReqStatus = '';
        }

        if (property_exists($showProducts, 'old_prod_flag')) {
            $oldProdReqFlag = $showProducts->old_prod_flag;
            if ($oldProdReqFlag == 1) {
                $badge = 'Modified Product';
                $badgeColor = 'badge-info';
            } elseif ($oldProdReqFlag == 0) {
                $badge = 'New Product';
                $badgeColor = 'badge-warning';
            }
        } else {
            $oldProdReqFlag = '';
            $badgeColor = 'badge-light';
        }

        
        if (property_exists($showProducts, 'edit_request_flag')) {
            $editRequestFlag = $showProducts->edit_request_flag;
            $table = 'products';
        } else {
            $editRequestFlag = '';
            $table = 'product_request';
        }

        if (!empty($comp1) && !empty($comp2)) {
            $compSeparetor = ',';
        }else {
            $compSeparetor = '';
        }

        echo "<div class='p-1 border-bottom list'>
                <div class='d-flex'>
                    <div class='col-sm-9 pl-2 fw-500' id='$showProducts->product_id' prodReqStatus='$prodReqStatus' oldProdReqFlag='$oldProdReqFlag' editRequestFlag='$editRequestFlag' table='$table' onclick='searchProduct(this)'>
                    $showProducts->name </div>
                    <div class='col-sm-2 justify-content-end'><span class='badge $badgeColor'>$badge</span></div>
                </div>

                
                <div class='ml-2 text-gray-700'>
                    <small> $comp1 $compSeparetor $comp2</small>
                </div>
                
            </div>";
    }
} else {
    // echo "<p class='text-center font-weight-bold'>manufacturerNot Found!</p>";
    echo "<div class='p-1 border-bottom list'> No data found </div>";
}
