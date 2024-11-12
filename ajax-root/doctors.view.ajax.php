<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'doctor.category.class.php';


$docId = $_GET['docId'];

$Doctors        = new Doctors();
$doctorCategory = new DoctorCategory();

$showDoctor = json_decode($Doctors->showDoctorNameById($docId));
$docSplzList = $doctorCategory->showDoctorCategory();

$showDoctor = $showDoctor->data;

$docId          = $showDoctor->doctor_id;
$docRegNo       = $showDoctor->doctor_reg_no;
$docName        = $showDoctor->doctor_name;
$docSplz        = $showDoctor->doctor_specialization;
$docDegree      = $showDoctor->doctor_degree;
$docAlsoWith    = $showDoctor->also_with;
$docAddress     = $showDoctor->doctor_address;
$docEmail       = $showDoctor->doctor_email;
$docPhno        = $showDoctor->doctor_phno;

?>

<div>
    <div class="row justify-content-between text-left mt-2">
        <div class="col-sm-6 ">
            <div class="form-group">
                <input type="text" class="med-input" id="u-doctor-name" placeholder="" value="<?php echo $docName; ?>" autocomplete="off">
                <label for="u-doctor-name" class="med-label">Doctor Name:<span class="text-danger small">*</span></label>
                <input type="text" class="form-control" id="u-doctor-id" value="<?php echo $docId; ?>" readonly hidden>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <input type="text" class="med-input" id="u-doc-reg-no" placeholder="" value="<?php echo $docRegNo; ?>">
                <label for="u-doc-reg-no" class="med-label">Registration No:<span class="text-danger small">*</span></label>
            </div>
        </div>
    </div>

    <div class="row justify-content-between text-left">

        <div class="col-sm-6 mt-2">
            <div class="form-group">
                <select id="u-doc-speclz-id" class="med-input" placeholder="">
                    <?php
                    foreach ($docSplzList as $eachSplzList) {
                        $selected = $docSplz == $eachSplzList['doctor_category_id'] ? 'selected' : '';
                        echo '<option value="' . $eachSplzList["doctor_category_id"] . '" ' . $selected . '>' . $eachSplzList["category_name"] . '</option>';
                    }
                    ?>
                </select>
                <label for="u-doc-speclz-id" class="med-label">Specialization: <span class="text-danger small">*</span></label>
            </div>
        </div>


        <div class="form-group col-sm-6 flex-column d-flex mt-2">
            <input type="text" class="med-input" id="u-doc-degree" placeholder="" value="<?php echo $docDegree; ?>" autocomplete="off">
            <label for="u-doc-degree" class="med-label">Doctor Degree:<span class="text-danger small">*</span></label>
        </div>
    </div>

    <div class="row justify-content-between text-left">
        <div class="form-group col-sm-6 flex-column d-flex mt-2">
        <input type="email" class="med-input" id="u-doc-email" placeholder="" value="<?php echo $docEmail; ?>" autocomplete="off">
        <label for="u-doc-email" class="med-label">Email:</label>
        </div>
        <div class="form-group col-sm-6 flex-column d-flex mt-2">
            <input type="text" class="med-input" id="u-doc-phno" value="<?php echo $docPhno; ?>" autocomplete="off" placeholder="" maxlength="10" pattern="\d{10}" required>
            <label for="u-doc-phno" class="med-label">Contact Number:</label>
        </div>
    </div>

    <div class="row justify-content-between text-left">

        <div class="form-group col-sm-6 flex-column d-flex mt-2">
            
            <textarea class="med-input" id="u-doc-address" placeholder="" rows="3"><?php echo $docAddress; ?></textarea autocomplete="off">
            <label for="u-doc-address" class="med-label">Address:</label>
            </div>
            <div class="form-group col-sm-6 flex-column d-flex mt-2">
                <input type="text" class="med-input" id="u-doc-with" placeholder="" value="<?php echo $docAlsoWith; ?>" autocomplete="off">
                <label for="u-doc-with" class="med-label">Also With:</label>
            </div>
        </div>
        <div class="text-center">
            <button type="button" class="btn btn-sm btn-primary" onclick="editDoc()">Save changes</button>
        </div>

    </div>
<div>