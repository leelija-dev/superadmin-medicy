<?php

namespace Models;

require_once dirname(__DIR__, 2) . '/classes/dbconnection.php';


use DatabaseConnection\DatabaseConnection;
use Exception;

class DragPermit
{
    private $conn;

    public function __construct()
    {
        $db = new DatabaseConnection();
        $this->conn = $db->conn;
        header('Content-Type: application/json');

        
    }

    // function updateDragPermit($id, $data) {
    //     // print_r($id);  die();
    //     try {
    //         // print_r($data); die;
    //         $form_20 = $data['form_20'];
    //         $form_21 = $data['form_21'];
    //         $gst_no = $data['gstin'];
    //         $pan = $data['pan'];

    //         $updateQuery = "UPDATE `clinic_info` 
    //         SET `form_20` = ?, `form_21` = ?, `gstin` = ?, `pan` = ? 
    //         WHERE `admin_id` = ?";
            
            
    //         $stmt = $this->conn->prepare($updateQuery);
    
    //         $stmt->bind_param("sssss", $form_20, $form_21, $gst_no, $pan, $id);
    
    //         $stmt->execute();
    
    //         $stmt->close();
    
    //         return ['result' => '1'];
    //     } catch (Exception $e) {
    //         return ['result' => '0', 'message' => $e->getMessage()];
    //     }
    // }

    function updateDragPermit($id, $data) {
        try {
            // Extract only the required fields
            $form_20 = $data['image'] ?? null; // Use 'image' key for form_20 final path
            $form_21 = $data['imageTwo'] ?? null; // Use 'imageTwo' key for form_21 final path
            $gst_no = $data['gstin'] ?? null;
            $pan = $data['pan'] ?? null;
    
            // Prepare the SQL query
            $updateQuery = "UPDATE `clinic_info` 
                            SET `form_20` = ?, `form_21` = ?, `gstin` = ?, `pan` = ? 
                            WHERE `admin_id` = ?";
            
            $stmt = $this->conn->prepare($updateQuery);
            
            // Bind parameters to the query
            $stmt->bind_param("sssss", $form_20, $form_21, $gst_no, $pan, $id);
            
            // Execute the statement
            $stmt->execute();
            
            // Close the statement
            $stmt->close();
            
            // Return success response
            return ['result' => '1'];
        } catch (Exception $e) {
            // Return error response with the exception message
            return ['result' => '0', 'message' => $e->getMessage()];
        }
    }
    


    public function getImage($adminId)
    {
        
        // print_r($hospitalId);   die();
        $query = "SELECT * FROM clinic_info WHERE admin_id = ? LIMIT 1";
    
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Statement preparation failed: " . $this->conn->error);
        }
    
        $stmt->bind_param('s', $adminId);
    
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
            return $row;
        } else {
            // Handle execution failure
            die("Statement execution failed: " . $stmt->error);
        }
    }

  
    public function getDrugPermitDetails($hospitalId)
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
