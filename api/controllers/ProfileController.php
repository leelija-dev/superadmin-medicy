<?php

namespace Api\Controllers;

require_once dirname(__DIR__, 1) . '/models/ProfileModel.php';

use Models\Profile;
use Exception;

class ProfileController
{

    public function updateProfileImage($prodId, $data)
    {
        $files = [];
            $files[] = [
                'file_name' => $data['imagesName'],
                'temp_path' => $data['tempImgsName']
            ];

        $productData = ['files' => $files];
        $res = $this->UpdateProfilePic($prodId, $productData);
        
        return $res;
    }

    private function UpdateProfilePic($admId, $data)
    {
        try {
            $files = $data['files'];
            $profileModel = new Profile();
            $existingImage = $profileModel->getProfileImage($admId);
            foreach ($files as $file) {
                $imageName = $file['file_name'];
                $tempImgName = $file['temp_path'];

                if ($imageName && $tempImgName) {
                    if ($existingImage) {
                        $existingImagePath = PROFILE_IMG_DIR . DIRECTORY_SEPARATOR . $existingImage;
                        // print_r($existingImagePath);  die();

                        if (file_exists($existingImagePath) && is_writable($existingImagePath)) {
                            unlink($existingImagePath);
                            // echo "hi"; die;
                        }
                    }

                if ($imageName && $tempImgName) {
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $randomString = '';
                    for ($k = 0; $k < 9; $k++) {
                        $randomString .= $characters[rand(0, strlen($characters) - 1)];
                    }

                    $extension = substr($imageName, strrpos($imageName, '.'));
                    $imageFileName = substr($imageName, 0, strrpos($imageName, '.'));

                    $imageFile = $imageFileName . '-' . $randomString . $extension;
                    $imgFolder = PROFILE_IMG_DIR . DIRECTORY_SEPARATOR . $imageFile;

                    if (!is_writable(PROFILE_IMG_DIR)) {
                        throw new \Exception("Directory not writable: " . PROFILE_IMG_DIR);
                    }

                    if (!rename($tempImgName, $imgFolder)) {
                        $errorMessage = "Failed to move file from '$tempImgName' to '$imgFolder'";
                        error_log($errorMessage);
                        throw new \Exception($errorMessage);
                    }

                    $image = addslashes($imageFile);
                    $status = 1;
                    $addImages = $profileModel->updateprofileImage($admId, $image);
                    if (!$addImages) {
                        throw new \Exception("Failed to add image for product ID: $admId");
                    }
                } else {
                    throw new \Exception("Image data missing for product ID: $admId");
                }
            } else {
                throw new \Exception("Image data missing for product ID: $admId");
            }
            }

            return $addImages;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            error_log("UpdateProduct Error: " . $e->getMessage());

            return false;
        }
    }

    public function getAdminDetails($ProductId)
    {
        // print_r($ProductId);  die();
        $productModel = new Profile();
        $data = $productModel->getAdminDetails($ProductId);
        return $data;
    }

}
