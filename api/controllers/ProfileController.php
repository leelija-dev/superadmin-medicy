<?php

namespace Api\Controllers;

require_once dirname(__DIR__, 1) . '/models/ProfileModel.php';

use Models\Profile;
use Exception;

class ProfileController
{

    public function updateProfileImage($prodId, $data)
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
        return $this->UpdateProfilePic($prodId, $productData);
    }

    private function UpdateProfilePic($prodId, $data)
    {
        try {
            $files = $data['files'];
            $profileModel = new Profile();
            // $admId = 111;
            $existingImage = $profileModel->getProfileImage($prodId);
            foreach ($files as $file) {
                $imageName = $file['file_name'];
                $tempImgName = $file['temp_path'];

                if ($imageName && $tempImgName) {
                    // Delete existing image if it exists
                    if ($existingImage) {
                        $existingImagePath = SUP_ADM_IMG_DIR . DIRECTORY_SEPARATOR . $existingImage;
                        if (file_exists($existingImagePath) && is_writable($existingImagePath)) {
                            unlink($existingImagePath);
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
                    $imgFolder = SUP_ADM_IMG_DIR . DIRECTORY_SEPARATOR . $imageFile;

                    if (!is_writable(SUP_ADM_IMG_DIR)) {
                        throw new \Exception("Directory not writable: " . SUP_ADM_IMG_DIR);
                    }

                    // Use rename() since we created the file manually in the temporary path
                    if (!rename($tempImgName, $imgFolder)) {
                        $errorMessage = "Failed to move file from '$tempImgName' to '$imgFolder'";
                        error_log($errorMessage);
                        throw new \Exception($errorMessage);
                    }

                    $image = addslashes($imageFile);
                    $status = 1;
                    $addImages = $profileModel->updateprofileImage($prodId, $image);

                    if (!$addImages) {
                        throw new \Exception("Failed to add image for product ID: $prodId");
                    }
                } else {
                    throw new \Exception("Image data missing for product ID: $prodId");
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
}
