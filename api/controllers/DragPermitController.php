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

            // Delete existing images if present
            if ($imageOne) {
                $existingImagePathOne = DRAG_PERMIT_IMG_DIR . DIRECTORY_SEPARATOR . $imageOne;
                if (file_exists($existingImagePathOne) && is_writable($existingImagePathOne)) {
                    unlink($existingImagePathOne);
                }
            }
            if ($imageTwo) {
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

            // Process first image if available
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
            }

            // Process second image if available
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


    public function getDrugPermitDetails($adminId)
    {
        $dragpermitModel = new DragPermit();
        $data = $dragpermitModel->getDrugPermitDetails($adminId);
        return $data;
    }
}
