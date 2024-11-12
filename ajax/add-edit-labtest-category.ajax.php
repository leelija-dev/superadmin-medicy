<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not


require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'utilityImage.class.php';


$Pathology = new Pathology;
$ImageUtil = new ImageUtil;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['flag'] === '1') {
        $categoryName = $_POST['lab-cat-name'];
        $categoryDescription = $_POST['lab-cat-dsc'];
        $updatedFileName = '';
    
        // Check if the file upload exists and is valid
        if (isset($_FILES['lab-cat-img']) && $_FILES['lab-cat-img']['error'] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['lab-cat-img']['name'];
            $tmpFileName = $_FILES['lab-cat-img']['tmp_name'];
    
            // Generate a 9-character random string
            $randomString = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 9);
    
            // Generate a timestamp with microseconds
            $microtime = microtime(true);
            $formattedDateTime = date('YmdHis', $microtime) . sprintf('%06d', ($microtime - floor($microtime)) * 1000000);
    
            // Extract file extension
            $extention = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileNewName = $formattedDateTime . '-' . $randomString . '.' . $extention;
            $fileFolder = LABTEST_IMG_DIR . $fileNewName;
    
            // Move uploaded file
            if (move_uploaded_file($tmpFileName, $fileFolder)) {
                $updatedFileName = addslashes($fileNewName);
            }
        }
    
        // Add lab category data
        $addLabCategoryData = $Pathology->addTestCategory($categoryName, $categoryDescription, $updatedFileName, 1, NOW);
        $addResponse = json_decode($addLabCategoryData);
    
        // Output response
        print_r($addLabCategoryData);
    }
    





    if ($_POST['flag'] === '2') {
        $id = url_dec($_POST['lab-cat-id']);
        $categoryName = $_POST['lab-cat-name'];
        $categoryDescription = $_POST['lab-cat-dsc'];
    
        // Initialize updated file name
        $updatedFileName = '';
    
        // Check if image data is already provided
        if (isset($_POST['imageData'])) {
            $updatedFileName = $_POST['imageData'];
        } elseif (isset($_FILES['lab-cat-img']) && $_FILES['lab-cat-img']['error'] === UPLOAD_ERR_OK) {
            // Handle image upload
            $fileName = $_FILES['lab-cat-img']['name'];
            $tmpFileName = $_FILES['lab-cat-img']['tmp_name'];

            $imageDirPath = LABTEST_IMG_DIR;
            $databaseTable = 'test_category';
            $imgColumn = 'image';
            $identifierColumn = 'id';
            $identifierValue = $id;

            $imgUpload = json_decode($ImageUtil->uploadAndDeleteImage($fileName, $tmpFileName, $imageDirPath, $databaseTable, $imgColumn, $identifierColumn, $identifierValue));
    
            if($imgUpload->status){
                $updatedFileName = $imgUpload->image_name;
            }else{
                $updatedFileName = '';
            }
        }
    
        // Update lab category data
        $updateLabCatData = $Pathology->updateLabTypes($id, $categoryName, $categoryDescription, $updatedFileName);
        $updateResponse = json_decode($updateLabCatData);
    
        // Print response
        print_r($updateLabCatData);
    }
    

}
