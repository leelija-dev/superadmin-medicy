<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once CLASS_DIR . 'encrypt.inc.php';
// require_once 'config/constant.php';
require_once 'controllers/ProfileController.php';

use Api\Controllers\ProfileController;

require_once "./headers.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode('/', $uri);

$method = $_SERVER['REQUEST_METHOD'];

// if ($third_segment === 'api' && $forth_segment == 'products.php') {
if ($uri[$uriPosition] === 'api' && str_contains($uri[$uriContains], 'profile.php')) {

    $controller = new ProfileController();
    switch ($method) {
        case 'PUT':
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            if (strpos($contentType, 'multipart/form-data') !== false) {
                $boundary = substr($contentType, strpos($contentType, "boundary=") + 9);
                $inputData = file_get_contents("php://input");
                $parts = explode("--" . $boundary, $inputData);
                $data = [];
                foreach ($parts as $part) {
                    if (strpos($part, 'Content-Disposition: form-data;') !== false) {
                        preg_match('/name="([^"]*)"/', $part, $matches);
                        $name = $matches[1] ?? '';

                        if (strpos($part, 'filename="') !== false) {
                            preg_match('/filename="([^"]*)"/', $part, $fileMatches);
                            $filename = $fileMatches[1] ?? '';
                            preg_match('/Content-Type: ([^"]*)/', $part, $typeMatches);
                            $fileType = $typeMatches[1] ?? '';

                            $fileContent = trim(explode("\r\n\r\n", $part)[1]);
                            $tempPath = sys_get_temp_dir() . '/' . $filename;
                            file_put_contents($tempPath, $fileContent);

                            // Add image data to the $data array
                            $data['imagesName'] = $filename;
                            $data['tempImgsName'] = $tempPath;
                        } else {
                            // Regular form data
                            $value = trim(explode("\r\n\r\n", $part)[1]);
                            $data[$name] = $value;
                        }
                    }
                }

                if (!empty($data['imagesName'])) {
                    $defined_token = 'profile_details';
                    $enc_token = pass_enc($defined_token, ADMIN_PASS);
                    $dec_token = pass_dec($enc_token, ADMIN_PASS);
                    $token = $data['token'];
                    if ($token == $dec_token) {
                        $id = $data['id'];
                        // if ($data['token'] == $defined_token) {
                        $data = $controller->updateProfileImage($id, $data);
                        // print_r($data);  die();
                        if ($data['result'] == 1) {
                            $response = array(
                                'status' => true,
                                'message' => 'Image Update successfully',
                                'data'    =>  $data,
                            );
                        } else {
                            $response = array(
                                'status' => true,
                                'message' => 'Not Uploaded',
                            );
                        }
                    }
                } else {
                    $response = array(
                        'status' => false,
                        'message' => 'No image file provided',
                    );
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
            if ($_GET['name'] == 'admin-details') {
                $key = 'profile-detail';
                $enc_token = pass_enc($key, ADMIN_PASS);
                $dec_token = pass_dec($enc_token, ADMIN_PASS);
                $newToken = $_GET['token'];
                if ($dec_token == $newToken) {
                    $admId = $_GET['id'];
                    $data = $controller->getAdminDetails($admId);
                    if (true) {
                        $response = array(
                            'status' => true,
                            'message' => 'details fetched successfully',
                            'data' => $data,
                        );
                        echo json_encode($response);
                    }
                } else {
                    $response = array(
                        'status' => false,
                        'message' => 'kupon value must be wrong',
                    );
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
