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

        // case 'PUT':
        //     if ($_POST['name'] == 'add-image') {
        //         $imagesName         = $_FILES['img-files']['name'];
        //         $data['imagesName']         = $imagesName;

        //         $data['tempImgsName']       = $_FILES['img-files']['tmp_name'];
        //         $tempImgsName = $_FILES['img-files']['tmp_name'];
        //         $imageArrayCaount = count($imagesName);
        //         $data['tempImageArrayCaount'] = count($tempImgsName);

        //         if ($imageArrayCaount >= 1) {
        //             if ($imagesName[0] != '') {
        //                 $imageAdded = true;
        //             } else {
        //                 $imageAdded = false;
        //             }
        //         } else {
        //             $imageAdded = false;
        //         }

        //         $controller->addProductImage($data);
        //         if (true) {
        //             $response = array(
        //                 'status' => true,
        //                 'message' => 'Image added successfully',
        //             );
        //             echo json_encode($response);
        //         }
        //     } else {
        //         $response = array(
        //             'status' => false,
        //             'message' => 'Key value must be wrong',
        //         );
        //         echo json_encode($response);
        //     }
            break;
    }
} else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["message" => "Endpoint not found"]);
}
