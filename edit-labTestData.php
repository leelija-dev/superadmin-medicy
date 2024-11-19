<?php
require_once 'config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'PathologyReportType.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

$Pathology      = new Pathology;
$PathReportType = new PathologyReportType;

if (isset($_GET['catId']) && isset($_GET['testId'])) {
    $parentCategoryId = url_dec($_GET['catId']);
    $testId = url_dec($_GET['testId']);

    $testDetails = json_decode($Pathology->showTestById($testId));

    if ($testDetails->status) {
        $testName           = $testDetails->data->name;
        $testPrice          = $testDetails->data->price;
        $testDescription    = $testDetails->data->dsc;
        $testPreparation    = $testDetails->data->preparation;
        $reportType         = $testDetails->data->report_type;
        $reportTextFormat   = $testDetails->data->report_text_format;
        $testStatus        = $testDetails->data->status;

        // $testParamHeading = json_decode($Pathology->showHeadByTestId($testId));

        $selectTestParam = json_decode($Pathology->showParametersByTest($testId));
        if ($selectTestParam->status) {
            $testParamList = $selectTestParam->data;
            // $testParamHead = json_decode($Pathology->showHeadByTestId());
        } else {
            $testParamList = '';
        }
    } else {
        echo 'No data found!';
        exit;
    }

    $allReportTypes = json_decode($PathReportType->pathalogyReportTypes());
    if ($allReportTypes->status) {
        $allReportTypes = $allReportTypes->data;
    }
} else {
    echo 'Invalid Request!';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Test Details Page">
    <meta name="author" content="">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900"
        type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css" type="text/css" />
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

                <div class="container-fluid">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between">
                            <div>
                                <h5>Edit Old Test Data</h3>
                            </div>
                            <div class="d-flex">
                                <div class="col-4">
                                    <select class="form-control form-control-sm" id="change-report-status" data-test="<?=$testId?>">
                                        <option value="1" <?= $testStatus === 1 ? 'selected' : '' ?>>Active</option>
                                        <option value="0" <?= $testStatus === 0 ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>
                                <select class="form-control form-control-sm mr-2" id="change-report-format"
                                    data-test="<?=$testId?>">
                                    <!-- <option value="" selected disabled>Select Report Type</option> -->
                                    <?php
                                    foreach ($allReportTypes as $eachType) {
                                        $isSelected = $reportType == $eachType->id ? 'selected' : '';
                                        echo "<option value='$eachType->id' $isSelected >$eachType->name</option>";
                                    }
                                    ?>
                                </select>
                                <button type="button" class="btn btn-sm btn-primary px-3"
                                    id="update-test-data-btn">Save</button>
                            </div>

                        </div>
                        <div class="card-body">
                            <!-- Hidden Inputs for Category and Test IDs -->
                            <input type="hidden" id="global-flag" value="2">
                            <input type="hidden" id="parent-category-id" name="parent-category-id"
                                value="<?= htmlspecialchars(url_enc($parentCategoryId)); ?>">
                            <input type="hidden" id="test-id" name="test-id"
                                value="<?= htmlspecialchars(url_enc($testId)); ?>">

                            <!-- Test Details Section -->
                            <div class="row mt-3">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="test-name" class="form-label">Test Name</label>
                                    <input type="text" id="test-name" name="test-name" class="form-control"
                                        placeholder="Enter test name" value="<?= htmlspecialchars($testName); ?>"
                                        required data-edit-id="<?= $testId; ?>" test-name-prev-data="<?= $testName; ?>">
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="test-price" class="form-label">Test Price</label>
                                    <input type="number" id="test-price" name="test-price" class="form-control"
                                        placeholder="Enter test price" value="<?= htmlspecialchars($testPrice); ?>"
                                        required>
                                </div>
                            </div>

                            <!-- Test Description and Preparation -->
                            <div class="row mt-3">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="test-description" class="form-label">Test Description</label>
                                    <textarea id="test-description" name="test-description" class="form-control"
                                        rows="4"
                                        placeholder="Enter test description"><?= htmlspecialchars($testDescription); ?></textarea>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="test-process" class="form-label">Test Preparation</label>
                                    <textarea id="test-process" name="test-process" class="form-control" rows="4"
                                        placeholder="Enter test preparation"><?= htmlspecialchars($testPreparation); ?></textarea>
                                </div>
                            </div>

                            <hr>

                            <form id="test-details-form" class="" style="width: 21cm">
                                <div class="row" id="dynamic-row-container">
                                    <?php
                                    if ($reportType == 2) {
                                        require_once "./components/ReportFormat/text-format.php";
                                    } else {
                                        require_once './components/ReportFormat/SingleFieldFormat.php';
                                    }
                                    ?>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <!-- Import CKEditor using import maps -->
    <script type="importmap">
        {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.0.0/"
        }
    } 
    </script>

    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>
    <!-- <script src="<?= JS_PATH ?>admin-js/ckEditor-import-map.js" defer></script> -->
    <script type="module" src="<?= JS_PATH ?>ckEditor-module.js" defer></script>
    <script type="module" src="<?= JS_PATH ?>add-edit-labTestData.js"></script>
    <script type="module" src="<?= JS_PATH ?>report-format-selector.js"></script>
    <script type="module" src="<?= JS_PATH ?>report-single-field-format.js"></script>
</body>

</html>