<?php
class MeasureOfUnits{
    use DatabaseConnection;

    function addMeasureOfUnits($shortName, $fullName, $addedBy, $addedOn,$newData, $adminId) {
        try {
            // Define the SQL query using a prepared statement
            $insert = "INSERT INTO quantity_unit (`short_name`, `full_name`, `added_by`, `added_on`,`new`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?)";
            
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($insert);
    
            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ssssis", $shortName, $fullName, $addedBy, $addedOn,$newData, $adminId);
    
                // Execute the query
                $insertQuery = $stmt->execute();
                $stmt->close();
                return $insertQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // Customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    


 

    function updateUnit($shortName, $fullName, $unitId, $updatedBy, $updatedOn) {
        try {
            $update = "UPDATE `quantity_unit` SET `short_name` = ?, `full_name` = ?, `updated_by` = ?, `updated_on` = ? WHERE `id` = ?";
            
            $stmt = $this->conn->prepare($update);
    
            if ($stmt) {
                $stmt->bind_param("ssisi", $shortName, $fullName, $updatedBy, $updatedOn, $unitId);

                $updatedQuery = $stmt->execute();
                $stmt->close();
                return $updatedQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function updateBadge($unitId){
        try {
            $update = "UPDATE `quantity_unit` SET `new` = '0' WHERE `id` = ?";
            
            $stmt = $this->conn->prepare($update);
    
            if ($stmt) {
                $stmt->bind_param("i", $unitId);

                $updatedQuery = $stmt->execute();
                $stmt->close();
                return $updatedQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }



    function showMeasureOfUnits($adminId = ''){
        $data           = array();
        if(!empty($adminId)){
            $select         = " SELECT * FROM quantity_unit WHERE `admin_id` = '$adminId'";
        }else{
            $select         = " SELECT * FROM quantity_unit";
        }
    
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_array() ) {
            $data[] = $result;
        }
        return $data;
    }//eof showMeasureOfUnits





    function showMeasureOfUnitsById($unitId){
        $data          = array();
        $select        = " SELECT * FROM quantity_unit WHERE `quantity_unit`.`id` = '$unitId'";
        $selectQuery   = $this->conn->query($select);
        while ($result = $selectQuery->fetch_assoc() ) {
            $data = $result;
        }
        return $data;
    }//eof showMeasureOfUnits



    
    





    function deleteUnit($unitId){

        $Delete = "DELETE FROM `quantity_unit` WHERE `quantity_unit`.`id` = '$unitId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;

    }//end deleteManufacturer function


    //===========insert and update unit activity=======//
    function insertUnitactivity($unitId,$sortName,$fullName, $addedBy,$addedOn,$updatedBy,$updatedOn){
        try{
            $insert = "INSERT INTO loose_unit (`unit_id`, `short_name`, `full_name`, `added_by`, `added_on`, `updated_by`, `updated_on`) VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($insert);
    
            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("issssss",$unitId, $sortName, $fullName, $addedBy, $addedOn, $updatedBy,$updatedOn);
    
                // Execute the query
                $insertQuery = $stmt->execute();
                $stmt->close();
                return $insertQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        }catch(Exception $e){

        }
    }

    function showUnitactivity($unitId){
        $data = '';
        $result = "SELECT * FROM loose_unit WHERE `unit_id` = '$unitId'";
        $selectQuery   = $this->conn->query($result);
        while ($result = $selectQuery->fetch_assoc() ) {
            $data = $result;
        }
        return $data;
    }

    function deleteUnitActivity($unitId){

        $Delete = "DELETE FROM `loose_unit` WHERE `loose_unit`.`unit_id` = '$unitId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;

    }

    
    function prodUnitSearch($match) {
        try {
            if ($match == 'all') {
                
                $select = "SELECT * FROM `quantity_unit` LIMIT 6";
                $stmt = $this->conn->prepare($select);
            }else {
                
                $select = "SELECT * FROM `quantity_unit` WHERE 
                       `short_name` LIKE CONCAT('%', ?, '%') OR 
                       `full_name` LIKE CONCAT('%', ?, '%') OR
                       `id` LIKE CONCAT('%', ?, '%')  LIMIT 6";
                $stmt = $this->conn->prepare($select);
            }
                       

            if ($stmt) {
                if ($match != 'all') {
                    $stmt->bind_param("sss", $match, $match, $match);
                }
                
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
    
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
    
                    return json_encode(['status' => 1, 'message' => 'success', 'data'=> $data]);
                } else {
                    return json_encode(['status' => 0, 'message' => 'empty', 'data'=> '']);
                }
                $stmt->close();
            } else {
                return json_encode(['status' => 0, 'message' => "Statement preparation failed: ".$this->conn->error, 'data'=> '']);
            }

        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => "Error: " . $e->getMessage(), 'data'=> '']);

        }
    }

    function prodUnitCardSearch($match, $adminId) {
        try {
            if ($match == 'all') {
                
                $select = "SELECT * FROM `quantity_unit` WHERE `admin_id` = ?  LIMIT 6";
                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("s", $adminId);
            }else {
                
                $select = "SELECT * FROM `quantity_unit` WHERE 
                       (`short_name` LIKE CONCAT('%', ?, '%') OR 
                       `full_name` LIKE CONCAT('%', ?, '%') OR
                       `id` LIKE CONCAT('%', ?, '%')) AND (`admin_id` = ?) LIMIT 6";
                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("ssss", $match, $match, $match, $adminId);
            }
                       

            if ($stmt) {
                // if ($match != 'all') {
                //     $stmt->bind_param("sss", $match, $match, $match);
                // }
                
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
    
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
    
                    return json_encode(['status' => 1, 'message' => 'success', 'data'=> $data]);
                } else {
                    return json_encode(['status' => 0, 'message' => 'empty', 'data'=> '']);
                }
                $stmt->close();
            } else {
                return json_encode(['status' => 0, 'message' => "Statement preparation failed: ".$this->conn->error, 'data'=> '']);
            }

        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => "Error: " . $e->getMessage(), 'data'=> '']);

        }
    }


}//end of LabTypes Class


?>