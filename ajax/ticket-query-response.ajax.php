<?php

require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
require_once SUP_ADM_DIR.'_config/sessionCheck.php';
require_once SUP_ADM_DIR . '_config/accessPermission.php';


require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'request.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

$Request = new Request;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $masterTable = $_POST['masterTable'];
    $responseTable = $_POST['responseTable'];
    $adminId = $_POST['adminId'];
    $ticketNo = $_POST['ticketNo'];
    $adminUsername = $_POST['adminUsername'];
    $msgSender = $_POST['msgSender'];
    $email = $_POST['email'];
    $msgTitle = $_POST['msgTitle'];
    $contact = $_POST['contact'];
    $response = $_POST['queryResponse'];

    $fileName = $_POST['fileName'];
    $filePath = $_POST['filePath'];

    $status = 1;
    $viewStatus = 1;
    //======================================
    if ($fileName != '' && $filePath != '') {
        $file = $_FILES['file'];
        // print_r($file);
        $fileName = $_FILES['file']['name'];
        $tmpFileName = $_FILES['file']['tmp_name'];
        // echo $fileName;
        // echo $tmpFileName;

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
    } else {
        $updatedFilename = $fileName;
    }

    $addResponse = $Request->addResponseToTicketQueryTable($responseTable, $ticketNo, $updatedFilename, $response, $status, NOW, $viewStatus);
    // print_r($addResponse);

    $updatedResponse = json_decode($addResponse);
    if($updatedResponse->status){
        $updatedStatus = 'INACTIVE';
        $updateMasterTable = $Request->updateStatusByTableName($masterTable, $ticketNo, $updatedStatus, NOW);

        $updateMasterTableStatus = json_decode($updateMasterTable);
        if($updateMasterTableStatus->status){
            print_r($updateMasterTable);
        }else{
            print_r($updateMasterTable);
        }
    }else{
        print_r($addResponse);
    }
}
/* ============================ End ============================ */

?>
