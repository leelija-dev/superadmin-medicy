<?php

require_once __DIR__ . '/config/constant.php';
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
    if ($queryDetails->status) {
        $queryDetailsData = $queryDetails->data;
    }



    $adminId = $selectMasterData->data->admin_id;
    $msgSender = $selectMasterData->data->name;
    $senderEmail = $selectMasterData->data->email;
    $senderContact = $selectMasterData->data->contact;

    $adminData = json_decode($Admin->adminDetails($adminId));
    // $user = $adminData->data->username;
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
                        <div class="row d-none">
                            <input type="text" id="ticket-number" name="ticket-number" value="<?= $token ?>" required readonly>
                            <input type="text" id="query-table" name="query-table" value="<?= $tableName ?>" required readonly>
                        </div>
                        <form id="admin-ticket-response" method="POST" enctype="multipart/form-data">
                            <!-- data holder -->
                            <div class="row d-flex">
                                <div class="col-md-4 form-group d-none">
                                    <label>Table Name</label></br>
                                    <input type="text" class="med-input" id="master-table" name="master-table" value="<?= $table1; ?>" required readonly>
                                </div>
                                <div class="col-md-4 form-group d-none">
                                    <label>Response Table Name</label></br>
                                    <input type="text" class=" med-input" id="respnse-table-name" name="respnse-table-name" value="<?= $table2; ?>" required readonly>
                                </div>
                                <div class="col-md-4 form-group d-none">
                                    <label>Sender admin id</label></br>
                                    <input type="text" class="med-input" id="user-id" name="user-id" value="<?= $adminId; ?>" required readonly>
                                </div>
                            </div>
                            <!-- ticket number & user name -->
                            <div class="row d-flex">
                                <div class="col-md-2 form-group">
                                    <label for="ticket-no">Token</label>
                                    <input type="text" class="w-100" id="ticket-no" name="ticket-no" value="<?= $ticketNo; ?>" required readonly>

                                </div>
                                <div class="col-md-3 form-group w-75">
                                    <label for="user">Ticket Launcher</label>
                                    <input type="text" id="user-name" name="user-name" value="<?= $msgSender ?>" required readonly>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="user">Query Sender</label>
                                    <input type="text" id="msg-sender" name="msg-sender" value="<?= $msgSender ?>" required readonly>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="emial">Email</label>
                                    <input type="text" class="w-100" id="email" name="email" value="<?= $senderEmail; ?>" required readonly>

                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="contact-no">Contact No</label>
                                    <input type="text" class="w-75" id="contact-no" name="contact-no" value="<?= $senderContact; ?>" required readonly>
                                </div>
                            </div>
                            <!-- image div -->
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <label for="msg-title">Message Title</label>&nbsp;
                                        <input type="text" class="w-75" id="msg-title" name="msg-title" value="<?= $lastMsgTitle; ?>" required readonly></br>
                                    </div></br>
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
                                        <div id="document-show" class="col-sm-11 card med-card"></div>
                                    </div>
                                    <label class="med-label text-primary mt-n4" for="fileInput" style="margin-left:10px;">Document</label>
                                    <i class="fas fa-upload text-primary" id="upload-document1" style="position: absolute; left: 18rem; bottom: 3rem; cursor: pointer;" onclick="document.getElementById('fileInput').click();"></i>
                                    <input type="file" class="d-none" name="new-file-input" id="fileInput" value="" onchange="takeInputFile(this, 'document-show')">
                                    <input type="text" class="d-none" id="db-file-data-holder" name="prev-file-input" value="<?= $fileName; ?>">
                                </div>
                            </div>
                            <hr class="my-2">
                            <!-- response div -->
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="med-label" for="query-responce">Responce Message</label>
                                    <textarea class="med-input w-100" placeholder="" name="query-responce" id="query-responce" style="max-height: 90px; min-height: 90px;" required></textarea>
                                </div>
                            </div>
                        </form>
                        <div class="col-md-12 d-flex justify-content-end mt-2">
                            <button type="submit" name="ticket-query-response-submit" id="ticket-query-response-submit" class="btn btn-sm btn-primary" onclick="ticketQueryResponse()">Send Responce</button>
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
    <script src="<?php echo ADM_JS_PATH ?>ajax.custom-lib.js"></script>
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
            displayFileFromDatabase(fileUrl, 'document-show');
        <?php endif; ?>
    </script>

</body>

</html>