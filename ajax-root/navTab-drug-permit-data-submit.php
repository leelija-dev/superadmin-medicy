<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; // Check if admin is logged in

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'subscription.class.php';
require_once CLASS_DIR.'utility.class.php';

$HealthCare     = new HealthCare;
$Subscription   = new Subscription;
$Utility        = new Utility;

function handleFileUpload($fileKey, $directory)
{
    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {

        $fileName = $_FILES[$fileKey]['name'];
        $tmpFileName = $_FILES[$fileKey]['tmp_name'];
        
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($k = 0; $k < 9; $k++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        $nowString = MILI_NOW;
        $microtime = strtotime($nowString) + (floatval(substr($nowString, -3)) / 1000);
        $milliseconds = sprintf("%06d", ($microtime - floor($microtime)) * 1000000);
        $datetime = new DateTime('@' . floor($microtime));
        $formattedDateTime = $datetime->format('YmdHis') . substr($milliseconds, 0, 6);

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileNewName = $formattedDateTime . '-' . $randomString . '.' . $extension;
        $fileFolder = $directory . $fileNewName;

        if (move_uploaded_file($tmpFileName, $fileFolder)) {
            return addslashes($fileNewName);
        }
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gstin = $_POST['gstin'] ?? '';
    $pan = $_POST['pan'] ?? '';

    if($_POST['flagA'] == 1){
        $form20fileNewName = handleFileUpload('form20', CLINIC_PERMIT_FORM_DIR);
    }else if($_POST['flagA'] == 2){
        $form20fileNewName = $_POST['oldForm20'];
    }
    
    if($_POST['flagB'] == 1){
        $form21fileNewName = handleFileUpload('form21', CLINIC_PERMIT_FORM_DIR);
    }else if($_POST['flagB'] == 2){
        $form21fileNewName = $_POST['oldForm21'];
    }
    
    if ($form20fileNewName && $form21fileNewName) {
        // Update clinic data
        $updateClinicData = $HealthCare->updateDrugPermissionData($form20fileNewName, $form21fileNewName, $gstin, $pan, $ADMINID);

        $updateInfo = json_decode($updateClinicData);
        if ($updateInfo->status == '1') {
            print_r($updateClinicData);
        } else if ($updateInfo->status == '2') {
            print_r($updateClinicData);
        } else {
            print_r($updateClinicData);
            // echo json_encode(['status' => 'Failed', 'message' => 'Failed to update clinic data', 'icon' => 'error']);
        }
    } else {
        echo json_encode(['status' => 'Failed', 'message' => 'Failed to move uploaded files', 'icon' => 'error']);
    }
} else {
    echo json_encode(['status' => 'Failed', 'message' => 'Request not found!', 'icon' => 'error']);
}
