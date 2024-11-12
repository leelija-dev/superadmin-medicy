<?php

class ItemUnit
{
    use DatabaseConnection;

    function addItemUnit($unitName, $status, $new, $addedby, $addedOn, $adminId) {
        try {
            $insert = "INSERT INTO item_unit (`name`, `status`, `new`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($insert);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ssssss", $unitName, $status, $new, $addedby, $addedOn, $adminId);

                // Execute the query
                $insertQuery = $stmt->execute();
                $stmt->close();
                return $insertQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }





    // function updateUnit($unitId, $unitName, $updatedBy, $updatedOn) {
    //     try {
    //         $update = "UPDATE `packaging_type` SET `unit_name` = ?, `updated_by` = ?, `updated_on` = ? WHERE `id` = ?";

    //         $stmt = $this->conn->prepare($update);

    //         if ($stmt) {
    //             $stmt->bind_param("sssi", $unitName, $updatedBy, $updatedOn, $unitId);

    //             $updatedQuery = $stmt->execute();
    //             $stmt->close();
    //             return $updatedQuery;
    //         } else {
    //             throw new Exception("Failed to prepare the statement.");
    //         }
    //     } catch (Exception $e) {
    //         echo "Error: " . $e->getMessage();
    //         return false;
    //     }
    // }



    function showItemUnits()
    {
        $select         = " SELECT * FROM item_unit";
        $selectQuery    = $this->conn->query($select);
        while ($result  = $selectQuery->fetch_assoc()) {
            $data[] = $result;
        }
        return $data;
    } //eof showMeasureOfUnits




    function itemUnitName($unitId)
    {
        $select        = " SELECT name FROM item_unit WHERE `id` = '$unitId'";
        $selectQuery   = $this->conn->query($select);
        if ($selectQuery->num_rows > 0) {

            $result = $selectQuery->fetch_array();
            return $result['name'];
        }
        return '';
    } //eof showMeasureOfUnits



    function findItemUnit($unitName)
    {
        try {

            $select = "SELECT * FROM `item_unit` WHERE `name` = ?  LIMIT 6";
            $stmt = $this->conn->prepare($select);
            $stmt->bind_param("s", $unitName);

            if ($stmt) {

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_object()) {
                        $data = $row;
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

    function itemUnitCardSearch($match, $adminId)
    {
        try {
            if ($match == 'all') {

                $select = "SELECT * FROM `item_unit` WHERE `admin_id` = ?  LIMIT 6";
                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("s", $adminId);
            } else {

                $select = "SELECT * FROM `item_unit` WHERE `name` LIKE CONCAT('%', ?, '%') LIMIT 6";
                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("s", $match);
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


    // function deleteUnit($unitId){

    //     $Delete = "DELETE FROM `packaging_type` WHERE `packaging_type`.`id` = '$unitId'";
    //     $DeleteQuey = $this->conn->query($Delete);
    //     return $DeleteQuey;

    // }//end deleteManufacturer function





} //end of LabTypes Class
