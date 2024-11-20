<?php

namespace Models;

require_once dirname(__DIR__, 2) . '/classes/dbconnection.php';


use DatabaseConnection\DatabaseConnection;
use Exception;

class Profile
{
    private $conn;

    public function __construct()
    {
        $db = new DatabaseConnection();
        $this->conn = $db->conn;
        header('Content-Type: application/json');
        
    }

    function updateprofileImage($id, $imageName) {
        try {
            // print_r($imageName);  die();
            $updateQuery = "UPDATE `admin` SET `adm_img`=? WHERE `admin_id`=?";
            
            $stmt = $this->conn->prepare($updateQuery);
    
            $stmt->bind_param("ss", $imageName, $id);
    
            $stmt->execute();
    
            $stmt->close();
            if(true){
               $result = ['result' => '1', 'image_name' => $imageName];
            }else{
                $result = ['result' => '0'];
               }
    // print_r($result);  die();
            return $result;
        } catch (Exception $e) {
            return ['result' => '0', 'message' => $e->getMessage()];
        }
    }


    function addImagesBySupAdmin($productId, $productImage, $status, $addedBy, $addedOn, $adminId)
    {
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

    public function getProfileImage($prodId)
    {
        // print_r($prodId);  die();
        $query = "SELECT adm_img FROM admin WHERE admin_id = ? LIMIT 1";
    
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Statement preparation failed: " . $this->conn->error);
        }
    
        $stmt->bind_param('s', $prodId);
    
        // Execute the statement
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();
    
            // Fetch the row
            $row = $result->fetch_assoc();
    
            // Close the statement
            $stmt->close();
    
            // Return the profile image filename or null if not found
            // print_r($row['adm_img']);  die();
            return $row['adm_img'] ?? null;
        } else {
            // Handle execution failure
            die("Statement execution failed: " . $stmt->error);
        }
    }

    public function getAdminDetails($admId)
    {

        header('Content-Type: application/json');
        $query = "SELECT * FROM admin WHERE admin_id = ?";
    
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Statement preparation failed: " . $this->conn->error);
        }
    
        $stmt->bind_param('s', $admId);
    
        // Execute the statement
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();
    
            // Fetch the row
            $row = $result->fetch_assoc();
    // print_r($row);  die();
            // Close the statement
            $stmt->close();
            return $row;
        } else {
            // Handle execution failure
            die("Statement execution failed: " . $stmt->error);
        }
    }
    
}
