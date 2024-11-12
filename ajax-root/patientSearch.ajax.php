<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . "dbconnect.php";
require_once CLASS_DIR . "search.class.php";


$Search = new Search();

// echo "Data id: ".$_GET['data'];

$result = $Search->searchCustomerByAdmin($_GET['data'], $adminId);
// print_r($result);

if (count($result) > 0) {
    foreach ($result as $patient) {
        $id = $patient['patient_id'];
        echo '<div class="row border-bottom customer-row" id="' . $id . '" onclick="setPatient(this.id);">
            <div class="col-md-6">
            <b>' . $patient['name'] . '</b><br>
            <small>' . $patient['patient_id'] . '</small></div>
        </div>';
    }

    echo '    
        <div class="d-flex flex-column justify-content-center">
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addnewTestbill"
            onclick="addnewpatient()">
            Add New
            <i class="fas fa-plus-circle"></i>
            </button>
        </div>';
} else {
    echo '    
        <div class="d-flex flex-column justify-content-center">
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addnewTestbill" onclick="addnewpatient()">
            Add New
            <i class="fas fa-plus-circle"></i>
            </button>
        </div>';
}
