<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'UtilityFiles.class.php';
require_once CLASS_DIR . 'labTestTypes.class.php';
require_once CLASS_DIR . 'sub-test.class.php';



//Classes Initilizing

$Patients        = new Patients();
$IdsGeneration   = new IdsGeneration();
$HealthCare      = new HealthCare;
$labTypes        = new LabTestTypes;
$subTests        = new SubTests;


$showLabTypes = json_decode($labTypes->showLabTypes());
if($showLabTypes->status){
    if(!empty($showLabTypes->data)){
        $showLabTypes = $showLabTypes->data;
    }
}
// print_r($showLabTypes);

$clinicInfo  = $HealthCare->showHealthCare($adminId);
$clinicInfo  = json_decode($clinicInfo, true);

if ($clinicInfo['status'] == 1) {
    $data = $clinicInfo['data'];
    $email    = $data['hospital_name'];
    $district = $data['dist'];
    $pin      = $data['pin'];
    $state    = $data['health_care_state'];
} else {
    echo "Error: " . $clinicInfo['msg'];
}


////////////////////////////////////////////////////////////
$swalControl = 2;
if (isset($_POST['add-new-subtest']) == true) {

    $subTestName = $_POST['subtest-name'];
    $parentTestId = $_POST['parent-test'];
    $ageGroup = $_POST['age-group'];
    $subTestPrep = $_POST['test-prep'];
    $subTestDsc = $_POST['subtest-dsc'];
    $price = $_POST['price'];
    $SubTestUnit = $_POST['subtest-unit'];

    $addsubTests = json_decode($subTests->addSubTests($subTestName, $SubTestUnit, $parentTestId, $ageGroup, $subTestPrep, $subTestDsc, $price));

    if ($addsubTests->status) {
        $swalControl = 1;
    } else {
        $swalControl = 0;
    }
}

?>

<!doctype html>

<html lang="en">

<head>

    <!-- Required meta tags -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>patient-style.css">

    <link href="<?= CSS_PATH ?>new-sales.css" rel="stylesheet">
    <title>Enter Patient Details</title>

    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= PLUGIN_PATH ?>font-asesome-5/font-awesome-5.15.4-all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= ASSETS_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

</head>

<body>

    <!-- Page Wrapper -->
    <div>
        <div class="row d-flex justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 text-center">
                <div class="bg-light p-4 pb-0">
                    <!-- <h4 class="text-center mb-4 mt-0"><b>Fill The Patient Details</b></h4> -->
                    <form form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <!-- first row -->
                        <div class="row">
                            <div class="form-group col-sm-6 flex-column d-flex justify-content-between text-left">
                                <label class="form-control-label h7 font-weight-bold" for="parent-test" style="color: #5A59EB; margin-right: 8px;">Parent Test Name <span class="text-danger font-weight-bold">*</span></label>
                                <select name="parent-test" class="newsalesAdd" id="parent-test" required>
                                    <option value="" disabled selected>Select Main Test</option>
                                    <?php
                                    // if ($showLabTypes && isset($showLabTypes['status']) && $showLabTypes['status'] == 1) {
                                    foreach ($showLabTypes as $labTypeName) {
                                        print_r($labTypeName);
                                        echo '<option value="' . $labTypeName->id . '">' . $labTypeName->test_type_name . '</option>';
                                    }
                                    // }
                                    ?>

                                </select>
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex justify-content-between text-left">
                                <label class="form-control-label h7 font-weight-bold" for="subtest-name" style="color: #5A59EB; margin-right: 8px;"> Sub Test Name <span class="text-danger font-weight-bold">*</span></label>
                                <input class="newsalesAdd" id="subtest-name" name="subtest-name" type="text" required autocomplete="off">
                            </div>
                        </div>
                        <!-- second row -->
                        <div class="row mt-2">
                            <div class="form-group col-sm-6 flex-column d-flex justify-content-between text-left">
                                <label class="form-control-label h7 font-weight-bold" for="age-group" style="color: #5A59EB; margin-right: 8px;">Age Group <span class="text-danger font-weight-bold">*</span></label>
                                <select class="newsalesAdd" id="age-group" name="age-group" required>
                                    <option value="" disabled selected>Select Age Group <span class="text-danger font-weight-bold">*</span></option>
                                    <option value="Any Age Group">Any Age Group <span class="text-danger font-weight-bold">*</span></option>
                                    <option value="Bellow 18">Bellow 18</option>
                                    <option value="Above 18">Above 18</option>
                                </select>
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex justify-content-between text-left">
                                <label class="form-control-label h7 font-weight-bold" for="subtest-unit" style="color: #5A59EB; margin-right: 8px;"> Sub Test Unit <span class="text-danger font-weight-bold">*</span></label>
                                <input class="newsalesAdd" id="subtest-unit" name="subtest-unit" type="text" required autocomplete="off">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="form-group col-sm-6 flex-column d-flex justify-content-between text-left">
                                <label class="form-control-label h7 font-weight-bold" for="test-prep" style="color: #5A59EB; margin-right: 8px;">Pre Checkup Procedure for patient <span class="text-danger font-weight-bold">*</span></Address></label>

                                <textarea class="newsalesAdd" id="test-prep" name="test-prep" cols="30" rows="3" required></textarea>

                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex justify-content-between text-left">
                                <label class="form-control-label h7 font-weight-bold" for="subtest-dsc" style="color: #5A59EB; margin-right: 8px;">Description <span class="text-danger font-weight-bold">*</span></label>

                                <textarea class="newsalesAdd" id="subtest-dsc" name="subtest-dsc" cols="30" rows="3" required></textarea>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="form-group col-sm-6 flex-column d-flex justify-content-between text-left">
                                <label class="form-control-label h7 font-weight-bold" for="price" style="color: #5A59EB; margin-right: 8px;">Price <span class="text-danger font-weight-bold">*</span></label>
                                <input class="newsalesAdd" id="price" name="price" type="number" min="0" required>
                            </div>
                            <div class="form-group col-sm-6 flex-column d-flex justify-content-between text-left mt-5">
                                <button class="btn btn-success me-md-2" name="add-new-subtest" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--/End Part 1  -->



    <!-- js -->
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>

    <?php

    if ($swalControl == 1) {
    ?>
        <script>
            // alert('Data insert successfully');
            // console.log(<?php echo $swalControl; ?>);
            Swal.fire("Success", "Data Addition successfull!", "success");
        </script>
    <?php
    }

    if ($swalControl == 0) {
    ?>
        <script>
            // alert('Data insertion fail!');
            Swal.fire("Failed", "Data Addition Failed!", "error");
        </script>
    <?php

    }

    ?>

</body>

</html>