<?php

namespace Api\Controllers;

require_once dirname(__DIR__, 1) . '/models/SettingModel.php';

use Models\Setting;
use Exception;

class SettingController
{

    public function updateSiteLogo($hospitalId, $data)
    {
        $files = [];
            $files[] = [
                'file_name' => $data['imagesName'],
                'temp_path' => $data['tempImgsName']
            ];

        $productData = ['files' => $files];
        return $this->UpdateSettingLogo($hospitalId, $productData);
    }

    private function UpdateSettingLogo($admId, $data)
    {
        try {
            $files = $data['files'];
            $settingModel = new Setting();
            $existingImage = $settingModel->getSiteLogo($admId);
            // print_r($existingImage);  die();
            foreach ($files as $file) {
                $imageName = $file['file_name'];
                $tempImgName = $file['temp_path'];

                if ($imageName && $tempImgName) {
                    if ($existingImage) {
                        $existingImagePath =dirname(dirname(__DIR__)) . "/assets/images/orgs" . DIRECTORY_SEPARATOR . $existingImage;
                        print_r($existingImagePath);  die();
                        if (file_exists($existingImagePath) && is_writable($existingImagePath)) {                          
                            unlink($existingImagePath);
                        // print_r($existingImagePath);  die();
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
                    // $imgFolder = SUP_ADM_IMG_DIR . DIRECTORY_SEPARATOR . $imageFile;
                    $imgFolder      = dirname(dirname(__DIR__)) . "/assets/images/orgs/" . $imageFile;
                    // print_r($imgFolder);  die();

                    if (!is_writable(SUP_ADM_IMG_DIR)) {
                        throw new \Exception("Directory not writable: " . SUP_ADM_IMG_DIR);
                    }

                    if (!rename($tempImgName, $imgFolder)) {
                        $errorMessage = "Failed to move file from '$tempImgName' to '$imgFolder'";
                        error_log($errorMessage);
                        throw new \Exception($errorMessage);
                    }

                    $image = addslashes($imageFile);
                    $status = 1;
                    $addImages = $settingModel->updatelogo($admId, $image);

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

            return true;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            error_log("UpdateProduct Error: " . $e->getMessage());

            return false;
        }
    }

    public function getSettingValues($hospitalId)
    {
        $productModel = new Setting();
        $data = $productModel->getSettinsData($hospitalId);
        return $data;
    }
}
