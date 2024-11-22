
<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'employee.class.php';

$empId = isset($_GET['empId']) ? $_GET['empId'] : null;
$empUsername = isset($_GET['empUsername']) ? $_GET['empUsername'] : null;
$empfName = isset($_GET['empfName']) ? $_GET['empfName'] : null;
$empLName = isset($_GET['empLName']) ? $_GET['empLName'] : null;
$empEmail = isset($_GET['empEmail']) ? $_GET['empEmail'] : null;
$empContact = isset($_GET['empContact']) ? $_GET['empContact'] : null;
$empAddress = isset($_GET['empAddress']) ? $_GET['empAddress'] : null;
$empRole = isset($_GET['empRole']) ? $_GET['empRole'] : null;
$empPermission = isset($_GET['empPermission']) ? $_GET['empPermission'] : null;

$employees = new Employees();
$EditEmp = $employees->updateEmp($empUsername, $empfName, $empLName, $empRole, $empPermission, $empEmail, $empContact, $empAddress, $empId);

if ($EditEmp) {
    echo "<div class='alert alert-primary alert-dismissible fade show' role='alert'>
            <strong>Success</strong> Your Employee Data Has been Updated!
        </div>";
} else {
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <strong>Failed!</strong> Employee Data Not Updated!
        </div>";
}
?>