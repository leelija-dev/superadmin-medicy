<?php
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'distributor.class.php';

$Distributor = new Distributor();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['distributorId']) && isset($_POST['newStatus'])) {
    $distributorId = $_POST['distributorId'];
    $newStatus = $_POST['newStatus'];

$showDistRequest  = json_decode($Distributor->showDistRequest($distributorId));
$showDistRequest  = $showDistRequest->data;
// print_r($showDistRequest);
if (is_array($showDistRequest)) {
    foreach ($showDistRequest  as $rowDistributor) {
        // print_r($rowDistributor);
        $distributorId      = $rowDistributor->dist_id;
        $distributorName    = $rowDistributor->name;
        $distributorPhno    = $rowDistributor->phno;
        $distributorPin     = $rowDistributor->area_pin_code;
        $distributorEmail   = $rowDistributor->email;
        $distributorAddr    = $rowDistributor->address;
        $distributorDsc     = $rowDistributor->dsc;
        $distributorStatus  = $rowDistributor->status;
    }
}




    $updateDistStatus = $Distributor->updateDistStatus($newStatus, $distributorId);
    if ($updateDistStatus) {
        $updateDist = $Distributor->updateDist($distributorName, $distributorAddr, $distributorPin, $distributorPhno, $distributorEmail, $distributorDsc, $supAdminId, NOW, $distributorId);
        $deleteDistRequest = $Distributor->deleteDistRequest($distributorId);
        $updateNewBadges   = $Distributor->updateNewBadges($distributorId);
        // echo json_encode($deleteDistRequest);
        if ($deleteDistRequest || $updateNewBadges || $updateDist) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete distributor request']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update distributor status']);
    }
} else {
    echo 'Invalid request';
}
