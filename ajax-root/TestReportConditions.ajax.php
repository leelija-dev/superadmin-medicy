<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';

require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'PathologyReport.class.php';

$Pathology          = new Pathology;
$PathologyReport    = new PathologyReport;


if (isset($_POST['testId'])) {

    if (!empty($_POST['testId'])) {

        // Check if a comma exists in the string
        if (strpos($_POST['testId'], ',') !== false) {
            // Comma exists, explode the string into an array
            $firstTestId = explode(',', $_POST['testId']);
            $firstTestId = $firstTestId[0];
        } else {
            $firstTestId = $_POST['testId'];
        }

        $response = json_decode($Pathology->showTestById($firstTestId));
        // print_r($response);
        $catId = $response->data->cat_id;

        $catResponse = json_decode($Pathology->showTestByCat($catId));
        if ($catResponse->status) {
            foreach ($catResponse->data as $eachData) {
                $allowedTests[] = $eachData->id;
            }
            echo json_encode($allowedTests);
        }
    }
}
