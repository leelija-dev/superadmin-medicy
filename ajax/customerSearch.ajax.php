<?php
require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
require_once SUP_ADM_DIR .'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR."dbconnect.php";
require_once CLASS_DIR."search.class.php";


$Search = new Search();

// echo "Data id: ".$_GET['data'];

$result = $Search->searchCustomer($_GET['data']);
// print_r($result);

if (count($result) > 0) {
    foreach ($result as $customer) {
        $id = $customer['patient_id'];
        echo '<div class="row border-bottom customer-row" id="'.$id.'" onclick="setCustomer(this.id);">
            <div class="col-md-6">
            <b>'.$customer['name'].'</b><br>
            <small>'.$customer['phno'].'</small></div>
        </div>';

    }
}else{
    echo'
    <div class="d-flex flex-column justify-content-center">
        <button type="button" id="add-customer" class="text-primary"
        data-toggle="modal" data-target="#add-customer-modal"
        onclick="addCustomerModal()"><i class="fas fa-plus-circle"></i>
        Add Now</button>
    </div>
    ';
}


?>


