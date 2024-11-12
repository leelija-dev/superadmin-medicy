<?php

trait PrintComponents
{
    
    function billHeader()
    {
        if ($this->PageNo() == 1) {  ///this line only show the header first page

            //.. healthCareLogo...//
            $logoX = 10;
            $logoY = 5;
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
            $this->SetFont('Arial', '', 10);
            $address = $this->healthCareAddress1 . "," . $this->healthCareAddress2 . "," . $this->healthCareCity . "," . $this->healthCarePin ."\nGST ID : " . $this->gstinData;

            $this->SetXY($logoX + $logoWidth + 3, $logoY + 8); // Position below the title
            $this->MultiCell(120, 4.2, $address, 0, 'L');

            //...Invoice Info
          
            $this->SetY(10); // Reset Y position
            $this->SetX(-47); // Align to the right
            // Draw vertical line
            // $this->SetDrawColor(108, 117, 125);
            // $this->SetLineWidth(0.1);
            $this->Line($this->GetX(), $this->GetY() -2, $this->GetX(), $this->GetY() + 15);
            $this->SetFont('Arial', 'B', 10);
            $this->cell(80, -2, ' Invoice', 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->MultiCell(80, 4.2, "\n #" . $this->invoiceId . "\n Payment: " . $this->pMode . "\n Date: " . $this->billDate, 0, 'L');
            $this->Ln(1.8);

            // $this->SetDrawColor(108, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(10);
        }
    }
    // Page footer
    function billFooter()
    {
        $FooterFontSize = 8;

        if ($this->isLastPage) { /// this line only show the footer last page 

            $pageHeight = $this->GetPageHeight();
            $middleY = ($pageHeight / 2)-5.7;
            $this->SetY($middleY);
            // $this->SetLineWidth(0.3);
            $this->SetDrawColor(0,0,0);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            // $this->Ln(2);

            // Set the font for the footer content
            $this->SetFont('Arial', '', $FooterFontSize);

            // Patient Info
            $this->SetY($this->GetY() + 1); // Add some padding
            $startX = 10;
            $currentY = $this->GetY();

            $this->SetX($startX);
            if ($this->REFFBY !== 'Cash Sales') {
                $this->SetFont('Arial', 'B', $FooterFontSize);
                $this->Cell(30, 5, 'Referred By: ', 0, 0, 'L');
                $this->SetFont('Arial', '', $FooterFontSize);
                $this->Cell(30, 5, $this->REFFBY, 0, 'L');
            } else {
                $this->Cell(30, 5, '');
            }
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', $FooterFontSize);
            $this->Cell(30, 5, 'Patient: ', 0, 0, 'L');
            $this->SetFont('Arial', '', $FooterFontSize);
            $this->Cell(30, 5, $this->PATIENTNAME, 0, 'L');
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', $FooterFontSize);
            $this->Cell(30, 5, 'Age: ', 0, 0, 'L');
            $this->SetFont('Arial', '', $FooterFontSize);
            $this->Cell(30, 5, $this->PATIENTAGE, 0, 1, 'L');
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', $FooterFontSize);
            $this->Cell(30, 5, 'Contact: ', 0, 0, 'L');
            $this->SetFont('Arial', '', $FooterFontSize);
            $this->Cell(30, 5, $this->PATIENTPHNO, 0, 1, 'L');

            // GST Calculation
            // $this->SetY(149); // Reset Y position
            // $this->SetX(98); // Align to the right
            // // Draw vertical line
            // $this->SetDrawColor(108, 117, 125);
            // $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 19);

            
            $startX = 70;
            if ($this->REFFBY !== 'Cash Sales') {
                $this->SetY($currentY+1); 
            }else{
                $this->SetY($currentY); 
            }
            // $this->SetY($currentY+2); // Reset Y position to top of the section
            $this->SetX($startX);
            $this->Cell(70, 5, 'CGST :', 0, 0, 'C');
            $this->Cell(-10, 5, ' ' . ($this->TOTALGST / 2), 0, 1, 'C');
            $this->SetX($startX);
            $this->Cell(70, 5, 'SGST :', 0, 0, 'C');
            $this->Cell(-10, 5, ' ' . ($this->TOTALGST / 2), 0, 1, 'C');
            $this->SetX($startX);
            $this->Cell(75, 5, 'Total GST :', 0, 0, 'C');
            $this->Cell(-21, 5, ' ' . $this->TOTALGST, 0, 1, 'C');

            // Amount Calculation
            $startX = 140;
            if ($this->REFFBY !== 'Cash Sales') {
                $this->SetY($currentY+2); 
            }else{
                $this->SetY($currentY); 
            }
            // $this->SetY($currentY+2); // Reset Y position to top of the section
            $this->SetX($startX);
            $this->Cell(20, 5, 'Total :', 0, 0, 'R');
            $this->Cell(41, 5, ' ' . $this->TOTALMRP, 0, 1, 'R');

            $savedName = $this->TOTALMRP - $this->BILLAMOUT > 0 ? 'You Saved  :' : '';
            $savedAmount = $this->TOTALMRP - $this->BILLAMOUT > 0 ? $this->TOTALMRP - $this->BILLAMOUT : '';
            
            $this->SetX($startX);
            $this->SetFont('Arial', '', $FooterFontSize);
            $this->Cell(28, 5, $savedName, 0, 0, 'R');
            $this->Cell(33, 5, $savedAmount, 0, 1, 'R');
            
            $this->SetX($startX);
            $this->SetFont('Arial', 'B', $FooterFontSize+2);
            $this->Cell(28, 5, 'Payable :', 0, 0, 'R');
            $this->Cell(33, 5, ' ' . $this->BILLAMOUT, 0, 1, 'R');

            if ($this->REFFBY !== 'Cash Sales') {
                $this->SetY(144); // Reset Y position
                $this->SetX(98.1); // Align to the right
                // Draw vertical line
                // $this->SetDrawColor(108, 117, 125);
                $this->Line($this->GetX(), $this->GetY()+1, $this->GetX(), $this->GetY() + 19);
                $this->Ln(20.5);
            }else{
                $this->SetY(144); // Reset Y position
                $this->SetX(98.1); // Align to the right
                // Draw vertical line
                // $this->SetDrawColor(108, 117, 125);
                $this->Line($this->GetX(), $this->GetY()+1, $this->GetX(), $this->GetY() + 15);
                $this->Ln(16);
            }
            // $this->Ln(5);
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

    // purchanse invoice Header
    function purchaseHeader(){
        global $distributorName, $distAddress, $distPIN, $distContact,$distributorBill, $pMode,$billDate, $dueDate, $patientName, $patientAge, $gstinData;

        if ($this->PageNo() == 1) {  ///this line only show the header first page

            ///....Title (distributorName Name)...///
            $this->Ln(-4);
            $this->SetFont('Arial', 'B', 16);
            $distributorName = strtoupper($this->distributorName);
            $this->Cell(90, 8, $distributorName, 0, 1, 'L'); // Centered text

            // Address
            $this->SetFont('Arial', '', 9);
            $address = "$distAddress, $distPIN";
            // $this->MultiCell(130, 3.5, $address, 0, 'L');
            if(strlen($address) > 45){
                $this->MultiCell(130, 3.5, $address, 0, 'L');
                $this->SetFont('Arial', 'B', 9);
                $this->Cell(130, 4, "M :");
                $this->SetFont('Arial', '', 9);
                $this->SetX(15);
                $this->Cell(0, 3.5, " $distContact,\n");
                $this->SetX(35);
                $this->SetFont('Arial', 'B', 9);
                $this->Cell(130, 4, "GST ID :");
                $this->SetFont('Arial', '', 9);
                $this->SetX(50);
                $this->Cell(0, 3.5, "$gstinData");
                // $this->Cell(130,4,"M: $distContact,\nGST ID : $gstinData",0, 'L');
            }else{
                $this->MultiCell(130, 4, $address, 0, 'L');
                $this->SetFont('Arial', 'B', 9);
                $this->Cell(130, 4, "M:");
                $this->SetFont('Arial', '', 9);
                $this->SetX(15);
                $this->Cell(-1, 4, "$distContact,\n");
                $this->SetY(22);
                $this->SetFont('Arial', 'B', 9);
                $this->Cell(130, 4, "GST ID :");
                $this->SetFont('Arial', '', 9);
                $this->SetX(23);
                $this->Cell(0, 4, "$gstinData");

                // $this->MultiCell(130,4,"M: $distContact,\nGST ID : $gstinData",0, 'L');
            }

            

            ///...Invoice Info
            $this->SetY(10); // Reset Y position
            $this->SetX(-51); // Align to the right
            // Draw vertical line
            // $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY() -2 , $this->GetX(), $this->GetY() + 17);
            $this->SetFont('Arial', 'B', 10);
            $this->cell(80, -2, ' Purchase:', 0, 'L');
            $this->SetFont('Arial', '', 8);
            $this->MultiCell(80, 4, "\n #$distributorBill\n Payment: $pMode\n Bill Date: $billDate\n Due Date: $dueDate", 0, 'L');

            $this->Ln(1);
            // $this->SetDrawColor(108, 117, 125);
            // $this->SetLineWidth(0.1);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(8);
        }
    }

    // purchase Invoice footer 
    function purchaseFooter(){
         if ($this->isLastPage) { /// this line only show the footer last page 

         $pageHeight = $this->GetPageHeight();
         $middleY = ($pageHeight / 2) - 15.2;
         $this->SetY($middleY);
         // $this->SetLineWidth(0.4);
         $this->SetDrawColor(0, 0, 0);
         $this->Line(10, $this->GetY(), 200, $this->GetY());
         // $this->Ln(2);


         // Patient Info
         $this->SetY($this->GetY()+1); // Add some padding
         $startX = 10;
         $currentY = $this->GetY();

         $this->SetX($startX);
         $this->SetFont('Arial', 'B', 9);
         $this->Cell(30, 5, 'Bill To : ', 0, 0, 'L');
         $this->SetFont('Arial', '', 9);
         $this->MultiCell(80, 5,  " $this->pharmacyName\n $this->pharmacyContact", 0, 'L');

         // GST Calculation
         $this->SetY(134.5); // Reset Y position
         $this->SetX(92); // Align to the right
         // Draw vertical line
         $this->SetDrawColor(0, 0, 0);
         $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 14);

         $startX = 70;
         $this->SetY($currentY); // Reset Y position to top of the section
         $this->SetX($startX);
         $this->Cell(72, 5, 'CGST :', 0, 0, 'C');
         $this->Cell(-10, 5, '' . $this->cGst, 0, 1, 'C');
         $this->SetX($startX);
         $this->Cell(72, 5, 'SGST :', 0, 0, 'C');
         $this->Cell(-10, 5, '' . $this->cGst, 0, 1, 'C');
         $this->SetX($startX);
         $this->Cell(78, 5, 'Total GST :', 0, 0, 'C');
         $this->Cell(-21, 5, '' . $this->totalGst, 0, 1, 'C');

         // Amount Calculation
         $startX = 140;
         $this->SetY($currentY); // Reset Y position to top of the section
         $this->SetX($startX);
         $this->Cell(20, 5, 'Total :', 0, 0, 'R');
         $this->Cell(41, 5, '' . $this->totalMrp, 0, 1, 'R');
         $this->SetX($startX);
         $this->SetFont('Arial', '', 9);
         $this->Cell(28, 5, 'You Saved :', 0, 0, 'R');
         $this->Cell(33, 5, '' . ($this->totalMrp - $this->billAmnt), 0, 1, 'R');
         $this->SetX($startX);
         $this->SetFont('Arial', 'B', 9);
         $this->Cell(30, 5, 'Net Amount :', 0, 0, 'R');
         $this->Cell(31, 5, '' . $this->billAmnt, 0, 1, 'R');
         
         $this->Ln(0.1);
         $this->SetDrawColor(0, 0, 0);
         $this->Line(10, $this->GetY(), 200, $this->GetY());

         $this->Ln(2.2);
         $phoneIcon = '../assets/plugins/pdfprint/icon/phone.png';
         $emailIcon = '../assets/plugins/pdfprint/icon/email.png';
         $this->SetFont('Arial', '', 8);
         $startX = $this->GetX();
         $startY = $this->GetY();
         $this->Image( $phoneIcon, $startX, $startY-2, 4); // Adjust position and size as needed
         $this->Image( $emailIcon, $startX + 23, $startY-2, 3.5);
         // Construct the address text
         $address = " " . $this->distContact . ",        ". $this->distEmail . "  Print Time: " . date('Y-m-d H:i:s');
         $textX = $startX + 3;
         $this->SetXY($textX, $startY);
         // Output the address text
         $this->SetFont('Arial', 'B', 8);
         $this->Cell(0, 0, $address, 0, 1, 'L');
        }
    }

    //purchase return Header
    function purchaseReturnHeader(){

        global $distributorName, $distAddress, $distPIN, $distContact, $stockReturnId,$refundMode,  $patientPhno,$returnDate, $gstinData;
        if ($this->PageNo() == 1) {  ///this line only show the header first page

            ///....Title (distributorName Name)...///
            $this->Ln(-4);
            $this->SetFont('Arial', 'B', 16);
            $distributorName = strtoupper($this->distributorName);
            $this->Cell(120, 8, $distributorName, 0, 1, 'L'); // Centered text

            // Address
            // $this->SetFont('Arial', '', 9);
            // $address = "$distAddress ,$distPIN \nM: $distContact,\nGST ID : $gstinData";
            // $this->MultiCell(130, 4, $address, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $address = "$distAddress, $distPIN";
            // $this->MultiCell(130, 3.5, $address, 0, 'L');
            if(strlen($address) > 45){
                $this->MultiCell(130, 3.5, $address, 0, 'L');
                $this->SetFont('Arial', 'B', 9);
                $this->Cell(130, 4, "M :");
                $this->SetFont('Arial', '', 9);
                $this->SetX(15);
                $this->Cell(0, 3.5, " $distContact,\n");
                $this->SetX(35);
                $this->SetFont('Arial', 'B', 9);
                $this->Cell(130, 4, "GST ID :");
                $this->SetFont('Arial', '', 9);
                $this->SetX(50);
                $this->Cell(0, 3.5, "$gstinData");
                // $this->Cell(130,4,"M: $distContact,\nGST ID : $gstinData",0, 'L');
            }else{
                $this->MultiCell(130, 4, $address, 0, 'L');
                $this->SetFont('Arial', 'B', 9);
                $this->Cell(130, 4, "M:");
                $this->SetFont('Arial', '', 9);
                $this->SetX(15);
                $this->Cell(-1, 4, "$distContact,\n");
                $this->SetY(22);
                $this->SetFont('Arial', 'B', 9);
                $this->Cell(130, 4, "GST ID :");
                $this->SetFont('Arial', '', 9);
                $this->SetX(23);
                $this->Cell(0, 4, "$gstinData");

                // $this->MultiCell(130,4,"M: $distContact,\nGST ID : $gstinData",0, 'L');
            }

            ///...Invoice Info
            $this->SetY(10); // Reset Y position
            $this->SetX(-51); // Align to the right
            // Draw vertical line
            // $this->SetDrawColor(108, 117, 125);
            $this->Line($this->GetX(), $this->GetY()-2, $this->GetX(), $this->GetY() + 15);
            $this->SetFont('Arial', 'B', 10);
            $this->cell(45, -1, ' Return Bill', 0, 'L');
            $this->SetFont('Arial', '', 8);
            $this->MultiCell(45, 4, "\n #$stockReturnId\n Refund Mode : $refundMode\n Return Date : $returnDate", 0, 'L');
            $this->Ln(1.6);
            // $this->SetDrawColor(108, 117, 125);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(8);
        }
    }

    //purchase return Footer
    function purchaseReturnFooter(){
         if ($this->isLastPage) { /// this line only show the footer last page 

         $pageHeight = $this->GetPageHeight();
         $middleY = ($pageHeight / 2)-15.2;
         $this->SetY($middleY);
         // $this->SetLineWidth(0.4);
         $this->SetDrawColor(0,0,0);
         $this->Line(10, $this->GetY(), 200, $this->GetY());
         // $this->Ln(2);

        // Set the font for the footer content
        $this->SetFont('Arial', '', 10);

         // Patient Info
         $this->SetY($this->GetY() + 1); // Add some padding
         $startX = 10;
         $currentY = $this->GetY();

         $this->SetX($startX);
         $this->SetFont('Arial', 'B', 9);
         $this->Cell(30, 5, 'Customer : ', 0, 0, 'L');
         $this->SetFont('Arial', '', 9);
         $this->MultiCell(80, 5,  " $this->pharmacyName\n $this->pharmacyContact", 0, 'L');

         $this->SetY(134.5); // Reset Y position
         $this->SetX(92); // Align to the right
         // Draw vertical line
         // $this->SetDrawColor(108, 117, 125);
         $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 14);

         $startX = 70;
         $this->SetY($currentY); // Reset Y position to top of the section
         $this->SetX($startX);
         $this->Cell(72, 5, 'CGST :', 0, 0, 'C');
         $this->Cell(-10, 5, '' . $this->returnGst/2, 0, 1, 'C');
         $this->SetX($startX);
         $this->Cell(72, 5, 'SGST :', 0, 0, 'C');
         $this->Cell(-10, 5, '' . $this->returnGst/2, 0, 1, 'C');
         $this->SetX($startX);
         $this->Cell(78, 5, 'Total GST :', 0, 0, 'C');
         $this->Cell(-20, 5, '' . floatval($this->returnGst), 0, 1, 'C');

         $this->SetY(134.5); // Reset Y position
         $this->SetX(150); // Align to the right
         // Draw vertical line
         // $this->SetDrawColor(108, 117, 125);
         $this->Line($this->GetX(), $this->GetY(), $this->GetX(), $this->GetY() + 14);

         // Amount Calculation
         $startX = 140;
         $this->SetY($currentY); // Reset Y position to top of the section
         $this->SetX($startX);
         $this->Cell(35.6, 5, 'Total Items :', 0, 0, 'R');
         $this->Cell(25.2, 5, '' . $this->itemQty, 0, 1, 'R');
         $this->SetX($startX);
         $this->SetFont('Arial', '', 9);
         $this->Cell(35.5, 5, 'Total Units :', 0, 0, 'R');
         $this->Cell(25.2, 5, '' . $this->totalReturnQty, 0, 1, 'R');
         $this->SetX($startX);
         $this->SetFont('Arial', 'B', 9);
         $this->Cell(40, 5, 'Total Refund :', 0, 0, 'R');
         $this->Cell(21, 5, '' . floatval($this->refund), 0, 1, 'R');
         
         $this->Ln(0.1);
         // $this->SetDrawColor(108, 117, 125);
         $this->Line(10, $this->GetY(), 200, $this->GetY());
     // }

         $this->Ln(2.2);
         $phoneIcon = '../assets/plugins/pdfprint/icon/phone.png';
         $emailIcon = '../assets/plugins/pdfprint/icon/email.png';
         $this->SetFont('Arial', '', 8);
         $startX = $this->GetX();
         $startY = $this->GetY();
         $this->Image( $phoneIcon, $startX, $startY-2, 4); // Adjust position and size as needed
         $this->Image( $emailIcon, $startX + 23, $startY-2, 3.5);
         // Construct the address text
         $address = " " . $this->distContact . ",        ". $this->distEmail . "  Print Time: " . date('Y-m-d H:i:s');
         $textX = $startX + 3;
         $this->SetXY($textX, $startY);
         // Output the address text
         $this->SetFont('Arial', 'B', 8);
         $this->Cell(0, 0, $address, 0, 1, 'L');
         }
    }
}
