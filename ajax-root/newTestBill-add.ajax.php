<?php
$page = "appointments";
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


//Classes Initilizing
$HealthCare     = new HealthCare;
$doctors        = new Doctors();

$test = false;
if (isset($_GET['test'])) {
    if ($_GET['test'] == 'true') {
        $test = true;
    }
}

if (isset($_GET['searchName'])) {
    $searched = $_GET['searchName'];
}

$showDoctors = $doctors->showDoctors($adminId);
$showDoctors = json_decode($showDoctors);
$allDoctors  = $showDoctors->data;

$clinicInfo  = $HealthCare->showHealthCare($adminId);
$clinicInfo  = json_decode($clinicInfo, true);

if ($clinicInfo['status'] == 1) {
    $data = $clinicInfo['data'];
    $district = $data['dist'];
    $pin      = $data['pin'];
    $state    = $data['health_care_state'];
} else {
    echo "Error: " . $clinicInfo['msg'];
}

?>


<div class="container-fluid px-1  mx-auto">
    <div class="row d-flex justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12 text-center">
            <form id="patientForm" class="form-card " action="" method="post">
                <div class="row justify-content-between text-left">

                    <div class="form-group col-sm-6 mt-2">
                        <input class="med-input" type="text" maxlength="80" name="patientName" id="patientName"
                            value="<?= $searched; ?>" autocomplete="off" required>
                        <label class="med-label" for="patientName">Patient Name <span
                                class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-sm-6 mt-2">
                        <input class="med-input" type="text" maxlength="80" name="patientGurdianName"
                            id="patientGurdianName" placeholder="" autocomplete="off" required>
                        <label class="med-label" for="patientGurdianName">Gurdian Name <span
                                class="text-danger">*</span></label>
                    </div>

                </div>

                <div class="row justify-content-between text-left">

                    <div class="form-group col-sm-6 mt-2">
                        <input type="number" class="med-input" name="patientAge" id="patientAge" placeholder=""
                            onfocusout="checkAge(this)" maxlength="3" minlength="1" autocomplete="off" required>
                        <label class="med-label" for="patientAge">Age <span class="text-danger">*</span></label>
                        <p class='text-danger fs-6' id='ageMsg'></p>
                    </div>

                    <div class="col-sm-6 mt-4 mt-2">
                        <label class="mb-3 mr-1" for="gender">Gender: </label>
                        <input type="radio" class="btn-check" name="gender" id="male" value="Male" autocomplete="off"
                            required>
                        <label class="btn btn-sm btn-outline-secondary" for="male" value="Male">Male</label>
                        <input type="radio" class="btn-check" name="gender" id="female" value="Female"
                            autocomplete="off" required>
                        <label class="btn btn-sm btn-outline-secondary" for="female" value="Female">Female</label>
                        <input type="radio" class="btn-check" name="gender" id="secret" value="Others"
                            autocomplete="off" required>
                        <label class="btn btn-sm btn-outline-secondary" for="secret" value="Secret">Others</label>
                        <!-- <div class="valid-feedback mv-up">You selected a gender!</div>
                        <div class="invalid-feedback mv-up">Please select a gender!</div> -->
                    </div>
                </div>

                <div class="row justify-content-between text-left">
                    <?php if (!$test) : ?>

                    <div class="form-group col-sm-6 mt-2">
                        <input class="med-input" type="number" name="patientWeight" id="patientWeight" placeholder=""
                            onfocusout="checkWeight(this)" autocomplete="off">
                        <label class="med-label" for="patientWeight">Weight</label>
                        <p id="wghtMsg" style="color: red;"></p>
                    </div>

                    <?php endif; ?>

                    <div class="form-group col-sm-6 mt-2">
                        <input class="med-input" type="text" name="patientPhoneNumber" id="patientPhoneNumber"
                            placeholder="" onfocusout="checkContactNo(this)" maxlength="10" pattern="\d{10}" required
                            autocomplete="off">
                        <label class="med-label" for="patientPhoneNumber">Phone Number<span
                                class="text-danger">*</span></label>
                        <p id="pMsg" style="color: red;"></p>
                    </div>

                    <div class="form-group col-sm-6 mt-2">
                        <input class="med-input" type="date" name="appointmentDate" id="appointmentDate" placeholder=""
                            value="<?php print(date("Y-m-d")) ?>" required autocomplete="off" onclick="pickAppintmentDate()">
                        <label class="med-label" for="appointmentDate">Appointment Date<span
                                class="text-danger">*</span></label>
                    </div>

                    <?php if (!$test) : ?>

                    <div class="form-group col-sm-6 mt-2">
                        <input class="med-input" type="email" name="patientEmail" id="email" placeholder=""
                            onchange="checkMail(this)" autocomplete="off">
                        <label class="med-label" for="email">Email</label>
                        <p id='emailMsg' style="color: red;"></p>
                    </div>

                    <?php endif; ?>
                </div>

                <div class="row justify-content-between text-left">

                    <div class="form-group col-sm-6 mt-2">
                        <input class="med-input" type="text" id="patientAddress1" name="patientAddress1" placeholder=""
                            autocomplete="off">
                        <label class="med-label" for="patientAddress1">Address</label>
                    </div>

                    <?php if ($test) : ?>

                    <div class="form-group col-sm-6 mt-2">
                        <input class="med-input" type="email" name="patientEmail" id="email" placeholder=""
                            onchange="checkMail(this)" autocomplete="off">
                        <label class="med-label" for="email">Email</label>
                        <p id='emailMsg' style="color: red;"></p>
                    </div>

                    <?php endif; ?>
                    <?php if (!$test) : ?>

                    <div class="form-group col-sm-6 mt-2">
                        <input class="med-input" type="text" id="patientPS" name="patientPS" placeholder=""
                            autocomplete="off">
                        <label class="med-label" for="patientPS">Police Station</label>
                    </div>

                    <?php endif; ?>
                </div>

                <div class="row justify-content-between text-left">
                    <?php if ($test) : ?>

                    <div class="form-group col-sm-6 mt-2">
                        <input class="med-input" type="text" id="patientPS" name="patientPS" placeholder=""
                            autocomplete="off">
                        <label class="med-label" for="patientPS">Police Station</label>
                    </div>

                    <?php endif; ?>

                    <div class="form-group col-sm-6 mt-2">
                        <input class="med-input" type="text" id="patientDist" name="patientDist"
                            Value="<?= $district; ?>" placeholder="" autocomplete="off" required>
                        <label class="med-label" for="patientDist">District<span class="text-danger">*</span></label>
                    </div>

                    <?php if (!$test) : ?>

                    <div class="form-group col-sm-6 mt-2">
                        <input class="med-input" type="text" id="patientPIN" name="patientPIN"
                            onfocusout="checkPin(this)" Value="<?= $pin ?>" placeholder="" autocomplete="off" required>
                        <label class="med-label" for="patientPIN">PIN Code<span class="text-danger">*</span></label>
                        <span class='text-danger fs-6' id='pinMsg'></span>
                    </div>

                    <?php endif; ?>

                </div>

                <div class="row justify-content-between text-left">
                    <?php if ($test) : ?>

                    <div class="form-group col-sm-6 mt-2">
                        <input class="med-input" type="text" id="patientPIN" name="patientPIN"
                            onfocusout="checkPin(this)" Value="<?= $pin ?>" placeholder="" autocomplete="off" required>
                        <label class="med-label" for="patientPIN">PIN Code<span class="text-danger">*</span></label>
                        <span class='text-danger fs-6' id='pinMsg'></span>
                    </div>

                    <?php endif; ?>
                    <?php if ($test) : ?>
                    <div class="form-group col-sm-6 mt-2">
                        <select class="med-input" id="" name="patientState" required>
                            <option Value="<?= $state ?>" selected><?= $state ?></option>
                            <option value="West bengal">West Bengal</option>
                            <option value="Other">Other</option>
                        </select>
                        <label class="med-label" for="patientState">State<span class="text-danger"> *</span></label>
                    </div>
                    <?php endif; ?>
                </div>



                <?php if (!$test) : ?>
                <div class="row justify-content-between text-left">
                    <div class="form-group col-sm-6 mt-2">
                        <select class="med-input" id="" name="patientState" required>
                            <option Value="<?= $state ?>" selected><?= $state ?></option>
                            <option value="West bengal">West Bengal</option>
                            <option value="Other">Other</option>
                        </select>
                        <label class="med-label" for="patientState">State<span class="text-danger"> *</span></label>
                    </div>

                    <!-- <div class="form-group col-sm-6 mt-2"> -->
                    <!-- <select class="med-input" id="docList" name="patientDoctor" onclick="showAddDoctorButton()"
                            required>
                            <?php
                                // if ($allDoctors != null) {
                                //     echo "<option value=''>Select Doctor</option>";
                                //     foreach ($allDoctors as $showDoctorDetails) {
                                //         $doctorId = $showDoctorDetails->doctor_id;
                                //         $doctorName = $showDoctorDetails->doctor_name;
                                //         echo '<option value=' . $doctorId . '>' . $doctorName . '</option>';
                                //     }
                                // }else {
                                //     echo "<option value='' style='display: none;'>Please Add Doctor First</option>";
                                // }
                                ?>
                        </select>
                        <label class="med-label" for="patientDoctor">Doctor Name<span class="text-danger">
                                *</span></label> -->

                    <div class="form-group px-2 p-0 col-sm-6 mt-2 " id="docDropdown">
                        <div class="dropdown-selected" onclick="toggleDropdown()">Select Doctor</div>
                        <div class="med-input dropdown-options h-auto" id="doctorOptions" name="doctorOptions">
                            <?php
                                if ($allDoctors != null) {
                                    echo '<div class="px-2 py-1 mb-1 text-white bg-secondary" style="--bs-bg-opacity: .2;"onclick="selectDoctor(\'\', \'Select Doctor\')">Select Doctor</div>';
                                    foreach ($allDoctors as $showDoctorDetails) {
                                        $doctorId = $showDoctorDetails->doctor_id;
                                        $doctorName = $showDoctorDetails->doctor_name;
                                        echo '<div class="dropdown-option" onclick="selectDoctor(\'' . $doctorId . '\', \'' . $doctorName . '\')">' . $doctorName . '</div>';
                                    }
                                } else {
                                    echo '<div class="dropdown-option">Please Add Doctor First</div>';
                                }
                                echo '<button type="button" class="btn btn-primary btn-block" id="appointDocAdd" onclick="openDocModal()" name="add-new-doc-data" data-toggle="modal" data-target="#addDoctorDataModal">+ Add Doctor</button>';
                                ?>
                        </div>
                        <label class="med-label" for="patientDoctor">Doctor Name<span class="text-danger">*</span></label>
                    </div>
                    <input type="hidden" name="patientDoctor" id="patientDoctor" required>
                    <!-- <?php
                        //  if ($allDoctors == null || count($allDoctors) === 0) {
                        //  echo '<div class="form-group col-sm-6 col-md-12 col-lg-12 ml-n3 position-absolute bg-white z-index shadow-lg" id="addDoctorDropdown" style="display: none; z-index: 1; height: 80px">
                        //     <button type="button" class="btn btn-primary btn-block mt-4" id="appointDocAdd" onclick="openDocModal()" name="add-new-doc-data" data-toggle="modal" data-target="#addDoctorDataModal">+ Add Doctor</button>
                        // </div>';
                        //  }
                         ?> -->
                    <!-- </div> -->
                </div>
                <?php else : ?>
                <input class="d-none" type="text" value=" " name="patientDoctor">
                <?php endif; ?>

                <div class="row justify-content-end mt-2">
                    <div class="form-group col-sm-4">
                        <button type="submit" name="submit" id="submitBtn"
                            class="btn btn-block btn-primary">Submit</button>
                        <!-- onclick=submitNewLabPatientData() -->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- add new doctor Modal -->
<div class="modal fade" id="addDoctorDataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Doctor Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-3 px-4 open-doctormodal" id="new-doctor-modal"></div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- End of add Doctor Modal -->
