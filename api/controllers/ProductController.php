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

    public function updateProductImage($prodId, $data)
    {
        // Prepare the image data array for processing in `UpdateProduct`
        $files = [];
        for ($i = 0; $i < count($data['imagesName']); $i++) {
            $files[] = [
                'file_name' => $data['imagesName'][$i],
                'temp_path' => $data['tempImgsName'][$i]
            ];
        }

        $productData = ['files' => $files];

        // Call the UpdateProduct function
        return $this->UpdateProduct($prodId, $productData);
    }

    private function UpdateProduct($prodId, $data)
{
    try {
        $files = $data['files'];
        $productModel = new Product();
        $admId = 111;

        foreach ($files as $file) {
            $imageName = $file['file_name'];
            $tempImgName = $file['temp_path'];

            if ($imageName && $tempImgName) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';
                for ($k = 0; $k < 9; $k++) {
                    $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }

                $extension = substr($imageName, strrpos($imageName, '.'));
                $imageFileName = substr($imageName, 0, strrpos($imageName, '.'));

                $imageFile = $imageFileName . '-' . $randomString . $extension;
                $imgFolder = PRODUCT_IMG_DIR . DIRECTORY_SEPARATOR . $imageFile;

                if (!is_writable(PRODUCT_IMG_DIR)) {
                    throw new \Exception("Directory not writable: " . PRODUCT_IMG_DIR);
                }

                // Use rename() since we created the file manually in the temporary path
                if (!rename($tempImgName, $imgFolder)) {
                    $errorMessage = "Failed to move file from '$tempImgName' to '$imgFolder'";
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
        echo "Error: " . $e->getMessage();
        error_log("UpdateProduct Error: " . $e->getMessage());

        return false;
    }
}


public function getProductDetails($ProductId)
    {
        $productModel = new Product();
        // Prepare the image data array for processing in `UpdateProduct`
        $data = $productModel->getDetails($ProductId);
        // print_r($data);  die();
        return $data;
    }

}
