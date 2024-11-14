<?php

require_once dirname(__DIR__) . '/config/constant.php';
// require_once 'config/constant.php';
require_once 'controllers/ProductController.php';

use Api\Controllers\ProductController;

require_once "./headers.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode('/', $uri);

$method = $_SERVER['REQUEST_METHOD'];

// if ($third_segment === 'api' && $forth_segment == 'products.php') {
if ($uri[$uriPosition] === 'api' && str_contains($uri[$uriContains], 'products.php')) {

    $controller = new ProductController();
    switch ($method) {
        case 'POST':
            if ($_POST['name'] == 'add-image') {
                $imagesName         = $_FILES['img-files']['name'];
                $data['imagesName']         = $imagesName;

                $data['tempImgsName']       = $_FILES['img-files']['tmp_name'];
                $tempImgsName = $_FILES['img-files']['tmp_name'];
                $imageArrayCaount = count($imagesName);
                $data['tempImageArrayCaount'] = count($tempImgsName);

                if ($imageArrayCaount >= 1) {
                    if ($imagesName[0] != '') {
                        $imageAdded = true;
                    } else {
                        $imageAdded = false;
                    }
                } else {
                    $imageAdded = false;
                }

                $controller->addProductImage($data);
                if (true) {
                    $response = array(
                        'status' => true,
                        'message' => 'Image added successfully',
                    );
                    echo json_encode($response);
                }
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'Key value must be wrong',
                );
                echo json_encode($response);
            }
            break;

            case 'PUT':
                $contentType = $_SERVER["CONTENT_TYPE"] ?? '';
                
                // Check if content type is multipart/form-data
                if (strpos($contentType, 'multipart/form-data') !== false) {
                    // Get the boundary from content type
                    preg_match('/boundary=(.*)$/', $contentType, $matches);
                    $boundary = $matches[1];
                    
                    // Read the raw input
                    $rawData = file_get_contents('php://input');
                    
                    // Split the data by boundary
                    $parts = explode('--' . $boundary, $rawData);
                    $data = [];
                    
                    foreach ($parts as $part) {
                        if (strpos($part, 'Content-Disposition: form-data;') !== false) {
                            // If this part contains file data, parse it accordingly
                            if (preg_match('/name="([^"]*)"; filename="([^"]*)"/', $part, $matches)) {
                                // It's a file upload field
                                $fileField = $matches[1];
                                $fileName = $matches[2];
                                
                                // Extract the file content
                                $fileContent = substr($part, strpos($part, "\r\n\r\n") + 4);
                                $fileContent = rtrim($fileContent, "\r\n");
                                
                                // Save file to a temporary location or handle it as needed
                                $tempFilePath = sys_get_temp_dir() . '/' . $fileName;
                                file_put_contents($tempFilePath, $fileContent);
                                
                                // Store file data in $data array
                                $data['files'][] = [
                                    'name' => $fileField,
                                    'file_name' => $fileName,
                                    'temp_path' => $tempFilePath,
                                ];
                            } elseif (preg_match('/name="([^"]*)"/', $part, $matches)) {
                                // Regular form field
                                $fieldName = $matches[1];
                                $fieldValue = substr($part, strpos($part, "\r\n\r\n") + 4);
                                $fieldValue = rtrim($fieldValue, "\r\n");
                                
                                $data[$fieldName] = $fieldValue;
                            }
                        }
                    }
            // print_r($data['files']);  die();
                    // Check the 'name' value to identify if this is an update action
                    if ($data['name'] == 'update-image') {
                        if (!empty($data['files'])) {
                            $id = $data['id'];
                            // Call the controller's update method with parsed data
                            $controller->UpdateProduct($id, $data);
                            
                            // Success response
                            $response = array(
                                'status' => true,
                                'message' => 'Image updated successfully',
                            );
                            echo json_encode($response);
                        } else {
                            // No image found for update
                            $response = array(
                                'status' => false,
                                'message' => 'No image to update',
                            );
                            echo json_encode($response);
                        }
                    } else {
                        // Invalid action key
                        $response = array(
                            'status' => false,
                            'message' => 'Invalid key value',
                        );
                        echo json_encode($response);
                    }
                } else {
                    // Content type error
                    $response = array(
                        'status' => false,
                        'message' => 'Content type is not multipart/form-data',
                    );
                    echo json_encode($response);
                }
                break;
            
            
            }
} else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["message" => "Endpoint not found"]);
}
