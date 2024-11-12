<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
// require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'distributor.class.php';

$requestId = isset($_POST['requestId']) ? $_POST['requestId'] : null;

$Distributor        = new Distributor();
$response    = json_decode($Distributor->showDistRequestById($requestId));
if(isset($response->status) && $response->status == 1){

    $data = $response->data;
    // print_r($data);
    if (!empty($data)) {
        $DistributorName    = $data->name;
        $DistributorAddress = $data->address;
        $DistributorPIN     = $data->area_pin_code;
        $DistributorPhno    = $data->phno;
        $DistributorEmail   = $data->email;
        $DistributorDsc     = $data->req_dsc;
    }
}
?>

<form>
    <input type="hidden" id="distributorId" value="<?php echo $distributorId; ?>">
    <div class="form-group">
        <label for="distributor-name" class="form-label mb-0 mt-0">Distributor Name:</label>
        <input type="text" class="form-control" id="distributor-name" value="<?= $DistributorName; ?>">
    </div>

    <div class="form-group">
        <label for="distributor-phno" class="form-label mb-0">Distributor Contact:</label>
        <input type="text" class="form-control" id="distributor-phno" value="<?= $DistributorPhno; ?>">
    </div>

    <div class="form-group">
        <label for="distributor-email" class="form-label mb-0">Distributor Email:</label>
        <input type="text" class="form-control" id="distributor-email" value="<?= $DistributorEmail; ?>">
    </div>


    <div class="form-group">
        <label for="distributor-address" class="form-label mb-0 mt-2">Distributor Address:</label>
        <textarea class="form-control" id="distributor-address" rows="3"><?= $DistributorAddress; ?></textarea>
    </div>

    <div class="form-group">
        <label for="distributor-area-pin" class="form-label mb-0">Area PIN:</label>
        <input type="text" class="form-control" id="distributor-pin" value="<?= $DistributorPIN; ?>">
    </div>

    <div class="form-group">
        <label for="distributor-dsc" class="form-label mb-0 mt-2">Description:</label>
        <textarea class="form-control" id="distributor-dsc" rows="2"><?= $DistributorDsc; ?></textarea>
    </div>

</form>