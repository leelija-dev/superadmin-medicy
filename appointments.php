<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR      . '_config/sessionCheck.php';
// require_once __DIR__ . '/config/constant.php';
// require_once ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
// require_once ADM_DIR . '_config/accessPermission.php';
require_once CLASS_DIR    . 'dbconnect.php';
require_once SUP_ADM_DIR  . '_config/healthcare.inc.php';
require_once CLASS_DIR    . 'appoinments.class.php';
require_once CLASS_DIR    . 'pagination.class.php';
require_once CLASS_DIR    . 'doctors.class.php';
require_once CLASS_DIR    . 'employee.class.php';
require_once CLASS_DIR    . 'admin.class.php';

$Appoinments = new Appointments();
$Pagination  = new Pagination;
$Doctors     = new Doctors();
$Employees   = new Employees;
$Admin       = new Admin();

// ================ADMIN DATA========================//
$adminDetails = $Admin->adminDetails();
$adminDetails = json_decode($adminDetails);

// ================ EMPLOYEES DATA ==================
// $col = 'admin_id';
$employeeDetails = $Employees->selectEmpByCol();
$employeeDetails = json_decode($employeeDetails);

if ($employeeDetails->status) {
    $employeeDetails = $employeeDetails->data;
} else {
    $employeeDetails = array();
}


// ============ DOCTOR LIST ====================
$doctorDetails = $Doctors->showDoctors();
$doctorDetails = json_decode($doctorDetails);

if ($doctorDetails->status) {
    $doctorList = $doctorDetails->data;
} else {
    $doctorList = array();
}


// ============= APPOINTMENT DATA ================

if (isset($_GET['search'])) {
    $search = $_GET['search'];

    if ($search == 'doctor_id') {
        $doctorID = $_GET['searchKey'];
        $col = $_GET['search'];
        $allAppointments = $Appoinments->appointmentsFilter($col, $doctorID);
        $allAppointments = json_decode($allAppointments);
        // print_r($allAppointments);
    }

    if ($search == 'appointment_search') {
        $searchPattern = $_GET['searchKey'];
        $allAppointments = $Appoinments->filterAppointmentsByIdOrName($searchPattern);
        $allAppointments = json_decode($allAppointments);
        // print_r($allAppointments);
    }

    if ($search == 'added_by') {
        $doctorID = $_GET['searchKey'];
        $col = $_GET['search'];
        // print_r($doctorID);
        $allAppointments = $Appoinments->appointmentsFilter();
        $allAppointments = json_decode($allAppointments);
        
        ///find allappointment based on admin///
        $allAppointments = $Appoinments->allAppointmentByAdmin($doctorID);
        $allAppointments = json_decode($allAppointments);
        // print_r($allAppointments);
    }

    if ($search == 'added_on') {

        $value = $_GET['searchKey'];

        if ($value == 'T') {
            $fromDt = date('Y-m-d');
            $toDt = date('Y-m-d');
        }

        if ($value == 'Y') {
            $fromDt = new DateTime('yesterday');
            $fromDt = $fromDt->format('Y-m-d');
            $toDt = $fromDt;
        }

        if ($value == 'LW') {
            $fromDt = new DateTime('-7 days');
            $fromDt = $fromDt->format('Y-m-d');
            $toDt = date('Y-m-d');
        }

        if ($value == 'LM') {
            $fromDt = new DateTime('-30 days');
            $fromDt = $fromDt->format('Y-m-d');
            $toDt = date('Y-m-d');
        }

        if ($value == 'LQ') {
            $fromDt = new DateTime('-90 days');
            $fromDt = $fromDt->format('Y-m-d');
            $toDt = date('Y-m-d');
        }

        if ($value == 'CFY') {
            $currentYear = new DateTime();
            $fiscalYear = $currentYear->format('Y');
            $fiscalYear = intval($fiscalYear) + 1;
            $currentYear = $currentYear->format('Y');

            $fromDt = $currentYear . '-04-01';
            $toDt = $fiscalYear . '-03-31';
        }

        if ($value == 'PFY') {
            $currentYear = new DateTime();
            $prevFiscalYr = $currentYear->format('Y');
            $prevFiscalYr = intval($prevFiscalYr) - 1;
            $currentYear = $currentYear->format('Y');

            $fromDt = $prevFiscalYr . '-04-01';
            $toDt = $currentYear . '-03-31';
        }

        if ($value == 'CR') {
            $fromDt = $_GET['fromDt'];
            $toDt = $_GET['toDt'];
        }

        $allAppointments = $Appoinments->appointmentsFilterByDate($fromDt, $toDt);
        $allAppointments = json_decode($allAppointments);
    }
} else {
    $allAppointments = $Appoinments->appointmentsDisplay();
    $allAppointments = json_decode($allAppointments);
}


if ($allAppointments->status) {
    if ($allAppointments->data != '') {
        $allAppointmentsData = $allAppointments->data;

        if (is_array($allAppointmentsData)) {
            // print_r($allAppointmentsData);
            $response = json_decode($Pagination->arrayPagination($allAppointmentsData));

            $slicedAppointments = '';
            $paginationHTML = '';
            $totalItem = $slicedAppointments = $response->totalitem;

            if ($response->status == 1) {
                $slicedAppointments = $response->items;
                $paginationHTML = $response->paginationHTML;
            }
        } else {
            $totalItem = 0;
        }
    }
} else {
    $totalItem = 0;
    $paginationHTML = '';
}


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

    <title>Appointments - <?= $healthCareName ?> | <?= SITE_NAME ?></title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/appointment.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/return-page.css">

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

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">

                        <div class="card-header py-3 justify-content-between">

                            <div class="col-12 d-flex justify-content-between">
                                <div class="">
                                    <h6 class="font-weight-bold text-primary">Total Appointments: <?= $totalItem ?></h6>
                                </div>
                                <!-- <div class="ml-4">
                                        <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#appointmentSelection">
                                            <p class="m-0 p-0">Entry</p>
                                        </a>
                                    </div> -->
                            </div>


                            <div class="row mt-2">
                                <div class="d-flex">
                                    <div class="col-md-6 col-6 mt-2">
                                        <div class="input-group">
                                            <input class="cvx-inp" type="text" placeholder="Appointment ID / Patient Id / Patient Name" name="appointment-search" id="appointment_search" style="outline: none;" value="<?= isset($match) ? $match : ''; ?>">

                                            <div class="input-group-append">
                                                <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="button-addon" onclick="filterAppointment()"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6 col-6  mt-2">
                                        <select class="cvx-inp1" name="added_on" id="added_on" onchange="filterAppointmentByValue(this)">
                                            <option value="" disabled="" selected="">Select Duration</option>
                                            <option value="T">Today</option>
                                            <option value="Y">yesterday</option>
                                            <option value="LW">Last 7 Days</option>
                                            <option value="LM">Last 30 Days</option>
                                            <option value="LQ">Last 90 Days</option>
                                            <option value="CFY">Current Fiscal Year</option>
                                            <option value="PFY">Previous Fiscal Year</option>
                                            <option value="CR">Custom Range </option>
                                        </select>

                                    </div>
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="col-md-6 col-6 mt-2">
                                        <select class="cvx-inp1" name="doctor-filter" id="doctor_id" onchange="filterAppointmentByValue(this)">
                                            <option value="" selected="" disabled="">Find By Doctor</option>

                                            <?php

                                            foreach ($doctorList as $doctorList) {
                                                echo '<option value="' . $doctorList->doctor_id . '">' . $doctorList->doctor_name . '</option>';
                                            }

                                            ?>

                                        </select>
                                    </div>
                                    <div class="col-md-6 col-6 mt-2">
                                        <select class="cvx-inp1" id="added_by" onchange="filterAppointmentByValue(this)">
                                            <option value="" disabled="" selected="">Select Admin</option>

                                            <?php


                                            if ($adminDetails && isset($adminDetails->status) && $adminDetails->status == 1 && isset($adminDetails->data)) {
                                                foreach ($adminDetails->data as $admin) {
                                                    $username = $admin->username;
                                                    $adminID  =  $admin->admin_id;
                                                    echo '<option value="' . $adminID . '">' . $username . '</option>';
                                                }
                                            } 
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="d-flex justify-content-end">
                                    <div class=" col-3 mt-2">
                                        <a class="btn btn-sm btn-primary  " data-toggle="modal" data-target="#appointmentSelection">
                                            <p class="m-0 ">Entry</p>

                                        </a>
                                    </div>
                                </div> -->

                            </div>

                            <div class="dropdown-menu  p-2 row ml-4" id="dtPickerDiv" style="display: none; margin-top: -280px; background-color: rgba(255, 255, 255, 0.8);">
                                <div class=" col-md-12">
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
                                            <button class="btn btn-sm btn-primary" onclick="customDate()">Find</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered sortable-table" id="appointments-dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Patient Name</th>
                                            <th>Assigned Doctor</th>
                                            <th>Date</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($slicedAppointments)) {
                                            // print_r($slicedAppointments);
                                            foreach ($slicedAppointments as $showAppointDetails) {
                                                $appointmentTableID = $showAppointDetails->id;
                                                $appointmentID = $showAppointDetails->appointment_id;
                                                $appointmentDate = date("d-m-Y", strtotime($showAppointDetails->appointment_date));
                                                $appointmentName = $showAppointDetails->patient_name;
                                                $getDoctorForPatient = $showAppointDetails->doctor_id;

                                                $deleteAppointmentLink = "delete-appointment.php?delete-appointment=$appointmentID";
                                                $updateAppointmentLink = "update-appointment.php?update-prescription=$appointmentID";
                                                $prescriptionViewLink = "prescription.php?prescription=$appointmentID";

                                                $showDoctorsForPatient = $Doctors->showDoctorsForPatient($getDoctorForPatient);

                                                // echo $appointmentTableID.'<br>';
                                                if ($showDoctorsForPatient != NULL) {

                                                    foreach ($showDoctorsForPatient as $rowDoc) {
                                                        $docName = $rowDoc['doctor_name'];
                                                        // echo $docName.'<br><br>';
                                                    }
                                                } else {
                                                    $docName = '';
                                                }

                                                echo '<tr>
                                                        
                                                        <td>' . $appointmentID . '</td>
                                                        <td>' . $appointmentName . '</td>
                                                        <td>' . $docName . '</td>
                                                        <td>' . $appointmentDate . '</td>

                                                        <td>
                                
                                                        <a href="prescription.php?prescription=' . url_enc($appointmentID) . '" class="text-primary" title="View and Print"><i class="fas fa-print"></i></a>

                                                        </td>
                                                    </tr>';
                                            }
                                        }
                                        // href="ajax/appointment.delete.ajax.php?appointmentId='.$appointmentID.'"
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center" id="pagination-control">
                                <?= $paginationHTML ?>
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
    <!-- Select Appointment Type Modal  -->
    <div class="modal fade" id="appointmentSelection" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog centered" role="document">
            <div class="modal-content">
                <div class="modal-body d-flex justify-content-around align-items-center py-4">
                    <a href="add-patient.php" class="btn btn-info" href="">New Patient</a>
                    OR
                    <a href="patient-selection.php" class="btn btn-info" href="">Returning Patient</a>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!--/end Select Appointment Type Modal  -->

    <!-- View & Edit Appointment Modal -->
    <div class="modal fade AppointmntViewAndEdit" tabindex="-1" role="dialog" aria-labelledby="AppointmntViewAndEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body view-and-edit-appointments">
                    <!-- Appointments Details Goes Here By Ajax -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="window.location.reload()">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of View & Edit Appointment Modal -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>
    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>

    <script>
        $(document).ready(function() {
            $(document).on("click", ".delete-btn", function() {

                if (confirm("Are You Sure?")) {
                    apntID = $(this).data("id");
                    btn = this;

                    $.ajax({
                        url: "ajax/appointment.delete.ajax.php",
                        type: "POST",
                        data: {
                            id: apntID
                        },
                        success: function(data) {
                            if (data == 1) {
                                $(btn).closest("tr").fadeOut()
                            } else {
                                $("#error-message").html("Deletion Field !!!").slideDown();
                                $("success-message").slideUp();
                            }

                        }
                    });
                }
                return false;
            })

        })
    </script>
    <script>
        appointmentViewAndEditModal = (appointmentTableID) => {

            let url = "ajax/appointment.view.ajax.php?appointmentTableID=" + appointmentTableID;
            $(".view-and-edit-appointments").html(
                '<iframe width="99%" height="440px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');

        } // end of LabCategoryEditModal function
    </script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <!-- <script src="<?= JS_PATH ?>filter.js"></script> -->


    <script>
        const filterAppointmentByValue = (t) => {

            document.getElementById('dtPickerDiv').style.display = 'none';

            key = t.id;
            val = t.value;

            if (val != 'CR') {
                var currentURL = window.location.href;

                // Get the current URL without the query string
                var currentURLWithoutQuery = window.location.origin + window.location.pathname;

                var newURL = `${currentURLWithoutQuery}?search=${key}&searchKey=${val}`;

                window.location.replace(newURL);
            }

            if (val == 'CR') {
                document.getElementById('dtPickerDiv').style.display = 'block';
            }
        }


        const customDate = () => {
            let fromDate = document.getElementById('from-date').value;
            let toDate = document.getElementById('to-date').value;

            //fetch current url and pathname
            var currentURLWithoutQuery = window.location.origin + window.location.pathname;
            // create new url with added value to previous url
            var newUrl = `${currentURLWithoutQuery}?search=${'added_on'}&searchKey=${'CR'}&fromDt=${fromDate}&toDt=${toDate}`;
            // replace previous url with new url
            window.location.replace(newUrl);
        }


        const filterAppointment = () => {

            document.getElementById('dtPickerDiv').style.display = 'none';

            var key = document.getElementById("appointment_search").id;
            var val = document.getElementById("appointment_search").value;

            var currentURLWithoutQuery = window.location.origin + window.location.pathname;
            if (val.length > 2) {
                var newURL = `${currentURLWithoutQuery}?search=${key}&searchKey=${val}`;
                window.location.replace(newURL);
            } else {
                console.log("min 3 char");;
            }
        }
    </script>

</body>

</html>