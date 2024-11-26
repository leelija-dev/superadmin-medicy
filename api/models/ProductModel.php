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

    // function addImagesBySupAdmin($productId, $productImage, $status, $addedBy, $addedOn, $adminId, $priority)
    // {
    //     header('Content-Type: application/json');
    //     try {
    //         if (!empty($adminId)) {
    //             $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `status`, `added_by`,  `added_on`, `admin_id`, 'set_priority') VALUES (?, ?, ?, ?, ?, ?, ?)";
    //             $stmt = $this->conn->prepare($insertImage);
    //             $stmt->bind_param("ssssss", $productId, $productImage, $status, $addedBy, $addedOn, $adminId, $priority);
    //         } else {
    //             $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `added_by`, `added_on`, 'set_priority')    VALUES (?, ?, ?, ?, ?)";
    //             $stmt = $this->conn->prepare($insertImage);
    //             $stmt->bind_param("ssss", $productId, $productImage, $addedBy, $addedOn, $priority);
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

    function addImagesBySupAdmin($productId, $productImage, $status, $addedBy, $addedOn, $adminId, $isfeatured)
    {
        header('Content-Type: application/json');
        try {
            echo 'featured:' . $isfeatured;
            if (!empty($adminId)) {
                $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `status`, `added_by`, `added_on`, `admin_id`, `set_priority`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertImage);
                $stmt->bind_param("sssssss", $productId, $productImage, $status, $addedBy, $addedOn, $adminId, $isfeatured);
            } else {
                $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `added_by`, `added_on`, `set_priority`) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertImage);
                $stmt->bind_param("sssss", $productId, $productImage, $addedBy, $addedOn, $isfeatured);
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



    function updateImagesBySupAdmin($productId, $productImage, $status, $addedBy, $addedOn, $adminId, $isfeatured)
    {
        header('Content-Type: application/json');
        try {
            echo 'featured:' . $isfeatured;
            if (!empty($adminId)) {
                $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `status`, `added_by`, `added_on`, `admin_id`, `set_priority`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertImage);
                $stmt->bind_param("sssssss", $productId, $productImage, $status, $addedBy, $addedOn, $adminId, $isfeatured);
            } else {
                $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `added_by`, `added_on`, `set_priority`) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertImage);
                $stmt->bind_param("sssss", $productId, $productImage, $addedBy, $addedOn, $isfeatured);
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


    // public function checkPriorityImage($prodId)
    // {
    //     header('Content-Type: application/json');
    //     $query = "SELECT * FROM product_images WHERE product_id = ? and set_priority = ?";
    //     $priority = 1;
    //     $stmt = $this->conn->prepare($query);
    //     if (!$stmt) {
    //         die("Statement preparation failed: " . $this->conn->error);
    //     }

    //     $stmt->bind_param('si', $prodId, $priority);

    //     // Execute the statement
    //     if ($stmt->execute()) {
    //         // Get the result
    //         $result = $stmt->get_result();
    //         // print_r($result); die;
    //         // Fetch the row
    //         $row = $result->mysqli_fetch_all();
    //         print_r($row);
    //         die;
    //         // Close the statement
    //         $stmt->close();
    //         return $row;
    //     } else {
    //         // Handle execution failure
    //         die("Statement execution failed: " . $stmt->error);
    //     }
    // }

    public function checkPriorityImage($prodId)
    {
        // Set the header for JSON response
        header('Content-Type: application/json');

        // Define query and priority value
        $query = "SELECT image FROM product_images WHERE product_id = ? AND set_priority = ?";
        $priority = 1;

        // Prepare the statement
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die(json_encode(['error' => 'Statement preparation failed', 'details' => $this->conn->error]));
        }

        // Bind parameters
        $stmt->bind_param('ss', $prodId, $priority);

        // Execute the statement
        if ($stmt->execute()) {
            // Fetch result
            $result = $stmt->get_result();

            if ($result) {
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                
                $result->free();
                $stmt->close();

                return $rows;
            } else {
                $stmt->close();
                die(json_encode(['error' => 'Failed to fetch result', 'details' => $this->conn->error]));
            }
        } else {
            // Handle execution failure
            $stmt->close();
            die(json_encode(['error' => 'Statement execution failed', 'details' => $stmt->error]));
        }
    }
}
