<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

//Intitilizing Doctor class for fetching doctors
$Products       = new Products();
$Request        = new Request;
$Pagination     = new Pagination();
$ProductImages  = new ProductImages();
$Admin = new Admin;


if (isset($_GET['tokenNo'])) {
    $token = $_GET['tokenNo'];
    $tableName = $_GET['table'];

    if ($tableName == 'Generate Quarry') {
        $table1 = 'query_request';
        $table2 = 'query_response';
    } elseif ($tableName == 'Generate Ticket') {
        $table1 = 'ticket_request';
        $table2 = 'ticket_response';
    }

    $selectMasterData = json_decode($Request->fetchMasterTicketData($table1, $token));
    // print_r($selectMasterData);
    // echo "<br>";

    $queryDetails = json_decode($Request->selectFromTableNames($token, $table2));
    if($queryDetails->status){
        $queryDetailsData = $queryDetails->data;
    }
    


    $adminId = $selectMasterData->data->admin_id;
    $msgSender = $selectMasterData->data->name;
    $senderEmail = $selectMasterData->data->email;
    $senderContact = $selectMasterData->data->contact;

    $adminData = json_decode($Admin->adminDetails($adminId));
    $user = $adminData->data->username;
    //==================================================

    /// dcument detaisl
    $filePath = TICKET_DOCUMENT_PATH;

    foreach ($queryDetailsData as $query) {
        // print_r($query);
        $ticketNo = $query->ticket_no;
        $lastMsgTitle = $query->title;
        $fileName = $query->attachment;
    }

    if ($fileName == '') {
        $fullFilePath = '';
    } else {
        $filePath = TICKET_DOCUMENT_PATH;
        $fullFilePath = $filePath . $fileName;
    }

    // $fullFilePath = false;
    // $fileType = pathinfo($fullFilePath, PATHINFO_EXTENSION);
    $masteruUrlPath = ADM_URL;
} else {
    $ticketNo = '';
    $user = '';
    $msgSender = '';
    $senderEmail = '';
    $lastMsgTitle = '';
    $senderContact = '';
    $queryDetailsData = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Medicy Employees</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/employees.css">
    <link href="<?php echo CSS_PATH ?>form.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>/custom/password-show-hide.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include SUP_ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include SUP_ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin container-fluid -->
                <div class="container-fluid">
                    <div class="card-body shadow">
                        <div class="row d-flex text-center">
                            <div class="col-md-12">
                                <!-- data holder -->
                                <div class="row d-flex">
                                    <div class="col-md-4 form-group d-none">
                                        <input type="text" class="med-input" id="master-table" name="master-table" value="<?= $table1 ?>" required readonly>
                                    </div>
                                    <div class="col-md-4 form-group d-none">
                                        <input type="text" class=" med-input" id="respnse-table-name" name="table-name" value="<?= $table2 ?>" required readonly>
                                    </div>
                                    <div class="col-md-4 form-group d-none">
                                        <input type="text" class=" med-input" id="user-id" name="user-id" value="<?= $adminId; ?>" required readonly>
                                    </div>
                                </div>
                                <!-- ticket number & user name -->
                                <div class="row d-flex">
                                    <div class="col-md-4 form-group">
                                        <input type="text" class="med-input" id="ticket-no" name="ticket-no" value="<?= $ticketNo; ?>" required readonly>
                                        <label class="med-label" style="margin-left:10px;" for="ticket-no">Ticket No</label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input type="text" class=" med-input" id="user-name" name="user-name" value="<?= $user ?>" required readonly>
                                        <label class="med-label" style="margin-left:10px;" for="user">User</label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input type="text" class=" med-input" id="msg-sender" name="msg-sender" value="<?= $msgSender; ?>" required readonly>
                                        <label class="med-label" style="margin-left:10px;" for="msg-sender">Sender</label>
                                    </div>
                                </div>
                                <!-- sender and email -->
                                <div class="row d-flex">
                                    <div class="col-md-4 form-group">
                                        <input type="text" class=" med-input" id="email" name="email" value="<?= $senderEmail; ?>" required readonly>
                                        <label class="med-label" style="margin-left:10px;" for="emial">Email</label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input type="text" class="med-input" id="msg-title" name="msg-title" value="<?= $lastMsgTitle; ?>" required readonly>
                                        <label class="med-label" style="margin-left:10px;" for="msg-title">Title</label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input type="text" class="med-input" id="contact-no" name="contact-no" value="<?= $senderContact; ?>" required readonly>
                                        <label class="med-label" style="margin-left:10px;" for="contact-no">Contact No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-6">
                                <!-- query respo view -->
                                <div class="messaging-response-area">
                                    <div class="message mb-4 p-3 border rounded bg-light" style="overflow-y:scroll; max-height: 18rem;">
                                        <?php foreach ($queryDetailsData as $msgData) : ?>
                                            <?php if (!empty($msgData->message)) : ?>
                                                <div class="query mb-3">
                                                    <small>
                                                        <?php
                                                        $dateString = $msgData->added_on;
                                                        $dateTime = new DateTime($dateString);
                                                        $formattedDate = $dateTime->format('F j, Y H:i');
                                                        echo $formattedDate;
                                                        ?>
                                                    </small>
                                                    <div class="form-control w-50" readonly style="height: auto; width:auto; background-color:#ffd9b3; color:black;"><?php echo htmlentities($msgData->message); ?></div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($msgData->response)) : ?>
                                                <div class="response d-flex flex-column align-items-end">
                                                    <small><?php
                                                            $dateString = $msgData->added_on;
                                                            $dateTime = new DateTime($dateString);
                                                            $formattedDate = $dateTime->format('F j, Y H:i');
                                                            echo $formattedDate;
                                                            ?></small>
                                                    <div class="form-control w-50" readonly style="height: auto; width:auto; background-color:#b3e6ff; color:black;"><?php echo htmlentities($msgData->response); ?></div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card med-card" style="border: 1px solid #ced4da; padding: 1rem; height: 18rem; position: relative;">
                                    <div id="document-show-1" class="col-sm-11 card med-card"></div>
                                </div>
                                <label class="med-label text-primary mt-n4" for="fileInput1" style="margin-left:10px;">Document</label>
                                <i class="fas fa-upload text-primary" id="upload-document1" style="position: absolute; left: 18rem; bottom: 3rem; cursor: pointer;" onclick="document.getElementById('fileInput1').click();"></i>
                                <input type="file" class="d-none" name="fileInput1" id="fileInput1" value="<?= $fileName; ?>" onchange="takeInputFile(this, 'document-show-1')">
                                <input type="text" class="d-none" id="db-file-data-holder" value="<?= $fileName; ?>">
                            </div>
                        </div>
                        <hr class="my-2">
                        <!-- response div -->
                        <div class="row text-center">
                            <div class="col-md-12 mt-2">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <textarea class="med-input" placeholder="" name="query-responce" id="query-responce" style="max-height: 90px; min-height: 90px;" required></textarea>
                                        <label class="med-label" style="margin-left:10px;" for="query-responce">Responce Message</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 d-flex justify-content-end mt-2">
                                <button type="submit" name="ticket-query-response-submit" id="ticket-query-response-submit" class="btn btn-sm btn-primary" onclick="responseOfQuery(this)">Send Responce</button>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End of container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <!-- <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <script src="<?php echo ADM_JS_PATH ?>custom-js.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo ADM_JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>
    <script src="<?php echo ADM_JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo ADM_JS_PATH ?>sb-admin-2.js"></script>
    <script src="<?php echo ADM_JS_PATH ?>ticket-query-response.js"></script>


    <script>
        function displayFileFromDatabase(fileUrl, previewId) {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const preview = document.getElementById(previewId);
                    const fileType = fileUrl.split('.').pop().toLowerCase();
                    const contentType = xhr.getResponseHeader("Content-Type");
                    const blob = new Blob([xhr.response], {
                        type: contentType
                    });
                    const reader = new FileReader();
                    reader.onload = function() {
                        const base64data = reader.result;
                        if (fileType === 'pdf') {
                            preview.innerHTML = `<embed src="${base64data}" type="application/pdf" width="100%" height="100%">`;
                        } else if (fileType === 'jpg' || fileType === 'jpeg' || fileType === 'png') {
                            preview.innerHTML = `<img src="${base64data}" style="max-width: 100%; max-height: 12rem;">`;
                        } else {
                            preview.innerHTML = `<p>Unsupported file format</p>`;
                        }
                    };
                    reader.readAsDataURL(blob);
                }
            };
            xhr.open('GET', fileUrl, true);
            xhr.responseType = 'arraybuffer';
            xhr.send();
        }

        // PHP code to embed JavaScript
        <?php if (!empty($fileName)) : ?>
            const fileUrl = <?php echo json_encode($fullFilePath); ?>;
            displayFileFromDatabase(fileUrl, 'document-show-1');
        <?php endif; ?>
    </script>

</body>

</html>