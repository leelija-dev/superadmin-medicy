<?php
require_once realpath(dirname(dirname(__DIR__)). '/config/constant.php');
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';


$match = $_GET['match'];

$Products        = new Products();

if ($match == 'all') {
    $showProducts   = json_decode($Products->prodSearchByMatch($match));
} else {
    $showProducts   = json_decode($Products->prodSearchByMatch($match));
}


if ($showProducts->status) {
    $showProducts = $showProducts->data;

    foreach ($showProducts as $showProducts) {
        echo "<div class='p-1 border-bottom list'>
                <div class='' id='$showProducts->product_id' onclick='searchProduct(this)'>
                    $showProducts->name
                </div>

                <div>
                    <small>" . $showProducts->comp_1 . " , " . $showProducts->comp_2 . "</small>
                </div>
            </div>";
    }
} else {
    // echo "<p class='text-center font-weight-bold'>manufacturerNot Found!</p>";
    echo "<div class='p-1 border-bottom list'> No data found </div>";
}
