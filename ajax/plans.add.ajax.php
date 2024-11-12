<?php
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'plan.class.php';
$Plan = new Plan();


if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $planName   = $_POST['plan-name'];
    $duration   = $_POST['plan-duration'];
    $price      = $_POST['plan-price'];
    $status     = $_POST['plan-status'];
    $planPermissions = $_POST['permissions'];
    $featuresArr   = $_POST['features'];

    $permissions = implode(",",$planPermissions);

    $response = $Plan->addPlanWithFeatures($planName, $permissions, $duration, $price, $status, $featuresArr);
    $response = json_decode($response);
    print_r($response->status);
}

exit;

?>