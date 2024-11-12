<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';//check admin loggedin or not
require_once CLASS_DIR.'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR.'patients.class.php';

$Patients = new Patients();

$showPatients = json_decode($Patients->allPatients());
$showPatients = $showPatients->data;
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


    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet" />

    <link href="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet" />

    <!-- Custom styles for this page -->
    <link href="<?= CSS_PATH ?>custom/appointment.css" rel="stylesheet" />
    <link href="<?= CSS_PATH ?>custom/appointment.css" rel="stylesheet" />

    <!-- <link href="<?= PLUGIN_PATH ?>select2/select2.min.css" rel="stylesheet" /> -->
    <!-- Choices includes -->
    <link href="<?= PLUGIN_PATH ?>choices/assets/styles/choices.min.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css"> -->
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


                <div class="container-fluid">
                    <div class="card p-0">
                        <div class="card-header">

                        </div>
                        <div class="card-body my-5 my-md-1 p-md-5">

                            <form class="row flex-column align-items-center" action="lab-billing.php" method="post">

                                <div class="section col-12 col-md-6">
                                    <div data-test-hook="remove-button">
                                        <select class="form-control" name="patientId" id="choices-remove-button"
                                            required>
                                            <option value="" selected disabled> Search Patient Name </option>
                                            <?php
                                                foreach ($showPatients as $patientsRow) {
                                                    echo "<option value='$patientsRow->patient_id'>$patientsRow->patient_id - $patientsRow->name</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-md-6 mt-2">
                                    <input type="date" class="form-control w-100" id="testDate" name="testDate"
                                        placeholder="" required>
                                </div>


                                <div class="form-group col-12 col-md-2">
                                    <button type="submit" name="bill-proceed"
                                        class="btn-block btn-primary">Proceed</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <?php include ROOT_COMPONENT.'footer-text.php'; ?>
                <!-- End of Footer -->

                <!-- Bootstrap core JavaScript-->
                <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
                <script src="<?= PLUGIN_PATH ?>jquery/jquery.slim.js"></script>
                <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
                <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.min.js"></script>
                <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.js"></script>

                <!-- Core plugin JavaScript-->
                <!-- <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script> -->

                <!-- Custom scripts for all pages-->
                <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
                <!-- <script src="<?= PLUGIN_PATH ?>select2/select2.min.js"></script> -->
                <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.js"></script>





                <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script> -->
                <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script> -->

                <script>
                // $('#patientList').select2();

                // //patient selection js
                // $(document).ready(function(){
                //     $('.patient-select').selectpicker();

                // })
                document.addEventListener('DOMContentLoaded', function() {

                    new Choices('#choices-remove-button', {
                        allowHTML: true,
                        removeItemButton: true,
                    });

                });
                </script>
</body>

</html>