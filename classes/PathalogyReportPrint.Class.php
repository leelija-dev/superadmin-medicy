<?php

/**
 * Author: Dipak Majumdar
 * @since: 02-11-2024
 * description: This class is uning for printing the pathalogy test reports only
 */
// Include FPDF library
require('assets/plugins/pdfprint/fpdf/fpdf.php');

class PDF extends FPDF
{
    var $isLastPage = false;
    var $paramsValues = [];

    private $testData;

    function setTestData($data)
    {
        $this->testData = $data;
    }

    function WriteHTML($html)
    {
        // Remove new lines and extra spaces
        $html = str_replace("\n", '', $html);
        $html = preg_replace('/\s+/', ' ', $html);
        $html = str_replace('&nbsp;', ' ', $html);
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $html = str_replace("\xC2\xA0", " ", $html);
        $html = str_replace("Â", "", $html);

        // Split the HTML into elements
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

        $inTable = false;
        $inRow = false;
        $inCell = false;

        $tableXPosition = 21; // X position for the table
        $currentX = $tableXPosition;
        $currentY = $this->GetY(); // Y position for the table
        $cellHeight = 6; // Adjust cell height as needed

        $rows = [];
        $currentRow = [];
        $maxColumnWidths = [];

        $currentCol = 0;

        // First pass: Calculate maximum width for each column
        foreach ($a as $i => $e) {
            if ($i % 2 == 0) {
                // Text
                if ($inCell) {
                    $textWidth = $this->GetStringWidth($e);
                    $currentRow[$currentCol] = $textWidth + 2; // Add padding
                    $currentCol++;
                }
            } else {
                // Tag
                $tag = strtolower($e);
                if ($tag == 'tr') {
                    $inRow = true;
                    $currentRow = [];
                    $currentCol = 0;
                }
                if ($tag == '/tr') {
                    $inRow = false;
                    $rows[] = $currentRow;
                    // Calculate the max width for each column
                    foreach ($currentRow as $colIndex => $width) {
                        if (!isset($maxColumnWidths[$colIndex])) {
                            $maxColumnWidths[$colIndex] = $width;
                        } else {
                            $maxColumnWidths[$colIndex] = max($maxColumnWidths[$colIndex], $width);
                        }
                    }
                }
                if ($tag == 'td') {
                    $inCell = true;
                }
                if ($tag == '/td') {
                    $inCell = false;
                }
            }
        }

        // Second pass: Render the table with calculated column widths
        $rowIndex = 0;
        foreach ($a as $i => $e) {
            if ($i % 2 == 0) {
                // Text
                if ($inCell) {
                    $cellWidth = $maxColumnWidths[$currentCol]; // Use the max width for this column
                    $this->SetXY($currentX, $currentY);
                    $this->Cell($cellWidth, $cellHeight, $e, 1);
                    $currentX += $cellWidth;
                    $currentCol++;
                } else {
                    $this->Write(5, $e);
                    // $this->Ln(-1);
                    // $this->MultiCell(165, 4, $e, 0, 'L');
                }
            } else {
                // Tag
                $tag = strtolower($e);
                if ($tag == 'b') $this->SetFont('', 'B');
                if ($tag == '/b') $this->SetFont('', '');
                if ($tag == 'i') $this->SetFont('', 'I');
                if ($tag == '/i') $this->SetFont('', '');
                if ($tag == 'table') {
                    $inTable = true;
                    $this->Ln(1); // Line break before the table
                }
                if ($tag == '/table') {
                    $inTable = false;
                    $this->Ln(8); // Line break after the table
                    $currentY = $this->GetY();
                }
                if ($tag == 'tr') {
                    $inRow = true;
                    $this->Ln(); // Start new row
                    $currentX = $tableXPosition; // Reset X position for the new row
                    $currentY = $this->GetY(); // Update Y position for the new row
                    $currentCol = 0; // Reset column index for the new row
                }
                if ($tag == '/tr') {
                    $inRow = false;
                    $rowIndex++;
                }
                if ($tag == 'td') {
                    $inCell = true;
                }
                if ($tag == '/td') {
                    $inCell = false;
                }
            }
        }
        // $this->SetX($tableXPosition);
    }

    
//     function TextFormatHTML($html){
//     // Clean HTML content
//     $html = str_replace("\n", '', $html);
//     $html = preg_replace('/\s+/', ' ', $html);
//     $html = str_replace('&nbsp;', ' ', $html);
//     $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
//     $html = str_replace("Â", "", $html);
//     $html = str_replace("â€“", " - ", $html);

//     // Split the HTML into elements
//     $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

//     $inTable = false;
//     $inRow = false;
//     $inCell = false;

//     $tableXPosition = 15;
//     $currentX = $tableXPosition;
//     $currentY = $this->GetY();
//     $cellHeight = 6;

//     $rows = [];
//     $currentRow = [];
//     $maxColumnWidths = [];
//     $currentCol = 0;

//     // First pass: Calculate max width for each column
//     foreach ($a as $i => $e) {
//         if ($i % 2 == 0) {
//             if ($inCell) {
//                 $textWidth = $this->GetStringWidth($e) + 1;
//                 $currentRow[$currentCol] = $textWidth;
//                 $currentCol++;
//             }
//         } else {
//             $tag = strtolower($e);
//             if ($tag == 'tr') {
//                 $inRow = true;
//                 $currentRow = [];
//                 $currentCol = 0;
//             }
//             if ($tag == '/tr') {
//                 $inRow = false;
//                 $rows[] = $currentRow;
//                 foreach ($currentRow as $colIndex => $width) {
//                     $maxColumnWidths[$colIndex] = isset($maxColumnWidths[$colIndex]) 
//                         ? max($maxColumnWidths[$colIndex], $width) 
//                         : $width + 0.1;
//                 }
//             }
//             if ($tag == 'td') {
//                 $inCell = true;
//             }
//             if ($tag == '/td') {
//                 $inCell = false;
//             }
//         }
//     }

//     // Second pass: Render the table with max column widths
//     $rowIndex = 0;
//     foreach ($a as $i => $e) {
//         if ($i % 2 == 0) {
//             if ($inCell) {
//                 $cellWidth = $maxColumnWidths[$currentCol];
//                 $this->SetXY($currentX, $currentY);
//                 $this->Cell($cellWidth, $cellHeight, $e, 1);
//                 $currentX += $cellWidth;
//                 $currentCol++;
//             } else {
//                 $this->Write(5, $e);
//             }
//         } else {
//             $tag = strtolower($e);
//             if ($tag == 'p'){
//                  $this->Ln();
//             }
//             // if ($tag == '/p') $this->Ln(4);
//             if ($tag == 'b') $this->SetFont('', 'B');
//             if ($tag == '/b') $this->SetFont('', '');
//             if ($tag == 'i') $this->SetFont('', 'I');
//             if ($tag == '/i') $this->SetFont('', '');
//             if ($tag == 'table') {
//                 $inTable = true;
//                 $this->Ln(2);
//             }
//             if ($tag == '/table') {
//                 $inTable = false;
//                 $this->Ln(4);
//                 $currentY = $this->GetY();
//             }
//             if ($tag == 'tr') {
//                 $inRow = true;
//                 $this->Ln();
//                 $currentX = $tableXPosition;
//                 $currentY = $this->GetY();
//                 $currentCol = 0;
//             }
//             if ($tag == '/tr') {
//                 $inRow = false;
//                 $rowIndex++;
//             }
//             if ($tag == 'td') {
//                 $inCell = true;
//             }
//             if ($tag == '/td') {
//                 $inCell = false;
//             }
//         }
//     }
// }

function TextFormatHTML($html) {
    // Clean HTML content
    $html = str_replace("\n", '', $html);
    $html = preg_replace('/\s+/', ' ', $html);
    $html = str_replace('&nbsp;', ' ', $html);
    $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $html = str_replace("Â", "", $html);
    $html = str_replace("â€“", " - ", $html);

    // Split the HTML into elements
    $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

    $inTable = false;
    $inRow = false;
    $inCell = false;

    $tableXPosition = 15;
    $currentX = $tableXPosition;
    $currentY = $this->GetY();
    $cellHeight = 6;

    $rows = [];
    $currentRow = [];
    $maxColumnWidths = [];
    $currentCol = 0;

    // Define page height threshold (50%)
    $pageHeightThreshold = $this->GetPageHeight() * (0.95 * 4);
    $cumulativeHeight = 0;

    // First pass: Calculate max width for each column
    foreach ($a as $i => $e) {
        if ($i % 2 == 0) {
            if ($inCell) {
                $textWidth = $this->GetStringWidth($e) + 0.1;
                $currentRow[$currentCol] = $textWidth;
                $currentCol++;
            }
        } else {
            $tag = strtolower($e);
            if ($tag == 'tr') {
                $inRow = true;
                $currentRow = [];
                $currentCol = 0;
            }
            if ($tag == '/tr') {
                $inRow = false;
                $rows[] = $currentRow;
                foreach ($currentRow as $colIndex => $width) {
                    $maxColumnWidths[$colIndex] = isset($maxColumnWidths[$colIndex]) 
                        ? max($maxColumnWidths[$colIndex], $width) 
                        : $width + 0.1;
                }
            }
            if ($tag == 'td') {
                $inCell = true;
            }
            if ($tag == '/td') {
                $inCell = false;
            }
        }
    }

    // Second pass: Render the table with max column widths and check height
    $rowIndex = 0;
    foreach ($a as $i => $e) {
        if ($i % 2 == 0) {
            if ($inCell) {
                $cellWidth = $maxColumnWidths[$currentCol];
                $this->SetXY($currentX, $currentY);
                
                // Add cell and track height
                $this->Cell($cellWidth, $cellHeight, $e, 1);
                $currentX += $cellWidth;
                $currentCol++;
                
                // Update cumulative height and check if new page is needed
                $cumulativeHeight += $cellHeight;
                if ($cumulativeHeight >= $pageHeightThreshold) {
                    // $this->Line(10, $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());
                    $this->AddPage();
                    $currentY = $this->GetY();
                    $currentX = $tableXPosition;
                    $cumulativeHeight = 0;  // Reset for new page
                }
            } else {
                $this->Write(5, $e);
            }
        } else {
            $tag = strtolower($e);
            if ($tag == 'p'){
                 $this->Ln();
            }
            if( $tag == 'strong') $this->setFont('', 'B');
            if( $tag == '/strong') $this->setFont('', '');
            if ($tag == 'b') $this->SetFont('', 'B');
            if ($tag == '/b') $this->SetFont('', '');
            if ($tag == 'i') $this->SetFont('', 'I');
            if ($tag == '/i') $this->SetFont('', '');
            if ($tag == 'table') {
                $inTable = true;
                $this->Ln(2);
            }
            if ($tag == '/table') {
                $inTable = false;
                $this->Ln(4);
                $currentY = $this->GetY();
            }
            if ($tag == 'tr') {
                $inRow = true;
                $this->Ln();
                $currentX = $tableXPosition;
                $currentY = $this->GetY();
                $currentCol = 0;
            }
            if ($tag == '/tr') {
                $inRow = false;
                $rowIndex++;
            }
            if ($tag == 'td') {
                $inCell = true;
            }
            if ($tag == '/td') {
                $inCell = false;
            }
        }
    }
}



    ///.....for gradient color....///
    function Gradient($x, $y, $w, $h, $startColor, $endColor, $startPercentage = 1, $direction = 'horizontal')
    {
        list($r1, $g1, $b1) = $startColor;
        list($r2, $g2, $b2) = $endColor;

        for ($i = 0; $i <= 100; $i++) {
            if ($i / 100 >= (1 - $startPercentage)) {
                $r = $r1 + ($r2 - $r1) * (($i / 100 - (1 - $startPercentage)) / $startPercentage);
                $g = $g1 + ($g2 - $g1) * (($i / 100 - (1 - $startPercentage)) / $startPercentage);
                $b = $b1 + ($b2 - $b1) * (($i / 100 - (1 - $startPercentage)) / $startPercentage);
            } else {
                $r = $r1;
                $g = $g1;
                $b = $b1;
            }
            $this->SetFillColor($r, $g, $b);

            if ($direction == 'horizontal') {
                $this->Rect($x + $i * ($w / 100), $y, $w / 100, $h, 'F');
            } else {
                $this->Rect($x, $y + $i * ($h / 100), $w, $h / 100, 'F');
            }
        }
    } ///...end gradient color....///

    //.....Header Star....//
    function Header()
    {
        global $healthCareName, $name, $patient_id, $age, $gender, $sampCltDate, $reportDate, $healthCareAddress1,
            $healthCareAddress2, $healthCareCity, $healthCareDist, $healthCareState, $healthCarePin, $healthCarePhno, $healthCareApntbkNo, $doctorName, $PlanName;

        // if ($this->PageNo() == 1) {

        $leftSpace = 3; // Left side space in mm
        $rightSpace = -6; // Right side space in mm
        // Calculate positions
        $imageX = $leftSpace; // X position with left space
        $imageY = 3; // Y position
        $imageWidth = 200 - ($leftSpace + $rightSpace); // Adjusted width with spaces
        $imageHeight = 18; // Height of the image
        $this->Image('./assets/images/top-wave.jpg', $imageX, $imageY, $imageWidth, $imageHeight);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 25);
        $this->SetTextColor(24, 54, 151);
        $this->Cell(186, 14, $healthCareName, 0, 1, 'R');
        $this->SetFont('Arial', '', 12);
        $this->Gradient($this->GetPageWidth() / 2, 26, $this->GetPageWidth() / 2 - 10, 8, [255, 255, 255], [24, 54, 151], 1, 'horizontal');
        $this->SetY(20);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 16, $PlanName, 0, 1, 'R');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(24, 54, 151);
        // $website = './assets/plugins/pdfprint/icon/internet.png';
        $phoneIcon = './assets/plugins/pdfprint/icon/contact.png'; //
        // $this->Image($phoneIcon, 0, 0, 3.5, 3.5);
        // $this->Image($website, 158.5, 35, 3.5, 3.5);
        // $stateWords = explode(' ', $healthCareState);
        // $healthCareStateAbbr = '';
        // foreach ($stateWords as $word) {
        //     $healthCareStateAbbr .= strtoupper($word[0]);
        // }
        // $this->Cell(20, 2, $healthCareAddress1 . $healthCareAddress2.','.$healthCareCity .','.$healthCareDist.',('.    $healthCareStateAbbr.'),'.$healthCarePin.',   '.$healthCarePhno.'/'.$healthCareApntbkNo, 0, 1, 'L');
        // $this->setX(27);
        $fixedWidth = 178; // Adjust the width as needed
        $left = 27;
        $right = 12;
        $pageWidth = $this->GetPageWidth();
        $availableWidth = $pageWidth - $left - $right;

        $addressText = $healthCareAddress1 . $healthCareAddress2 . ',' . $healthCareCity . ',' . $healthCareDist . ',' .  $healthCareState . ',' . $healthCarePin . ',' . $healthCarePhno . '/' . $healthCareApntbkNo;
        if ($this->GetStringWidth($addressText) > 178) {
            $this->setY(33.3);
            $this->setX(25);

            // $this->Image($phoneIcon,160, 37.5, 3.5, 3.5);
            $this->MultiCell($fixedWidth, 4, $addressText, 0, 'R');
            $this->Image('./assets/images/report-heart.jpg', 2, 35.5, 26, 0);
            $this->SetDrawColor(24, 54, 151);
            $currentY = $this->GetY();
            $this->Line(28.3, $currentY + 1, 205, $currentY + 1);
        } else {
            $this->setX(33);
            $this->Cell($availableWidth, 2, $addressText, 0, 0, 'R');

            // $this->Image($phoneIcon,163.8, 35, 3.5, 3.5);
            $this->MultiCell($fixedWidth, 4, $addressText, 0, 'R');

            $this->Image('./assets/images/report-heart.jpg', 2, 33.2, 26, 0);
            $this->SetDrawColor(24, 54, 151);
            $this->Line(28.3, 40, 205, 40);
        }

        // $this->Image('./assets/images/report-heart.jpg', 2, 33.2, 26, 0);
        // $this->SetDrawColor(24, 54, 151);
        // $this->Line(28.3, 40, 200, 40);
        $this->Ln(8);
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 5, "Patient's Name: $name", 0, 0, 'L');
        $this->Cell(0, 5, "Age: $age   Sex: $gender", 0, 1, 'R');
        $this->Cell(0, 5, "Patient ID: $patient_id", 0, 0, 'L');
        $this->Cell(0, 5, 'Collection Date: ' . formatDateTime($sampCltDate, '/'), 0, 1, 'R');
        $this->Cell(0, 5, 'Place of collection: LAB', 0, 0, 'L');
        $this->Cell(0, 5, 'Reporting Date: ' . formatDateTime($reportDate, '/'), 0, 1, 'R');
        $this->Cell(0, 5, 'Ref. by: ' . $doctorName, 0, 0, 'L');
        $this->Ln(5);
        $this->SetDrawColor(0, 0, 0);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(2);
        // }
    } //.....Header end....//

    //....footer start....//
    function Footer()
    {
        global $doctorName, $doctorReg;
        // if ($this->isLastPage) {
        $this->SetY(-55);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(0, 5, 'Reference values are obtained from the literature provided with reagent kit.', 0, 'C');
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(2);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, '***END OF REPORT***', 0, 1, 'C');
        $this->Ln(2);
        $this->SetFont('Arial', 'I', 10);
        $this->SetTextColor(24, 54, 151);
        $this->Cell(60, 5, 'A Health Care Unit for :-', 4, 0, 'L');
        $this->SetTextColor(0, 0, 0);
        $this->Cell(60, 5, 'Verified by :', 8, 0, 'R');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(70, 5, $doctorName, 0, 1, 'R');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(24, 54, 151);
        $this->Cell(60, 5, 'Advance Assay, USG & ECHO, Colour Doppler,', 0, 0, 'L');
        $this->Cell(60, 5, '', 0, 0, 'C');
        $this->SetTextColor(0, 0, 0);
        $this->Cell(70, 5, 'Consultant Pathologist(MD)', 0, 1, 'R');
        $this->SetTextColor(24, 54, 151);
        $this->Cell(60, 5, 'Digital X-Ray, Special X-Ray, OPG, ECG & Eye.', 0, 0, 'L');
        $this->Cell(60, 5, '', 0, 0, 'C');
        $this->SetTextColor(0, 0, 0);
        $this->Cell(70, 5, 'Reg. No: ' . $doctorReg, 0, 1, 'R');

        // Define left and right side spacing
        $leftSpace = 3; // Left side space in mm
        $rightSpace = -6; // Right side space in mm
        $imageX = $leftSpace; // X position with left space
        $imageY = 276; // Y position
        $imageWidth = 200 - ($leftSpace + $rightSpace); // Adjusted width with spaces
        $imageHeight = 18; // Height of the image

        $this->Image('./assets/images/bottom-wave.jpg', $imageX, $imageY, $imageWidth, $imageHeight);
        $textY = $imageY + $imageHeight + -10; // Adjust Y position as needed
        $this->SetY($textY);
        $this->SetFont('Arial', 'I', 7);
        $this->SetTextColor(255, 255, 255);
        $cellWidth = 180 - ($leftSpace + $rightSpace);
        $this->Cell($cellWidth, 3, '*The result may be correlation clinically', 0, 1, 'R');
        $this->Cell($cellWidth, 3, '*Patient identification not verified', 0, 1, 'R');
        $this->Cell($cellWidth, 3, '*This report is not valid for medico legal purpose', 0, 1, 'R');
        // }
    } //....footer end....//

    //.....Main test content start....//
    function AddContentPage()
    {
        // Array to store test names that have already been rendered
        $renderedTests = [];

        if (!is_array($this->testData)) {

            $this->AddPage("", "A4");
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 8, "REPORT OF XXXX $this->testData TEST", 0, 1, 'C');
            $lineWidth = 200 * 0.6;
            $lineX = (208 - $lineWidth) / 2;
            $this->Line($lineX, $this->GetY(), $lineX + $lineWidth, $this->GetY());
            $this->Ln(10);
        }

        foreach ($this->testData as $test) {

             // Check if the current test has already been rendered
             if (in_array($test['test-name'], $renderedTests)) {
                 continue; // Skip this test if it has already been rendered
             }
             // Add the current test to the list of rendered tests
             $renderedTests[] = $test['test-name'];

            $this->AddPage("", "A4");
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 8, "REPORT OF {$test['test-name']} TEST", 0, 1, 'C');
            $lineWidth = 200 * 0.6;
            $lineX = (208 - $lineWidth) / 2;
            $this->Line($lineX, $this->GetY(), $lineX + $lineWidth, $this->GetY());
            $this->Ln(10);

            if (isset($test['textformat_data'])) {
                $remainingHeight = $this->GetPageHeight() - $this->GetY();
                $this->Ln(-8);
                $this->SetFont('Arial', '', 9);
                $textformat_data = $this->TextFormatHTML($test['textformat_data']);
            }

            
            // $parameterCount = 0;
            $heightThreshold = 240;
            $lastParamName = '';
            $accumulatedValues = [];
            foreach ($test['parameters'] as $eachParam) {

                $currentY = $this->GetY();
                $this->setX(20);

                $paramHeight = 5 + (count(explode('<br>', $eachParam['child-range'])) * 5) + 10 + (count(explode('<br>', $eachParam['adult-male-range'])) * 5) + 10 + (count(explode('<br>', $eachParam['adult-female-range'])) * 5) + 10 + (count(explode('<br>', $eachParam['general-range'])) * 5) + 10; // Rough estimation
                if ($currentY + $paramHeight > $heightThreshold) {
                    // $this->Line($lineX, $heightThreshold, $lineX + $lineWidth, $heightThreshold);
                    $this->AddPage("", "A4");
                    $this->Ln(10); // Add some space at the top of the new page
                }

                if ($eachParam['param-heading'] == 0) {

                    $this->setX(20);
                    $this->SetFont('Arial', 'B', 10.5);
                    $this->Cell(50, 5, $eachParam['param-name'], 0, 0, 'L');
                    $this->SetFont('Arial', 'B', 10);
                    $this->setX(160);
                    $this->Cell(15, 5, ': ' . $eachParam['param-value'] . " " . $eachParam['unit'], 0, 1, 'L');

                    if ($eachParam['child-range']) {
                        $this->setX(20);
                        $this->SetFont('Arial', 'B', 8.2);
                        $this->Cell(50, 5, 'For Child', 0, 0, 'L');
                        $width = 165;
                        $this->Ln(5);
                        $this->SetFont('Arial', '', 8);
                        $childRanges = explode('<br>', $eachParam['child-range']);
                        foreach ($childRanges as $range) {
                            $this->setX(20);
                            $range = $this->WriteHTML($range);
                            // $this->WriteHTML($width, 4, $range, 0, 'L');
                            $this->MultiCell($width, 4, $range, 0, 'L');
                        }
                    }
                    // $this->Ln(-1);
                    if ($eachParam['adult-male-range']) {
                        $this->setX(20);
                        $this->SetFont('Arial', 'B', 8.2);
                        $this->Cell(50, 5, 'For Adult Male', 0, 0, 'L');
                        $this->SetFont('Arial', '', 8);
                        $this->Ln(5);
                        // $this->Cell(0, 5, ($eachParam['adult-range']), 0, 1, 'C');
                        $adultMaleRanges = explode('<br>', $eachParam['adult-male-range']);
                        foreach ($adultMaleRanges as $range) {
                            $this->setX(20);
                            $range = $this->WriteHTML($range);
                            $this->MultiCell($width, 4, $range, 0, 'L');
                        }
                    }


                    if ($eachParam['adult-female-range']) {
                        $this->setX(20);
                        $this->SetFont('Arial', 'B', 8.2);
                        $this->Cell(50, 5, 'For Adult Female', 0, 0, 'L');
                        $this->SetFont('Arial', '', 8);
                        $this->Ln(5);
                        // $this->Cell(0, 5, ($eachParam['adult-range']), 0, 1, 'C');
                        $adultFemaleRanges = explode('<br>', $eachParam['adult-female-range']);
                        foreach ($adultFemaleRanges as $range) {
                            $this->setX(20);
                            $range = $this->WriteHTML($range);
                            $this->MultiCell($width, 4, $range, 0, 'L');
                        }
                    }

                    if ($eachParam['general-range']) {
                        $this->setX(20);
                        $this->SetFont('Arial', 'B', 8.2);
                        $this->Cell(50, 5, 'For General', 0, 0, 'L');
                        $this->SetFont('Arial', '', 8);
                        $this->Ln(5);
                        // $this->Cell(0, 5, ($eachParam['adult-range']), 0, 1, 'C');
                        $generalRanges = explode('<br>', $eachParam['general-range']);
                        foreach ($generalRanges as $range) {
                            $this->setX(20);
                            $range = $this->WriteHTML($range);
                            $this->MultiCell($width, 4, $range, 0, 'L');
                        }
                    }
                    $this->Ln(2);
                } else {
                    if (isset($eachParam['report-id']) && !empty($eachParam['report-id'])) {
                        // Check if the current parameter name is different from the last one
                        if ($eachParam['param-name'] !== $lastParamName) {
                            // If it's a new parameter, output the previous one if applicable
                            if (!empty($lastParamName)) {
                                // Output the accumulated values for the previous param-name
                                $this->setX(60);
                                $this->SetFont('Arial', '', 10);
                                $this->Cell(50, 5, implode('         ', $accumulatedValues), 0, 1, 'L');
                            }
                            // Start a new parameter name
                            $this->Ln(3);
                            $this->setX(20);
                            $this->SetFont('Arial', 'B', 10);
                            $this->Cell(50, 15, $eachParam['param-name'] . '  : ', 0, 0, 'L'); // Display param-name

                            // Check if phead-names is different from param-name or hasn't been displayed yet
                            if ($eachParam['phead-names'] !== $lastPheadName) {
                                $this->setX(61);
                                $pheadNameWidth = strlen($eachParam['phead-names']) * 2.5;
                                $this->Cell($pheadNameWidth, 5, $eachParam['phead-names'], 0, 1, 'L'); // Display phead-names
                            } else {
                                $this->Ln(5);
                            }
                            $lastParamName = $eachParam['param-name'];
                            $lastPheadName = $eachParam['phead-names'];
                            $accumulatedValues = []; // Reset for the new param-name
                        }

                        // Accumulate the current param-value
                        $accumulatedValues[] = $eachParam['param-value'];
                    }
                }
            }
        }
        // After the loop, check to display any remaining values
        if (!empty($lastParamName)) {
            $this->setX(60); // Set X position to start displaying values
            $this->SetFont('Arial', '', 10);
            // Join accumulated values with spaces and display
            $this->Cell(0, 5, implode('         ', $accumulatedValues), 0, 0, 'L');
        }
        $this->Ln(3);
    }
    //.....Main test content end....//

    //....footer set last page...//
    function AddLastPage()
    {
        $this->isLastPage = true;
    } //footer end..///

}
