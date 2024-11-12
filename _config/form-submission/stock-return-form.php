<?php

require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once ROOT_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'stockReturn.class.php';
require_once CLASS_DIR.'idsgeneration.class.php';
require_once CLASS_DIR.'currentStock.class.php';


//  INSTANTIATING CLASS
$HelthCare       = new HealthCare();
$StockReturn     = new StockReturn();
$IdsGeneration    = new IdsGeneration();
$CurrentStock    =  new CurrentStock();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['stock-return'])) {
        
        $stockReturnId   = $IdsGeneration->stockReturnId();

        $stockInId = $_POST['stockInId'];
        $stockInDetailsId = $_POST['stok-in-details-id'];
        $distributorId   = $_POST['dist-id'];
        $distributorName = $_POST['dist-name'];
        $distBillNo = $_POST['dist-bill-no'];
        
        $returnDate      = $_POST['return-date'];
        $returnDate      = date("Y-m-d", strtotime($returnDate));

        $itemQty         = $_POST['items-qty'];
        $totalReturnQty  = $_POST['total-return-qty'];
        
        $returnGst       = $_POST['return-gst-val'];

        $refundMode      = $_POST['refund-mode'];
        // $billNo          = $_POST['bill-no'];
        $refund          = $_POST['refund'];

        $addedBy         = $employeeId;
        $addedOn         = NOW;
        $Admin           = $adminId;
        $status          = 'active';

        // echo "<br>Stock Return Id : "; print_r($stockReturnId); echo gettype($stockReturnId);
        // echo "<br>stock in Id : "; print_r($stockInId); echo gettype($stockInId);
        // echo "<br>Distributor Id : "; print_r($distributorId); echo gettype(intval($distributorId));
        // echo "<br>Distributor bill no : "; print_r($distBillNo); echo gettype($distBillNo);
        // echo "<br>Return Date : "; print_r($returnDate); echo gettype($returnDate);
        // echo "<br>Item Qantity : "; print_r($itemQty); echo gettype(intval($itemQty));
        // echo "<br>Total Return Qantity : "; print_r($totalReturnQty); echo gettype($totalReturnQty);
        // echo "<br>Refund GST amount : "; print_r($returnGst); echo gettype(floatval($returnGst));
        // echo "<br>Refund Mode : "; print_r($refundMode); echo gettype($refundMode);
        // echo "<br>Refund Amount : "; print_r($refund); echo gettype(floatval($refund));
        // echo "<br>status : "; print_r($status); echo gettype($status);
        // echo "<br>added by : "; print_r($addedBy); echo gettype($addedBy);
        // echo "<br>added on : "; print_r($addedOn); echo gettype($addedOn);
        // echo "<br>admin : "; print_r($Admin); echo gettype($Admin);
        // echo "<br><br><br>";
    
        $returned = $StockReturn->addStockReturn($stockReturnId, $stockInId, intval($distributorId), $returnDate, intval($itemQty), intval($totalReturnQty), floatval($returnGst), $refundMode, floatval($refund), $status, $addedBy, $addedOn, $Admin);
        
        $returnResult = $returned['result'];

        // $returnResult = true;
        if($returnResult == 'true'){

            //arrays
            $stokInDetailsId = $_POST['stok-in-details-id'];
            $productId      = $_POST['productId'];
            
            $productName    = $_POST['productName'];
            $ids            = count($productId);
        
            $batchNo        = $_POST['batchNo'];
            $expDate        = $_POST['expDate'];

            $setof          = $_POST['setof'];
            $unit           = preg_replace('/[0-9]/','',$setof);
            $weightage      = preg_replace('/[a-z]/','',$setof);

            $purchasedQty   = $_POST['purchasedQty'];
            $freeQty        = $_POST['freeQty'];
            $mrp            = $_POST['mrp'];
            $ptr            = $_POST['ptr'];
            
            $gstPercent     = $_POST['gst'];
            $gstPercent     = preg_replace('/[%]/','',$gstPercent);

            $discParcent    = $_POST['disc-percent'];
            $discParcent    = preg_replace('/[%]/','',$discParcent);

            $returnQty      = $_POST['return-qty'];
            $returnFQty     = $_POST['return-free-qty'];
            $refundAmount   = $_POST['refund-amount'];

            


        
            for ($i=0; $i < $ids; $i++) { 
                $currentStockData = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($stokInDetailsId[$i]));
                // print_r($currentStockData);
                    $wholeQty = $currentStockData->qty;
                    $looseQty = $currentStockData->loosely_count;
                    // echo "<br><br>current stock loose count : $looseQty";
                    // echo "<br>current stock whole count : $wholeQty";


                // echo "<br> Stock in detaisl id : $stokInDetailsId[$i]";
                // echo "<br>Return qty : $returnQty[$i]";
                // echo "<br>Return free qty : $returnFQty[$i]";
                // echo "<br>Item Unit name : $unit[$i]";

                if($unit[$i] == 'tablets' || $unit[$i] == 'capsules'){
                    $updatedLooseQty = intval($looseQty) - ((intval($returnQty[$i]) +  intval($returnFQty[$i])) * $weightage[$i]);
                    $updatedQty = intdiv($updatedLooseQty, $weightage[$i]);
                }else{
                    $updatedLooseQty = 0;
                    $updatedQty = intval($wholeQty) - (intval($returnQty[$i]) +  intval($returnFQty[$i]));
                }
            
                $updatedOn = NOW;
                
                if($_SESSION['ADMIN']){
                    $updatedBy = $adminId;
                }else{
                    $updatedBy = $employeeId;
                }


                // echo "<br>stock in details id : "; print_r($stokInDetailsId); echo "<br>",gettype($stokInDetailsId[$i]);
                // echo "<br>Updated qty : "; print_r($updatedQty); echo "<br>",gettype($updatedQty);
                // echo "<br>Updated L count : "; print_r($updatedLooseQty); echo "<br>",gettype($updatedLooseQty);
                // echo "<br>updated by : "; print_r($updatedBy); echo "<br>",gettype($updatedBy);
                // echo "<br>updated on : "; print_r($updatedOn); echo "<br>",gettype($updatedOn);
                

                // ============== update current stock function =================
                $updateCurrentStock = $CurrentStock->updateStockByReturnEdit(intval($stokInDetailsId[$i]), intval($updatedQty), intval($updatedLooseQty), $updatedBy, $updatedOn);



                // print_r($updateCurrentStock);
                // echo "<br><br>";
                // echo "<br>Stock Return Id : "; print_r($stockReturnId); echo "<br>",gettype($stockReturnId);
                // echo "<br>stokInDetailsId : "; print_r($stokInDetailsId); echo "<br>",gettype($stokInDetailsId[$i]);
                // echo "<br>Product Id : "; print_r($productId); echo "<br>",gettype($productId[$i]);
                // echo "<br>Product Name : "; print_r($productName);
                // echo "<br>Batch No : "; print_r($batchNo); echo "<br>",gettype($batchNo[$i]);
                // echo "<br>EXP Date : "; print_r($expDate); echo "<br>",gettype($expDate[$i]);
                // echo "<br>Set Of : "; print_r($setof); echo "<br>",gettype($setof[$i]);
                // echo "<br>ITEM UNIT Of : "; print_r($unit);
                // echo "<br> Item weaitage : "; print_r($weightage);
                // echo "<br>Purchase QTY : "; print_r($purchasedQty); echo "<br>",gettype($purchasedQty[$i]); 
                // echo "<br>Free QTY : "; print_r($freeQty); echo "<br>",gettype($freeQty[$i]);
                // echo "<br>MRP : "; print_r($mrp); echo "<br>",gettype($mrp[$i]);
                // echo "<br>PTR : "; print_r($ptr); echo "<br>",gettype($ptr[$i]);
                // echo "<br>GST parcent : "; print_r($gstPercent); echo "<br>",gettype($gstPercent[$i]);
                // echo "<br>DISCOUNT PARCENT ON PURCHASE : "; print_r($discParcent); echo "<br>",gettype($discParcent[$i]);
                // echo "<br>Return QTY : "; print_r($returnQty); echo "<br>",gettype($returnQty[$i]);
                // echo "<br>Return F QTY : "; print_r($returnFQty); echo "<br>",gettype($returnFQty[$i]);
                // echo "<br>Refund Amount : "; print_r($refundAmount); echo "<br>",gettype($refundAmount[$i]);
      
                // exit;

                // ====== add stock return function =============
                $detailesReturned = $StockReturn->addStockReturnDetails($stockReturnId, intval($stokInDetailsId[$i]), $productId[$i], $batchNo[$i], $expDate[$i], $setof[$i], intval($purchasedQty[$i]), intval($freeQty[$i]), floatval($mrp[$i]), floatval($ptr[$i]), intval($gstPercent[$i]), intval($discParcent[$i]), intval($returnQty[$i]), intval($returnFQty[$i]), floatval($refundAmount[$i]));

                // echo $productId[$i].'<br>';
                // echo $batchNo[$i].'<br>';
                // echo $expDate[$i].'<br>';
                // echo $setof[$i].'<br>';
                // echo $purchasedQty[$i].'<br>';
                // echo $freeQty[$i].'<br>';
                // echo $mrp[$i].'<br>';
                // echo $ptr[$i].'<br>';
                // echo $purchaseAmount[$i].'<br>';
                // echo $gst[$i].'<br>';
                // echo $returnQty[$i].'<br>';
                // echo $refundAmount[$i].'<br><br><br><br><br><br>';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Return</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/test-bill.css">

</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?php if($refundMode != 'Credit'){ echo "paid-bg";} ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="<?= SITE_IMG_PATH ?>logo-p.jpg" alt="Medicy">
                    </div>
                    <div class="col-sm-8">
                        <h4 class="text-start my-0"><?php echo $healthCareName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareAddress1.', '.$healthCareAddress2.', '.$healthCareCity.', '.$healthCarePin; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -8px; margin-bottom: 0px;">
                            <small><?php echo 'M: '.$healthCarePhno.', '.$healthCareApntbkNo; ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-dark">
                        <p class="my-0"><b>Return Bill</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id:
                                #<?php echo $stockReturnId; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Refund Mode:
                                <?php echo $refundMode;?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Return Date: <?php echo $returnDate;?></small>
                        </p>
                    </div>
                </div>
            </div>
            <hr class="my-0" style="height:1px; background: #000000; border: #000000;">
            <div class="row my-0">
                <div class="col-sm-6 my-0">
                    <p ><small><b>Distributor: </b>
                            <?= $distributorName; ?></small></p>
                </div>
                <div class="col-sm-6 my-0 text-end">
                    <p style="margin-top: -3px; margin-bottom: 0px;"><small><b>Bill Date: </b>
                            <?php echo $returnDate; ?></small></p>
                </div>

            </div>
            <hr class="my-0" style="height:1px;">

            <div class="row">
                <!-- table heading -->

                <div class="col-sm-2">
                    <small><b>Name</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Batch</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Packing</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Exp.</b></small>
                </div>
                <div class="col-sm-1" style="width: 7%;">
                    <small><b>P.Qty</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 5%;">
                    <small><b>Free</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>MRP</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>PTR</b></small>
                </div>
                <div class="col-sm-1 text-end" style="width: 7%;">
                    <small><b>GST%</b></small>
                </div>
                <div class="col-sm-1" style="width: 7%;">
                    <small><b>DISC%</b></small>
                </div>
                <div class="col-sm-1" style="width: 7%;">
                    <small><b>Return</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>Refund</b></small>
                </div>
                <!--/end table heading -->
            </div>

            <hr class="my-0" style="height:1px;">

            <div class="row">
                <?php

                    for ($i=0; $i < $ids; $i++) { 
                $slno = $i+1;

                        if ($slno >1) {
                            echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                        }
                        
                        // $stirng1 = '(';
                        // $stirng2 = 'F';
                        // $stirng3 = ')';
                        // if($returnFQty[$i] > 0){
                        //     $returnQty = $returnQty[$i].$stirng1.$returnFQty[$i].$stirng2.$stirng3;
                        // }else{
                        //     $returnQty = $returnQty[$i];
                        // }
                        // echo $returnQty[$i];
                echo '
                    <div class="col-sm-2 ">
                        <small>'.substr($productName[$i], 0, 15).'</small>
                    </div>
                    <div class="col-sm-1">
                        <small>'.strtoupper($batchNo[$i]).'</small>
                    </div>
                    <div class="col-sm-1">
                        <small>'.$setof[$i].'</small>
                    </div>
                    <div class="col-sm-1">
                        <small>'.$expDate[$i].'</small>
                    </div>
                    <div class="col-sm-1" style="width: 7%;">
                        <small>'.$purchasedQty[$i].'</small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 5%;">
                        <small>'.$freeQty[$i].'</small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small>'.$mrp[$i].'</small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small>'.$ptr[$i].'</small>
                    </div>
                    <div class="col-sm-1 text-end" style="width: 7%;">
                        <small>'.$gstPercent[$i].'</small>
                    </div>
                    <div class="col-sm-1" style="width: 7%;">
                        <small>'.$discParcent[$i].'</small>
                    </div>
                    <div class="col-sm-1" style="width: 7%;">
                        <small>'.$returnQty[$i].'('.$returnFQty[$i].'F'.')'.'</small>
                    </div>
                    <div class="col-sm-1 text-end">
                        <small>'.$refundAmount[$i].'</small>
                    </div>';
            
                    }
                
                ?>

            </div>
            <!-- </div> -->

            <!-- </div> -->
            <div class="footer">
                <hr calss="my-0" style="height: 1px;">

                <!-- table total calculation -->
                <div class="row my-0">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>CGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $returnGst / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>SGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $returnGst / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total GST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo floatval($returnGst); ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row text-end">
                            <small class="pt-0 mt-0">Total Items <b><?php echo $itemQty;?></b> & Total Units
                                <b><?php echo $totalReturnQty; ?></b></small>
                        </div>
                        <div class="row text-end mt-1">
                            <h5 class="mb-0 pb-0">Total Refund: <b>₹<?php echo floatval($refund); ?></b></h5>

                        </div>

                    </div>

                </div>



            </div>
            <hr style="height: 1px; margin-top: 2px;">
        </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <button class="btn btn-primary shadow mx-2" onclick="goBack()">Go Back</button>
        <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
    </div>
    </div>
</body>
<script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>

<script>
    const goBack = () =>{
        window.location.href = '<?= URL ?>stock-return.php';
    }
</script>
</html>