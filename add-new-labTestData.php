<?php
require_once __DIR__ . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; // Check if admin is logged in
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'PathologyReportType.class.php';

$Pathology      = new Pathology;
$PathReportType = new PathologyReportType;

$parentCategoryId = isset($_GET['catId']) ? url_dec($_GET['catId']) : null;

$allReportTypes = json_decode($PathReportType->pathalogyReportTypes());
    if ($allReportTypes->status) {
        $allReportTypes = $allReportTypes->data;
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Fonts and Stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= CSS_PATH ?>font-awesome.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">
    <!-- SweetAlert -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" type="text/css" />
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css" />
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include SUP_ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include SUP_ROOT_COMPONENT . 'topbar.php'; ?>

                <!-- Add Test Form -->
                <div class="container-fluid">

                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between">
                            <div>
                                <h5>Add New Test</h5>
                            </div>
                            <div class="d-flex">

                                <select class="form-control form-control-sm mr-2" id="change-report-format" data-test="">
                                    <option value="" selected disabled>Select Report Type</option>
                                    <?php
                                    foreach ($allReportTypes as $eachType) {
                                        echo "<option value='$eachType->id' >$eachType->name</option>";
                                    }
                                    ?>
                                </select>

                                <button type="button" class="btn btn-sm btn-primary px-3" id="add-test-btn">Save</button>
                            </div>

                        </div>
                        <div class="card-body">



                            <!-- Hidden Category Id -->
                            <input type="number" id="global-flag" class="d-none" value="1">
                            <input type="text" id="parent-category-id" name="parent-category-id" class="form-control d-none" value="<?= url_enc($parentCategoryId); ?>" readonly>

                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <label for="test-name" class="form-label">Test Name</label>
                                    <input type="text" id="test-name" name="test-name" class="form-control" placeholder="Enter test name" autocomplete="off" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="test-price" class="form-label">Test Price</label>
                                    <input type="number" id="test-price" name="test-price" class="form-control" placeholder="Enter test price" autocomplete="off" required>
                                </div>
                            </div>

                            <hr class="my-4">

                            <input type="number" id="initial-row-index" class="d-none" value="0">
                            <input type="number" id="param-count" class="d-none" value="0">

                            <!-- Dynamic Row Container -->
                            <form id="test-details-form">
                                <div id="dynamic-row-container"></div>
                            </form>

                            <!-- Add/Delete Parameters Buttons -->
                            <!-- <div class="row d-flex mt-3 d-flex justify-content-between">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary w-100" id="add-new-param-btn">Add New Parameter</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-danger w-100" id="remove-last-param-btn">Delete Last Parameter</button>
                                </div>
                            </div> -->

                            <hr class="my-4">

                            <!-- Test Description and Procedure -->
                            <div class="row mt-3">
                                <div class="col-sm-6">
                                    <label for="test-description" class="form-label">Test Description</label>
                                    <textarea id="test-description" name="test-description" class="form-control" placeholder="Enter test description" rows="3" autocomplete="off"></textarea>
                                </div>
                                <div class="col-sm-6">
                                    <label for="test-process" class="form-label">Pre Test Procedure</label>
                                    <textarea id="test-process" name="test-process" class="form-control" rows="3" autocomplete="off"></textarea>
                                </div>
                            </div>

                            <!-- Add Test Button -->
                            <!-- <div class="row mt-4">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary w-100" id="add-test-btn">Add Test</button>
                                </div>
                            </div> -->

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script type="importmap">
        {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.0.0/"
        }
    } 
    </script>

    <!-- Bootstrap core JavaScript -->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages -->
    <script src="<?= JS_PATH ?>sb-admin-2.js"></script>

    <!-- SweetAlert JS and Custom Script -->
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>
    <script type="module" src="<?= JS_PATH ?>ckEditor-module.js"></script>
    <script type="module" src="<?= JS_PATH ?>add-lab-test.js"></script>

    <script type="module" src="<?= JS_PATH ?>report-format-selector-add.js"></script>
    <script type="module" src="<?= JS_PATH ?>report-single-field-format.js"></script>
</body>

</html>