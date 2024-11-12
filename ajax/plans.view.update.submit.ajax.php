<?php

require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'plan.class.php';

$Plan = new Plan();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $planId = $_POST['plan-id'];
    $planName = $_POST['plan-name'];
    $duration = $_POST['plan-duration'];
    $price    = $_POST['plan-price'];
    $status   = $_POST['plan-status'];

    $permissions = implode(',',$_POST['permissions']);

    $response = $Plan->updatePlan($planId, $planName, $price, $duration, $status, $permissions);
    $response = json_decode($response);
    if ($response->status == 1) {
        $deleteRes = json_decode($Plan->deletePlanFeatures($planId));
        if ($deleteRes->status == 1) {
            foreach ($_POST['features'] as $eachFeature) {
                $finalResponse = $Plan->addPlanFeature($planId, $eachFeature, 1);
                print_r($finalResponse);
            }
        }
    }
}