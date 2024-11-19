<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'PathologyReportType.class.php';

$Pathology      = new Pathology;
$PathReportType = new PathologyReportType;

$rowCount = 1;

?>

<div class="w-100 d-flex justify-content-end p-3">
    <button type="button" class="btn btn-sm btn-primary mr-2" id="add-new-param-btn">Add New Parameter</button>
    <button type="button" class="btn btn-sm btn-danger" id="remove-last-param-btn">Delete Last Parameter</button>
</div>
