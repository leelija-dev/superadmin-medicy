<?php
print_r($_POST);
print_r($_FILES);


if (isset($_FILES['csvFile'])) {

    // $file = $_FILES['csvFile'];
    // $fileName = $file['name'];
    // $fileTmpName = $file['tmp_name'];
    // $fileSize = $file['size'];
    // $fileError = $file['error'];
    // $fileType = $file['type'];

    // $fileExt = explode('.', $fileName);
    // $fileActualExt = strtolower(end($fileExt));

    // $allowed = array('csv');

    // if(in_array($fileActualExt, $allowed)){
    //     if($fileError === 0){
    //         if($fileSize < 1000000){
    //             $fileNameNew = uniqid('', true). '.'. $fileActualExt;
    //             $fileDestination = 'uploads/'. $fileNameNew;
    //             move_uploaded_file($fileTmpName, $fileDestination);
    //             echo "File uploaded successfully.";
    //         } else {
    //             echo "File size is too large.";
    //         }
    //     } else {
    //         echo "There was an error uploading the file.";
    //     }
    // } else {
    //     echo "You cannot upload files of this type.";
    // }
}
?>