<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'UtilityFiles.class.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'labTestTypes.class.php';


//Classes Initilizing
$HealthCare      = new HealthCare;
$labTypes       = new LabTestTypes;

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


////////////////////////////////////////////////////////
$swalControl = 2;

if (isset($_POST['new-lab-test-data']) == true) {

    $imgName = $_FILES['labTest-img']['name'];

    $tempImgname = $_FILES['labTest-img']['tmp_name'];
    // echo "tempImg name : $tempImgname<br>";

    $imgFolder = LABTEST_IMG_DIR . $imgName;
    move_uploaded_file($tempImgname, $imgFolder);

    $testName   = $_POST['test-name'];
    $testPvdBy  = $_POST['provided-by'];
    $testDsc    = $_POST['test-dsc'];

    //Object initilizing for Adding Main/Parent Tests/Labs
    $addLabTestType = json_decode($labTypes->addLabTypes($imgName, $testName, $testPvdBy, $testDsc));

    if ($addLabTestType->status) {
        $swalControl = 1;
    } else {
        $swalControl = 0;
    }
}

// echo $swalControl;
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

    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= PLUGIN_PATH ?>font-asesome-5/font-awesome-5.15.4-all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= ASSETS_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= PLUGIN_PATH ?>choices/assets/styles/choices.min.css" rel="stylesheet" />
    <!-- Custom styles for this page -->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/appointment.css">

</head>

<body>

    <!-- Page Wrapper -->
    <div>
        <div class="row d-flex justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 text-center">
                <div class="bg-light pt-4 pb-0">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-5 justify-content-between text-left">
                                <div class="form-group">
                                    <div class="alert alert-danger d-none" id="err-show" role="alert">
                                        Only jpg/jpeg and png files are allowed!
                                    </div>
                                    <div class="border border-dark w-100" style="height: 247px">
                                        <img class="p-1 rounded img-uv-view" id="img-preview" src="<?php echo LABTEST_IMG_PATH . 'default-lab-test/labtest.svg' ?>" alt="" style="width:100%;height: 246px">
                                    </div>
                                    <div class="mt-3 d-flex justify-content-center">
                                        <input type="file" style="display:none;" id="img-uv-input" accept=".jpg,.jpeg,.png" name="labTest-img" onchange="validateFileType()">
                                        <label for="img-uv-input" class="btn btn-primary">Change Image</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-7 justify-content-between text-left">
                                <div class="form-floating">
                                    <input class="form-control newbillInput" type="text" id="test-name" name="test-name" placeholder="" value="" required autocomplete="off">
                                    <label class="form-control-label h7 font-weight-bold" for="test-name" style="color: #5A59EB; margin-left: -14px;">
                                        <i class="fas fa-vial"></i> Test Name<span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="form-floating mt-3">
                                    <input class="form-control newbillInput" type="text" id="provided-by" name="provided-by" placeholder="" required autocomplete="off">
                                    <label class="form-control-label h7 font-weight-bold" for="provided-by" style="color: #5A59EB;  margin-left: -14px;">
                                        <i class="fas fa-clinic-medical"></i> Provided By<span class="text-danger">
                                            *</span>
                                    </label>
                                </div>

                                <div class="form-group mt-4">
                                    <label class="form-control-label h7 font-weight-bold" for="test-dsc" style="color: #5A59EB; margin-right: 8px;">
                                        <i class="fas fa-file-medical"></i> Description<span class="text-danger">
                                            *</span>
                                    </label>

                                    <textarea class="form-control newbillInput" name="test-dsc" id="test-dsc" cols="30" rows="2" required autocomplete="off"></textarea>
                                </div>

                                <div class="row justify-content-center mt-2">
                                    <div class="form-group col-sm-4">
                                        <button class="btn btn-success me-md-2" type="submit" name="new-lab-test-data">Submit</button>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--/End Part 1  -->


    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery-3-5-1.min.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.slim.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
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

    <script src="<?= PLUGIN_PATH ?>img-uv/img-uv.js"></script>
    <script>
        function validateFileType() {
            var fileName = document.getElementById("img-uv-input").value;
            console.log(fileName);
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
                document.getElementById("err-show").classList.add("d-none");
            } else {
                document.getElementById("err-show").classList.remove("d-none");
                // Show current image when error occurs
                document.querySelector('.img-uv-view').src = "";
            }
        }
    </script>
</body>

</html>