<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

$Pathology = new Pathology;

if (isset($_GET['catId']) && isset($_GET['testId'])) {
    $parentCategoryId = url_dec($_GET['catId']);
    $testId = url_dec($_GET['testId']);

    $testDetails = json_decode($Pathology->showTestById($testId));

    if ($testDetails->status) {
        $testName = $testDetails->data->name;
        $testPrice = $testDetails->data->price;
        $testDescription = $testDetails->data->dsc;
        $testPreparation = $testDetails->data->preparation;

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

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" type="text/css" />
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css" />

</head>

<body id="page-top">
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content" class="container-fluid">
                <!-- Hidden Inputs for Category and Test IDs -->
                <input type="hidden" id="global-flag" value="2">
                <input type="hidden" id="parent-category-id" name="parent-category-id" value="<?= htmlspecialchars(url_enc($parentCategoryId)); ?>">
                <input type="hidden" id="test-id" name="test-id" value="<?= htmlspecialchars(url_enc($testId)); ?>">

                <!-- Test Details Section -->
                <div class="row mt-3">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="test-name" class="form-label">Test Name</label>
                        <input type="text" id="test-name" name="test-name" class="form-control" placeholder="Enter test name" value="<?= htmlspecialchars($testName); ?>" required data-edit-id="<?= $testId; ?>" test-name-prev-data="<?= $testName; ?>">
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="test-price" class="form-label">Test Price</label>
                        <input type="number" id="test-price" name="test-price" class="form-control" placeholder="Enter test price" value="<?= htmlspecialchars($testPrice); ?>" required>
                    </div>
                </div>

                <hr>

                <form id="test-details-form" class="container-fluid">
                    <div class="" id="dynamic-row-container">
                        <!-- Parameter Details Section -->
                        <!-- <div id="prev-data-container" class="row mt-3"> -->
                        <?php $rowCount = 0;
                        if ($testParamList):
                            foreach ($testParamList as $index => $param) :
                                $rowCount++;
                                $standardRangeDetails = json_decode($Pathology->showRangeByParameter($param->id));
                                $paramHeaderDetails = json_decode($Pathology->showHeadByParameterId($param->id));

                                $rangeId = '';
                                $childRange = '';
                                $adultMaleRange = '';
                                $adultFemaleRange = '';
                                $generalRange = '';

                                if ($standardRangeDetails->status) {
                                    $rangeId = $standardRangeDetails->data->id;
                                    $childRange = htmlspecialchars($standardRangeDetails->data->child, ENT_QUOTES);
                                    $adultMaleRange = htmlspecialchars($standardRangeDetails->data->adult_male, ENT_QUOTES);
                                    $adultFemaleRange = htmlspecialchars($standardRangeDetails->data->adult_female, ENT_QUOTES);
                                    $generalRange = htmlspecialchars($standardRangeDetails->data->general, ENT_QUOTES);
                                }else{
                                    $rangeId = 0;
                                    $childRange = '';
                                    $adultMaleRange = '';
                                    $adultFemaleRange = '';
                                    $generalRange = '';
                                }
                        ?>
                                <div class="row d-flex mt-3 mb-3" id="dynamic-row-<?= $rowCount; ?>">
                                    <div class="col-md-3 col-sm-12 mb-3">
                                        <input type="hidden" id="test-param-id-<?= $rowCount ?>" value="<?= $param->id; ?>">
                                        <label for="param-name-<?= $rowCount; ?>" class="form-label">Parameter Name</label>
                                        <input type="text" class="form-control" id="param-name-<?= $rowCount; ?>" name="param-name-<?= $rowCount; ?>" placeholder="Enter parameter name" value="<?= htmlspecialchars($param->name, ENT_QUOTES, 'UTF-8'); ?>" required>

                                        <label for="param-unit-<?= $rowCount; ?>" class="form-label mt-2">Parameter Unit</label>
                                        <input type="text" id="param-unit-<?= $rowCount; ?>" name="param-unit-<?= $rowCount; ?>" class="form-control" placeholder="Enter unit" value="<?= htmlspecialchars($param->unit); ?>" required>
                                    </div>

                                    <!-- Range Data -->
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <input type="hidden" id="standard-range-id-<?= $rowCount ?>" value="<?= $rangeId; ?>">
                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label for="child-unit-data-<?= $rowCount; ?>" class="form-label">Range for Child</label>
                                                <textarea id="child-unit-data-<?= $rowCount; ?>" name="child-unit-data-<?= $rowCount; ?>" class="form-control" rows="2" required><?= $childRange ?></textarea>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label for="adult-male-data-<?= $rowCount; ?>" class="form-label">Range for Adult Male</label>
                                                <textarea id="adult-male-data-<?= $rowCount; ?>" name="adult-male-data-<?= $rowCount; ?>" class="form-control" rows="2" required><?= $adultMaleRange ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label for="adult-female-data-<?= $rowCount; ?>" class="form-label">Range for Adult Female</label>
                                                <textarea id="adult-female-data-<?= $rowCount; ?>" name="adult-female-data-<?= $rowCount; ?>" class="form-control" rows="2" required><?= $adultFemaleRange ?></textarea>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label for="general-range-data-<?= $rowCount; ?>" class="form-label">General Range</label>
                                                <textarea id="general-range-data-<?= $rowCount; ?>" name="general-range-data-<?= $rowCount; ?>" class="form-control" rows="2" required><?= $generalRange ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Header Buttons -->
                                    <div id="dynamic-head-container-<?= $rowCount; ?>" class="col-md-2 col-sm-12">
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-small add-old-heading-btn" data-index="<?= $rowCount; ?>">
                                                <i class="fas fa-plus-circle"></i> Add
                                            </button>
                                            <!-- <button type="button" class="btn btn-small remove-old-heading-btn" data-index="<?= $rowCount; ?>">
                                                <i class="fas fa-minus-circle"></i> Remove
                                            </button> -->
                                        </div>

                                        <?php if ($paramHeaderDetails->status) :
                                            $headCount = 0;
                                            foreach ($paramHeaderDetails->data as $paramWiseHead) :
                                                $headCount++; ?>
                                                <div class="row mb-3" id="header-container-<?= $rowCount; ?>-<?= $headCount; ?>">
                                                    <!-- <label for="param-header-<?= $rowCount; ?>-<?= $headCount; ?>" class="form-label">Header <?= $headCount; ?></label> -->
                                                    <input type="hidden" id="param-header-id-<?= $rowCount; ?>-<?= $headCount; ?>" name="param_header_id_<?= $rowCount; ?>[]" value="<?= htmlspecialchars($paramWiseHead->id); ?>">
                                                    <input type="text" id="param-header-<?= $rowCount; ?>-<?= $headCount; ?>" name="param_header_name_<?= $rowCount; ?>[]" class="form-control pe-5" placeholder="Enter header name" value="<?= htmlspecialchars($paramWiseHead->name); ?>" required>
                                                    <i class="fas fa-trash text-danger mt-2" id="remove-header-btn"
                                                        title="Delete-head"
                                                        head-id="<?= $paramWiseHead->id; ?>"
                                                        aria-label="Remove"
                                                        style="cursor: pointer; position: absolute; margin-left: 145px;">
                                                    </i>
                                                </div>
                                            <?php endforeach; ?>
                                            <input type="hidden" id="prev-header-count-<?= $rowCount; ?>" name="prev_header_count_<?= $rowCount; ?>" value="<?= $headCount; ?>">
                                        <?php else: ?>
                                            <input type="hidden" id="prev-header-count-<?= $rowCount; ?>" name="prev_header_count_<?= $rowCount; ?>" value="0">
                                        <?php endif; ?>

                                    </div>

                                    <!-- Delete Parameter Button -->
                                    <div class="col-md-1 d-flex justify-content-end align-items-center">
                                        <i class="fas fa-trash text-danger" id="del-prev-param" param-id="<?= $param->id; ?>" range-id="<?= $rangeId; ?>" style="cursor: pointer; font-size: 1.2rem;" title="Delete-Parameter"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <input type="hidden" id="initial-row-index" value="<?= $rowCount; ?>">
                            <input type="hidden" id="param-count" value="<?= $rowCount; ?>">

                        <?php else: ?>
                            <input type="hidden" id="initial-row-index" value="<?= $rowCount; ?>">
                            <input type="hidden" id="param-count" value="<?= $rowCount; ?>">
                        <?php endif; ?>
                        <input type="hidden" id="prev-row-count" value="<?= $rowCount; ?>">
                        <!-- </div> -->
                    </div>
                </form>
            </div>

            <div class="row mt-3">
                <div class="col-12 col-md-6">
                    <button type="button" class="btn btn-primary w-100" id="add-new-param-btn">Add New Parameter</button>
                </div>
                <div class="col-12 col-md-6">
                    <button type="button" class="btn btn-danger w-100" id="remove-last-param-btn">Delete Last Parameter</button>
                </div>
            </div>

            <!-- Test Description and Preparation -->
            <div class="row mt-3">
                <div class="col-12 col-md-6 mb-3">
                    <label for="test-description" class="form-label">Test Description</label>
                    <textarea id="test-description" name="test-description" class="form-control" rows="4" placeholder="Enter test description"><?= htmlspecialchars($testDescription); ?></textarea>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="test-process" class="form-label">Test Preparation</label>
                    <textarea id="test-process" name="test-process" class="form-control" rows="4" placeholder="Enter test preparation"><?= htmlspecialchars($testPreparation); ?></textarea>
                </div>
            </div>

            <!-- Update Button -->
            <div class="row mt-4 mb-5">
                <div class="col-12">
                    <button type="button" class="btn btn-primary w-100" id="update-test-data-btn">Update Test</button>
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
    <script type="module" src="<?= JS_PATH ?>admin-js/ckEditor-module.js" defer></script>
    <script type="module" src="<?= JS_PATH ?>admin-js/add-edit-labTestData.js"></script>

</body>

</html>