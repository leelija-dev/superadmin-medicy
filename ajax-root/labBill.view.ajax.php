<?php
require_once dirname(__DIR__) . '/config/constant.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'utility.class.php';

$billId = $_GET['billId'];

$LabBilling         = new LabBilling;
$LabBillDetails     = new LabBillDetails;
$Pathology          = new Pathology;
$Patients           = new Patients;
$Doctors            = new Doctors;

$labBil      = json_decode($LabBilling->labBillDisplayById($billId));


if ($_GET['billId']) {

    // $billId  = url_dec($_GET['billId']);
    $billDetails = json_decode($LabBilling->labBillDisplayById($billId));
    if ($billDetails->status) {
        $billDetails = $billDetails->data;
        // print_r($billDetails);
        $billDate           = $billDetails->bill_date;
        $patientId          = $billDetails->patient_id;
        $refBy              = $billDetails->refered_doctor;
        $sampCltDate        = $billDetails->sample_collection_date;
        $totalAmount        = $billDetails->total_amount;
        $discountOnTotal    = $billDetails->discount;
        $billDetails->total_after_discount;
        $billDetails->cgst;
        $billDetails->sgst;
        $paidAmount         = $billDetails->paid_amount;
        $dueAmount          = $billDetails->due_amount;
        $billStatus         = $billDetails->status;
        $billDetails->added_by;
        $billDetails->added_on;
        if (!empty($billDetails->update_by)) {
            $billDetails->update_by;
        }
        if (!empty($billDetails->update_on)) {
            $billDetails->update_on;
        }
        $billDetails->admin_id;



        $patientDetails = json_decode($Patients->patientsDisplayByPId($patientId));
        // print_r($patientDetails);

        $patientName    = $patientDetails->name;
        $patientPhno    = $patientDetails->phno;
        $patientAge     = $patientDetails->age;
        $patientGender  = $patientDetails->gender;


        if ($refBy == 'Self') {
            $doctorName = 'Self';
        } else {
            if ($refBy != NULL) {
                $doctorDetails = $Doctors->showDoctorNameById($refBy);
                $doctorDetails = json_decode($doctorDetails);
                if ($doctorDetails->status == 1) {
                    $refBy = $doctorDetails->data->doctor_name;
                }
            }
        }


        $allTests = $LabBillDetails->testsNum($billId);
    } else {
        echo "Something is wrong";
        exit;
    }
}
// print_r($allTests);
// header("Location: lab-billing.php");
// exit;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Dashboard - <?= $HEALTHCARENAME ?></title>

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="<?= CSS_PATH; ?>sb-admin-2.css" type="text/css">
    <link rel="stylesheet" href="<?= PLUGIN_PATH; ?>fontawesome-free/css/all.min.css" type="text/css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/invoice.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>spaces.css" type="text/css" />
</head>

<body id="page-top">

    <div class="container-fluid labBill-Container">
        <div class=" bg-light position-relative test-modal-body p-3">
            <div class="container small">
                <div class="row justify-content-between">
                    <!-- Left column for patient details -->
                    <div class="col-12 col-lg-6">
                        <table class="mr-lg-auto">
                            <tr>
                                <td><b>Patient</b></td>
                                <td class="px-3">:</td>
                                <td><?= $patientName ?></td>
                            </tr>
                            <tr>
                                <td><b>Age/Gender</b></td>
                                <td class="px-3">:</td>
                                <td><?= $patientAge; ?> / <?= $patientGender; ?></td>
                            </tr>
                            <tr>
                                <td><b>Mobile</b></td>
                                <td class="px-3">:</td>
                                <td><?= $patientPhno; ?></td>
                            </tr>
                            <tr>
                                <td><b>Referred By</b></td>
                                <td class="px-3">:</td>
                                <td><?= $refBy; ?></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Right column for bill details -->
                    <div class="col-12 col-lg-6">
                        <table class="ml-lg-auto">
                            <tr>
                                <td><b>Patient ID</b></td>
                                <td class="px-3">:</td>
                                <td><?= $patientId; ?></td>
                            </tr>
                            <tr>
                                <td><b>Bill ID</b></td>
                                <td class="px-3">:</td>
                                <td><?= $billId; ?></td>
                            </tr>
                            <tr>
                                <td><b>Bill Date</b></td>
                                <td class="px-3">:</td>
                                <td><?= formatDateTime($billDate) ?></td>
                            </tr>
                            <tr>
                                <td><b>Collection Date</b></td>
                                <td class="px-3">:</td>
                                <td><?= formatDateTime($sampCltDate); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr class="mb-0 mt-2">
            </div>


            <hr class="mb-0 mt-2" style="height:1px;">
            <div class=" sl-no lg-overflow-hidden overflow-sm-auto">
                <div class="row  flex-nowrap ">
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

                <?php
                $slno = 1;
                $subTotal = floatval(00.00);
                foreach ($allTests as $eachTest) {
                    // print_r($eachTest);
                    $eachTestId = $eachTest['test_id'];
                    // echo $eachTestId;
                    $testPrice  = $eachTest['test_price'];
                    $discount   = $eachTest['percentage_of_discount_on_test'];
                    $amount     = $eachTest['price_after_discount'];

                    $eachTest = json_decode($Pathology->showTestById($eachTestId));
                    if ($eachTest->status) {
                        $eachTestName  = $eachTest->data->name;
                    }

                    // if ($slno > 1) {
                    // // echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0;align-items: center;">';
                    // }

                ?>
                    <div class="row rw flex-nowrap">
                        <div class="col-sm-2 text-center my-0">
                            <small><?= $slno ?></small>
                        </div>
                        <div class="col-sm-4 my-0">
                            <small><?= $eachTestName; ?></small>
                        </div>
                        <div class="col-sm-2">
                            <small><?= $testPrice; ?></b></small>
                        </div>
                        <div class="col-sm-2">
                            <small><?= $discount; ?></small>
                        </div>
                        <div class="col-sm-2 text-end my-0">
                            <small><?= $amount; ?></small>
                        </div>

                    </div>
                <?php
                    $slno++;
                    $subTotal = floatval($subTotal + $amount);
                } ?>
            </div>

            <div class="footer labBill-footer">
                <hr calss="my-0" style="height: 1px;">

                <!-- table total calculation -->
                <div class="w-100 w-md-50 ml-auto">

                    <div class="row my-0 fr flex-nowrap">
                        <div class="col-sm-8 mt-0 mb-1 text-end">
                            <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total Amount:</small></p>
                        </div>
                        <div class="col-sm-4 mt-0 mb-1 text-end">
                            <p style="margin-top: -5px; margin-bottom: 0px;">
                                <small><b>₹<?= floatval($totalAmount); ?></small></b>
                            </p>
                        </div>
                    </div>
                    <div class="row my-0 fr flex-nowrap">
                        <div class="col-sm-8 mt-0 mb-1 text-end">
                            <p style="margin-top: -5px; margin-bottom: 0px;"><small>Less Amount:</small></p>
                        </div>
                        <div class="col-sm-4 mt-0 mb-1 text-end">
                            <p style="margin-top: -5px; margin-bottom: 0px;">
                                <small><b>₹ <?= $discountOnTotal ?></small></b>
                            </p>
                        </div>
                    </div>
                    <div class="row my-0 fr flex-nowrap">
                        <div class="col-sm-8 mt-0 mb-1 text-end">
                            <p style="margin-top: -5px; margin-bottom: 0px;"><small>Due Amount:</small></p>
                        </div>
                        <div class="col-sm-4 mt-0 mb-1 text-end">
                            <p style="margin-top: -5px; margin-bottom: 0px;">
                                <small><b>₹ <?= $dueAmount ?></small></b>
                            </p>
                        </div>
                    </div>

                    <div class="row my-0 fr flex-nowrap">
                        <div class="col-sm-8 mb-3 text-end">
                            <p style="margin-top: -5px; margin-bottom: 0px;"><small>Paid Amount:</small></p>
                        </div>
                        <div class="col-sm-4 mb-3 text-end">
                            <p style="margin-top: -5px; margin-bottom: 0px;">
                                <small><b>₹<?= floatval($paidAmount); ?></small></b>
                            </p>
                        </div>
                    </div>
                    <!--/end table total calculation -->
                </div>

            </div>
        </div>
        <div class="text-right mt-2">
            <!-- <button type="button" class="btn btn-sm btn-primary mx-2" disabled>Send</button> -->
            <?php
            if ($billStatus != "Cancelled") {

                echo '<a class="btn btn-sm btn-primary mx-2" href="' . URL . 'edit-lab-billing.php?invoice=' . url_enc($billId) . '">Edit</a>';
            } else {
                echo '<button type="button" class="btn btn-sm btn-danger mx-2">Bill Cancelled</button>';
            }
            ?>
            <a class="btn btn-sm btn-success shadow mx-2 <?= $billStatus == "Cancelled" ? 'disabled' : ''; ?>" href="<?= URL . "invoices/lab-invoice.php?billId=" . url_enc($billId) ?>" onclick="openPrint(this.href); return false;">Print</a>
        </div>
    </div>
</body>

</html>