<?php

use function PHPSTORM_META\type;

require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'report-generate.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';

$billId  = $_GET['bill-id'];
$LabReport          = new LabReport();
$LabBilling         = new LabBilling();
$LabBillDetails     = new LabBillDetails();

$labBillingData     = $LabBilling->labBillDisplayById($billId); ///
$labBillingDetails  = $LabBillDetails->billDetailsById($billId); //labBillingDetails
$showpatient        = $LabReport->patientDatafetch($labBillingData[0]['patient_id']);
$billId             = $labBillingData[0]['bill_id'];
$patientId          = $labBillingData[0]['patient_id'];




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $addedeReport = $LabReport->labReportAdd($billId, $patientId, NOW, $adminId);
    $reportId       = $addedeReport['insert_id'];
    $reportStatus   = $addedeReport['result'];

    // print_r($reportId);
    if ($reportStatus) {
        $testIds    = $_POST['testId'];
        $testValue  = $_POST['values'];
        $unitValues = $_POST['unitValues'];
        if(is_array($testValue))
        foreach ($testValue as $index => $value) {
            $unitValue = $unitValues[$index];
            $testId = $testIds[$index];

            $labReportAdd = $LabReport->labReportDetailsAdd($value, $unitValue, $testId, intval($reportId));
            if (!$labReportAdd) {
                $errMsg = "Something is wrong with the value : {$unitValue}";
                break;
            }
        }
    }

    if ($labReportAdd) {
        header('Location: lab-report.php?bill_id='.base64_encode($billId));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lab Report Generate</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>/sb-admin-2.min.css" rel="stylesheet">

    <link href="<?php echo PLUGIN_PATH ?>/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Sweet Alert Link  -->
    <script src="<?php echo JS_PATH ?>/sweetAlert.min.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include SUP_ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include SUP_ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 booked_btn">
                            <?php if (isset($errMsg)) {?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $errMsg ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php } ?>
                            <form method="POST" action="">
                                <div class="card-body">
                                    <?php
                                    $showpatient = json_decode($showpatient);
                                    if ($showpatient !== null) {
                                        $patientName = isset($showpatient->name)   ? $showpatient->name   : 'N/A';
                                        $patientAge  = isset($showpatient->age)    ? $showpatient->age    : 'N/A';
                                        $patientSex  = isset($showpatient->gender) ? $showpatient->gender : 'N/A';
                                    }
                                    $testDate = $labBillingData[0]['test_date'];
                                    ?>
                                    <div
                                        style="display: flex; justify-content:space-between; align-items: center;flex-wrap: wrap;">
                                        <h6><b>Patient Name:</b> <?php echo $patientName; ?></h6>
                                        <h6><b>Age:</b> <?php echo $patientAge; ?></h6>
                                        <h6><b>Sex:</b> <?php echo $patientSex; ?></h6>
                                        <h6><b>Test Date:</b> <?php echo $testDate; ?></h6>
                                    </div>

                                    <hr class="sidebar-divider">

                                    <div>

                                        <?php
                                        $unitCounts = array();
                                        if(is_array($labBillingDetails))
                                        foreach ($labBillingDetails as $index => $test) {
                                            $testId = $test['test_id'];
                                            $showTestName = $LabReport->patientTest($testId);
                                            // print_r($showTestName);
                                            $showTestName = json_decode($showTestName);
                                            
                                            $testId = $showTestName->id;
                                            $subTestName = $showTestName->sub_test_name;
                                            $unitNames = $showTestName->unit;
                                            
                                            echo "<div style='margin:5px 0px 10px 0px;width:100%;heigh:auto;padding:10px;box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;'>";
                                            echo "<div>
                                                    <div style='width:40%; margin-left:20px;'>$subTestName</div>";
                                            if (!empty($unitNames)) {

                                                $unitValues = explode(',', $unitNames);    // Split the unitNames by comma and store them in an array

                                                foreach ($unitValues as $unitValue) {
                                                    $unitValue = trim($unitValue);          // Trim to remove any leading or trailing whitespace
                                                    // echo $unitValue;
                                                    if (isset($unitCounts[$unitValue])) {   // Count the occurrences of each unit value
                                                        $unitCounts[$unitValue]++;
                                                    } else {
                                                        $unitCounts[$unitValue] = 1;
                                                    }

                                                    // Generate input boxes based on the count of unit values
                                                    for ($i = 0; $i < $unitCounts[$unitValue]; $i++) {
                                                        echo "<div class='d-flex justify-content-end' style='margin-left: 50%;'>";
                                                        echo "<input type='text' name='values[]' placeholder='$unitValue' required style='width:200px; margin-right:20px; border: none; border-bottom: 1px solid #000; padding: 5px; box-sizing: border-box; outline: none; background-color: transparent;' onfocus='this.style.borderBottom=\"2px solid #000\";' onblur='this.style.borderBottom=\"1px solid #000\";'>";
                                                        echo "<input type='hidden' name='unitValues[]' value='$unitValue'>";
                                                        echo "<input type='hidden' name=' testId[]' value=' $testId'>";
                                                        echo "</div>";
                                                    }
                                                }
                                            }
                                            echo "</div>";
                                            echo "</div>";
                                        }
                                        ?>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" id="generateReport"
                                            class="btn btn-primary btn-sm">Generate Report</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <?php include SUP_ROOT_COMPONENT . 'footer-text.php'; ?>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->






    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>/jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>/bootstrap-js-4/bootstrap.bundle.min.js"></script>




    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo PLUGIN_PATH ?>/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo JS_PATH ?>/demo/datatables-demo.js"></script>

</body>

</html>