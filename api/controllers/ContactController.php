<?php

namespace Api\Controllers;

require_once dirname(__DIR__, 1) . '/models/ContactModel.php';

use Models\Contact;

class ApiContactController {

    public function createContact($data) {
        $contactModel = new Contact();
        $result = json_decode($contactModel->createContact($data));
        // print_r($result);
        header("Content-Type: application/json; charset=UTF-8");

        if($result->status){
            echo json_encode(['status'=>true, 'message'=>$result->message]);
        }else{
            echo json_encode(['status'=>false, 'message'=>$result->message]);
        }
    }
}
