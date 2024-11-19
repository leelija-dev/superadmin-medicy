<?php

require_once dirname(__DIR__, 1) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';
require_once SUP_ADM_DIR . '_config/accessPermission.php';


require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';



$Request = new Request;

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['data'])) {

        $ticketNo = htmlspecialchars($_POST['ticketNo']);
        $tableIdentity = htmlspecialchars($_POST['tableIdentity']);

        if ($tableIdentity === 'Generate Ticket') {
            $table1 = 'ticket_response';
            $table2 = 'ticket_request';
        }

        if ($tableIdentity === 'Generate Quarry') {
            $table1 = 'query_response';
            $table2 = 'query_request';
        }

        $ticketDetails = json_decode($Request->fetchedTicketQueryData($table1, $table2, $ticketNo));

        if ($ticketDetails->status) {
            echo json_encode(['status' => true, 'data' => $ticketDetails->data, 'masterTable' => $table2, 'responseTable' => $table1, 'message' => 'Data found']);
        } else {
            echo json_encode(['status' => false, 'message' => 'No Data found']);
        }
    } else {
        $response = [
            'status' => false,
            'message' => 'Required data is missing.'
        ];
        echo json_encode($response);
    }
} else {
    $response = [
        'status' => false,
        'message' => 'Invalid request method.'
    ];
    echo json_encode($response);
}
