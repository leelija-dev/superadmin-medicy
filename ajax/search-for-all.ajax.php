<?php 
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'searchForAll.class.php';

$SearchForAll = new SearchForAll;

// === sod fixd date data fetch =======
if(isset($_GET['searchKey'])){
    $searchFor = $_GET['searchKey'];
    print_r($searchFor);
    $appointmentsResult = json_decode($SearchForAll->searchAllFilterForAppointment($searchFor, $adminId));
    $patientsResult = json_decode($SearchForAll->searchAllFilterForPatient($searchFor, $adminId));
    $stockIn = json_decode($SearchForAll->searchAllFilterForStockIn($searchFor, $adminId));
    // if($searchResult->status){
    //     $searchResult = $searchResult->data;
    // }
    $combinedResult = [
        'appointments' => $appointmentsResult,
        'patients'     => $patientsResult,
        'stockin'      => $stockIn
    ];

    print_r($combinedResult);
    echo json_encode($combinedResult);
}

?>