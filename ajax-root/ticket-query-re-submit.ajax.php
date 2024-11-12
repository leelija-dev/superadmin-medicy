<?php
/*
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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['reQuery']) {

    
