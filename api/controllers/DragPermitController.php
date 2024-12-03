<?php

namespace Api\Controllers;

require_once dirname(__DIR__, 1) . '/models/DragPermitModel.php';

use Models\Setting;
use Exception;
use Models\DragPermit;

class DragPermitController
{

    public function updateDragPermit($admId, $data)
{
    try {
        $dragpermitModel = new DragPermit();
        $existingData = $dragpermitModel->getImage($admId);
        $imageOne = $existingData['form_20'];
        $imageTwo = $existingData['form_21'];

        $imageNameOne = $data['form_20']['filename'] ?? null;
        $tempImgNameOne = $data['form_20']['temp_path'] ?? null;

        $imageNameTwo = $data['form_21']['filename'] ?? null;
        $tempImgNameTwo = $data['form_21']['temp_path'] ?? null;

        // Delete existing images if new images are provided
        if ($imageOne && $imageNameOne) {
            $existingImagePathOne = DRAG_PERMIT_IMG_DIR . DIRECTORY_SEPARATOR . $imageOne;
            // print_r($existingImagePathOne);  die;
            if (file_exists($existingImagePathOne) && is_writable($existingImagePathOne)) {
                // echo "hi"; die;
                unlink($existingImagePathOne);
            }
            // die;
        }

        if ($imageTwo && $imageNameTwo) {
            $existingImagePathTwo = DRAG_PERMIT_IMG_DIR . DIRECTORY_SEPARATOR . $imageTwo;
            if (file_exists($existingImagePathTwo) && is_writable($existingImagePathTwo)) {
                unlink($existingImagePathTwo);
            }
        }

        // Generate random string for unique file naming
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($k = 0; $k < 9; $k++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Process first image if available, else retain the old image name
        if ($imageNameOne && $tempImgNameOne) {
            $extensionOne = substr($imageNameOne, strrpos($imageNameOne, '.'));
            $imageFileNameOne = substr($imageNameOne, 0, strrpos($imageNameOne, '.'));
            $imageFileOne = $imageFileNameOne . '-' . $randomString . $extensionOne;
            $imgFolderOne = DRAG_PERMIT_IMG_DIR . DIRECTORY_SEPARATOR . $imageFileOne;

            if (!is_writable(DRAG_PERMIT_IMG_DIR)) {
                throw new \Exception("Directory not writable: " . DRAG_PERMIT_IMG_DIR);
            }
            if (!rename($tempImgNameOne, $imgFolderOne)) {
                throw new \Exception("Failed to move file from '$tempImgNameOne' to '$imgFolderOne'");
            }
            $data['image'] = addslashes($imageFileOne);
        } else {
            $data['image'] = addslashes($imageOne); // Retain the previous image
        }

        // Process second image if available, else retain the old image name
        if ($imageNameTwo && $tempImgNameTwo) {
            $extensionTwo = substr($imageNameTwo, strrpos($imageNameTwo, '.'));
            $imageFileNameTwo = substr($imageNameTwo, 0, strrpos($imageNameTwo, '.'));
            $imageFileTwo = $imageFileNameTwo . '-' . $randomString . $extensionTwo;
            $imgFolderTwo = DRAG_PERMIT_IMG_DIR . DIRECTORY_SEPARATOR . $imageFileTwo;

            if (!is_writable(DRAG_PERMIT_IMG_DIR)) {
                throw new \Exception("Directory not writable: " . DRAG_PERMIT_IMG_DIR);
            }
            if (!rename($tempImgNameTwo, $imgFolderTwo)) {
                throw new \Exception("Failed to move file from '$tempImgNameTwo' to '$imgFolderTwo'");
            }
            $data['imageTwo'] = addslashes($imageFileTwo);
        } else {
            $data['imageTwo'] = addslashes($imageTwo); // Retain the previous image
        }

        // Update database record
        $status = 1;
        $addImages = $dragpermitModel->updateDragPermit($admId, $data);

        if (!$addImages) {
            throw new \Exception("Failed to update image for product ID: $admId");
        }
    } catch (\Exception $e) {
        error_log($e->getMessage());
        throw $e;
    }
}



    // public function updateDragPermit($admId, $data)
    // {
    //     try {
    //         // print_r($data);  die;
    //         $dragpermitModel = new DragPermit();
    //         $existingData = $dragpermitModel->getImage($admId);
    //         $imageOne = $existingData['form_20'];
    //         $imageTwo = $existingData['form_21'];

    //         $imageNameOne = $data['form_20']['filename'] ?? null;
    //         $tempImgNameOne = $data['form_20']['temp_path'] ?? null;

    //         $imageNameTwo = $data['form_21']['filename'] ?? null;
    //         $tempImgNameTwo = $data['form_21']['temp_path'] ?? null;

    //         // Delete existing images if present
    //         if ($imageOne && $imageNameOne) {
    //             $existingImagePathOne = DRAG_PERMIT_IMG_DIR . DIRECTORY_SEPARATOR . $imageOne;
    //             if (file_exists($existingImagePathOne) && is_writable($existingImagePathOne)) {
    //                 unlink($existingImagePathOne);
    //             }
    //         }
    //         if ($imageTwo && $imageNameTwo) {
    //             $existingImagePathTwo = DRAG_PERMIT_IMG_DIR . DIRECTORY_SEPARATOR . $imageTwo;
    //             if (file_exists($existingImagePathTwo) && is_writable($existingImagePathTwo)) {
    //                 unlink($existingImagePathTwo);
    //             }
    //         }

    //         // Generate random string for unique file naming
    //         $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //         $randomString = '';
    //         for ($k = 0; $k < 9; $k++) {
    //             $randomString .= $characters[rand(0, strlen($characters) - 1)];
    //         }

    //         // Process first image if available
    //         if ($imageNameOne && $tempImgNameOne) {
    //             $extensionOne = substr($imageNameOne, strrpos($imageNameOne, '.'));
    //             $imageFileNameOne = substr($imageNameOne, 0, strrpos($imageNameOne, '.'));
    //             $imageFileOne = $imageFileNameOne . '-' . $randomString . $extensionOne;
    //             $imgFolderOne = DRAG_PERMIT_IMG_DIR . DIRECTORY_SEPARATOR . $imageFileOne;

    //             if (!is_writable(DRAG_PERMIT_IMG_DIR)) {
    //                 throw new \Exception("Directory not writable: " . DRAG_PERMIT_IMG_DIR);
    //             }
    //             if (!rename($tempImgNameOne, $imgFolderOne)) {
    //                 throw new \Exception("Failed to move file from '$tempImgNameOne' to '$imgFolderOne'");
    //             }
    //             $data['image'] = addslashes($imageFileOne);
    //         }else{
    //             $data['image'] = addslashes($imageOne);
    //         }

    //         // Process second image if available
    //         if ($imageNameTwo && $tempImgNameTwo) {
    //             $extensionTwo = substr($imageNameTwo, strrpos($imageNameTwo, '.'));
    //             $imageFileNameTwo = substr($imageNameTwo, 0, strrpos($imageNameTwo, '.'));
    //             $imageFileTwo = $imageFileNameTwo . '-' . $randomString . $extensionTwo;
    //             $imgFolderTwo = DRAG_PERMIT_IMG_DIR . DIRECTORY_SEPARATOR . $imageFileTwo;

    //             if (!is_writable(DRAG_PERMIT_IMG_DIR)) {
    //                 throw new \Exception("Directory not writable: " . DRAG_PERMIT_IMG_DIR);
    //             }
    //             if (!rename($tempImgNameTwo, $imgFolderTwo)) {
    //                 throw new \Exception("Failed to move file from '$tempImgNameTwo' to '$imgFolderTwo'");
    //             }
    //             $data['imageTwo'] = addslashes($imageFileTwo);
    //         }

    //         // Update database record
    //         $status = 1;
    //         $addImages = $dragpermitModel->updateDragPermit($admId, $data);

    //         if (!$addImages) {
    //             throw new \Exception("Failed to update image for product ID: $admId");
    //         }
    //     } catch (\Exception $e) {
    //         error_log($e->getMessage());
    //         throw $e;
    //     }
    // }

    // public function updateDragPermit($admId, $data)
    // {
    //     try {
    //         // print_r($data);  die;
    //         $dragpermitModel = new DragPermit();
    //         $existingData = $dragpermitModel->getImage($admId);

    //         $existingImageOne = $existingData['form_20'] ?? null;
    //         $existingImageTwo = $existingData['form_21'] ?? null;

    //         $tempImgNameOne = $_FILES['form_20']['tmp_name'] ?? null;
    //         $imageNameOne = $_FILES['form_20']['name'] ?? null;

    //         $tempImgNameTwo = $_FILES['form_21']['tmp_name'] ?? null;
    //         $imageNameTwo = $_FILES['form_21']['name'] ?? null;

    //         $gstin = $data['gstin'] ?? null;
    //         $pan = $data['pan'] ?? null;

    //         // Directory to store images
    //         $imageDir = DRAG_PERMIT_IMG_DIR;

    //         if (!is_dir($imageDir) || !is_writable($imageDir)) {
    //             throw new \Exception("Directory not writable: " . $imageDir);
    //         }

    //         // Generate unique random string for file names
    //         $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //         $randomString = substr(str_shuffle($characters), 0, 9);

    //         // Handle form_20 (First Image)
    //         $imageFileOne = null;
    //         if ($tempImgNameOne && $imageNameOne) {
    //             $extensionOne = pathinfo($imageNameOne, PATHINFO_EXTENSION);
    //             $baseNameOne = pathinfo($imageNameOne, PATHINFO_FILENAME);
    //             $imageFileOne = $baseNameOne . '-' . $randomString . '.' . $extensionOne;

    //             $destinationOne = $imageDir . DIRECTORY_SEPARATOR . $imageFileOne;
    //             if (!move_uploaded_file($tempImgNameOne, $destinationOne)) {
    //                 throw new \Exception("Failed to move file from '$tempImgNameOne' to '$destinationOne'");
    //             }

    //             // Delete old image
    //             if ($existingImageOne) {
    //                 $oldImagePathOne = $imageDir . DIRECTORY_SEPARATOR . $existingImageOne;
    //                 if (file_exists($oldImagePathOne)) {
    //                     unlink($oldImagePathOne);
    //                 }
    //             }
    //         }

    //         // Handle form_21 (Second Image)
    //         $imageFileTwo = null;
    //         if ($tempImgNameTwo && $imageNameTwo) {
    //             $extensionTwo = pathinfo($imageNameTwo, PATHINFO_EXTENSION);
    //             $baseNameTwo = pathinfo($imageNameTwo, PATHINFO_FILENAME);
    //             $imageFileTwo = $baseNameTwo . '-' . $randomString . '.' . $extensionTwo;

    //             $destinationTwo = $imageDir . DIRECTORY_SEPARATOR . $imageFileTwo;
    //             if (!move_uploaded_file($tempImgNameTwo, $destinationTwo)) {
    //                 throw new \Exception("Failed to move file from '$tempImgNameTwo' to '$destinationTwo'");
    //             }

    //             // Delete old image
    //             if ($existingImageTwo) {
    //                 $oldImagePathTwo = $imageDir . DIRECTORY_SEPARATOR . $existingImageTwo;
    //                 if (file_exists($oldImagePathTwo)) {
    //                     unlink($oldImagePathTwo);
    //                 }
    //             }
    //         }

    //         // Prepare data for database update
    //         $updateData = [
    //             'form_20' => $imageFileOne,
    //             'form_21' => $imageFileTwo,
    //             'gstin' => $gstin,
    //             'pan' => $pan
    //         ];

    //         // Update database record
    //         $updateStatus = $dragpermitModel->updateDragPermit($admId, $updateData);
    //         if (!$updateStatus) {
    //             throw new \Exception("Failed to update drag permit data for admin ID: $admId");
    //         }

    //         // Return success response
    //         return [
    //             'status' => true,
    //             'message' => 'Drag permit updated successfully'
    //         ];
    //     } catch (\Exception $e) {
    //         error_log($e->getMessage());
    //         return [
    //             'status' => false,
    //             'message' => $e->getMessage()
    //         ];
    //     }
    // }



    public function getDrugPermitDetails($adminId)
    {
        $dragpermitModel = new DragPermit();
        $data = $dragpermitModel->getDrugPermitDetails($adminId);
        return $data;
    }
}
