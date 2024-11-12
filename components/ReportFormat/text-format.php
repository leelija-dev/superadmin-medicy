<?php
require_once dirname(dirname(dirname(__DIR__))) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'Pathology.class.php';

$Pathology      = new Pathology;

// if (isset($_GET['catId']) && isset($_GET['testId'])) {
if (isset($_GET['test-id'])) {

    $testId = $_GET['test-id'];

    $testDetails = json_decode($Pathology->showTestById($testId));

    if ($testDetails->status) {
        $reportTextFormat   = $testDetails->data->report_text_format;
    } else {
        $reportTextFormat   = '';
    }


}
?>

<textarea id="report-text-format-field" name="report-text-format" class="form-control" rows="40"><?= $reportTextFormat ?></textarea>
