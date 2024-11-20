<?php

require_once dirname(__DIR__, 1) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';
require_once SUP_ADM_DIR . '_config/accessPermission.php';


require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

$Request = new Request;

function renameInputedFile($fileName, $tmpFileName){

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

    $extention = substr($fileName, -4);
    $uploadedFileName = substr($fileName, 0, -4);

    $fileNewName  =   $formattedDateTime . '-' . $randomString . $extention;
    // echo $fileNewName;
    // echo TICKET_DOCUMENT_DIR;
    $fileFolder     = TICKET_DOCUMENT_DIR . $fileNewName;

    move_uploaded_file($tmpFileName, $fileFolder);
    $updatedFilename     =  addslashes($fileNewName);

    return $updatedFilename;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $masterTable = $_POST['master-table'];
    $responseTable = $_POST['respnse-table-name'];
    $adminId = $_POST['user-id'];
    $ticketNo = $_POST['ticket-no'];
    $adminUsername = $_POST['user-name'];
    $msgSender = $_POST['msg-sender'];
    $email = $_POST['email'];
    $contact = $_POST['contact-no'];
    $msgTitle = $_POST['msg-title'];
    $response = $_POST['query-responce'];
    $prevFileInput = $_POST['prev-file-input'];
    // print_r($_POST);
    // echo $prevFileInput;
    // $fileName = $_POST['fileName'];
    // $filePath = $_POST['filePath'];

    $status = 1;
    $viewStatus = 1;
    //======================================
    if($_FILES['new-file-input']['size'] > 0){
        $fileName = $_FILES['new-file-input']['name'];
        $tmpFileName = $_FILES['new-file-input']['tmp_name'];
        $updatedFilename = renameInputedFile($fileName, $tmpFileName);
    }else{
        $updatedFilename = $prevFileInput;
    }
    // print_r($_POST);
    $addResponse = $Request->addResponseToTicketQueryTable($responseTable, $ticketNo, $msgTitle, $updatedFilename, $response, $status, NOW, $viewStatus);
    // print_r($addResponse);

    $updatedResponse = json_decode($addResponse);
    if ($updatedResponse->status) {
        $updatedStatus = 'INACTIVE';
        $updateMasterTable = $Request->updateStatusByTableName($masterTable, $ticketNo, $updatedStatus, NOW);

        $updateMasterTableStatus = json_decode($updateMasterTable);
        if ($updateMasterTableStatus->status) {
            print_r($updateMasterTable);
        } else {
            print_r($updateMasterTable);
        }
    } else {
        print_r($addResponse);
    }
}
/* ============================ End ============================ */
