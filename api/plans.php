<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once 'controllers/PlansController.php';

use Api\Controllers\PlansController;

require_once "./headers.php";

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$method = $_SERVER['REQUEST_METHOD'];

if ($uri[$uriPosition] === 'api' && str_contains($uri[$uriContains], 'plans')) {

    $controller = new PlansController();

    switch ($method) {
        case 'GET':
            $plans = $controller->getAllPlans();
            echo json_encode($plans);
            break;

        default:
            header("HTTP/1.1 405 Method Not Allowed");
            break;
    }
} else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["message" => "Endpoint not found"]);
}
