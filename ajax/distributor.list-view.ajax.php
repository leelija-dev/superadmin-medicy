<?php 
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'distributor.class.php';


$match = $_GET['match'];

$Distributor        = new Distributor();

if ($match == 'all') {
    $showDistributor    = json_decode($Distributor->distributorSearch($match));
}else {
    $showDistributor    = json_decode($Distributor->distributorSearch($match));
}

if ($showDistributor->status == 1) {
    $showDistributor = $showDistributor->data;

    foreach ($showDistributor as $eachDistributor) {
        echo "<div class='p-1 border-bottom list' id='$eachDistributor->id' onclick='setDistributor(this)'>
        $eachDistributor->name
        </div>";
    }
}else {
    // echo "<p class='text-center font-weight-bold'>Distributor Not Found!</p>";
    echo "<div class='p-1 border-bottom list'> $match </div>";
}
?>