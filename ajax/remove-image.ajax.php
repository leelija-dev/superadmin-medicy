<?php
require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
require_once SUP_ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';

require_once CLASS_DIR.'productsImages.class.php';

if (isset($_POST['imageID'])) {

$imageId = $_POST['imageID'];
$ProductImages = new ProductImages;

$showImage = $ProductImages->showImageByImgId($imageId);
$showImage = json_decode($showImage);

if ($showImage->status == '1' && !empty($showImage->data)) {
    foreach ($showImage->data as $image) {
        $imageName = $image->image;
        echo $imageName;
    }

$deleteImg = $ProductImages->deleteImage($imageId) ;

if($deleteImg){

    $filePath = ROOT_DIR . '/images/product-image/' . $imageName;
    
    if (file_exists($filePath)) {
        unlink($filePath);

        echo "Selected image deleted";
    } else {
        echo "File not found: $filePath";
    }
}else{
    echo "selected image not delete";
}
}else {
    echo 'No images found.';
}
}else {
    echo 'Image ID not set in the POST request.';
}
?> 

