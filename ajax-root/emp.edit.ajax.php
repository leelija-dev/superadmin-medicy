<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'employee.class.php';

$employees = new Employees();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['formFlag'] === 'editEmployeeData') {

        $empId                  = $_POST['empId'];
        $empUsername            = $_POST['empUsername'];
        $firstName              = $_POST['firstName'];
        $lastName               = $_POST['lastName'];
        $empRole                = $_POST['empRole'];
        $empAccessPermission    = $_POST['permission'];
        $empEmail               = $_POST['empEmail'];
        $empContact             = $_POST['empContact'];
            
        $EditEmp = $employees->updateEmp($empUsername, $firstName, $lastName, $empRole, $empAccessPermission, $empEmail, $empContact, $empId);
        print_r($EditEmp);

        // if ($EditEmp) {
        //     echo "<div class='alert alert-primary alert-dismissible fade show' role='alert'>
        //     <strong>Success</strong>Your Employee Data Has been Updateed!
        // </div>";
        // } else {
        //     $EditEmp = json_decode($EditEmp);

        //     echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
        //     <strong>$EditEmp->message;</strong> Employee Data Not Updated!
        // </div>";
        // }
    }
}

?>