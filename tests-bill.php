<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'sub-test.class.php';

$billId = $_GET['bill_id'];

//  INSTANTIATING CLASS
$SubTests = new SubTests();


if (isset($_POST['bill-generate'])) {
    // echo '<pre>';
    // print_r($_POST);
    $idList          = $_POST['testId'];

    $patientId       = $_POST['patientId'];
    $patientName     = $_POST['patientName'];
    $patientAge      = $_POST['patientAge'];
    $patientGender   = $_POST['patientGender'];
    $patientPhno     = $_POST['patientPhnNo'];
    $patientTestDate = $_POST['patientTestDate'];
    $patientDoctor   = $_POST['prefferedDocId'];

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
        <div class="card-body ">
            <div class="row">
                <div class="col-sm-2">
                    <img class="float-end" style="height: 120px; width: 130px;" src="../images/logo-p.jpg" alt="Medicy">
                </div>
                <div class="col-sm-6">
                    <h2 class="text-start mt-4">Medicy Healthcare</h2>
                    <h6 class="text-start">Thanar More, Daulatabad, Murshidabad, Murshidabad, 742302</h6>
                </div>
                <div class="col-sm-4">
                    <p class="text-end mb-0" style="margin-top: 20px;">Bill id: <b>MI000000</b></p>
                    <p class="text-end my-0">Patient ID: <b><?php echo $patientId;?></b> </p>
                    <p class="text-end my-0">Billing Date: <b>12/12/2022</b></p>
                </div>
            </div>
        </div>
        <hr class="mb-1 mt-0">
        <div class="row my-0">
            <div class="col-sm-6 my-0">Bill ID: 087VTY6RDT</div>
            <div class="col-sm-6 my-0 text-end">Test Date: <?php echo $patientTestDate;?></div>
        </div>
        <hr class="my-1">
        <div class="row">
            <div class="col-sm-6">
                <b>Patient:</b>
                <p class="mb-0">Name: <?php echo $patientName; ?></p>
                <p class="mb-0">Age: <?php echo $patientAge;?>, Sex: <?php echo $patientGender;?></p>
                <p>Contact: <?php echo $patientPhno;?></p>
            </div>
            <div class="col-sm-6">
                <b>Prefered Doctor:</b>
                <p class="mb-0">Dr. Subhash Mukhopaddhay</p>
                <p class="mb-0">MBBS,MD(CN) DCH. PGPN(USA), Child Specialist</p>
                <p>Reg: WBCM-786558</p>
            </div>
            <!-- <hr> -->
            <div class="row">
                <!-- table heading -->
                <div class="col-sm-3 text-center">
                    <b>SL. NO.</b>
                </div>
                <div class="col-sm-6">
                    <b>Description</b>
                </div>
                <div class="col-sm-3 text-end">
                    <b>Amount</b>
                </div>
                <!--/end table heading -->
                <hr class="my-1">
                <?php
                $slno = 1;
                $subTotal = 00.00;
                    foreach ($idList as $subTestId) {
                        if($subTestId != ''){
                            $showSubTest = $SubTests->showSubTestsId($subTestId);
                            foreach ($showSubTest as $rowTest) {
                                $testName = $rowTest['sub_test_name'];
                                $testPrice = $rowTest['price'];


                                echo '<div class="col-sm-3 text-center mb-1">
                                            '.$slno.'
                                        </div>
                                        <div class="col-sm-6 mb-1">
                                        '.$testName.'
                                        </div>
                                        <div class="col-sm-3 text-end mb-1">
                                            '.$testPrice.'
                                        </div>';
                                $slno++;
                                $subTotal = $subTotal + $testPrice;
                            }
                            
                            
                        }
                    }
                ?>

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="row">
                <!-- table total calculation -->
                <div class="col-sm-8 text-end">
                    Sub Total:
                </div>
                <div class="col-sm-4 text-end">
                    <?php echo $subTotal; ?>
                </div>
                <div class="col-sm-8 text-end">
                    CGST:
                </div>
                <div class="col-sm-2 text-end">
                    <?php
                            $cgst = 5;
                            echo $cgst.'%';
                        ?>
                </div>
                <div class="col-sm-2 text-end">
                    <?php

                        $cgst = $cgst/100*$subTotal;
                        echo $cgst;

                        ?>
                </div>
                <div class="col-sm-8 text-end">
                    SGST:
                </div>
                <div class="col-sm-2 text-end">
                    <?php
                            $sgst = 5;
                            echo $sgst.'%';
                        ?>
                </div>
                <div class="col-sm-2 text-end">
                    <?php
                        $sgst = $sgst/100*$subTotal;
                        echo $sgst;
                        ?>
                </div>
                <div class="col-sm-8 text-end">
                    <b>Total:</b>
                </div>
                <div class="col-sm-4 text-end">
                    <?php
                            $total = $subTotal + $sgst + $cgst;
                            echo '<b>'.$total.'</b>';
                        ?>
                    <!-- <b></b> -->
                </div>

                <!--/end table total calculation -->
            </div>
        </div>
        <!-- <div class="footer">
            <div class="row  border border-primary mt-4 pt-2 pb-0">
                <div class="col-md-4 custom-width-name mb-0">
                    <ul>
                        <li class=" list-unstyled">
                            <img id="healthcare-name-box" class="pe-2" src="../employee/partials/hospital.png"
                                alt="Healt Care" style="width:28px; height:20px;" /><?php echo 'Medicy Healthcare' ?>
                        </li>
                    </ul>
                </div>

                <div class="col-md-4 custom-width-email mb-0">
                    <ul>
                        <li class="list-unstyled"><img id="email-box" class="pe-2"
                                src="../employee/partials/email-logo.png" alt="Email"
                                style="width:28px; height:20px;" /><?php echo 'medicyhealthcare@gmail.com' ?>
                        </li>
                    </ul>
                </div>

                <div class="col-md-4 custom-width-number mb-0">
                    <ul>
                        <li class="list-unstyled"><img id="number-box" class="pe-2"
                                src="../employee/partials/call-logo.png" alt="Contact"
                                style="width:28px; height:20px;" />
                            <span><?php echo '7654321097 / 76543210975'?></span>
                        </li>
                    </ul>
                </div>
            </div>
            <p class="text-center">NOTE:- Incase of any medical emergency, please visit your nearest hospital</p>
        </div> -->
        <div class="justify-content-center print-sec d-flex my-5">
            <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button>
            <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
        </div>

    </div>
</body>
<script src="../js/bootstrap-js-5/bootstrap.js"></script>

</html>