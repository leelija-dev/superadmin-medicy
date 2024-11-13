<?php

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = ['https://medicy.in', 'http://localhost:5173'];

$server = LOCAL_DIR;
// echo $server;
if ($server === '/') {
// if ($server === '/superadmin-medicy/') {
    // echo $server;
    $uriPosition = 1;
    $uriContains = 2;
}

if ($server === '/medicy.in/') {
    $uriPosition = 2;
    $uriContains = 3;
}
if ($server === '/superadmin-medicy/') {
    $uriPosition = 2;
    $uriContains = 3;
}

// Set headers for CORS and response content type
if (in_array($origin, $allowed_origins)) {
    // echo $origin;
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=UTF-8");
}
