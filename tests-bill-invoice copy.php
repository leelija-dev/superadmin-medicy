<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../php_control/sub-test.class.php';
require_once '../php_control/doctors.class.php';
require_once '../php_control/labAppointments.class.php';
require_once '../php_control/hospital.class.php';



//  INSTANTIATING CLASS
$SubTests        = new SubTests();
$Doctors         = new Doctors();
$LabAppointments = new LabAppointments();
$HelthCare       = new HealthCare();


//variable declreation
$totalAmount = 0;


if (isset($_POST['bill-generate'])) {
    // echo '<pre>';
    // print_r($_POST);
    $testIds         = $_POST['testId'];
    $testDisc        = $_POST['disc'];
    $testAmount      = $_POST['amountOfTest'];
    $totalAmount     = $_POST['total'];
    $paidAmount      = $_POST['paid_admount'];
    $patientId       = $_POST['patientId'];
    $patientName     = $_POST['patientName'];
    $patientAge      = $_POST['patientAge'];
    $patientGender   = $_POST['patientGender'];
    $patientPhno     = $_POST['patientPhnNo'];
    $testDate        = $_POST['patientTestDate'];
    $docId           = $_POST['prefferedDocId'];
    $referedDoc      = $_POST['refferedDocName'];

    // echo $patientId;
    
    $testDiscBck   = $testDisc;
    $testAmountBck = $testAmount;


    foreach ($testIds as $subTestId) {
        if($subTestId != ''){
            $showSubTest = $SubTests->showSubTestsId($subTestId);
            foreach ($showSubTest as $rowTest) {
                $testId[]    = $rowTest['id'];
                $testName  = $rowTest['sub_test_name'];
                $testPrice = $rowTest['price'];

                $prices[] = $testPrice;
                // $totalAmount = $totalAmount + $testPrice;
            }
        }
    }

    $totalBill = 0;
    foreach ($testAmount as $singletestAmount) {
                $totalBill = $totalBill + $singletestAmount;
    }


    $testId     = implode(', ', $testId);
    $prices     = implode(', ', $prices);
    $testDisc   = implode(', ', $testDisc);
    $testAmount = implode(', ', $testAmount);

    // Bill id/ invoice id generation
    $totalLabAppointments = $LabAppointments ->showLabAppointments();
    if ($totalLabAppointments == NULL) {
        $billId = 1;
    }else{
        foreach ($totalLabAppointments as $rowLabAppointments) {
            $billId = $rowLabAppointments['bill_id'];
            $billId = $billId+1;
        }
    }
    if ($billId < 10) {
        $billId = '0'.$billId;
    }

    //Billing Date
    $billingDate = date("d-m-Y");

    //CGST & SGST Generation
    // $cgstPercentage = 5;
    // $cgst = $cgstPercentage/100*$totalBill;
    
    // $sgstPercentage = 5;
    // $sgst = $sgstPercentage/100*$totalBill;

    //paid amount generation
    // $paidAmount = $totalBill+$cgst+$sgst;

    // CGST & SGST Generation
    // $cgstPercentage = 5;
    $cgst = 0;
    
    // $sgstPercentage = 5;
    $sgst = 0;

 

    if ($docId != NULL) {
        //function calling
        $showDoctorById = $Doctors->showDoctorById($docId);
        foreach($showDoctorById as $rowDoctor){
            $doctorName = $rowDoctor['doctor_name'];
            $doctorReg  = $rowDoctor['doctor_reg_no'];
        }
    }
    
    if ($referedDoc != NULL) {
        $doctorName = $referedDoc;
        $docId      = $referedDoc;
        $doctorReg  = NULL;
    }

    $addLabtest = $LabAppointments->addLabtestByInternal($billId, $patientId, $docId, $testId, $prices, $testDisc, $testAmount, $totalBill, $cgst, $sgst, $paidAmount, $testDate, $billingDate);
    if ($addLabtest) {
        echo "<script>alert('Bill Generated!');</script>";
    }else {
        echo "<script>alert('Bill Generation Failed!');</script>";
    }

}else {
    header("Location: lab-billing.php");
    exit;
}






$showhelthCare = $HelthCare->showhealthCare($adminId);
foreach ($showhelthCare as $rowhelthCare) {
    $healthCareName     = $rowhelthCare['hospital_name'];
    $healthCareAddress1 = $rowhelthCare['address_1'];
    $healthCareAddress2 = $rowhelthCare['address_2'];
    $healthCareCity     = $rowhelthCare['city'];
    $healthCarePIN      = $rowhelthCare['pin'];
    $healthCarePhno     = $rowhelthCare['hospital_phno'];
    $healthCareApntbkNo = $rowhelthCare['appointment_help_line'];

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tests Bill</title>
    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="../css/custom/test-bill.css">
</head>


<body>
    <div class="custom-container">
        <div class="custom-body">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="../images/logo-p.jpg"
                            alt="Medicy">
                    </div>
                    <div class="col-sm-8">
                        <h4 class="text-start my-0"><?php echo $healthCareName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareAddress1.', '.$healthCareAddress2.', '.$healthCareCity.', '.$healthCarePIN; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -8px; margin-bottom: 0px;">
                            <small><?php echo 'M: '.$healthCarePhno.', '.$healthCareApntbkNo; ?></small></p>

                    </div>
                    <div class="col-sm-3 border-start border-primary">
                        <p class="my-0">Invoice</p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id: <?php echo $billId; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Billing Date:
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
                </div><div class="col-sm-2">
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
                    foreach ($testIds as $subTestId) {
                        if($subTestId != ''){
                            $showSubTest = $SubTests->showSubTestsId($subTestId);
                            foreach ($showSubTest as $rowTest) {
                                $testName = $rowTest['sub_test_name'];
                                $testPrice = $rowTest['price'];

                                
                                $disc   = array_shift($testDiscBck);
                                $amount = array_shift($testAmountBck);

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
                                            <small><b>'.$disc.'</b></small>
                                        </div>
                                        <div class="col-sm-2 text-end my-0">
                                            <small>'.$amount.'</small>
                                        </div>';
                                $slno++;
                                $subTotal = floatval($subTotal + $amount);
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
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>₹<?php echo floatval($subTotal); ?></small></b></p>
                    </div>
                    <div class="col-sm-8 mb-3 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Paid Amount:</small></p>
                    </div>
                    <div class="col-sm-4 mb-3 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>₹<?php echo floatval($paidAmount); ?></small></b></p>
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
            <!-- <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button> -->
            <a class="btn btn-primary shadow mx-2" href="lab-tests.php">Go Back</a>
            <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
        </div>
    </div>
    <?php



    ?>
</body>
<script src="../js/bootstrap-js-5/bootstrap.js"></script>

</html>