<?php


class CurrentStock {
    use DatabaseConnection;
    
    function addCurrentStock($stockInDetailsId, $productId, $batchNo, $expDate, $distributorId, $looselyCount, $looselyPrice, $weightage, $unit, $qty, $mrp, $ptr, $gst, $addedBy, $addedOn, $adminId)
    {
        try {
            $insert = "INSERT INTO `current_stock` (`stock_in_details_id`, `product_id`, `batch_no`, `exp_date`, `distributor_id`, `loosely_count`, `loosely_price`, `weightage`, `unit`, `qty`, `mrp`, `ptr`, `gst`, `added_by`, `added_on`, `admin_id`) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($insert);

            if ($stmt) {
                $stmt->bind_param("isssiidisiddisss", $stockInDetailsId, $productId, $batchNo, $expDate, $distributorId, $looselyCount, $looselyPrice, $weightage, $unit, $qty, $mrp, $ptr, $gst, $addedBy, $addedOn, $adminId);
                $res = $stmt->execute();
                $stmt->close();
                return $res;
            } else {
                // Handle the case where the prepared statement couldn't be created
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle the exception (e.g., log the error, return an error message, etc.)
            // You can customize this part to suit your needs.
            echo "Error: " . $e->getMessage();
            return false;
        }
    }



    // ========================== CURRENT STOCK UPDATE AFTER sales RETURN =============================

    function updateStockByStockInDetailsId($stockInDetailsId, $newQuantity, $newLCount)
    {
        try {
            $updateQuery = "UPDATE `current_stock` SET `qty` = ?, `loosely_count` = ? WHERE `stock_in_details_id` = ?";
            $stmt = $this->conn->prepare($updateQuery);

            if ($stmt) {
                $stmt->bind_param("iii", $newQuantity, $newLCount, $stockInDetailsId);
                $res = $stmt->execute();
                $stmt->close();
                return $res;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle the exception (e.g., log the error, return an error message, etc.)
            // Customize this part to suit your needs.
            echo "Error: " . $e->getMessage();
            return false;
        }
    }



    // ========================== CURRENT STOCK ON SELL =============================

    function updateStockOnSell($id, $updatedQty, $updatedLCount)
    {
        try {
            $updateQuery = "UPDATE `current_stock` SET `qty` = ?, `loosely_count` = ? WHERE `id` = ?";
            $stmt = $this->conn->prepare($updateQuery);

            if ($stmt) {
                $stmt->bind_param("iii", $updatedQty, $updatedLCount, $id);
                $res = $stmt->execute();
                $stmt->close();
                return $res;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle the exception (e.g., log the error, return an error message, etc.)
            // Customize this part to suit your needs.
            echo "Error: " . $e->getMessage();
            return false;
        }
    }



    // ==================== CURRENT STOCK UPDATE UPDATE SLESE RETURN EDIT =====================

    function updateStockByReturnEdit($stokInDetailsId, $newQuantity, $newLCount, $updatedBy, $updatedOn)
    {
        try {
            $editUpdate = "UPDATE `current_stock` SET `qty` = ?, `loosely_count` = ?, `updated_by` = ?, `updated_on` = ? WHERE `stock_in_details_id` = ?";
            $stmt = $this->conn->prepare($editUpdate);

            if ($stmt) {
                $stmt->bind_param("iissi", $newQuantity, $newLCount, $updatedBy, $updatedOn, $stokInDetailsId);
                $res = $stmt->execute();
                $stmt->close();
                return ['result' => true];
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle the exception (e.g., log the error, return an error message, etc.)
            // You can customize this part to suit your needs.
            echo "Error: " . $e->getMessage();
            return false;
        }
    }





    ///// ================ SELECT MRP FROM CURRENT STOCK =========================
    function selectColumOnStockByAdmin($col, $adminId)
    {
        try {
            $resultData = array();

            $selectSql = "SELECT $col FROM `current_stock` WHERE (`qty` > 0 OR `loosely_count` > 0) AND `admin_id` = ?";

            $stmt = $this->conn->prepare($selectSql);

            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $resultData[] = $row;
                    }
                } else {
                    echo "Query failed: " . $this->conn->error;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }

            return $resultData;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }






    /// ================ select from current stock by col and data =================

    function selectByColAndData($column, $data)
    {
        try {
            $resultData = array();

            $selectSql = "SELECT * FROM `current_stock` WHERE $column = ?";

            $stmt = $this->conn->prepare($selectSql);

            if ($stmt) {
                $stmt->bind_param("s", $data);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $resultData[] = $row;
                    }
                } else {
                    echo "Query failed: " . $this->conn->error;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }

            return $resultData;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return array();
        }
    }





    // ================== SHOW CURRENT STOCK BY ADMIN ID ========================
    function showCurrentStockbyAdminId($adminId = '')
    {
        // echo $adminId;
        try {
            $data = array();
            if (!empty($adminId)) {
                $select = "SELECT * 
                            FROM `current_stock` 
                            WHERE (`qty` > 0 OR `loosely_count` > 0) AND `admin_id` = ? 
                            GROUP BY `product_id` 
                            ORDER BY added_on ASC";
                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("s", $adminId);
            } else {
                $select = "SELECT * FROM `current_stock` WHERE (`qty` > 0 OR `loosely_count` > 0) ORDER BY added_on ASC";
                $stmt = $this->conn->prepare($select);
            }

            if ($stmt) {
                // $stmt->bind_param("s", $adminId);
                $stmt->execute();

                $result = $stmt->get_result();

                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $data[] = $row;
                    }
                } else {

                    echo "Query failed: " . $this->conn->error;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }

            if ($result->num_rows > 0) {
                return $data;
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return array();
        }
    }






    function prdSearchBynameOrComposition($data){
        try {
            $selectQuery = "SELECT `product_id` 
                            FROM products 
                            WHERE `name` LIKE ? 
                               OR `comp_1` LIKE ? 
                               OR `comp_2` LIKE ?
            
                        UNION
            
                            SELECT `product_id` 
                            FROM product_request 
                            WHERE `name` LIKE ? 
                               OR `comp_1` LIKE ? 
                               OR `comp_2` LIKE ?";
    
            $stmt = $this->conn->prepare($selectQuery);
    
            if (!$stmt) {
                return "Statement preparation failed: " . $this->conn->error;
            } else {
                
                $param = "%$data%";
                $stmt->bind_param("ssssss", $param, $param, $param, $param, $param, $param);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_array()) {
                        $data[] = $row;
                    }
                    $stmt->close(); 
                    return $data;
                } else {
                    return "No results found.";
                }
            }
        } catch (Exception $e) {
            return "An error occurred: " . $e->getMessage();
        }
    }






    function prodSearchBynameOrCompositionForCurrentStock($searchTerm){
        try {
            $resultData = [];  // Use a different variable to store the result
            
            $selectQuery = "SELECT `product_id` 
                            FROM products 
                            WHERE `name` LIKE ? 
                               OR `comp_1` LIKE ? 
                               OR `comp_2` LIKE ? 
                            UNION
                            SELECT `product_id` 
                            FROM product_request 
                            WHERE `name` LIKE ? 
                               OR `comp_1` LIKE ? 
                               OR `comp_2` LIKE ?";
            
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($selectQuery);
            
            if (!$stmt) {
                return json_encode(['status'=>false, 'message'=>'Statement preparation failed: ' . $this->conn->error]);
            }
            
            // Create the search pattern
            $param = "%" . $searchTerm . "%";
            
            // Bind parameters (6 placeholders for the 6 search conditions)
            $stmt->bind_param("ssssss", $param, $param, $param, $param, $param, $param);
            
            // Execute the statement
            $stmt->execute();
            
            // Get the result set
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Fetch the data
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultData[] = $row;
                }
                
                // Close the statement
                $stmt->close();
                
                // Return the found data
                return json_encode(['status' => true, 'message' => 'Data found', 'data' => $resultData]);
            } else {
                // No data found
                return json_encode(['status' => false, 'message' => 'No data found', 'data' => []]);
            }
            
        } catch (Exception $e) {
            // Catch any exception that occurs
            return json_encode(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    
    





    function currentStockGroupbyPidOnAdmin($adminId = '')
    {
        try {
            $data = array();
            if (!empty($adminId)) {
                $select = "SELECT * FROM current_stock WHERE `admin_id` = ? GROUP BY `product_id`";
                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("s", $adminId);
            } else {
                $select = "SELECT * FROM current_stock GROUP BY `product_id`";
                $stmt = $this->conn->prepare($select);
            }

            // $stmt = $this->conn->prepare($select);

            if ($stmt) {
                // $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $data[] = $row;
                    }
                } else {
                    echo "Query failed: " . $this->conn->error;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }

            return $data;
        } catch (Exception $e) {
            if ($e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }





    function showStockExpiry($currentdate = '', $adminId = '')
    {
        $currentdate = date('Y-m-d', strtotime($currentdate));
        try {
            $data = array();
            if (!empty($adminId)) {
                $select = "SELECT * FROM current_stock WHERE STR_TO_DATE(CONCAT('01/', exp_date), '%d/%m/%Y') < DATE_ADD('$currentdate', INTERVAL 2 MONTH) AND admin_id = '$adminId'";
            } else {
                $select = "SELECT * FROM current_stock WHERE STR_TO_DATE(CONCAT('01/', exp_date), '%d/%m/%Y') < DATE_ADD('$currentdate', INTERVAL 2 MONTH) ";
            }

            $query = $this->conn->query($select);

            while ($row = $query->fetch_assoc()) {
                $data[] = $row;
            }

            return $data;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return array();
        }
    }




    function showStockExpiryByBatch($currentdate = '', $adminId = '')
    {
        $currentdate = date('Y-m-d', strtotime($currentdate));
        try {
            $data = array();

            if (!empty($adminId)) {
                $select = "SELECT * FROM current_stock 
                       WHERE STR_TO_DATE(CONCAT('01/', exp_date), '%d/%m/%Y') 
                       < DATE_ADD(?, INTERVAL 2 MONTH) 
                       AND admin_id = ?";
            } else {
                $select = "SELECT * FROM current_stock 
                       WHERE STR_TO_DATE(CONCAT('01/', exp_date), '%d/%m/%Y') 
                       < DATE_ADD(?, INTERVAL 2 MONTH)";
            }

            $stmt = $this->conn->prepare($select);
            if ($stmt) {
                if (!empty($adminId)) {
                    $stmt->bind_param('ss', $currentdate, $adminId);
                } else {
                    $stmt->bind_param('s', $currentdate);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    return json_encode(['status' => true, 'data' => $data]);
                } else {
                    return json_encode(['status' => false, 'message' => 'No data found']);
                }
                $stmt->close();
            } else {
                return json_encode(['status' => false, 'message' => 'Failed to prepare the query']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'error' => $e->getMessage()]);
        }
    }

    




    function itemExpiryFilter($searchVal = '', $expSearch = '', $adminId = '') {
        try {
            $searchSQL = "SELECT * FROM current_stock WHERE 1=1";
    
            if (!empty($searchVal)) {
                $searchSQL .= " AND (product_id IN (SELECT product_id FROM products WHERE name LIKE '%$searchVal%') OR batch_no LIKE '%$searchVal%')";
            }
    
            if (!empty($expSearch)) {
                $searchSQL .= " AND exp_date = '$expSearch'";
            }
    
            if (!empty($adminId)) {
                $searchSQL .= " AND admin_id = '$adminId'";
            }
    
            $stmt = $this->conn->query($searchSQL);  
    
            if (!$stmt) {
                throw new Exception('Query execution failed: ' . $this->conn->error);
            }
    
            if ($stmt->num_rows > 0) {
                $responseArray = array();
                while ($row = $stmt->fetch_object()) {
                    $responseArray[] = $row;
                }
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $responseArray]);
            } else {
                return json_encode(['status' => '0', 'message' => 'No data found', 'data' => '']);
            }
    
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => 'Error: ' . $e->getMessage(), 'data' => '']);
        }
    }
    







    function stockExpiaringCheck($date, $adminId)
    {
        try {
            $data = array();

            $expCheckQarry = "SELECT * FROM current_stock WHERE STR_TO_DATE(CONCAT('01/', exp_date), '%d/%m/%Y')    < DATE_ADD($date, INTERVAL 2 MONTH) AND admin_id = '$adminId'";

            $res = $this->conn->query($expCheckQarry);
            while ($result = $res->fetch_array()) {
                $data[] = $result;
            }

            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }





    function showCurrentStocByProductId($productId, $adminId)
    {
        $data = array();
        try {
            $select = "SELECT * FROM current_stock WHERE product_id = ? AND admin_id = ?";
            $stmt = $this->conn->prepare($select);

            if ($stmt) {
                $stmt->bind_param("ss", $productId, $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return json_encode($data);
    }






    // =========== batch number fetch function for new sales pagge ============
    function selectBatch($productId, $adminId)
    {
        $data = array();
        try {
            $select = "SELECT `id`, `product_id`, `batch_no`, `qty`, `loosely_count`, `weightage`, `unit`  FROM current_stock WHERE (product_id = ? AND admin_id = ?) AND qty > 0";
            $stmt = $this->conn->prepare($select);

            if ($stmt) {
                $stmt->bind_param("ss", $productId, $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return json_encode($data);
    }




    

    function showCurrentStockGroupByProductId($productId, $adminId)
    {
        $data = array();
        try {
            $select = "SELECT * FROM current_stock WHERE product_id = ? AND admin_id = ? GROUP BY product_id";
            $stmt = $this->conn->prepare($select);

            if ($stmt) {
                $stmt->bind_param("ss", $productId, $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    return $data;
                }
                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }






    function showCurrentStocByTwoCol($col1, $data1, $col2, $data2)
    {
        try {
            $data = array();
            $select = "SELECT * FROM current_stock WHERE $col1 = ? AND $col2 = ? AND `qty` > 0 ORDER BY added_on ASC";
            $stmt = $this->conn->prepare($select);

            if ($stmt) {
                $stmt->bind_param("ss", $data1, $data2);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_array()) {
                    $data[] = $row;
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





    function showExpStockForStocksummaryCard($chkDt = '', $adminId = '')
    {
        try {
            $data = array();
            if (!empty($adminId)) {
                $select = "SELECT DISTINCT * FROM `current_stock`
                    WHERE CONCAT(SUBSTRING(current_stock.exp_date, 4, 4), '-', SUBSTRING(current_stock.exp_date, 1, 2)) < ?
                    AND (`qty` > 0 OR `loosely_count` > 0)
                    AND `admin_id` = ?
                    ORDER BY added_on ASC";

                $stmt = $this->conn->prepare($select);
                $stmt->bind_param("ss", $chkDt, $adminId);
            } else {
                $select = "SELECT DISTINCT * FROM `current_stock`
                    WHERE CONCAT(SUBSTRING(current_stock.exp_date, 4, 4), '-', SUBSTRING(current_stock.exp_date, 1, 2)) < ?
                    AND (`qty` > 0 OR `loosely_count` > 0)
                    ORDER BY added_on ASC";

                $stmt = $this->conn->prepare($select);
            }
            // Use prepared statements to prevent SQL injection
            // $stmt = $this->conn->prepare($select);
            // $stmt->bind_param("ss", $chkDt, $adminId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
                    $data[] = $row;
                }
                return $data;
            } else {
                return null;
            }
            $stmt->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }







    function showCurrentStocByStokInDetialsId($stockInDetialsId)
    {
        try {
            $select = "SELECT * FROM current_stock WHERE `stock_in_details_id` = '$stockInDetialsId'";
            // echo $select;
            $selectQuery = $this->conn->query($select);
            if ($selectQuery->num_rows > 0) {
                while ($result = $selectQuery->fetch_object()) {
                    $data = $result;
                }
                return json_encode($data);
            } else {
                return null;
            }
        } catch (Exception $e) {
            if ($e) {
                return $e;
            }
        }
    } //eof showCurrentStocByProductId





    function showCurrentStockByPIdAndAdmin($productId, $admin)
    {
        try {
            $data = array();
            $select = "SELECT * FROM current_stock WHERE `product_id` = ? AND `admin_id` = ? AND `qty` > '0' ORDER BY added_on ASC";

            $stmt = $this->conn->prepare($select);

            $stmt->bind_param("ss", $productId, $admin);

            $stmt->execute();

            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                while ($result = $res->fetch_array()) {
                    $data[] = $result;
                }
                $stmt->close();

                return json_encode(['status' => '1', 'message' => 'success', 'data' => $data]);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'message' => 'no data found!', 'data' => '']);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return 0;
    }



    //=====================================================================================================
    ///////////////////////////////////////////////////////////////////////////////////////////////////////


    function incrCurrentStock($productId, $quantity)
    {

        $incrCurrentStock = " UPDATE `current_stock` SET `qty` = '$quantity' WHERE `current_stock`.`product_id` = '$productId'";

        $incrCurrentStockQuery = $this->conn->query($incrCurrentStock);

        return $incrCurrentStockQuery;
    } //eof incrementCurrentStock function 




    // function updateCurrentStockById($id, $newQuantity, $newLCount)
    // {
    //     $editUpdate = " UPDATE `current_stock` SET `qty` = '$newQuantity', `loosely_count`='$newLCount' WHERE `id` = '$id'";
    //     $res = $this->conn->query($editUpdate);
    //     return $res;
    // } //eof updateStock (duplicate function)


    // ==================== current stock update after stock in edit =========================

    function updateStockByStokinDetailsId($stokinDetailsId, $productId, $batchNo, $expDate, $distributorId, $newQuantity, $newLCount, $mrp, $ptr)
    {
        $editUpdate = " UPDATE `current_stock` SET `product_id` = '$productId', `batch_no` = '$batchNo', `exp_date` = '$expDate', `distributor_id` = '$distributorId', `loosely_count` = '$newLCount', `qty` = '$newQuantity', `mrp` = '$mrp', `ptr` = '$ptr' WHERE `current_stock`.`stock_in_details_id` = '$stokinDetailsId'";
        $res = $this->conn->query($editUpdate);
        return $res;
    } //eof updateStock



    function updateCurrentStockByStockInId($stokinDetailsId, $productId, $batchNo, $expDate, $distributorId, $looseCount, $newQuantity, $ptr, $addedby)
    {
        $editUpdate = " UPDATE `current_stock` SET `product_id` = '$productId', `batch_no` = '$batchNo', `exp_date` = '$expDate', `distributor_id` = '$distributorId', `loosely_count` = '$looseCount', `qty` = '$newQuantity', `ptr` = '$ptr', `added_by` = '$addedby' WHERE `stock_in_details_id` = '$stokinDetailsId'";
        $res = $this->conn->query($editUpdate);
        return $res;
    } //eof updateStock

    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //==============================================

    function checkStock($productId, $batchNo)
    {
        $data = array();
        $check = " SELECT * FROM `current_stock` WHERE product_id = '$productId' AND batch_no = '$batchNo'";
        $res = $this->conn->query($check);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }






    function showCurrentStocByPId($productId)
    {
        //echo $productId;
        $data = array();
        $select = "SELECT * FROM current_stock WHERE `product_id` = '$productId' AND `current_stock`.`qty` > '0' ORDER BY added_on ASC ";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStocByPId


    function fetchAllBatchnoByPid($productId)
    {
        //echo $productId;
        $data = array();
        $select = "SELECT `current_stock`.`batch_no` FROM current_stock WHERE `current_stock`.`product_id` = '$productId' AND `current_stock`.`qty` > '0' ORDER BY added_on ASC ";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStocByPId





    function showCurrentStocById($stockId)
    {
        //echo $productId;
        $data = array();
        $select = "SELECT * FROM current_stock WHERE `id` = '$stockId'";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStocByProductId




    function showCurrentStocByProductIdandBatchNo($productId, $BatchNo)
    {
        $data = array();
        $select = "SELECT * FROM `current_stock` WHERE `current_stock`.`product_id` = '$productId' AND `current_stock`.`batch_no`='$BatchNo'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStocByPIdAndProductBatchNo


    //showCurrentStocByProductIdandBatchNoDistributorId
    function showCurrentStocByPIdBNoDId($productId, $BatchNo, $distributorId)
    {
        $data = array();
        $select = "SELECT * FROM current_stock WHERE `current_stock`.`product_id` = '$productId' AND `current_stock`.`batch_no`='$BatchNo' AND `current_stock`.`distributor_id`='$distributorId'";
        // echo $select;
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof showCurrentStocByProductIdandBatchNoDistributorId





    function showCurrentStockByUnit($productId, $unitType, $adminId)
    {

        $data = array();

        try {
            $select = "SELECT * FROM current_stock WHERE (product_id = ? AND $unitType > 0) AND admin_id = ?    ORDER BY added_on ASC";

            $stmt = $this->conn->prepare($select);

            if ($stmt) {
                $stmt->bind_param("ss", $productId, $adminId);
                $stmt->execute();

                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                $stmt->close();
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Log or handle the exception appropriately
            error_log("Error: " . $e->getMessage());
        }

        return $data;
    }





    function checkStockExist($productId)
    {
        $data = array();
        $select = "SELECT product_id FROM current_stock WHERE `current_stock`.`product_id` = '$productId'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    } //eof checkStockExist






    // ========== item expiry details report function ==========
    function itemExpiryDataDetails($startDate, $endDate, $admin, $search = '') {
        $data = array();
        try {
            // Define the base query
            $selectQuery1 = "SELECT 
                                dist.name AS dist_name,
                                dist.gst_id AS dist_gstin,
                                cs.product_id AS product,
                                cs.qty AS current_qty,
                                cs.ptr AS item_ptr,
                                cs.exp_date AS exp_date
                            FROM 
                                current_stock cs
                            JOIN 
                                stock_in_details sid ON sid.id = cs.stock_in_details_id
                            JOIN 
                                stock_in si ON si.id = sid.stokIn_id
                            JOIN 
                                distributor dist ON dist.id = si.distributor_id
                            WHERE 
                                STR_TO_DATE(CONCAT('01/', cs.exp_date), '%d/%m/%Y') BETWEEN STR_TO_DATE(?, '%d/%m/%Y') AND STR_TO_DATE(?, '%d/%m/%Y')
                                AND si.admin_id = ?";  
    
            $selectQuery2 = " AND (dist.name LIKE ? OR dist.gst_id LIKE ?)";
                            
            if ($search != '') {
                $selectQuery = $selectQuery1 . $selectQuery2;
            } else {
                $selectQuery = $selectQuery1;
            }
            
            // Prepare the statement
            $stmt = $this->conn->prepare($selectQuery);
            
            if ($stmt) {
                // Bind parameters based on the presence of the search term
                if ($search != '') {
                    $searchParam = '%' . $search . '%';
                    $stmt->bind_param("sssss", $startDate, $endDate, $admin, $searchParam, $searchParam);
                } else {
                    $stmt->bind_param("sss", $startDate, $endDate, $admin);
                }
    
                // Execute the statement
                $stmt->execute();
        
                // Get the result
                $result = $stmt->get_result();
        
                // Fetch data
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return json_encode(['status' => true, 'data' => $data]);
                } else {
                    return json_encode(['status' => false]);  // No results
                }
                
                // Close the statement
                $stmt->close();
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    
    
    
    ///////////////////////////////////////////////////////////////////////
    //=============================delete section =========================
    ///////////////////////////////////////////////////////////////////////

    function deleteCurrentStockbyId($productId)
    {
        try {
            $delQry = "DELETE FROM `current_stock` WHERE `product_id` = ?";

            $stmt = $this->conn->prepare($delQry);

            $stmt->bind_param("s", $productId);

            $stmt->execute();

            $result = $stmt->affected_rows;
            $stmt->close();

            if ($result > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            if ($e) {
                return $e;
            } else {
                return 0;
            }
        }
    }





    function deleteCurrentStockbyStockIndetailsId($stockIndetailsID)
    {
        try {
            $delQry = "DELETE FROM `current_stock` WHERE `stock_in_details_id` = ?";
            $stmt = $this->conn->prepare($delQry);

            $stmt->bind_param("i", $stockIndetailsID);

            $stmt->execute();

            $result = $stmt->affected_rows;

            $stmt->close();

            if ($result > 0) {
                return true;
            } else {
                return $result;
            }
        } catch (Exception $e) {
            if ($e) {
                echo "Error: " . $e->getMessage();
            } else {
                return 0; // indicate execution fail
            }
        }
    }






    function deleteCurrentStock($productId, $batchNo)
    {

        $delQry = "DELETE FROM `current_stock` WHERE `current_stock`.`product_id` = '$productId' and `batch_no` = '$batchNo' ";
        // echo $delQry.$this->conn->error;exit;
        $delSql = $this->conn->query($delQry);
        return $delSql;
    } // eof stockInDelete







    function deleteByTabelData($table, $data)
    {
        $delQry = "DELETE FROM `current_stock` WHERE `$table` = '$data'";
        $delSql = $this->conn->query($delQry);
        return $delSql;
    } // eof stockInDelete

}//eof Products class
