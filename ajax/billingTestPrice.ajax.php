<?php
// require_once dirname(__DIR__).'/config/constant.php';
require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'sub-test.class.php';


$subTestId = $_GET['subtest_id'];

$SubTests = new SubTests();
$showSubTestsId = $SubTests->showSubTestsId($subTestId);

foreach($showSubTestsId as $rowsSubTest){
    $testPrice = $rowsSubTest['price'];
    echo $testPrice;

}


?>