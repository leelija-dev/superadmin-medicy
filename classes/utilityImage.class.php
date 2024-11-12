<?php
class ImageUtil {
    use DatabaseConnection;

    // public function uploadAndDeleteImage($imageName, $imageTempName, $imageDirPath, $databaseTable, $imgColumn, $identifierColumn, $identifierValue) {

    //     $previousImageName = $this->getExistingImagePath($databaseTable, $imgColumn, $identifierColumn, $identifierValue);
        
    //     // Get file extension
    //     $fileExtension = pathinfo($imageName, PATHINFO_EXTENSION);

    //     // Generate random digits (4-6 digits)
    //     $randomDigits = mt_rand(1000, 999999);

    //     // New filename with random digits added before the extension
    //     $newImageName = basename($imageName, "." . $fileExtension) . "-" . $randomDigits . "." . $fileExtension;

    //     //Upload The Image Into Server
    //     $imgFullPath = $imageDirPath . $newImageName;
    //     $result = move_uploaded_file($imageTempName, $imgFullPath);

    //     if ($result) {

    //         // Update the database with the new image path
    //         $query = "UPDATE $databaseTable SET $imgColumn = ? WHERE $identifierColumn = ?";
    //         $stmt = $this->conn->prepare($query);
    //         $stmt->bind_param("ss", $newImageName, $identifierValue);
    //         $stmt->execute();
    //         $stmt->close();
    //         $this->conn->close();

    //         // Delete previous image from directory
    //         if (file_exists($imageDirPath.$previousImageName)) {
    //             if (is_file($imageDirPath . $previousImageName)) {
    //                 unlink($imageDirPath.$previousImageName);
    //             }
    //         }
    //         return json_encode(['status'=> '1', 'message'=> 'success', 'data'=> $newImageName]);
    //     }

    // }

    public function uploadAndDeleteImage($imageName, $imageTempName, $imageDirPath, $databaseTable, $imgColumn, $identifierColumn, $identifierValue) {
        try {
            // Get existing image name
            $previousImageName = $this->getExistingImagePath($databaseTable, $imgColumn, $identifierColumn, $identifierValue);
            
            // Get file extension
            $fileExtension = pathinfo($imageName, PATHINFO_EXTENSION);
    
            // Generate random digits (4-6 digits)
            $randomDigits = mt_rand(1000, 999999);
    
            // New filename with random digits added before the extension
            $newImageName = basename($imageName, "." . $fileExtension) . "-" . $randomDigits . "." . $fileExtension;
    
            //Upload The Image Into Server
            $imgFullPath = $imageDirPath . $newImageName;
            $result = move_uploaded_file($imageTempName, $imgFullPath);
    
            if ($result) {
                // Update the database with the new image path
                $query = "UPDATE $databaseTable SET $imgColumn = ? WHERE $identifierColumn = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("ss", $newImageName, $identifierValue);
                $stmt->execute();
                $stmt->close();
                $this->conn->close();
    
                // Delete previous image from directory
                if (file_exists($imageDirPath.$previousImageName)) {
                    if (is_file($imageDirPath . $previousImageName)) {
                        unlink($imageDirPath.$previousImageName);
                    }
                }
                return json_encode(['status'=> '1', 'message'=> 'success', 'image_name'=> $newImageName]);
            } else {
                throw new Exception('Error moving uploaded file.');
            }
        } catch (Exception $e) {
            return json_encode(['status'=> '0', 'message'=> $e->getMessage()]);
        }
    }

    
    private function getExistingImagePath($databaseTable, $imgColumn, $identifierColumn, $identifierValue) {

        $query = "SELECT $imgColumn FROM $databaseTable WHERE $identifierColumn = ?";
        $imagePath = "";

        // Assuming $conn is your MySQLi connection object
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $identifierValue); // "s" indicates a string type
        $stmt->execute();
        $stmt->store_result(); // Store the result set
        $stmt->bind_result($imagePath); // Bind the result to $imagePath
        $stmt->fetch(); // Fetch the result

        return $imagePath;
        
    }
}
?>