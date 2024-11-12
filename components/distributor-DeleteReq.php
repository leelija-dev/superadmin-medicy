<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'distributor.class.php';


//Class Initilizing
$Distributor = new Distributor();
if (isset($_POST['distId'])) {
    $delReqId = $_POST['distId'];
}

$showDist = $Distributor->showDistributorById($delReqId);
$showDistributor    = json_decode($showDist);


if (isset($showDistributor->status) && $showDistributor->status == 1) {
    $data = $showDistributor->data;

    if (!empty($data)) {
        $distributorId      = $data->id; 
        $DistributorName    = $data->name;
        $DistributorAddress = $data->address;
        $DistributorPIN     = $data->area_pin_code;
        $DistributorPhno    = $data->phno;
        $DistributorEmail   = $data->email;
        $DistributorDsc     = $data->dsc;
    }
}
?>
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Contact</th>
            <th>action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= $DistributorName ?> </td>
            <td><?= $DistributorPhno ?></td>
            <td>
                <a class="mx-1" id="delete-btn" data-id="<?= $distributorId ?>" data-toggle="tooltip" data-placement="left" title="Delete"><i class="far fa-trash-alt"></i></a>
                <a class="mx-1" onclick="cancelDeleteReqEmp(<?= $distributorId ?>)" data-toggle="tooltip" data-placement="left" title="Cancel Request"><i class="fas fa-times-circle" style="color: red;"></i></a>
            </td>
        </tr>
    </tbody>
</table>
