<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';

require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

// $billId = url_dec($_GET['id']);

//  INSTANTIATING CLASS
$LabBilling         = new LabBilling();
$LabBillDetails     = new LabBillDetails();
$Pathology          = new Pathology();
$Doctors            = new Doctors();
$Patients           = new Patients();
$Utility            = new Utility;

if (isset($_GET['id']) || isset($_GET['billId'])):

    if (isset($_GET['id'])) {
        $billId = url_dec($_GET['id']);
    }

    if (isset($_GET['billId'])) {
        $billId = url_dec($_GET['billId']);
    }

    $labBil      = json_decode($LabBilling->labBillDisplayById($billId));

    $billId         = $labBil->data->bill_id;
    $billDate       = $labBil->data->bill_date;
    $patientId      = $labBil->data->patient_id;
    $docId          = $labBil->data->refered_doctor;
    $testDate       = $labBil->data->test_date;
    $totalAmount    = $labBil->data->total_amount;
    $dicountAmount  = $labBil->data->discount;
    $afterDiscount  = $labBil->data->total_after_discount;
    $cgst           = $labBil->data->cgst;
    $sgst           = $labBil->data->sgst;
    $paidAmount     = $labBil->data->paid_amount;
    $dueAmount      = $labBil->data->due_amount;
    $status         = $labBil->data->status;
    $addedBy        = $labBil->data->added_by;
    $BillOn         = $labBil->data->added_on;

    $patient = json_decode($Patients->patientsDisplayByPId($patientId));
    $patientName    = isset($patient->name) ? $patient->name : 'N/A';
    $patientPhno    = isset($patient->phno) ? $patient->phno : 'N/A';
    $patientAge     = isset($patient->age)  ? $patient->age  : 'N/A';
    $patientGender  = isset($patient->gender) ? $patient->gender : 'N/A';


    if (is_numeric($docId)) {
        $showDoctor = $Doctors->showDoctorNameById($docId);
        $showDoctor = json_decode($showDoctor);
        if ($showDoctor->status == 1) {
                $doctorName = $showDoctor->data->doctor_name;
                $doctorReg = $showDoctor->data->doctor_reg_no;
        }
    } else {
        $doctorName = $docId;
        $doctorReg  = NULL;
    }

    /*
        $labBillDetailsData = json_decode($LabBillDetails->billDetailsById($billId));

        if ($labBillDetailsData->status) {
            $labBillDetailsData = $labBillDetailsData->data;

            // $discArray = [];
            // $amountArray = [];
            // $amountAfterDisc = [];

            // foreach ($labBillDetailsData as $detailsData) {
            //     array_push($discArray, $detailsData->percentage_of_discount_on_test);
            //     array_push($amountArray, $detailsData->test_price);
            //     array_push($amountAfterDisc, $detailsData->price_after_discount);
            // }
        }
    */

endif;

// Include FPDF library
require('../assets/plugins/pdfprint/fpdf/fpdf.php');

class PDF extends FPDF
{

    var $isLastPage = false;

    private $billId;
    // private $billingDate;
    private $billDate;
    private $subTotal;
    private $discountAmount;
    private $dueAmount;
    private $paidAmount;
    private $LabBillDetails;
    private $SubTests;
    private $healthCareLogo;
    private $healthCarePhno, $healthCareApntbkNo, $healthCareEmail;

    // Constructor with parameters
    function __construct($billId, $billDate, $subTotal, $discountAmount, $dueAmount, $paidAmount, $LabBillDetails, $SubTests, $healthCareLogo,$healthCarePhno, $healthCareApntbkNo,  $healthCareEmail)
    {
        parent::__construct();
        $this->billId = $billId;
        // $this->billingDate = $billingDate;
        $this->billDate = $billDate;
        // $this->subTotal = $subTotal;
        $this->discountAmount = $discountAmount;
        $this->dueAmount = $dueAmount;
        $this->paidAmount = $paidAmount;
        $this->LabBillDetails = $LabBillDetails;
        $this->SubTests = $SubTests;
        $this->healthCareLogo = $healthCareLogo;
        $this->healthCarePhno = $healthCarePhno;
        $this->healthCareApntbkNo = $healthCareApntbkNo;
        $this->healthCareEmail = $healthCareEmail;
    }

    function Header()
    {
        global $healthCareLogo, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCarePin, $healthCarePhno, $healthCareApntbkNo, $billId, $billingDate, $billDate, $patientName, $patientAge, $patientPhno, $testDate, $doctorName, $doctorReg;

        if ($this->PageNo() == 1) {  ///this line only show the header first page

            //.. healthCareLogo...///
            $logoX = 10;
            $logoY = 8;
            $logoWidth = 20;
            $logoHeight = 20;
            if (!empty($this->healthCareLogo)) {
                $this->Image($this->healthCareLogo, $logoX, $logoY, $logoWidth, $logoHeight);
            }

            ///....Title (Healthcare Name)...///
            $this->SetFont('Arial', 'B', 16);
            $this->SetXY($logoX + $logoWidth + 5, $logoY); // Position next to the logo
            $this->Cell(90, 8, $healthCareName, 0, 1, 'L'); // Centered text

            // Address
            $this->SetFont('Arial', '', 9);
            $address = "$healthCareAddress1, $healthCareAddress2\n$healthCareCity, $healthCarePin\nM: $healthCarePhno, $healthCareApntbkNo";
            

            $this->SetXY($logoX + $logoWidth + 5, $logoY + 8); // Position below the title
            $this->MultiCell(90, 4.2, $address, 0, 'L');

            ///...Invoice Info
            $this->SetY(12); // Reset Y position
            $this->SetX(-49.9); // Align to the right
            // Draw vertical line
            // $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY()-2, $this->GetX(), $this->GetY() + 14);

            $this->SetFont('Arial', 'B', 10);
            $this->cell(80, -1, ' Invoice:', 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->MultiCell(80, 4.2, " \n #$billId\n Bill Date : ". (isset($billingDate) && !empty($billingDate) ? formatDateTime( $billingDate) : formatDateTime( $billDate)), 0, 'L');

            // Patient Info
            $this->Ln(5.2);
            // $this->SetDrawColor(108, 117, 125);
            $this->SetFillColor(236, 236, 236);
            $this->Rect(10, $this->GetY(), 190.1, 9, 'F');
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            // Set font for "Patient Info:"
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(20, 5, 'Name:', 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(30, 5, $patientName, 0, 1, 'L');
            $this->SetY($this->GetY()-5);
            $this->SetX(60);
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(8, 5, 'Age : ', 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(0, 5, $patientAge, 0, 1, 'L');
            $this->SetY($this->GetY());
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(5, 4, 'M:', 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(0, 4, $patientPhno, 0, 1, 'L');
            $this->SetY($this->GetY()-4);
            $this->SetX(35);
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(18, 4, 'Test Date : ', 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(0, 4, formatDateTime($testDate), 0, 1, 'L');
            // Doctor Info
            $this->SetY($this->GetY()-8.5); // Move Y position up to align with patient info
            $this->SetX(-84); // Align to the right
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(38, 4, "Referred Doctor : ", 0, 0, 'R');
            $this->SetFont('Arial', '', 9);
            $this->Cell(30, 4, $doctorName, 0, 1, 'L');
            $this->SetY($this->GetY()+1); // Move Y position up to align with patient info
            $this->SetX(-80); 
            if($doctorReg != NULL){
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(34, 3, "Registration No : ", 0, 0, 'R');
            $this->SetFont('Arial', '', 9);
            $this->Cell(0, 3, $doctorReg, 0, 1, 'L');
            }else{
                $this->SetFont('Arial', 'B', 9);
                $this->Cell(34, 3, "Registration No : ", 0, 0, 'R');
                $this->SetFont('Arial', '', 9);
                $this->Cell(0, 3, 'N/A', 0, 1, 'L');
            }
            $this->Ln(0.8);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(8);
        }
    }


    // Page footer
    function Footer()
    {
        if ($this->isLastPage) { /// this line only show the footer last page 

            $pageHeight = $this->GetPageHeight();
            $middleY = ($pageHeight / 2)-30.1;
            $this->SetY($middleY);
            // $this->SetLineWidth(0.4);
            $this->SetDrawColor(0, 0, 0);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(1);

            $this->SetFont('Arial', 'B', 9);
            $this->Cell(111, 5, 'Total Amount :', 0, 0, 'R');
            $this->SetFont('Arial', '', 9);
            $this->Cell(80, 5, ' ' . number_format(floatval($this->subTotal), 2), 0, 1, 'R');

            if (isset($_GET['billId'])) {
                $this->SetFont('Arial', 'B', 9);
                $this->Cell(111, 5, 'Less Amount :', 0, 0, 'R');
                $this->SetFont('Arial', '', 9);
                $this->Cell(80, 5, ' ' . number_format(floatval($this->discountAmount), 2), 0, 1, 'R');
            }

            if ($this->dueAmount != NULL && $this->dueAmount > 0) {
                $this->SetFont('Arial', 'B', 9);
                $this->Cell(110, 5, 'Due Amount :', 0, 0, 'R');
                $this->SetFont('Arial', '', 9);
                $this->Cell(81, 5, ' ' . number_format(floatval($this->dueAmount), 2), 0, 1, 'R');
            }

            $this->SetFont('Arial', 'B', 9);
            $this->Cell(111, 5, 'Paid Amount :', 0, 0, 'R');
            // $this->SetFont('Arial', '', 9);
            $this->Cell(80, 5, ' ' . number_format(floatval($this->paidAmount), 2), 0, 1, 'R');

            $this->Ln(1);
            // $this->SetDrawColor(0, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(2.5);

            $phoneIcon = '../assets/plugins/pdfprint/icon/phone.png';
            $emailIcon = '../assets/plugins/pdfprint/icon/email.png';
            $this->SetFont('Arial', '', 8);
            $startX = $this->GetX();
            $startY = $this->GetY();
            $this->Image($phoneIcon, $startX, $startY - 2, 4); // Adjust position and size as needed
            if(!empty($this->healthCareEmail)){
            $this->Image($emailIcon, $startX + 38, $startY -2, 3.5);
            }
            $address = " " . $this->healthCarePhno . "," . $this->healthCareApntbkNo . ",          ".$this->healthCareEmail.",  Print Time: " . date('Y-m-d H:i:s');
            $textX = $startX + 3;
            if (empty($this->healthCareEmail)) {
                $address = " " . $this->healthCarePhno . "," . $this->healthCareApntbkNo . ",  Print Time: " . date('Y-m-d H:i:s');
            }
            $this->SetXY($textX, $startY);
            // Output the address text
            $this->SetFont('Arial', 'B', 8);
            $this->MultiCell(0, 0, $address, 0, 'L');
        }
    }

    function AddContentPage()
    {
        $this->AddPage();
        ///....add paid badge...///
        if( $this->paidAmount){
            $imageX = 70; // X position with left space
            $imageY = 55;
            $imageWidth = 80; // Adjusted width with spaces
            $imageHeight = 45; // Height of the image
           $this->Image('../assets/images/paid-seal.png', $imageX, $imageY, $imageWidth, $imageHeight);
       }///....end page badge...///

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, -10, 'SL. NO.', 0, 0, 'L');
        $this->Cell(80, -10, 'Description', 0, 0, 'L');
        $this->Cell(30, -10, 'Price', 0, 0, 'L');
        $this->Cell(31, -10, 'Disc (%)', 0, 0, 'L');
        $this->Cell(30, -10, 'Amount', 0, 1, 'R');
        $this->Ln(8);
        // $this->SetDrawColor(108, 117, 125);
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // Draw line

        ///...Bill Details...///
        $this->SetFont('Arial', '', 9);
        $slno = 1;
        $rowsPerPage = 8; // Maximum rows per page
        $rowCounter = 0;
        $amount=0;
        $billDetails = json_decode($this->LabBillDetails->billDetailsById($this->billId))->data;

        foreach ($billDetails as $rowDetails) {
            if ($rowCounter >= $rowsPerPage) {

                ///....show first page total amount...////
                $this->Ln(-2);
                $this->SetFont('Arial', 'B', 8);
                $this->Cell(170, 10, 'Total Amount :', 0, 0, 'R');
                $this->SetFont('Arial', '', 9);
                $this->Cell(21, 10, '' .$amount, 0, 1, 'R');

                // Add new page if rowCounter reaches rowsPerPage
                $this->AddPage();
                $this->Ln(10);
                $this->SetFont('Arial', '', 8);

                $rowCounter = 0; // Reset row counter for new page

                 ///....add paid badge...///
               if($this->paidAmount){
                   $imageX = 70; // X position with left space
                   $imageY = 55;
                   $imageWidth = 80; // Adjusted width with spaces
                   $imageHeight = 45; // Height of the image
                  $this->Image('../assets/images/paid-seal.png', $imageX, $imageY, $imageWidth, $imageHeight);
                }///....end page badge...///
            }

            $subTestId = $rowDetails->test_id;
            $testAmount = $rowDetails->price_after_discount;
            $testDisc = $rowDetails->percentage_of_discount_on_test;

            if ($subTestId != '') {
                // print_r($this->SubTests->showTestById($subTestId));
                // exit;
                $showSubTest = json_decode($this->SubTests->showTestById($subTestId));
                $testName = $showSubTest->data->name;
                $testPrice = $showSubTest->data->price;

                //...start dotted row line...//
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
                }//...end dotted row...///
                $this->SetFont('Arial', '', 9);
                $this->Cell(20, 8, $slno, 0, 0, 'L');
                $this->Cell(80, 8, $testName, 0, 0, 'L');
                $this->Cell(30, 8, $testPrice, 0, 0, 'L');
                $this->Cell(31, 8, $testDisc, 0, 0, 'L');
                $this->Cell(30, 8, $testAmount, 0, 1, 'R');
                $amount  = $amount + $testAmount;
                $slno++;
                $this->subTotal += $testAmount;
                $rowCounter++;
            }
        }
    }

    //....footer set last page...//
    function AddLastPage()
    {
        $this->isLastPage = true;
    } //footer end..///

}

// if (isset($_POST['printPDF'])) {

$healthCare   = json_decode($HealthCare->showHealthCare($ADMINID));
if ($healthCare->status === 1) {
    $healthCare = $healthCare->data;
    $healthCareLogo      = $healthCare->logo;
    $healthCareLogo      = empty($healthCareLogo) ? SITE_IMG_PATH . 'logo-p.png' : URL . $healthCareLogo;
    // print($healthCareLogo);
    $logoFilename = basename($healthCareLogo);
    // print($logoFilename);
    // $healthCareLogo = empty($healthCareLogo) ? SITE_IMG_PATH.'logo-p.png' : URL .  rawurlencode($healthCareLogo);
    $healthCareLogo = empty($healthCareLogo) ? SITE_IMG_PATH . 'logo-p.png' : realpath('../assets/images/orgs/' . $logoFilename . '');
}
// exit;

$pdf = new PDF($billId, $billDate, $totalAmount, $dicountAmount, $dueAmount, $paidAmount, $LabBillDetails, $Pathology, $healthCareLogo,$healthCarePhno, $healthCareApntbkNo,  $healthCareEmail);
$pdf->AliasNbPages();
$pdf->AddContentPage();
$pdf->AddLastPage();
ob_clean();
$pdf->Output();
exit;
// }
?>
