<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'doctor.category.class.php';

$DoctorCategory = new DoctorCategory();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['docSpeclzn']) && isset($_POST['docSpeclznDsc'])) {
        // print_r($_POST);

        $docCatNme = $_POST['docSpeclzn'];
        $docDesc   = $_POST['docSpeclznDsc'];

        // echo $docCatNme;

        $addDoctorCategory = $DoctorCategory->addDoctorCategory($docCatNme, $docDesc, $employeeId, NOW, $adminId);
        if ($addDoctorCategory) {
            echo $addDoctorCategory;
        } else {
            echo 0;
        }
    }
}
