<?php
require_once CLASS_DIR.'encrypt.inc.php';

class Employees
{
    use DatabaseConnection;

    function addEmp($adminId, $empUsername, $firstName, $lastName, $empRole, $permissions, $empMail,   $empContact, $empAddress, $empPass)
   {
    $password = pass_enc($empPass, EMP_PASS);  // Encrypt the password
    try {
        // Insert query
        $sql = "INSERT INTO `employees` (admin_id, emp_username, fname, lname, emp_role, permission_id, emp_email, contact, emp_address, emp_password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing the insert statement: " . $this->conn->error);
        }

        // Bind parameters for query
        $stmt->bind_param("ssssississ", $adminId, $empUsername, $firstName, $lastName, $empRole, $permissions, $empMail, $empContact, $empAddress, $password);

        // Execute the query
        if ($stmt->execute()) {
            return ["result" => true, "message" => 'Employee added successfully..........!'];
        } else {
            return ["result" => false, "message" => 'Error executing insert statement: ' . $stmt->error];
        }

        $stmt->close();
    } catch (Exception $e) {
        return ["result" => false, "error" => $e->getMessage()];
    }
   }






    function employeeDetails($empId, $adminId){
        try{
            $selectEmp = "SELECT * FROM employees WHERE `emp_id` = '$empId' AND `admin_id` = '$adminId'";

            $stmt = $this->conn->prepare($selectEmp);

            $stmt->execute();

            $res = $stmt->get_result();

            if($res->num_rows > 0){
                $empData = array();
                while ($result = $res->fetch_object()) {
                    $empData[] = $result;
                }
                $stmt->close();
                return json_encode(['status'=>'1', 'message'=>'success', 'data'=>$empData]);
            } else {
                $stmt->close();
                return json_encode(['status'=>'0', 'message'=>'no data', 'data'=> '']);
            }
        } catch(Exception $e) {
            return json_encode(['status'=>'0', 'message'=>$e->getMessage(), 'data'=> '']);
        }
    } //end employeesDisplay function




    function employeesDisplay($adminId=''){
        $empData = array();
        
        if(!empty($adminId)){
        $selectEmp = "SELECT emp_id,emp_username,fname,lname,emp_role,emp_email,updated_on FROM employees WHERE `admin_id` = '$adminId'";
        }else{
            $selectEmp = "SELECT * FROM employees ";  
        }
        $empQuery = $this->conn->query($selectEmp);

        while ($result = $empQuery->fetch_assoc()) {
            $empData[] = $result;
        }

        return $empData;
    } //end employeesDisplay function





    function selectEmpByCol($col='', $data=''){
        try {
            if (!empty($data)) {
            $selectEmp = "SELECT * FROM employees WHERE `$col` = ?";
            $stmt = $this->conn->prepare($selectEmp);
            $stmt->bind_param("s", $data);
            }else{
                $selectEmp = "SELECT * FROM employees ";  
                $stmt = $this->conn->prepare($selectEmp);
            }
            // $stmt = $this->conn->prepare($selectEmp);
            // print_r($stmt);
            if (!$stmt) {
                throw new Exception("Prepare statement failed.");
            }

            // $stmt->bind_param("s", $data);
            $stmt->execute();

            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $empData = array();
                while ($row = $result->fetch_object()) {
                    $empData[] = $row;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $empData]);
            }else{
                return json_encode(['status' => '0', 'message' => 'no data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
    }





    function selectEmpByColData($col, $data){
        try {
            $selectEmp = "SELECT * FROM employees WHERE `$col` = ?";
            $stmt = $this->conn->prepare($selectEmp);
            $stmt->bind_param("s", $data);
           

            if (!$stmt) {
                throw new Exception("Prepare statement failed.");
            }

            $stmt->execute();

            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $empData = array();
                while ($row = $result->fetch_object()) {
                    $empData[] = $row;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $empData]);
            }else{
                return json_encode(['status' => '0', 'message' => 'no data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
    }





    function employeesDisplayByUsername($empUsername){

        $select = "SELECT id,employee_username,employee_name,emp_role FROM employees WHERE employee_username = '$empUsername'";

        $query = $this->conn->query($select);

        while ($result = $query->fetch_array()) {

            $data = $result;
        }

        return $data;
    } //end selectAppointments function





    function empDisplayById($empId){
        $select = "SELECT * FROM employees WHERE emp_id = '$empId'";
        $query = $this->conn->query($select);
        while ($result = $query->fetch_object()) {
            $data = $result;
        }
        $data = json_encode($data);
        return $data;
    } //end empDisplayById function





    function empDisplayByAdminAndEmpId($empId, $admin) {
        try {
            $select = "SELECT * FROM employees WHERE emp_id = ? AND `admin_id` = ?";

            $stmt = $this->conn->prepare($select);

            if (!$stmt) {
                throw new Exception("Error in preparing statement: " . $this->conn->error);
            }

            $stmt->bind_param("ss", $empId, $admin);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data[] = array();
                while($resultData = $result->fetch_object()){
                    $data = $resultData;
                }
                return json_encode($data);
            }else{
                return null;
            }

            
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
            $stmt->close();
        }
    }






    function updateEmpData($fname, $lname, $email, $contactNo, $address, $updatedOn, $empid, $adminid) {
        try {
            $updateQuery = "UPDATE `employees` SET `fname`=?, `lname`=?, `emp_email`=?, `contact`=?, `emp_address`=?, `updated_on`=? WHERE `emp_id`=? AND `admin_id`=?";
            
            $stmt = $this->conn->prepare($updateQuery);
    
            $stmt->bind_param("ssssssss", $fname, $lname, $email, $contactNo, $address, $updatedOn, $empid, $adminid);
            $stmt->execute();
            $stmt->close();
    
            return ['result' => '1'];
        } catch (Exception $e) {
            return ['result' => '0', 'message' => $e->getMessage()];
        }
    }





    function updateEmployeePassword($newPass, $empid, $adminid){
        $password = pass_enc($newPass, EMP_PASS);

        try{
            $updateEmpPass = "UPDATE `employees` SET `emp_password`=? WHERE `emp_id`=? AND `admin_id`=?";

            $stmt = $this->conn->prepare($updateEmpPass);
    
            $stmt->bind_param("sss", $password, $empid, $adminid);

            $stmt->execute();
    
            $stmt->close();

            return ['result' => '1'];

        }catch(Exception $e){
            return ['status'=> '0', 'message'=>$e->getMessage(), 'data'=> ''];
        }
    }


    
    // function updateEmp($empUsername, $empName, $empRole, $empEmail, $empContact, /*Last Variable for id which one you want to update */ $empId){
    //     $edit = "UPDATE  `employees` SET `emp_username` = '$empUsername', `emp_name`= '$empName', `emp_role` = '$empRole', `emp_email` = '$empEmail', `contact` = '$empContact' WHERE `employees`.`emp_id` = '$empId'";

    //     $editQuery = $this->conn->query($edit);
        
    //     return $editQuery;
    // } //end updateEmp function

    function updateEmp($empUsername, $empfName, $empLName, $empRole, $empPermission, $empEmail, $empContact, $empAddress, $empId) {
        try {
            $edit = "UPDATE `employees` SET 
                        `emp_username` = ?, 
                        `fname` = ?, 
                        `lname` = ?, 
                        `emp_role` = ?, 
                        `permission_id` = ?, 
                        `emp_email` = ?, 
                        `contact` = ?, 
                        `emp_address` = ? 
                     WHERE `emp_id` = ?";
    
            $stmt = $this->conn->prepare($edit);
            $stmt->bind_param("sssissisi", $empUsername, $empfName, $empLName, $empRole, $empPermission, $empEmail, $empContact, $empAddress, $empId);
            
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
                return json_encode(['status'=>true, 'message'=>'Data updated successfully']); 
            } else {
                return json_encode(['status'=>false, 'message'=> $this->conn->error]); 
            }
        } catch (Exception $e) {
            return json_encode(['status'=>false, 'message'=>$e->getMessage()]); 
        }
    }
    





    function deleteEmp($deleteEmpId){
        $delEmp = "DELETE FROM `employees` WHERE `employees`.`emp_id` = '$deleteEmpId'";
        $delEmpQuery = $this->conn->query($delEmp);
        return $delEmpQuery;
    } 

    
}//end class