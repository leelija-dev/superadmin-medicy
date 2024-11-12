<?php
// require_once dirname(__DIR__) . '/config/constant.php';
require_once 'config/constant.php';
require_once SUP_ADM_DIR      . '_config/sessionCheck.php';
require_once CLASS_DIR        . 'dbconnect.php';
require_once SUP_ADM_DIR      . '_config/healthcare.inc.php';
require_once CLASS_DIR        . 'appoinments.class.php';
require_once CLASS_DIR        . 'pagination.class.php';
require_once CLASS_DIR        . 'doctors.class.php';
require_once CLASS_DIR        . 'employee.class.php';
require_once CLASS_DIR        . 'admin.class.php';
require_once CLASS_DIR        . 'login.class.php';


$Appoinments = new Appointments();
$Pagination  = new Pagination;
$Doctors     = new Doctors();
$Employees   = new Employees;
$Admin       = new Admin();

// $adminDetails = $Admin->adminDetails();
// $adminDetails = json_decode($adminDetails);


if (isset($_GET['search'])) {
    $search = $_GET['search'];

    if ($search == 'appointment_search') {
        $searchPattern = $_GET['searchKey'];
        $adminDetails = $Admin->filterAdminByIdOrName($searchPattern);
        $adminDetails = json_decode($adminDetails);
        // print_r($allAppointments);
    }
} else {
    $adminDetails = $Admin->adminDetails();
    $adminDetails = json_decode($adminDetails);
}

if ($adminDetails->status) {
    if ($adminDetails->data != '') {
        $adminDetailsData = $adminDetails->data;
        if (is_array($adminDetailsData)) {
            // print_r($adminDetailsData);
            $response = json_decode($Pagination->arrayPagination($adminDetailsData));

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

    <title>All Customer | <?= SITE_NAME ?></title>

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

                        <div class="card-header row justify-content-between py-3 m-0">

                            <div class="col-12 col-sm-3 d-flex justify-content-start align-items-center">
                                <h6 class="font-weight-bold text-primary mb-0">Total Customer: <?= $totalItem ?>
                                </h6>
                            </div>

                            <div class="col-12 mt-2 col-sm-6 mt-sm-0 ">
                                <div class="input-group">
                                    <input class="cvx-inp" type="text" placeholder="Customer ID / Customer Name ..." name="appointment-search" id="appointment_search" style="outline: none;" value="<?= isset($match) ? $match : ''; ?>">

                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="button-addon" onclick="filterAppointment()"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered sortable-table" id="appointments-dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Customer ID</th>
                                            <th>Customer Name</th>
                                            <th>Customer Contact</th>
                                            <th>Last Login</th>
                                            <th>Expiry Date</th>
                                            <th>Status</th>
                                            <th>Employees</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($adminDetails->status == '1') {
                                            foreach ($adminDetails->data as $admin) {
                                                $customerId       = $admin->admin_id;
                                                $CustomerName     = $admin->username;
                                                $CustomerContanct = $admin->email;
                                                $showLoginTime   = $Admin->showLoginTime($customerId);
                                                $showLoginTime   = json_decode($showLoginTime, true);
                                                // print_r($showLoginTime);
                                                $LastLogin        = "";
                                                if ($showLoginTime['status'] == 1) {
                                                    $loginData = $showLoginTime['data'];
                                                    $lastLoginEntry = end($loginData);
                                                    // print_r($lastLoginEntry);
                                                    if (isset($lastLoginEntry['login_time'])) {
                                                        $loginTime = $lastLoginEntry['login_time'];
                                                        $formattedLoginTime = date('Y-m-d H:i:s', strtotime($loginTime));
                                                        $LastLogin =  $formattedLoginTime;
                                                    }
                                                }

                                                $Status           = $admin->reg_status == '1' ? 'Active' : 'Inactive';
                                                $StatusClass      = $admin->reg_status == 1 ? 'text-success font-weight-bold' : 'text-danger font-weight-bold';
                                                $AddedOn          = $admin->added_on;
                                                $expiryDate       = $admin->expiry;

                                                $EmployeeCount = $admin->reg_status == '1' ? count($Employees->employeesDisplay($customerId)) : '';

                                                $link = $admin->reg_status == 1
                                                    ? '<a href="employees.php?customerId=' . url_enc($customerId) . '" class="text-success text-decoration-none " data-toggle="tooltip" data-placement="left" title="Show Employees"><i class="fas fa-eye"> Emp: ' . $EmployeeCount . '</a>'
                                                    : '<a href="" class="text-danger text-decoration-none ml-4" data-toggle="tooltip" data-placement="left" title="Inactive Customer"><i class="fas fa-eye-slash"></a>';

                                                echo '<tr>
                                                          <td>' . $customerId . '</td>
                                                          <td>' . $CustomerName . '</td>
                                                          <td>' . $CustomerContanct . '</td>
                                                          <td>' . $LastLogin . '</td>
                                                          <td>' . $expiryDate . '</td>
                                                          <td class="' . $StatusClass . '">' . $Status . '</td>
                                                          <td>' . $link . '</td>
                                                          <td>
                                                              <a href="customer-report.php?report=' . url_enc($customerId) . '" class="text-success" data-toggle="tooltip" data-placement="left" title="Live Report"><i class="fas fa-chart-pie"></i></a>
                                                              <a href="#" class="text-danger" onclick="deleteAppointment(\'' . $customerId . '\');" data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash"></i></a> 
                                                          </td>
                                                     </tr>';
                                            }
                                        }
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
                <!-- <a href="prescription.php?prescription=' . url_enc($customerId) . '" class="text-primary" data-toggle="tooltip" data-placement="left" title="View and Print"><i class="fas fa-print"></i></a> -->
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
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    <script>
        function deleteAppointment(apntID) {
            var id = apntID;
            console.log(id);
            if (confirm("Are You Sure?")) {
                $.ajax({
                    url: "ajax/appointment.delete.ajax.php",
                    type: "POST",
                    data: {
                        id: apntID
                    },
                    success: function(data) {
                        location.reload();
                    }
                });
            }
            return false;
        }
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
            print_r(key);

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
            var newUrl =
                `${currentURLWithoutQuery}?search=${'added_on'}&searchKey=${'CR'}&fromDt=${fromDate}&toDt=${toDate}`;
            // replace previous url with new url
            window.location.replace(newUrl);
        }


        const filterAppointment = () => {

            // document.getElementById('dtPickerDiv').style.display = 'none';

            var key = document.getElementById("appointment_search").id;
            var val = document.getElementById("appointment_search").value;
            console.log(key);
            console.log(val);
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