<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'doctors.class.php';
require_once CLASS_DIR.'appoinments.class.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'sub-test.class.php';
require_once CLASS_DIR.'labAppointments.class.php';

//Classes Initilized
$appointments    = new Appointments();
$Patients        = new Patients();
$LabAppointments = new LabAppointments();
$SubTests        = new SubTests();
$Doctors         = new Doctors();

//Function Initilized
$showDoctors    = $Doctors->showDoctors();
$showSubTests   = $SubTests->showSubTests();


if (isset($_SESSION['appointment-data'])) {
    $data = $_SESSION['appointment-data'];

    $patientId          = $data['patientId'];
    $testDate    = $data['appointmentDate'];
    $patientName        = $data['patientName'];
    $patientGurdianName = $data['patientGurdianName'];
    $patientEmail       = $data['patientEmail'];
    $patientPhoneNumber = $data['patientPhoneNumber'];
    $patientAge         = $data['patientAge'];
    $patientWeight      = $data['patientWeight'];
    $gender             = $data['gender'];
    $patientAddress1    = $data['patientAddress1'];
    $patientAddress2    = $data['patientAddress2'];
    $patientPS          = $data['patientPS'];
    $patientDist        = $data['patientDist'];
    $patientPIN         = $data['patientPIN'];
    $patientState       = $data['patientState'];
    $patientDoctor      = $data['patientDoctor'];

    unset($_SESSION['appointment-data']);
}


// exit;

// if (isset($_POST['update-lab-visit'])) {
//     $patientId          = $_POST["patientId"];
//     $testDate           = $_POST["testDate"];
//     $patientName        = $_POST["patientName"];
//     $patientGurdianName = $_POST["patientGurdianName"];
//     $patientEmail       = $_POST["patientEmail"];
//     $patientPhoneNumber = $_POST["patientPhoneNumber"];
//     $patientAge         = $_POST["patientAge"];
//     $patientWeight      = $_POST["patientWeight"];
//     $gender             = $_POST["gender"];
//     $patientAddress1    = $_POST["patientAddress1"];
//     $patientAddress2    = $_POST["patientAddress2"];
//     $patientPS          = $_POST["patientPS"];
//     $patientDist        = $_POST["patientDist"];
//     $patientPIN         = $_POST["patientPIN"];
//     $patientState       = $_POST["patientState"];
// }

if(isset($_POST['bill-proceed'])){
    if(isset($_POST['patientId'])){
        $exist          = TRUE;
        $patientId      = $_POST['patientId'];
        $testDate       = $_POST["testDate"];

        if ($exist == TRUE) {
            $rowPatients = json_decode($Patients->patientsDisplayByPId($patientId));
            // print_r($rowPatients);
            $patientName            = $rowPatients->name;
            $patientGurdianName     = $rowPatients->gurdian_name;
            $patientEmail           = $rowPatients->email;
            $patientPhoneNumber     = $rowPatients->phno;
            $patientAge             = $rowPatients->age;
            $gender                 = $rowPatients->gender;
            $patientAddress1        = $rowPatients->address_1;
            $patientAddress2        = $rowPatients->address_2;
            $patientPS              = $rowPatients->patient_ps;
            $patientDist            = $rowPatients->patient_dist;
            $patientPIN             = $rowPatients->patient_pin;
            $patientState           = $rowPatients->patient_state;
        }
    }
    
}



?>

<!doctype html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.css">
    <!-- <link rel="stylesheet" href="../css/patient-style.css"> -->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/custom-form-style.css">


    <link rel="stylesheet" href="<?php echo CSS_PATH ?>font-awesome.css">
    <title>Lab Test Bill Generate - Medicy Health Care</title>


    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/appointment.css">


</head>

<body>

    <!-- Page Wrapper -->

    <div id="wrapper">

        <!-- sidebar -->
        <?php include SUP_ROOT_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include SUP_ROOT_COMPONENT.'topbar.php'; ?>
                <!-- End of top bar -->


                <div class="container-fluid px-1  mx-auto">
                    <div class="row d-flex justify-content-center">
                        <div class="col-xl-5 col-lg-5 col-md-5 text-center">
                            <div class="card shadow p-4 mt-0">
                                <div class="row justify-content-between text-left">
                                    <div class="form-group col-sm-12 flex-column d-flex">
                                        <div class="row justify-content-start">
                                            <div class="col-md-5 mb-0">
                                                <p>Patient Name: </p>
                                            </div>
                                            <div class="col-md-7 mb-0 justify-content-start">
                                                <p class="text-start"><b><?=  $patientName; ?> </b></p>
                                            </div>

                                            <div class="col-md-5 mb-0">
                                                <p>Patient ID: </p>
                                            </div>
                                            <div class="col-md-7 mb-0 justify-content-start">
                                                <p class="text-start"><b><?php echo $patientId; ?></b></p>
                                            </div>
                                            <div class="col-md-5 mb-0">
                                                <p>Test Date: </p>
                                            </div>
                                            <div class="col-md-7 mb-0 justify-content-start">
                                                <p class="text-start"><b><?php echo $testDate; ?> </b></p>
                                            </div>

                                            <div class="col-md-5 mb-0">
                                                <p>Rrefered Doctor: </p>
                                            </div>
                                            <div class="col-md-7 mb-0 justify-content-start">
                                                <p class="text-start"><b><span id="preferedDoc"> </span></b></p>
                                            </div>

                                        </div>

                                    </div>
                                </div>


                                <div class="row justify-content-between text-left">
                                    <div class="form-group col-sm-12 flex-column d-flex my-0">
                                        <label class="form-control-label" for="patientDoctor">Rreffered By</label>
                                        <select id="docList" class="form-control" name="patientDoctor"
                                            onChange="getDoc()" required>
                                            <option disabled selected>Select</option>
                                            <option value="">New Doctor</option>
                                            <option value="Self">By Self</option>

                                            <?php
                                            if($showDoctors){
                                                $showDoctors = json_decode($showDoctors, true);
                                            if($showDoctors && $showDoctors['status'] == 1 && !empty($showDoctors['data'])){
                                                foreach ($showDoctors['data'] as $showDoctorDetails) {
                                                    $doctorId = $showDoctorDetails['doctor_id'];
                                                    $doctorName = $showDoctorDetails['doctor_name'];
                                                    echo'<option value="'.$doctorId.'">'. $doctorName.'</option>';
                                                }
                                            }
                                        }
                                                ?>
                                        </select>
                                    </div>

                                    <div class="justify-content-center text-center">
                                        or
                                    </div>

                                    <div class="form-group col-sm-12 flex-column d-flex mt-0">
                                        <input type="text" id="docName" class="form-control"
                                            placeholder="Enter Doctor Name" onkeyup="newDoctor(this.value);">
                                    </div>
                                </div>

                                <div class="row justify-content-between text-left">
                                    <div class="form-group col-sm-12 flex-column d-flex mt-0">
                                        <input type="text" id="test-name" hidden>
                                        <input type="text" id="test-id" hidden>
                                        <select id="test" class="form-control" name="test" onChange="getPrice()"
                                            required disabled>
                                            <option disabled selected>Select Test</option>
                                            <?php
                                                foreach ($showSubTests as $rowSubTests) {
                                                    $subTestId   = $rowSubTests['id'];
                                                    $subTestName = $rowSubTests['sub_test_name'];
                                                    echo'<option value='.$subTestId.'>'. $subTestName.'</option>';
                                                }
                                                ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-between text-left">
                                    <div class="form-group col-sm-5 flex-column d-flex mt-0">
                                        <p class="form-control">Price ₹<span id="price">0</span>/Test</p>
                                    </div>

                                    <div class="form-group col-sm-5 flex-column d-flex mt-0">
                                        <input class="form-control" id="disc" onkeyup="getDisc(this.value);"
                                            placeholder="Discount %" type="number" disabled>
                                    </div>
                                </div>
                                <div class="row justify-content-between text-left">
                                    <div class="form-group col-sm-6 flex-column d-flex mt-0">
                                        <p class="form-control">Total ₹ <span id="total"></span></p>
                                    </div>
                                    <div class="form-group col-sm-5 flex-column d-flex mt-0">
                                        <button class="btn btn-primary" id="add-bill-btn" type="button"
                                            onClick="getBill()" disabled>Add to Bill <i
                                                class="fa fa-arrow-right"></i></button>
                                    </div>
                                </div>


                            </div>
                        </div>


                        <div class="col-xl-7 col-lg-7 col-md-7 text-center">
                            <div class="card shadow p-4 mt-0">
                                <form class="form-card" action="_config/form-submission/tests-bill-invoice.php" method="post">
                                    <input type="hidden" name="patientId" value="<?php echo $patientId; ?>">
                                    <input type="hidden" name="patientName" value="<?php echo $patientName; ?>">

                                    <input type="hidden" name="patientAge" value="<?php echo $patientAge; ?>">
                                    <input type="hidden" name="patientGender" value="<?php echo $gender; ?>">
                                    <input type="hidden" name="patientPhnNo" value="<?php echo $patientPhoneNumber; ?>">
                                    <input type="hidden" name="patientTestDate" value="<?php echo $testDate;?>">
                                    <input type="hidden" name="prefferedDocId" id="prefferedDocId">
                                    <input type="hidden" name="refferedDocName" id="refferedDocName">


                                    <!-- Header Row -->
                                    <div class="row justify-content-between text-left my-0 py-0">
                                        <div class="form-group col-sm-2 flex-column my-0 py-0 d-flex">
                                            <p class="my-0 py-0">SL. No. </p>
                                        </div>
                                        <div class="form-group col-sm-3 flex-column mb-0 mt-0 d-flex">
                                            <p class="my-0 py-0 ">Description</p>
                                        </div>
                                        <div class="form-group col-sm-2 flex-column mb-0 mt-0 d-flex">
                                            <p class="my-0 py-0 ">Price ₹</p>
                                        </div>
                                        <div class="form-group col-sm-2 flex-column mb-0 mt-0 d-flex">
                                            <p class="my-0 py-0 ">Disc %</p>
                                        </div>
                                        <div class="form-group col-sm-2 flex-column my-0 py-0 d-flex">
                                            <p class="my-0 py-0 text-end">Amount</p>
                                        </div>
                                        <div class="form-group col-sm-1 flex-column my-0 py-0 d-flex">
                                            <p class="my-0 py-0 text-end"></p>
                                        </div>
                                    </div>
                                    <!--/END Header Row -->
                                    <hr>
                                    <!-- Test List Row -->
                                    <div id="lists">
                                        <!-- Items are shown here by jquery -->
                                    </div>
                                    <input type="text" id="dynamic-id" value="0" hidden>
                                    <!--/END Test List Row -->

                                    <hr>
                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <p class="mb-1">Total: </p>
                                        </div>
                                        <div class="form-group col-sm-5 flex-column d-flex ">
                                            <input type="number" name="total" id="total-test-price" value="00" hidden>
                                            <p class="mb-1 text-end">₹ <span id="total-view"></span></p>
                                        </div>
                                        <div class="form-group col-sm-1 flex-column d-flex">
                                            <p class="mb-1 text-end"> </p>
                                        </div>
                                    </div>

                                    <!-- ################################################## -->
                                    <div class="row justify-content-between text-left calculation">
                                        <div class="form-group col-sm-9 flex-column d-flex">
                                            <p class="mb-1">Payable: </p>
                                        </div>
                                        <div class="form-group col-sm-3 flex-column d-flex ">
                                            <input class="myForm text-center" id="payable" name="payable"
                                                onkeyup="getLessAmount(this.value)" type="number" value="00" required>
                                        </div>

                                    </div>
                                    <!-- ################################################## -->

                                    <div onload="disabledField();" class="row justify-content-between text-left">
                                        <div class="form-group col-sm-3 flex-column d-flex">
                                            <label class="form-control-label" for="">Update</label>
                                            <select class="form-control" onchange="updateBill(this.value)" name="status"
                                                id="update" required>
                                                <option value="" disabled selected>Select Update</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Partial Due">Partial Due</option>
                                                <option value="Credit">Credit</option>
                                            </select>

                                            <!-- <span style="color:red;">*Update Status </span> -->

                                        </div>
                                        <div class="form-group col-sm-3 flex-column d-flex ">
                                            <label class="form-control-label" for="">Due Amount</label>
                                            <input class="myForm text-center" name="due" id="due" type="number"
                                                onkeyup="dueAmount(this.value)" required readonly>
                                        </div>
                                        <div class="form-group col-sm-3 flex-column d-flex">
                                            <label class="form-control-label" for="less-amount">Less Amount</label>
                                            <input class="myForm text-center" id="less-amount" name="less_amount"
                                                type="any" value="00" readonly>
                                        </div>
                                        <div class="form-group col-sm-3 flex-column d-flex">
                                            <label class="form-control-label" for="">Paid Amount</label>
                                            <input class="myForm text-center" name="paid_amount" id="paid-amount"
                                                type="number" onkeyup="paidAmount(this.value)" required readonly>
                                        </div>
                                    </div>

                                    <div class="row justify-content-end">
                                        <button class="btn btn-primary w-25" type="submit" id="bill-generate"
                                            name="bill-generate" disabled>Generate Bill</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!--/End Part 1  -->


                </script>
                <!-- Footer -->
                <?php include SUP_ROOT_COMPONENT.'footer-text.php'; ?>
                <!-- End of Footer -->

                <!-- Bootstrap core JavaScript-->
                <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
                <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
                <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>


                <!-- Core plugin JavaScript-->
                <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

                <!-- Custom scripts for all pages-->
                <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>
                <script src="<?php echo JS_PATH ?>custom/lab-billing.js"></script>

</body>

</html>