<?php

class Request
{
    use DatabaseConnection;

    function addNewProductRequest($ticket, $productId, $prodName, $prodCategory, $packegingType,  $qantity, $packegingUnit, $medicinePower, $mrp, $gst, $hsnoNumber, $description, $addedBy, $addedOn, $adminId, $status)
    {
        try {
            $addQuery = "INSERT INTO `product_request`(`ticket_no`, `product_id`, `name`, `type`, `packaging_type`,  `unit_quantity`, `unit`, `power`, `mrp`, `gst`, `hsno_number`, `req_dsc`, `requested_by`, `requested_on`, `admin_id`, `prod_req_status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);
            $stmt->bind_param("ssssisssdisssssi", $ticket, $productId, $prodName, $prodCategory, $packegingType,  $qantity, $packegingUnit, $medicinePower, $mrp, $gst, $hsnoNumber, $description, $addedBy, $addedOn, $adminId, $status);

            if ($stmt->execute()) {
                // Insert successful
                $stmt->close();
                return true;
            } else {
                // Insert failed
                throw new Exception("Error inserting data into the database: " . $stmt->error);
            }
        } catch (Exception $e) {
            // Handle the exception, log the error, or return an error message as needed
            return "Error: " . $e->getMessage();
        }
    }






    function
    addOldProductRequest($ticketNo, $oldProdId, $productId, $prodName, $composition1, $composition2, $prodCategory, $packegingType, $qantity, $unitid, $packegingUnit, $manufid, $medicinePower, $mrp, $gst, $hsnoNumber, $description, $addedBy, $addedOn, $adminId, $status, $oldProdFlag)
    {
        try {

            // echo $oldProdId, $productId, $prodName, $composition1, $composition2, $prodCategory, $packegingType, $qantity, $packegingUnit, $medicinePower, $mrp, $description, $gst, $hsnoNumber, $addedBy, $addedOn, $adminId, $status, $oldProdFlag;

            $addQuery = "INSERT INTO `product_request`(`ticket_no`,`old_prod_id`, `product_id`, `name`, `comp_1`, `comp_2`, `type`, `packaging_type`, `unit_quantity`, `unit_id`, `unit`, `manufacturer_id`, `power`, `mrp`, `gst`, `hsno_number`, `req_dsc`, `requested_by`, `requested_on`, `admin_id`, `prod_req_status`, `old_prod_flag`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);

            if (!$stmt) {
                throw new Exception("Error preparing SQL query: " . $this->conn->error);
            }

            $bindResult = $stmt->bind_param("sssssssisisisdsissssii", $ticketNo, $oldProdId, $productId, $prodName, $composition1, $composition2, $prodCategory, $packegingType, $qantity, $unitid, $packegingUnit, $manufid, $medicinePower, $mrp, $gst, $hsnoNumber, $description, $addedBy, $addedOn, $adminId, $status, $oldProdFlag);

            if (!$bindResult) {
                throw new Exception("Error binding parameters: " . $stmt->error);
            }

            $executeResult = $stmt->execute();
            if (!$executeResult) {
                throw new Exception("Error executing SQL query: " . $stmt->error);
            }

            if ($stmt->affected_rows > 0) {
                $stmt->close();
                return json_encode(['status' => '1', 'data' => 'success']);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'data' => "No rows affected. Insertion failed."]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', "Error: " . $e->getMessage()]);
        }
    }







    function addImageRequest($productId, $productImage, $addedBy, $addedOn, $adminId, $status)
    {
        try {
            if (!empty($adminId)) {
                $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `added_by`, `added_on`, `admin_id`, `status`) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertImage);
                $stmt->bind_param("sssssi", $productId, $productImage, $addedBy, $addedOn, $adminId, $status);
            } else {
                $insertImage = "INSERT INTO `product_images` (`product_id`, `image`, `added_by`, `added_on`, `status`) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertImage);
                $stmt->bind_param("ssssi", $productId, $productImage, $addedBy, $addedOn, $status);
            }


            if ($stmt->execute()) {
                // Insert successful
                $stmt->close();
                return true;
            } else {
                // Insert failed
                throw new Exception("Error inserting data into the database: " . $stmt->error);
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }







    function selectProductById($prodId, $adminId)
    {
        $resultData = array();

        try {
            $searchSql = "SELECT * FROM `product_request` WHERE `old_prod_id` = ? AND `admin_id` = ?";
            $stmt = $this->conn->prepare($searchSql);

            if ($stmt) {
                $stmt->bind_param("ss", $prodId, $adminId);
                $stmt->execute();

                // Get the results
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $resultData = $row;
                    }
                    return json_encode(['status' => '1', 'message' => 'data found', 'data' => $resultData]);
                } else {
                    return json_encode(['status' => '0', 'message' => 'no data found', 'data' => []]);
                }
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }

        return 0;
    }








    function selectProductData($prodId)
    {
        $resultData = array();

        try {
            $searchSql = "SELECT * FROM `product_request` WHERE `product_id` = ?";
            $stmt = $this->conn->prepare($searchSql);

            if ($stmt) {
                $stmt->bind_param("s", $prodId);
                $stmt->execute();

                // Get the results
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $resultData = $row;
                    }
                    return json_encode(['status' => '1', 'message' => 'data found', 'data' => $resultData]);
                } else {
                    return json_encode(['status' => '0', 'message' => 'no data found', 'data' => []]);
                }
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }

        return 0;
    }








    function selectProductReqData($prodId, $adminId)
    {
        $resultData = array();

        try {
            $searchSql = "SELECT * FROM `product_request` WHERE `product_id` = ? AND `admin_id` = ?";
            $stmt = $this->conn->prepare($searchSql);

            if ($stmt) {
                $stmt->bind_param("ss", $prodId, $adminId);
                $stmt->execute();

                // Get the results
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $resultData[] = $row;
                    }
                    return json_encode(['status' => '1', 'message' => 'data found', 'data' => $resultData]);
                } else {
                    return json_encode(['status' => '0', 'message' => 'no data found', 'data' => []]);
                }
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }

        return 0;
    }











    function selectItemLikeProdReqest($data, $adminId)
    {
        $resultData = array();

        try {
            $searchSql = "SELECT * FROM `product_request` WHERE `name` LIKE ? AND `admin_id`= ? LIMIT 10";
            $stmt = $this->conn->prepare($searchSql);

            if ($stmt) {

                $searchPattern = "%" . $data . "%";
                $stmt->bind_param("ss", $searchPattern, $adminId);
                $stmt->execute();

                // Get the results
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $resultData[] = $row;
                    }
                    return json_encode(['status' => '1', 'message' => 'data found', 'data' => $resultData]);
                } else {
                    return json_encode(['status' => '0', 'message' => 'no data found', 'data' => '']);
                }
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
            $stmt->close();
        } catch (Exception $e) {

            echo "Error: " . $e->getMessage();
        }

        return 0;
    }





    function editUpdateProductRequest($productId, $prodName, $composition1, $composition2, $prodCategory,   $packagingType, $quantity, $packagingUnit, $medicinePower, $mrp, $gst, $hsnoNumber, $description,     $addedBy, $addedOn, $prodReqStatus, $oldProdFlag, $adminId)
    {
        try {
            $updateProdRequest = "UPDATE product_request SET `name` = ?, `comp_1` = ?, `comp_2` = ?, `type`     = ?, `packaging_type` = ?, `unit_quantity` = ?, `unit` = ?, `power` = ?, `mrp` = ?,  `gst` = ?,     `hsno_number` = ?, `req_dsc` = ?, `requested_by` = ?, `requested_on` = ?, `prod_req_status` = ?,    `old_prod_flag` = ? WHERE product_id = ? AND `admin_id` = ?";

            $stmt = $this->conn->prepare($updateProdRequest);

            if (!$stmt) {
                throw new Exception("Error preparing update statement: " . $this->conn->error);
            }

            $stmt->bind_param("ssssisssdissssiiss", $prodName, $composition1, $composition2, $prodCategory,     $packagingType, $quantity, $packagingUnit, $medicinePower, $mrp, $gst, $hsnoNumber, $description,   $addedBy, $addedOn, $prodReqStatus, $oldProdFlag, $productId, $adminId);

            if (!$stmt->execute()) {
                throw new Exception("Error updating product request: " . $stmt->error);
            }

            $affectedRows = $stmt->affected_rows;
            $stmt->close();

            if ($affectedRows > 0) {
                return json_encode(['status' => '1', 'data' => 'success']);
            } else {
                return json_encode(['status' => '0', 'data' => 'fail']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'data' => $e->getMessage()]);
        }
    }







    function lastRowId()
    {
        $sql = "SELECT * FROM product_request ORDER BY id DESC LIMIT 1";

        try {
            $stmt = $this->conn->prepare($sql);

            if ($stmt->execute()) {

                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $resultData[] = $row;
                    }
                    return json_encode(['status' => '1', 'data' => $resultData]);
                } else {
                    return json_encode(['status' => '0', 'data' => '']);
                }
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }





    function deleteRequest($prodId)
    {
        try {
            $sql = "DELETE FROM product_request WHERE `product_id` = ?";
            $statement = $this->conn->prepare($sql);

            if ($statement) {
                $statement->bind_param("s", $prodId);
                $statement->execute();

                $result = $statement->get_result();

                if ($statement->affected_rows > 0) {
                    return json_encode(['status' => '1', 'message' => 'Data deleted successfully']);
                } else {
                    return json_encode(['status' => '0', 'message' => 'No data found for deletion']);
                }
            } else {
                throw new Exception("Error preparing delete statement: " . $this->conn->error);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage()]);
        }
    }





    function deleteProductOnTable($ticket, $table)
    {
        try {
            $sql = "DELETE FROM $table WHERE `ticket_no` = ?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception("Error preparing delete statement: " . $this->conn->error);
            }
            $statement->bind_param("s", $ticket);
            $statement->execute();

            if ($statement->affected_rows > 0) {
                return json_encode(['status' => '1', 'message' => 'Data deleted successfully']);
            } else {
                return json_encode(['status' => '0', 'message' => 'No data found for deletion']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage()]);
        }
    }









    function fetchRequestDataByTableName($tableName, $adminId)
    {
        try {
            $queries = [
                'product_request' => ['id', 'ticket_no', 'name', 'req_dsc', 'prod_req_status'],
                'distributor' => ['id', 'ticket_no', 'name', 'dsc', 'status', 'new'],
                'manufacturer' => ['id', 'ticket_no', 'name', 'dsc', 'status', 'new'],
                'packaging_type' => ['id', 'ticket_no', 'unit_name', 'status', 'new'],
                'quantity_unit' => ['id', 'ticket_no', 'short_name', 'status', 'new'],
                'query_request' => ['ticket_no', 'title', 'message', 'status'],
                'ticket_request' => ['ticket_no', 'title', 'message', 'status']
            ];

            if (!array_key_exists($tableName, $queries)) {
                throw new Exception("Invalid table name: " . $tableName);
            }

            $columns = $queries[$tableName];
            $selectColumns = implode(", ", $columns);

            $condition = ($tableName === 'quantity_unit' || $tableName === 'packaging_type' || in_array('new', $columns)) ? "AND new = 1" : "";

            $requestQuery = "SELECT $selectColumns FROM $tableName WHERE admin_id = ? $condition";

            $requestStmt = $this->conn->prepare($requestQuery);
            if (!$requestStmt) {
                throw new Exception("Error preparing SQL query: " . $this->conn->error);
            }

            $requestStmt->bind_param("s", $adminId);
            if (!$requestStmt->execute()) {
                throw new Exception("Error executing query: " . $requestStmt->error);
            }

            $requestResult = $requestStmt->get_result();
            if (!$requestResult) {
                throw new Exception("Error fetching result: " . $this->conn->error);
            }

            if ($requestResult->num_rows > 0) {
                $requestResultData = [];
                while ($row = $requestResult->fetch_assoc()) {
                    $requestResultData[] = $row;
                }
                return json_encode(['status' => '1', 'data' => $requestResultData]);
            } else {
                return json_encode(['status' => '0', 'data' => 'No data found.']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'error' => $e->getMessage()]);
        }
    }





    ///// ======= for super admin view ===========
    function fetchAllRequestDataByTableName($tableName)
    {
        try {
            $newDistributer = false;
            $name = '';
            $description = '';
            $statusColumn = '';

            switch ($tableName) {
                case 'product_request':
                    $name = 'name';
                    $description = 'req_dsc';
                    $statusColumn = 'prod_req_status';
                    break;
                case 'distributor_request':
                    $name = 'name';
                    $description = 'req_dsc';
                    $statusColumn = 'status';
                    break;
                case 'manufacturer_request':
                    $name = 'name';
                    $description = 'req_dsc';
                    $statusColumn = 'status';
                    break;
                case 'packtype_request':
                    $name = 'unit_name';
                    $description = 'req_dsc';
                    $statusColumn = 'status';
                    break;
                case 'distributor':
                    $name = 'name';
                    $description = 'dsc';
                    $statusColumn = 'status';
                    $newDistributer = true;
                    break;
                case 'manufacturer':
                    $name = 'name';
                    $description = 'dsc';
                    $statusColumn = 'status';
                    $newDistributer = true;
                    break;
                case 'packaging_type':
                    $name = 'unit_name';
                    $description = 'Add New Packaging Unit';
                    $statusColumn = 'status';
                    $newDistributer = true;
                    break;
                case 'quantity_unit':
                    $name = 'short_name';
                    $newDistributer = true;
                    break;
                case 'query_request':
                    $name = true;
                    break;
                case 'ticket_request':
                    $name = true;
                    break;
            }

            if ($newDistributer) {
                if ($tableName == 'quantity_unit') {
                    $requestQuery = "SELECT id, $name, new FROM $tableName WHERE new = 1";
                } elseif ($tableName == 'packaging_type') {
                    $requestQuery = "SELECT id, $name, $statusColumn, new FROM $tableName WHERE new = 1";
                } else {
                    $requestQuery = "SELECT id, $name, $description, $statusColumn, new FROM $tableName WHERE new = 1";
                }
            } elseif ($name) {
                if ($tableName == 'query_response') {
                    $requestQuery = "SELECT * FROM $tableName WHERE `status`='ACTIVE'";
                } elseif ($tableName == 'ticket_response') {
                    $requestQuery = "SELECT * FROM $tableName WHERE `status`='ACTIVE'";
                } else {
                    $requestQuery = "SELECT * FROM $tableName";
                }
            } else {
                throw new Exception("Invalid table name specified.");
            }

            $requestStmt = $this->conn->prepare($requestQuery);

            if (!$requestStmt) {
                throw new Exception("Error preparing SQL query: " . $this->conn->error);
            }

            if (!$requestStmt->execute()) {
                throw new Exception("Error executing product request query: " . $requestStmt->error);
            }

            $requestResult = $requestStmt->get_result();

            if (!$requestResult) {
                throw new Exception("Error fetching product request result: " . $this->conn->error);
            }

            if ($requestResult->num_rows > 0) {
                $requestResultData = array();
                while ($row = $requestResult->fetch_assoc()) {
                    $requestResultData[] = $row;
                }
                return json_encode(['status' => '1', 'data' => $requestResultData]);
            } else {
                return json_encode(['status' => '0', 'data' => 'No data found.']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'error' => $e->getMessage()]);
        }
    }






    // ======================== DATA FETCH QUERY ========================
    function fetchDataByTableName($data, $table)
    {
        try {

            $fetchQuery = "SELECT * FROM $table WHERE ticket_no = ?";

            $requestStmt = $this->conn->prepare($fetchQuery);

            if (!$requestStmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }
            $requestStmt->bind_param('s', $data);
            $requestStmt->execute();
            $result = $requestStmt->get_result();

            $responseData = [];

            if ($result->num_rows > 0) {
                while ($res = $result->fetch_object()) {
                    $responseData = $res;
                }
                $requestStmt->close();
                return json_encode(['status' => true, 'data' => $responseData]);
            } else {
                $requestStmt->close();
                return json_encode(['status' => false, 'data' => []]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }





    // =========== add data to response tabel (ticket / query response table) ============
    function addToTicketQueryTable($tableName, $ticketNo, $queryTitle, $queryMessage, $document, $response, $requestCreater, $sender, $status, $addedOn, $viewStatus)
    {
        try {

            $addQuery = "INSERT INTO $tableName(`ticket_no`, `title`, `message`, `attachment`, `response`, `query_creater`, `sender`, `status`, `added_on`, `view_status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);

            if ($stmt === false) {
                throw new Exception('Prepare statement failed: ' . $this->conn->error);
            }

            // Adjust the types according to your database schema, here it's assumed that $addedOn is a string
            $stmt->bind_param("sssssssssi", $ticketNo, $queryTitle, $queryMessage, $document, $response, $requestCreater, $sender, $status, $addedOn, $viewStatus);

            if (!$stmt->execute()) {
                throw new Exception('Execute statement failed: ' . $stmt->error);
            }else{
                if($tableName == 'ticket_response'){
                    $message = "Ticket generated successfully!\nNo:$ticketNo";
                }
                if($tableName == 'query_response'){
                    $message = "Query generated successfully!\nNo:$ticketNo";
                }
            }
            return json_encode(['status' => true, 'rowId' => $stmt->insert_id, 'message'=>$message]);
            $stmt->close();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }







    function addResponseToTicketQueryTable($tableName, $masterTicketNo, $msgTitle, $filename, $response, $status, $addedOn, $viewStatus)
    {
        try {

            $addQuery = "INSERT INTO $tableName(`ticket_no`, `title`, `attachment`, `response`, `status`, `added_on`, `view_status`) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);

            if ($stmt === false) {
                throw new Exception('Prepare statement failed: ' . $this->conn->error);
            }

            // Adjust the types according to your database schema, here it's assumed that $addedOn is a string
            $stmt->bind_param("ssssssi", $masterTicketNo, $msgTitle, $filename, $response, $status, $addedOn, $viewStatus);

            if (!$stmt->execute()) {
                throw new Exception('Execute statement failed: ' . $stmt->error);
            }
            return json_encode(['status' => true, 'rowId' => $stmt->insert_id, 'message'=>'Response send successfully!']);
            $stmt->close();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }





    // =========================== update query/table data ==================================
    function updateStatusByTableName($table, $ticket, $status, $updatedOn)
    {
        try {
            $updateQuery = "UPDATE `$table` SET `status` = ?, `updated_on` = ? WHERE `ticket_no` = ?";

            // echo $updateQuery;

            $stmt = $this->conn->prepare($updateQuery);

            if ($stmt === false) {
                throw new Exception('Prepare statement failed: ' . $this->conn->error);
            }

            $stmt->bind_param("sss", $status, $updatedOn, $ticket);

            if (!$stmt->execute()) {
                throw new Exception('Execute statement failed: ' . $stmt->error);
            }

            $stmt->close();

            return json_encode(['status' => true, 'message' => 'Status updated successfully']);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }





    // ====================== ticket request =====================
    function addNewQueryRequest($tableName, $ticketNo, $email, $contact, $name, $title, $description, $document, $admin, $status, $time)
    {
        // echo $ticketNo, $email, $contact, $name, $description, $document, $admin, $status, $time;
        try {
            $addQuery = "INSERT INTO $tableName (`ticket_no`, `email`, `contact`, `name`, `title`, `message`, `attachment`, `admin_id`, `status`, `added_on`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }

            $stmt->bind_param("ssisssssss", $ticketNo, $email, $contact, $name, $title, $description, $document, $admin, $status, $time);

            if ($stmt->execute()) {
                $insertId = $this->conn->insert_id;
                $stmt->close();
                return json_encode(['status' => true, 'insert_id' => $insertId, 'message' =>'Query Submitted Successfully!']);
            } else {
                // throw new Exception("Error inserting data into the database: " . $stmt->error);
                return json_encode(['status' => false, 'message' => $stmt->error]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'error' => $e->getMessage()]);
        }
    }





    // ====================== query request ======================
    // function addNewQueryRequest($ticketNo, $email, $contact, $name, $title, $description, $document, $admin, $status, $time)
    // {
    //     try {
    //         $addQuery = "INSERT INTO `query_request`(`ticket_no`, `email`, `contact`, `name`, `title`, `message`, `attachment`, `admin_id`, `status`, `added_on`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    //         $stmt = $this->conn->prepare($addQuery);
    //         if (!$stmt) {
    //             throw new Exception("Error preparing statement: " . $this->conn->error);
    //         }

    //         $stmt->bind_param("ssisssssss", $ticketNo, $email, $contact, $name, $title, $description, $document, $admin, $status, $time);

    //         if ($stmt->execute()) {
    //             $insertId = $this->conn->insert_id;
    //             $stmt->close();
    //             return json_encode(['status' => true, 'insert_id' => $insertId, 'message' => '']);
    //         } else {
    //             // throw new Exception("Error inserting data into the database: " . $stmt->error);
    //             return json_encode(['status' => false, 'message' => $stmt->error]);
    //         }
    //     } catch (Exception $e) {
    //         return json_encode(['status' => false, 'error' => $e->getMessage()]);
    //     }
    // }






    function adminResponseCheck($table)
    {
        try {

            $selectQuery = "SELECT * FROM $table WHERE `status`=1 AND `view_status`=1";

            $result = $this->conn->query($selectQuery);

            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return ['status' => true, 'data' => $data];
            } else {
                return ['status' => false, 'data' => ''];
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }






    function editResponseCheck($ticket)
    {
        try {
            $tables = ['ticket_response', 'query_response'];
            $tableName = null;

            for ($i = 0; $i < count($tables); $i++) {
                $checkExistence = "SELECT id FROM `{$tables[$i]}` WHERE `ticket_no` = ?";
                $stmt = $this->conn->prepare($checkExistence);
                $stmt->bind_param('s', $ticket);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $tableName = $tables[$i];
                    break;
                }
            }

            if ($tableName) {
                $updateQuery = "UPDATE `$tableName` SET `view_status` = 0 WHERE `ticket_no` = ?";
                $stmt = $this->conn->prepare($updateQuery);
                $stmt->bind_param('s', $ticket);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo true;
                } else {
                    echo "No rows updated";
                }
            } else {
                echo "No matching table found";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }






    // fetching master ticket
    function fetchMasterTicketNumber($table, $token)
    {
        try {
            $status = false;
            $fetchQuery = "SELECT master_ticket_no FROM $table WHERE ticket_no = ?";
            $stmt = $this->conn->prepare($fetchQuery);
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('s', $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $status = true;
                $data = $result->fetch_object();
                $message = 'Data found';
            } else {
                $data = null; // Use null for consistency if no data found
                $message = 'No data found';
            }

            $stmt->close(); // Close the statement
            return json_encode(['status' => $status, 'data' => $data, 'message' => $message]);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'data' => null, 'message' => $e->getMessage()]);
        }
    }





    // new fucntion for fetching ticket query data
    function fetchedTicketQueryData($table1, $table2, $token)
    {
        try {
            $status = false;
            $fetchQuery = "
                SELECT 
                    t1.*, 
                    t2.email AS sender_email,
                    t2.contact AS sender_contact,
                    t2.status AS master_table_status
                FROM $table1 t1
                JOIN $table2 t2 ON t1.ticket_no = t2.ticket_no
                WHERE t1.ticket_no = ?";
            
            $stmt = $this->conn->prepare($fetchQuery);
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }
    
            $stmt->bind_param('s', $token);
            $stmt->execute();
            
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                while($res = $result->fetch_object()){
                    $status = true;
                    $data[] = $res;  
                    $message = 'Data found';
                }
            } else {
                $data = null; 
                $message = 'No data found';
            }
    
            $stmt->close();  
    
            return json_encode(['status' => $status, 'data' => $data, 'message' => $message]);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'data' => null, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    




    function fetchMasterTicketData($table, $token)
    {
        try {
            $status = false;
            $fetchQuery = "SELECT * FROM $table WHERE ticket_no = ?";
            $stmt = $this->conn->prepare($fetchQuery);
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('s', $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $status = true;
                $data = $result->fetch_object();
                $message = 'Data found';
            } else {
                $data = null; // Use null for consistency if no data found
                $message = 'No data found';
            }

            $stmt->close(); // Close the statement
            return json_encode(['status' => $status, 'data' => $data, 'message' => $message]);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'data' => null, 'message' => $e->getMessage()]);
        }
    }







    function selectFromTables($ticket)
    {
        try {
            $tables = ['ticket_request', 'query_request'];
            $tableName = null;
            $queryTable = null;
            $data = [];
            $status = false;

            // Check existence in tables
            foreach ($tables as $table) {
                $checkExistence = "SELECT * FROM `$table` WHERE `ticket_no` = ?";
                $stmt = $this->conn->prepare($checkExistence);
                if ($stmt === false) {
                    throw new Exception('Prepare failed: ' . $this->conn->error);
                }
                $stmt->bind_param('s', $ticket);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $tableName = $table;
                    break;
                }
            }

            // Determine the response table based on the found table
            if ($tableName == 'ticket_request') {
                $queryTable = 'ticket_response';
            } elseif ($tableName == 'query_request') {
                $queryTable = 'query_response';
            }

            // Perform join if the response table is found
            if (!empty($queryTable)) {
                $joinQuery = "SELECT * FROM $queryTable WHERE ticket_no = ? ORDER BY added_on ASC";

                $stmt = $this->conn->prepare($joinQuery);
                if ($stmt === false) {
                    throw new Exception('Prepare failed: ' . $this->conn->error);
                }
                $stmt->bind_param('s', $ticket);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $status = true;
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                }
                $stmt->close();
            } else {
                $data = 'No response table found!';
            }

            return json_encode(['status' => $status, 'data' => $data, 'masterTable' => $tableName, 'responseTable' => $queryTable]);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'error' => $e->getMessage()]);
        }
    }





    // function selectFromTables($ticket)
    // {
    //     try {
    //         $tables1 = ['ticket_request', 'query_request'];
    //         $tables2 = ['ticket_response', 'query_response'];
    //         $tableName1 = null;
    //         $tableName2 = null;
    //         $data = [];
    //         $status = false;

    //         // Check existence in tables1
    //         foreach ($tables1 as $table) {
    //             $checkExistence = "SELECT * FROM `$table` WHERE `ticket_no` = ?";
    //             $stmt = $this->conn->prepare($checkExistence);
    //             if ($stmt === false) {
    //                 throw new Exception('Prepare failed: ' . $this->conn->error);
    //             }
    //             $stmt->bind_param('s', $ticket);
    //             $stmt->execute();
    //             $result = $stmt->get_result();

    //             if ($result->num_rows > 0) {
    //                 $tableName1 = $table;
    //                 break;
    //             }
    //         }

    //         // Check existence in tables2
    //         foreach ($tables2 as $table) {
    //             $checkExistence = "SELECT * FROM `$table` WHERE `ticket_no` = ?";
    //             $stmt = $this->conn->prepare($checkExistence);
    //             if ($stmt === false) {
    //                 throw new Exception('Prepare failed: ' . $this->conn->error);
    //             }
    //             $stmt->bind_param('s', $ticket);
    //             $stmt->execute();
    //             $result = $stmt->get_result();

    //             if ($result->num_rows > 0) {
    //                 $tableName2 = $table;
    //                 break;
    //             }
    //         }

    //         // Perform join if both tables are found
    //         if ($tableName1 && $tableName2) {
    //             $joinQuery = "SELECT 
    //                             t1.ticket_no AS ticket1,
    //                             t1.master_ticket_no AS master_ticket,
    //                             t1.email AS sender_email,
    //                             t1.contact AS sender_contact,
    //                             t1.name AS user_name,
    //                             t1.title AS msg_title,
    //                             t1.message AS query,
    //                             t1.attachment AS document,
    //                             t1.admin_id AS user_id,
    //                             t1.status AS query_status,
    //                             t1.added_on AS add_on,
    //                             t1.updated_on AS update_on,
    //                             t2.id AS table2_id,
    //                             t2.ticket_no AS table2_ticket_no,
    //                             t2.master_ticket_no AS table2_master_ticket_no,
    //                             t2.title AS table2_query_title,
    //                             t2.message AS table2_query_message,
    //                             t2.response AS table2_response,
    //                             t2.added_on AS table2_add_on
    //                         FROM `$tableName1` t1
    //                         JOIN `$tableName2` t2 ON t1.`master_ticket_no` = t2.`master_ticket_no`
    //                         WHERE t1.`ticket_no` = ?";

    //             $stmt = $this->conn->prepare($joinQuery);
    //             if ($stmt === false) {
    //                 throw new Exception('Prepare failed: ' . $this->conn->error);
    //             }
    //             $stmt->bind_param('s', $ticket);
    //             $stmt->execute();
    //             $result = $stmt->get_result();

    //             if ($result->num_rows > 0) {
    //                 $status = true;
    //                 $data[] = $result->fetch_object();
    //             }
    //             $stmt->close();
    //         } else {
    //             $data = 'No table found!';
    //         }

    //         return json_encode(['status' => $status, 'data' => $data, 'table1' => $tableName1, 'table2' => $tableName2]);
    //     } catch (Exception $e) {
    //         return json_encode(['status' => false, 'error' => $e->getMessage()]);
    //     }
    // }






    // function selectFromTableNames($ticket, $table1, $table2)
    // {
    //     try {
    //         $data = [];
    //         $status = false; // Initialize status variable

    //         $joinQuery = "SELECT 
    //                         t1.ticket_no AS ticket1,
    //                         t1.master_ticket_no AS master_ticket,
    //                         t1.email AS sender_email,
    //                         t1.contact AS sender_contact,
    //                         t1.name AS sender_name,
    //                         t1.title AS msg_title,
    //                         t1.message AS query,
    //                         t1.attachment AS document,
    //                         t1.admin_id AS user_id,
    //                         t1.status AS query_status,
    //                         t1.added_on AS add_on,
    //                         t1.updated_on AS update_on,
    //                         t2.id AS table2_id,
    //                         t2.ticket_no AS table2_ticket_no,
    //                         t2.master_ticket_no AS table2_master_ticket_no,
    //                         t2.title AS table2_query_title,
    //                         t2.message AS table2_query_message,
    //                         t2.query_creater AS table2_query_creater,
    //                         t2.sender AS table2_query_sender,
    //                         t2.response AS table2_response,
    //                         t2.added_on AS table2_add_on
    //                     FROM `$table1` t1
    //                     LEFT JOIN `$table2` t2 ON t1.`master_ticket_no` = t2.`master_ticket_no`
    //                     WHERE t1.`master_ticket_no` = ?
    //                     ORDER BY t1.added_on ASC, t2.added_on ASC";

    //         $stmt = $this->conn->prepare($joinQuery);
    //         if ($stmt === false) {
    //             throw new Exception('Prepare failed: ' . $this->conn->error);
    //         }

    //         $stmt->bind_param('s', $ticket);
    //         $stmt->execute();
    //         $result = $stmt->get_result();

    //         if ($result->num_rows > 0) {
    //             $status = true;
    //             while ($row = $result->fetch_object()) {
    //                 $data[] = $row;
    //             }
    //         }

    //         $stmt->close();
    //         return json_encode(['status' => $status, 'data' => $data, 'table1' => $table1, 'table2' => $table2]);
    //     } catch (Exception $e) {
    //         return json_encode(['status' => false, 'error' => $e->getMessage()]);
    //     }
    // }






    function selectFromTableNames($ticket, $table1)
    {
        try {
            $data = [];
            $status = false; // Initialize status variable

            $joinQuery = "SELECT * FROM $table1 WHERE ticket_no = ? ORDER BY added_on ASC";

            $stmt = $this->conn->prepare($joinQuery);
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('s', $ticket);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $status = true;
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
            }

            $stmt->close();
            return json_encode(['status' => $status, 'data' => $data, 'table1' => $table1]);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'error' => $e->getMessage()]);
        }
    }




    function selectFromResponseTable($ticket)
    {
        try {
            $tables = ['ticket_response', 'query_response'];
            $tableName = null;
            $data = [];
            $status = false;

            foreach ($tables as $table) {
                $checkExistence = "SELECT id FROM `$table` WHERE `ticket_no` = ?";
                $stmt = $this->conn->prepare($checkExistence);
                if ($stmt === false) {
                    throw new Exception('Prepare failed: ' . $this->conn->error);
                }
                $stmt->bind_param('s', $ticket);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $tableName = $table;
                    break;
                }
            }

            if ($tableName) {
                $updateQuery = "SELECT * FROM `$tableName` WHERE `ticket_no` = ?";
                $stmt = $this->conn->prepare($updateQuery);
                if ($stmt === false) {
                    throw new Exception('Prepare failed: ' . $this->conn->error);
                }
                $stmt->bind_param('s', $ticket);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $status = true;
                    $data = $result->fetch_object();
                }
                $stmt->close();
            } else {
                $data = 'No table found!';
            }

            return json_encode(['status' => $status, 'data' => $data]);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'error' => $e->getMessage()]);
        }
    }




    function checkTicket($table, $ticket){
        try {
            $stmt = $this->conn->prepare("SELECT `ticket_no` FROM $table WHERE `master_ticket_no` = ?");
            $stmt->bind_param("s", $ticket);
            $stmt->execute();
            
            $stmt->store_result();
    
            if ($stmt->num_rows > 1) {
                return json_encode(['status'=>true, 'rowCount'=>$stmt->num_rows]);
            } else {
                return json_encode(['status'=>false, 'rowCount'=>$stmt->num_rows]);
            }
            $stmt->close();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }




    function fetchDataFromTables($ticket, $table1, $table2) {
        try {
            $fetchQuery = "SELECT * FROM $table1 t1
                           INNER JOIN $table2 t2 ON t1.master_ticket_no = t2.master_ticket_no
                           WHERE t1.master_ticket_no = ?";
    

            $requestStmt = $this->conn->prepare($fetchQuery);
    
            if (!$requestStmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }
    
            $requestStmt->bind_param('s', $ticket);
    
            $requestStmt->execute();
    
            $result = $requestStmt->get_result();
    
            $responseData = [];
    
            if ($result->num_rows > 0) {
                while ($res = $result->fetch_assoc()) {
                    print_r($res);
                    $responseData[] = $res;
                }
                $requestStmt->close();
                return json_encode(['status' => true, 'data' => $responseData]);
            } else {
                $requestStmt->close();
                return json_encode(['status' => false, 'data' => []]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }




    // 'ticket_response',
    function addQueryAgainstResponse($tableName, $masterTicket, $title, $message, $updatedFile, $adminId, $status, $addedOn)
    {
        try {
            $addQuery = "INSERT INTO $tableName(`ticket_no`, `title`, `message`, `attachment`, `sender`, `status`, `added_on`) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('sssssss', $masterTicket, $title, $message, $updatedFile, $adminId, $status, $addedOn);

            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $stmt->close();
                return json_encode(['status' => true, 'message' => 'Query added successfully']);
            } else {
                throw new Exception('Failed to add query');
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'error' => $e->getMessage()]);
        }
    }

    



    function updateMasterTableStatus($table, $ticket, $status, $updatedOn){
        $response = array('status' => '', 'message' => '');
        try{
            $updateQuery = "UPDATE $table SET `status`= ?, `updated_on`=? WHERE `ticket_no`=?";
            $stmt = $this->conn->prepare($updateQuery);
            
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }
    
            $stmt->bind_param('sss', $status, $updatedOn, $ticket);
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
                $response['status'] = true;
                $response['message'] = 'Record updated successfully';
            } else {
                $response['status'] = false;
                $response['message'] = 'No record updated';
            }
    
            $stmt->close();
        } catch(Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }
        return json_encode($response);
    }
    



    function updateProductRequestTable($tokentNo, $col, $data){
        $response = array('status' => '', 'message' => '');
        try{
            $updateQuery = "UPDATE `product_request` SET $col = ? WHERE `ticket_no`=?";
            $stmt = $this->conn->prepare($updateQuery);
            
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }
    
            $stmt->bind_param('ss', $data, $tokentNo);
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
                $response['status'] = true;
                $response['message'] = 'Record updated successfully';
            } else {
                $response['status'] = false;
                $response['message'] = 'No record updated';
            }
    
            $stmt->close();
        } catch(Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }
        return json_encode($response);
    }
}
