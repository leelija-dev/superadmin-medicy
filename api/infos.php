<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once 'controllers/InfoController.php';

use Api\Controllers\ApiInfoController;

require_once "./headers.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);


$method = $_SERVER['REQUEST_METHOD'];

if ($uri[$uriPosition] === 'api' && str_contains($uri[$uriContains], 'infos')) {
    $controller = new ApiInfoController();

    switch ($method) {
        case 'GET':
            if (isset($uri[3]) && is_numeric($uri[3])) {
                $controller->getInfoByName($uri[3]);
            } else {
                $controller->getAllInfos();
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $controller->createInfo($data);
            break;

        case 'PUT':
            if (isset($uri[3]) && is_numeric($uri[3])) {
                $data = json_decode(file_get_contents('php://input'), true);
                $controller->updateInfo($uri[3], $data);
            }
            break;

        case 'DELETE':
            if (isset($uri[3]) && is_numeric($uri[3])) {
                $controller->deleteInfo($uri[3]);
            }
            break;

        default:
            header("HTTP/1.1 405 Method Not Allowed");
            break;
    }
}else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["message" => "Endpoint not found"]);
}
