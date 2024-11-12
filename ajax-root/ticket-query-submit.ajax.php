<?php
// echo dirname(dirname(__DIR__)) . '/config/constant.php';
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


$Request        = new Request;
$Utility        = new Utility;
$IdsGeneration  = new IdsGeneration;

$uniqueNumber = $Utility->ticketNumberGenerator();
$status = 'ACTIVE';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['formFlag'] === '1') {
        $table1 = $_POST['table'];
        $user = $_POST['user'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $title = $_POST['title'];
        $message = $_POST['message'];

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['file']['name'];
            $tmpFileName = $_FILES['file']['tmp_name'];

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
            $fileFolder     = TICKET_DOCUMENT_DIR . $fileNewName;

            move_uploaded_file($tmpFileName, $fileFolder);
            $updatedFile         = addslashes($fileNewName);
        } else {
            $updatedFile = '';
        }


        $submitTicket = $Request->addNewQueryRequest($table1, $uniqueNumber, $email, $contact, $user, $title, $message, $updatedFile, $adminId, $status, NOW);

        $submitTicketResponse = json_decode($submitTicket);

        if ($submitTicketResponse->status) {
            if ($table1 == 'ticket_request') {
                $table2 = 'ticket_response';
            }

            if ($table1 == 'query_request') {
                $table2 = 'query_response';
            }


            $ticketNo = $uniqueNumber;
            $status = 1;
            $viewStatus = 0;
            $submitTicketResponseTable = $Request->addToTicketQueryTable($table2, $ticketNo, $title, $message, $updatedFile, '', $user, $adminId, $status, NOW, $viewStatus);

            $submitTicketResponseTableStatus = json_decode($submitTicketResponseTable);
            if ($submitTicketResponseTableStatus->status == true) {
                print_r($submitTicketResponseTable);
            } else {
                print_r($submitTicketResponseTable);
            }
        } else {
            print_r($submitTicket);
        }
    }





    if ($_POST['formFlag'] === '2') {
        
        $masterTable        =   $_POST['masterTable'];
        $table              =   $_POST['requestTable'];
        $masterTicket       =   $_POST['masterTicket'];
        $msgTitle           =   $_POST['msgTitle'];
        $message            =   $_POST['newQuery'];
        $fileName           =   $_POST['fileName'];
        $filePath           =   $_POST['filePath'];

        if ($fileName != '' && $filePath !='') {
            $file = $_FILES['file'];
            $fileName = $_FILES['file']['name'];
            $tmpFileName = $_FILES['file']['tmp_name'];

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
            $fileFolder     = TICKET_DOCUMENT_DIR . $fileNewName;

            move_uploaded_file($tmpFileName, $fileFolder);
            $updatedFile         = addslashes($fileNewName);
        } else {
            $updatedFile = $fileName;
        }

        $status = '1';
        $submitTicketRequery = $Request->addQueryAgainstResponse($table, $masterTicket, $msgTitle, $message, $updatedFile, $adminId, $status, NOW);

        $submitTicketRequeryStatus = json_decode($submitTicketRequery);
        if($submitTicketRequeryStatus->status){
            $status = 'ACTIVE';
            $updateMasterTable = $Request->updateMasterTableStatus($masterTable, $masterTicket, $status, NOW);

            $masterTableStatus = json_decode($updateMasterTable);
            if($masterTableStatus->status){
                print_r($updateMasterTable);
            }else{
                print_r($updateMasterTable);
            }
        }else{
            print_r($submitTicketRequery);
        }
    }
}
