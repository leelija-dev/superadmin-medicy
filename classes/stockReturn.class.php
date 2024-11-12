<?php
require_once 'dbconnect.php';



class StockReturn
{


    use DatabaseConnection;


    ##################################################################################################################################
    #                                                                                                                                #
    #                                            Stock Return Functions(stock_return)                                                #
    #                                                                                                                                #
    ##################################################################################################################################

    function addStockReturn($stockReturnId, $stockInId, $distributorId, $returnDate, $itemQty, $totalReturnQty, $returnGst, $refundMode, $refund, $status, $addedBy, $addedOn, $Admin)
    {
        try {
            // Construct the SQL query with placeholders
            $sql = "INSERT INTO `stock_return` (`id`, `stockin_id`, `distributor_id`, `return_date`, `items`, `total_qty`, `gst_amount`, `refund_mode`, `refund_amount`, `status`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing insert statement: " . $this->conn->error);
            }

            // Bind the parameters
            $stmt->bind_param("iiisiidsdisss", $stockReturnId, $stockInId, $distributorId, $returnDate, $itemQty, $totalReturnQty, $returnGst, $refundMode, $refund, $status, $addedBy, $addedOn, $Admin);

            // Execute the prepared statement
            if ($stmt->execute()) {
                // Return the ID of the newly inserted record
                $insertedId = $stmt->insert_id;
                return ["result" => true];
            } else {
                throw new Exception("Error executing insert statement: " . $stmt->error);
            }
        } catch (Exception $e) {
            return ["result" => false, "error" => $e->getMessage()];
        }
    } // eof addStockReturn



    function showStockReturn($adminId = '')
    {
        try {
            $data = array();
            if (empty($adminId)) {
                $sql  = "SELECT * FROM stock_return";
            } else {
                $sql  = "SELECT * FROM stock_return WHERE `admin_id` = '$adminId' ";
            }
            // $sql  = "SELECT * FROM stock_return";
            $res  = $this->conn->query($sql);

            if ($res->num_rows > 0) {
                while ($result = $res->fetch_object()) {
                    $data[] = $result;
                }
                return json_encode(['status' => '1', 'message' => 'data found', 'data' => $data]);
            } else {
                return json_encode(['status' => '0', 'message' => 'no data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => ' ', 'message' => $e->getMessage(), 'data' => '']);
        }
    } //eof showStockReturn





    function showStockReturnById($returnId)
    {
        try {
            $sql  = "SELECT * FROM stock_return WHERE `id` = $returnId";
            $res  = $this->conn->query($sql);

            if ($res->num_rows > 0) {
                $data = array();
                while ($result = $res->fetch_object()) {
                    $data[] = $result;
                }
                return json_encode(['status' => '1', 'message' => 'data found', 'data' => $data]);
            } else {
                return json_encode(['status' => '0', 'message' => 'no data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => ' ', 'message' => $e->getMessage(), 'data' => '']);
        }
    }





    function stockReturnFilter($table, $value)
    {
        try {
            $sql = "SELECT * FROM stock_return WHERE `$table` = '$value' ORDER BY id DESC";
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                return json_encode(['status' => true, 'message' => 'data found', 'data' => $data]);
                $result->close();
            } else {
                return json_encode(['status' => false, 'message' => 'no data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage(), 'data' => '']);
        }
        return 0;
    }





    function stockReturnBySearchFilter($value, $adminId)
    {
        try {
            $sql = "SELECT * FROM stock_return WHERE (`id` LIKE '%$value%' OR `return_date`LIKE '%$value%' OR `added_by` LIKE '%$value%' OR `refund_mode` LIKE '%$value%' OR `refund_amount` LIKE '%$value%') AND `admin_id` = '$adminId'";

            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                return json_encode(['status' => '1', 'message' => 'data found', 'data' => $data]);
                $result->close();
            } else {
                return json_encode(['status' => '0', 'message' => 'no data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => ' ', 'message' => $e->getMessage(), 'data' => '']);
        }

        return 0;
    }






    function stockReturnFilterByTableName($table, $value, $admin)
    {
        try {
            $sql = "SELECT * FROM stock_return WHERE `$table` = '$value' AND admin_id = '$admin'";
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                return json_encode(['status' => '1', 'message' => 'data found', 'data' => $data]);
                $result->close();
            } else {
                return json_encode(['status' => '0', 'message' => 'no data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => ' ', 'message' => $e->getMessage(), 'data' => '']);
        }

        return 0;
    }






    function stockReturnFilterbyDate($table, $value1, $value2, $Admin)
    {
        try {
            $data = array();
            $sql  = $sql = "SELECT * FROM `stock_return`
            WHERE admin_id = '$Admin' AND DATE(`$table`) BETWEEN '$value1' AND '$value2'";
            $res  = $this->conn->query($sql);

            if ($res->num_rows > 0) {
                while ($result = $res->fetch_array()) {
                    $data[] = $result;
                }
                return json_encode(['status' => '1', 'message' => 'data found', 'data' => $data]);
            } else {
                return json_encode(['status' => '0', 'message' => 'no data found', 'data' => '']);
            }
        } catch (Exception $e) {
            if ($e) {
                echo "Exception occur : " . $e;
            }
        }
    } //eof stockReturnFilter





    function stockReturnByTables($table1, $data1, $table2, $data2)
    {
        try {
            $response = array();
            $selectSalesReturn = "SELECT * FROM `stock_return` WHERE `$table1` = '$data1' AND `$table2` = '$data2'";
            $res = $this->conn->query($selectSalesReturn);
            
            if($res->num_rows > 0){
                while ($result = $res->fetch_array()) {
                    $response[] = $result;
                }
                return json_encode(['status' => '1', 'message' => 'data found', 'data' => $response]);
            }else{
                return json_encode(['status' => '0', 'message' => 'data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return $e->errorMessage();
        }
    }



    function stockReturnStatus($returnId, $statusValue)
    {
        try {

            $sql  = "UPDATE stock_return SET `status` = ? WHERE `id` = ?";
            $stmt = $this->conn->prepare($sql);

            $stmt->bind_param("ii", $statusValue, $returnId);

            $stmt->execute();

            $data = $stmt->affected_rows;

            $stmt->close();

            return $data;
        } catch (Exception $e) {
            return false;
        }
    }


    //stockReturn Edit\update function...........
    function stockReturnEdit($id, $distributorId, $returnDate, $items, $totalQty, $gst, $refundMode, $refundAmount, $addedBy)
    {

        $update = "UPDATE `stock_return` SET `distributor_id`='$distributorId',`return_date`='$returnDate',`items`='$items',`total_qty`='$totalQty',`gst_amount`='$gst',`refund_mode`='$refundMode',`refund_amount`='$refundAmount', `added_by`='$addedBy' WHERE `stock_return`.`id`='$id' ";

        $response = $this->conn->query($update);

        return $response;
    }

    // ---------------EDIT STOCK RETURN UPDATE FUNCTION----------------------------

    function stockReturnEditUpdate($id, $items, $totalQty, $gst, $refundMode, $refundAmount, $updatedBy, $updatedOn)
    {
        try {
            $editANDupdate = "UPDATE `stock_return` SET `items`=?, `total_qty`=?, `gst_amount`=?, `refund_mode`=?, `refund_amount`=?, `updated_by`=?, `updated_on`=? WHERE `id`=?";
            $stmt = $this->conn->prepare($editANDupdate);

            if ($stmt) {
                $stmt->bind_param("sssssssi", $items, $totalQty, $gst, $refundMode, $refundAmount, $updatedBy,  $updatedOn, $id);
                $response = $stmt->execute();
                $stmt->close();
                return ["result" => true];
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }



    function updateStockReturnOnEditStockIn($table1, $data1, $updateData1, $updateData2, $addedBy)
    {

        try {
            $updateOnEditStockIn = "UPDATE `stock_return` SET `distributor_id`=?, `bill_no`=?, `added_by`=? WHERE `$table1`=? ";

            $statement = $this->conn->prepare($updateOnEditStockIn);
            if (!$statement) {
                throw new Exception("Error preparing update statement: " . $this->conn->error);
            }

            $statement->bind_param("ssss", $updateData1, $updateData2, $addedBy, $data1);

            if ($statement->execute()) {
                $affectedRows = $statement->affected_rows;
                if ($affectedRows > 0) {
                    $updatedId = $this->conn->insert_id;
                    return ["result" => true, "id" => $updatedId];
                } else {
                    return ["result" => false, "message" => "No rows were updated."];
                }
            } else {
                throw new Exception("Error executing update statement: " . $statement->error);
            }
        } catch (Exception $e) {
            return ["result" => false, "error" => $e->getMessage()];
        }
    }

    // ========================= stock return filter =======================

    function stockReturnUpdatedFilter($searchVal = '', $startDate = '', $endDate = '', $returnInitiatedBy = '', $paymentMode = '', $adminId = '') {
        try {
            $searchSQL = "SELECT * FROM stock_return WHERE 1=1";
            $params = array();
            $types = '';
    
            if (!empty($searchVal)) {
                $searchSQL .= " AND (id LIKE '%$searchVal%' OR distributor_id  IN (SELECT id  FROM distributor WHERE name LIKE '%$searchVal%') OR refund_amount LIKE '%$searchVal%' OR refund_mode LIKE '%$searchVal%')";
            }
            
            if (!empty($startDate) && !empty($endDate)) {
                $searchSQL .= " AND DATE(return_date) BETWEEN STR_TO_DATE('$startDate', '%d-%m-%Y') AND STR_TO_DATE('$endDate', '%d-%m-%Y')";
            }
    
            if (!empty($returnInitiatedBy)) {
                $searchSQL .= " AND added_by = '$returnInitiatedBy'";
            }

            if (!empty($paymentMode)) {
                $searchSQL .= " AND refund_mode = '$paymentMode'";
            }
    
            if (!empty($adminId)) {
                $searchSQL .= " AND admin_id = '$adminId'";
            }
    
            $stmt = $this->conn->prepare($searchSQL);
            if (!$stmt) {
                throw new Exception('Statement preparation exception: ' . $this->conn->error);
            }
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $purchaseData = array();
                while ($row = $result->fetch_object()) {
                    $purchaseData[] = $row;
                }
                return json_encode(['status' => true, 'message' => 'success', 'data' => $purchaseData]);
            } else {
                return json_encode(['status' => false, 'message' => 'No data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage(), 'data' => '']);
        }
    }


    // ------------------------- DELETE STOCK RETURN DATA -----------------------------

    function delteStockReturnData($stockReturnId)
    {
        $delQuary = "DELETE FROM `stock_return` WHERE `id`='$stockReturnId'";
        $delFromStockReturn = $this->conn->query($delQuary);
        return $delFromStockReturn;
    }

    ###################################################################################################################################
    #                                                                                                                                 #
    #                                           Stock Return Details Functions(stock_return_details)                                  #
    #                                                                                                                                 #
    ###################################################################################################################################

    function addStockReturnDetails($stockReturnId, $stockInDetailsId, $productId, $billNo, $batchNo, $expDate, $unit, $purchaseQty, $freeQty, $mrp, $ptr, $gst, $disc, $returnQty, $refundAmount)
    {
        try {
            $sql = "INSERT INTO stock_return_details (`stock_return_id`, `stokIn_details_id`, `product_id`, `dist_bill_no`, `batch_no`, `exp_date`, `unit`, `purchase_qty`, `free_qty`, `mrp`, `ptr`, `gst`, `disc`, `return_qty`, `refund_amount`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
            $stmt = $this->conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("iisssssiiddiiid", $stockReturnId, $stockInDetailsId, $productId, $billNo, $batchNo, $expDate, $unit, $purchaseQty, $freeQty, $mrp, $ptr, $gst, $disc, $returnQty, $refundAmount);
                $res = $stmt->execute();
                $stmt->close();
                return $res;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }




    //stock return start-------------------

    function showStockReturnDetails($returnId)
    {
        try {
            $data = array();
            $sql = "SELECT * FROM stock_return_details WHERE `stock_return_id` = ?";
            $stmt = $this->conn->prepare($sql);

            $stmt->bind_param("i", $returnId);

            $stmt->execute();

            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                while ($result = $res->fetch_array()) {
                    $data[] = $result;
                }
            }
            return $data;

            $stmt->close();

            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return 0; // SQL failed
    }





    function showStockReturnDataByStokinId($stockInDetailsId)
    {
        try {
            $data = array();
            $sql  = "SELECT * FROM stock_return_details WHERE `stokIn_details_id` = '$stockInDetailsId'";
            $stmt  = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare the statement.");
            }

            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                while ($result = $res->fetch_array()) {
                    $data[] = $result;
                }
                return json_encode(['status' => '1', 'data' => $data]);
            } else {
                return json_encode(['status' => '0', 'data' => '']);
            }
            $stmt->close();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }





    function showStockReturnDataByStokinIdasArray($stockInDetailsId)
    {
        try {
            $data = array();
            $sql  = "SELECT * FROM stock_return_details WHERE `stokIn_details_id` = '$stockInDetailsId'";
            $stmt  = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare the statement.");
            }

            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                while ($result = $res->fetch_array()) {
                    $data[] = $result;
                }
                return $data;
            } else {
                return null;
            }
            $stmt->close();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }





    function showStockReturnDetailsById($Id)
    {

        $data = array();
        $sql  = "SELECT * FROM stock_return_details WHERE `id` = '$Id'";
        $res  = $this->conn->query($sql);
        while ($result = $res->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }
    //eof showStockReturn


    //stockReturnDetails Edit\update function...........
    function stockReturnDetailsEdit($id, $stockReturnId, $productId, $batchNo, $expDate, $unit, $purchaseQty, $freeQty, $mrp, $ptr, $purchaseAmount, $gst, $returnQty, $refundAmount, $addedBy)
    {

        $update = "UPDATE `stock_return_details` SET `product_id`='$productId',`batch_no`='$batchNo',`exp_date`='$expDate',`unit`='$unit',`purchase_qty`='$purchaseQty',`free_qty`='$freeQty',`mrp`='$mrp',`ptr`='$ptr',`purchase_amount`='$purchaseAmount',`gst`='$gst',`return_qty`='$returnQty',`refund_amount`='$refundAmount',`added_by`='$addedBy' WHERE `id`='$id' AND `stock_return_id`='$stockReturnId' ";

        $res = $this->conn->query($update);

        return $res;
    }


    // ---------------- stock return detaisl edit -----------------
    function stockReturnDetailsEditByStockInDetailsId($stockInDetailsId, $productId, $batchNo, $expDate, $unit, $purchaseQty, $freeQty, $mrp, $ptr, $discParcent, $gst, $addedBy)
    {

        try {
            // Construct the SQL query with placeholders
            $update = "UPDATE `stock_return_details` SET `product_id`=?, `batch_no`=?, `exp_date`=?, `unit`=?, `purchase_qty`=?, `free_qty`=?, `mrp`=?, `ptr`=?, `disc`=?, `gst`=?, `added_by`=? WHERE `stokIn_details_id`=?";

            // Prepare the SQL statement
            $statement = $this->conn->prepare($update);
            if (!$statement) {
                throw new Exception("Error preparing update statement: " . $this->conn->error);
            }

            // Bind the parameters
            $statement->bind_param("ssssssssssss", $productId, $batchNo, $expDate, $unit, $purchaseQty, $freeQty, $mrp, $ptr, $discParcent, $gst, $addedBy, $stockInDetailsId);

            // Execute the prepared statement
            if ($statement->execute()) {
                // Check if any rows were affected by the update
                $affectedRows = $statement->affected_rows;

                // Return the result based on the affected rows
                return ($affectedRows > 0);
            } else {
                throw new Exception("Error executing update statement: " . $statement->error);
            }
        } catch (Exception $e) {
            return false;
        }
    } // edit on stock in details id ===========


    // ----------------- stock return details edit/update by id ----------------RD-----------
    function stockReturnDetailsEditUpdate($id, $returnQTY, $returnFQTY, $refundAmount, $updatedBy, $updatedOn)
    {

        try {
            $editUpdate = "UPDATE `stock_return_details` SET  `return_qty`= ?, `return_free_qty` =  ?,`refund_amount`= ?,`updated_by`= ?, `updated_on` =  ? WHERE `id`= ?";

            // Prepare the SQL statement
            $statement = $this->conn->prepare($editUpdate);
            if (!$statement) {
                throw new Exception("Error preparing update statement: " . $this->conn->error);
            }

            // Bind the parameters
            $statement->bind_param("iiidss", $returnQTY, $returnFQTY, $refundAmount, $updatedBy, $updatedOn, $id);

            // Execute the prepared statement
            if ($statement->execute()) {
                // Check if any rows were affected by the update
                $affectedRows = $statement->affected_rows;
                // Return the result based on the affected rows
                return ($affectedRows > 0);
            } else {
                throw new Exception("Error executing update statement: " . $statement->error);
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // =====

    

    //  purchaser return report function
    function gstPurchaseReturnDetailsReport($gstBasedFilter, $startDate, $endDate, $adminId) {
        try {
            $data = array();
            
            // Base query
            $selectQuery = "SELECT 
                                DATE(sr.return_date) AS bill_date,
                                DATE(sr.added_on) AS added_on,
                                srd.dist_bill_no AS bill_no,
                                d.name AS dist_name,
                                p.name AS item_name,
                                srd.refund_amount AS total_paid_on_item,
                                srd.gst AS total_gst_percent,
                                ROUND(srd.refund_amount - (srd.refund_amount / (1 + (srd.gst / 100))), 2) AS total_gst_amount
                            FROM 
                                stock_return_details srd
                            JOIN
                                stock_return sr ON sr.id = srd.stock_return_id 
                            JOIN
                                products p ON p.product_id = srd.product_id
                            JOIN
                                distributor d ON d.id = sr.distributor_id 
                            WHERE
                                DATE(sr.added_on) BETWEEN ? AND ?
                                AND sr.admin_id = ?";
        
            // Add condition for GST filter
            if ($gstBasedFilter == 0) {
                $selectQuery .= " AND srd.gst = 0";
            }
        
            $stmt = $this->conn->prepare($selectQuery);
        
            if ($stmt === false) {
                throw new Exception("Error preparing the query: " . $this->conn->error);
            }
        
            // Bind parameters
            $stmt->bind_param("sss", $startDate, $endDate, $adminId);
        
            $stmt->execute();
        
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                $returnData = ['status' => '1', 'data' => $data];
            } else {
                $returnData = ['status' => '0', 'data' => []];
            }
        
            // Close the statement
            $stmt->close();
            return json_encode($returnData);
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'error' => $e->getMessage()]);
        }
    }
    




    // ---------------------- STOCK RETURN DETAILS ITEMS DELETE -----------------------------

    function delteStockReturnDetailsbyReturnId($stockReturnedId)
    {
        $delQuary = "DELETE FROM `stock_return_details` WHERE `stock_return_id`='$stockReturnedId'";
        $delByReturnId = $this->conn->query($delQuary);
        return $delByReturnId;
    }

    function delteStockReturnDetailsbyItemId($stockReturnDetailsId)
    {
        $delQuary = "DELETE FROM `stock_return_details` WHERE `id`='$stockReturnDetailsId'";
        $delbyId = $this->conn->query($delQuary);
        return $delbyId;
    }

    function deleteStockByTableData($table, $data)
    {
        $delQuary = "DELETE FROM `stock_return_details` WHERE `$table`='$data'";
        $delbyId = $this->conn->query($delQuary);
        return $delbyId;
    }



    /// stock return audit fucntion
    function stockReturnAuditFunction($startDate, $endDate, $groupBy, $admin) {
        try {
            switch ($groupBy) {
                case 'year':
                    $groupByClause = "YEAR(sr.return_date)";
                    $dateFormat = "YEAR(sr.return_date)";
                    $dataOrder = "$groupByClause ASC";
                    break;
                case 'month':
                    $groupByClause = "YEAR(sr.return_date), MONTH(sr.return_date)";
                    $dateFormat = "DATE_FORMAT(sr.return_date, '%b %Y')"; 
                    $dataOrder = "$groupByClause ASC";
                    break;
                case 'date':
                default:
                    $groupByClause = "DATE(sr.return_date)";
                    $dateFormat = "DATE_FORMAT(sr.return_date, '%d %b %Y')"; 
                    $dataOrder = "$groupByClause ASC";
                    break;
            }
    
            $query = "SELECT
                          $dateFormat AS rtn_dt,
                          COUNT(sr.distributor_id) AS rtn_count,
                          SUM(sr.refund_amount) AS rtn_amount
                      FROM 
                          stock_return sr
                      WHERE
                          sr.return_date BETWEEN ? AND ?
                          AND sr.admin_id = ?
                      GROUP BY
                          $groupByClause
                      ORDER BY
                          $dataOrder";
    
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Error preparing the query: " . $this->conn->error);
            }
    
            $stmt->bind_param('sss', $startDate, $endDate, $admin);
            $stmt->execute();
    
            $result = $stmt->get_result();
            $data = [];
    
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                return json_encode(['status' => true, 'data' => $data]);
            } else {
                return json_encode(['status' => false, 'message' => 'No data found!', 'data' => []]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error -> ' . $e->getMessage()]);
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $this->conn->close();
        }
    }
    
}
