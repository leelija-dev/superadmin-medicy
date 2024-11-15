<?php

require_once dirname(__DIR__) . '/config/constant.php';
// require_once 'config/constant.php';
require_once 'controllers/SettingController.php';

use Api\Controllers\SettingController;

require_once "./headers.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode('/', $uri);

$method = $_SERVER['REQUEST_METHOD'];

// if ($third_segment === 'api' && $forth_segment == 'products.php') {
if ($uri[$uriPosition] === 'api' && str_contains($uri[$uriContains], 'setting.php')) {

    $controller = new SettingController();
    switch ($method) {
                case 'PUT':
                    $id = $_GET['id'];
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

                                    // Get the file content
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
                            
                            // $id = 8;
                            $controller->updateSiteLogo($id, $data);
                            $response = array(
                                'status' => true,
                                'message' => 'Logo Updated successfully',
                            );
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
            }
    
} else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["message" => "Endpoint not found"]);
}
