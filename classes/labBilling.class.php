<?php
class LabBilling
{
    use DatabaseConnection;

    /** 
     * ###### CHANGES IN TO DATABASE TABLE ######
     *  Update By:  Dipak Majumdar
     *  date:       23-10-2024
     *  change:     'test_date' column changed into 'sample_collection_date' into all functions
     * 
     * 
     * 
     * 
     *  NEEDS TO UPDATE
     * labBiilingDetailsByPatientIdForChartData AND labBiilingDetailsByPatientId are same operation function
     */


    function addLabBill($billingDate, $patientId, $referedDoc, $testDate, $totalAmount, $discountOnTotal, $totalAfterDiscount, $cgst, $sgst, $paidAmount, $dueAmount, $status, $addedBy, $addedOn, $adminId)
    {

        try {
            $insertBill = "INSERT INTO lab_billing (`bill_date`, `patient_id`, `refered_doctor`, `sample_collection_date`, `total_amount`, `discount`, `total_after_discount`, `cgst`, `sgst`, `paid_amount`, `due_amount`, `status`, `added_by`, `added_on`, `admin_id`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($insertBill);

            if ($stmt) {
                $stmt->bind_param('sssssssssssssss', $billingDate, $patientId, $referedDoc, $testDate, $totalAmount, $discountOnTotal, $totalAfterDiscount, $cgst, $sgst, $paidAmount, $dueAmount, $status, $addedBy, $addedOn, $adminId);

                if ($stmt->execute()) {
                    return json_encode(['status' => true, 'insertId' => $stmt->insert_id]);
                } else {
                    throw new Exception("Execution failed: " . $stmt->error);
                }
                $stmt->close();
            } else {
                throw new Exception("Data binding error : " . $stmt->error);
            }
        } catch (Exception $e) {
            $errorMessage = $e->getMessage() . ' on ' . $e->getFile() . ' line no ' . $e->getLine();
            error_log($errorMessage);
            return json_encode(['status' => false, 'error' => $errorMessage]);
        }
    }






    function labBillDisplay($adminId = '')
    {
        try {
            if (!empty($adminId)) {
                $selectBill = "SELECT * FROM lab_billing WHERE admin_id = '$adminId' ORDER BY bill_date DESC";

            } else {
                $selectBill = "SELECT * FROM lab_billing ORDER BY bill_date DESC";
            }
            $stmt = $this->conn->prepare($selectBill);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $billData = array();
                while ($rows = $result->fetch_object()) {
                    $billData[] = $rows;
                }
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $billData]);
            } else {
                return json_encode(['status' => '0', 'message' => 'fail', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
        return 0;
    } //end employeesDisplay function




    function labBillDisplayById($billId)
    {
        try {
            $billId = intval($billId);

            $selectBill = "SELECT * FROM lab_billing WHERE bill_id = $billId ORDER BY bill_date DESC";
            $stmt = $this->conn->prepare($selectBill);

            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $billData = $row;
                    }
                    $returnData = json_encode(['status' => true, 'data' => $billData]);
                } else {
                    $returnData = json_encode(['status' => false, 'message' => 'No data found']);
                }
                $stmt->close();

                return $returnData;
            } else {
                throw new Exception('Statement prepare exception');
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }





    function labBillFilter($adminId = '', $col = '', $filterVal = '')
    {
        try {
            if (!empty($adminId)) {
                if ($col == 'search') {
                    $selectBill = "SELECT * FROM lab_billing WHERE admin_id = '$adminId' AND (bill_id LIKE '%$filterVal%' OR patient_id LIKE '%$filterVal%') ORDER BY bill_id DESC";
                } else {
                    $selectBill = "SELECT * FROM lab_billing WHERE admin_id = '$adminId' AND $col = $filterVal ORDER BY bill_id DESC";
                }
            } else {
                if ($col == 'search') {
                    $selectBill = "SELECT * FROM lab_billing WHERE  (bill_id LIKE '%$filterVal%' OR patient_id LIKE '%$filterVal%') ORDER BY bill_id DESC";
                } else {
                    $selectBill = "SELECT * FROM lab_billing WHERE  refered_doctor = ' $filterVal' ORDER BY bill_date DESC";
                }
            }


            $stmt = $this->conn->prepare($selectBill);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $billData = array();
                while ($row = $result->fetch_object()) {
                    $billData[] = $row;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $billData]);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'message' => 'fail', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
    } //end employeesDisplay function


    ///====== Lab Bill Filter based on AdminId=====///
    function labBillFilterByAdminID($adminId)
    {
        try {
            $selectBill = "SELECT * FROM `lab_billing` WHERE `admin_id` = ? ORDER BY bill_id DESC";

            $stmt = $this->conn->prepare($selectBill);
            $stmt->bind_param('s', $adminId);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $billData = array();
                while ($row = $result->fetch_object()) {
                    $billData[] = $row;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $billData]);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'message' => 'fail', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
    }



    function labBillFilterByDate($adminId, $fromDate, $toDate)
    {
        try {

            $selectBill = "SELECT * FROM lab_billing 
                            WHERE date(added_on) BETWEEN '$fromDate' AND '$toDate' 
                            AND admin_id = '$adminId' ";

            $stmt = $this->conn->prepare($selectBill);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $billData = array();
                while ($row = $result->fetch_object()) {
                    $billData[] = $row;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $billData]);
            } else {
                $stmt->close();
                return json_encode(['status' => '0', 'message' => 'fail', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
    } //end employeesDisplay function






    function labBillDataSearchFilter($searchVal = '', $startDate = '', $endDate = '', $docId = '', $empId = '', $adminId = '')
    {
        try {
            $searchSQL = "SELECT * FROM lab_billing WHERE 1=1";

            $params = array();

            if (!empty($searchVal)) {
                $searchSQL .= " AND (bill_id  LIKE '%$searchVal%' OR patient_id LIKE '%$searchVal%')";
            }

            if (!empty($startDate) && !empty($endDate)) {
                $searchSQL .= " AND DATE(sample_collection_date) BETWEEN '$startDate' AND '$endDate'";
            }

            if (!empty($docId)) {
                $searchSQL .= " AND refered_doctor = '$docId'";
            }

            if (!empty($empId)) {
                $searchSQL .= " AND added_by = '$empId'";
            }

            if (!empty($adminId)) {
                $searchSQL .= " AND admin_id = '$adminId'";
            }

            $stmt = $this->conn->prepare($searchSQL);
            // print_r($searchSQL);

            if (!$stmt) {
                throw new Exception('statement preaper exception');
            }

            foreach ($params as $param => &$value) {
                $stmt->bindParam($param, $value);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $appointmentsResult = array();
                while ($row = $result->fetch_object()) {
                    $appointmentsResult[] = $row;
                }
                return json_encode(['status' => '1', 'message' => 'success', 'data' => $appointmentsResult]);
            } else {
                return json_encode(['status' => '0', 'message' => 'No data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => 'Error: ' . $e->getMessage(), 'data' => '']);
        }
    }





    /** 
     * ###### CHANGES ######
     *  Update By:  Dipak Majumdar
     *  Date:       23-10-2024
     *  Change:     'test_date' column changed into 'sample_collection_date'
     * 
     */

    function updateLabBill($billId, $referedDoc, $testDate,  $totalAmount, $discountOnTotal, $totalAfterDiscount, $cgst, $sgst, $paidAmount, $dueAmount, $status)
    {
        $updateBill = "UPDATE  lab_billing SET  `refered_doctor` = '$referedDoc', `sample_collection_date` = '$testDate', `total_amount` = '$totalAmount', `discount` = '$discountOnTotal', `total_after_discount` = '$totalAfterDiscount', `cgst` = '$cgst', `sgst` = '$sgst', `paid_amount` = '$paidAmount', `due_amount` = '$dueAmount', `status` = '$status' WHERE `lab_billing`.`bill_id` = '$billId'";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $updateBillQuery = $this->conn->query($updateBill);
        return $updateBillQuery;
    } //end updateLabBill function






    /** 
     * ###### CHANGES ######
     *  Update By:  Roodraditya Das
     *  Date:       06-11-2024
     *  Change:     'modify fucntion updateLabBill with name updateEditLabBill'
     * 
     */

     function updateEditLabBill($billId, $referedDoc, $testDate, $totalAmount, $discountOnTotal, $totalAfterDiscount, $cgst, $sgst, $paidAmount, $dueAmount, $status, $updatedBy, $updatedOn) {
        try {
            // SQL query with the missing comma fixed
            $updateBill = "UPDATE lab_billing SET `refered_doctor` = ?, `sample_collection_date` = ?, `total_amount` = ?, `discount` = ?, `total_after_discount` = ?, `cgst` = ?, `sgst` = ?, `paid_amount` = ?, `due_amount` = ?, `status` = ?, `updated_by` = ?, `updated_on` = ? WHERE `lab_billing`.`bill_id` = ?";
    
            $stmt = $this->conn->prepare($updateBill);
            
            if ($stmt === false) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }
    
            $stmt->bind_param("sssssssssssss", $referedDoc, $testDate, $totalAmount, $discountOnTotal, $totalAfterDiscount, $cgst, $sgst, $paidAmount, $dueAmount, $status, $updatedBy, $updatedOn, $billId);
    
            $updateBillQuery = $stmt->execute();
    
            if ($updateBillQuery) {
                if ($this->conn->affected_rows > 0) {
                    return json_encode(['status'=>true, 'message'=>'data updated', 'data'=>$updateBillQuery]);
                } else {
                    return json_encode(['status'=>false, 'message'=>'data updation not done', 'data'=>$updateBillQuery]);
                }
            } else {
                throw new Exception("Error executing statement: " . $stmt->error);
            }
            $stmt->close();
    
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    
    



     


    function cancelLabBill($billId, $status)
    {

        $cancelBill = "UPDATE `lab_billing` SET `status` = '$status' WHERE `lab_billing`.`bill_id` = '$billId'";
        // echo $cancelBill.$this->conn->error;
        // exit;
        $cancelBillQuery = $this->conn->query($cancelBill);
        // echo $cancelBillQuery.$this->conn->error;
        // exit;
        return $cancelBillQuery;
    } //end updateLabBill function

    /// Lab bill details by patient Id ///

    function labBiilingDetailsByPatientId($patientId)
    {
        try {
            $sql = "SELECT *  FROM lab_billing WHERE `lab_billing`.`patient_id` = '$patientId'";
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $rows[] = $row;
                }
                return $rows;
            } else {
                return null;
            }
        } catch (Exception $e) {
            $e->getMessage();
        }
    }





    function labBiilingDetailsByPatientIdForChartData($patientId)
    {
        try {
            $sql = "SELECT *  FROM lab_billing WHERE `lab_billing`.`patient_id` = '$patientId'";
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $rows[] = $row;
                }
                return json_encode(['status' => true, 'data' => $rows]);
            } else {
                return json_encode(['status' => false, 'data' => 'no data found']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'error occured : ' . $e->getMessage()]);
        }
    }




    // Function to get the last lab bill ID from the database
    function getLastLabBillId()
    {

        // Replace 'lab_bills' with your actual table name
        $query = "SELECT MAX(CAST(bill_id AS SIGNED)) AS largest_bill_id FROM lab_billing";

        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            // Fetch the last lab bill ID
            $row = $result->fetch_assoc();

            return $row['largest_bill_id'];
        } else {
            // No lab bill ID found
            return null;
        }
    }



    function labBillingDataFetch($startDate, $endDate, $groupBy, $admin) {
        try {
            switch ($groupBy) {
                case 'year':
                    $groupByClause = "YEAR(lab.sample_collection_date)";
                    $dateFormat = "YEAR(lab.sample_collection_date)";
                    $dataOrder = "$groupByClause ASC";
                    break;
                case 'month':
                    $groupByClause = "YEAR(lab.sample_collection_date), MONTH(lab.sample_collection_date)";
                    $dateFormat = "DATE_FORMAT(lab.sample_collection_date, '%b %Y')"; // "Jan 2022"
                    $dataOrder = "$groupByClause ASC";
                    break;
                case 'date':
                default:
                    $groupByClause = "DATE(lab.sample_collection_date)";
                    $dateFormat = "DATE_FORMAT(lab.sample_collection_date, '%d %b %Y')"; // "04 Jan 2022"
                    $dataOrder = "$groupByClause ASC";
                    break;
            }
    
            $query = "SELECT
                          $dateFormat AS apmnt_dt,
                          COUNT(lab.patient_id) AS patient_count
                      FROM 
                          lab_billing lab
                      WHERE
                          lab.sample_collection_date BETWEEN ? AND ?
                          AND lab.admin_id = ?
                          AND lab.status != 'Cancelled'
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