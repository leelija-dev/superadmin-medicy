<?php

class Emproles
{
    use DatabaseConnection;

    function addDesigRole($desigName)
    {
        try {
            $sql = "INSERT INTO `emp_role` (desig_name) VALUES (?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $desigName);

            $result = $stmt->execute();
            $stmt->close();

            return $result;
        } catch (Exception $e) {
            $e->getMessage();
        }
    }


    // function designationRole($adminId)
    // {
    //     try {
    //         $data = [];
    //         $sql = "SELECT * FROM `emp_role` WHERE `admin_id` = '$adminId' ";
    //         $result = $this->conn->query($sql);
    //         while ($results =  $result->fetch_object()) {
    //             $data[] = $results;
    //         }
    //         $data = json_encode($data);
    //         return $data;
    //     } catch (Exception $e) {
    //         $e->getMessage();
    //     }
    // }

    function designationRoleCheckForLogin()
    {
        try {
            $data = [];
            $sql = "SELECT * FROM `emp_role` ";
            $result = $this->conn->query($sql);
            while ($results =  $result->fetch_object()) {
                $data[] = $results;
            }
            $data = json_encode($data);
            return $data;
        } catch (Exception $e) {
            $e->getMessage();
        }
    }



    function designationRoleID($desinId)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM `emp_role` WHERE `id` = ?");

            if (!$stmt) {
                throw new Exception("Error preparing statement");
            }

            $stmt->bind_param("i", $desinId);
            $stmt->execute();

            $result = $stmt->get_result();

            if (!$result) {
                throw new Exception("Error executing query");
            }

            $data = null;

            while ($results = $result->fetch_object()) {
                $data = $results;
            }

            $stmt->close();

            return json_encode(['status'=> '1', 'message'=>'success', 'data'=>$data]);
        } catch (Exception $e) {

            return json_encode(['status'=> '0', 'message'=>$e->getMessage(), 'data'=>'']);
        }
    }




    function deleteDesign($deleteRole)
    {
        $delEmp = "DELETE FROM `emp_role` WHERE `emp_role`.`id` = '$deleteRole'";
        $delEmpQuery = $this->conn->query($delEmp);
        return $delEmpQuery;
    } // end deleteDocCat function

    // function editDesign($desigName, $designId){
    //     $edit = "UPDATE  `designation` SET `desig_name` = '$desigName' WHERE `designation`.`id` = '$designId'";
    //     $editQuery = $this->conn->query($edit);
    //     return $editQuery;
    // }

    function editDesign($desigName, $designId)
    {
        try {
            $edit = "UPDATE `emp_role` SET `desig_name` = ? WHERE `id` = ?";
            $stmt = $this->conn->prepare($edit);

            if (!$stmt) {
                throw new Exception("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
            }

            // Bind parameters
            $stmt->bind_param("ss", $desigName, $designId);

            // Execute the statement
            $editQuery = $stmt->execute();

            if (!$editQuery) {
                throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }

            return $editQuery;
        } catch (Exception $e) {
            // Handle the exception (e.g., log the error, display an error message, etc.)
            echo "Error: " . $e->getMessage();
            return false;
        } finally {
            // Close the statement
            $stmt->close();
        }
    }


    function desigShowID($designId)
    {
        $select = "SELECT * FROM `emp_role` WHERE `id` = '$designId'";
        $query = $this->conn->query($select);
        while ($result = $query->fetch_object()) {
            $data = $result;
        }
        $data = json_encode($data);
        return $data;
    }
}
