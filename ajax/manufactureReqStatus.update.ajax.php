<?php
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'manufacturer.class.php';

$Manufacturer = new Manufacturer();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manufacturerId']) && isset($_POST['newStatus'])) {
    $manufacturerId = $_POST['manufacturerId'];
    $newStatus = $_POST['newStatus'];

    $showManufacturer = $Manufacturer->showRequestManufacturer($manufacturerId);
    if ($showManufacturer !== null) {
        $showManufacturer = json_decode($showManufacturer);
        if (is_array($showManufacturer)) {
            foreach ($showManufacturer as $rowManufacturer) {

                $manufacturerId          = $rowManufacturer->manu_id;
                $manufacturerName        = $rowManufacturer->name;
                $manufacturerShortNamr   = $rowManufacturer->short_name;
                $manufacturerDsc         = $rowManufacturer->dsc;
                $manufacturerStatus      = $rowManufacturer->status;
            }
        }
    }

    $updateManuStatus = $Manufacturer->updateManuStatus($newStatus, $manufacturerId);
    if ($updateManuStatus) {
        $updateManufacturer = $Manufacturer->updateManufacturer($manufacturerName, $manufacturerDsc, $manufacturerId, $manufacturerShortNamr, $supAdminId, NOW);
        $deleteManufacturer = $Manufacturer->deleteRequestManufacturer($manufacturerId);
        $updateNewBadges = $Manufacturer->updateNewBadges($manufacturerId);
        if ($deleteManufacturer || $updateNewBadges || $updateManufacturer) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete Manufacturer request']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update Manufacturer status']);
    }
    // if($updateManuStatus){
    //     echo json_encode($updateManuStatus);
    // }
} else {
    echo 'Invalid request';
}
