<?php
// require_once dirname(__DIR__).'/config/constant.php';
require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
require_once CLASS_DIR.'labtypes.class.php';

$updateLabType = $_GET['editCatDtlsId'];

// $img =$_GET['editTestCategoryImage']['name'];
// $tempImgname = $_GET['editTestCategoryImage']['tmp_name'];
// $labCategoryImage = "img/lab-tests/".$img;
// move_uploaded_file($tempImgname, $labCategoryImage);

$testTypeName = $_GET['editTestCategoryName'];
$pvdBy = $_GET['editTestCategoryProvidedBy'];
$dsc = $_GET['editTestCategoryDsc'];

// echo $updateLabType.'<br> <br> <br>';
// echo $testTypeName.'<br> <br> <br>';
// echo $pvdBy.'<br> <br> <br>';
// echo $dsc.'<br> <br> <br>';
// exit;

$labtypes = new LabTypes();
$updateLabtypes = $labtypes->updateLabTypes($testTypeName, $pvdBy, $dsc, /*Last Veriable to select the id of the lab tyoe whichi we wants to delete*/ $updateLabType);
// if ($updateLabtypes) {
    echo "<p>Details has been succesfully Updated</p>";
// }
?>