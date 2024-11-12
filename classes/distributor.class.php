<?php

class Distributor{
    use DatabaseConnection;
    

    function addDistributor($ticketNo, $distributorName, $distributorGSTID, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $addedBy, $addedOn, $distributorStatus, $newData, $adminId){

        try {
            $insert = "INSERT INTO distributor (`ticket_no`, `name`, `gst_id`, `address`, `area_pin_code`, `phno`, `email`, `dsc`, `added_by`, `added_on`,`status`,`new`,`admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            $stmt = $this->conn->prepare($insert);
            
            if (!$stmt) {
                throw new Exception("Failed to prepare the statement.");
            }
            
            $stmt->bind_param("ssssisssssiis", $ticketNo, $distributorName, $distributorGSTID, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $addedBy, $addedOn, $distributorStatus, $newData, $adminId);
    
            if (!$stmt) {
                throw new Exception("Failed to bind parameters.");
            }
    
            if($stmt->execute()){
                return json_encode(['status'=>true, 'message'=>'distributor added successfully']);
            } else {
                return json_encode(['status'=>false, 'message'=>'erro occured. '.$stmt->error]);
            }
            $stmt->close();
        } catch (Exception $e) {
            return json_encode(['status'=>false, 'message'=>'erro occured. '.$e->getMessage()]);
        }
    }
    




    function updateDist($distributorName, $distributorGSTIN, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $updatedBy, $updatedOn, $distributorId)
    {
        try {
            // Define the SQL query using a prepared statement
            $update = "UPDATE `distributor` SET `name`=?, `gst_id`=?, `address`=?, `area_pin_code`=?, `phno`=?, `email`=?, `dsc`=?, `updated_by`=?, `updated_on`=? WHERE `id`=?";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($update);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("sssssssssi", $distributorName, $distributorGSTIN, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $updatedBy, $updatedOn, $distributorId);

                // Execute the query
                $updatedQuery = $stmt->execute();
                $stmt->close();
                return $updatedQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            return json_encode(['status'=>false, 'message'=>"Error: " . $e->getMessage()]);
        }
    }



    function updateDistStatus($status, $distributorId)
    {
        try {
            $update =  "UPDATE `distributor` SET `status`=? WHERE `id`=?";
            $stmt = $this->conn->prepare($update);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ii", $status, $distributorId);

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


    function distributorName($DistributorId)
    {
        try {
            $select = "SELECT `name` FROM `distributor` WHERE `id` = ?";
            $stmt = $this->conn->prepare($select);

            if ($stmt) {
                $stmt->bind_param("i", $DistributorId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_object();
                    $data = $row->name;
                    // $row = json_encode($row);
                } else {
                    echo "Query returned no results.";
                }
                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
            return $data;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }



    function showDistributor($adminId = '')
    {
        try {
            if (!empty($adminId)) {
                $select = "SELECT * FROM distributor WHERE `admin_id` = '$adminId' ";
            } else {
                $select = "SELECT * FROM distributor";
            }
            $selectQuery = $this->conn->prepare($select);

            if (!$selectQuery) {
                throw new Exception("Error preparing the query: " . $this->conn->error);
            }

            $selectQuery->execute();

            if ($selectQuery->error) {
                throw new Exception("Error executing the query: " . $selectQuery->error);
            }

            $result = $selectQuery->get_result();

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            if (empty($data)) {
                return json_encode(['status' => 0, 'message' => 'empty', 'data' => '']);
            }

            return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => 'Error: ' . $e->getMessage(), 'data' => '']);
        }
    }


    
    function showDistributorById($distributorId)
    {
        try {
            $select         = " SELECT * FROM `distributor` WHERE `distributor`.`id`= '$distributorId'";
            $selectQuery    = $this->conn->query($select);
            if ($selectQuery->num_rows > 0) {
                $data = array();
                while ($result  = $selectQuery->fetch_assoc()) {
                    $data = $result;
                }
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $data]);
            } else {
                return json_encode(['status' => '0', 'message' => 'empty', 'data' => array()]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => ' ', 'message' => $e->getMessage(), 'data' => '']);
        }
    } //eof showDistributorById functiion



    function selectDistributorByName($distributorName)
    {
        $select         = " SELECT * FROM `distributor` WHERE `distributor`.`name`= '$distributorName'";
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showDistributorByName functiion



    function distributorSearch($match)
    {
        try {
            if ($match == 'all') {
                $select = "SELECT * FROM `distributor` LIMIT 6";
                $stmt = $this->conn->prepare($select);
            } else {

                $select = "SELECT * FROM `distributor` WHERE 
                       `name` LIKE CONCAT('%', ?, '%') OR 
                       `id` LIKE CONCAT('%', ?, '%') OR 
                       `address` LIKE CONCAT('%', ?, '%')  LIMIT 6";
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

    function distCardSearch($match, $adminId)
    {
        try {
            if ($match == 'all') {
                $select = "SELECT * FROM `distributor` WHERE `admin_id` = ? OR `status` = '1' LIMIT 6";
                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("s", $adminId);
            } else {

                $select = "SELECT * FROM `distributor` WHERE 
                       (`name` LIKE CONCAT('%', ?, '%') OR 
                       `id` LIKE CONCAT('%', ?, '%') OR 
                       `address` LIKE CONCAT('%', ?, '%')) AND (`admin_id` = ? OR `status` = '1') LIMIT 6";
                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("ssss", $match, $match, $match, $adminId);
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



    function deleteDist($distributorId)
    {

        $Delete = "DELETE FROM `distributor` WHERE `distributor`.`id` = '$distributorId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;
    } //end deleteManufacturer function



    ///================distributor request============////

    function insertRequestDist($distributorId, $distributorName, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $reqDescription, $addedOn, $adminId)
    {
        try {
            // Define the SQL query using a prepared statement
            $insert = "INSERT INTO distributor_request (`dist_id`, `name`, `address`, `area_pin_code`, `phno`, `email`, `dsc`,`req_dsc`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($insert);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("issiisssss", $distributorId, $distributorName, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $reqDescription, $addedOn, $adminId);

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

    

    function showDistRequest($DistributorId = '')
    {
        try {
            if(!empty($DistributorId)){
                $select = "SELECT * FROM distributor_request WHERE `dist_id` = $DistributorId";
            }else{
                $select = "SELECT * FROM distributor_request";
            }
            // $select = "SELECT * FROM distributor";
            $selectQuery = $this->conn->prepare($select);

            if (!$selectQuery) {
                throw new Exception("Error preparing the query: " . $this->conn->error);
            }

            $selectQuery->execute();

            if ($selectQuery->error) {
                throw new Exception("Error executing the query: " . $selectQuery->error);
            }

            $result = $selectQuery->get_result();

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            if (empty($data)) {
                return json_encode(['status' => 0, 'message' => 'empty', 'data' => '']);
            }

            return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => 'Error: ' . $e->getMessage(), 'data' => '']);
        }
    }


    function showDistRequestById($id){
        try {
            $select = "SELECT * FROM distributor_request WHERE `id` = $id";
            $selectQuery = $this->conn->prepare($select);

            if (!$selectQuery) {
                throw new Exception("Error preparing the query: " . $this->conn->error);
            }

            $selectQuery->execute();

            if ($selectQuery->error) {
                throw new Exception("Error executing the query: " . $selectQuery->error);
            }

            $result = $selectQuery->get_result();

            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }

            if (empty($data)) {
                return json_encode(['status' => 0, 'message' => 'empty', 'data' => '']);
            }

            return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => 'Error: ' . $e->getMessage(), 'data' => '']);
        }
    }


    function deleteDistRequest($distributorId)
    {

        $Delete = "DELETE FROM `distributor_request` WHERE `distributor_request`.`dist_id` = '$distributorId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;
    } //end deleteDistRequest function



    function updateNewBadges($distributorId){
        try {
            $update =  "UPDATE `distributor` SET `new`= '0' WHERE `id`=?";
            $stmt = $this->conn->prepare($update);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("i", $distributorId);

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



    function updateDeleteReq($distributorId){
        try {
            $update =  "UPDATE `distributor` SET `del_req`= '1' WHERE `id`=?";
            $stmt = $this->conn->prepare($update);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("i", $distributorId);

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



    function cancelDeleteReq($distributorId){
        try {
            $update =  "UPDATE `distributor` SET `del_req`= '0' WHERE `id`=?";
            $stmt = $this->conn->prepare($update);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("i", $distributorId);

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
