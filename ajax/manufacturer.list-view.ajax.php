<?php 
// echo dirname(dirname(__DIR__));
require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once SUP_ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'manufacturer.class.php';


$match = $_GET['match'];

$Manufacturer        = new Manufacturer();

if ($match == 'all') {
    $showmanufacturer   = json_decode($Manufacturer->manufSearch($match));
}else {
    $showmanufacturer   = json_decode($Manufacturer->manufSearch($match));
}


if ($showmanufacturer->status) {
    $showmanufacturer= $showmanufacturer->data;

    foreach ($showmanufacturer as $eachManufacturer) {
        echo "<div class='p-1 border-bottom list' id='$eachManufacturer->id' onclick='setManufacturer(this)'>
        $eachManufacturer->name
        </div>";
    }
}else {
    // echo "<p class='text-center font-weight-bold'>manufacturerNot Found!</p>";
    echo "<div class='p-1 border-bottom list'> $match </div>";
}
?>