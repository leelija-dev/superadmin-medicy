<?php
require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
// require_once dirname(__DIR__).'/config/constant.php';
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'admin.class.php';

if(isset($_POST['id'])) { 
    $customerId = $_POST['id'];
    print_r($customerId);
    $adminData  = new Admin();
    $customerDel = $adminData->deleteAdminData($customerId);

    if ($customerDel) {
        echo 1;
    } else {
        echo 0;
    }
} else {
    echo "No id provided";
}

?>