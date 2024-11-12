<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';


require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'PathologyReport.class.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'utility.class.php';



// $LabAppointments = new LabAppointments();
$Patients        = new Patients();
$LabBilling      = new LabBilling();
$LabBillDetails  = new LabBillDetails();
$PathologyReport = new PathologyReport;
$Pathology       = new Pathology;
$Doctors         = new Doctors();
$Employees       = new Employees;
$Pagination      = new Pagination;
$Utility         = new Utility;


// ================ doctor detials ===================
$DoctorsList = json_decode($Doctors->showDoctors());
if (!empty($DoctorsList->data)) {
    $DoctorList = $DoctorsList->data;
}

// ================ employee detials ===================
$empCol = '';
$admid = '';
$employeeDetails = $Employees->selectEmpByCol($empCol, $admid);
$employeeDetails = json_decode($employeeDetails);
if($employeeDetails->status){
    $employeeDetails = $employeeDetails->data;
}else{
    $employeeDetails = [];
}


// ================ data filter ===================
// ============= APPOINTMENT DATA ================
$searchVal = '';
$match = '';
$startDate = '';
$endDate = '';
$docId = '';
$empId = '';


if (isset($_GET['search']) || isset($_GET['dateFilterStart']) || isset($_GET['dateFilterEnd']) || isset($_GET['docIdFilter']) || isset($_GET['staffIdFilter'])) {

    if (isset($_GET['search'])) {
        $searchVal = $match = $_GET['search'];
    }

    if (isset($_GET['dateFilterStart'])) {
        $startDate = $_GET['dateFilterStart'];
        $endDate = $_GET['dateFilterEnd'];
    }

    if (isset($_GET['docIdFilter'])) {
        $docId = $_GET['docIdFilter'];
    }

    if (isset($_GET['staffIdFilter'])) {
        $empId = $_GET['staffIdFilter'];
    }

    $labBillDisplay = $LabBilling->labBillDataSearchFilter($searchVal, $startDate, $endDate, $docId);
} else {
    $labBillDisplay = $LabBilling->labBillDisplay();
}

$labBillDisplay = json_decode($labBillDisplay);
// print_r($labBillDisplay);
$slicedLabBills = '';
if ($labBillDisplay->status) {
    if ($labBillDisplay->data != '') {
        $billsResponseData = $labBillDisplay->data;
        if (is_array($billsResponseData)) {
            $response = json_decode($Pagination->arrayPagination($billsResponseData));

            $paginationHTML = '';
            $totalItem = $slicedLabBills = $response->totalitem;

            if ($response->status == 1) {
                $slicedLabBills = $response->items;
                $paginationHTML = $response->paginationHTML;
            }
        } else {
            $totalItem = 0;
        }
    } else {
        $totalItem = 0;
        $paginationHTML = '';
    }
} else {
    $totalItem = 0;
    $paginationHTML = '';
}

?>

<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Lab Appointments - <?= $HEALTHCARENAME ?></title>

    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/return-page.css" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css" />
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
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
                    
                    <!-- Test Appointments -->
                    <div class="card shadow-sm">
                        <div class="row d-flex">
                            <div class="col-md-5">
                                <h6 class="mt-3 ml-4 font-weight-bold text-primary">List of Bookings : <?= $totalItem ?></h6>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end ml-5">
                                <a class="mt-3 btn btn-sm btn-primary" href="lab-patient-selection.php?test=true">
                                    <p class="m-0 p-0">Entry</p>
                                </a>
                            </div>
                        </div>

                        <div class="row d-flex">
                            <div class="col-12 d-flex mt-4">
                                <div class="col-sm-6 col-md-3">
                                    <label class="d-none" id="control-flag"><?= $flagVal; ?></label>
                                    <label class="d-none" id="parent-url"><?php echo URL; ?></label>

                                    <div class="input-group">
                                        <input class="cvx-inp" type="text" placeholder="Invoice ID / Patient ID" name="appointment-search" aria-describedby="button-addon2" id="search-by-id-name-contact" style="outline: none;" value="<?= isset($match) ? $match : ''; ?>">

                                        <div class="input-group-append" id="appointment-search-filter-1">
                                            <button class="btn btn-sm btn-outline-primary shadow-none searchIcon" type="button" id="button-addon" onclick="filterAppointmentByValue()"><i class="fas fa-search"></i></button>
                                        </div>

                                        <div class="input-group-append">
                                            <button class="d-none btn btn-sm btn-outline-primary shadow-none input-group-append searchIcon" id="filter-reset-1" type="button" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <div class="input-group">

                                        <select class="col-md-11 cvx-inp1" name="added_on" id="added_on" onchange="filterAppointmentByValue()">
                                            <option value="" disabled selected>Select Duration</option>
                                            <option value="T">Today</option>
                                            <option value="Y">yesterday</option>
                                            <option value="LW">Last 7 Days</option>
                                            <option value="LM">Last 30 Days</option>
                                            <option value="LQ">Last 90 Days</option>
                                            <option value="CFY">Current Fiscal Year</option>
                                            <option value="PFY">Previous Fiscal Year</option>
                                            <option value="CR">Custom Range </option>
                                        </select>
                                        <button class="d-none btn btn-sm btn-outline-primary shadow-none rounded-0" type="button" id="filter-reset-2" style="margin-left: -26px; z-index: 100; background: white;" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                    </div>

                                    <label class="d-none" id="select-start-date"><?php echo $startDate; ?></label>
                                    <label class="d-none" id="select-end-date"><?php echo $endDate; ?></label>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <div class="input-group">
                                        <select class="col-md-11 cvx-inp1" name="doctor-filter" id="doctor_id" onchange="filterAppointmentByValue()">
                                            <option value="" selected disabled>Find By Doctor</option>
                                            <?php
                                            foreach ($DoctorList as $doctor) {
                                                $selected = $doctorID ==  $doctor->doctor_id ? 'selected' : '';
                                                echo "<option $selected value='$doctor->doctor_id'>$doctor->doctor_name</option>";
                                            }
                                            ?>
                                        </select>
                                        <button class="d-none btn btn-sm btn-outline-primary shadow-none rounded-0" type="button" id="filter-reset-3" style="margin-left: -26px; z-index: 100; background: white;" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                    </div>
                                    <label class="d-none" id="select-docId"><?php echo $docId; ?></label>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <div class="input-group">
                                        <select class="col-md-11 cvx-inp1" name="added_by" id="added_by" onchange="filterAppointmentByValue()">
                                            <option value="" disabled="" selected="">Select Staff</option>
                                            <?php
                                            if(!empty($employeeDetails)){
                                                foreach ($employeeDetails as $empData) {
                                                    // print_r($empData);
                                                    echo '<option value="' . $empData->emp_id . '">' . $empData->fname . ' '. $empData->lname .'</option>';
                                                }
                                            }else{
                                                echo '<option value="">No Data Found</option>';
                                            }
                                            ?>
                                        </select>
                                        <button class="d-none btn btn-sm btn-outline-primary shadow-none rounded-0" type="button" id="filter-reset-4" style="margin-left: -26px; z-index: 100; background: white;" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                    </div>
                                    <label class="d-none" id="select-empId"><?php echo $empId; ?></label>
                                </div>
                            </div>
                        </div>

                        <label class="d-none" id="date-range-control-flag">0</label>
                        <label class="d-none" id="url-control-flag">0</label>
                        <div class="dropdown-menu  p-2 row ml-4 mt-2" id="dtPickerDiv" style="display: none; position: relative; background-color: rgba(255, 255, 255, 0.8);">
                            <div class=" col-md-8">
                                <div class="d-flex">
                                    <div class="dtPicker" style="margin-right: 1rem;">
                                        <label>Strat Date</label>
                                        <input type="date" id="from-date" name="from-date">
                                    </div>
                                    <div class="dtPicker" style="margin-right: 1rem;">
                                        <label>End Date</label>
                                        <input type="date" id="to-date" name="to-date">
                                    </div>
                                    <div class="dtPicker">
                                        <button class="btn btn-sm btn-primary" onclick="filterAppointmentByValue()">Find</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">

                            <?php
                            if ($totalItem > 0) {
                            ?>
                                <div class="table-responsive h-100">
                                    <table class="table table-sm table-bordered table-hover table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>Invoice</th>
                                                <th>Test Date</th>
                                                <th>Test</th>
                                                <th>Refered By</th>
                                                <th>Paid Amount</th>
                                                <th>Status</th>
                                                <th>Added By</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (is_array($slicedLabBills) && count($slicedLabBills) > 0) {
                                                foreach ($slicedLabBills as $rowlabBill) {
                                                    $billId        = $rowlabBill->bill_id;
                                                    $patientId     = $rowlabBill->patient_id;
                                                    $referdDoc     = $rowlabBill->refered_doctor;
                                                    $testDate      = $rowlabBill->test_date;
                                                    $paidAmount    = $rowlabBill->paid_amount;
                                                    $status        = $rowlabBill->status;
                                                    $addedBy       = $rowlabBill->added_by;

                                                    $addedName = $Utility->getNameById($addedBy);

                                                    $billDetails = json_decode($LabBillDetails->billDetailsById($billId));

                                                    if ($billDetails->status) {
                                                        $billDetails = $billDetails->data;
                                                    } else {
                                                        $billDetails = [];
                                                    }

                                                    $test = count($billDetails);


                                                    $docId = $referdDoc;
                                                    if (is_numeric($docId)) {
                                                        $showDoctor = $Doctors->showDoctorNameById($docId);
                                                        $showDoctor = json_decode($showDoctor);
                                                        if ($showDoctor->status == 1) {
                                                            $docName = $showDoctor->data->doctor_name;
                                                        }
                                                    } else {
                                                        $docName = $referdDoc;
                                                    }


                                                    /* Geeting the status of report acording to bill number */
                                                    $testIds = [];
                                                    $statusResponse = $PathologyReport->reportStatus($billId);
                                                    if($statusResponse['status']){
                                                        foreach ($statusResponse['data'] as $eachId) {
                                                            $paramRes = $Pathology->testIdByParameter($eachId);
                                                            $paramRes = json_decode($paramRes);
                                                            
                                                            if ($paramRes->status) {
                                                                // print_r($paramRes->data->test_id);
                                                                $testIds[] = $paramRes->data->test_id;
                                                            }
                                                        }
                                                    }
                                                    $completeTestNos = count(array_unique($testIds));

                                                    /* Prepareing The Status of Report Acording to Bill */
                                                    if ($completeTestNos === $test) {
                                                        $starusIcon = '<i class="far fa-check-circle text-primary"></i>';
                                                    }elseif ($completeTestNos > 0 &&  $completeTestNos < $test) {
                                                        $starusIcon = '<i class="fas fa-hourglass-half text-warning"></i>';
                                                    }else {
                                                        $starusIcon = '<i class="far fa-times-circle text-danger"></i>';
                                                    }
                                                    /*---------------------------------------------------------*/



                                                    echo '<tr ';

                                                    if ($status == "Credit") {
                                                        echo 'style="background-color:#FFCCCB";';
                                                    } elseif ($status == "Partial Due") {
                                                        echo 'style="background-color: #FFFF99";';
                                                    } elseif ($status == "Cancelled") {
                                                        echo 'style="background-color: #b51212; color: #FFF;"';
                                                    } else {
                                                        echo 'style="background-color:white";';
                                                    }
                                                    echo '>
                                                        <td>#' . $billId . '</td>
                                                        <td>' . formatDateTime($testDate) . '</td>
                                                        <td>' . $test . '</td>
                                                        <td>' . $docName . '</td>
                                                        <td>' . $paidAmount . '</td>
                                                        <td>' . $starusIcon . '</td>
                                                        <td>' . $addedName . '</td>
                                                        <td>

                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-primary rounded dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                                                <i class="fas fa-sliders-h"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <span class="dropdown-item cursor-pointer" data-toggle="modal" data-target="#billModal" onclick="billViewandEdit(' . $billId . ')" >
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                                View & Edit
                                                                </span>

                                                                
                                                                <a class="dropdown-item" onclick="openPrint(this.href); return false;" href="' . URL . 'invoices/print.php?name=lab_invoice&id=' . url_enc($billId) . '">
                                                                <i class="fas fa-print"></i>
                                                                Print Invoice
                                                                </a>

                                                                <a class="dropdown-item" href="test-report-generate.php?bill-id=' . $billId . '">
                                                                    <i class="fa fa-flask" aria-hidden="true"></i>
                                                                    Generate Report
                                                                </a>

                                                                <span class="dropdown-item cursor-pointer text-danger" id="' . $billId . '" onclick="cancelBill(' . $billId . ')">
                                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                                Cancel Invoice
                                                                </span>
                                                        
                                                            </div>
                                                        </div>
                                                        </td>
                                                    </tr>';
                                                }
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            <?php
                            } else {
                                echo '<div class="col-md-12  p-2 row p-2 d-flex justify-content-center" id="dtPickerDiv" style="position: relative; background-color: rgba(255, 255, 255, 0.8);">
                                    <label class="text-danger font-weight-bold">No Data Found</label>
                               </div>';
                            }

                            if ($totalItem > 16) {
                                echo '<div class="d-flex justify-content-center">
                                ' . $paginationHTML . '
                                </div>';
                            }

                            ?>

                        </div>
                    </div>
                    <!--/end Test Appointments -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>

    <!-- Bill View Modal -->
    <div class="modal fade" id="billModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Invoice Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body billview">
                    <!-- Data will be appeare here by ajax  -->
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <script src="<?= JS_PATH ?>polyclinic-searchFilter.js"></script>



    <script>
        billViewandEdit = (obj) => {

            let billId = obj;
            // alert(billId);
            let url = "ajax/labBill.view.ajax.php?billId=" + billId;
            $(".billview").html(
                '<iframe width="99%" height="500px" frameborder="0" overflow-x: hidden; overflow-y: scroll; allowtransparency="true"  src="' +
                url + '"></iframe>');

        } // end of viewAndEdit function

        function resizeIframe(obj) {
            obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
        }


        cancelBill = (billId) => {
            swal({
                    title: "Are you sure?",
                    text: "Once Cancelled, You Will Not Be Able to Modify This Bill.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        $.ajax({
                            url: "ajax/labBill.delete.ajax.php",
                            type: "POST",
                            data: {
                                billId: billId,
                                status: "Cancelled",
                            },
                            success: function(data) {
                                // alert (data);
                                if (data == 1) {
                                    swal("Done! Your Bill Has Been Cancelled.", {
                                        icon: "success",
                                    });
                                    row = document.getElementById(billId);
                                    row.closest('tr').style.background = '#b51212';
                                    row.closest('tr').style.color = '#FFFFFF';
                                } else {
                                    $("#error-message").html("Cancellation Field !!!").slideDown();
                                }

                            }
                        });

                    }
                });
        }
        
    </script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>/sb-admin-2.min.js"></script>
    <!-- new tab for invoice print  -->
    <script src="<?php echo JS_PATH ?>/main.js"></script>

</body>

</html>