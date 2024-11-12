<?php
class Manufacturer
{
    use DatabaseConnection;


    function addManufacturer($id, $ticketNo, $manufacturerName, $shortName, $manufacturerDsc, $addedBy, $addedOn, $manufactureStatus, $newData, $adminId)
    {
        try {
            $insert = "INSERT INTO manufacturer (`id`, `ticket_no`, `name`, `short_name`, `dsc`, `added_by`, `added_on`, `status`,`new`, `admin_id`)   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($insert);

            if ($stmt) {
                $stmt->bind_param("issssssiis", $id, $ticketNo, $manufacturerName, $shortName, $manufacturerDsc, $addedBy, $addedOn, $manufactureStatus, $newData, $adminId);
            }else {
                 throw new Exception("query error .");
            }

            if(!$stmt){
                throw new Exception("parameter bind error.");
            }

            if($stmt->execute()){
                return json_encode(['status'=>true, 'message'=>'Manufacturer added successfully']);
            }else{
                return json_encode(['status'=>false, 'message'=>'Manufacturer added successfully']);
            }
        } catch (Exception $e) {
            return json_encode(['status'=>true, 'message'=>'function error.'.$e->getMessage()]);
        }
    }






    function lastManufDataFetch(){
        try {
            $select = "SELECT * FROM `manufacturer` ORDER BY `id` DESC LIMIT 1";
    
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($select);
    
            if (!$stmt) {
                throw new Exception("Failed to prepare the statement.");
            } else {
                $stmt->execute();
    
                $result = $stmt->get_result();
    
                if($result->num_rows > 0){
                    $data = $result->fetch_assoc(); // Fetch associative array directly
                    return json_encode($data);
                } else {
                    return null;
                }
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    





    function updateManufacturer($manufacturerName, $manufacturerDsc, $manufacturerId, $manufShortName,  $updatedBy, $updatedOn)
    {
        try {
            // Define the SQL query using a prepared statement
            $update = "UPDATE `manufacturer` SET `name`=?, `dsc`=?, `short_name`=?, `updated_by`=?,     `updated_on`=? WHERE `id`=?";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($update);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("sssssi", $manufacturerName, $manufacturerDsc, $manufShortName, $updatedBy,   $updatedOn, $manufacturerId);


                $updatedQuery = $stmt->execute();
                $stmt->close();

                return $updatedQuery;
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

    ///======Uodate Manufacture Status=======///
    function updateManuStatus($status, $manufacturerId)
    {
        try {
            $update =  "UPDATE `manufacturer` SET `status`=? WHERE `id`=?";
            $stmt = $this->conn->prepare($update);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ii", $status, $manufacturerId);

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
    } ///====== End Uodate Manufacture Status=======///





    function showManufacturer($adminId = '') {
        try {
            $data = array();
            // Prepare the SQL query
            if (!empty($adminId)) {
                $select = "SELECT * FROM `manufacturer` WHERE `admin_id` = ? OR `status` = '1'";
                $selectQuery = $this->conn->prepare($select);
                $selectQuery->bind_param("s", $adminId); // Bind parameter to the query
            } else {
                $select = "SELECT * FROM manufacturer";
                $selectQuery = $this->conn->prepare($select);
            }
            
            $selectQuery->execute();
            
            $result = $selectQuery->get_result();
           
            if($result->num_rows > 0){
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                return $data;
            }else{
                return null;
            }
        } catch (Exception $e) {
            return "Error in showManufacturer: " . $e->getMessage();
        }
    }
    



    function showManufacturerWithLimit()
    {
        try {
            $data = array();
            $select = "SELECT * FROM `manufacturer` LIMIT 10";
            $selectQuery = $this->conn->prepare($select);

            if (!$selectQuery) {
                throw new Exception("Query preparation failed.");
            }

            $selectQuery->execute();

            $result = $selectQuery->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                return json_encode($data);
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo "Error in showManufacturer: " . $e->getMessage();
        }
        return 0;
    }







    function showManufacturerById($manufacturerId)
    {
        try {
            $select = "SELECT * FROM `manufacturer` WHERE `manufacturer`.`id` = ?";
            $stmt = $this->conn->prepare($select);

            $stmt->bind_param("s", $manufacturerId);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $data = $row;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => '', 'data' => $data]);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'message' => '', 'data' => '']);
            }
        } catch (Exception $e) {

            return json_encode(['status' => '', 'message' => $e->getMessage(), 'data' => '']);
        }
        return 0;
    }


    function manufacturerShortName($manufacturerId)
    {
        try {
            $select = "SELECT short_name FROM `manufacturer` WHERE `manufacturer`.`id` = ?";
            $stmt = $this->conn->prepare($select);

            $stmt->bind_param("s", $manufacturerId);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $data = $row->short_name;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => '', 'data' => $data]);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'message' => '', 'data' => '']);
            }
        } catch (Exception $e) {

            return json_encode(['status' => '', 'message' => $e->getMessage(), 'data' => '']);
        }
        return 0;
    }





    function manufSearch($match)
    {
        try {
            if ($match == 'all') {

                $select = "SELECT * FROM `manufacturer` LIMIT 6";
                $stmt = $this->conn->prepare($select);
            } else {

                $select = "SELECT * FROM `manufacturer` WHERE 
                       `name` LIKE CONCAT('%', ?, '%') OR 
                       `id` LIKE CONCAT('%', ?, '%') OR 
                       `short_name` LIKE CONCAT('%', ?, '%') LIMIT 6";
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

    function manufCardSearch($match, $adminId)
    {
        try {
            if ($match == 'all') {

                $select = "SELECT * FROM `manufacturer` WHERE `admin_id` = ? OR `status` = '1' LIMIT 6";
                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("s", $adminId);
            } else {

                $select = "SELECT * FROM `manufacturer` WHERE 
                       (`name` LIKE CONCAT('%', ?, '%') OR 
                       `id` LIKE CONCAT('%', ?, '%') OR 
                       `short_name` LIKE CONCAT('%', ?, '%')) AND (`admin_id` = ? OR `status` = '1') LIMIT 6";
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


    function manufacturerByName($name){
        try {
            $stmt = $this->conn->prepare("SELECT * FROM `manufacturer` WHERE `name` = ?");
            $stmt->bind_param("s", $name);
            
            $stmt->execute();
            
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data = $row;
                }
                return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
            } else {
                return json_encode(['status' => 0, 'message' => 'empty']);
            }
    
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
    
    



    function deleteManufacturer($manufacturerId)
    {
        try {
            $delete = "DELETE FROM `manufacturer` WHERE `id` = ?";

            $stmt = $this->conn->prepare($delete);

            if ($stmt) {
                $stmt->bind_param("i", $manufacturerId);

                $deleteQuery = $stmt->execute();
                $stmt->close();
                return $deleteQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();;
        }
    }


    /// =================manufacture request============ ///

    function insertRequestManufacturer($manufacturerId, $manufacturerName, $manufShortName, $manufacturerDsc,$reqDescription, $addedOn, $adminId)
    {
        try {
            // Define the SQL query using a prepared statement
            $insert = "INSERT INTO manufacturer_request (`manu_id`,`name`, `short_name`, `dsc`,`req_dsc`, `added_on`, `admin_id`)   VALUES (?, ?, ?, ?, ?, ?, ?)";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($insert);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("issssss", $manufacturerId, $manufacturerName, $manufShortName, $manufacturerDsc,$reqDescription, $addedOn, $adminId);

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

    function showRequestManufacturer($manufacturerId = '')
    {
        try {
            $data = array();
            if(!empty($manufacturerId)){
                $select = "SELECT * FROM `manufacturer_request` WHERE `manu_id` = $manufacturerId";
            }else{
                $select = "SELECT * FROM `manufacturer_request`";
            }

            // $select = "SELECT * FROM `manufacturer`";
            $selectQuery = $this->conn->prepare($select);

            if (!$selectQuery) {
                throw new Exception("Query preparation failed.");
            }

            $selectQuery->execute();

            $result = $selectQuery->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                return json_encode($data);
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo "Error in showManufacturer: " . $e->getMessage();
        }
    }

    function deleteRequestManufacturer($manufacturerId)
    {
        $Delete = "DELETE FROM `manufacturer_request` WHERE `manufacturer_request`.`manu_id` = '$manufacturerId'";
        $DeleteQuey = $this->conn->query($Delete);
        return $DeleteQuey;
    }

    function updateNewBadges($distributorId){
        try {
            $update =  "UPDATE `manufacturer` SET `new`= '0' WHERE `id`=?";
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
}//end of LabTypes Class
