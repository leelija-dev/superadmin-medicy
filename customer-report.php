<?php
require_once 'config/constant.php';
require_once SUP_ADM_DIR      . '_config/sessionCheck.php';
require_once CLASS_DIR        . 'dbconnect.php';
require_once SUP_ADM_DIR      . '_config/healthcare.inc.php';
require_once CLASS_DIR        . 'employee.class.php';
require_once CLASS_DIR        . 'admin.class.php';
require_once CLASS_DIR        . "stockOut.class.php";
require_once CLASS_DIR        . "salesReturn.class.php";
require_once CLASS_DIR        . "stockIn.class.php";
require_once CLASS_DIR        . "stockReturn.class.php";
require_once CLASS_DIR        . 'doctors.class.php';
require_once CLASS_DIR        . 'appoinments.class.php';
require_once CLASS_DIR        . 'encrypt.inc.php';



$CustomerId = url_dec($_GET['report']);

$Employees   = new Employees;
$StockOut    = new StockOut();
$SalesReturn = new SalesReturn();
$StockIn     = new StockIn();
$StockReturn = new StockReturn();
$User        = new Admin();
$doctors     = new Doctors();
$Appointments= new Appointments();


$userDetails = json_decode($User->adminDetails($CustomerId));
if ($userDetails->status) {
    $userDetails = $userDetails->data[0];
    $password = pass_dec($userDetails->password, ADMIN_PASS);
}

///=================salse amount========================///
$CountSoldItems = count($StockOut->stockOutDisplay($CustomerId));
$soldItems      = $StockOut->stockOutDisplay($CustomerId);
$salesReturn    = count($SalesReturn->salesReturnDisplay($CustomerId));
// print_r($salesReturn);

$totalAmount = 0;
$creditCount = 0;
$paymentModeOccurrences = array();
foreach ($soldItems as $item) {
    $totalAmount += $item['amount'];
    $paymentMode = $item['payment_mode'];
    // print_r($paymentMode);
    if ($item['payment_mode'] === 'Credit') {
        $creditCount++;
    }

    if (array_key_exists($paymentMode, $paymentModeOccurrences)) {
        $paymentModeOccurrences[$paymentMode]++;
    } else {
        $paymentModeOccurrences[$paymentMode] = 1;
    }
}

$strtDt = date('Y-m-d');
$lst24hrs = date('Y-m-d', strtotime($strtDt . ' - 1 days'));
$lst7 = date('Y-m-d', strtotime($strtDt . ' - 7 days'));
$lst30 = date('Y-m-d', strtotime($strtDt . ' - 30 days'));

$salesOfTheDayToday = $StockOut->customerDayRange($strtDt, $strtDt, $CustomerId);

$sodLst24Hrs = $StockOut->customerDayRange($lst24hrs, $strtDt, $CustomerId);

$sodLst7Days = $StockOut->customerDayRange($lst7, $strtDt, $CustomerId);

$sodLst30Days = $StockOut->customerDayRange($lst30, $strtDt, $CustomerId);
// print_r($sodLst30Days);


///=========purches amount==================///
$CountPurchesItems = count($StockIn->showStockIn($CustomerId));
$PurchesItems      = $StockIn->showStockIn($CustomerId);
$PurchesRetun      = $StockReturn->showStockReturn($CustomerId);
$PurchesRetun      = json_decode($PurchesRetun, true);
// print_r($PurchesRetun);

$totalPurchesAmount = 0;
$creditPurchesCount = 0;
$totalPurchesRetun  = 0;
$purchesPaymentModeOccur = array();
foreach ($PurchesItems as $item) {
    $totalPurchesAmount += $item['amount'];
    $paymentMode = $item['payment_mode'];

    if ($item['payment_mode'] === 'Credit') {
        $creditPurchesCount++;
    }

    if (array_key_exists($paymentMode, $purchesPaymentModeOccur)) {
        $purchesPaymentModeOccur[$paymentMode]++;
    } else {
        $purchesPaymentModeOccur[$paymentMode] = 1;
    }
}
// count return purches amount //
if ($PurchesRetun['status'] == 1 && isset($PurchesRetun['data']) && is_array($PurchesRetun['data'])) {
    $data = $PurchesRetun['data'];
    $totalPurchesRetun = count($data);
}


$podStrtDt = date('Y-m-d');
$podLst24hrs = date('Y-m-d', strtotime($strtDt . ' - 1 days'));
$podLst7 = date('Y-m-d', strtotime($strtDt . ' - 7 days'));
$podLst30 = date('Y-m-d', strtotime($strtDt . ' - 30 days'));

$purchaeTodayCurrentData = $StockIn->customerPurchDayRange($podStrtDt, $podStrtDt, $CustomerId);

$purchaeTodayDataLst24hrs = $StockIn->customerPurchDayRange($podLst24hrs, $podStrtDt, $CustomerId);

$purchaeTodayDataLst7dys = $StockIn->customerPurchDayRange($podLst7, $podStrtDt, $CustomerId);

$purchaeTodayDataLst30dys = $StockIn->customerPurchDayRange($podLst30, $podStrtDt, $CustomerId);
// print_r($purchaeTodayDataLst30dys);

// find total doctor
$showDoctorByid = $doctors->showDoctors($CustomerId);
$showDoctor     = json_decode($showDoctorByid,true);
$totalDoct = 0;
if ($showDoctor && isset($showDoctor['data']) && is_array($showDoctor['data'])) {
    $doctorData = $showDoctor['data'];
    foreach ($doctorData as $doctor) {
        $totalDoct++;
    }
} 

// find total appointment
$showAppointment   = $Appointments->allAppointmentByAdmin($CustomerId);
$showAppointment   = json_decode($showAppointment,true);
$totalAppoint = 0;
if ($showAppointment && isset($showAppointment['data']) && is_array($showAppointment['data'])) {
    $appointData = $showAppointment['data'];
    foreach ($appointData as $appData) {
        $totalAppoint++;
    }
} 
// find employee count 
$EmployeeCount = count($Employees->employeesDisplay($CustomerId));
// print_r($EmployeeCount);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--git test -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>All Customer | <?= SITE_NAME ?></title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/appointment.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/return-page.css">
    <script src="<?php echo PLUGIN_PATH; ?>chartjs-4.4.0/updatedChart.js"></script>

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

                    <div class="card shadow mb-2">
                        <div class="card-body">
                            <h6 class="font-weight-bold text-secondary mb-0 pb-0">Report Parameters</h6>
                        </div>
                    </div>
                    <div class="card shadow-sm mb-2">
                        <div class="card-header">
                            <h6 class="font-weight-bold text-secondary mb-0 pb-0">Customer Details:</h6>
                        </div>
                        <div class="card-body">
                            Password: <?= $password ?>
                        </div>
                        <div class="card-body">
                        </div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 justify-content-between">
                            <div class="row mb-4">
                                <div class="col-sm-4 border-0">
                                    <div class="card border-left-primary shadow">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Doctors:</h5>
                                            <p class="text-center font-weight-bold"><?= $totalDoct; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card border-left-info shadow">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Appointment:</h5>
                                            <p class="text-center font-weight-bold"><?= $totalAppoint ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card border-left-success shadow">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Employee:</h5>
                                            <p class="text-center font-weight-bold"><?= $EmployeeCount ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body pt-1">
                                <div class="d-flex justify-content-between flex-wrap">
                                    <div class="d-flex flex-column flex-wrap mb-3">
                                        <div class="d-flex justify-content-start">
                                            <h6 class="font-weight-bold text-secondary"><b>Total Sold Item :</b> <?= $CountSoldItems ?></h6>
                                        </div>
                                        <div class="bg-white border border-0 rounded shadow" style="width: 106%;">
                                            <div class="ml-4" style="width: 72%;">
                                                <div class="d-flex justify-content-between mb-0 pb-0" style="width: 127%;">
                                                    <h5 class="pt-3 pb-n5 mb-0" style="color: #5a5c69;font-weight: 600;">Sales Item Based On Payment Mode</h5>
                                                    <!-- <button class="btn btn-sm d-flex justify-content-end m-3 mb-0 pb-0 border ">...</button> -->
                                                    <div class="d-flex justify-content-end px-2 mr-n4 mt-2">
                                                        <div class="dropdown-menu dropdown-menu-right p-2 mt-n5" id="sodDatePikDiv" style="display: none; margin-right:1rem;position: relative;">
                                                            <input type="date" id="salesOfTheDayDate">
                                                            <button class="btn btn-sm btn-primary" onclick="sodOnDate()" style="height: 2rem;">Find</button>
                                                        </div>
                                                        <div class="dropdown-menu dropdown-menu-right p-2 mt-n5" id="sodDtPikRngDiv" style="display: none; margin-right:1rem; position: relative;">
                                                            <div class="d-flex d-flex justify-content-start">
                                                                <div>
                                                                    <label>Start Date</label>&nbsp<input type="date" id="sodStartDt"><br>
                                                                    <label>End Date</label>&nbsp&nbsp&nbsp<input type="date" id="sodEndDt">
                                                                </div>&nbsp
                                                                <div>
                                                                    <br>
                                                                    <button class="btn btn-sm btn-primary" onclick="sodDtRange()" style="height: 2rem;">Find</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="btn-group mr-2">
                                                            <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <button class="dropdown-item" type="button" id="sodCurrentDt" onclick="chkSod(this.id)">Today</button>
                                                                <button class="dropdown-item" type="button" id="sodLst24hrs" onclick="chkSod(this.id)">Last 24 hrs</button>
                                                                <button class="dropdown-item" type="button" id="sodLst7" onclick="chkSod(this.id)">Last 7 Days</button>
                                                                <button class="dropdown-item" type="button" id="sodLst30" onclick="chkSod(this.id)">Last 30 Days</button>
                                                                <button class="dropdown-item dropdown" type="button" id="sodGvnDt" onclick="chkSod(this.id)">By Date</button>
                                                                <button class="dropdown-item dropdown" type="button" id="sodDtRng" onclick="chkSod(this.id)">By Date Range</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='bg-white pl-5 pr-5 pt-1 pb-2'>
                                                    <canvas id="myChart" width="50" height="50"></canvas>
                                                </div>
                                                <div class="ml-n2 d-flex justify-content-between" style="width: 130%;">
                                                    <h6 class="font-weight-bold text-secondary"><b>Total Amount : </b><?= $totalAmount ?></h6>
                                                    <h6 class="font-weight-bold text-secondary  mb-0 pb-0"><b>Return Item : </b><?= $salesReturn ?></h6>
                                                    <h6 class="font-weight-bold text-secondary  mb-0 pb-0"><b>Credit Amount : </b><?= $creditCount ?> </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column flex-wrap">
                                        <div class="d-flex justify-content-start">
                                            <h6 class="font-weight-bold text-secondary"><b>Total Purches item :</b><?= $CountPurchesItems ?> </h6>
                                        </div>
                                        <div class="bg-white border border-0 rounded shadow" style="width: 100%;">
                                            <div class="ml-5" style="width: 70%;">
                                                <div class="d-flex justify-content-between mb-0 pb-0" style="width: 127%;">
                                                    <h5 class="pt-3" style="color: #5a5c69;font-weight: 600;">Purches Item Based On Payment Mode</h5>
                                                    <!-- <button class="btn btn-sm d-flex justify-content-end mr-1 mt-3 ml-3 mb-0 pb-0 border ">...</button> -->
                                                    <div class="d-flex justify-content-end px-2 mt-2 ">
                                                        <div class="dropdown-menu dropdown-menu-right p-2  mt-n5" id="podDatePikDiv" style="display: none; margin-right:1rem;position: relative;">
                                                            <input type="date" id="purchaseOfTheDayDate">
                                                            <button class="btn btn-sm btn-primary" onclick="podOnDateFun()" style="height: 2rem;">Find</button>
                                                        </div>
                                                        <div class="dropdown-menu dropdown-menu-right p-2  mt-n5" id="podDtPikRngDiv" style="display: none; margin-right:1rem;position: relative;">
                                                            <div class="d-flex d-flex justify-content-start">
                                                                <div>
                                                                    <label>Start Date</label>&nbsp<input type="date" id="podStartDt"><br>
                                                                    <label>End Date</label>&nbsp&nbsp&nbsp<input type="date" id="podEndDt">
                                                                </div>&nbsp
                                                                <div>
                                                                    <br>
                                                                    <button class="btn btn-sm btn-primary" onclick="podOnDtRange()" style="height: 2rem;">Find</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <button class="dropdown-item" type="button" id="podCurrentDt" onclick="chkPod(this.id)">Today</button>
                                                                <button class="dropdown-item" type="button" id="podLst24hrs" onclick="chkPod(this.id)">Last 24 hrs</button>
                                                                <button class="dropdown-item" type="button" id="podLst7" onclick="chkPod(this.id)">Last 7 Days</button>
                                                                <button class="dropdown-item" type="button" id="podLst30" onclick="chkPod(this.id)">Last 30 Days</button>
                                                                <button class="dropdown-item dropdown" type="button" id="podGvnDt" onclick="chkPod(this.id)">By Date</button>
                                                                <button class="dropdown-item dropdown" type="button" id="podDtRng" onclick="chkPod(this.id)">By Date Range</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='bg-white pl-5 pr-5 pt-1 pb-2'>
                                                    <canvas id="myChart1" width="50" height="50"></canvas>
                                                </div>
                                                <div class=" d-flex justify-content-between" style="width: 140%; margin-left:-13%;">
                                                    <h6 class="font-weight-bold text-secondary mb-0 pb-0"><b>Total Amount: </b><?= $totalPurchesAmount ?></h6>
                                                    <h6 class="font-weight-bold text-secondary mb-0 pb-0"><b>Return Item: </b><?= $totalPurchesRetun ?></h6>
                                                    <h6 class="font-weight-bold text-secondary mb-0 pb-0"><b>Credit Amount:</b><?= $creditPurchesCount ?> </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include  SUP_ROOT_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>
    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <!-- <script src="<?= JS_PATH ?>filter.js"></script> -->

    <script>
        //////////////========satart Sales Item==========/////////
        function updateSod(uploadSodData) {
            if (uploadSodData != null && typeof uploadSodData === 'object' && Array.isArray(uploadSodData) && uploadSodData.length > 0) {
                console.log("get..", uploadSodData);
                let paymentModeOccurrences30days = {};

                uploadSodData.forEach(function(data) {
                    if (data && typeof data === 'object' && 'payment_mode' in data) {
                        let paymentMode = data.payment_mode;

                        if (paymentMode in paymentModeOccurrences30days) {
                            paymentModeOccurrences30days[paymentMode]++;
                        } else {
                            paymentModeOccurrences30days[paymentMode] = 1;
                        }
                    }
                });

                console.log(paymentModeOccurrences30days);
                updateSaleChart('myChart', paymentModeOccurrences30days);
            } else {
                console.log('uploadSodData is not an object or is null');
            }
        }


        function sodOnDate() {

            let sodDateSelect = document.getElementById('salesOfTheDayDate').value;

            var xmlhttp = new XMLHttpRequest();
            var sodOnDateUrl = `<?php echo ADM_URL ?>ajax/customerSalePurcItem.ajax.php?sodONDate=${sodDateSelect}&customerId=<?php echo $CustomerId ?>`;
            xmlhttp.open('GET', sodOnDateUrl, false);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(null);

            updateSod(JSON.parse(xmlhttp.responseText));

            // Hide the date picker divs after clicking "Find"
            document.getElementById('sodDatePikDiv').style.display = 'none';
            document.getElementById('sodDtPikRngDiv').style.display = 'none';
        }

        // === sod date range select from calander ...
        function sodDtRange() {
            let sodStartDate = document.getElementById('sodStartDt').value;
            let sodEndDate = document.getElementById('sodEndDt').value;
            var xmlhttp = new XMLHttpRequest();
            var sodOnDateRangeUrl = `<?php echo ADM_URL ?>ajax/customerSalePurcItem.ajax.php?sodStartDate=${sodStartDate}&sodEndDate=${sodEndDate}&customerId=<?php echo $CustomerId ?>`;

            xmlhttp.open('GET', sodOnDateRangeUrl, false);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(null);
            // console.log(xmlhttp.responseText);
            updateSod(JSON.parse(xmlhttp.responseText));

            // Hide the date picker divs after clicking "Find"
            document.getElementById('sodDatePikDiv').style.display = 'none';
            document.getElementById('sodDtPikRngDiv').style.display = 'none';
        }

        function chkSod(id) {

            if (id == 'sodCurrentDt') {
                document.getElementById('sodDatePikDiv').style.display = 'none';
                document.getElementById('sodDtPikRngDiv').style.display = 'none';
                updateSod(<?php echo json_encode($salesOfTheDayToday) ?>);
            }
            if (id == 'sodLst24hrs') {
                document.getElementById('sodDatePikDiv').style.display = 'none';
                document.getElementById('sodDtPikRngDiv').style.display = 'none';
                updateSod(<?php echo json_encode($sodLst24Hrs) ?>);
            }
            if (id == 'sodLst7') {
                document.getElementById('sodDatePikDiv').style.display = 'none';
                document.getElementById('sodDtPikRngDiv').style.display = 'none';
                updateSod(<?php echo json_encode($sodLst7Days) ?>);
            }
            if (id == 'sodLst30') {
                updateSod(<?php echo json_encode($sodLst30Days) ?>);
            }
            if (id == 'sodGvnDt') {
                document.getElementById('sodDatePikDiv').style.display = 'block';
                document.getElementById('sodDtPikRngDiv').style.display = 'none';

            }
            if (id == 'sodDtRng') {
                document.getElementById('sodDatePikDiv').style.display = 'none';
                document.getElementById('sodDtPikRngDiv').style.display = 'block';
            }
        }
        //////////////=========end sales item==========///////////////

        /////////////==========start purches item======///////////////
        function updatePod(uploadPodData) {
            if (uploadPodData != null && typeof uploadPodData === 'object' && Array.isArray(uploadPodData) && uploadPodData.length > 0) {
                // console.log("get..", uploadPodData);
                let paymentModeOccurrences30days = {};

                uploadPodData.forEach(function(data) {
                    if (data && typeof data === 'object' && 'payment_mode' in data) {
                        let paymentMode = data.payment_mode;

                        if (paymentMode in paymentModeOccurrences30days) {
                            paymentModeOccurrences30days[paymentMode]++;
                        } else {
                            paymentModeOccurrences30days[paymentMode] = 1;
                        }
                    }
                });

                console.log(paymentModeOccurrences30days);
                updatePurchChart('myChart1', paymentModeOccurrences30days);
            } else {
                console.log('uploadPodData is not an object or is null');
            }
        }

        function podOnDateFun() {
            let podDateSelect = document.getElementById('purchaseOfTheDayDate').value;

            var xmlhttp = new XMLHttpRequest();
            var podOnDateUrl = `<?php echo ADM_URL ?>ajax/customerSalePurcItem.ajax.php?podONDate=${podDateSelect}&customerId=<?php echo $CustomerId ?>`;
            xmlhttp.open('GET', podOnDateUrl, false);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(null);

            updatePod(JSON.parse(xmlhttp.responseText));

            document.getElementById('podDtPikRngDiv').style.display = 'none';
            document.getElementById('podDatePikDiv').style.display = 'none';
        }

        function podOnDtRange() {
            let podStartDate = document.getElementById('podStartDt').value;
            let podEndDate = document.getElementById('podEndDt').value;
            // console.log(sodEndDate);
            var xmlhttp = new XMLHttpRequest();
            var podOnDateRangeUrl = `<?php echo ADM_URL ?>ajax/customerSalePurcItem.ajax.php?podStartDate=${podStartDate}&podEndDate=${podEndDate}&customerId=<?php echo $CustomerId ?>`;

            xmlhttp.open('GET', podOnDateRangeUrl, false);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(null);
            console.log(xmlhttp.responseText);
            updatePod(JSON.parse(xmlhttp.responseText));

            document.getElementById('podDtPikRngDiv').style.display = 'none';
            document.getElementById('podDatePikDiv').style.display = 'none';

        }

        function chkPod(id) {

            if (id == 'podCurrentDt') {
                document.getElementById('podDatePikDiv').style.display = 'none';
                document.getElementById('podDtPikRngDiv').style.display = 'none';
                updatePod(<?php echo json_encode($purchaeTodayCurrentData) ?>);

            }

            if (id == 'podLst24hrs') {
                document.getElementById('podDatePikDiv').style.display = 'none';
                document.getElementById('podDtPikRngDiv').style.display = 'none';
                updatePod(<?php echo json_encode($purchaeTodayDataLst24hrs) ?>);
            }

            if (id == 'podLst7') {
                document.getElementById('podDatePikDiv').style.display = 'none';
                document.getElementById('podDtPikRngDiv').style.display = 'none';
                updatePod(<?php echo json_encode($purchaeTodayDataLst7dys) ?>);
            }

            if (id == 'podLst30') {
                document.getElementById('podDatePikDiv').style.display = 'none';
                document.getElementById('podDtPikRngDiv').style.display = 'none';
                updatePod(<?php echo json_encode($purchaeTodayDataLst30dys) ?>);
            }

            if (id == 'podGvnDt') {
                document.getElementById('podDatePikDiv').style.display = 'block';
                document.getElementById('podDtPikRngDiv').style.display = 'none';
            }

            if (id == 'podDtRng') {
                document.getElementById('podDatePikDiv').style.display = 'none';
                document.getElementById('podDtPikRngDiv').style.display = 'block';
            }
        }

        ///=====updated sales chart ======////
        function updateSaleChart(chartId, newData) {
            const ctx = document.getElementById(chartId).getContext('2d');
            const labels = Object.keys(newData);
            const data = Object.values(newData);
            const backgroundColors = generateRandomColors(data.length);

            // Get existing chart instance if it exists and destroy it
            const existingChart = Chart.getChart(ctx);
            if (existingChart) {
                existingChart.destroy();
            }

            // Create a new chart with updated data
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Most Payment',
                        data: data,
                        backgroundColor: backgroundColors,
                        hoverOffset: 4
                    }]
                },
                options: {
                    labels: {
                        display: true,
                        position: 'left',
                    },
                },
            });
        }
        ////======end sales chart=====/////
        ////======start purches chart====///
        function updatePurchChart(chartId, newData) {
            const ctx1 = document.getElementById(chartId).getContext('2d');
            const labels1 = Object.keys(newData);
            const data1 = Object.values(newData);
            const backgroundColors1 = generateRandomColors1(data1.length);

            // Get existing chart instance if it exists and destroy it
            const existingChart = Chart.getChart(ctx1);
            if (existingChart) {
                existingChart.destroy();
            }

            // Create a new chart with updated data
            new Chart(ctx1, {
                type: 'pie',
                data: {
                    labels: labels1,
                    datasets: [{
                        label: 'Most Payment',
                        data: data1,
                        backgroundColors1: backgroundColors1,
                        hoverOffset: 4
                    }]
                },
                options: {
                    labels: {
                        display: true,
                        position: 'left',
                    },
                },
            });
        }

        ////...........by default chart ...............////
        const labels = Object.keys(<?php echo json_encode($paymentModeOccurrences); ?>);
        const labels1 = Object.keys(<?php echo json_encode($purchesPaymentModeOccur); ?>);

        const data = Object.values(<?php echo json_encode($paymentModeOccurrences); ?>);
        const data1 = Object.values(<?php echo json_encode($purchesPaymentModeOccur); ?>);
        // console.log(data1);
        const backgroundColors = generateRandomColors(data.length);
        const backgroundColors1 = generateRandomColors1(data1.length1);

        // .....Create the chart for sales...//
        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Most Payment',
                    data: data,
                    backgroundColor: backgroundColors,
                    hoverOffset: 4
                }]
            },
            options: {
                labels: {
                    display: true,
                    position: 'left',
                },
            },
        });

        //...Create the chart for purches...//
        const ctx1 = document.getElementById('myChart1').getContext('2d');
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: labels1,
                datasets: [{
                    label: 'Most Payment',
                    data: data1,
                    backgroundColors1: backgroundColors1,
                    hoverOffset: 4
                }]
            },
            options: {
                labels: {
                    display: true,
                    position: 'left',
                },
            },
        });

        //==== generate random colors===//
        function generateRandomColors(numColors) {
            const colors = [];
            for (let i = 0; i < numColors; i++) {
                colors.push(getRandomColor());
            }
            return colors;
        }

        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        function generateRandomColors1(numColors) {
            const colors = [];
            for (let i = 0; i < numColors; i++) {
                colors.push(getRandomColor1());
            }
            return colors;
        }

        function getRandomColor1() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>

</body>

</html>