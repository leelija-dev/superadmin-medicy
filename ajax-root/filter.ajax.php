<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . "stockReturn.class.php";
require_once CLASS_DIR . "distributor.class.php";
require_once CLASS_DIR . "appoinments.class.php";
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'doctors.class.php';

$Pagination  = new Pagination;
$Appointments = new Appointments();
$Doctors = new Doctors;
$Patients   = new Patients;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['search'])) {

        $match = $_POST['search'];
        $searchFor  = $_POST['searchFor'];


        switch ($searchFor) {

            case 'appointment-search':

                if (strlen($match) > 0) {
                    if (preg_match('/\d/', $match)) {
                        $col = 'appointment_id';
                    } else {
                        $col = 'patient_name';
                    }

                    $resultData = $Appointments->filterAppointmentsByIdOrName($col, $match, $adminId);
                    $resultData = json_decode($resultData);
                    if($resultData->status){
                        $resultData = $resultData->data;
                    } else {
                        $resultData = array();
                    }

                    // print_r($resultData);
                    $dataArray = array();
                    foreach($resultData as $resultData){
                        $doctorData = $Doctors->showDoctorNameById($resultData->doctor_id);
                        $doctorData = json_decode($doctorData);
                        $doctorName = $doctorData->data;
                        
                        array_push($dataArray, array('appointment_id' => $resultData->appointment_id,
                         'patient_id' => $resultData->patient_id,
                         'patient_name' => $resultData->patient_name, 
                         'doc_name' => $doctorName, 
                         'appointment_date' => $resultData->appointment_date));
                    }
                    // print_r($dataArray);


                    if (!empty($dataArray)) {
                        
                        $allAppointmentsData = $dataArray;
                        
                        if (is_array($allAppointmentsData)) {
                            // print_r($allAppointmentsData);
                            $response = json_decode($Pagination->arrayPagination($allAppointmentsData));
                            
                            $slicedAppointments = '';
                            $paginationHTML = '';
                            $totalItem = $slicedAppointments = $response->totalitem;
                
                            if ($response->status == 1) {
                                $slicedAppointments = $response->items;
                                $paginationHTML = $response->paginationHTML;
                                // print_r($paginationHTML);
                                $response = ['data'=>$slicedAppointments, 'pagination'=>$paginationHTML];

                                echo json_encode($response);
                            }
                        } else {
                            $totalItem = 0;
                        }
                    } else {
                        $totalItem = 0;
                        $paginationHTML = '';
                    }
                } 
                
                break;
            case 'patients-search':

                if (strlen($match) >= 2) {
                    // echo 'Search For => patients-search and Data=> '.$match;
                        if (preg_match('/\d/', $match)) {
                            $col = 'patient_id';
                        } else {
                            $col = 'patient_name';
                        }
                        if ($match) {

                            // $filterPatient = $Patients->filterPatient($col, $match, $adminId);
                            // $filterPatient = json_decode($filterPatient);
                            // // print_r($filterPatient);
                            // if ($filterPatient->status == 1) {
                            //     $patientData = $filterPatient->data[0];
                            //     $patientID   = $patientData->patient_id;
                            //     $patientName = $patientData->name;
                            //     $patientAge  = $patientData->age;
                            //     $patientContact  = $patientData->phno;
                            //     $patientVisit    = $patientData->visited;
                            //     $patientLabVisit = $patientData->lab_visited;
                            //     $patientPin      = $patientData->patient_pin;
                            //     echo "
                            //     <div class='table-responsive'>
                            //     <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
                            //         <thead>
                            //             <tr>
                            //                 <th>Patient ID</th>
                            //                 <th>Patient Name</th>
                            //                 <th>Age</th>
                            //                 <th>Contact</th>
                            //                 <th>Visits</th>
                            //                 <th>Area PIN</th>
                            //                 <th class='text-center'>View</th>
                            //             </tr>
                            //         </thead>
                            //         <tbody>
                            //         </tbody>
                            //         <tr>
                            //         <td>$patientID</td>
                            //         <td>$patientName</td>
                            //         <td>$patientAge</td>
                            //         <td>$patientContact</td>
                            //         <td class='align-middle pb-0 pt-0'>
                            //                  <small class='small'>
                            //                      <span>Doctor: $patientVisit</span>
                            //                      <br>
                            //                      <span>Lab: $patientLabVisit</span></small>
                            //              </td>
                            //         <td>$patientPin</td>     
                            //              <td class='text-center'>
                            //              <a class='text-primary' href='patient-details.php?patient=. url_enc($patientID).'
                            //                  title='View and Edit'><i class='fas fa-eye'></i>
                            //              </a>
                            //          </td>
                            //         </tr>
                            //         </table>
                            //         </div?
                            //     ";

                            // } 
                           
                            
                        }
                    }
                else {
                    echo 'Please Enter Minimum 3 character';
                }
                break;
            default:
                echo 'Nothing';
                break;
        }
    }
}

exit;

$StockReturn    = new StockReturn();
$Distributor = new Distributor();


$today = date("Y-m-d");
$value1 = date("Y-m-d");
$value2 = date("Y-m-d");

if ($_GET['table'] !== null && $_GET['value'] !== null && $_GET['fromDate'] !== null && $_GET['toDate'] !== null) {

    $table = ($_GET['table']);
    $value = ($_GET['value']);
    $from_date = ($_GET['fromDate']);
    $to_date = ($_GET['toDate']);

    // echo "<br>Table Name : $table";
    // echo "<br>Table Value : $value";
    // echo "<br>From Date : $from_date";
    // echo "<br>To Date : $to_date";

    if ($table == 'added_by' || $table == 'distributor_id' || $table == 'refund_mode') {
        $n = 1;
    } elseif ($table == 'added_on' && $value != 'CR') {
        $n = 2;
    } elseif ($table == 'added_on' && $value == 'CR') {
        $n = 3;
    }

    // echo "<br>check switch : $n";

    switch ($n) {
        case 1:
            // echo "<br>this is case 1";
            $data1 = $StockReturn->stockReturnFilter($table, $value);
            $data = $data1;
            break;
        case 2:
            // echo "<br>this is case 2";
            if ($value == 'T') {
                $fromDate = date("Y-m-d");
                $toDate = date("Y-m-d");
            } elseif ($value == 'Y') {
                $fromDate = date("Y-m-d", strtotime("yesterday"));
                $toDate = date("Y-m-d", strtotime("yesterday"));
            } elseif ($value == 'LW') {
                $fromDate = date("Y-m-d", strtotime("-7 days"));
                $toDate = date("Y-m-d");
            } elseif ($value == 'LM') {
                $fromDate = date("Y-m-d", strtotime("-30 days"));
                $toDate = date("Y-m-d");
            } elseif ($value == 'LQ') {
                $fromDate = date("Y-m-d", strtotime("-90 days"));
                $toDate = date("Y-m-d");
            } elseif ($value == 'CFY') {
                $crntYear = date("Y");
                $crntMnth = date("m");
                if ($crntMnth < 4) {
                    $yr = $crntYear - 1;
                    $fromDate = date("$yr-04-01");
                    $toDate = date("Y-m-d");
                } else {
                    $fromDate = date("Y-04-01");
                    $toDate = date("Y-m-d");
                }
            } elseif ($value == 'PFY') {
                $crntYear = date("Y");
                $crntMnth = date("m");
                $yr = $crntYear - 1;
                if ($crntMnth < 4) {
                    $frmYr = $crntYear - 2;
                    $toYr = $crntYear - 1;
                    $fromDate = date("$frmYrr-04-01");
                    $toDate = date("$toYr-03-31");
                } else {
                    $frmYr = $crntYear - 1;
                    $toYr = $crntYear;
                    $fromDate = date("$frmYr-04-01");
                    $toDate = date("$toYr-03-31");
                }
            }

            // echo "<br>from date : $fromDate";
            // echo "<br>to date : $toDate";

            $data2 = $StockReturn->stockReturnFilterbyDate($table, $fromDate, $toDate);
            $data = $data2;

            break;
        case 3:
            // echo "<br>this is case 3";
            $fromDate = $from_date;
            $toDate = $to_date;
            if ($fromDate <= $toDate) {
                $data3 = $StockReturn->stockReturnFilterbyDate($table, $fromDate, $toDate);
                $data = $data3;
            } else {
                echo "DATE RANGE IS NOT ACCURATE";
            }
            break;
        default:
            echo "<br>default case";
    }
    // print_r($data);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Filter</title>
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.css">

</head>

<body>

    <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
        <thead class="bg-primary text-light">
            <tr>
                <th>Return Id</th>
                <th>Distributor</th>
                <th>Return Date</th>
                <th>Entry Date</th>
                <th>Entry By</th>
                <th>Payment Mode</th>
                <th>Amount</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            if ($data) {
                // print_r($data);
                foreach ($data as $row) {
                    $distId = $row['distributor_id'];
                    $distributorData = $Distributor->showDistributorById($distId);
                    // print_r($distData);
                    foreach ($distributorData as $distData) {
                        $distName = $distData['name'];
                    }

            ?>
                    <tr>
                        <td><?php echo $row['id'] ?></td>
                        <td><?php echo $distName ?></td>
                        <td><?php echo $row['return_date'] ?></td>
                        <td><?php echo $row['added_on'] ?></td>
                        <td><?php echo $row['added_by'] ?></td>
                        <td><?php echo $row['refund_mode'] ?></td>
                        <td><?php echo $row['refund_amount'] ?></td>
                        <td>
                            <a href="stock-return-edit.php?returnId='<?php echo $row['id'] ?>'" class="text-primary ml-4"><i class="fas fa-edit"></i></a>
                            <a class="text-danger ml-2" onclick="cancelPurchaseReturn('<?php echo $row['id'] ?>', this)"><i class="fas fa-window-close"></i></a>
                        </td>
                    </tr>
                <?php
                }
            } else { ?>
                <tr>
                    <td> <?php echo "No Data" ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <?php
    // }


    ?>


    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <script src="<?= PLUGIN_PATH ?>product-table/jquery.dataTables.js"></script>
    <script src="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script>

</body>

</html>