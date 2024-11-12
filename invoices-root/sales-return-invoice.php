<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'salesReturn.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'patients.class.php';

require_once CLASS_DIR . 'encrypt.inc.php';

// $invoiceId = $_GET['id'];
// echo $invoiceId;

//  INSTANTIATING CLASS
$SalesReturn     = new SalesReturn;
$StockOut        = new StockOut;
$Products        = new Products();
$ItemUnit        = new ItemUnit();
$PackagingUnits  = new PackagingUnits();
$Manufacturer    = new Manufacturer();
$Patients        = new Patients;
$ClinicInfo  = new HealthCare;
// echo $healthCareLogo;


if (isset($_GET['id'])) {

    $returnId = url_dec($_GET['id']);
    // echo $returnId."<br>";
    $salesReturnData  = $SalesReturn->selectSalesReturn('id', $returnId);
    // print_r($salesReturnData);
    // echo "<br>";

    foreach ($salesReturnData as $salesReturnData) {
        $invoiceId      = $salesReturnData['invoice_id'];
        // echo $invoiceId;
        $customerId     = $salesReturnData['patient_id'];
        // $reffby         = $salesReturnData['reff_by'];
        $refundAmount       = $salesReturnData['refund_amount'];
        $totalGSt       = $salesReturnData['gst_amount'];
        // $billAmout      = $salesReturnData['amount'];
        $refundMode          = $salesReturnData['refund_mode'];
        $billdate       = $salesReturnData['bill_date'];
        $returnDate     = $salesReturnData['return_date'];


        $salesReturnDetails = $SalesReturn->selectSalesReturnList('sales_return_id ', $returnId);
        // print_r($salesReturnDetails);
    }
}

if ($customerId != 'Cash Sales') {
    $patient = json_decode($Patients->patientsDisplayByPId($customerId));

    $patientName = $patient->name;
    $patientPhno = $patient->phno;
    $patientAge  = $patient->age;
    $patientEmail= $patient->email;

    // $patientElement = "<p style='margin-top: -3px; margin-bottom: 0px;'><small><b>Patient: </b>  $patientName, <b>Age:</b> $patientAge </small></p><p style='margin-top: -5px; margin-bottom: 0px;'><small><b>M:</b> $patientPhno </small></p>"";
} else {
    // $patientElement = "<p style='margin-top: -3px; margin-bottom: 0px;'><small><b>Patient: </b>  $customerId</small></p>"";
    $patientName = 'Cash Sales';
    $patientPhno = '';
    $patientAge = '';
}



$selectClinicInfo = json_decode($ClinicInfo->showHealthCare($adminId));
// print_r($selectClinicInfo->data);
$pharmacyLogo = $selectClinicInfo->data->logo;
$pharmacyName = $selectClinicInfo->data->hospital_name;



// Include FPDF library
require('../assets/plugins/pdfprint/fpdf/fpdf.php');

// Extend the FPDF class
class PDF extends FPDF
{
    private $invoiceId;
    private $refundMode;
    private $billDate;
    private $patientName;
    private $patientAge;
    private $patientPhno;
    private $totalGSt;
    private $totalMrp;
    private $billAmout;
    private $healthCareLogo;
    private $healthCareName;
    private $healthCareAddress1;
    private $healthCareAddress2;
    private $healthCareCity;
    private $healthCarePin;
    private $healthCarePhno;
    private $healthCareApntbkNo;
    private $gstinData;
    private $refundAmount;
    private $slno;
    private $isLastPage;
    private $patientEmail;

    // Constructor with parameters
    function __construct($invoiceId, $refundMode, $billDate, $patientName, $patientAge, $patientPhno, $totalGSt,  $healthCareLogo, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCarePin, $healthCarePhno, $healthCareApntbkNo, $gstinData, $refundAmount,$patientEmail) {
        parent::__construct();

        $this->invoiceId = $invoiceId;
        $this->$refundMode = $refundMode;
        $this->billDate = $billDate;
        $this->patientName = $patientName;
        $this->patientAge = $patientAge;
        $this->patientPhno = $patientPhno;
        $this->totalGSt = $totalGSt;
        // $this->totalMrp = $totalMrp;
        // $this->billAmout = $billAmout;
        $this->healthCareLogo = $healthCareLogo;
        $this->healthCareName = $healthCareName;
        $this->healthCareAddress1 = $healthCareAddress1;
        $this->healthCareAddress2 = $healthCareAddress2;
        $this->healthCareCity = $healthCareCity;
        $this->healthCarePin = $healthCarePin;
        $this->healthCarePhno = $healthCarePhno;
        $this->healthCareApntbkNo = $healthCareApntbkNo;
        $this->gstinData = $gstinData;
        $this->refundAmount = $refundAmount;
        $this->patientEmail = $patientEmail;
    }

    function Header() {
        global $healthCareLogo, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCarePin, $healthCarePhno, $healthCareApntbkNo, $invoiceId, $refundMode, $billdate, $patientName, $patientAge, $patientPhno, $gstinData;

        if ($this->PageNo() == 1) {  ///this line only show the header first page

            //.. healthCareLogo...///
            $logoX = 10;
            $logoY = 6;
            $logoWidth = 20;
            $logoHeight = 20;
            if (!empty($this->healthCareLogo)) {
                $this->Image($this->healthCareLogo, $logoX, $logoY, $logoWidth, $logoHeight);
            }

            ///....Title (Healthcare Name)...///
            $this->SetFont('Arial', 'B', 16);
            $this->SetXY($logoX + $logoWidth + 3, $logoY); // Position next to the logo
            $healthCareName = strtoupper($this->healthCareName);
            $this->Cell(150, 8, $healthCareName, 0, 1, 'L'); // Centered text

            // Address
            $this->SetFont('Arial', '', 9);
            $address = "$healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCarePin\nM: $healthCarePhno, $healthCareApntbkNo\nGST ID : $gstinData";
            
            $this->SetXY($logoX + $logoWidth + 3, $logoY + 8); // Position below the title
            $this->MultiCell(120, 4.2, $address, 0, 'L');

            ///...Invoice Info
            $this->SetY(11); // Reset Y position
            $this->SetX(-51); // Align to the right
            // Draw vertical line
            // $this->SetLineWidth(0.4);
            // $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY() -2, $this->GetX(), $this->GetY() + 15);
            $this->SetFont('Arial', 'B', 10);
            $this->cell(80, -2, ' Invoice:', 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->MultiCell(80, 4.2, " \n #$invoiceId\n Payment: $refundMode\n Date: $this->billDate", 0, 'L');
            $this->Ln(1.6);
            // $this->SetDrawColor(108, 117, 125);
            // $this->SetLineWidth(0.4);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(9);
        }
    }

    // Page footer
    function Footer() {
        if ($this->isLastPage) { /// this line only show the footer last page 

            $pageHeight = $this->GetPageHeight();
            $middleY = ($pageHeight / 2)-2;
            $this->SetY($middleY);
            // $this->SetLineWidth(0.4);
            // $this->SetDrawColor(0, 0, 0);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            // $this->Ln(1);

           // Set the font for the footer content
           $this->SetFont('Arial', '', 8);

            // Patient Info
            $this->SetY($this->GetY() + 1); // Add some padding
            $startX = 10;
            $currentY = $this->GetY();

            $this->SetX($startX);
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, 'Patient: ', 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(30, 5, $this->patientName, 0, 'L');
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, 'Contact: ', 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(30, 5, $this->patientPhno, 0, 1, 'L');

             // Draw vertical line
            $this->SetY(147.5); // Reset Y position
            $this->SetX(96); // Align to the right
            // $this->SetLineWidth(0.4);
            // $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 10);

            //..Amount Calculation
            $startX = 140;
            $this->SetY($currentY); // Reset Y position to top of the section
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, 'Total Refund', 0, 0, 'R');
            // $this->SetFont('Arial', '', 8);
            $this->Cell(31.4, 5, ': ' . $this->refundAmount, 0, 1, 'R');
            
            $this->Ln(6.2);
            // $this->SetLineWidth(0.4);
            // $this->SetDrawColor(108, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(2.5);

            $phoneIcon = '../assets/plugins/pdfprint/icon/phone.png';
            $emailIcon = '../assets/plugins/pdfprint/icon/email.png';
            $this->SetFont('Arial', '', 8);
            $startX = $this->GetX();
            $startY = $this->GetY();
            $this->Image($phoneIcon, $startX, $startY - 2, 4); // Adjust position and size as needed
            if(!empty($this->patientEmail)){
            $this->Image($emailIcon, $startX + 38, $startY -2, 3.5);
            }
            $address = " " . $this->healthCarePhno . "," . $this->healthCareApntbkNo . ",          ".$this->patientEmail.",  Print Time: " . date('Y-m-d H:i:s');
            $textX = $startX + 3;
            if (empty($this->patientEmail)) {
                $address = " " . $this->healthCarePhno . "," . $this->healthCareApntbkNo . ",  Print Time: " . date('Y-m-d H:i:s');
            }
            $this->SetXY($textX, $startY);
            // Output the address text
            $this->SetFont('Arial', 'B', 8);
            $this->MultiCell(0, 0, $address, 0, 'L');
        }
    }

    function AddContentPage($salesReturnDetails, $Products, $Manufacturer, $ItemUnit, $PackagingUnits, $StockOut) {
        $this->AddPage();
    
        ///....add paid badge...///
        if ($this->$refundMode != 'Credit') {
            $imageX = 80; // X position with left space
            $imageY = 70;
            $imageWidth = 45; // Adjusted width with spaces
            $imageHeight = 45; // Height of the image
            $this->Image('../assets/images/refund-seal.png', $imageX, $imageY, $imageWidth, $imageHeight);
        }///....end page badge...///
    
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(12, -11, 'SL.', 0, 0, 'L');
        $this->Cell(32, -11, 'Name', 0, 0, 'L');
        $this->Cell(15, -11, 'Manuf.', 0, 0, 'L');
        $this->Cell(20, -11, 'Batch', 0, 0, 'L');
        $this->Cell(13, -11, 'Exp.', 0, 0, 'L');
        $this->Cell(14, -11, 'Unit.', 0, 0, 'L');
        $this->Cell(14, -11, 'Buy Qty', 0, 0, 'L');
        $this->Cell(13, -11, 'Ret.Qty', 0, 0, 'L');
        $this->Cell(13, -11, 'Rate', 0, 0, 'L');
        $this->Cell(15, -11, 'Disc (%)', 0, 0, 'L');
        $this->Cell(14, -11, 'GST(%)', 0, 0, 'L');
        $this->Cell(16, -11, 'Amount', 0, 1, 'R');
        $this->Ln(8.1);
        // $this->SetDrawColor(108, 117, 125);
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // Draw line
        $this->Ln(2);

        $slno = 1;
        $rowsPerPage = 7; // Maximum rows per page
        $rowCounter = 0;
        foreach($salesReturnDetails as $detail){
            
            $checkTable = json_decode($Products->productExistanceCheck($detail['product_id']));

                    if ($checkTable->status == 1) {
                        $table = 'products';
                    } else {
                        $table = 'product_request';
                    }

                    $productResponse = json_decode($Products->showProductsByIdOnTableName($detail['product_id'], $table));

                    $product = $productResponse->data;
                    $packQty = $product->unit_quantity;

                    if (isset($product->manufacturer_id)) {
                        $manuf = json_decode($Manufacturer->manufacturerShortName($product->manufacturer_id));

                        $manufacturerName = $manuf->status == 1 ? $manuf->data : '';
                    } else {
                        $manufacturerName = '';
                    }

                    $itemunit = $ItemUnit->itemUnitName($product->unit);
                    $packUnit = $PackagingUnits->packagingTypeName($product->packaging_type);

                    $weatage = "$itemunit of $packUnit";

                    $col1 = 'invoice_id';
                    $col2 = 'item_id';
                    $stockOutData = $StockOut->stokOutDetailsDataByTwoCol($col1, $detail['invoice_id'], $col2, $detail['item_id']);

                    if($stockOutData[0]['loosely_count'] != 0){
                        $purchasedQty = $stockOutData[0]['loosely_count'];
                    }else{
                        $purchasedQty = $stockOutData[0]['qty'];
                    }
                    $totalMrp = floatval($totalMrp) + floatval($detail['mrp']);

                    if ($rowCounter >= $rowsPerPage) {

                        ///....show first page total amount...////
                        $this->SetFont('Arial', 'B', 10);
                        $this->Cell(170, 10, 'Total Amount:', 0, 0, 'R');
                        $this->SetFont('Arial', '', 10);
                        $this->Cell(20, 10, '' .$amount, 0, 1, 'R');
        
                        // Add new page if rowCounter reaches rowsPerPage
                        $this->AddPage();
                        $this->Ln(10);
                        $this->SetFont('Arial', '', 10);
        
                        $rowCounter = 0; // Reset row counter for new page
        
                         ///....add paid badge...///
                       if($this->paidAmount){
                           $imageX = 50; // X position with left space
                           $imageY = 70;
                           $imageWidth = 100; // Adjusted width with spaces
                           $imageHeight = 60; // Height of the image
                          $this->Image('../assets/images/paid-seal.png', $imageX, $imageY, $imageWidth, $imageHeight);
                        }///....end page badge...///
                    }

        $this->SetFont('Arial', '', 8);  
        
        if ($slno > 1) {
            $this->SetDrawColor(183, 182, 182); // Set color for the dotted line
            $dotWidth = 0.5; // Width of each dot
            $spaceWidth = 0.2; // Space between each dot
            $lineLength = 200; // Length of the line
            $x = 10; // Starting X position
            $y = $this->GetY(); // Current Y position
            
            // Draw the dotted line
            $drawDot = true; // Initialize to draw dot
            while ($x <= $lineLength) {
                if ($drawDot) {
                    $this->Line($x, $y, $x + $dotWidth, $y); // Draw dot
                }
                $x += $dotWidth + $spaceWidth; // Move X position to next dot
                $drawDot = !$drawDot; // Switch drawing state for next dot
            }
        }
        $this->Cell(12, 5, $slno, 0, 0, 'L');

        $productName = $product->name;
        if (strlen($productName) > 15) {
            $productName = substr($productName, 0, 15) . "" . substr($productName, 15);
        }
        $x = $this->GetX();
        $y = $this->GetY(2);
        // $this->Ln(2);
        $this->MultiCell(32, 4, $productName, 0, 'L');
        $this->SetXY($x + 32, $y-2);
        // $this->Cell(28, 10, $product->name, 0, 0, 'L');
        $this->Cell(15, 8, $manufacturerName, 0, 0, 'L');
        $this->Cell(20, 8, $detail['batch_no'], 0, 0, 'L');
        $this->Cell(13, 8, $detail['exp'], 0, 0, 'L');
        $this->Cell(16, 8, $detail['weatage'], 0, 0, 'L');
        $this->Cell(12, 8, $purchasedQty, 0, 0, 'L');
        $this->Cell(13, 8, $detail['return_qty'], 0, 0, 'L');
        $this->Cell(14, 8, $detail['ptr'], 0, 0, 'L');
        $this->Cell(15, 8, $detail['disc'], 0, 0, 'L');
        $this->Cell(15, 8, $detail['gst'], 0, 0, 'L');
        $this->Cell(14, 8, $detail['refund_amount'], 0, 1, 'R');

        $amount  = $amount + $detail['amount'];
        $slno++;
        $rowCounter++;
        }
    
        
    }
    

    //....footer set last page...//
    function AddLastPage() {
        $this->isLastPage = true;
    }//footer

}

// if (isset($_POST['printPDF'])) {

    $healthCare   = json_decode($HealthCare->showHealthCare($ADMINID));
    if ($healthCare->status === 1 ) {
        $healthCare = $healthCare->data;
        $healthCareLogo      = $healthCare->logo;
        $healthCareLogo      = empty($healthCareLogo) ? SITE_IMG_PATH.'logo-p.png' : URL.$healthCareLogo;
        $logoFilename = basename($healthCareLogo);
        $healthCareLogo = empty($healthCareLogo) ? SITE_IMG_PATH.'logo-p.png' : realpath('../assets/images/orgs/'.$logoFilename.'');
    }

    $stockOut  = $StockOut->stockOutDisplayById($invoiceId);
    foreach ($stockOut as $stockOut) {
     $billDate       = $stockOut['bill_date'];
    }

    // exit;
    $pdf = new PDF($invoiceId, $refundMode, $billDate, $patientName, $patientAge, $patientPhno, $totalGSt,  $healthCareLogo, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCarePin, $healthCarePhno, $healthCareApntbkNo, $gstinData, $refundAmount,$patientEmail);

    $pdf->AliasNbPages();
    $pdf->AddContentPage($salesReturnDetails, $Products, $Manufacturer, $ItemUnit, $PackagingUnits,$StockOut);
    $pdf->AddLastPage();
    ob_clean();
    $pdf->Output();
    exit;
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Lab Test Bill</title>
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.css">
    <!-- <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/sell-return-bill.css"> -->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/receipts.css">

</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?php if ($refundMode != 'Credit') {
                                    echo "paid-bg";
                                } ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <!-- <img class="float-end" style="height: 55px; width: 58px;" src="<?= LOCAL_DIR . $pharmacyLogo ?>" -->
                        <img class="float-end" style="height: 55px; width: 58px; position: absolute;" src="<?= $healthCareLogo ?>"
                            alt="Medicy">
                    </div>
                    <div class="col-sm-8">
                        <h4 class="text-start my-0"><?php echo $healthCareName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareAddress1 . ', ' . $healthCareAddress2 . ', ' . $healthCareCity . ', ' . $healthCarePin; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -6px; margin-bottom: 0px;">
                            <small><?php echo 'M: ' . $healthCarePhno . ', ' . $healthCareApntbkNo; ?></small>
                        </p>
                        <p class="m-0" style="font-size: 0.850em;"><small><b>GST ID :</b></small><?php echo $gstinData?>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-dark">
                        <p class="my-0"><b>Invoice</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id:
                                #<?php echo $invoiceId; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Payment:
                                <?php echo $refundMode; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Date: <?php echo $billdate; ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <!-- <hr class="my-0" style="height:0px; background: #000000; border: #000000;"> -->
            <!-- <div class="row my-0">
                <div class="col-sm-6 ms-4 my-0">
                    <p class="text-start" style="margin-top: -3px; margin-bottom: 0px;"><small><b>Refered By:</b>
                            <?php echo $reffby; ?></small></p>
                </div>
            </div> -->
            <hr class="my-0" style="height:1px;opacity:1;">

            <table class="table">
                <thead>
                    <tr>
                        <th class="pt-1 pb-1" scope="col"><small>SL.</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Name</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Manuf.</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Batch</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Exp.</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Unit</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Buy Qty</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Ret.Qty</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Rate</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Disc(%)</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>GST(%)</small></th>
                        <th class="pt-1 pb-1" scope="col"><small>Refund</small></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                $slno = 0;
                $totalMrp = 0;
                $subTotal = floatval(00.00);
                foreach ($salesReturnDetails as $index => $detail) {
                    // print_r($detail);
                    //=========================
                    $checkTable = json_decode($Products->productExistanceCheck($detail['product_id']));

                    if ($checkTable->status == 1) {
                        $table = 'products';
                    } else {
                        $table = 'product_request';
                    }
                    //=========================

                    $productResponse = json_decode($Products->showProductsByIdOnTableName($detail['product_id'], $table));

                    $product = $productResponse->data;
                    // print_r($product);

                    $packQty = $product->unit_quantity;

                    if (isset($product->manufacturer_id)) {
                        $manuf = json_decode($Manufacturer->manufacturerShortName($product->manufacturer_id));

                        $manufacturerName = $manuf->status == 1 ? $manuf->data : '';
                    } else {
                        $manufacturerName = '';
                    }


                    $itemunit = $ItemUnit->itemUnitName($product->unit);
                    $packUnit = $PackagingUnits->packagingTypeName($product->packaging_type);

                    $weatage = "$itemunit of $packUnit";

                    $slno++;
                    // if ($slno > 1) {
                    //     echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                    // }

                    $col1 = 'invoice_id';
                    $col2 = 'item_id';
                    $stockOutData = $StockOut->stokOutDetailsDataByTwoCol($col1, $detail['invoice_id'], $col2, $detail['item_id']);
                    // print_r($stockOutData);

                    if($stockOutData[0]['loosely_count'] != 0){
                        $purchasedQty = $stockOutData[0]['loosely_count'];
                    }else{
                        $purchasedQty = $stockOutData[0]['qty'];
                    }

                    // ================== TOTAL MRP CALCULATION AREA =======================
                    $totalMrp = floatval($totalMrp) + floatval($detail['mrp']);

                    $isLastRow = $index === count($salesReturnDetails) - 1;
                    // Add border style only if it's not the last row
                    $borderStyle = $isLastRow ? 'border-bottom: transparent;' : 'border-bottom: #dfdfdf;height:24px;';

                   echo ' <tr style="'.$borderStyle.'">
                        <th scope="row" class="pt-1 pb-1"><small>' . $slno . '</small> </th>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $product->name . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $manufacturerName . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['batch_no'] . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['exp'] . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['weatage'] . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $purchasedQty . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['return_qty'] . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['ptr'] . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['disc'] . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['gst'] . '</small></td>
                        <td class="pt-1 pb-1" ><small style="font-size: 0.750em;">' . $detail['refund_amount'] . '</small></td>
                    </tr>';
                }
                ?>
                </tbody>
            </table>


            <div class="footer">
                <hr calss="my-0" style="height: 1px; margin-bottom:0;opacity:1;">
                <!-- table total calculation -->

                <div class="row my-0">
                    <div class="col-5">
                        <div class="row mt-2">
                            <div class="col-2">
                                <b><small>Patient </small></b><br>
                                <!-- <b><small>Age</small></b><br> -->
                                <b><small>Contact</small></b>
                            </div>
                            <div class="col-9">
                                <p class="text-start mb-0"><small><?= ' :  ' . $patientName; ?></small></p>
                                <!-- <p class="text-start mb-0"><small><?= ' :  ' . $patientAge; ?></small></p> -->
                                <p class="text-start"><small><?= ':  ' . $patientPhno; ?></small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-7 border-start border-dark">
                        <div class="row mt-3">
                            <div class="col-sm-10">
                                <p class="text-end mb-0"><b>Total Refund :</b></p>
                            </div>
                            <div class="col-sm-2">
                                <p class="mb-0 me-3"><b><?= $refundAmount; ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr style="height: 1px; margin-top:0;opacity: 1;">
            </div>
        </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button>
        <!-- <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button> -->
        <form method="post">
            <button class="btn btn-primary shadow mx-2" type="submit" name="printPDF">Print PDF</button>
        </form>
    </div>
    </div>
</body>
<script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>

</html>