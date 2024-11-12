<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'currentStock.class.php';

$CurrentStock = new CurrentStock();
if (isset($_GET["id"])) {

    $pid = $_GET["id"];
    $productIds = $_GET["chkBtch"];
    // print_r($productIds); 
    $productIds = explode (",", $productIds);
    // print_r($productIds);
    $count = 0;
    $flag = 0;

    $arrSize = count($productIds);
    $checkBatch = $productIds[$arrSize-1];
    $batchArray = $CurrentStock->fetchAllBatchnoByPid($checkBatch);
    // print_r($batchArray);
    $batchArrayCount = count($batchArray);

    for($i = 0; $i<count($productIds); $i++){
        if($productIds[$i] == $pid){
            $count++;
        }
    }

    // count()

    $count = $count - 1;

    $stock = $CurrentStock->showCurrentStocByPId($pid);
    
    if($batchArrayCount == 1){
        foreach ($batchArray as $batchNo) {
            echo $batchNo["batch_no"];
        }
    } 
    else{
        echo $stock[$count]['batch_no'];
    }
    
}
?> 