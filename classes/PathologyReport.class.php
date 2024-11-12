<?php

class PathologyReport
{
    use DatabaseConnection;

    /********************************************************************************************
     *                                      Test Report Table                                   *
     ********************************************************************************************/

    function addTestReport($bill_id, $patient_id, $adminId, $addedBy, $added_on = NOW)
    {
        try {
            $addQuery = "INSERT INTO `test_report` (`bill_id`, `patient_id`, `admin_id`, `created_by`, `added_on`) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($addQuery);

            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('sssss', $bill_id, $patient_id, $adminId, $addedBy, $added_on);

            $result = $stmt->execute();
            if ($result) {
                $last_id = $this->conn->insert_id;
            }
            if ($stmt->affected_rows > 0) {
                $response = ['status' => true, 'message' => 'success', 'reportid' => $last_id];
            } else {
                $response = ['status' => false, 'message' => 'Data insertion failed!'];
            }

            $stmt->close();
            return json_encode($response);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }


    function seletIdByBillId($billId)
    {
        try {
            $query = "SELECT id FROM test_report WHERE bill_id = ?";
            $stmt = $this->conn->prepare($query);

            if ($stmt === false) {
                throw new Exception("Failed to prepare the SQL statement.");
            }

            $stmt->bind_param("i", $billId);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $data[] = $row;
                }
                $stmt->close();
                return json_encode(['status' => true, 'message' => 'Data found', 'data' => $data]);
            } else {
                $stmt->close();
                return json_encode(['status' => false, 'message' => 'No data found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => '']);
        }
    }



    function reportStatus($billId)
    {
        try {
            $query1 = "SELECT id FROM test_report WHERE bill_id = $billId";
            $stmt = $this->conn->prepare($query1);
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $reportIds[] = $row['id'];
                    }
                    // print_r($reportIds);
                    foreach ($reportIds as $eachReport) {
                        $reportDetails = $this->labReportDetailbyId($eachReport);
                        $reportDetails = json_decode($reportDetails);
                        foreach ($reportDetails as $eachDetail) {
                            $params[] = $eachDetail->param_id;
                        }
                    }
                    // print_r($params);
                    $returnData = ['status' => true, 'message' => 'success', 'data' => $params];
                } else {
                    $returnData = ['status' => false, 'message' => 'No data found'];
                }
                $stmt->close();

                return $returnData;
            } else {
                throw new Exception('Statement prepare exception');
            }
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    function testReportById($report_id)
    {
        try {
            $sql = "SELECT * FROM `test_report` WHERE `id` = '$report_id'";
            $query = $this->conn->query($sql);
            $result = $query->fetch_assoc();
            $data = $result;
            $data['details'] = $this->reportDetails($report_id);
            $dataset = json_encode($data);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function testReportByPatient($patient_id)
    {
        try {
            $sql = "SELECT * FROM `test_report` WHERE `patient_id` = '$patient_id'";
            $query = $this->conn->query($sql);
            $data = [];
            while ($result = $query->fetch_assoc()) {
                $result['details'] = $this->reportDetails($result['id']);
                $data[] = $result;
            }
            $dataset = json_encode($data);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    function testReportFetch($adminId = "")
    {
        try {
            $datas = array();
            if (!empty($adminId)) {
                $sql = "SELECT * FROM `test_report` WHERE `admin_id` = '$adminId' ORDER BY `id` DESC";
            } else {
                $sql = "SELECT * FROM `test_report` ORDER BY `id` DESC";

            }
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $datas[] = $result;
            }
            $dataset = json_encode($datas);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    function testReportFetchWithJsonReturn($adminId = "")
    {
        try {
            $datas = array();

            if (!empty($adminId)) {
                $sql = "SELECT * FROM `test_report` WHERE `admin_id` = '$adminId' ORDER BY `id` DESC";

            } else {
                $sql = "SELECT * FROM `test_report` ORDER BY `id` DESC";
            }

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare SQL statement");
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $datas[] = $row;
                }
                return json_encode(['status' => true, 'data' => $datas]);
            } else {
                return json_encode(['status' => false, 'message' => 'No records found']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }



    function labReportbyReportId($reportId)
    {
        try {
            $datas = null;
            $sql = "SELECT * FROM `test_report` where `bill_id`='$reportId'";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $datas = $result;
            }
            $dataset = json_encode($datas);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
     *********** USED IN ***********
     * Not Used yet
     */
    function getReportFormat($billId)
    {
        try {

            $formatType =  null;
            $sql = "SELECT id FROM `test_report` WHERE `bill_id`= $billId";
            $query = $this->conn->query($sql);

            while ($result = $query->fetch_object()) {

                $sql = "SELECT report_format_type FROM `test_report_details` where `report_id`= $result->id";
                $query = $this->conn->query($sql);
                while ($result = $query->fetch_assoc()) {
                    $formatType = $result['report_format_type'];
                }
            }

            return $formatType;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }





    function getReportFormatByIdAndTestId($billId, $testId)
    {
        try {
            $query = "SELECT trd.report_format_type
                        FROM test_report_details trd
                        JOIN test_report tr ON trd.report_id = tr.id
                        WHERE trd.test_id = ?
                        AND tr.bill_id = ?";

            $stmt = $this->conn->prepare($query);
            if(!$stmt){
                throw new Exception("Statement prepare exception");
            }
            $stmt->bind_param('ii', $testId,$billId);
            $stmt->execute();
            $result = $stmt->get_result(); 
            // print_r($result);
            if($result->num_rows > 0){
                while ($row = $result->fetch_object()) {
                    $data = $row;
                }
                return json_encode(['status'=>true, 'data'=>$data]);
            }else{
                return json_encode(['status'=>false, 'message'=>'no data found']);   
            }
        } catch (Exception $e) {
            return json_encode(['status'=>false, 'message'=>'error -> '.$e->getMessage()]);
        }
    }



    /**
     *********** USED IN ***********
     * 1. components/TestReportBody.inc.php
     */
    function getTextReportByBill($billId)
    {
        try {
            $sql = "SELECT id FROM `test_report` WHERE `bill_id`= $billId";
            $query = $this->conn->query($sql);

            $reports = [];
            while ($result = $query->fetch_object()) {
                $reports[] = $result->id;
            }

            $existingParams = [];
            if (!empty($reports)) {
                foreach ($reports as $eachReport) {
                    $response = $this->reportDetails($eachReport);
                    print_r($response);

                    // if ($response) {
                    //     foreach ($response as $eachRes) {
                    //         $existingParams[] = $eachRes['param_id'];
                    //     }
                    // }
                }
            }

            return $existingParams;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
     *********** USED IN ***********
     * 1. components/TestReportBody.inc.php
     */
    function getReportParamsByBill($billId)
    {
        try {
            $sql = "SELECT id FROM `test_report` WHERE `bill_id`= $billId";
            $query = $this->conn->query($sql);

            $reports = [];
            while ($result = $query->fetch_object()) {
                $reports[] = $result->id;
            }

            $existingParams = [];
            if (!empty($reports)) {
                foreach ($reports as $eachReport) {
                    $response = $this->reportDetails($eachReport);
                    if ($response) {
                        foreach ($response as $eachRes) {
                            $existingParams[] = $eachRes['param_id'];
                        }
                    }
                }
            }

            return $existingParams;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }



    //test report filter
    function testReportSearchFilter($searchVal = '', $startDate = '', $endDate = '', $docId = '', $adminId = '')
    {
        try {
            // SQL query with proper joins and fields selection
            $searchSQL = "SELECT tr.*, pd.patient_id, pd.name, pd.phno, lb.bill_date, lb.refered_doctor 
                          FROM test_report tr
                          LEFT JOIN patient_details pd ON tr.patient_id = pd.patient_id
                          LEFT JOIN lab_billing lb ON tr.bill_id = lb.bill_id
                          WHERE 1=1";

            // Add search criteria based on input
            if (!empty($searchVal)) {
                $searchSQL .= " AND (tr.bill_id LIKE '%$searchVal%' OR tr.patient_id LIKE '%$searchVal%' 
                                OR pd.name LIKE '%$searchVal%' OR pd.phno LIKE '%$searchVal%')";
            }

            // Filter by date range on either the billing date or added date
            if (!empty($startDate) && !empty($endDate)) {
                $searchSQL .= " AND (DATE(lb.bill_date) BETWEEN '$startDate' AND '$endDate' 
                                OR DATE(tr.added_on) BETWEEN '$startDate' AND '$endDate')";
            }

            // Filter by doctor ID if provided
            if (!empty($docId)) {
                $searchSQL .= " AND lb.refered_doctor = '$docId'";
            }

            // Filter by admin ID if provided
            if (!empty($adminId)) {
                $searchSQL .= " AND tr.admin_id = '$adminId'";
            }

            // Debugging print to see the generated SQL query
            // print_r($searchSQL);

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($searchSQL);

            if (!$stmt) {
                throw new Exception('Statement preparation failed.');
            }

            // Execute the query
            $stmt->execute();
            $result = $stmt->get_result();

            // Process the result
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



    /********************************************************************************************
     *                                  Test Report Details Table                                *
     ********************************************************************************************/
/**
 *     USED IN
 * 1. test-report-generate.php
 * 2. 
 */
    function addReportDetails($reportId, $param_id, $testId, $rangeId, $headId, $param_value, $textformat_data, $testTypeFormat)
    {
        try {

            if ($textformat_data !== '' ) {
                $addQuery = "INSERT INTO `test_report_details` (`report_id`, `textformat_data`,`report_format_type`, `test_id`) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($addQuery);

                if ($stmt === false) {
                    throw new Exception('Prepare failed: ' . $this->conn->error);
                }
    
                $stmt->bind_param('isii', $reportId, $textformat_data, $testTypeFormat, $testId);
            }else{
                $addQuery = "INSERT INTO `test_report_details` (`report_id`, `param_id`, `test_id`, `standered_range_id`, `param_head_id`, `param_value`, `textformat_data`, `report_format_type`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($addQuery);

                if ($stmt === false) {
                    throw new Exception('Prepare failed: ' . $this->conn->error);
                }

                // $stmt->bind_param('iiiiss', $reportId, $param_id, $rangeId, $headId, $param_value);
                $stmt->bind_param('iiiiissi', $reportId, $param_id, $testId, $rangeId, $headId, $param_value, $textformat_data, $testTypeFormat);
            }

        // }
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response = ['status' => true, 'message' => 'success',];
            } else {
                $response = ['status' => false, 'message' => 'Data insertion failed!'];
            }

            $stmt->close();
            return json_encode($response);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }


    // lab report data fetch by Id
    function labReportDetailbyId($reportId)
    {
        try {
            $datas = array();
            $sql = "SELECT * FROM `test_report_details` where `report_id`= '$reportId'";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $datas[] = $result;
            }
            $dataset = json_encode($datas);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function reportDetails($reportId)
    {
        try {
            $datas = null;
            $sql = "SELECT * FROM `test_report_details` where `report_id`=$reportId";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_assoc()) {
                $datas[] = $result;
            }
            $dataset = $datas;
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }



    function generatedReportCount($billId, $adminId)
    {
        try {
            $query = "SELECT trd.test_id 
                  FROM test_report_details trd 
                  JOIN test_report tr ON tr.id = trd.report_id
                  WHERE tr.bill_id = ? AND tr.admin_id = ?";

            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $this->conn->error);
            }

            $stmt->bind_param('ii', $billId, $adminId); 
            $stmt->execute();
            $result = $stmt->get_result();

            $data = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $data[] = $row->test_id; 
                }
                return json_encode(['status' => true, 'data' => $data]);
            } else {
                return json_encode(['status' => false, 'data' => []]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error -> ' . $e->getMessage()]);
        }
    }
}
