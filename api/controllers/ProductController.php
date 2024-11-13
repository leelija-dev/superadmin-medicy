<?php

namespace Api\Controllers;

require_once dirname(__DIR__, 1) . '/models/ProductModel.php';

use Models\Product;

class ProductController {

    public function addProductImage($data){
        $productModel = new Product();
        $imagesName         = $data['imagesName']; //$_FILES['img-files']['name'];
        $tempImgsName       = $data['tempImgsName'] ; // $_FILES['img-files']['tmp_name'];

        $imageArrayCaount = count($imagesName);
        $tempImageArrayCaount = count($tempImgsName);
        $addedBy = 'self';
        $newProductId = 10;

        $imageDataTuple = json_encode(['imageNmArray' => $imagesName, 'tempImageNmArray' => $tempImgsName, 'imgArrayCount' => $imageArrayCaount, 'tempImgArrayCount' => $tempImageArrayCaount, 'addedBy' => $addedBy, 'adminId' => $addedBy, 'productId' => $newProductId]); // createing image data tupel ------

                $imageDataTuple = json_decode($imageDataTuple);
                $imagesName = $imageDataTuple->imageNmArray;
                $tempImgsName = $imageDataTuple->tempImageNmArray;

                $imageArrayCount = $imageDataTuple->imgArrayCount;
                $tempImageArrayCount = $imageDataTuple->tempImgArrayCount;

                for ($j = 0; $j < $imageArrayCount && $j < $tempImageArrayCount; $j++) {
                    ////////// RANDOM 12DIGIT STRING GENERATOR FOR IMAGE NAME PREFIX \\\\\\\\\\\\\

                    $imgStatus = 1;

                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $randomString = '';
                    for ($i = 0; $i < 9; $i++) {
                        $randomString .= $characters[rand(0, strlen($characters) - 1)];
                    }

                    $randomString = $randomString;

                    ////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                    //===== Main Image 
                    $imageName        = $imagesName[$j];
                    $tempImageName   = $tempImgsName[$j];

                    $extension = substr($imageName, -4);
                    $imageNameWithoutExtension = substr($imageName, 0, -4);

                    $image         = $imageNameWithoutExtension . '-' . $randomString . $extension;
                    $imgFolder     = PROD_IMG . $image;

                    move_uploaded_file($tempImageName, $imgFolder);
                    $image         = addslashes($image);
// print_r($image);  die();
                    $addImages = $productModel->addImagesBySupAdmin($imageDataTuple->productId, $image, $imgStatus, $imageDataTuple->addedBy, NOW, $imageDataTuple->adminId);
                }

                return $addImages;
    }
}
