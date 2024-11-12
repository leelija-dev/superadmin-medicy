<?php



class Appointments {

    use DatabaseConnection;
    
    // ============ apointments book by patients him/her self ==========
    function addAppointments($appointmentId, $patientId, $appointmentDate, $patientName, $patientGurdianNAme, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor, $patientDoctorShift)
    {

        $insertApoointments = "INSERT INTO `appointments` (`appointment_id`, `patient_id`, `appointment_date`, `patient_name`, `patient_gurdian_name`, `patient_email`, `patient_phno`, `patient_age`, `patient_weight`, `patient_gender`, `patient_addres1`, `patient_addres2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`, `doctor_id`, `patient_doc_shift`) VALUES ('$appointmentId', $patientId, '$appointmentDate', '$patientName', '$patientGurdianNAme', '$patientEmail', '$patientPhoneNumber', '$patientAge', '$patientWeight', '$gender', '$patientAddress1', '$patientAddress2', '$patientPS', '$patientDist', '$patientPIN', '$patientState', '$patientDoctor', '$patientDoctorShift')";

        $insertQuery = $this->conn->query($insertApoointments);

        return $insertQuery;
    } // end addAppointments function


    // ======== booked by admin or employeee ==============
    function addFromInternal($appointmentId, $appointmentSerial, $patientId, $appointmentDate, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor, $addedBy, $addedOn, $adminId)
    {
        try {
            $insertAppointments = "INSERT INTO `appointments` (`appointment_id`, `sl_no`, `patient_id`, `appointment_date`, `patient_name`, `patient_gurdian_name`, `patient_email`, `patient_phno`, `patient_age`, `patient_weight`, `patient_gender`, `patient_addres1`, `patient_addres2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`, `doctor_id`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($insertAppointments);

            if ($stmt) {
                $stmt->bind_param("sisssssssisssssssssss", $appointmentId, $appointmentSerial, $patientId, $appointmentDate, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor, $addedBy, $addedOn, $adminId);

                if ($stmt->execute()) {
                    return true; // Success
                } else {
                    return false; // Failed to execute the query
                }

                $stmt->close();
            } else {
                return false; // Statement preparation failed
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    // end addAppointments function




    function appointmentsDisplay($adminId='')
    {
        $data = array();

        try {
            if(!empty($adminId)){
                $stmt = $this->conn->prepare("SELECT * FROM appointments WHERE admin_id = ? ORDER BY appointment_date DESC");
                $stmt->bind_param("s", $adminId);
            }else{
                $stmt = $this->conn->prepare("SELECT * FROM appointments ORDER BY appointment_date DESC");
            }
            

            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $data[] = $row;
                    }
                    $stmt->close();
                    return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
                } else {
                    return json_encode(['status' => 0, 'message' => '', 'data' => '']);
                }
            } else {
                throw new Exception("Error statement preparation: $stmt->error");
            }
        } catch (Exception $e) {
            error_log("Error in appointmentsDisplay: " . $e->getMessage());
        }

        return 0;
    }



    function filterAppointments($filterBy, $column, $adminId)
    {
        $data = array();

        if ($column == 'search') {
        }
        try {
            // Create a prepared statement
            $stmt = $this->conn->prepare("SELECT * FROM appointments WHERE admin_id = ? ORDER BY id DESC");

            if ($stmt) {
                // Bind the parameter (adminId) to the statement
                $stmt->bind_param("s", $adminId);

                // Execute the statement
                if ($stmt->execute()) {
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }

                    $stmt->close(); // Close the statement
                } else {
                    // Handle query execution error here, if needed
                    throw new Exception("Error execution query: $stmt->error");
                }
            } else {
                // Handle statement preparation error here, if needed
                throw new Exception("Error statement preparation: $stmt->error");
            }
        } catch (Exception $e) {
            error_log("Error in appointmentsDisplay: " . $e->getMessage());
        }

        return $data;
    }





    function filterAppointmentsByIdOrName($data='', $adminId=''){

        try {
            if (!empty($adminId)) {
                $stmt = $this->conn->prepare("SELECT * FROM appointments WHERE (appointment_id LIKE ? OR  patient_id LIKE ? OR patient_name LIKE ?) AND admin_id = ? ORDER BY id DESC");
                $searchPattern = "%".$data ."%";
                $stmt->bind_param("ssss", $searchPattern, $searchPattern, $searchPattern, $adminId);
            }else{
                $stmt = $this->conn->prepare("SELECT * FROM appointments WHERE appointment_id LIKE ? OR  patient_id LIKE ? OR patient_name LIKE ? ORDER BY id DESC");
                $searchPattern = "%".$data ."%";
                $stmt->bind_param("sss", $searchPattern, $searchPattern, $searchPattern);
            }

                if ($stmt) {

                    // $searchPattern = "%".$data ."%";
                    // $stmt->bind_param("ssss", $searchPattern, $searchPattern, $searchPattern, $adminId);
                    
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $resultData = array();
                        while ($row = $result->fetch_object()) {
                            $resultData[] = $row;
                        }
                        $stmt->close(); 
                        return json_encode(['status' => '1', 'message' => 'success', 'data'=> $resultData]);
                    } else {
                        return json_encode(['status' => '0', 'message' => '', 'data'=> '']);
                        $stmt->close();
                    }
                } else {
                    throw new Exception("Error statement preparation: $stmt->error");
                }
            

            
        } catch (Exception $e) {
            error_log("Error in appointmentsDisplay: " . $e->getMessage());
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
        return 0;
    }






    function appointmentsFilter($col='', $data='', $adminId=''){
        try {
            if (!empty($adminId)) {
            $selectLastMonth = "SELECT * FROM appointments WHERE $col = ? AND admin_id = ?";
            $stmt = $this->conn->prepare($selectLastMonth);
            $stmt->bind_param("si", $data, $adminId);
            }else{
                $selectLastMonth = "SELECT * FROM appointments  WHERE  doctor_id = ?"; 
                $stmt = $this->conn->prepare($selectLastMonth);
                $stmt->bind_param("s", $data,);
            }
            // $stmt = $this->conn->prepare($selectLastMonth);

            // $stmt->bind_param("si", $data, $adminId);

            $stmt->execute();

            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $appointmentsRestult = array();
                while ($row = $result->fetch_object()) {
                    $appointmentsRestult[] = $row;
                } 
                return json_encode(['status'=>'1', 'message'=>'success', 'data'=>$appointmentsRestult]);
            } else {
                return json_encode(['status'=>'0', 'message'=>'', 'data'=>'']);
            }
            $stmt->close();
        } catch (Exception $e) {
            error_log("Error in appointmentsFilter: " . $e->getMessage());
            return 0;
        }
    }

  
    ///========== allAppointmentByAdmin =====////
    function allAppointmentByAdmin($adminId){
        try {
            $selectLastMonth = "SELECT * FROM appointments WHERE admin_id = ?"; 

            $stmt = $this->conn->prepare($selectLastMonth);
            $stmt->bind_param("s", $adminId);

            $stmt->execute();

            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $appointmentsRestult = array();
                while ($row = $result->fetch_object()) {
                    $appointmentsRestult[] = $row;
                } 
                return json_encode(['status'=>'1', 'message'=>'success', 'data'=>$appointmentsRestult]);
            } else {
                return json_encode(['status'=>'0', 'message'=>'', 'data'=>'']);
            }
            $stmt->close();
        } catch (Exception $e) {
            error_log("Error in appointmentsFilter: " . $e->getMessage());
            return 0;
        }
    }



    // =============== appointment filter =================
    // function appointmentsDataSearchFilter($searchVal='', $startDate='', $endDate='', $docId='', $empId='', $adminId='')
    // {
    //     try {

    //         $searchSQL = "SELECT * FROM appointments WHERE (((appointment_id LIKE :%$searchVal% OR :$searchVal IS NULL) OR (patient_id LIKE :%$searchVal% OR :$searchVal IS NULL) OR (patient_name LIKE :%$searchVal% OR :$searchVal IS NULL))
    //             AND (added_on BETWEEN DATE($startDate AND $endDate))
    //             AND (doctor_id = :$docId OR :$docId IS NULL)
    //             AND (added_by = :$empId OR :$empId IS NULL))
    //             AND admin_id = '$adminId'";

    //         $searchPattern = "%" . $searchVal . "%";

    //         $stmt = $this->conn->prepare($searchSQL);
    //         $stmt->bind_param("ssss", $searchPattern, $searchPattern, $searchPattern, $adminId);



    //         $stmt->execute();
    //         $result = $stmt->get_result();

    //         if($result->num_rows > 0){
    //             $appointmentsRestult = array();
    //             while ($row = $result->fetch_object()) {
    //                 $appointmentsRestult[] = $row;
    //             } 
    //             return json_encode(['status'=>'1', 'message'=>'success', 'data'=>$appointmentsRestult]);
    //         } else {
    //             return json_encode(['status'=>'0', 'message'=>'', 'data'=>'']);
    //         }

    //     }catch(Exception $e){
    //         print_r($e->getLine());
    //     }
    // }





    function appointmentsDataSearchFilter($searchVal = '', $startDate = '', $endDate = '', $docId = '', $empId = '', $adminId = '') {
        try {
            $searchSQL = "SELECT * FROM appointments WHERE 1=1";
            
            $params = array();
    
            if (!empty($searchVal)) {
                $searchSQL .= " AND (appointment_id LIKE '%$searchVal%' OR patient_id LIKE '%$searchVal%' OR patient_name LIKE '%$searchVal%' OR patient_phno LIKE '%$searchVal%')";
            }
            
            if (!empty($startDate) && !empty($endDate)) {
                $searchSQL .= " AND DATE(appointment_date) BETWEEN '$startDate' AND '$endDate'";
            }
    
            if (!empty($docId)) {
                $searchSQL .= " AND doctor_id = '$docId'";
            }
    
            if (!empty($empId)) {
                $searchSQL .= " AND added_by = '$empId'";
            }
    
            if (!empty($adminId)) {
                $searchSQL .= " AND admin_id = '$adminId'";
            }

            // print_r($searchSQL);

            $stmt = $this->conn->prepare($searchSQL);

            if(!$stmt){
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
    
    

    




    function appointmentsFilterByDate($fromDate='', $toDate='', $adminId=''){
        try {
            if (!empty($adminId)) {
            $selectLastMonth = "SELECT * FROM `appointments` 
                                WHERE added_on BETWEEN '$fromDate' AND '$toDate'
                                AND admin_id = '$adminId'";
            }else{
                $selectLastMonth = "SELECT * FROM `appointments` 
                WHERE added_on BETWEEN '$fromDate' AND '$toDate'";
            }
            $stmt = $this->conn->prepare($selectLastMonth);

            $stmt->execute();

            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $appointmentsRestult = array();
                while ($row = $result->fetch_object()) {
                    $appointmentsRestult[] = $row;
                } 
                $stmt->close();
                return json_encode(['status'=>'1', 'message'=>'success', 'data'=>$appointmentsRestult]);
            } else {
                $stmt->close();
                return json_encode(['status'=>'0', 'message'=>'', 'data'=>'']);
            }
        } catch (Exception $e) {
            error_log("Error in appointmentsFilter: " . $e->getMessage());
        }
        return 0;
    }





    function appointmentsDisplayOfLastMonth()
    {

        $selectLastMonth = "SELECT * FROM appointments WHERE YEAR(appointment_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(appointment_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
        $selectLastMonthQuery = $this->conn->query($selectLastMonth);
        while ($result = $selectLastMonthQuery->fetch_array()) {
            $data[]    = $result;
        }

        return $data;
    } //end appointmentsDisplay function




    function appointmentsDisplaybyTableId($appointmentTableId)
    {
        $selectById = "SELECT * FROM appointments WHERE `appointments`.`id` = '$appointmentTableId'";
        $selectByIdQuery = $this->conn->query($selectById);
        while ($result = $selectByIdQuery->fetch_array()) {
            $data[]    = $result;
        }
        return $data;
    } //end appointmentsDisplaybyId function




    function appointmentsDisplaybyId($appointmentId)
    {
        $data = [];
        try {
            $selectById = "SELECT * FROM `appointments` WHERE `appointments`.`appointment_id` = ?";
            $stmt = $this->conn->prepare($selectById);
            $stmt->bind_param("s", $appointmentId);
            $stmt->execute();
            
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            
            $stmt->close();
        } catch (Exception $e) {
            error_log("Error fetching appointment data: " . $e->getMessage());
            return false; 
        }
        return $data;
    }
    





    function updateAppointmentsbyTableId($appointmentDate, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor, $patientDoctorTiming, /*Last Parameter For Appointment Id Which Details You Want to Update*/ $appointmentTableId)
    {

        $updateById = "UPDATE  `appointments` SET `appointment_date` = '$appointmentDate', `patient_name` = '$patientName', `patient_gurdian_name` = '$patientGurdianName', `patient_email`= '$patientEmail', `patient_phno` = '$patientPhoneNumber', `patient_age` = '$patientAge', `patient_weight` = '$patientWeight', `patient_gender` = '$gender', `patient_addres1` = '$patientAddress1', `patient_addres2` = '$patientAddress2', `patient_ps` = '$patientPS', `patient_dist` = '$patientDist',`patient_pin` = '$patientPIN', `patient_state` = '$patientState', `doctor_id` = '$patientDoctor', `patient_doc_shift` = '$patientDoctorTiming' WHERE `appointments`.`id` = '$appointmentTableId'";

        // echo $updateById.$this->conn->error;
        // exit;

        $updatedByIdQuery = $this->conn->query($updateById);

        return $updatedByIdQuery;
    } // end updateAppointmentsbyId function






    function updateAppointmentsbyId($appointmentDate, $patientName, $patientGurdianNAme, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor, $patientDoctorTiming, /*Last Parameter For Appointment Id Which Details You Want to Update*/ $appointmentID)
    {

        $updateById = "UPDATE  `appointments` SET `appointment_date` = '$appointmentDate', `patient_name` = '$patientName', `patient_gurdian_name` = '$patientGurdianNAme', `patient_email`= '$patientEmail', `patient_phno` = '$patientPhoneNumber', `patient_age` = '$patientAge', `patient_weight` = '$patientWeight', `patient_gender` = '$gender', `patient_addres1` = '$patientAddress1', `patient_addres2` = '$patientAddress2', `patient_ps` = '$patientPS', `patient_dist` = '$patientDist',`patient_pin` = '$patientPIN', `patient_state` = '$patientState', `doctor_id` = '$patientDoctor', `patient_doc_shift` = '$patientDoctorTiming' WHERE `appointments`.`appointment_id` = '$appointmentID'";

        $updatedByIdQuery = $this->conn->query($updateById);

        return $updatedByIdQuery;
    } // end updateAppointmentsbyId function


    // //  start appointment entry function
    // function appointmententry($appointmentId, $appointmentDate, $patientName, $patientGurdianNAme, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor){

    //     $insertApoointments = "INSERT INTO `appointments` (`appointment_id`, `appointment_date`, `patient_name`, `patient_gurdian_name`, `patient_email`, `patient_phno`, `patient_age`, `patient_weight`, `patient_gender`, `patient_addres1`, `patient_addres2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`, `doctor_id`) VALUES ('$appointmentId', '$appointmentDate', '$patientName', '$patientGurdianNAme', '$patientEmail', '$patientPhoneNumber', '$patientAge', '$patientWeight', '$gender', '$patientAddress1', '$patientAddress2', '$patientPS', '$patientDist', '$patientPIN', '$patientState', '$patientDoctor' )";
    //     $insertQuery = $this->conn->query($insertApoointments);

    //     return $insertQuery;
    // }// end addAppointments function




    function deleteAppointmentsById($appointmentId)
    {

        $appointmentDelete = "DELETE FROM `appointments` WHERE `appointments`.`appointment_id` = '$appointmentId'";
        $DeleteQuey = $this->conn->query($appointmentDelete);
        return $DeleteQuey;
    } //end deleteAppointmentsById function





    function opdAuditDataFetch($startDate, $endDate, $groupBy, $admin) {
        try {
            $groupByClause = "";
            $dateFormat = ""; // To format the date as "Jan 2022", etc.
    
            switch($groupBy) {
                case 'year':
                    $groupByClause = "YEAR(ap.appointment_date)";
                    $dateFormat = "YEAR(ap.appointment_date)";
                    $dataOrder = "YEAR(ap.appointment_date) ASC";
                    break;
                case 'month':
                    $groupByClause = "YEAR(ap.appointment_date), MONTH(ap.appointment_date)";
                    $dateFormat = "DATE_FORMAT(ap.appointment_date, '%b %Y')"; // Formats as "Jan 2022"
                    $dataOrder = "YEAR(ap.appointment_date) ASC, MONTH(ap.appointment_date) ASC";
                    break;
                case 'date':
                default:
                    $groupByClause = "DATE(ap.appointment_date)";
                    $dateFormat = "DATE_FORMAT(ap.appointment_date, '%d %b %Y')"; // Formats as "04 Jan 2022"
                    $dataOrder = "DATE(ap.appointment_date) ASC";
                    break;
            }
    
            // SQL statement with dynamic grouping and formatting
            $select = "SELECT
                           $dateFormat AS apmnt_dt,
                           COUNT(ap.patient_id) AS patient_count
                       FROM 
                           appointments ap
                       WHERE
                           ap.appointment_date BETWEEN ? AND ?
                           AND ap.admin_id = ?
                       GROUP BY
                           $groupByClause
                       ORDER BY
                           $dataOrder";
    
            // Prepare and execute the statement
            $stmt = $this->conn->prepare($select);
            if ($stmt) {
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
                    return json_encode(['status' => false, 'data' => []]);
                }
            } else {
                throw new Exception("Error preparing the query: " . $this->conn->error);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error -> ' . $e->getMessage()]);
        } finally {
            if ($stmt) {
                $stmt->close();
            }
            $this->conn->close();
        }
    }
    
    
    
    


} //end class
