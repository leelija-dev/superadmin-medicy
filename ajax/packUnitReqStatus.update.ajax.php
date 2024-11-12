<?php
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'packagingUnit.class.php';

$PackagingUnits = new PackagingUnits();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unitId']) && isset($_POST['newStatus'])) {
    $packagingUnitId = $_POST['unitId'];
    $newStatus       = $_POST['newStatus'];

    $showPackagingRequest = json_decode($PackagingUnits->showPackagingRequest($packagingUnitId));
    if (is_object($showPackagingRequest) && property_exists($showPackagingRequest, 'data')) {
        $showPackagingRequest = $showPackagingRequest->data;
        if (is_array($showPackagingRequest) || is_object($showPackagingRequest)) {
            foreach ($showPackagingRequest as $rowPackagingUnits) {
                $unitId     = $rowPackagingUnits->pack_id;
                $unitName   = $rowPackagingUnits->unit_name;
                $packStatus = $rowPackagingUnits->status;
            }
        }
    }
    $updatePackagingStatus = $PackagingUnits->updatePackStatus($newStatus, $packagingUnitId);
    if ($updatePackagingStatus) {

        $updateUnit = $PackagingUnits->updateUnit($unitId, $unitName, $supAdminId, NOW);
        $deletePackRequest = $PackagingUnits->deletePackRequest($packagingUnitId);
        $updateNewBadges   = $PackagingUnits->updateNewBadges($packagingUnitId);
        if ($deletePackRequest || $updateNewBadges || $updateUnit) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete Manufacturer request']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update Manufacturer status']);
    }
} else {
    echo 'Invalid request';
}
