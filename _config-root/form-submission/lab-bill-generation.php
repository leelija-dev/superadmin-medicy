<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'utility.class.php';


$SubTests        = new SubTests;
$Doctors         = new Doctors;
$Patients        = new Patients;
$LabBilling      = new LabBilling;
$LabBillDetails  = new LabBillDetails;
$Utility         = new Utility;


if (isset($_GET['billId'])) {
    $billId = $_GET['billId'];
    $billId = url_dec($billId);

    $labBillData = json_decode($LabBilling->labBillDisplayById($billId)); // lab bill data

    $testDate = $Utility->convertDateFormat($labBillData->data->test_date); // test date

    if ($labBillData->status) {
        $patientColumn = 'patient_id';
        $patientData = json_decode($Patients->chekPatientsDataOnColumn($patientColumn, $labBillData->data->patient_id, $adminId));

        if ($labBillData->data->refered_doctor != 'Self') {
            $docColumn = 'doctor_id';
            $docData = json_decode($Doctors->chekDataOnColumn($docColumn, $labBillData->data->refered_doctor, $adminId));

            $doctorName  = $docData->data->doctor_name;
            $doctorReg  = $docData->data->doctor_reg_no;
        } else {
            $doctorName = 'SELF';
            $doctorReg = '';
        }

        $payable     = $labBillData->data->total_after_discount;
        $patientName = $patientData->data->name;
        $patientPhno = $patientData->data->phno;
        $patientAge  = $patientData->data->age;

        $paidAmount     = $labBillData->data->paid_amount;
        $dueAmount      = $labBillData->data->due_amount;
        $dicountAmount  = $labBillData->data->discount;
        $billDate       = $labBillData->data->bill_date;

        $labBillDetailsData = json_decode($LabBillDetails->billDetailsById($billId));

        if ($labBillDetailsData->status) {
            $labBillDetailsData = $labBillDetailsData->data;

            $discArray = [];
            $amountArray = [];
            $amountAfterDisc = [];

            foreach ($labBillDetailsData as $detailsData) {
                array_push($discArray, $detailsData->percentage_of_discount_on_test);
                array_push($amountArray, $detailsData->test_price);
                array_push($amountAfterDisc, $detailsData->price_after_discount);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Lab Test Bill</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap/5.3.3/dist/css/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/receipts.css">
</head>

<body>
    <div class="custom-container">
        <div class="custom-body <?= $payable == $paidAmount ? "paid-bg" : ''; ?>">
            <div class="card-body ">
                <div class="row mt-2">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;position: absolute;" src="<?= $healthCareLogo ?>" alt="Medicy">
                    </div>
                    <div class="col-sm-8 ps-4">
                        <h4 class="text-start mb-1"><?php echo $healthCareName; ?></h4>
                        <p class="text-start mb-0" style="margin-top: -5px;">
                            <small><?php echo $healthCareAddress1 . ', ' . $healthCareAddress2 . ', ' . $healthCareCity . ', ' . $healthCarePin; ?></small>
                        </p>

                        <p class="text-start" style="margin-top: -5px; margin-bottom: 2px;">
                            <small><?php echo 'M: ' . $healthCarePhno . ', ' . $healthCareApntbkNo; ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 invoice-info">
                        <p class="my-0"><strong>Invoice</strong></p>
                        <p>#<?= $billId; ?></p>
                        <p><?= formatDateTime($billDate) ?></p>
                    </div>
                </div>
            </div>
            <hr class="mt-1 mb-0" style="height:1px; opacity: .3;">
            <div class="row my-1">
                <div class="col-sm-6 my-0">
                    <p style="margin-top: -3px; margin-bottom: 0px;"><small><b>Patient: </b>
                            <?php echo $patientName . ', <b>Age:</b> ' . $patientAge; ?></small></p>
                    <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>M:</b>
                            <?php echo $patientPhno;
                            echo ', <b>Test date:</b> ' . $testDate; ?></small></p>
                </div>
                <div class="col-sm-6 my-0">
                    <p class="text-end" style="margin-top: -3px; margin-bottom: 0px;"><small><b>Refered By:</b>
                            <?php echo $doctorName; ?></small></p>
                    <p class="text-end" style="margin-top: -5px; margin-bottom: 0px;">
                        <small><?php if ($doctorReg != NULL) {
                                    echo '<b>Reg:</b> ' . $doctorReg;
                                } ?></small>
                    </p>
                </div>

            </div>

            <hr class="my-0" style="height:1px; opacity: .3;">

            <!-- table heading -->
            <div class="row py-1 mx-0">
                <div class="col-sm-2 ps-4">
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
            </div>
            <!--/end table heading -->

            <hr class="my-0" style="height:1px;opacity: .3;">

            <div class="row mx-0">
                <?php
                $slno = 1;
                $subTotal = floatval(00.00);
                $mrpOnTotal = 0;

                $labBillDetailsData = json_decode($LabBillDetails->billDetailsById($billId));
                if ($labBillDetailsData->status) {
                    $labBillDetailsData = $labBillDetailsData->data;

                    for ($i = 0; $i < count($labBillDetailsData); $i++) {
                        $testIds  = $labBillDetailsData[$i]->test_id;

                        $showSubTest = $SubTests->showSubTestsId($testIds);
                        // print_r($showSubTest);

                        $testName = $showSubTest[0]['sub_test_name'];
                        $testPrice = $showSubTest[0]['price'];

                        $disc   = $discArray[$i];
                        $amount = $amountAfterDisc[$i];

                        $mrpOnTotal += $amountArray[$i];

                        if ($slno > 1) {
                            echo '<hr style="border-top: 1px dashed #8c8b8b; margin: 4px auto;">';
                        }

                        echo '
                        <div class="col-sm-2 ps-4 my-0">
                                    <small>' . $slno . '</small>
                                </div>
                                <div class="col-sm-4 my-0">
                                    <small>' . $testName . '</small>
                                </div>
                                <div class="col-sm-2">
                                    <small>' . $testPrice . '</small>
                                </div>
                                <div class="col-sm-2">
                                    <small>' . $disc . '</small>
                                </div>
                                <div class="col-sm-2 text-end my-0">
                                    <small>' . $amount . '</small>
                                </div>';
                        $slno++;
                        $subTotal = floatval($subTotal + $amount);
                    }
                }

                $overAllDiscPercent = floatval(floatval($mrpOnTotal) - floatval($subTotal)) * 100 / floatval($mrpOnTotal);
                $overAllDiscPercent = number_format(floatval($overAllDiscPercent), 2);
                $overallLessAmount = floatval($mrpOnTotal) - floatval($subTotal);

                ?>

            </div>

            <div class="footer">
                <hr calss="my-0" style="opacity: .3;">

                <!-- table total calculation -->
                <div class="row my-0">
                    <div class="col-sm-8 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total Amount:</small></p>
                    </div>
                    <div class="col-sm-4 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small>₹<?php echo floatval($subTotal); ?></small>
                        </p>
                    </div>

                    <?php
                    echo '
                    <div class="col-sm-8 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Less Amount:</small></p>
                    </div>
                    <div class="col-sm-4 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small>₹ ' . $dicountAmount . '</small>
                        </p>
                    </div>';

                    if ($dueAmount != NULL && $dueAmount > 0) {
                        echo '
                    <div class="col-sm-8 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Due Amount:</small></p>
                    </div>
                    <div class="col-sm-4 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small>₹ ' . $dueAmount . '</small>
                        </p>
                    </div>';
                    }
                    ?>

                    <div class="col-sm-8 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>Paid Amount:</b></small></p>
                    </div>
                    <div class="col-sm-4 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><b>₹<?php echo floatval($paidAmount); ?></small></b>
                        </p>
                    </div>
                </div>
                <hr style="opacity: .3;">
            </div>

        </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <!-- <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button> -->
        <a class="btn btn-primary shadow mx-2" href="../../test-appointments.php">Go Back</a>
        <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
    </div>
    </div>
    <?php



    ?>
</body>
<script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>

</html>