<?php 
class AccessPermission{

    use DatabaseConnection;
    

    function showPermission($roleId, $adminId) {
        try {
            $select = "SELECT allow_page FROM `access_permission` WHERE `role_id` = ?";
            
            $stmt = $this->conn->prepare($select);
            
            if ($stmt === false) {
                throw new Exception("Error in preparing statement: " . $this->conn->error);
            }
    
            $stmt->bind_param("s", $roleId);
    
            $stmt->execute();
    
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                while($resultData = $result->fetch_assoc()){
                    $data[] = $resultData['allow_page'];
                }
                return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
            } else {
                return json_encode(['status' => 0, 'message' => 'empty', 'data' => '']);
            }
        } catch (Exception $e) {
            echo  $e->getMessage();
            return json_encode(['status' => 0, 'message' => $e->getMessage(), 'data' => '']);

        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
        return 0;
    }
    
}
?>
