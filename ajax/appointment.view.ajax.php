<?php
require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
require_once SUP_ADM_DIR . '_config/sessionCheck.php';
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'appoinments.class.php';
require_once CLASS_DIR.'doctors.class.php';

//get appointment dbtabloe id 
$appointmentTableId = $_GET['appointmentTableID'];

// appointments class initilization
$appointments = new Appointments();

// variable for showing appointment status updation
$updated = false;

if(isset($_POST['update'])){
    // echo $appointmentTableId;
    // exit;
    $patientName         = $_POST['patientName'];
    $patientGurdianName  = $_POST['patientGurdianName'];
    $patientEmail        = $_POST['patientEmail'];
    $patientPhoneNumber  = $_POST['patientPhoneNumber'];
    $appointmentDate     = $_POST['appointmentDate'];
    $patientWeight       = $_POST['patientWeight'];
    $patientDOB          = $_POST['patientDOB'];
    $gender              = $_POST['gender'];
    $patientAddress1     = $_POST['patientAddress1'];
    $patientAddress2     = $_POST['patientAddress2'];
    $patientPS           = $_POST['patientPS'];
    $patientDist         = $_POST['patientDist'];
    $patientPIN          = $_POST['patientPIN'];
    $patientState        = $_POST['patientState'];
    $patientDoctor       = $_POST['patientDoctor'];
    $patientDoctorTiming = $_POST['doctorTiming'];
    
    $updateAppointment = $appointments->updateAppointmentsbyTableId($appointmentDate,$patientName,$patientGurdianName,$patientEmail,      $patientPhoneNumber,$patientDOB,$patientWeight,$gender,$patientAddress1,$patientAddress2,$patientPS,$patientDist,$patientPIN,$patientState,$patientDoctor,$patientDoctorTiming, /*Last Parameter For Appointment Id Which Details You Want to Update*/$appointmentTableId);

    if($updateAppointment){
        $updated = true;
    }
    
    
}



$showAppointments = $appointments->appointmentsDisplaybyTableId($appointmentTableId);

foreach ($showAppointments as $appointment) {
    $appointmentTableId         = $appointment['id'];
    $appointmentId              = $appointment['appointment_id'];
    $appointmentDate            = $appointment['appointment_date'];
    $PatientName                = $appointment['patient_name'];
    $PatientGurdianName         = $appointment['patient_gurdian_name'];
    $PatientEmail               = $appointment['patient_email'];
    $PatientPhno                = $appointment['patient_phno'];
    $PatientAge                 = $appointment['patient_age'];
    $PatientWeight              = $appointment['patient_weight'];
    $PatientGender              = $appointment['patient_gender'];
    $PatientAddress1            = $appointment['patient_addres1'];
    $PatientAddress2            = $appointment['patient_addres2'];
    $PatientPs                  = $appointment['patient_ps'];
    $PatientDist                = $appointment['patient_dist'];
    $PatientPin                 = $appointment['patient_pin'];
    $PatientState               = $appointment['patient_state'];
    $getDoctorForPatient        = $appointment['doctor_id'];
    $PatientDocShift            = $appointment['patient_doc_shift'];
}


?>

<head>
    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="../css/lab-test.css"> -->

</head>

<body>
    <div class="mx-3">
        <form class="form-card" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <input type="hidden" id="editCatDtlsId" name="nm_option" value="<?php // echo $;
                                                                                    ?>">
                    <label class="form-label px-3" for="patientName">Patient Name<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="patientName" name="patientName" placeholder="Enter Patient Name" value="<?php echo $PatientName; ?>">
                </div>
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-label px-3" for="patientGurdianNAme">Patient's Gurdian Name<span class="text-danger"> *</span></label>
                    <input class="form-control" type="text" id="patientGurdianName" name="patientGurdianName" placeholder="Enter Patient's Gurdian Name" value="<?php echo $PatientGurdianName; ?>">
                </div>
            </div>

            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-label px-3" for="patientEmail">Patient Email</label>
                    <input class="form-control" type="text" id="patientEmail" name="patientEmail" placeholder="yourname@email.com" value="<?php echo $PatientEmail; ?>">
                </div>
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-label px-3" for="patientPhoneNumber">Phone number<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="patientPhoneNumber" name="patientPhoneNumber" placeholder="0123456789" value="<?php echo $PatientPhno; ?>">
                </div>
            </div>

            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-label px-3" for="appointmentDate">Appointment Date<span class="text-danger">*</span></label>
                    <input class="form-control" type="date" id="appointmentDate" name="appointmentDate" placeholder="DD-MM-YYYY" value="<?php echo $appointmentDate; ?>">
                </div>
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-label px-3" for="patientWeight">Weight <small>(in kg)</small><span class="text-danger"> *</span></label>
                    <input class="form-control" type="text" id="patientWeight" name="patientWeight" placeholder="Patient's Weight" maxlength="3" value="<?php echo $PatientWeight; ?>">
                </div>
            </div>

            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-label px-3" for="patientDOB">Age<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="patientDOB" name="patientDOB" placeholder="Patient's Date of birth" maxlength="3" minlength="1" value="<?php echo $PatientAge; ?>">
                </div>

                <div class="col-sm-6 mt-4">
                    <label class="form-label mb-3 mr-1" for="gender">Gender: </label>

                    <input type="radio" class="btn-check" name="gender" id="male" value="Male" autocomplete="off"
                    <?= $PatientGender == "Male" ? "checked" : ''; ?> required>
                    <label class="btn btn-sm btn-outline-secondary" for="male" value="Male">Male</label>

                    <input type="radio" class="btn-check" name="gender" id="female" value="Female" autocomplete="off" 
                    <?= $PatientGender == "Female" ? "checked" : ''; ?> required>
                    <label class="btn btn-sm btn-outline-secondary" for="female" value="Female">Female</label>

                    <input type="radio" class="btn-check" name="gender" id="secret" value="Secret" autocomplete="off"
                    <?= $PatientGender == "Secret" ? "checked" : '' ; ?> required>
                    
                    <label class="btn btn-sm btn-outline-secondary" for="secret" value="Secret">Secret</label>
                    <div class="valid-feedback mv-up">You selected a gender!</div>
                    <div class="invalid-feedback mv-up">Please select a gender!</div>
                </div>
            </div>

            <h5 class="text-center mb-4 mt-5">Patient Address</h5>

            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-label px-3" for="patientAddress1">Address Line 1<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="patientAddress1" name="patientAddress1" placeholder="Enater Patient's Address 1" value="<?php echo $PatientAddress1; ?>">
                </div>

                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-label px-3" for="patientAddress2">Address Line 2<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="patientAddress2" name="patientAddress2" placeholder="Enater Patient's Address 2" value="<?php echo $PatientAddress2; ?>">
                </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-label px-3" for="patientPS">Police Station<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="patientPS" name="patientPS" placeholder="Enater Police Station" value="<?php echo $PatientPs; ?>">
                </div>

                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-label px-3" for="patientDist">District<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="patientDist" name="patientDist" placeholder="Enater District" value="<?php echo $PatientDist; ?>">
                </div>
            </div>

            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-label px-3" for="patientPIN">PIN Code<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="patientPIN" name="patientPIN" placeholder="Enter PIN Code" maxlength="7" value="<?php echo $PatientPin; ?>">
                </div>

                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-label px-3" for="patientState">State<span class="text-danger">*</span></label>
                    <select class="browser-default custom-select" id="dropSelection" name="patientState">
                        <option value="West bengal" <?php if ($PatientState == "West bengal") {
                                                        echo "selected";
                                                    } ?>>West
                            Bengal</option>
                        <option value="Assam" <?php if ($PatientState == "Assam") {
                                                    echo "selected";
                                                } ?>>Assam</option>
                        <option value="Kerala" <?php if ($PatientState == "Kerala") {
                                                    echo "selected";
                                                } ?>>Kerala</option>
                        <option value="Delhi" <?php if ($PatientState == "Delhi") {
                                                    echo "selected";
                                                } ?>>Delhi</option>
                    </select>
                </div>
            </div>

            <h5 class="text-center mb-4 mt-5">Select Doctor</h5>

            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-label px-3" for="patientDoctor">Doctor Name<span class="text-danger">*</span></label>
                    <select id="docList" class="browser-default custom-select" name="patientDoctor" onChange="getShift()" required>

                        <?php
                        $doctors = new Doctors();
                        $showDocForPatient = $doctors->showDoctorsForPatient($getDoctorForPatient);
                        foreach ($showDocForPatient as $docForPatient) {
                            $docForPatientId = $docForPatient['doctor_id'];
                            $docForPatientName = $docForPatient['doctor_name'];
                            if ($docForPatientId == $getDoctorForPatient) {
                                echo '<option value="' . $docForPatientId . '">' . $docForPatientName . '</option>';
                            }
                        }
                        ?>

                        <?php
                        $showDoctors = $doctors->showDoctors($adminId);
                        $showDoctors = json_decode($showDoctors, true);
                        if($showDoctors && $showDoctors['status'] == 1 && !empty($showDoctors))
                        foreach ($showDoctors['data'] as $doctordetails) {
                            $doctorId = $doctordetails['doctor_id'];
                            $doctorName = $doctordetails['doctor_name'];
                            echo '<option value="' . $doctorId . '">' . $doctorName . '</option>';
                        }
                        ?>

                    </select>
                </div>
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-label px-3" for="doctorTiming">Time Slot<span class="text-danger">*</span></label>
                    <select id="shiftList" class="browser-default custom-select" name="doctorTiming">
                        <option value="" id="shiftList" onChange="getShiftValues()"><?php echo $PatientDocShift; ?></option>

                        <!-- Option goes here by ajax -->
                    </select>
                </div>
            </div>

            <?php
            if ($updated) {
                echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Appointment has been updated.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            }
            ?>

            <div class="row justify-content-end my-4">
                <button type="submit" class="btn-block btn-primary btn btn-sm" name="update">Update</button>
            </div>
        </form>

    </div>

    <script>
        function editLabCatDtls() {
            let editCatDtlsId = $("#editCatDtlsId").val();
            let editTestCategoryName = document.getElementById("editTestCategoryName").value;
            let editTestCategoryProvidedBy = document.getElementById("editTestCategoryProvidedBy").value;
            let editTestCategoryDsc = document.getElementById("editTestCategoryDsc").value;
            // console.log(editTestCategoryDsc);
            let url = "updateLabCat-Ajax.php?editCatDtlsId=" + escape(editCatDtlsId) + "&editTestCategoryName=" + escape(
                    editTestCategoryName) + "&editTestCategoryProvidedBy=" + escape(editTestCategoryProvidedBy) +
                "&editTestCategoryDsc=" + escape(editTestCategoryDsc);
            // console.log(url);
            // alert('Working');
            // $("#reportUpdate").html('<iframe width="99%" height="40px" frameborder="0" allowtransparency="true" src="'+url+'"></iframe>');
            // alert("Hello");
            request.open('GET', url, true);

            request.onreadystatechange = getEditUpdates;

            request.send(null);
        }
        getEditUpdates = () => {
            if (request.readyState == 4) {
                if (request.status == 200) {
                    var xmlResponse = request.responseText;
                    document.getElementById('reportUpdate').innerHTML = xmlResponse;
                } else if (request.status == 404) {
                    alert("Request page doesn't exist");
                } else if (request.status == 403) {
                    alert("Request page doesn't exist");
                } else {
                    alert("Error: Status Code is " + request.statusText);
                }
            }
        }

        //Function to get the doctors shhift in the dropdown
        function getShift() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET", "appointment.getdoc.ajax.php?doctor_shift=" + document.getElementById("docList").value, false);
            xmlhttp.send(null);
            document.getElementById("shiftList").innerHTML = xmlhttp.responseText;
        }

        function getShiftValues() {

            // document.getElementById('shiftValue').innerHTML= ("The Selected Dropdown value is: "+formid.doctorTiming[formid.doctorTiming.selectedIndex].text)
            var getShiftValues = document.getElementById("shiftList").value;
            console.log(getShiftValues);
        }
    </script>

    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?php echo PLUGIN_PATH ?>bootstrap-5.0.2/js/bootstrap.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>bootstrap-5.0.2/js/bootstrap.min.js"></script>


    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>


</body>

</html>