<?php
##########################################################################################################
#                                                                                                        #
#                                      Sales Return Edit Page                                            #
#                                                                                                        #
##########################################################################################################

require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR."stockOut.class.php";
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR."salesReturn.class.php";

$StockOut   = new StockOut();
$Products   = new Products();
$Patients   = new Patients();
$salesReturn = new SalesReturn();

$tabel = 'id';
$col1 = 'invoice_id';
$col2 = 'item_id';

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
    echo $patient[0]['name'];
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
    echo $bill[0]["reff_by"];
}

// get products list
if (isset($_GET["products"])) {

    $invoiceId = $_GET["products"];
    $salesRetundid = $_GET["salesreturnID"];
    // echo "$invoiceId<br>$salesRetundid";

    $table = 'sales_return_id';
    $items = $salesReturn->selectSalesReturnList($table, $salesRetundid);
    echo '<option value="" selected disabled>Select item</option>';
    foreach ($items as $item) {
        // print_r($items);
        $product = $Products->showProductsById($item['product_id']);
        // print_r($product); echo "<br><br>";
        echo '<option data-invoice="'.$invoiceId.'" sales-return-id="'.$item['sales_return_id'].'" value="'.$item['product_id'].'" returned-item-id="'.$item['id'].'" current-stock-item-id="'.$item['item_id'].'">'.$product[0]['name'].'</option>';
    }
}



// CHECK DATA
// if (isset($_GET["products"])) {
//     $invoiceId = $_GET["products"];
//     $salesRtnId = $_GET["salesreturnID"];
//     $bill = $StockOut->stockOutDisplayById($invoiceId);
//     echo "$invoiceId<br>$salesRtnId";
// }
// ===========================  Item Details   =========================== 

// get product exp date
if (isset($_GET["exp-date"])) {
    $exp = $_GET["exp-date"];
    $item = $salesReturn->selectSalesReturnList($tabel, $exp);
    // print_r($item);
    echo $item[0]['exp'];
}


///////////////////////////// get product full unit////////////////////////
if (isset($_GET["unit"])) {
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["unit"]);
    echo $item[0]['weatage'];
}

// get product full unit
if (isset($_GET["unitType"])) {
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["unitType"]);
    $unit = $item[0]['weatage'];
    $unitType = preg_replace('/[0-9]/','', $unit);
    echo $unitType;   
}

// get product full unit
if (isset($_GET["itemWeatage"])) {
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["itemWeatage"]);
    $unit = $item[0]['weatage'];
    $itemWeatage = preg_replace('/[a-z]/','', $unit);
    echo $itemWeatage;
}//=======================================================================

//batch number
if (isset($_GET["batchNo"])) {
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["batchNo"]);
    $batch = $item[0]['batch_no'];
    echo $batch;
}

// get product mrp
if (isset($_GET["mrp"])) {
    $invoice = $_GET["mrp"];
    $item = $StockOut->stokOutDetailsDataByTwoCol($col1, $invoice, $col2, $_GET["p-id"]);
    echo $item[0]['mrp'];
}



// get product ptr
if (isset($_GET["ptr"])) {
    $invoice = $_GET["ptr"];
    $item = $StockOut->stokOutDetailsDataByTwoCol($col1, $invoice, $col2, $_GET["p-id"]);
    echo $item[0]['ptr'];
}



// get product purchase qty
if (isset($_GET["pqty"])) {
    $invoice = $_GET["pqty"];
    //$item = $StockOut->stockOutSelect($invoice, $_GET["p-id"], $_GET["batch"]);
    $item = $StockOut->stokOutDetailsDataByTwoCol($col1, $invoice, $col2, $_GET["p-id"]);
    foreach($item as $item){
        if($item['loosely_count'] > 0){
            $purchaseQty = $item['loosely_count'];
        }else{
            $purchaseQty = $item['qty'];
        }
    }
    echo $purchaseQty;
}



// get product current qty
if (isset($_GET["cqty"])) {
    $invoice = $_GET["cqty"];
    $itemId = $_GET['p-id'];

    $purchaseItem = $StockOut->stokOutDetailsDataByTwoCol($col1, $invoice, $col2, $_GET["p-id"]);
    foreach($purchaseItem as $item){
        if($item['loosely_count'] > 0){
            $purchaseQty = $item['loosely_count'];
        }else{
            $purchaseQty = $item['qty'];
        }
    }


    $table1 = 'invoice_id';
    $table2 = 'item_id';
    $returnedItem = $salesReturn->seletReturnDetailsBy($table1, $invoice, $table2, $itemId);
    // print_r($returnedItem);
    $returnQty = 0;
    if(empty($returnedItem) != true){
        foreach($returnedItem as $item){
           $returnQty = intval($returnQty) + intval($item['return_qty']);
        }
    }else{
        $returnQty = 0;
    }
    echo intval($purchaseQty) - intval($returnQty);
}



// get product return qty
if (isset($_GET["rtnqty"])) {
    $invoice = $_GET["rtnqty"];
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["rtnqty"]);
    //print_r($item);
    if(!empty($item)){
        echo $item[0]['return_qty'];
    }else{
        echo 0;
    }
    // echo $invoice;
}

// get product discount
if (isset($_GET["disc"])) {
    $invoice = $_GET["disc"];
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["disc"]);
    echo $item[0]['disc'];
}

// get product gst percentage
if (isset($_GET["gst"])) {
    $invoice = $_GET["gst"];

    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["gst"]);
    echo $item[0]['gst'];
}

// get product taxable
if (isset($_GET["taxable"])) {
    $invoice = $_GET["taxable"];
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["taxable"]);
    echo $item[0]['taxable'];
}

// get product amount
if (isset($_GET["amount"])) {
    $invoice = $_GET["amount"];
    $item = $salesReturn->selectSalesReturnList($tabel, $_GET["amount"]);
    echo $item[0]['refund_amount'];
}

?>


