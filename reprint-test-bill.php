<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR.'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';

require_once CLASS_DIR.'sub-test.class.php';
require_once CLASS_DIR.'doctors.class.php';
require_once CLASS_DIR.'labBilling.class.php';
require_once CLASS_DIR.'labBillDetails.class.php';
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'patients.class.php';




//  INSTANTIATING CLASS
$LabBilling      = new LabBilling();
$LabBillDetails  = new LabBillDetails();
$SubTests        = new SubTests();
$Doctors         = new Doctors();
$Patients        = new Patients();
// $LabAppointments = new LabAppointments();

if (isset($_GET['bill_id'])) {

    $billId = $_GET['bill_id'];

    $labBil      = $LabBilling->labBillDisplayById($billId);
    foreach ($labBil as $rowlabBil) {
                
        $billId         = $rowlabBil['bill_id'];
        $billingDate    = $rowlabBil['bill_date'];
        $patientId      = $rowlabBil['patient_id'];
        $docId          = $rowlabBil['refered_doctor'];
        $testDate       = $rowlabBil['test_date'];
        $totalAmount    = $rowlabBil['total_amount'];
        $totalDiscount  = $rowlabBil['discount'];
        $afterDiscount  = $rowlabBil['total_after_discount'];
        $cgst           = $rowlabBil['cgst'];
        $sgst           = $rowlabBil['sgst'];
        $paidAmount     = $rowlabBil['paid_amount'];
        $dueAmount      = $rowlabBil['due_amount'];
        $status         = $rowlabBil['status'];
        $addedBy        = $rowlabBil['added_by'];
        $BillOn         = $rowlabBil['added_on'];

    }

    $patient = json_decode($Patients->patientsDisplayByPId($patientId));
        // $patientName    = $patient->name;
        // $patientPhno    = $patient->phno;
        // $patientAge     = $patient->age;
        // $patientGender  = $patient->gender;

        if ($patient === null) {
            // JSON decoding failed
            echo "Error decoding JSON: " . json_last_error_msg();
        } else {
            // Access array elements using square brackets
            $patientName    = isset($patient['name'])   ? $patient['name']   : 'N/A';
            $patientPhno    = isset($patient['phno'])   ? $patient['phno']   : 'N/A';
            $patientAge     = isset($patient['age'])    ? $patient['age']    : 'N/A';
            $patientGender  = isset($patient['gender']) ? $patient['gender'] : 'N/A';
        }

    if (is_numeric($docId)) {
        $showDoctor = $Doctors->showDoctorNameById($docId);
        $showDoctor = json_decode($showDoctor);
        // print_r($showDoctor);
        if($showDoctor-> status == 1){
            foreach ($showDoctor->data as $rowDoctor) {
                $doctorName = $rowDoctor->doctor_name;
                $doctorReg = $rowDoctor->doctor_reg_no;
    
            }
        }
        // foreach ($showDoctor as $rowDoctor) {
        //     $doctorName = $rowDoctor['doctor_name'];
        //     $doctorReg = $rowDoctor['doctor_reg_no'];

        // }
    }else {
        $doctorName = $docId;
        $doctorReg  = NULL;
    }

    
}//eof cheaking post method

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $patientId.'-'.$billId ?></title>
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>/custom/test-bill.css">

</head>


<body>
    <div class="custom-container">
        <div class="custom-body">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="<?= $healthCareLogo?>"
                            alt="Medicy">
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
                    <div class="col-sm-3 border-start border-primary">
                        <p class="my-0">Invoice</p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id: <?php echo $billId; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Date:
                                <?php echo $billingDate;?></small></p>
                    </div>
                </div>
            </div>
            <hr class="my-0" style="height:1px; background: #000000; border: #000000;">
            <div class="row my-0">
                <div class="col-sm-6 my-0">
                    <p style="margin-top: -3px; margin-bottom: 0px;"><small><b>Patient: </b>
                            <?php echo $patientName.', Age: '.$patientAge; ?></small></p>
                    <p style="margin-top: -5px; margin-bottom: 0px;"><small>M:
                            <?php echo $patientPhno; echo ', Test date: '.$testDate;?></small></p>
                </div>
                <div class="col-sm-6 my-0">
                    <p class="text-end" style="margin-top: -3px; margin-bottom: 0px;"><small><b>Refered Doctor:</b>
                            <?php echo $doctorName; ?></small></p>
                    <p class="text-end" style="margin-top: -5px; margin-bottom: 0px;">
                        <small><?php if($doctorReg != NULL){echo 'Reg: '.$doctorReg; } ?></small>
                    </p>
                </div>

            </div>
            <hr class="my-0" style="height:1px;">
            <!-- <div class="row"> -->
            <!-- <hr> -->
            <div class="row">
                <!-- table heading -->
                <div class="col-sm-2 text-center">
                    <small><b>SL. NO.</b></small>
                </div>
                <div class="col-sm-4">
                    <small><b>Description</b></small>
                </div>
                <div class="col-sm-2">
                    <small><b>Price (₹)</b></small>
                </div>
                <div class="col-sm-2">
                    <small><b>Disc (%)</b></small>
                </div>
                <div class="col-sm-2 text-end">
                    <small><b>Amount (₹)</b></small>
                </div>
                <!--/end table heading -->
            </div>
            <hr class="my-0" style="height:1px;">
            <!-- <div syle="min-height: 500px;"> -->

            <div class="row">
                <!--style="min-height: 290px;"-->
                <?php
                $slno = 1;
                $subTotal = floatval(00.00);
                
                $billDetails = $LabBillDetails->billDetailsById($billId);
                if(is_array($billDetails))
                    foreach ($billDetails as $rowDetails) {
                        $subTestId = $rowDetails['test_id'];
                        $testAmount = $rowDetails['price_after_discount'];
                        $testDisc  = $rowDetails['percentage_of_discount_on_test'];
                        // $testDiscBck = $testDisc;
                        // $testAmountBck = $testAmount;


                        

                        if($subTestId != ''){
                            $showSubTest = $SubTests->showSubTestsId($subTestId);
                            foreach ($showSubTest as $rowTest) {
                                $testName = $rowTest['sub_test_name'];
                                $testPrice = $rowTest['price'];

                                
                                // $disc   = array_shift($testDiscBck);
                                // $amount = array_shift($testAmountBck);

                                if ($slno >1) {
                                    echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                                }

                                echo '
                                <div class="col-sm-2 text-center my-0">
                                            <small>'.$slno.'</small>
                                        </div>
                                        <div class="col-sm-4 my-0">
                                            <small>'.$testName.'</small>
                                        </div>
                                        <div class="col-sm-2">
                                            <small>'.$testPrice.'</b></small>
                                        </div>
                                        <div class="col-sm-2">
                                            <small><b>'.$testDisc.'</b></small>
                                        </div>
                                        <div class="col-sm-2 text-end my-0">
                                            <small>'.$testAmount.'</small>
                                        </div>';
                                $slno++;
                                $subTotal = floatval($subTotal + $testAmount);
                            }
                        }
                    }
                ?>

            </div>
            <!-- </div> -->

            <!-- </div> -->
            <div class="footer">
                <hr calss="my-0" style="height: 1px;">
                <div class="row">
                    <!-- table total calculation -->
                    <div class="col-sm-8 mb-3 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total Amount:</small></p>
                    </div>
                    <div class="col-sm-4 mb-3 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><b>₹<?php echo floatval($subTotal); ?></small></b></p>
                    </div>
                    <div class="col-sm-8 mb-3 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Paid Amount:</small></p>
                    </div>
                    <div class="col-sm-4 mb-3 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><b>₹<?php echo floatval($paidAmount); ?></small></b></p>
                    </div>
                    <!-- <div class="col-sm-8 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>CGST:</small></p>
                    </div>
                    <div class="col-sm-2 text-end">
                        <?php
                            // $cgst = 5;
                            // echo '<p style="margin-top: -5px; margin-bottom: 0px;"><small>'.$cgstPercentage.'%</small></p>';
                        ?>
                    </div>
                    <div class="col-sm-2 text-end">
                        <?php

                        // $cgst = $cgst/100*$subTotal;
                        //echo '<p style="margin-top: -5px; margin-bottom: 0px;"><small>'.$cgst.'</small></p>';

                        ?>
                    </div> -->
                    <!-- <div class="col-sm-8 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>SGST:</small></p>
                    </div>
                    <div class="col-sm-2 text-end">
                        <?php
                            // $sgst = 5;
                            //echo '<p style="margin-top: -5px; margin-bottom: 0px;"><small>'.$sgstPercentage.'%</small></p>';
                        ?>
                    </div>
                    <div class="col-sm-2 text-end">
                        <?php
                        // $sgst = $sgst/100*$subTotal;
                        //echo '<p style="margin-top: -5px; margin-bottom: 0px;"><small>'.$sgst.'</small></p>';
                        ?>
                    </div> -->
                    <!-- <div class="col-sm-8 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>Total:</b></small></p>
                    </div>
                    <div class="col-sm-4 text-end">
                        <?php
                            // $total = $subTotal + $sgst + $cgst;
                            //echo '<p style="margin-top: -5px; margin-bottom: 0px;"><small><b>₹'.$paidAmount.'</b></small></p>';
                        ?>
                    </div> -->
                    <!--/end table total calculation -->
                </div>
                <hr style="height: 1px; margin-top: 2px;">
            </div>
        </div>
        <div class="justify-content-center print-sec d-flex my-5">
            <!-- <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button> href="lab-tests.php"-->
            <a class="btn btn-primary shadow mx-2"  onclick="history.back()">Go Back</a>
            <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
        </div>
    </div>
    <?php



    ?>
</body>
<script src="<?php echo JS_PATH ?>/bootstrap-js-5/bootstrap.js"></script>

</html>