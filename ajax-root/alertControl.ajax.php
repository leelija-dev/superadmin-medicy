<?php
require_once dirname(__DIR__) . '/config/constant.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

$Request = new Request;


$ticketNo = url_dec($_GET['ticket']);
$check = $Request->editResponseCheck($ticketNo);
if($check == true){
    echo true;
}else{
    echo false;
}

