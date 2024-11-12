<?php
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'plan.class.php';
require_once CLASS_DIR . 'rbacController.class.php';

$Plan = new Plan();
$RbacController = new RbacController;

$planId = $_GET['id'];

$plans = $Plan->getAllPlansById($planId);
$plans = json_decode($plans);

$permissionDetails = json_decode($RbacController->selectPermissionTableDetails());

if (isset($plans->status) && $plans->status == 1) {
    if (isset($plans->data) && is_object($plans->data)) {
        $planId     = $plans->data->id;
        $planName   = $plans->data->name;
        $duration   = $plans->data->duration;
        $price      = $plans->data->price;
        $status     = $plans->data->status;

        $permissionsArrya = explode(',', $plans->data->permission_id);

        if ($plans->data->features->status == 1) {
            $featureArr = $plans->data->features->data;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <!-- <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap/bootstrap.css"> -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="mx-2">
    <form onsubmit="addEditPlan(event)" id="plan-form">
        <div class="row mx-0">
            <input type="hidden" name="plan-id" id="plan-id" value="<?= $planId ?>">
            <div class="col-12 py-1">
                <input type="text" class="form-control" name="plan-name" id="plan-name" value="<?= $planName; ?>">
            </div>
            <div class="col-12 py-1">
                <input type="text" class="form-control" name="plan-duration" id="plan-duration" value="<?= $duration; ?>">
            </div>
            <div class="col-6 py-1">
                <input type="number" class="form-control" name="plan-price" id="plan-price" value="<?= $price; ?>">
            </div>
            <div class="col-6 py-1">
                <select class="form-control" name="plan-status" id="plan-status">
                    <option value="0" <?= $status == 0 ? 'selected' : ''; ?>>Deactive</option>
                    <option value="1" <?= $status == 1 ? 'selected' : ''; ?>>Active</option>
                </select>
            </div>

            <div class="col-12 py-1">
                <label class="col-form-label" for="permissions">Access Permissions:</label>
                <div class="p-3" style="height: 165px; overflow-y: auto;">
                    <?php
                    $count = 0;
                    foreach ($permissionDetails->data as $permissionData) {
                        $count++;
                        $isChecked = in_array($permissionData->permission_id, $permissionsArrya) ? 'checked' : '';
                    ?>
                        <div class="row d-flex">
                            <label>
                                <input type="checkbox" name="permissions[]" id="permission-id-<?= $count; ?>" value="<?= $permissionData->permission_id; ?>" <?= $isChecked; ?>>
                                <?= $permissionData->permissions; ?>
                            </label>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <div id="feature-container" class="col-12 py-1">
                <?php
                if (!empty($featureArr)) {
                    foreach ($featureArr as $eachFeature) {
                ?>
                        <div class="form-group">
                            <div class="d-flex my-2 feature-row">
                                <input type="text" class="form-control form-control-sm" name="features[]" value="<?= $eachFeature->feature; ?>">
                                <button class="btn btn-sm btn-danger remove-feature rounded-right">
                                    <i class="far fa-times-circle"></i>
                                </button>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
            <div class="col-12">
                <button type="button" class="btn btn-sm btn-primary w-100" id="addFeature">
                    Add Feature <i class="fas fa-plus-circle"></i>
                </button>
            </div>
        </div>

        <div class="mt-2 reportUpdate" id="reportUpdate">
            <!-- Ajax Update Report Goes Here -->
        </div>
        <input type="hidden" name="flag-data" id="flag-data" value="2">
        <div class="mt-2 d-flex justify-content-end">
            <button type="submit" class="btn btn-sm btn-primary">Update</button>
        </div>
    </form>




    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <!-- Bootstrap Js -->
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>

    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>
    <script src="<?= JS_PATH ?>sup-admin/plans-control.js"></script>

</body>

</html>