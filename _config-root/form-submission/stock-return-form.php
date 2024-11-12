<?php

require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not
require_once dirname(dirname(__DIR__)) . '/config/service.const.php';

require_once CLASS_DIR.'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR .'encrypt.inc.php';
require_once ROOT_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'stockReturn.class.php';
require_once CLASS_DIR.'idsgeneration.class.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'distributor.class.php';


//  INSTANTIATING CLASS
$HelthCare       = new HealthCare();
$StockReturn     = new StockReturn();
$IdsGeneration   = new IdsGeneration();
$CurrentStock    = new CurrentStock();
$Distributor     = new Distributor;


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['stock-return'])) {
        
        $stockReturnId      = $IdsGeneration->stockReturnId();

        $stockInId          = $_POST['stockInId'];
        $stockInDetailsId   = $_POST['stok-in-details-id'];
        $distributorId      = intval($_POST['dist-id']);
        $distributorName    = $_POST['dist-name'];
        
        $distData = json_decode($Distributor->showDistributorById($distributorId));
        $distAddress    = $distData->data->address;
        $distPin        = $distData->data->area_pin_code;
        $distContact    = $distData->data->phno;

        $returnDate      = date("Y-m-d", strtotime($_POST['return-date']));
        $itemQty         = intval($_POST['items-qty']);
        $totalReturnQty  = intval($_POST['total-return-qty']);
        $returnGst       = floatval($_POST['return-gst-val']);
        $refund          = floatval($_POST['refund']);
        $refundMode      = $_POST['refund-mode'];
        $status          = 1;

        $returned = $StockReturn->addStockReturn($stockReturnId, $stockInId, $distributorId, $returnDate, $itemQty, $totalReturnQty, $returnGst, $refundMode, $refund, $status, $addedBy, NOW, $ADMINID);

        if(is_array($returned) && $returned['result'] == 1){

            //arrays
            $stokInDetailsId = $_POST['stok-in-details-id'];
            $productId      = $_POST['productId'];
            $ids            = count($productId);
            
            $productName    = $_POST['productName'];
        
            $batchNo        = $_POST['batchNo'];
            $distBillNo     = $_POST['distBillNo'];
            $expDate        = $_POST['expDate'];

            $setof          = $_POST['setof'];
            
            $unit           = preg_replace('/[0-9]/','',$setof);
            // print_r($unit);
            $weightage      = preg_replace('/[a-z-A-Z]/','',$setof);

            $purchasedQty   = $_POST['purchasedQty'];
            $freeQty        = $_POST['free-qty'];
            $mrp            = $_POST['mrp'];
            $ptr            = $_POST['ptr'];
            
            $gstPercent     = preg_replace('/[%]/','',$_POST['gst']);
            $discParcent    = preg_replace('/[%]/','',$_POST['disc-percent']);

            $returnQty      = $_POST['return-qty'];
            // $returnFQty     = $_POST['return-free-qty'];
            $refundAmount   = $_POST['refund-amount'];

            // exit;

            // print_r($productId);
            for ($i=0; $i < $ids; $i++) { 
                $currentStockData = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($stokInDetailsId[$i]));

                $wholeQty = intval($currentStockData->qty);
                $looseQty = intval($currentStockData->loosely_count);

                if ($wholeQty >= intval($returnQty[$i])) {
                
                    if (in_array(strtolower(trim($unit[$i])), LOOSEUNITS)){
                        $updatedLooseQty = $looseQty - (intval($returnQty[$i]) * $weightage[$i]);
                        $updatedQty = intdiv($updatedLooseQty, $weightage[$i]);
                    }else{
                        $updatedLooseQty = 0;
                        $updatedQty = intval($wholeQty) - intval($returnQty[$i]);
                    }
                
                    $updatedBy = ($_SESSION['ADMIN']) ? $ADMINID : $EMPID;

                    // ============== update current stock function =================
                    $updateCurrentStock = $CurrentStock->updateStockByReturnEdit(intval($stokInDetailsId[$i]), intval($updatedQty), intval($updatedLooseQty), $updatedBy, NOW);

                    // ====== add stock return function =============
                    $detailesReturned = $StockReturn->addStockReturnDetails($stockReturnId, intval($stokInDetailsId[$i]), $productId[$i], $distBillNo[$i], $batchNo[$i], $expDate[$i], $setof[$i], intval($purchasedQty[$i]), intval($freeQty[$i]), floatval($mrp[$i]), floatval($ptr[$i]), intval($gstPercent[$i]), intval($discParcent[$i]), intval($returnQty[$i]), floatval($refundAmount[$i]));
                    
                    print_r($detailesReturned);
                }else {
                    echo 'Return quantity is more then current stock quantity of this item!';
                    exit;
                }
            }
        }
    }
}


// exit;
if (isset($detailesReturned) && ($detailesReturned == true)) {
    $response = url_enc(json_encode(['stock_return_id' => $stockReturnId]));
    // header("Location: ".URL."stock-return-invoice.php?data=".$response);
    $redirectUrl = URL."invoices/purchase-return-invoice.php?data=" . $response;
    header("Location: $redirectUrl");
    exit;
}

?>
