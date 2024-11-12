<?php
##########################################################################################################
#                                                                                                        #
#                                           Sales Return Page                                            #
#                                                                                                        #
##########################################################################################################

require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR."stockOut.class.php";
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'salesReturn.class.php';

$StockOut   = new StockOut();
$Products   = new Products();
$Patients   = new Patients();
$salesReturn = new SalesReturn();

$attribute1 = 'invoice_id';
$attribute2 = 'item_id';

// get patient name
if (isset($_GET["patient"])) {
    $invoiceId = $_GET["patient"];
    $bill = $StockOut->stockOutDisplayById($invoiceId);
    //print_r($bill);
    if($bill[0]['customer_id'] == "Cash Sales"){
        $patient = "Cash Sales";
        echo $patient;
    }
    else{
    $patient = $Patients->patientsDisplayByPId($bill[0]['customer_id']);
    $patient = json_decode($patient);
    echo $patient->name;
    }
}

// get Bill Date
if (isset($_GET["bill-date"])) {
    $invoiceId = $_GET["bill-date"];
    $bill = $StockOut->stockOutDisplayById($invoiceId);
    echo date("d-m-Y", strtotime($bill[0]['bill_date']));
}


// get Reffered Doctor
if (isset($_GET["reff-by"])) {
    $invoiceId = $_GET["reff-by"];
    $bill = $StockOut->stockOutDisplayById($invoiceId);
    echo $bill[0]['reff_by'];
}


// get products list
if (isset($_GET["products"])) {
    $invoiceId = $_GET["products"];

    // $pharmacyInvoiceData = $StockOut->stockOutDetailsById($invoiceId); // bill invoice details
    // print_r($pharmacyInvoiceData);
    $stockOutDetailsData = $StockOut->stockOutDetailsDisplayById($invoiceId);
    print_r($stockOutDetailsData);
    // $salesReturnData = $salesReturn->;

    echo '<option value="" selected enable>Select item</option>';
    for ($i = 0; $i<count($stockOutDetailsData) ; $i++) {
        echo '<option stokOutDetails-data-id="'.$stockOutDetailsData[$i]['id'].'" pharmacy-data-id="'.$stockOutDetailsData[$i]['id'].'" data-invoice="'.$invoiceId.'" data-batch="'.$stockOutDetailsData[$i]['batch_no'].'" value="'.$stockOutDetailsData[$i]['item_id'].'">'.$stockOutDetailsData[$i]['item_name'].'</option>';
    }
}

// ===========================  Item Details   =========================== 


//product id
if (isset($_GET["prod-id"])) {
    $invoice = $_GET["prod-id"];
    $item = $StockOut->stokOutDetailsDataByTwoCol($attribute1, $invoice, $attribute2, $_GET["p-id"]);
    // print_r( $item);
    echo $item[0]['product_id'];
}


// get product exp date
if (isset($_GET["exp-date"])) {
    $invoice = $_GET["exp-date"];
    $item = $StockOut->stokOutDetailsDataByTwoCol($attribute1, $invoice, $attribute2, $_GET["p-id"]);
    echo $item[0]['exp_date'];
}

// get product full unit
if (isset($_GET["unit"])) {
    $invoice = $_GET["unit"];
    $item = $StockOut->stokOutDetailsDataByTwoCol($attribute1, $invoice, $attribute2, $_GET["p-id"]);
    echo $item[0]['weightage'].$item[0]['unit'];
}

// get product item unit
if (isset($_GET["itemUnit"])) {
    $invoice = $_GET["itemUnit"];
    $item = $StockOut->stokOutDetailsDataByTwoCol($attribute1, $invoice, $attribute2, $_GET["p-id"]);
    $unit =  $item[0]['unit'];
    echo $unit;
}

// get product item weatage
if (isset($_GET["itemWeatage"])) {
    $invoice = $_GET["itemWeatage"];
    $item = $StockOut->stokOutDetailsDataByTwoCol($attribute1, $invoice, $attribute2, $_GET["p-id"]);
    $weightage =  $item[0]['weightage'];
    echo $weightage;
}

// get product mrp
if (isset($_GET["mrp"])) {
    $invoice = $_GET["mrp"];
    $item = $StockOut->stokOutDetailsDataByTwoCol($attribute1, $invoice, $attribute2, $_GET["p-id"]);
    echo $item[0]['mrp'];
}


// get product ptr
if (isset($_GET["ptr"])) {
    $invoice = $_GET["ptr"];
    $item = $StockOut->stokOutDetailsDataByTwoCol($attribute1, $invoice, $attribute2, $_GET["p-id"]);
    echo $item[0]['ptr'];
}


//get product purchase quantity
if (isset($_GET["p_qty"])) {
    $invoice = $_GET["p_qty"];
    $itemId = $_GET["p-id"];

    $item = $StockOut->stokOutDetailsDataByTwoCol($attribute1, $invoice, $attribute2, $itemId);
    foreach($item as $item){
        $itemUnitType = $item['unit'];
        if($itemUnitType == 'tab' || $itemUnitType == 'cap'){
            echo $item['loosely_count'];
        }else{
            echo $item['qty'];
        }
    }
}

// ======================== get product current qty =========================================
if (isset($_GET["qty"])) {
    $invoice = $_GET["qty"];
    $itemId = $_GET["p-id"];
    $batchNo = $_GET["batch"];
    $totalReturnQTY = 0;

    $item = $StockOut->stokOutDetailsDataByTwoCol($attribute1, $invoice, $attribute2, $itemId); // details from stock out details table
    $itemType = $item[0]['unit'];;

    $table = 'invoice_id';
    $salesReturnData = $salesReturn->selectSalesReturn($table, $invoice);
    if($salesReturnData != null){
        $id = $salesReturnData[0]['id'];
    }else{
        $id = null;
    }

    $tabel1 = 'invoice_id';
    $tabel2 = 'item_id';
    $itemChek = $salesReturn->seletReturnDetailsBy($tabel1, $invoice, $tabel2, $itemId);
    // print_r($itemChek);
    $totalReturnQTY = 0;
    for($i = 0; $i<count($itemChek); $i++){
        $totalReturnQTY = intval($totalReturnQTY) + intval($itemChek[$i]['return_qty']);
    }

    if($salesReturnData != null){
        if($itemChek != null){
            $totalReturnQTY = $totalReturnQTY;
        }else{
            $totalReturnQTY = 0;
        }

    }else{
        $totalReturnQTY = 0;
    }
    
    if($itemType == 'tab' || $itemType == 'cap'){
        $currentQty = ($item[0]['loosely_count'] - intval($totalReturnQTY)); 
    }
    else{ 
        $currentQty =  $item[0]['qty'] - intval($totalReturnQTY);  
    }

    echo $currentQty;
}//====================================================================================================

// get product discount
if (isset($_GET["disc"])) {
    $invoice = $_GET["disc"];
    $item = $StockOut->stokOutDetailsDataByTwoCol($attribute1, $invoice, $attribute2, $_GET["p-id"]);
    echo $item[0]['discount'];
}

// get product gst percentage
if (isset($_GET["gst"])) {
    $invoice = $_GET["gst"];
    $item = $StockOut->stokOutDetailsDataByTwoCol($attribute1, $invoice, $attribute2, $_GET["p-id"]);
    echo $item[0]['gst'];
}

// get product taxable amount
if (isset($_GET["taxable"])) {
    $invoice = $_GET["taxable"];
    $item = $StockOut->stokOutDetailsDataByTwoCol($attribute1, $invoice, $attribute2, $_GET["p-id"]);
    echo $item[0]['taxable'];
}



// get product amount
if (isset($_GET["amount"])) {
    $invoice = $_GET["amount"];
    $item = $StockOut->stokOutDetailsDataByTwoCol($attribute1, $invoice, $attribute2, $_GET["p-id"]);
    echo $item[0]['amount'];
}

