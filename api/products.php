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
                $define_token = 'products_details';
                if ($_POST['token'] == $define_token) {
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
            // Parse `PUT` request body for `multipart/form-data`
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            // print_r($contentType);  die();
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
                        preg_match('/token="([^"]*)"/', $part, $matches);
                        $token = $matches[1] ?? '';

                        // Check if the part is an image file
                        if (strpos($part, 'filename="') !== false) {
                            preg_match('/filename="([^"]*)"/', $part, $fileMatches);
                            $filename = $fileMatches[1] ?? '';
                            // preg_match('/Content-Type: ([^"]*)/', $part, $typeMatches);
                            // $fileType = $typeMatches[1] ?? '';

                            // Get the file content
                            $fileContent = trim(explode("\r\n\r\n", $part)[1]);
                            $tempPath = sys_get_temp_dir() . '/' . $filename;
                            file_put_contents($tempPath, $fileContent);

                            // Add image data to the $data array
                            $data['imagesName'][] = $filename;
                            $data['tempImgsName'][] = $tempPath;
                        } else {
                            // Regular form data
                            $value = trim(explode("\r\n\r\n", $part)[1]);
                            $data[$name] = $value;
                        
                        }
                    }
                }

                if (!empty($data['imagesName'])) {
                    $defined_token = 'prod_details';
                    $id = $data['id'];
                    $adm_id = $data['adminId'];
                    $token = $data['token'];
                    // print_r($data);  die;
                    // print_r($adm_id);  die();
                    if($defined_token == $token){
                    $controller->updateProductImage($id, $data);
                    $response = array(
                        'status' => true,
                        'message' => 'Image Update successfully',
                    );
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
            $defined_token = 'product_details';
            if ($_GET['name'] == 'get-image') {
                $token = $_GET['token'];
                if ($defined_token == $token) {
                    $productId = $_GET['id'];
                    $data = $controller->getProductDetails($productId);
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
