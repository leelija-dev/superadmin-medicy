<?php

namespace Api\Controllers;

require_once dirname(__DIR__, 1) . '/models/ProductModel.php';

use Models\Product;
use Exception;

class ProductController
{

    public function addProductImage($data)
    {
        $productModel = new Product();
        $imagesName         = $data['imagesName']; //$_FILES['img-files']['name'];
        $tempImgsName       = $data['tempImgsName']; // $_FILES['img-files']['tmp_name'];

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

    // public function updateProductImage($prodId, $data)
    // {
    //     $productModel = new Product();
        
    //     $imagesName         = $data['imagesName']; //$_FILES['img-files']['name'];
    //     $tempImgsName       = $data['tempImgsName']; // $_FILES['img-files']['tmp_name'];

    //     $imageArrayCaount = count($imagesName);
    //     $tempImageArrayCaount = count($tempImgsName);
    //     $addedBy = 'self';
    //     $newProductId = 10;

    //     $imageDataTuple = json_encode(['imageNmArray' => $imagesName, 'tempImageNmArray' => $tempImgsName, 'imgArrayCount' => $imageArrayCaount, 'tempImgArrayCount' => $tempImageArrayCaount, 'addedBy' => $addedBy, 'adminId' => $addedBy, 'productId' => $newProductId]); // createing image data tupel ------

    //     $imageDataTuple = json_decode($imageDataTuple);
    //     $imagesName = $imageDataTuple->imageNmArray;
    //     $tempImgsName = $imageDataTuple->tempImageNmArray;

    //     $imageArrayCount = $imageDataTuple->imgArrayCount;
    //     $tempImageArrayCount = $imageDataTuple->tempImgArrayCount;

    //     for ($j = 0; $j < $imageArrayCount && $j < $tempImageArrayCount; $j++) {
    //         ////////// RANDOM 12DIGIT STRING GENERATOR FOR IMAGE NAME PREFIX \\\\\\\\\\\\\

    //         $imgStatus = 1;

    //         $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //         $randomString = '';
    //         for ($i = 0; $i < 9; $i++) {
    //             $randomString .= $characters[rand(0, strlen($characters) - 1)];
    //         }

    //         $randomString = $randomString;

    //         ////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\
    //         //===== Main Image 
    //         $imageName        = $imagesName[$j];
    //         $tempImageName   = $tempImgsName[$j];

    //         $extension = substr($imageName, -4);
    //         $imageNameWithoutExtension = substr($imageName, 0, -4);

    //         $image         = $imageNameWithoutExtension . '-' . $randomString . $extension;
    //         $imgFolder     = PROD_IMG . $image;

    //         move_uploaded_file($tempImageName, $imgFolder);
    //         $image         = addslashes($image);
    //         // print_r($image);  die();
    //         $addImages = $productModel->addImagesBySupAdmin($imageDataTuple->productId, $image, $imgStatus, $imageDataTuple->addedBy, NOW, $imageDataTuple->adminId);
    //     }

    //     return $addImages;
    // }
    // function UpdateProduct($prodId, $data)
    // {
    //     $files = $data['files'];
    //     // print_r($files[0]);  die();

    //         $productModel = new Product();
    //         // $imageData = $data['imageData'];
    //         $admId = 111;
    //         // $imageData = json_decode($imageData);

    //         // $imageName = $imageData->imageNameArray;
    //         // $tempImgName = $imageData->tempImgNmArray;
    //         $imageName         = $files[0]['file_name']; //$_FILES['img-files']['name'];
    //         $tempImgName       = $files[0]['temp_path'] ; 

    //         for ($i = 0, $j = 0; $i < count($imageName) && $j < count($tempImgName); $i++, $j++) {
    //             $imgStatus = 0;
    //             $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //             $randomString = '';
    //             for ($k = 0; $k < 9; $k++) {
    //                 $randomString .= $characters[rand(0, strlen($characters) - 1)];
    //             }

    //             $image          = $imageName[$i];
    //             $tempImage      = $tempImgName[$j];
    //             $extention      = substr($image, -4);
    //             $imageFileName  = substr($image, 0, -4);


    //             $imageFile  =   $imageFileName . '-' . $randomString . $extention;
    //             $imgFolder  = PROD_IMG_DIR . $imageFile;
    //             move_uploaded_file($tempImage, $imgFolder);
    //             $image      = addslashes($imageFile);

    //             $status = 1;

    //             $addImages = $productModel->addImagesBySupAdmin($prodId, $image, $status, $admId, NOW, $admId);

    //             if (!$addImages) {
    //                 throw new Exception("Failed to add image for product ID: $prodId");
    //             }
    //         }
    //         return true;

    // }

    // function UpdateProduct($prodId, $data)
    // {
    //     $files = $data['files'];

    //     $productModel = new Product();
    //     $admId = 111;

    //     // Handle multiple files if there are more than one
    //     foreach ($files as $file) {
    //         $imageName = $file['file_name'];   // The name of the uploaded file
    //         $tempImgName = $file['temp_path']; // The temporary file path

    //         // Ensure both image name and temp path are available
    //         if ($imageName && $tempImgName) {
    //             // Generate a unique name for the file
    //             $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //             $randomString = '';
    //             for ($k = 0; $k < 9; $k++) {
    //                 $randomString .= $characters[rand(0, strlen($characters) - 1)];
    //             }

    //             // Split the file extension and base name
    //             $extention = substr($imageName, strrpos($imageName, '.')); // Get the file extension
    //             $imageFileName = substr($imageName, 0, strrpos($imageName, '.')); // Get the base file name

    //             // Construct the new image name
    //             $imageFile = $imageFileName . '-' . $randomString . $extention;
    //             $imgFolder = PROD_IMG_DIR . $imageFile;
    //             // print_r($imgFolder);  die();
    //             // Move the uploaded file to the desired location
    //             if (move_uploaded_file($tempImgName, $imgFolder)) {
    //                 echo "hi";
    //                 die();
    //                 $image = addslashes($imageFile); // Sanitize for storage

    //                 $status = 1;
    //                 $addImages = $productModel->addImagesBySupAdmin($prodId, $image, $status, $admId, NOW, $admId);

    //                 // Check if the image was successfully added to the database
    //                 if (!$addImages) {
    //                     // throw new Exception("Failed to add image for product ID: $prodId");
    //                     echo "error i";
    //                 }
    //             } else {
    //                 throw new Exception("Failed to move uploaded file for product ID: $prodId");
    //                 // echo "error im";

    //             }
    //         } else {
    //             throw new Exception("Image data missing for product ID: $prodId");
    //             // echo "error ig";

    //         }
    //     }

    //     return true;
    // }

    function UpdateProduct($prodId, $data)
{
    try {
        $files = $data['files'];
        $productModel = new Product();
        $admId = 111;

        foreach ($files as $file) {
            $imageName = $file['file_name'];
            // print_r($imageName); die();
            $tempImgName = $file['temp_path'];
// print_r($tempImgName); die;
            if ($imageName && $tempImgName) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';
                for ($k = 0; $k < 9; $k++) {
                    $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }

                $extention = substr($imageName, strrpos($imageName, '.'));
                $imageFileName = substr($imageName, 0, strrpos($imageName, '.'));

                $imageFile = $imageFileName . '-' . $randomString . $extention;
                $imgFolder = PROD_IMG_DIR . $imageFile;

                // Check if the target directory is writable
                if (!is_writable(PROD_IMG_DIR)) {
                    throw new \Exception("Directory not writable: " . PROD_IMG_DIR);
                }

                // Attempt to move the uploaded file
                if (!move_uploaded_file($tempImgName, $imgFolder)) {
                    // print_r($imgFolder);  die();
                    // Log specific reasons for failure
                    $errorMessage = "Failed to move uploaded file '$tempImgName' to '$imgFolder'.";
                    error_log($errorMessage);
                    throw new \Exception($errorMessage);
                }

                $image = addslashes($imageFile);
                $status = 1;
                $addImages = $productModel->addImagesBySupAdmin($prodId, $image, $status, $admId, NOW, $admId);

                if (!$addImages) {
                    throw new \Exception("Failed to add image for product ID: $prodId");
                }
            } else {
                throw new \Exception("Image data missing for product ID: $prodId");
            }
        }

        return true;

    } catch (\Exception $e) {
        // Log and display the error message
        echo "Error: " . $e->getMessage();
        error_log("UpdateProduct Error: " . $e->getMessage());

        return false; // Indicate failure
    }
}

}
