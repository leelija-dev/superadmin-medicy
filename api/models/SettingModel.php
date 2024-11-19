<?php

namespace Models;

require_once dirname(__DIR__, 2) . '/classes/dbconnection.php';


use DatabaseConnection\DatabaseConnection;
use Exception;

class Setting
{
    private $conn;

    public function __construct()
    {
        $db = new DatabaseConnection();
        $this->conn = $db->conn;
        header('Content-Type: application/json');

        
    }

    function updatelogo($id, $imageName) {
        // print_r($id);  die();
        try {
            $updateQuery = "UPDATE `clinic_info` SET `logo`= ? WHERE `admin_id` = ?";
            
            $stmt = $this->conn->prepare($updateQuery);
    
            $stmt->bind_param("ss", $imageName, $id);
    
            $stmt->execute();
    
            $stmt->close();
    
            return ['result' => '1'];
        } catch (Exception $e) {
            return ['result' => '0', 'message' => $e->getMessage()];
        }
    }


    // function addImagesBySupAdmin($productId, $productImage, $status, $addedBy, $addedOn, $adminId)
    // {
    //     try {
    //         if (!empty($adminId)) {
    //             $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `status`, `added_by`,  `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?)";
    //             $stmt = $this->conn->prepare($insertImage);
    //             $stmt->bind_param("ssssss", $productId, $productImage, $status, $addedBy, $addedOn, $adminId);
    //         } else {
    //             $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `added_by`, `added_on`)    VALUES (?, ?, ?, ?)";
    //             $stmt = $this->conn->prepare($insertImage);
    //             $stmt->bind_param("ssss", $productId, $productImage, $addedBy, $addedOn);
    //         }

    //         if ($stmt->execute()) {
    //             // Insert successful
    //             $stmt->close();
    //           return true;
                
    //         } else {
    //             // Insert failed
    //             throw new Exception("Error inserting data into the database: " . $stmt->error);
    //         }
    //     } catch (Exception $e) {
    //         return "Error: " . $e->getMessage();
    //     }
    // }

    public function getSiteLogo($hospitalId)
    {
        // print_r($hospitalId);   die();
        $query = "SELECT * FROM clinic_info WHERE admin_id = ? LIMIT 1";
    
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Statement preparation failed: " . $this->conn->error);
        }
    
        $stmt->bind_param('s', $hospitalId);
    
        // Execute the statement
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();
    
            // Fetch the row
            $row = $result->fetch_assoc();
    
            // Close the statement
            $stmt->close();
    
            // Return the profile image filename or null if not found
            // print_r($row['logo']);  die();
            return $row['logo'] ?? null;
        } else {
            // Handle execution failure
            die("Statement execution failed: " . $stmt->error);
        }
    }

    public function getSettinsData($hospitalId)
    {

        header('Content-Type: application/json');
        $query = "SELECT * FROM clinic_info WHERE admin_id = ?";
    
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Statement preparation failed: " . $this->conn->error);
        }
    
        $stmt->bind_param('s', $hospitalId);
    
        // Execute the statement
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();
    
            // Fetch the row
            $row = $result->fetch_assoc();
    
            // Close the statement
            $stmt->close();
            return $row;
        } else {
            // Handle execution failure
            die("Statement execution failed: " . $stmt->error);
        }
    }
    
}
