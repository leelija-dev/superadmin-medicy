<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'idsgeneration.class.php';


//Classes Initilizing
$Patients        = new Patients();
$IdsGeneration    = new IdsGeneration();


?>

<!doctype html>

<html lang="en">

<head>

    <!-- Required meta tags -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>patient-style.css">
    <title>Enter Patient Details</title>

    <link href="<?= ASSETS_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
<script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

</head>

<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
        //Patient Id Generate
        $patientId = $IdsGeneration->patientidGenerate();
        
        $visited = 0;
    
        $added = $Patients->addPatients($patientId, $_POST['patientName'], $_POST['patientGurdianName'], $_POST['patientEmail'], $_POST['patientPhoneNumber'], $_POST['patientAge'], $_POST['gender'], $_POST['patientAddress1'], $_POST['patientAddress2'], $_POST['patientPS'], $_POST['patientDist'], $_POST['patientPIN'], $_POST['patientState'], $visited, $employeeId, NOW, $adminId);

        if ($added == TRUE) {
            ?>

            <script>
                swal("Success", "Customer Added Successfuly!", "success");
            </script>
            
            <?php
        }else {
            ?>

            <script>
                swal("Failed", "Customer Addition Failed !", "error");
            </script>

            <?php
        }
    
    }

    ?>

    <!-- Page Wrapper -->
    <div>
        <div class="row d-flex justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 text-center">
                <div class="card mt-0">
                    <h4 class="text-center mb-4 mt-0"><b>Fill The Patient Details</b></h4>
                    <form class="form-card" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientName">Patient Name<span
                                        class="text-danger"> *</span></label>
                                <input type="text" id="patientName" name="patientName" placeholder="Enter Patient Name"
                                    value="" required>
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientGurdianName">Patient's
                                    Gurdian Name<span class="text-danger"> *</span></label>
                                <input type="text" id="patientGurdianName" name="patientGurdianName"
                                    placeholder="Enter Patient's Gurdian Name" value="" required>
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientEmail">Patient
                                    Email</label>
                                <input type="text" id="patientEmail" name="patientEmail" placeholder="Patient Email"
                                    value="">
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientPhoneNumber">Phone
                                    number<span class="text-danger"> *</span></label>
                                <input type="text" id="patientPhoneNumber" name="patientPhoneNumber"
                                    placeholder="Phone Number" maxlength="10" minlength="10" value="" required>
                            </div>

                        </div>



                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientAge">Age<span class="text-danger">
                                        *</span></label>
                                <input type="text" id="patientAge" name="patientAge" placeholder="Age" maxlength="3"
                                    minlength="1" required>
                            </div>

                            <div class="col-sm-6 mt-4">
                                <label class="mb-3 mr-1" for="gender">Gender: </label>

                                <input type="radio" class="btn-check" name="gender" id="male" value="Male"
                                    autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-secondary" for="male" value="Male">Male</label>


                                <input type="radio" class="btn-check" name="gender" id="female" value="Female"
                                    autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-secondary" for="female"
                                    value="Female">Female</label>


                                <input type="radio" class="btn-check" name="gender" id="secret" value="Others"
                                    autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-secondary" for="secret"
                                    value="Secret">Others</label>

                            </div>

                        </div>



                        <h5 class="text-center mb-4 mt-5">Patient Address</h5>
                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientAddress1">Address Line
                                    1<span class="text-danger"> *</span></label>
                                <input type="text" id="patientAddress1" name="patientAddress1"
                                    placeholder="Address Line 1" value="" required>
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientAddress2">Address Line
                                    2<span class="text-danger"> *</span></label>
                                <input type="text" id="patientAddress2" name="patientAddress2" value=""
                                    placeholder="Address Line 2">
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientPS">Police Station<span
                                        class="text-danger"> *</span></label>
                                <input type="text" id="patientPS" name="patientPS" placeholder="Police Station" value=""
                                    required>
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientDist">District<span
                                        class="text-danger"> *</span></label>
                                <input type="text" id="patientDist" name="patientDist" placeholder="District" value=""
                                    required>
                            </div>
                        </div>


                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientPIN">PIN Code<span
                                        class="text-danger"> *</span></label>
                                <input type="text" id="patientPIN" name="patientPIN" placeholder="Pin Code"
                                    maxlength="7" value="" required>
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientState">State<span
                                        class="text-danger"> *</span></label>
                                <select id="dropSelection" name="patientState" required>
                                    <option disabled>Select State</option>
                                    <option value="West bengal">West Bengal</option>
                                    <option value="Other">Other</option>
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

</body>

</html>