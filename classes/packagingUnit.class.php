<?php

class PackagingUnits{
    use DatabaseConnection;

    function addPackagingUnit($ticketNo, $unitName, $addedby, $addedOn, $packStatus, $newData, $adminId)
    {
        try {
            // Define the SQL query using a prepared statement
            $insert = "INSERT INTO packaging_type (`ticket_no`, `unit_name`, `added_by`, `added_on`,`status`,`new`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?,?)";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($insert);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ssssiis", $ticketNo, $unitName, $addedby, $addedOn, $packStatus,$newData, $adminId);

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





    function updateUnit($unitId, $unitName, $updatedBy, $updatedOn)
    {
        try {
            $update = "UPDATE `packaging_type` SET `unit_name` = ?, `updated_by` = ?, `updated_on` = ? WHERE `id` = ?";

            $stmt = $this->conn->prepare($update);

            if ($stmt) {
                $stmt->bind_param("sssi", $unitName, $updatedBy, $updatedOn, $unitId);

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

    ///======= Update Packaging Status ======///
    function updatePackStatus($newStatus, $packagingUnitId)
    {
        try {
            $update = "UPDATE `packaging_type` SET `status` = ? WHERE `id` = ?";

            $stmt = $this->conn->prepare($update);

            if ($stmt) {
                $stmt->bind_param("ii", $newStatus, $packagingUnitId);

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
    } ///======= End Update Packaging Status ======///


    function showPackagingUnits($adminId = '')
    {
        $data = [];
        if (!empty($adminId)) {
            $select         = " SELECT * FROM `packaging_type` WHERE `admin_id` = '$adminId' OR `status` = '1'";
        } else {
            $select         = " SELECT * FROM `packaging_type` ";
        }
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_assoc()) {
            $data[] = $result;
        }
        return $data;
    } //eof showMeasureOfUnits




    function showPackagingUnitById($unitId){
        try {
            $select = "SELECT * FROM packaging_type WHERE `id` = ?";
            $stmt = $this->conn->prepare($select);
            $stmt->bind_param("i", $unitId);
            $stmt->execute();

            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $data = $result->fetch_assoc();
                return json_encode(['status'=>'1', 'data'=>$data]);
            }else{
                return json_encode(['status'=>'0', 'data'=>'']);
            }
            
            $stmt->close();
        } catch (Exception $e) {
            return error_log("Error in showPackagingUnitById: " . $e->getMessage());;
        }
    }





    function findPackagingUnit($unitName){
        try {
            $select = "SELECT * FROM packaging_type WHERE `unit_name` = ?";
            $stmt = $this->conn->prepare($select);
            $stmt->bind_param("s", $unitName);
            $stmt->execute();

            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $data[] = $result->fetch_assoc();
                $message = 'success';
                $status = 1;
            }else {
                $data = [];
                $message = 'empty';
                $status = 0;
            }
            return json_encode(['status'=> $status, 'message'=> $message, 'data'=> $data]);
            
            $stmt->close();
        } catch (Exception $e) {
            return error_log("Error in findPackagingUnit: " . $e->getMessage());;
        }
    }




    

    function packagingTypeName($unitId)
    {
        $select        = " SELECT unit_name FROM packaging_type WHERE `id` = '$unitId'";
        $selectQuery   = $this->conn->query($select);
        if ($selectQuery->num_rows > 0) {
            while ($result = $selectQuery->fetch_array()) {
                $data = $result['unit_name'];
            }
            return $data;
        }
    } //eof showMeasureOfUnits



    function deleteUnit($unitId)
    {

        $Delete = "DELETE FROM `packaging_type` WHERE `packaging_type`.`id` = '$unitId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;
    } //end deleteManufacturer function



    function packUnitSearch($match)
    {
        try {
            if ($match == 'all') {

                $select = "SELECT * FROM `packaging_type` LIMIT 6";
                $stmt = $this->conn->prepare($select);
            } else {

                $select = "SELECT * FROM `packaging_type` WHERE 
                       `unit_name` LIKE CONCAT('%', ?, '%') OR 
                       `id` LIKE CONCAT('%', ?, '%')  LIMIT 6";
                $stmt = $this->conn->prepare($select);
            }


            if ($stmt) {
                if ($match != 'all') {
                    $stmt->bind_param("ss", $match, $match);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }

                    return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
                } else {
                    return json_encode(['status' => 0, 'message' => 'empty', 'data' => '']);
                }
                $stmt->close();
            } else {
                return json_encode(['status' => 0, 'message' => "Statement preparation failed: " . $this->conn->error, 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => "Error: " . $e->getMessage(), 'data' => '']);
        }
    }

    function packUnitCardSearch($match, $adminId)
    {
        try {
            if ($match == 'all') {

                $select = "SELECT * FROM `packaging_type` WHERE `admin_id` = ? OR `status` = '1' LIMIT 6";
                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("s", $adminId);
            } else {

                $select = "SELECT * FROM `packaging_type` WHERE 
                       (`unit_name` LIKE CONCAT('%', ?, '%') OR 
                       `id` LIKE CONCAT('%', ?, '%')) AND (`admin_id` = ?  OR `status` = '1') LIMIT 6";
                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("sss", $match, $match, $adminId);
            }


            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }

                    return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
                } else {
                    return json_encode(['status' => 0, 'message' => 'empty', 'data' => '']);
                }
                $stmt->close();
            } else {
                return json_encode(['status' => 0, 'message' => "Statement preparation failed: " . $this->conn->error, 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => "Error: " . $e->getMessage(), 'data' => '']);
        }
    }


    function insertPackagingRequest($unitId, $unitName,$reqDescription, $addedOn, $adminId)
    {
        try {
            // Define the SQL query using a prepared statement
            $insert = "INSERT INTO packtype_request (`pack_id`,`unit_name`,`req_dsc`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?)";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($insert);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("issss", $unitId, $unitName,$reqDescription, $addedOn, $adminId);

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

    function showPackagingRequest($packagingUnitId = '')
    {
        try {
            if(!empty($packagingUnitId)){
                $result = "SELECT * FROM `packtype_request` WHERE `pack_id` = $packagingUnitId";
            }else{
                $result = "SELECT * FROM `packtype_request`";
            }
            $result = "SELECT * FROM `packtype_request`";
            $stmt = $this->conn->prepare($result);
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
                } else {
                    return json_encode(['status' => 0, 'message' => 'empty', 'data' => '']);
                }
                $stmt->close();
            } else {
                return json_encode(['status' => 0, 'message' => "Statement preparation failed: " . $this->conn->error, 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => "Error: " . $e->getMessage(), 'data' => '']);
        }
    }

    function deletePackRequest($unitId){
        try{ 
        $Delete = "DELETE FROM `packtype_request` WHERE `packtype_request`.`pack_id` = '$unitId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;
        }catch(Exception $e){
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function updateNewBadges($packagingUnitId){
        try {
            $update =  "UPDATE `packaging_type` SET `new`= '0' WHERE `id`=?";
            $stmt = $this->conn->prepare($update);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("i", $packagingUnitId);

                // Execute the query
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
} //end of LabTypes Class
