<?php
require_once dirname(dirname(dirname(__DIR__))) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'PathologyReportType.class.php';

$Pathology      = new Pathology;
$PathReportType = new PathologyReportType;

// if (isset($_GET['catId']) && isset($_GET['testId'])) {
if (isset($_GET['test-id'])) {

    $testId = $_GET['test-id'];

    $testDetails = json_decode($Pathology->showTestById($testId));

    if ($testDetails->status) {
        $testName           = $testDetails->data->name;
        $testPrice          = $testDetails->data->price;
        $testDescription    = $testDetails->data->dsc;
        $testPreparation    = $testDetails->data->preparation;
        $reportType         = $testDetails->data->report_type;
        $reportTextFormat   = $testDetails->data->report_text_format;

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

<div class="w-100 d-flex justify-content-end p-3">
    <button type="button" class="btn btn-sm btn-primary mr-2" id="add-new-param-btn">Add New Parameter</button>
    <button type="button" class="btn btn-sm btn-danger" id="remove-last-param-btn">Delete Last Parameter</button>
</div>
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
            $rangeId            = $standardRangeDetails->data->id;
            $childRange         = htmlspecialchars($standardRangeDetails->data->child, ENT_QUOTES);
            $adultMaleRange     = htmlspecialchars($standardRangeDetails->data->adult_male, ENT_QUOTES);
            $adultFemaleRange   = htmlspecialchars($standardRangeDetails->data->adult_female, ENT_QUOTES);
            $generalRange       = htmlspecialchars($standardRangeDetails->data->general, ENT_QUOTES);
        } else {
            $rangeId = 0;
            $childRange = '';
            $adultMaleRange = '';
            $adultFemaleRange = '';
            $generalRange = '';
        }
?>
        <div class="row px-3 pt-3 mb-3 bg-light rounded param-box" id="dynamic-row-<?= $rowCount; ?>">
            <!-- Delete Parameter Button -->
            <div class="text-right w-100">
                <button type="button" class="btn btn-sm btn-primary add-old-heading-btn" data-index="<?= $rowCount; ?>">
                    <i class="fas fa-plus-circle"></i> Add Field
                </button>

                <button type="button" class="btn btn-sm btn-danger" title="Delete-Parameter" id="del-prev-param" param-id="<?= $param->id; ?>" range-id="<?= $rangeId; ?>">Delete</button>
            </div>
            <div class="col-12 mb-3">
                <div class="row">
                    <input type="hidden" id="test-param-id-<?= $rowCount ?>" value="<?= $param->id; ?>">
                    <div class="col-6">
                        <label for="param-name-<?= $rowCount; ?>" class="form-label">Parameter Name</label>
                        <input type="text" class="form-control" id="param-name-<?= $rowCount; ?>" name="param-name" placeholder="Enter parameter name" value="<?= htmlspecialchars($param->name, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-6">
                        <label for="param-unit-<?= $rowCount; ?>" class="form-label">Parameter Unit</label>
                        <input type="text" id="param-unit-<?= $rowCount; ?>" name="param-unit" class="form-control" placeholder="Enter unit" value="<?= htmlspecialchars($param->unit); ?>" required>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <!-- Header Buttons -->
                <div id="dynamic-head-container-<?= $rowCount; ?>" class="col-md-2 col-sm-12">
                    <div class="d-flex">

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


            </div>

            <!-- Range Data -->
            <div class="col-12 mb-3">
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

        </div>
    <?php endforeach; ?>
    <input type="hidden" id="initial-row-index" value="<?= $rowCount; ?>">
    <input type="hidden" id="param-count" value="<?= $rowCount; ?>">

<?php else: ?>
    <input type="hidden" id="initial-row-index" value="<?= $rowCount; ?>">
    <input type="hidden" id="param-count" value="<?= $rowCount; ?>">
<?php endif; ?>
<input type="hidden" id="prev-row-count" value="<?= $rowCount; ?>">
<script>
    
</script>