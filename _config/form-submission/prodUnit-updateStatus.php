<?php
// print_r(dirname(dirname(dirname(__DIR__))) . '/config/constant.php');
require_once dirname(dirname(dirname(__DIR__))) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';

$MeasureOfUnits = new MeasureOfUnits();

// $showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();
// print_r($showMeasureOfUnits);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $unitId = $_POST['unitId'];

    $showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnitsById($unitId);
    print_r($showMeasureOfUnits);
    if (!empty($showMeasureOfUnits)) {
        $unitId     = $showMeasureOfUnits['id'];
        $sortName   = $showMeasureOfUnits['short_name'];
        $fullName   = $showMeasureOfUnits['full_name'];
        $addedBy    = $showMeasureOfUnits['added_by'];
        $addedOn    = $showMeasureOfUnits['added_on'];
        $updatedBy  = $showMeasureOfUnits['updated_by'];
        $updatedOn  = $showMeasureOfUnits['updated_on'];
        $adminId    = $showMeasureOfUnits['admin_id'];
        // echo $addedOn;


        $insert = $MeasureOfUnits->insertUnitactivity($unitId, $sortName, $fullName, $addedBy, $addedOn, $updatedBy, $updatedOn);
        if ($insert) {
            echo json_encode(['success' => true, 'message' => 'Data inserted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to insert data']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid unitId']);
    }
    // echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    // echo "none";
}
