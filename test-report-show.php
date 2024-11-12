<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';

require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'doctor.category.class.php';
require_once CLASS_DIR . 'report-generate.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'patients.class.php';

$reportId = $_GET['id'];

// $patientId = $_GET['patient_id'];

$LabReport     = new LabReport();
$Patients      = new Patients();
$LabBilling    = new LabBilling();
$LabBillDetails     = new LabBillDetails();


$labReportDetailbyId = $LabReport->labReportDetailbyId($reportId);
// echo $labReportDetailbyId;
$testIds = [];
$labReportDetailbyId = json_decode($labReportDetailbyId);
if (is_array($labReportDetailbyId) && !empty($labReportDetailbyId)) {
    foreach ($labReportDetailbyId as $report) {
        $testIds[] = $report->test_id;
        $reportId = $report->report_id;
    }
}

/// find patient id ///
$findPatienttId = $LabReport->labReportbyReportId($reportId);
// echo $findPatienttId;
$findPatienttId = json_decode($findPatienttId, true);
if ($findPatienttId !== null) {
    $patient_id = $findPatienttId['patient_id'];
    // echo $patient_id;
    $billId     = $findPatienttId['bill_id'];
    // echo "<div style='width:40%; margin-left:20px;' >  $billId</div>";
} //end..//

/// geting for test_date ///
$labBillingDetails   = $LabBilling->labBillDisplayById($billId); /// geting for test_date
if ($labBillingDetails !== 0) {
    foreach ($labBillingDetails as $result) {
        $testDate = $result['test_date'];
    }
} ///end..

//===find patient details ===//
$patientDatafetch = $LabReport->patientDatafetch($patient_id);
$patientDatafetch = json_decode($patientDatafetch, true);
if ($patientDatafetch !== null) {
    $name       = isset($patientDatafetch['name']) ? $patientDatafetch['name'] : 'N/A';
    $patient_id = isset($patientDatafetch['patient_id']) ? $patientDatafetch['patient_id'] : 'N/A';
    $age        = isset($patientDatafetch['age']) ? $patientDatafetch['age'] : 'N/A';
    $gender     = isset($patientDatafetch['gender']) ? $patientDatafetch['gender'] : 'N/A';
}


?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>lab-report.css">
    <!-- <link rel="stylesheet" href="../css/prescription.css"> -->
    <title>Prescription</title>
</head>

<body>

    <div style="height:100vh;">
        <div id="wave"></div>

        <div style="margin-right: 35px; margin-top: -26px; color:#183697;">
            <h1 class="text-start fw-bold mb-2 mt-4 me-3 d-flex justify-content-end"><?= $healthCareName ?></h1>
        </div>
        <div class="dname" style="display:flex;justify-content: flex-end;align-items: row-reverse;">
            <div style="margin-right: 52px; background: linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(24,54,151,1) 28%); padding:2px; height: 28px; width:347px;">
                <p class="" style="margin-left: 142px;color:#fff">DIAGNOSTIC & POLYCLINIC</p>
            </div>
        </div>
        <div style="display:flex;justify-content:center;align-items:center;color:#183697; margin-top:8px">
            <div>
                <p class="m-0" style="margin:0px 5px 0px 5px;padding: 0px 5px 0px 5px;">
                    <b>Daulatabad, Murshidabad,(W.B.),Pin -742302, Mobile:8695494415/9064390598,Website:www.medicy.in</b>
                </p>
            </div>
        </div>
        <!-- <img src="../images/heartbit.png" alt="" class="my-image"> -->
        <hr class="m-0" style="color: #043277; height:2px; z-index: index -1;">
        <br>
        <div style="display: flex; justify-content:space-around; align-items:flex-start;">
            <div>
                <p class="m-0"><b>Patient's Name :</b> <?php echo $name; ?></p>
                <p class="m-0"><b>Patient id :</b> <?php echo $patient_id ?></p>
                <p class="m-0"><b>Place of collection :</b> LAB </p>
                <p class="m-0"><b> Ref. by :</b> DR. SELF </p>
            </div>
            <div>
                <p class="m-0"><b>Age :</b> <?php echo $age; ?> <b>Sex :</b> <?php echo $gender; ?></p>

                <p class="m-0"><b>Collection Date :</b> <?php $testDate = $testDate;
                                                        $date = date_create($testDate);
                                                        echo date_format($date, "d-m-Y"); ?></p>
                <p class="m-0"><b>Reporting Date :</b> <?php $testDate = $testDate;
                                                        $date = date_create($testDate);
                                                        echo date_format($date, "d-m-Y"); ?></p>
            </div>
            <!-- <small><?php echo $healthCareAddress1 . ', ' . $healthCareAddress2 . ', ' . $healthCareCity . ',<br>' . $healthCareState . ', ' . $healthCarePin; ?></small> -->
        </div>


        <!-- ////////////////// -->
        <div style="box-shadow:none; padding: 0px 30px 40px;" class="card">
            <hr class="mb-0 mt-0" style="color:black;background:black; width:90%; height:5px; margin-left:50px">
            <div style="margin-top:20px; display: flex; justify-content:center; align-items:center">
                <div>
                    <h5><U><b>REPORT OF LIVER FUNCTION TEST</b></U></h5>
                </div>
            </div>
            <div style="height: 450px;">
                <?php
                $labReportDetailbyId = $LabReport->labReportDetailbyId($reportId);
                $labReportDetailbyId = json_decode($labReportDetailbyId);
                echo "<div class='' style='margin:5px 0px 10px 0px;width:100%;heigh:auto;padding:10px; '>";
                // echo "<div style='display: flex; justify-content:space-around; align-items:center' >";

                if (is_array($labReportDetailbyId) && !empty($labReportDetailbyId)) {

                    // $sub_test_names = [];
                    foreach ($testIds as $testId) {
                        $patientTest = $LabReport->patientTest($testId);
                        $decodedData = json_decode($patientTest, true);
                        if ($decodedData !== null) {
                            $sub_test_name = $decodedData['sub_test_name'];
                            echo "<div style='display: flex; justify-content:space-around; align-items:flex-start'>";
                            echo "<div ><p>$sub_test_name</p></div>";
                            echo "<div ><p>: $report->test_value</p></div>";
                            echo "</div>";
                        }
                    }
                    foreach ($labReportDetailbyId as $report) {
                        // echo "<div  style=' width: 40%; '>$report->test_value</div>";
                    }
                }
                echo "</div>";
                ?>
            </div>

            <div>
                <p style="margin-left:85px;margin-bottom:0px;">Reference values are obtained from the literature provided with reagent kit.</p>
                <hr class="mb-0 mt-0" style="color:black;background:black; width:90%; height:5px; margin-left:50px">
            </div>
            <div>
                <div style="display: flex; margin-top:15px; margin-right: 40px; justify-content:flex-end;align-items:center">
                    <div style="margin-right:13%;"><b>***END OF REPORT***</b></div>
                    <div>
                        <p class="m-0">&nbsp;&nbsp;&nbsp;&nbsp;<b>DR. S.BISWAS</b></p>
                        <p class="m-0"><b>Consultant Pathologist(MD)</b></p>
                        <p class="m-0"><b>Reg. No: 59304 (WBMC)</b></p>
                    </div>
                </div>
                <div style="display: flex;justify-content:flex-start;align-items:flex-start; color:#183697; margin-left:50px">
                    <div>
                        <p class="m-0"><small><i><b>A Health Care Unit for :-</b></i></small></p>
                        <p class="m-0"><small><b>Advance Assay, USG & ECHO, Colour Doppler,</b></small></p>
                        <p class="m-0"><small><b>Digital X-Ray, Special X-Ray, OPG, ECG & Eye.</b></small></p>
                    </div>
                    <div style="margin-left:5%;"><small><i><b>Verified by :</b></i></small></div>
                </div>
            </div>
        </div>
        <!-- ////// -->
        <div class="footer"></div>

        <div class="printButton mb-5">
            <button class="btn btn-primary" onclick="history.back()">Go Back</button>
            <button class="btn btn-primary" onclick="window.print()">Print Prescription</button>
        </div>
    </div>
</body>

</html>