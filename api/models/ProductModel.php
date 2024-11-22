<?php

namespace Models;

require_once dirname(__DIR__, 2) . '/classes/dbconnection.php';


use DatabaseConnection\DatabaseConnection;
use Exception;

class Product
{
    private $conn;

    public function __construct()
    {
        $db = new DatabaseConnection();
        $this->conn = $db->conn;
        
    }

    function addImagesBySupAdmin($productId, $productImage, $status, $addedBy, $addedOn, $adminId)
    {
        header('Content-Type: application/json');
        try {
            if (!empty($adminId)) {
                $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `status`, `added_by`,  `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertImage);
                $stmt->bind_param("ssssss", $productId, $productImage, $status, $addedBy, $addedOn, $adminId);
            } else {
                $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `added_by`, `added_on`)    VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertImage);
                $stmt->bind_param("ssss", $productId, $productImage, $addedBy, $addedOn);
            }

            if ($stmt->execute()) {
                // Insert successful
                $stmt->close();
              return true;
                
            } else {
                // Insert failed
                throw new Exception("Error inserting data into the database: " . $stmt->error);
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }


    function updateImagesBySupAdmin($productId, $productImage, $status, $addedBy, $addedOn, $adminId)
    {
        header('Content-Type: application/json');
        try {
            if (!empty($adminId)) {
                $updateImage = "UPDATE `product_images` 
                                SET `image` = ?, `status` = ?, `added_by` = ?, `added_on` = ? 
                                WHERE `admin_id` = ? AND `product_id` = ?";
                $stmt = $this->conn->prepare($updateImage);
                $stmt->bind_param("ssssss", $productImage, $status, $addedBy, $addedOn, $adminId, $productId);
            } else {
                $updateImage = "UPDATE `product_images` 
                                SET `image` = ?, `added_by` = ?, `added_on` = ? 
                                WHERE `product_id` = ?";
                $stmt = $this->conn->prepare($updateImage);
                $stmt->bind_param("ssss", $productImage, $addedBy, $addedOn, $productId);
            }
    
            if ($stmt->execute()) {
                // Update successful
                $stmt->close();
                return true;
            } else {
                // Update failed
                throw new Exception("Error updating data in the database: " . $stmt->error);
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    

    public function getDetails($hospitalId)
    {
        header('Content-Type: application/json');
        $query = "SELECT * FROM product_images WHERE product_id = ?";
    
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Statement preparation failed: " . $this->conn->error);
        }
    
        $stmt->bind_param('i', $hospitalId);
    
        // Execute the statement
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();
    
            // Fetch the row
            $row = $result->fetch_all();
    
            // Close the statement
            $stmt->close();
            return $row;
        } else {
            // Handle execution failure
            die("Statement execution failed: " . $stmt->error);
        }
    }
  
}
