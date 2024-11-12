<?php

class RbacController
{
    use DatabaseConnection;

    /************************************************** *
    /               role table function                 *
    /************************************************** */
    function selectRolesTableDetails($roleId = '') {
        try {
            if ($roleId != '') {
                $selectQuery = "SELECT * FROM `emp_role` WHERE `id` = ?";
                $selectStmt = $this->conn->prepare($selectQuery);
                if (!$selectStmt) {
                    throw new Exception("Error preparing statement: " . $this->conn->error);
                }
                $selectStmt->bind_param('i', $roleId);
            } else {
                $selectQuery = "SELECT * FROM `emp_role`";
                $selectStmt = $this->conn->prepare($selectQuery);
                if (!$selectStmt) {
                    throw new Exception("Error preparing statement: " . $this->conn->error);
                }
            }
    
            $selectStmt->execute();
            $result = $selectStmt->get_result();
    
            $responseData = [];
    
            if ($result->num_rows > 0) {
                while ($res = $result->fetch_object()) {
                    $responseData[] = $res;
                }
                $selectStmt->close();
                return json_encode([
                    'status' => true,
                    'data' => $responseData,
                    'message' => 'Data found.'
                ]);
            } else {
                $selectStmt->close();
                return json_encode([
                    'status' => false,
                    'data' => [],
                    'message' => 'No data found!'
                ]);
            }
        } catch (Exception $e) {
            return json_encode([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    



    /************************************************** *
    /               purmission table function           *
    /************************************************** */
    function selectPermissionTableDetails() {
        try {
            $selectQuery = "SELECT * FROM `users_permissions`";
            $selectStmt = $this->conn->prepare($selectQuery);
    
            if (!$selectStmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }
    
            $selectStmt->execute();
            $result = $selectStmt->get_result();
    
            $responseData = [];
    
            if ($result->num_rows > 0) {
                while ($res = $result->fetch_object()) {
                    $responseData[] = $res;
                }
                $selectStmt->close();
                return json_encode([
                    'status' => true, 
                    'data' => $responseData, 
                    'message' => 'Data found.'
                ]);
            } else {
                $selectStmt->close();
                return json_encode([
                    'status' => false, 
                    'data' => '', 
                    'message' => 'No data found!'
                ]);
            }
        } catch (Exception $e) {
            return json_encode([
                'status' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }



    /************************************************** *
    /               RBAC DATA FETCH FUNCTION            *
    /************************************************** */

    function selectRBACDetailsByAdminEmployee($admin, $emp = ''){
        try {
            if($emp != ''){
                $fetchSql = "SELECT `emp_role`, `permission_id` FROM `employees` WHERE `emp_id` = ? AND `admin_id` = ?";
            } else {
                $fetchSql = "SELECT `permission_id` FROM `admin` WHERE `admin_id` = ?";
            }
    
            $selectStmt = $this->conn->prepare($fetchSql);
            if (!$selectStmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }
    
            if($emp != ''){
                $selectStmt->bind_param('is', $emp, $admin);
            } else {
                $selectStmt->bind_param('s', $admin);
            }
    
            $selectStmt->execute();
            $result = $selectStmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($res = $result->fetch_object()) {
                    $data = $res;
                }
                return json_encode(['status' => true, 'data' => $data, 'message' => 'Data found!']);
            } else {
                return json_encode(['status' => true, 'data' => '', 'message' => 'No data found!']);
            }
    
            $selectStmt->close();
            
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}

?>