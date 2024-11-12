<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

require_once __DIR__.'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once ROOT_DIR . '_config/hralthcare.inc.php';

require_once CLASS_DIR.'appoinments.class.php';
require_once CLASS_DIR.'doctors.class.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'labAppointments.class.php';


//Classes Initilizing
$appointments    = new Appointments();
$Patients        = new Patients();
$LabAppointments = new LabAppointments();

$exist = FALSE;



if(isset($_POST['bill-proceed'])){
    $patientId = $_POST['patientId'];
    $exist = TRUE;
    if ($exist == TRUE) {
        $patientsDetails = json_decode($Patients->patientsDisplayByPId($patientId));
            $patientName    = $rowPatients->name;
            $patientGurdian = $rowPatients->gurdian_name;
            $patientEmail   = $rowPatients->email;
            $patientPhno    = $rowPatients->phno;
            $patientAge     = $rowPatients->age;
            $patientGender  = $rowPatients->gender;
            $patientAdd1    = $rowPatients->address_1;
            $patientAdd2    = $rowPatients->address_2;
            $patientPs      = $rowPatients->patient_ps;
            $patientDist    = $rowPatients->patient_dist;
            $patientPIN     = $rowPatients->patient_pin;
            $patientState   = $rowPatients->patient_state;
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
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>patient-style.css">
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <title>Enter Patient Details</title>


    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">


    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/appointment.css">

</head>

<body>

    <!-- Page Wrapper -->

    <div id="wrapper">

        <!-- sidebar -->
        <?php include ROOT_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include ROOT_COMPONENT.'topbar.php'; ?>
                <!-- End of top bar -->


                <div class="container-fluid px-1  mx-auto">
                    <div class="row d-flex justify-content-center">
                        <div class="col-xl-8 col-lg-9 col-md-10 text-center">
                            <div class="card mt-0">
                                <h4 class="text-center mb-4 mt-0"><b>Fill The Patient Details</b></h4>
                                <form class="form-card" action="lab-billing.php" method="post">
                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientName">Patient Name<span
                                                    class="text-danger"> *</span></label>
                                            <input type="text" id="patientName" name="patientName" placeholder="Enter Patient Name" value="<?php if($exist){ echo $patientName;}?>" required>
                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientGurdianName">Patient's Gurdian Name<span class="text-danger"> *</span></label>
                                            <input type="text" id="patientGurdianName" name="patientGurdianName" placeholder="Enter Patient's Gurdian Name" value="<?php if($exist){ echo $patientGurdian;}?>" required>
                                        </div>
                                    </div>

                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientEmail">Patient Email</label>
                                            <input type="text" id="patientEmail" name="patientEmail" placeholder="Patient Email" value="<?php if($exist){ echo $patientEmail;}?>">
                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientPhoneNumber">Phone number<span class="text-danger"> *</span></label>
                                            <input type="text" id="patientPhoneNumber" name="patientPhoneNumber" placeholder="Phone Number" maxlength="10" minlength="10" value="<?php if($exist){ echo $patientPhno;}?>" required>
                                        </div>

                                    </div>


                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="testDate">Test Date<span class="text-danger"> *</span></label>
                                            <input type="date" id="testDate" name="testDate" placeholder="" required>
                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientWeight">Weight <small>(in kg)</small><span class="text-danger"> *</span></label>
                                            <input type="text" id="patientWeight" name="patientWeight" placeholder="Weight in kg" maxlength="3" required>
                                        </div>

                                    </div>


                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientAge">Age<span class="text-danger"> *</span></label>
                                            <input type="text" id="patientAge" name="patientAge" placeholder="Age" maxlength="3" minlength="1" required>
                                        </div>

                                        <div class="col-sm-6 mt-4">
                                            <label class="mb-3 mr-1" for="gender">Gender: </label>

                                            <input type="radio" class="btn-check" name="gender" id="male" value="Male"
                                                autocomplete="off" <?php if($exist){ if($patientGender == "Male"){echo "checked";} }?> required>
                                            <label class="btn btn-sm btn-outline-secondary" for="male"
                                                value="Male">Male</label>


                                            <input type="radio" class="btn-check" name="gender" id="female"
                                                value="Female" autocomplete="off" <?php if($exist){ if($patientGender == "Female"){echo "checked";} }?> required>
                                            <label class="btn btn-sm btn-outline-secondary" for="female"
                                                value="Female">Female</label>


                                            <input type="radio" class="btn-check" name="gender" id="secret"
                                                value="Others" autocomplete="off" <?php if($exist){ if($patientGender == "Others"){echo "checked";} }?> required>
                                            <label class="btn btn-sm btn-outline-secondary" for="secret"
                                                value="Secret">Others</label>


                                            <div class="valid-feedback mv-up">You selected a gender!</div>

                                            <div class="invalid-feedback mv-up">Please select a gender!</div>

                                        </div>

                                    </div>



                                    <h5 class="text-center mb-4 mt-5">Patient Address</h5>
                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientAddress1">Address Line 1<span class="text-danger"> *</span></label>
                                            <input type="text" id="patientAddress1" name="patientAddress1" placeholder="Address Line 1" value="<?php if($exist){ echo $patientAdd1;}?>" required>
                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientAddress2">Address Line 2<span class="text-danger"> *</span></label>
                                            <input type="text" id="patientAddress2" name="patientAddress2" value="<?php if($exist){ echo $patientAdd2;}?>" placeholder="Address Line 2">
                                        </div>
                                    </div>

                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientPS">Police Station<span class="text-danger"> *</span></label>
                                            <input type="text" id="patientPS" name="patientPS" placeholder="Police Station" value="<?php if($exist){ echo $patientPs;}?>" required>
                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientDist">District<span class="text-danger"> *</span></label>
                                            <input type="text" id="patientDist" name="patientDist" placeholder="District" value="<?php if($exist){ echo $patientDist;}?>" required>
                                        </div>
                                    </div>


                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientPIN">PIN Code<span class="text-danger"> *</span></label>
                                            <input type="text" id="patientPIN" name="patientPIN" placeholder="Pin Code" maxlength="7" value="<?php if($exist){ echo $patientPIN;}?>" required>
                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientState">State<span class="text-danger"> *</span></label>
                                            <select id="dropSelection" name="patientState" required>
                                                <option disabled >Select State</option>
                                                <option <?php if($exist){ if($patientState == "West bengal"){echo "selected";} }?> value="West bengal">West Bengal</option>
                                                <option <?php if($exist){ if($patientState == "Other"){echo "selected";} }?> value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row justify-content-end">
                                        <div class="form-group col-sm-4">
                                            <button type="submit" name="submit" class="btn-block btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!--/End Part 1  -->


                </script>
                <!-- Footer -->
                <?php include ROOT_COMPONENT.'footer-text.php'; ?>
                <!-- End of Footer -->

                <!-- Bootstrap core JavaScript-->
                <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
                <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

                <!-- Core plugin JavaScript-->
                <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

                <!-- Custom scripts for all pages-->
                <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>

                <!-- Page level plugins -->
                <script src="<?php echo PLUGIN_PATH ?>chart.js/Chart.min.js"></script>

                <!-- Page level custom scripts -->
                <script src="<?php echo JS_PATH ?>demo/chart-area-demo.js"></script>
                <script src="<?php echo JS_PATH ?>demo/chart-pie-demo.js"></script>

                <script type="text/javascript">
                var todayDate = new Date();

                var date = todayDate.getDate();
                var month = todayDate.getMonth() + 1;
                var year = todayDate.getFullYear();

                if (date < 10) {
                    date = '0' + date;
                }
                if (month < 10) {
                    month = '0' + month;
                }
                var todayFullDate = year + "-" + month + "-" + date;
                console.log(todayFullDate);
                document.getElementById("testDate").setAttribute("min", todayFullDate);
                </script>


</body>

</html>