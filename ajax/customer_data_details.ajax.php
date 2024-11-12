<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; // Check if admin is logged in

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'hospital.class.php';


$Admin = new Admin;
$Subscription = new Subscription;
$HealthCare = new HealthCare;

if (isset($_GET['adminId'])) {
    $adminId = $_GET['adminId'];
    

    $adminData = json_decode($Admin->adminDetails($adminId));
    $healthCareDetails = json_decode($HealthCare->showHealthCare($adminId));
    $subscriptionDetails = json_decode($Subscription->getSubscription($adminId));

    print_r($adminData);
    echo "<br><br>";
    print_r($healthCareDetails);
    echo "<br><br>";
    print_r($subscriptionDetails);

} else {
    echo 'No admin data found.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Details</title>
</head>
<body>
    <!-- BODY DESIGN GOSE HEAR -->
</body>
</html>
