<?php
// print_r(dirname(dirname(dirname(__DIR__))) . '/config/constant.php');
require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';

$MeasureOfUnits = new MeasureOfUnits();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $unitId = $_POST['unitId'];

        $deleteUnitActivity = $MeasureOfUnits->deleteUnitActivity($unitId);
        if ($deleteUnitActivity) {
            echo json_encode(['success' => true, 'message' => 'Data delete successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete data']);
        }
    // } else {
    //   echo json_encode(['success' => false, 'message' => 'Invalid unitId']);
    // }
    // echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    // echo "none";
}
