<?php
require_once CLASS_DIR . 'encrypt.inc.php';
class SuperAdmin{
    use DatabaseConnection;
    
    function supAdminDetails(){
        try{
            $chkUser = " SELECT * FROM `super_admin` ";

            $stmt = $this->conn->prepare($chkUser);

            $stmt->execute();

            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                $adminData = array();
                while ($result = $res->fetch_object()) {
                    $adminData[] = $result;
                }
                $stmt->close();
                return json_encode(['status'=>'1', 'message'=>'success', 'data'=>$adminData]);
            } else {
                $stmt->close();
                return json_encode(['status'=>'0', 'message'=>'no data', 'data'=> '']);
            }
        } catch (Exception $e) {
            return json_encode(['status'=>'', 'message'=>$e->getMessage(), 'data'=> '']);
        }
        return 0;
    } //eof CheckEmail


    function getSupAdminPassword($superAdminId){
        try {
            // Prepare and execute the SQL query with a placeholder for the superAdminId
            $stmt = $this->conn->prepare("SELECT `password` FROM `super_admin` WHERE id = ?");
            
            if (!$stmt) {
                throw new Exception("Failed to prepare statement.");
            }
    
            $stmt->bind_param("s", $superAdminId);
            $stmt->execute();
    
            // Check for errors
            if ($stmt->errno) {
                throw new Exception("Error executing statement: " . $stmt->error);
            }
    
            $res = $stmt->get_result();
    
            if ($res->num_rows > 0) {
                $result = $res->fetch_object();
                $stmt->close();
                return json_encode(['status'=>1, 'message'=>'success', 'data'=>$result]);
            } else {
                $stmt->close();
                return json_encode(['status'=>0, 'message'=>'no data']);
            }
        } catch (Exception $e) {
            return json_encode(['status'=>0, 'message'=>$e->getMessage()]);
        }
    }
    



    function updateSupAdminDetails($fname, $lname, $img, $email, $mobNo, $address, $updatedOn, $supadminid) {
        try {
            $updateQuery = "UPDATE `super_admin` SET `fname`=?, `lname`=?, `adm_img`=?, `email`=?, `mobile_no`=?, `address`=?, `updated_on`=? WHERE `id`=?";
            
            $stmt = $this->conn->prepare($updateQuery);
    
            $stmt->bind_param("ssssssss", $fname, $lname, $img, $email, $mobNo, $address, $updatedOn, $supadminid);
    
            $stmt->execute();
    
            $stmt->close();
    
            return ['result' => '1'];
        } catch (Exception $e) {
            return ['result' => '0', 'message' => $e->getMessage()];
        }
    }



    function updateSuperAdminPass($oldPassword, $newPass, $SUPER_ADMINID){
        try{

            $response = json_decode($this->getSupAdminPassword($SUPER_ADMINID));
            if ($response->status == 1) {
                $superadmin    = $response->data;
                $DBPassword   = $superadmin->password;
            }else {
                throw new Exception($response->message);
            }

            $x_password = pass_dec($DBPassword, ADMIN_PASS);
            $password = pass_enc($newPass, ADMIN_PASS);
            
            $strong = isStrongPassword($password);
            if (!$strong) {
                throw new Exception($strong);
            }

            if ($oldPassword === $x_password) {

                $updatePass = "UPDATE `super_admin` SET `password`=? WHERE `id`=?";
                
                $stmt = $this->conn->prepare($updatePass);                
                $stmt->bind_param("ss", $password, $SUPER_ADMINID);
                if (!$stmt->execute()) {
                    throw new Exception($stmt->error);
                }
                $stmt->close();
                return json_encode(['status'=> 1, 'message'=> 'Password Updated!']);

            }else {
                return json_encode(['status'=> 0, 'message'=> 'Current Password  is incorrect!']);
            }
            
        }catch(Exception $e){
            return json_encode(['status'=> '0', 'message'=>$e->getMessage()]);
        }
    }
}

?>