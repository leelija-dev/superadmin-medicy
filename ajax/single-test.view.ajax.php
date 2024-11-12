<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/user-details.inc.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'UtilityFiles.class.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


$showTestId = $_GET['sigleTestId'];
$Pathology       = new Pathology;

$allTests = json_decode($Pathology->showTestById($showTestId));

// print_r($allTests);
if ($allTests->status) {
    $allTests = $allTests->data;
    $testName = $allTests->name;
    $testDesc = $allTests->dsc;
    $testPrice = $allTests->price;
    $testPreparation = $allTests->preparation;
    $testreportType = $allTests->report_type;
    $testStatus = $allTests->status;
}
?>
<div>

    <div class="mb-4">
        <p class="<?= $testStatus == 1 ? 'p-2 h4 rounded-0 text-black text-center font-weight-bold' : 'p-2 h4 rounded-0 text-white text-center font-weight-bold' ?>"
            style="<?= $testStatus == 1 ? 'background-color: lightgreen;' : ' background-color: lightcoral;' ?>">
            <?= $testName ?>
        </p>
    </div>
    <div class="ml-3">
        <div class="d-flex  justify-content-between">
            <div>
                <p><b>Price : </b>&#x20b9; <?= $testPrice ?> </p>
            </div>
            <div class="d-flex">
                <p><b>Report Type:</b> </p>
                <p class="ml-3"><?= $testreportType == 1 ? 'Single Field' : ($testreportType == 2 ? 'Text Editor' : '0') ?></p>
            </div>
            <div class="d-flex justify-content-between align-items-center mr-5">
                <p class="m-0"><b>Status : </b> </p>
                <span class="badge <?= $testStatus == 1 ? 'status-active px-3 mx-3' : 'status-inactive px-3 mx-3' ?>">
                    <?= $testStatus == 1 ? 'Active' : 'Inactive' ?>
                </span>
            </div>
        </div>

        <div>
            <p class="h5"><b>Description:</b></p>
            <p><?= $testDesc ? $testDesc : 'Nil' ?></p>
        </div>
    </div>
</div>