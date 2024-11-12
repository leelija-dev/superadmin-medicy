<?php
// require_once dirname(__DIR__) . '/config/constant.php';
require_once 'config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/user-details.inc.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR    . 'admin.class.php';

$Patients   = new Patients;
$Pagination = new Pagination;
$Employees  = new Employees;
$Admin       = new Admin();

//============= Admin Details ==================//
$adminDetails = $Admin->adminDetails();
$adminDetails = json_decode($adminDetails);

// ============ STAFF LIST FETCHING SECTION ===========
$empCol = 'admin_id';
$employeeDetails = $Employees->selectEmpByCol();
$employeeDetails = json_decode($employeeDetails);
$employeeDetails = $employeeDetails->data;


// ============== PATIENT DATA ===============
if (isset($_GET['search'])) {
    if ($_GET['search'] == 'added_by') {
        $col = $_GET['search'];
        $data = $_GET['searchKey'];

        $allPatients = $Patients->patientFilterByColData($col, $data);
        $allPatients = $Patients->patientFilterByAdminId( $data);
    }

    if ($_GET['search'] == 'search-by-id-name') {
        $data = $_GET['searchKey'];

        $allPatients = $Patients->filterPatientByNameOrPid($data);
    }

    if ($_GET['search'] == 'added_on') {
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

        // echo "$fromDt<br>";
        // echo $toDt;

        $allPatients = $Patients->patientFilterByDate($fromDt, $toDt);
    }
} else {
    $allPatients = $Patients->allPatients();
}

$allPatients = json_decode($allPatients);

if ($allPatients->status) {
    if ($allPatients->data != '') {
        $allPatientsData = $allPatients->data;
        if (is_array($allPatientsData)) {
            $response = json_decode($Pagination->arrayPagination($allPatientsData));
            $slicedPatients = '';
            $paginationHTML = '';
            $totalItem = '';
            if (property_exists($response, 'totalitem')) 
            $totalItem = $slicedPatients = $response->totalitem;

            if ($response->status == 1) {
                $slicedPatients = $response->items;
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

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Patients - <?= SITE_NAME ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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

                            <div class="row">
                                <div class="col-md-3 mt-md-2">
                                    <h6 class="font-weight-bold text-primary">List of Patients : <?= $totalItem ?></h6>
                                </div>

                                <div class="col-md-3 mt-2">
                                    <div class="input-group">
                                        <input class="cvx-inp" type="text" placeholder="Patients ID / Patient Name" name="search-by-id-name" id="search-by-id-name" style="outline: none;" value="<?= isset($match) ? $match : ''; ?>">

                                        <div class="input-group-append">
                                            <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="button-addon" onclick="filterPatients()"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-2">
                                    <select class="cvx-inp1" name="added_on" id="added_on" onchange="returnFilter(this)">
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

                                <div class="col-md-3 mt-2">
                                    <select class="cvx-inp1" id="added_by" onchange="returnFilter(this)">
                                        <option value="" disabled="" selected="">Select Admin
                                        </option>
                                        <?php
                                        // foreach ($employeeDetails as $empData) {
                                        //     echo '<option value="' . $empData->emp_id . '">' . $empData->emp_name . '</option>';
                                        // }
                                        if ($adminDetails && isset($adminDetails->status) && $adminDetails->status == 1 && isset($adminDetails->data)) {
                                            foreach ($adminDetails->data as $admin) {
                                                $username = $admin->username;
                                                echo '<option value="' . $admin->admin_id . '">' . $username . '</option>';

                                            }
                                        } 
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="dropdown-menu row ml-4" id="dtPickerDiv" style="display: none;margin-top: -265px; background-color: rgba(255, 255, 255, 0.8);">
                                <div class="col-md-12">
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

                        <!-- <a data-toggle="modal" data-target="#appointmentSelection"><button
                                    class="btn btn-sm btn-primary" onclick="addNewPatientData()"><i class="fas fa-edit"></i>Add New</button></a> -->

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Patient ID</th>
                                            <th>Patient Name</th>
                                            <th>Age</th>
                                            <th>Contact</th>
                                            <th>Visits</th>
                                            <th>Area PIN</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($slicedPatients)) {
                                            foreach ($slicedPatients as $slicedPatientsdetails) {
                                                // print_r($slicedPatientsdetails);
                                                $slicedPatientsID   = $slicedPatientsdetails->patient_id;
                                                $slicedPatientsName = $slicedPatientsdetails->name;
                                                $slicedPatientsAge = $slicedPatientsdetails->age;
                                                $slicedPatientsPhone = $slicedPatientsdetails->phno;
                                                $slicedPatientsAge = $slicedPatientsdetails->age;
                                                $slicedPatientsVisited = $slicedPatientsdetails->visited;
                                                $slicedPatientsLabVisited = $slicedPatientsdetails->lab_visited;
                                                $slicedPatientsPin = $slicedPatientsdetails->patient_pin;
                                                echo '<tr>
                                         <td>'. $slicedPatientsID .'</td>
                                         <td>'. $slicedPatientsName .'</td>
                                         <td>'. $slicedPatientsAge .'</td>
                                         <td><a class="text-decoration-none" href="tel:$slicedPatientsPhone">'. $slicedPatientsPhone .'</a></td>
                                         <td class="align-middle pb-0 pt-0">
                                             <small class="small">
                                                 <span>Doctor: '. $slicedPatientsVisited .'</span>
                                                 <br>
                                                 <span>Lab: '. $slicedPatientsLabVisited .'</span></small>
                                         </td>
                                         <td> '.$slicedPatientsPin.'</td>

                                         <td class="text-center">
                                             <a class="text-primary" href="patient-details.php?patient='. url_enc($slicedPatientsID).'"
                                                 title="View and Edit"><i class="fas fa-eye"></i>
                                             </a>
                                         </td>
                                     </tr>';
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">
                                <?= $paginationHTML ?>
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


    <!-- ADD NEW PATIENT MODAL -->
    <div class="modal fade appointmentSelection" tabindex="-1" role="dialog" aria-labelledby="appointmentSelectionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Patient Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body add-new-patient">
                    <!-- Appointments Details Goes Here By Ajax -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="window.location.reload()">ADD</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END OF ADD NEW PATIENT MODAL -->


    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <!-- <script src="<?php echo PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script> -->
    <!-- Page level custom scripts -->
    <!-- <script src="<?php echo JS_PATH ?>demo/datatables-demo.js"></script> -->
    <!-- <script src="<?= JS_PATH ?>filter.js"></script> -->

    
    <script>

        const returnFilter = (t) => {

            document.getElementById('dtPickerDiv').style.display = 'none';

            var id = t.id;
            var value = t.value;

            console.log(value);

            if (value != 'CR') {
                //fetch current url and pathname
                var currentURLWithoutQuery = window.location.origin + window.location.pathname;
                // create new url with added value to previous url
                var newUrl = `${currentURLWithoutQuery}?search=${id}&searchKey=${value}`;
                // replace previous url with new url
                window.location.replace(newUrl);
            }

            if (value == 'CR') {
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


        const filterPatients = () => {

            document.getElementById('dtPickerDiv').style.display = 'none';

            var id = document.getElementById('search-by-id-name').id;
            var value = document.getElementById('search-by-id-name').value;

            var currentURLWithoutQuery = window.location.origin + window.location.pathname;
            var newUrl = `${currentURLWithoutQuery}?search=${id}&searchKey=${value}`;

            window.location.replace(newUrl);
        }

    </script>
</body>

</html>