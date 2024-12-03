<?php

require_once dirname(__DIR__) . '/config/constant.php';
// require_once 'config/constant.php';
require_once 'controllers/DragPermitController.php';

use Api\Controllers\DragPermitController;

require_once "./headers.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode('/', $uri);

$method = $_SERVER['REQUEST_METHOD'];
// print_r($method);  die;
// if ($third_segment === 'api' && $forth_segment == 'products.php') {
if ($uri[$uriPosition] === 'api' && str_contains($uri[$uriContains], 'dragPermit.php')) {

    $controller = new DragPermitController();
    switch ($method) {

        case 'PUT':

            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

            if (strpos($contentType, 'multipart/form-data') !== false) {
                $boundary = substr($contentType, strpos($contentType, "boundary=") + 9);
                $inputData = file_get_contents("php://input");

                // Parse the multipart data into an associative array
                $parts = explode("--" . $boundary, $inputData);
                $data = [];
                foreach ($parts as $part) {
                    if (strpos($part, 'Content-Disposition: form-data;') !== false) {
                        preg_match('/name="([^"]*)"/', $part, $matches);
                        $name = $matches[1] ?? '';

                        // Check if the part is an image file
                        if (strpos($part, 'filename="') !== false) {
                            preg_match('/filename="([^"]*)"/', $part, $fileMatches);
                            $filename = $fileMatches[1] ?? '';

                            // Extract file content
                            $fileContent = substr($part, strpos($part, "\r\n\r\n") + 4, -2);
                            $tempPath = sys_get_temp_dir() . '/' . $filename;
                            file_put_contents($tempPath, $fileContent);

                            // Store file data under its field name
                            $data[$name]['filename'] = $filename;
                            $data[$name]['temp_path'] = $tempPath;
                        } else {
                            // Handle regular form data
                            $value = trim(substr($part, strpos($part, "\r\n\r\n") + 4));
                            $data[$name] = $value;
                        }
                    }
                }

                // Check if specific image fields are provided
                // if (!empty($data['form_20']['filename']) && !empty($data['form_21']['filename'])) {
                if (!empty($data)) {
                    $defined_token = 'prod_details';
                    $id = $data['id'] ?? null;
                    // $adm_id = $data['adminId'] ?? null;
                    $token = $data['token'] ?? '';
                    // print_r($data);  die;
                    if ($defined_token == $token) {
                        $controller->updateDragPermit($id, $data);
                        $response = [
                            'status' => true,
                            'message' => 'Images updated successfully',
                        ];
                    } else {
                        $response = [
                            'status' => false,
                            'message' => 'Invalid token',
                        ];
                    }
                } else {
                    $response = [
                        'status' => false,
                        'message' => 'Required image fields are missing',
                        'data'   => $data
                    ];
                }
                echo json_encode($response);
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'Unsupported Content-Type',
                );
                echo json_encode($response);
            }
            break;
        case 'GET':
            $defined_token = 'drug_permit_details';
            if ($_GET['name'] == 'drug-permit') {
                $token = $_GET['token'];
                if ($defined_token == $token) {
                    $productId = $_GET['id'];
                    $data = $controller->getDrugPermitDetails($productId);
                    if (true) {
                        $response = array(
                            'status' => true,
                            'message' => 'details fetched successfully',
                            'data' => $data,
                        );
                        echo json_encode($response);
                    }
                }
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'Key value must be wrong',
                );
                echo json_encode($response);
            }
            break;
    }
} else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["message" => "Endpoint not found"]);
}
