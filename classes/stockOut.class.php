<?php

/**
 * USED IN
 * 
 * 
 * NEEDS TO FIX
 * stockOutByPatientIdForChart AND stockOutByPatientId function are operating same perform
 */

class StockOut
{
    use DatabaseConnection;

    function addStockOut($invoiceId, $customerId, $reffBy, $items, $qty, $mrp, $disc, $gst, $amount, $paymentMode, $status, $billDate, $addedBy, $addedOn, $adminId)
    {
        try {
            $insertBill = "INSERT INTO stock_out (`invoice_id`, `customer_id`, `reff_by`, `items`, `qty`, `mrp`, `disc`, `gst`, `amount`, `payment_mode`, `status`, `bill_date`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($insertBill);
            $stmt->bind_param("sssiidsddssssss", $invoiceId, $customerId, $reffBy, $items, $qty, $mrp, $disc, $gst, $amount, $paymentMode, $status, $billDate, $addedBy, $addedOn, $adminId);

            if ($stmt->execute()) {
                $stmt->close();
                return array("success" => true);
            } else {

                throw new Exception("Error inserting data into the database: " . $stmt->error);
            }
        } catch (Exception $e) {
            return array("success" => false, "error" => "Error: " . $e->getMessage());
        }
    }





    function stockOutDisplay($adminId = '')
    {
        try {
            $billData = array();
            if (!empty($adminId)) {
                $selectBill = "SELECT * FROM `stock_out` WHERE admin_id = ?
                ORDER BY invoice_id DESC";
                $stmt = $this->conn->prepare($selectBill);
                $stmt->bind_param("s", $adminId);
            } else {
                $selectBill = "SELECT * FROM `stock_out` 
                ORDER BY invoice_id DESC";
                $stmt = $this->conn->prepare($selectBill);
            }

            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_array()) {
                        $billData[] = $row;
                    }

                    return $billData;
                } else {
                    return array();
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }






    function stockOutSearch($searchVal = '', $startDate = '', $endDate = '', $paymentMode = '', $adminId = '')
    {
        try {
            // Base query
            $searchSQL = "SELECT * FROM stock_out WHERE 1=1";
            $params = array();
            $types = '';

            // Adding search conditions
            if (!empty($searchVal)) {
                $searchSQL .= " AND (invoice_id LIKE '$searchVal' OR customer_id IN (SELECT patient_id FROM patient_details WHERE name LIKE '$searchVal') OR amount LIKE '$searchVal' OR bill_date LIKE '$searchVal' OR customer_id IN (SELECT patient_id FROM patient_details WHERE phno LIKE '$searchVal'))";
            }

            // Adding date range condition
            if (!empty($startDate) && !empty($endDate)) {
                $searchSQL .= " AND DATE(added_on) BETWEEN STR_TO_DATE('$startDate', '%d-%m-%Y') AND STR_TO_DATE('$endDate', '%d-%m-%Y')";
            }

            // Adding payment mode condition
            if (!empty($paymentMode)) {
                $searchSQL .= " AND payment_mode = '$paymentMode'";
            }

            // Adding admin ID condition
            if (!empty($adminId)) {
                $searchSQL .= " AND admin_id = '$adminId'";
            }

            // Prepare statement
            $stmt = $this->conn->prepare($searchSQL);
            if (!$stmt) {
                throw new Exception('Statement preparation exception: ' . $this->conn->error);
            }

            // Bind parameters dynamically
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            // Execute statement
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch data
            if ($result->num_rows > 0) {
                $salseData = array();
                while ($row = $result->fetch_object()) {
                    $salseData[] = $row;
                }
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $salseData]);
            } else {
                return json_encode(['status' => '0', 'message' => 'No data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => 'Error: ' . $e->getMessage(), 'data' => '']);
        }
    }









    ///== Stock out display by patient ID===//
    function stockOutByPatientId($patientId)
    {
        try {
            $data = [];
            $stmt = $this->conn->prepare("SELECT * FROM `stock_out` WHERE `customer_id` = ? ORDER BY `invoice_id` DESC");
            $stmt->bind_param("s", $patientId); // "s" denotes that the parameter is a string
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_object()) {
                $data[] = $row;
            }
            $stmt->close();
            return json_encode($data);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }




/**
 * USED IN
 * 1. Patient Details Page
 */
    function stockOutByPatientIdForChart($patientId) {
        try {
            $data = [];
    
            // Sanitize the input variable
            $patientId = intval($patientId); // Ensures $patientId is an integer
    
            // Prepare SQL statement with parameter binding
            $sql = "SELECT * FROM `stock_out` WHERE `customer_id` = ? ORDER BY invoice_id DESC";
            $stmt = $this->conn->prepare($sql);
    
            // Bind the parameter and execute the statement
            $stmt->bind_param("s", $patientId); // 'i' specifies an integer parameter
            $stmt->execute();
    
            // Fetch the results
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                return json_encode(['status' => true, 'data' => $data]);
            } else {
                return json_encode(['status' => false, 'message' => 'No data found for this patient.']);
            }
    
        } catch (Exception $e) {
            return json_encode(['status' => false, 'error' => $e->getMessage()]);
        }
    }
    





    // fethc sold amount on admin id
    function amountSoldByAll($adminId = '')
    {
        try {
            $sold = array();
            if (!empty($adminId)) {
                $sql = "SELECT items,amount FROM stock_out WHERE `admin_id` = '$adminId'";
            } else {
                $sql = "SELECT items,amount FROM stock_out";
            }
            $sqlQuery = $this->conn->query($sql);

            if ($sqlQuery) {
                while ($result = $sqlQuery->fetch_array()) {
                    $sold[] = $result;
                }
            } else {
                throw new Exception("Error executing SQL query");
            }

            return $sold;
        } catch (Exception $e) {
            // Handle the exception here, e.g., log the error or return an empty array
            error_log("Error: " . $e->getMessage());
            return array(); // Return an empty array in case of an error
        }
    }






    function stokOutDataByTwoCol($table1, $data1, $table2, $data2)
    {
        try {
            $stockOutSelect = array();

            // Define the SQL query using a prepared statement
            $selectSql = "SELECT * FROM `stock_out` WHERE $table1 = ? AND $table2 = ?";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($selectSql);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ss", $data1, $data2);

                // Execute the query
                $stmt->execute();

                // Get the result
                $result = $stmt->get_result();

                // Check if the query was successful
                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $stockOutSelect[] = $row;
                    }
                } else {
                    // Handle the case where the query failed
                    echo "Query failed: " . $this->conn->error;
                }

                // Close the statement
                $stmt->close();
            } else {
                // Handle the case where the statement preparation failed
                echo "Statement preparation failed: " . $this->conn->error;
            }

            return $stockOutSelect;
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // You can customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return array();
        }
    }






    function updateStockOut($invoiceId, $customerId, $reffBy, $itemsNo, $qty, $mrp, $disc, $gst, $amount, $paymentMode, $billDate, $updatedBy, $updatedOn)
    {
        try {
            $updateBill = "UPDATE stock_out SET `customer_id` = ?, `reff_by` = ?, `items` = ?, `qty` = ?, `mrp` = ?, `disc` = ?, `gst` = ?, `amount` = ?, `payment_mode` = ?, `bill_date` = ?, `updated_by` = ?, `updated_on` = ? WHERE `invoice_id` = ?";
            $stmt = $this->conn->prepare($updateBill);

            if ($stmt) {
                $stmt->bind_param("ssiiddddssssi", $customerId, $reffBy, $itemsNo, $qty, $mrp, $disc, $gst, $amount, $paymentMode, $billDate, $updatedBy, $updatedOn, $invoiceId);
                $updateBillQuery = $stmt->execute();
                $stmt->close();
                return $updateBillQuery;
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // You can customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return false;
        }
    }




    // =================== sales of the day data ==================

    // salse of the day in a specific date function
    function salesOfTheDay($addedOn, $adminId)
    {
        try {
            $select = "SELECT SUM(amount) AS total_amount, SUM(items) AS total_count
            FROM stock_out
            WHERE DATE(added_on) = ? AND admin_id = ?";

            $stmt = $this->conn->prepare($select);

            if ($stmt) {
                $stmt->bind_param("ss", $addedOn, $adminId);
                $stmt->execute(); // Corrected here
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $data = $row;
                    }
                    return $data;
                } else {
                    return null;
                }
                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }



    // salse of the day in a range function
    function salesOfTheDayRange($strtDt, $endDt, $adminId)
    {
        try {
            $select = "SELECT SUM(amount) AS total_amount, SUM(items) AS total_count
            FROM stock_out
            WHERE DATE(added_on) BETWEEN ? AND ? 
            AND admin_id = ?";

            $stmt = $this->conn->prepare($select);

            if ($stmt) {
                $stmt->bind_param("sss", $strtDt, $endDt, $adminId);
                $stmt->execute(); // Corrected here
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $data = $row;
                    }
                    return $data;
                } else {
                    return null;
                }
                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function customerDayRange($strtDt, $endDt, $adminId)
    {
        try {
            $select = "SELECT * FROM stock_out WHERE DATE(added_on) BETWEEN ? AND ? AND admin_id = ?";

            $stmt = $this->conn->prepare($select);

            if ($stmt) {
                $stmt->bind_param("sss", $strtDt, $endDt, $adminId);
                $stmt->execute(); // Corrected here
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }
                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function customerOnDay($onDt, $adminId)
    {
        try {
            $select = "SELECT * FROM stock_out WHERE DATE(added_on) LIKE ? AND admin_id = ?";

            $stmt = $this->conn->prepare($select);

            if ($stmt) {
                $stmt->bind_param("ss", $onDt, $adminId); // Remove the extra space here
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }
                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }




    // =================== end of sales of the day data ==================

    // =========== most visited customer data ====================


    function mostVistedCustomerFrmStart($adminId = '') //overall most visited customer function
    {
        try {
            if (!empty($adminId)) {
                $selectQuery = "SELECT customer_id, COUNT(customer_id) AS visit_count
            FROM stock_out
            WHERE admin_id = ?
            AND added_on >= (SELECT MIN(added_on) FROM stock_out WHERE admin_id = ?) 
            AND added_on <= NOW()
            GROUP BY customer_id
            ORDER BY visit_count DESC
            LIMIT 10";

                $stmt = $this->conn->prepare($selectQuery);
                $stmt->bind_param("ss", $adminId, $adminId);
            } else {
                $selectQuery = "SELECT customer_id, COUNT(customer_id) AS visit_count
            FROM stock_out
            WHERE added_on >= (SELECT MIN(added_on) FROM stock_out) 
            AND added_on <= NOW()
            GROUP BY customer_id
            ORDER BY visit_count DESC
            LIMIT 10";

                $stmt = $this->conn->prepare($selectQuery);
            }
            if ($stmt) {
                // $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return null;
    }




    function mostVisitCustomersByDay($adminId = '') //current date most visited customer function
    {
        try {
            if (!empty($adminId)) {
                $selectQuery = "SELECT customer_id, COUNT(*) AS visit_count
            FROM stock_out
            WHERE admin_id = ? AND DATE(added_on) = CURDATE()
            GROUP BY customer_id
            ORDER BY visit_count DESC
            LIMIT 10";

                $stmt = $this->conn->prepare($selectQuery);
                $stmt->bind_param("s", $adminId);
            } else {
                $selectQuery = "SELECT customer_id, COUNT(*) AS visit_count
                    FROM stock_out
                    WHERE DATE(added_on) = CURDATE()
                    GROUP BY customer_id
                    ORDER BY visit_count DESC
                    LIMIT 10";

                $stmt = $this->conn->prepare($selectQuery);
            }
            if ($stmt) {
                // $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }




    function mostVisitCustomersByWeek($adminId = '') //last week most visited customer function
    {
        try {
            if (!empty($adminId)) {
                $selectQuery = "SELECT customer_id, COUNT(*) AS visit_count
            FROM stock_out
            WHERE admin_id = ? 
            AND added_on >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY customer_id
            ORDER BY visit_count DESC
            LIMIT 10";

                $stmt = $this->conn->prepare($selectQuery);
                $stmt->bind_param("s", $adminId);
            } else {
                $selectQuery = "SELECT customer_id, COUNT(*) AS visit_count
            FROM stock_out
            WHERE added_on >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY customer_id
            ORDER BY visit_count DESC
            LIMIT 10";

                $stmt = $this->conn->prepare($selectQuery);
            }
            if ($stmt) {
                // $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }




    function mostVisitCustomersByMonth($adminId = '') //lst 30 days most visited customer function
    {
        try {
            if (!empty($adminId)) {
                $selectQuery = "SELECT customer_id, COUNT(*) AS visit_count
            FROM stock_out
            WHERE admin_id = ? 
            AND added_on >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY customer_id
            ORDER BY visit_count DESC
            LIMIT 10";

                $stmt = $this->conn->prepare($selectQuery);
                $stmt->bind_param("s", $adminId);
            } else {
                $selectQuery = "SELECT customer_id, COUNT(*) AS visit_count
                FROM stock_out
                WHERE added_on >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY customer_id
                ORDER BY visit_count DESC
                LIMIT 10";

                $stmt = $this->conn->prepare($selectQuery);
            }

            if ($stmt) {
                // $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }





    function mostVisitedCustomerOnDate($adminId, $date) // most visited customer by date function
    {
        try {
            $selectQuery = "SELECT customer_id, COUNT(*) AS visit_count
            FROM stock_out
            WHERE admin_id = ?
            AND DATE(added_on) = ?
            GROUP BY customer_id
            ORDER BY visit_count DESC
            LIMIT 10";

            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt) {
                $stmt->bind_param("ss", $adminId, $date);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }





    // most visited customer by date range function
    function mostVisitCustomersByDateRange($startDt, $endDate, $adminId)
    {
        try {
            $selectQuery = "SELECT customer_id, COUNT(*) AS visit_count
            FROM stock_out
            WHERE admin_id = ?
            AND DATE(added_on) BETWEEN ? AND ?
            GROUP BY customer_id
            ORDER BY visit_count DESC
            LIMIT 10";

            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt) {
                $stmt->bind_param("sss", $adminId, $startDt, $endDate);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }


    // ============= end of most visited customer functions ===============


    // ============= most purchase customer data functions ==============

    function overallMostPurchaseCustomer($admin = '') // overall most purchase customer fucntion
    {
        // echo "$admin";
        try {
            if (!empty($admin)) {
                // echo "<br>inside if : $admin";
                $selectQuery = "SELECT customer_id, amount AS total_purchase
                            FROM stock_out
                            WHERE admin_id = ? AND customer_id = 'Cash Sales'
                            UNION ALL
                            SELECT customer_id, SUM(amount) AS total_purchase
                            FROM stock_out
                            WHERE admin_id = ? AND customer_id != 'Cash Sales'
                            GROUP BY customer_id
                            ORDER BY total_purchase DESC
                            LIMIT 10;";

                $stmt = $this->conn->prepare($selectQuery);
                $stmt->bind_param("ss", $admin, $admin);
            } else {
                // echo "<br>inside else : $admin";
                $selectQuery = "SELECT customer_id, amount AS total_purchase
                            FROM stock_out
                            WHERE  customer_id = 'Cash Sales'
                            UNION ALL
                            SELECT customer_id, SUM(amount) AS total_purchase
                            FROM stock_out
                            WHERE customer_id != 'Cash Sales'
                            GROUP BY customer_id
                            ORDER BY total_purchase DESC
                            LIMIT 10;";

                $stmt = $this->conn->prepare($selectQuery);
            }

            if ($stmt) {
                // $stmt->bind_param("ss", $admin, $admin);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $data = array();
                        while ($row = $result->fetch_object()) {
                            $data[] = $row;
                        }
                        return $data;
                    } else {
                        return null;
                    }

                    $stmt->close();
                } else {
                    echo "Statement execution failed: " . $stmt->error;
                }
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return null;
    }





    function mostPurchaseCustomerByDay($admin = '') // most purchase customer on current date fucntion
    {
        try {
            if (!empty($adminId)) {
                $selectQuery = "SELECT customer_id, amount AS total_purchase
                            FROM stock_out
                            WHERE admin_id = ? AND customer_id = 'Cash Sales' 
                                AND DATE(added_on) = CURDATE()
                            UNION ALL
                            SELECT customer_id, SUM(amount) AS total_purchase
                            FROM stock_out
                            WHERE admin_id = ? AND customer_id != 'Cash Sales' 
                                AND DATE(added_on) = CURDATE()
                            GROUP BY customer_id
                            ORDER BY total_purchase DESC
                            LIMIT 10";

                $stmt = $this->conn->prepare($selectQuery);
                $stmt->bind_param("ss", $admin, $admin);
            } else {
                $selectQuery = "SELECT customer_id, amount AS total_purchase
                            FROM stock_out
                            WHERE customer_id = 'Cash Sales' 
                                AND DATE(added_on) = CURDATE()
                            UNION ALL
                            SELECT customer_id, SUM(amount) AS total_purchase
                            FROM stock_out
                            WHERE customer_id != 'Cash Sales' 
                                AND DATE(added_on) = CURDATE()
                            GROUP BY customer_id
                            ORDER BY total_purchase DESC
                            LIMIT 10";

                $stmt = $this->conn->prepare($selectQuery);
            }
            if ($stmt) {
                // $stmt->bind_param("ss", $admin, $admin);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $data = array();
                        while ($row = $result->fetch_object()) {
                            $data[] = $row;
                        }
                        return $data;
                    } else {
                        return null;
                    }

                    $stmt->close();
                } else {
                    echo "Statement execution failed: " . $stmt->error;
                }
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return null;
    }




    function mostPurchaseCustomerByWeek($admin = '') // most purchase customer last week fucntion
    {
        try {
            if (!empty($adminId)) {
                $selectQuery = "SELECT customer_id, amount AS total_purchase
                            FROM stock_out
                            WHERE admin_id = ? AND customer_id = 'Cash Sales' 
                            AND added_on >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                            UNION ALL
                            SELECT customer_id, SUM(amount) AS total_purchase
                            FROM stock_out
                            WHERE admin_id = ? AND customer_id != 'Cash Sales' 
                            AND added_on >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                            GROUP BY customer_id
                            ORDER BY total_purchase DESC
                            LIMIT 10";

                $stmt = $this->conn->prepare($selectQuery);
                $stmt->bind_param("ss", $admin, $admin);
            } else {
                $selectQuery = "SELECT customer_id, amount AS total_purchase
                FROM stock_out
                WHERE customer_id = 'Cash Sales' 
                AND added_on >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                UNION ALL
                SELECT customer_id, SUM(amount) AS total_purchase
                FROM stock_out
                WHERE customer_id != 'Cash Sales' 
                AND added_on >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY customer_id
                ORDER BY total_purchase DESC
                LIMIT 10";

                $stmt = $this->conn->prepare($selectQuery);
            }
            if ($stmt) {
                // $stmt->bind_param("ss", $admin, $admin);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $data = array();
                        while ($row = $result->fetch_object()) {
                            $data[] = $row;
                        }
                        return $data;
                    } else {
                        return null;
                    }

                    $stmt->close();
                } else {
                    echo "Statement execution failed: " . $stmt->error;
                }
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return null;
    }






    function mostPurchaseCustomerByMonth($admin = '') // most purchase customer last 30 days fucntion
    {
        // echo "before if : $admin<br>";
        try {
            if (!empty($admin)) {
                // echo $admin;
                // echo '<br>1';
                $selectQuery = "SELECT customer_id, amount AS total_purchase
                            FROM stock_out
                            WHERE admin_id = ? AND customer_id = 'Cash Sales' 
                            AND added_on >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                            UNION ALL
                            SELECT customer_id, SUM(amount) AS total_purchase
                            FROM stock_out
                            WHERE admin_id = ? AND customer_id != 'Cash Sales' 
                            AND added_on >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                            GROUP BY customer_id
                            ORDER BY total_purchase DESC
                            LIMIT 10";

                $stmt = $this->conn->prepare($selectQuery);
                $stmt->bind_param("ss", $admin, $admin);
            } else {
                // echo "else admin id : $admin";
                $selectQuery = "SELECT customer_id, amount AS total_purchase
                FROM stock_out
                WHERE customer_id = 'Cash Sales' 
                AND added_on >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                UNION ALL
                SELECT customer_id, SUM(amount) AS total_purchase
                FROM stock_out
                WHERE customer_id != 'Cash Sales' 
                AND added_on >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY customer_id
                ORDER BY total_purchase DESC
                LIMIT 10";

                $stmt = $this->conn->prepare($selectQuery);
            }

            // print_r($stmt);
            // echo "<br>select query : $selectQuery";
            if ($stmt) {
                // $stmt->bind_param("ss", $admin, $admin);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $data = array();
                        while ($row = $result->fetch_object()) {
                            $data[] = $row;
                        }
                        return $data;
                    } else {
                        return null;
                    }

                    $stmt->close();
                } else {
                    echo "Statement execution failed: " . $stmt->error;
                }
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return null;
    }






    function mostPurchaseCustomerByDate($adminId, $date)
    { // most purchase customer by date fucntion
        try {
            $selectQuery = "SELECT customer_id, amount AS total_purchase
                            FROM stock_out
                            WHERE admin_id = ? AND customer_id = 'Cash Sales' 
                            AND DATE(added_on) = ?
                            UNION ALL
                            SELECT customer_id, SUM(amount) AS total_purchase
                            FROM stock_out
                            WHERE admin_id = ? AND customer_id != 'Cash Sales' 
                            AND DATE(added_on) = ?
                            GROUP BY customer_id
                            ORDER BY total_purchase DESC
                            LIMIT 10";

            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt) {
                $stmt->bind_param("ssss", $adminId, $date, $adminId, $date);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }





    // most purchase customer by date range fucntion
    function mostPurchaseCustomerByDateRange($startDate, $endDate, $adminId)
    {
        try {
            $selectQuery = "SELECT customer_id, amount AS total_purchase
                            FROM stock_out
                            WHERE admin_id = ? AND customer_id = 'Cash Sales' 
                            AND DATE(added_on) BETWEEN ? AND ?
                            UNION ALL
                            SELECT customer_id, SUM(amount) AS total_purchase
                            FROM stock_out
                            WHERE admin_id = ? AND customer_id != 'Cash Sales' 
                            AND DATE(added_on) BETWEEN ? AND ?
                            GROUP BY customer_id
                            ORDER BY total_purchase DESC
                            LIMIT 10";

            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt) {
                $stmt->bind_param("ssssss", $adminId, $startDate, $endDate, $adminId, $startDate, $endDate);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }




    // =================== end of most purchase customer data ==============



    function stockOutDisplayById($invoiceId)
    {
        $billData = array();
        $selectBill = "SELECT * FROM stock_out WHERE `invoice_id` = '$invoiceId'";
        // echo $selectBill.$this->conn->error;
        $billQuery = $this->conn->query($selectBill);
        while ($result = $billQuery->fetch_array()) {
            $billData[]    = $result;
        }
        return $billData;
    } // eof stockOutDisplayById 




    // fethc sold amount on admin id and employee id
    function amountSoldByEmployee($pharmacist = '', $adminId = '')
    {
        $sold = array();
        if (!empty($adminId)) {
            $sql = "SELECT items,amount FROM stock_out WHERE `added_by` = '$pharmacist' AND `admin_id` = '$adminId'";
        } else {
            $sql = "SELECT items,amount FROM stock_out ";
        }
        $sqlQuery = $this->conn->query($sql);
        while ($result = $sqlQuery->fetch_array()) {
            $sold[]    = $result;
        }
        return $sold;
    } // eof amountSoldBy



    function soldByDate($billFromDate, $billToDate)
    {
        $data = array();
        $sql = "SELECT items,amount FROM stock_out WHERE `stock_out`.`added_on` BETWEEN $billFromDate AND $billToDate";
        $sqlQuery = $this->conn->query($sql);
        while ($result = $sqlQuery->fetch_array()) {
            $data[]    = $result;
        }
        return $data;
    } // eof amountSoldBy


    function needsToCollect($adminId = '')
    {
        $data = array();

        try {
            if (!empty($adminId)) {
                // Use prepared statements to prevent SQL injection
                $sql = "SELECT * FROM stock_out WHERE `payment_mode` = 'Credit' AND admin_id = ?";
            } else {
                $sql = "SELECT * FROM stock_out WHERE `payment_mode` = 'Credit'";
            }

            $stmt = $this->conn->prepare($sql);

            if ($stmt) {
                if (!empty($adminId)) {
                    // Bind parameters
                    $stmt->bind_param('s', $adminId); // Assuming admin_id is a string
                }

                // Execute the prepared statement
                $stmt->execute();

                // Get the result set
                $result = $stmt->get_result();

                // Fetch results into an array
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                // Close the statement
                $stmt->close();
            } else {
                throw new Exception("Error in preparing SQL statement");
            }
        } catch (Exception $e) {
            return array("error" => $e->getMessage());
        }

        return $data;
    }





    function selectStockOutDataOnDateFilter($startDate, $endDate, $adminId)
    {
        try {
            $selectSalesData = "SELECT COUNT(invoice_id) AS invoice_count, SUM(amount) 
                                AS sell_amount, DATE(added_on) AS sell_date 
                                FROM stock_out 
                                WHERE DATE(added_on) BETWEEN ? AND ? 
                                AND `admin_id` = ? GROUP BY DATE(added_on)";

            $stmt = $this->conn->prepare($selectSalesData);

            if (!$stmt) {
                throw new Exception("Error in preparing SQL statement: " . $this->conn->error);
            }

            $stmt->bind_param("sss", $startDate, $endDate, $adminId);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                $stmt->close();
                return $data;
            } else {
                $stmt->close();
                return null;
            }
        } catch (Exception $e) {
            echo "error" . $e->getMessage();
        }
    }








    function cancelLabBill($billId, $status)
    {
        $cancelBill = "UPDATE `stock_out` SET `status` = '$status' WHERE `stock_out`.`bill_id` = '$billId'";
        $cancelBillQuery = $this->conn->query($cancelBill);
        return $cancelBillQuery;
    } //end cancelLabBill function







    // sales report on item category
    function stockOutReportOnItemCategory($additionalFilter1, $groupFilter, $searchOnData, $startDate, $endDate, $adminId)
    {
        // echo $startDate;
        // echo $endDate;

        $sqlPart1 = "SELECT p.type AS category_name,";

        $sqlPart1b = "DATE(so.bill_date) AS bil_dt,";

        $sqlPart2a = "DATE(so.added_on) AS added_on, 
                  MIN(DATE(so.added_on)) AS start_date,
                  MAX(DATE(so.added_on)) AS end_date,";
        $sqlPart2b = "DATE(DATE_SUB(so.added_on, INTERVAL WEEKDAY(so.added_on) DAY)) AS start_date, 
                  DATE(DATE_ADD(DATE_SUB(so.added_on, INTERVAL WEEKDAY(so.added_on) DAY), INTERVAL 6 DAY)) AS end_date,";
        $sqlPart2c = "YEAR(so.added_on) AS year, 
                  MONTH(so.added_on) AS month,
                  MIN(DATE(so.added_on)) AS start_date,
                  MAX(DATE(so.added_on)) AS end_date,";

        $sqlPart3 = "ROUND(SUM(sod.amount), 2) AS total_stock_out_amount,
        
                 ROUND(SUM(sod.sales_margin), 2) AS total_sales_margin,
                 ROUND(SUM(so.disc), 2) as total_discount
                 FROM 
                    stock_out so
                 JOIN 
                    stock_out_details sod ON so.invoice_id = sod.invoice_id
                 JOIN 
                    products p ON sod.product_id = p.product_id
                 WHERE 
                    so.admin_id = ?
                    AND DATE(so.added_on) BETWEEN ? AND ?
                    AND p.type IN ($searchOnData)
                 GROUP BY 
                    p.type,";

        $sqlPart4a = "DATE(so.added_on) 
                  ORDER BY DATE(so.added_on), p.type";
        $sqlPart4b = "start_date 
                  ORDER BY start_date, p.type";
        $sqlPart4c = "YEAR(so.added_on), 
                  MONTH(so.added_on)
                  ORDER BY 
                  YEAR(so.added_on), MONTH(so.added_on), p.type";

        if($additionalFilter1 == 1){
            if ($groupFilter == 0) {
                $sqlQuery = $sqlPart1. $sqlPart1b . $sqlPart2a . $sqlPart3 . $sqlPart4a;
            } elseif ($groupFilter == 1) {
                $sqlQuery = $sqlPart1. $sqlPart1b . $sqlPart2b . $sqlPart3 . $sqlPart4b;
            } elseif ($groupFilter == 2) {
                $sqlQuery = $sqlPart1. $sqlPart1b . $sqlPart2c . $sqlPart3 . $sqlPart4c;
            } else {
                throw new Exception("Invalid group filter parameter");
            }
        }else{
            if ($groupFilter == 0) {
                $sqlQuery = $sqlPart1 . $sqlPart2a . $sqlPart3 . $sqlPart4a;
            } elseif ($groupFilter == 1) {
                $sqlQuery = $sqlPart1 . $sqlPart2b . $sqlPart3 . $sqlPart4b;
            } elseif ($groupFilter == 2) {
                $sqlQuery = $sqlPart1 . $sqlPart2c . $sqlPart3 . $sqlPart4c;
            } else {
                throw new Exception("Invalid group filter parameter");
            }
        }

        // print_r($sqlQuery);

        try {
            // Prepare and execute the query
            $stmt = $this->conn->prepare($sqlQuery);
            if (!$stmt) {
                throw new Exception("Error in preparing SQL statement: " . $this->conn->error);
            }

            // Bind the 
            $stmt->bind_param('sss', $adminId, $startDate, $endDate);
           
            $stmt->execute();

            $result = $stmt->get_result();
            // print_r($result);

            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                $returnResult = ['status' => true, 'data' => $data];
            } else {
                $returnResult = ['status' => false];
            }

            $stmt->close();

            return json_encode($returnResult);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }









    // sales report on payment mode
    function stockOutReportOnPaymentMode($additionalFilter1, $groupFilter, $searchOnData, $startDate, $endDate, $adminId)
    {
        $sqlPart1 = "SELECT so.payment_mode,";

        $sqlPart1b = "DATE(so.bill_date) AS bil_dt,";

        $sqlPart2a = "DATE(so.added_on) AS added_on, 
                  MIN(DATE(so.added_on)) AS start_date,
                  MAX(DATE(so.added_on)) AS end_date,";
        $sqlPart2b = "DATE(DATE_SUB(so.added_on, INTERVAL WEEKDAY(so.added_on) DAY)) AS start_date, 
                  DATE(DATE_ADD(DATE_SUB(so.added_on, INTERVAL WEEKDAY(so.added_on) DAY), INTERVAL 6 DAY)) AS end_date,";
        $sqlPart2c = "YEAR(so.added_on) AS year, 
                  MONTH(so.added_on) AS month,
                  MIN(DATE(so.added_on)) AS start_date,
                  MAX(DATE(so.added_on)) AS end_date,";

        $sqlPart3 = "ROUND(SUM(so.amount), 2) AS total_stock_out_amount,
                 ROUND(SUM(sod.sales_margin), 2) AS total_sales_margin,
                 ROUND(SUM(so.disc), 2) as total_discount
                 FROM 
                    stock_out so
                 JOIN 
                    stock_out_details sod ON so.invoice_id = sod.invoice_id
                 WHERE 
                    so.admin_id = ?
                    AND DATE(so.added_on) BETWEEN ? AND ?
                    AND so.payment_mode IN ($searchOnData)
                 GROUP BY 
                    so.payment_mode,";

        $sqlPart4a = "DATE(so.added_on) 
                  ORDER BY DATE(so.added_on), so.payment_mode";
        $sqlPart4b = "start_date 
                  ORDER BY start_date, so.payment_mode";
        $sqlPart4c = "YEAR(so.added_on), 
                  MONTH(so.added_on)
                  ORDER BY 
                  YEAR(so.added_on), MONTH(so.added_on), so.payment_mode";

                  if($additionalFilter1 == 1){
                    if ($groupFilter == 0) {
                        $sqlQuery = $sqlPart1. $sqlPart1b . $sqlPart2a . $sqlPart3 . $sqlPart4a;
                    } elseif ($groupFilter == 1) {
                        $sqlQuery = $sqlPart1. $sqlPart1b . $sqlPart2b . $sqlPart3 . $sqlPart4b;
                    } elseif ($groupFilter == 2) {
                        $sqlQuery = $sqlPart1. $sqlPart1b . $sqlPart2c . $sqlPart3 . $sqlPart4c;
                    } else {
                        throw new Exception("Invalid group filter parameter");
                    }
                }else{
                    if ($groupFilter == 0) {
                        $sqlQuery = $sqlPart1 . $sqlPart2a . $sqlPart3 . $sqlPart4a;
                    } elseif ($groupFilter == 1) {
                        $sqlQuery = $sqlPart1 . $sqlPart2b . $sqlPart3 . $sqlPart4b;
                    } elseif ($groupFilter == 2) {
                        $sqlQuery = $sqlPart1 . $sqlPart2c . $sqlPart3 . $sqlPart4c;
                    } else {
                        throw new Exception("Invalid group filter parameter");
                    }
                }

        // echo $sqlQuery;

        try {
            // Prepare and execute the query
            $stmt = $this->conn->prepare($sqlQuery);
            if (!$stmt) {
                throw new Exception("Error in preparing SQL statement: " . $this->conn->error);
            }

            // Bind the parameters
            $stmt->bind_param('sss', $adminId, $startDate, $endDate);

            $stmt->execute();

            $result = $stmt->get_result();
            // print_r($result);
            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                $returnResult = ['status' => true, 'data' => $data];
            } else {
                $returnResult = ['status' => false];
            }

            $stmt->close();

            return json_encode($returnResult);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }





    // sales report function on addedBy
    function stockOutReportOnAddedBy($additionalFilter1, $groupFilter, $searchOnData, $startDate, $endDate, $adminId)
    {
        $sqlPart1 = "SELECT COALESCE(adm.username, emp.emp_username) AS added_by_name,";

        $sqlPart1b = "DATE(so.bill_date) AS bil_dt,";

        $sqlPart2a = "DATE(so.added_on) AS added_on, 
                  MIN(DATE(so.added_on)) AS start_date,
                  MAX(DATE(so.added_on)) AS end_date,";
        $sqlPart2b = "DATE(DATE_SUB(so.added_on, INTERVAL WEEKDAY(so.added_on) DAY)) AS start_date, 
                  DATE(DATE_ADD(DATE_SUB(so.added_on, INTERVAL WEEKDAY(so.added_on) DAY), INTERVAL 6 DAY)) AS end_date,";
        $sqlPart2c = "YEAR(so.added_on) AS year, 
                  MONTH(so.added_on) AS month,
                  MIN(DATE(so.added_on)) AS start_date,
                  MAX(DATE(so.added_on)) AS end_date,";

        $sqlPart3 = "ROUND(SUM(sod.amount), 2) AS total_stock_out_amount,
                 ROUND(SUM(sod.sales_margin), 2) AS total_sales_margin,
                 ROUND(SUM(so.disc), 2) as total_discount
                 FROM 
                    stock_out so
                 JOIN 
                    stock_out_details sod ON so.invoice_id = sod.invoice_id
                 LEFT JOIN 
                    admin adm ON so.added_by = adm.admin_id
                 LEFT JOIN 
                    employees emp ON so.added_by = emp.emp_id
                 WHERE 
                    so.admin_id = ?
                    AND DATE(so.added_on) BETWEEN ? AND ?
                    AND so.added_by IN ($searchOnData)
                 GROUP BY 
                    added_by_name,";

        $sqlPart4a = "DATE(so.added_on) 
                  ORDER BY DATE(so.added_on), added_by_name";
        $sqlPart4b = "start_date 
                  ORDER BY start_date, added_by_name";
        $sqlPart4c = "YEAR(so.added_on), 
                  MONTH(so.added_on)
                  ORDER BY 
                  YEAR(so.added_on), MONTH(so.added_on), added_by_name";

        if($additionalFilter1 == 1){
            if ($groupFilter == 0) {
                $sqlQuery = $sqlPart1. $sqlPart1b . $sqlPart2a . $sqlPart3 . $sqlPart4a;
            } elseif ($groupFilter == 1) {
                $sqlQuery = $sqlPart1. $sqlPart1b . $sqlPart2b . $sqlPart3 . $sqlPart4b;
            } elseif ($groupFilter == 2) {
                $sqlQuery = $sqlPart1. $sqlPart1b . $sqlPart2c . $sqlPart3 . $sqlPart4c;
            } else {
                throw new Exception("Invalid group filter parameter");
            }
        }else{
            if ($groupFilter == 0) {
                $sqlQuery = $sqlPart1 . $sqlPart2a . $sqlPart3 . $sqlPart4a;
            } elseif ($groupFilter == 1) {
                $sqlQuery = $sqlPart1 . $sqlPart2b . $sqlPart3 . $sqlPart4b;
            } elseif ($groupFilter == 2) {
                $sqlQuery = $sqlPart1 . $sqlPart2c . $sqlPart3 . $sqlPart4c;
            } else {
                throw new Exception("Invalid group filter parameter");
            }
        }

        // echo $sqlQuery;

        try {
            // Prepare and execute the query
            $stmt = $this->conn->prepare($sqlQuery);
            if (!$stmt) {
                throw new Exception("Error in preparing SQL statement: " . $this->conn->error);
            }

            // Bind the parameters
            $stmt->bind_param('sss', $adminId, $startDate, $endDate);

            $stmt->execute();

            $result = $stmt->get_result();
            // print_r($result);
            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                $returnResult = ['status' => true, 'data' => $data];
            } else {
                $returnResult = ['status' => false];
            }

            $stmt->close();

            return json_encode($returnResult);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    // stock out on payment mode report function 
    function salesOnPaymentMode($filterOn, $startDate, $endDate, $admin)
    {
        try {
            $data = array();

            // Base query
            $selectQuery = "SELECT 
                                so.invoice_id AS bill_no,
                                so.bill_date AS bill_date,
                                DATE(so.added_on) AS added_on,
                                CASE 
                                    WHEN so.customer_id = 'Cash Sales' THEN 'Cash Sales'
                                    ELSE pd.name
                                END AS patient_name,
                                CASE 
                                    WHEN so.customer_id = 'Cash Sales' THEN 'Cash Sales'
                                    ELSE pd.phno
                                END AS patient_contact,
                                so.items AS total_items,
                                so.amount AS amount,
                                so.gst AS gst_amount
                            FROM 
                                stock_out so
                            LEFT JOIN
                                patient_details pd ON pd.patient_id = so.customer_id 
                            WHERE
                                DATE(so.added_on) BETWEEN ? AND ?
                                AND so.admin_id = ?";

            // Add condition for payment type filter
            $additionNlaQuery1 = " AND so.payment_mode = 'Credit'";

            // Append additional query condition based on filter
            if ($filterOn == 2) {
                $selectQuery .= $additionNlaQuery1;
            }
            
            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt === false) {
                throw new Exception("Error preparing the query: " . $this->conn->error);
            }

            // Bind parameters
            $stmt->bind_param("sss", $startDate, $endDate, $admin);

            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                $returnData = ['status' => true, 'data' => $data];
            } else {
                $returnData = ['status' => false, 'data' => []];
            }

            // Close the statement
            $stmt->close();
            return json_encode($returnData);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'error' => $e->getMessage()]);
        }
    }



    #######################################################################################################
    #                                                                                                     #
    #                                        STOCK OUT DETAILS                                            #
    #                                                                                                     #
    #######################################################################################################


    function addStockOutDetails($invoiceId, $itemId, $productId, $productName, $batchNo, $expDate, $weightage, $unit, $qty, $looselyCount, $mrp, $ptr, $discount, $gst, $gstAmount, $sMargin, $margin, $taxable, $amount)
    {
        try {
            $addStockOutDetails = $this->conn->prepare("INSERT INTO `stock_out_details`(`invoice_id`, `item_id`, `product_id`, `item_name`, `batch_no`, `exp_date`, `weightage`, `unit`, `qty`, `loosely_count`, `mrp`, `ptr`, `discount`, `gst`, `gst_amount`, `sales_margin`, `profit_margin`, `taxable`, `amount`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            if (!$addStockOutDetails) {
                return false; // Return false on query failure
            }
            $addStockOutDetails->bind_param("iissssssssddssddddd", $invoiceId, $itemId, $productId, $productName, $batchNo, $expDate, $weightage, $unit, $qty, $looselyCount, $mrp, $ptr, $discount, $gst, $gstAmount, $sMargin, $margin, $taxable, $amount);

            if ($addStockOutDetails->execute()) {
                $addStockOutDetails->close();
                return true; // Return true on successful insertion
            } else {
                $addStockOutDetails->close();
                return false; // Return false on execution failure
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo  $e->getMessage();
            return false;
        }
    }





    function updateStockOutDetaislById($id, $qty, $looseQty, $disc, $sellMargin, $margin, $taxable, $gstAmount, $amount, $updatedBy, $updatedOn)
    {
        try {
            $updateQuery = "UPDATE `stock_out_details` SET `qty`=?, `loosely_count`=?, `discount`=?, `sales_margin`=?, `profit_margin`=?, `taxable`=?, `gst_amount`=?, `amount`=?, `updated_by`=?, `updated_on`=? WHERE `id`=?";
            $stmt = $this->conn->prepare($updateQuery);

            if ($stmt) {
                $stmt->bind_param("iiidddddssi", $qty, $looseQty, $disc, $sellMargin, $margin, $taxable, $gstAmount, $amount, $updatedBy, $updatedOn, $id);

                if ($stmt->execute()) {
                    $stmt->close();
                    return true; // Return true on successful insertion
                } else {
                    $stmt->close();
                    return false; // Return false on execution failure
                }
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Handle any exceptions that occur
            // You can customize this part to suit your needs
            echo "Error: " . $e->getMessage();
            return false;
        }
    }





    function stokOutDetailsDataByTwoCol($table1, $data1, $table2, $data2)
    {
        try {
            $stockOutSelect = array();
            $selectSql = "SELECT * FROM `stock_out_details` WHERE $table1 = ? AND $table2 = ?";

            $stmt = $this->conn->prepare($selectSql);

            if ($stmt) {

                $stmt->bind_param("ss", $data1, $data2);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $stockOutSelect[] = $row;
                    }
                } else {
                    echo "Query failed: " . $this->conn->error;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }

            return $stockOutSelect;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return array();
        }
    }




    ///== fint stokOut details by invoice Id ==/// 
    function stockOutDetailsBYinvoiveID($invoiceIds)
    {
        try {

            if (empty($invoiceIds)) {
                return json_encode([]); // Return an empty JSON array if $invoiceIds is empty
            }

            $data = [];
            $invoiceIdsString = implode(',', $invoiceIds);
            $sql = "SELECT * FROM `stock_out_details` WHERE `invoice_id` IN ($invoiceIdsString)";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $data[] = $result;
            }
            $dataset = json_encode($data);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function stockOutDetailsBY1invoiveID($invoiceId)
    {
        try {
            $data = [];
            $sql = "SELECT * FROM `stock_out_details` WHERE `invoice_id`= ' $invoiceId'";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $data[] = $result;
            }
            $dataset = json_encode($data);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }



    function stokOutDetailsDataOnTable($table, $data)
    {
        try {
            $stockOutSelect = array();

            $selectBill = "SELECT * FROM `stock_out_details` WHERE `$table` = ?";

            $stmt = $this->conn->prepare($selectBill);

            if ($stmt) {
                $stmt->bind_param("s", $data);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $stockOutSelect[] = $row;
                    }
                } else {
                    echo "Query failed: " . $this->conn->error;
                }
                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }

            return $stockOutSelect;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return array();
        }
    }








    function updateStockOutDetaisOnStockInEdit($itemId, $batchNo, $expDate, $updatedBy, $updatedOn)
    {
        try {
            // Define the SQL query using a prepared statement
            $updateQuery = "UPDATE `stock_out_details` SET `batch_no`=?, `exp_date`=?, `updated_by`=?, `updated_on`=? WHERE `item_id`=?";
            $stmt = $this->conn->prepare($updateQuery);

            if ($stmt) {
                // Bind the parameters
                $stmt->bind_param("ssssi", $batchNo, $expDate, $updatedBy, $updatedOn, $itemId);

                // Execute the query
                $updateStockOutDetails = $stmt->execute();
                $stmt->close();
                return $updateStockOutDetails;
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





    //  ================== most sold item check query ====================
    //  ===================== most sold less sold data filter function =======================
    function stockOutDataFetchFromStart($adminId, $sortVal)
    {
        try {
            $selectQuery = "SELECT sod.product_id, SUM(sod.qty) AS total_sold
                            FROM stock_out_details sod
                            JOIN stock_out so ON sod.invoice_id = so.invoice_id
                            WHERE so.admin_id = ?
                              AND so.added_on >= (SELECT MIN(added_on) FROM stock_out WHERE admin_id = ?)
                              AND so.added_on <= NOW()
                            GROUP BY sod.product_id
                            ORDER BY total_sold $sortVal
                            LIMIT 10";

            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt) {
                $stmt->bind_param("ss", $adminId, $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return json_encode(['status' => '1', 'data' => $data]);
                } else {
                    return json_encode(['status' => '0']);
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }




    function dailyStockOutDataFetch($adminId, $sortVal)
    {
        try {
            $selectQuery = "SELECT sod.product_id, SUM(sod.qty) AS total_sold
                            FROM stock_out_details sod
                            JOIN stock_out so ON sod.invoice_id = so.invoice_id
                            WHERE so.admin_id = ?
                              AND so.added_on >= NOW() - INTERVAL 24 HOUR
                            GROUP BY sod.product_id
                            ORDER BY total_sold $sortVal
                            LIMIT 10";

            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return json_encode(['status' => '1', 'data' => $data]);
                } else {
                    return json_encode(['status' => '0']);
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }





    function stockOutDataFetchByRange($startDt, $endDt, $sortVal, $adminId)
    {
        $data = array();

        try {
            $selectQuery = "SELECT product_id, SUM(qty) AS total_sold
                            FROM stock_out_details
                            WHERE invoice_id IN (
                                SELECT invoice_id
                                FROM stock_out
                                WHERE admin_id = ? AND DATE(added_on) BETWEEN ? AND ?
                            )
                            GROUP BY product_id
                            ORDER BY total_sold $sortVal
                            LIMIT 10";

            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt) {
                $stmt->bind_param("sss", $adminId, $startDt, $endDt);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return json_encode(['status' => '1', 'data' => $data]);
                } else {
                    return json_encode(['status' => '0']);
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return $data;
    }

    //===================== eof most sold less sold data filter function ===============================

    // =========== most sold item function from the begening =========
    function mostSoldStockOutDataFromStart($adminId)
    {
        try {
            $selectQuery = "SELECT sod.product_id, SUM(sod.qty) AS total_sold
                            FROM stock_out_details sod
                            JOIN stock_out so ON sod.invoice_id = so.invoice_id
                            WHERE so.admin_id = ?
                              AND so.added_on >= (SELECT MIN(added_on) FROM stock_out WHERE admin_id = ?)
                              AND so.added_on <= NOW()
                            GROUP BY sod.product_id
                            ORDER BY total_sold DESC
                            LIMIT 10";


            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt) {
                $stmt->bind_param("ss", $adminId, $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }



    // =========== most sold item function for last 24 hrs =========
    function mostSoldStockOutDataGroupByDay($adminId)
    {
        try {
            $selectQuery = "SELECT sod.product_id, SUM(sod.qty) AS total_sold
                            FROM stock_out_details sod
                            JOIN stock_out so ON sod.invoice_id = so.invoice_id
                            WHERE so.admin_id = ?
                              AND so.added_on >= NOW() - INTERVAL 24 HOUR
                            GROUP BY sod.product_id
                            ORDER BY total_sold DESC
                            LIMIT 10";

            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }


    // =========== most sold item function in a date =========
    function mostSoldStockOutDataGroupByDt($date, $adminId)
    {
        $data = array();

        try {
            $selectQuery = "SELECT sod.product_id, SUM(sod.qty) AS total_sold
                            FROM stock_out_details sod
                            JOIN stock_out so ON sod.invoice_id = so.invoice_id
                            WHERE so.admin_id = ?
                              AND DATE(so.added_on) = ?
                            GROUP BY sod.product_id
                            ORDER BY total_sold DESC
                            LIMIT 10";

            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt) {
                $stmt->bind_param("ss", $adminId, $date);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        return $data;
    }





    // =========== most sold item function in a date range =========
    function mostSoldStockOutDataGroupByDtRng($startDt, $endDt, $adminId)
    {
        $data = array();

        try {
            $selectQuery = "SELECT product_id, SUM(qty) AS total_sold
                            FROM stock_out_details
                            WHERE invoice_id IN (
                                SELECT invoice_id
                                FROM stock_out
                                WHERE admin_id = ? AND DATE(added_on) BETWEEN ? AND ?
                            )
                            GROUP BY product_id
                            ORDER BY total_sold DESC
                            LIMIT 10";

            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt) {
                $stmt->bind_param("sss", $adminId, $startDt, $endDt);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return $data;
    }


    //  ============= end of most sold item check query ===========

    /// ========= less sold item check query ================

    function leastSoldStockOutDataFromStart($adminId)
    {
        try {
            $query = "SELECT product_id, SUM(qty) AS total_sold
            FROM stock_out_details
            WHERE invoice_id IN (
                SELECT invoice_id
                FROM stock_out
                WHERE added_on >= (SELECT MIN(added_on) FROM stock_out WHERE admin_id = ?)
                AND added_on <= NOW()
            )
            GROUP BY product_id
            ORDER BY total_sold
            LIMIT 10;";

            $stmt = $this->conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }





    function leastSoldStockOutDataGroupByDay($adminId)
    {
        try {
            $query = "SELECT product_id, SUM(qty) AS total_sold
                      FROM stock_out_details
                      WHERE invoice_id IN (
                          SELECT invoice_id
                          FROM stock_out
                          WHERE admin_id = ?
                            AND added_on >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                      )
                      GROUP BY product_id
                      ORDER BY total_sold
                      LIMIT 10";

            $stmt = $this->conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }







    function leastSoldStockOutDataGroupByWeek($adminId)
    {
        try {
            $query = "SELECT product_id, SUM(qty) AS total_sold
                      FROM stock_out_details
                      WHERE invoice_id IN (
                          SELECT invoice_id
                          FROM stock_out
                          WHERE admin_id = ?
                            AND added_on >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                      )
                      GROUP BY product_id
                      ORDER BY total_sold
                      LIMIT 10";

            $stmt = $this->conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }






    function leastSoldStockOutDataGroupByMonth($adminId)
    {
        try {
            $query = "SELECT product_id, SUM(qty) AS total_sold
                      FROM stock_out_details
                      WHERE invoice_id IN (
                          SELECT invoice_id
                          FROM stock_out
                          WHERE admin_id = ?
                            AND added_on >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                      )
                      GROUP BY product_id
                      ORDER BY total_sold
                      LIMIT 10";

            $stmt = $this->conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("s", $adminId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }





    function lessSoldStockOutDataGroupByDt($searchDt, $adminId)
    {
        $data = array();

        try {
            $selectQuery = "SELECT product_id, SUM(qty) AS total_sold
                            FROM stock_out_details
                            WHERE invoice_id IN (
                                SELECT invoice_id
                                FROM stock_out
                                WHERE admin_id = ? AND DATE(added_on) = ?
                            )
                            GROUP BY product_id
                            ORDER BY total_sold
                            LIMIT 10";

            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt) {
                $stmt->bind_param("ss", $adminId, $searchDt);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return $data;
    }





    function lessSoldStockOutDataGroupByDtRng($startDt, $endDt, $adminId)
    {
        $data = array();

        try {
            $selectQuery = "SELECT product_id, SUM(qty) AS total_sold
                            FROM stock_out_details
                            WHERE invoice_id IN (
                                SELECT invoice_id
                                FROM stock_out
                                WHERE admin_id = ? AND DATE(added_on) BETWEEN ? AND ?
                            )
                            GROUP BY product_id
                            ORDER BY total_sold
                            LIMIT 10";

            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt) {
                $stmt->bind_param("sss", $adminId, $startDt, $endDt);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = array();
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    return $data;
                } else {
                    return null;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return $data;
    }

    /// ========= end of less sold item check query ================


    // ======== data fetch function fro sales margin card ==============



    function salesMarginData($adminId)
    {
        $data = array();
        try {
            $selectData = "SELECT SUM(stock_out_details.qty) AS sales_pack, 
                        SUM(stock_out_details.mrp) AS sales_mrp, 
                        SUM(stock_out_details.margin) AS sell_margin 
                        FROM stock_out_details 
                        LEFT JOIN stock_out ON stock_out_details.invoice_id = stock_out.invoice_id 
                        WHERE stock_out.admin_id = $adminId";

            $dataQuery = $this->conn->query($selectData);

            if ($dataQuery->num_rows > 0) {
                while ($result = $dataQuery->fetch_object()) {
                    $data[]    = $result;
                }
                return $data;
            } else {
                return null;
            }
        } catch (Exception $e) {
            return $e;
        }
    } // eof stockOutDisplayById 



    // =========== end of sales margin card data fetch =================

    function stockOutDetailsDisplayById($invoiceId)
    {
        $billData = array();
        $selectBill = "SELECT * FROM `stock_out_details` WHERE `invoice_id` = '$invoiceId'";
        // echo $selectBill.$this->conn->error;

        $billQuery = $this->conn->query($selectBill);
        while ($result = $billQuery->fetch_array()) {
            $billData[]    = $result;
        }
        return $billData;
    } // eof stockOutDisplayById 





    function stokOutDetailsDataOnTables($table1, $data1, $table2, $data2, $table3, $data3,)
    {
        $stockOutSelect = array();
        $selectBill = "SELECT * FROM `stock_out_details` WHERE `$table1` = '$data1' AND `$table2` = '$data2' AND `$table3` = '$data3'";

        $stockOutDataQuery = $this->conn->query($selectBill);

        while ($result = $stockOutDataQuery->fetch_array()) {
            $stockOutSelect[]    = $result;
        }
        return $stockOutSelect;
    } //eof stockOut details by tabel and data






    function stockOutDetailsSelect($invoice, $productId, $batchNo)
    {

        //$stockOutDetailData = array();
        $stockOutDetailData = array();

        $selectData = "SELECT * FROM `stock_out_details` WHERE `stock_out_details`.`invoice_id` = '$invoice' AND `stock_out_details`.`product_id` = '$productId' AND `stock_out_details`.`batch_no` = '$batchNo'";

        $dataQuery = $this->conn->query($selectData);

        while ($result = $dataQuery->fetch_array()) {
            $stockOutDetailData[]    = $result;
        }

        return $stockOutDetailData;
    } //end of stockOutDetail fetch from pharmacy_invoice table function




    // stock out margin data
    function salesMarginDataFetch($startDate, $endDate, $adminId, $flag, $item = '') {
        $data = array();
        try {
            $marginQuery = "SELECT 
                                COALESCE(adm.username, emp.emp_username) AS added_by_name,
                                COALESCE(pd.name, 'cash sales') AS patient_name,
                                sod.invoice_id AS bill_no,
                                DATE_FORMAT(so.bill_date, '%d-%m-%Y') AS bill_date,
                                sod.item_name AS item,
                                CONCAT(sod.weightage, ' ', sod.unit) AS unit,
                                so.added_on AS stock_out_date,
                                CASE 
                                    WHEN sod.unit = 'Tablets' OR sod.unit = 'Capsules' THEN
                                        CASE 
                                            WHEN MOD(sod.loosely_count, sod.weightage) = 0 THEN sod.qty
                                            ELSE CONCAT(sod.loosely_count, ' (L)')
                                        END
                                    ELSE sod.qty
                                END AS stock_out_qty,
                                sod.loosely_count AS stock_out_lqty,
                                CASE 
                                    WHEN cs.unit = 'Tablets' OR cs.unit = 'Capsules' THEN 
                                        CONCAT(cs.qty, ' (', cs.loosely_count, ' L)')
                                    ELSE cs.qty
                                END AS current_qty,
                                sid.mrp AS mrp,
                                sod.amount AS sales_amount,
                                CASE 
                                    WHEN sod.unit = 'Tablets' OR sod.unit = 'Capsules' THEN 
                                        ((sid.amount / sid.loosely_count) * sod.loosely_count)
                                    ELSE 
                                        ((sid.amount / (sid.qty + sid.free_qty)) * sod.qty)
                                END AS p_amount,
                                CASE 
                                    WHEN sod.unit = 'Tablets' OR sod.unit = 'Capsules' THEN 
                                        (sod.gst_amount - ((sid.gst_amount / sid.loosely_count) * sod.loosely_count))
                                    ELSE 
                                        (sod.gst_amount - ((sid.gst_amount / sid.qty) * sod.qty))
                                END AS gst_amount,
                                sod.profit_margin AS margin_amount,
                                (((sod.amount - sod.profit_margin) * 100) / sod.amount) AS margin_percent,
                                CASE 
                                    WHEN sod.unit = 'Tablets' OR sod.unit = 'Capsules' THEN 
                                        CASE 
                                            WHEN MOD(sod.loosely_count, sod.weightage) = 0 THEN
                                                (sod.amount - (((sid.amount / (sid.qty + sid.free_qty)) * sod.qty) + sod.gst_amount))
                                            ELSE
                                                (sod.amount - (((sid.amount / sid.loosely_count) * sod.loosely_count) + sod.gst_amount))
                                        END
                                    ELSE 
                                        (sod.amount - (((sid.amount / (sid.qty + sid.free_qty)) * sod.qty) + sod.gst_amount))
                                END AS profit,
                                m.short_name AS manuf_short_name,
                                pt.name AS category
                            FROM 
                                stock_out_details sod
                            JOIN 
                                stock_out so ON sod.invoice_id = so.invoice_id
                            LEFT JOIN 
                                patient_details pd ON so.customer_id = pd.patient_id
                            JOIN 
                                current_stock cs ON sod.item_id = cs.stock_in_details_id
                            JOIN 
                                stock_in_details sid ON cs.stock_in_details_id = sid.id
                            JOIN 
                                products p ON sod.product_id = p.product_id
                            JOIN 
                                manufacturer m ON p.manufacturer_id = m.id
                            JOIN 
                                product_type pt ON pt.name = p.type
                            LEFT JOIN 
                                admin adm ON so.added_by = adm.admin_id
                            LEFT JOIN 
                                employees emp ON so.added_by = emp.emp_id
                            WHERE 
                                DATE(so.added_on) BETWEEN ? AND ?
                                AND so.admin_id = ?";
    
            if ($item != '') {
                if ($flag == 0) {
                    $marginQuery .= " AND sod.item_name LIKE ?";
                    $item = "%$item%";
                } elseif ($flag == 1) {
                    $marginQuery .= " AND (sod.item_name LIKE ? OR pd.name LIKE ?)";
                    $item = "%$item%";
                }
            }
    
            $stmt = $this->conn->prepare($marginQuery);
    
            if ($item != '') {
                if ($flag == 0) {
                    $stmt->bind_param("ssss", $startDate, $endDate, $adminId, $item);
                } elseif ($flag == 1) {
                    $stmt->bind_param("sssss", $startDate, $endDate, $adminId, $item, $item);
                }
            } else {
                $stmt->bind_param("sss", $startDate, $endDate, $adminId);
            }
    
            $stmt->execute();
    
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                $response = ['status' => true, 'data' => $data];
            } else {
                $response = ['status' => false, 'data' => 'No records found'];
            }
    
            $stmt->close();
    
            return json_encode($response);
        } catch (Exception $e) {
            // error_log($e->getMessage());
            return json_encode(['status' => false, 'error' => $e->getMessage()]);
        }
    }
    
    
    




    // stock out delete
    function deleteFromStockOutDetailsOnId($id)
    {

        $delete = "DELETE FROM `stock_out_details` WHERE `id` = '$id'";
        $delteQuery = $this->conn->query($delete);
        return $delteQuery;
    }


    //// stock out audit fucntion
    function stockOutAuditFunction($startDate, $endDate, $groupBy, $admin) {
        try {
            switch ($groupBy) {
                case 'year':
                    $groupByClause = "YEAR(so.bill_date)";
                    $dateFormat = "YEAR(so.bill_date)";
                    $dataOrder = "$groupByClause ASC";
                    break;
                case 'month':
                    $groupByClause = "YEAR(so.bill_date), MONTH(so.bill_date)";
                    $dateFormat = "DATE_FORMAT(so.bill_date, '%b %Y')"; 
                    $dataOrder = "$groupByClause ASC";
                    break;
                case 'date':
                default:
                    $groupByClause = "DATE(so.bill_date)";
                    $dateFormat = "DATE_FORMAT(so.bill_date, '%d %b %Y')"; 
                    $dataOrder = "$groupByClause ASC";
                    break;
            }
    
            $query = "SELECT
                          $dateFormat AS sales_dt,
                          COUNT(so.invoice_id) AS sales_count,
                          SUM(so.amount) AS sales_amount
                      FROM 
                          stock_out so
                      WHERE
                          so.bill_date BETWEEN ? AND ?
                          AND so.admin_id = ?
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
