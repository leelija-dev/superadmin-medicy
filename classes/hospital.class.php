<?php

class HealthCare
{
    use DatabaseConnection;


    function addClinicInfo($clinicId, $adminId, $addedTime)
    {
        try {
            $addClinicData = "INSERT INTO `clinic_info`(`hospital_id`, `admin_id`, `added_on`) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($addClinicData);

            if (!$stmt) {
                throw new Exception("Error in preparing statement: " . $this->conn->error);
            }

            $stmt->bind_param("sss", $clinicId, $adminId, $addedTime);

            if (!$stmt->execute()) {
                throw new Exception("Error executing statement: " . $stmt->error);
            }
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }






    function showHealthCare($adminId = '')
    {
        $response = array();

        try {
            if (!empty($adminId)) {
                $sql = "SELECT * FROM clinic_info WHERE `admin_id` = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("s", $adminId);
            } else {
                $sql = "SELECT * FROM clinic_info ";
                $stmt = $this->conn->prepare($sql);
            }
            // $stmt = $this->conn->prepare($sql);

            if ($stmt) {
                // $stmt->bind_param("s", $adminId);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();

                    // if ($result->num_rows > 0) {
                    // Fetch all rows into an array
                    $row = $result->fetch_assoc();
                    $response = $row;

                    return json_encode(['status' => 1, 'msg' => 'success', 'data' => $response]);
                    // } else {
                    //     return json_encode(['status'=> 0, 'msg' => 'empty', 'data' => '']);
                    // }
                } else {
                    return $response; // Return null if the query execution fails
                }
            } else {
                throw new Exception("Error in preparing SQL statement");
            }
        } catch (Exception $e) {
            // Handle any exceptions that may occur
            return json_encode(['status' => 0, 'msg' => $e->getMessage(), 'data' => '']);
        }
    }




    // update healthcare details function ------------------

    function updateHealthCare($imgFolder, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCareDist, $healthCarePin, $healthCareState, $healthCareEmail, $healthCareHelpLineNo, $healthCareApntBookingNo, $adminId) {
        try {
            $updateHealthCare = "UPDATE clinic_info SET logo = ?, hospital_name = ?, address_1 = ?, address_2 = ?, city = ?, dist = ?, pin = ?, health_care_state = ?, hospital_email = ?, hospital_phno = ?, appointment_help_line = ? WHERE admin_id = ?";
    
            if ($stmt = $this->conn->prepare($updateHealthCare)) {
                $stmt->bind_param("ssssssssssss", $imgFolder, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCareDist, $healthCarePin, $healthCareState, $healthCareEmail, $healthCareHelpLineNo, $healthCareApntBookingNo, $adminId);
    
                if ($stmt->execute()) {
                    $affectedRows = $stmt->affected_rows;
                    if($affectedRows > 0){
                        return $affectedRows;
                    }else{
                        return 'no data change';
                    }
                    $stmt->close();
                    
                } else {
                    throw new Exception("Execution failed: " . $stmt->error);
                }
            } else {
                throw new Exception("Statement preparation failed: " . $this->conn->error);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return "An error occurred while updating the healthcare information: " . $e->getMessage();
        }
        return 0; // Return 0 in case of failure
    }






    function updateDrugPermissionData($imgFolderForm20, $imgFolderForm21, $gstin, $pan, $adminId)
    {
        try {
            $updateHealthCare = "UPDATE clinic_info SET form_20 = ?, form_21 = ?, gstin = ?, pan = ? WHERE admin_id = ?";

            if (!$stmt = $this->conn->prepare($updateHealthCare)) {
                throw new Exception("SQL prepare failed: " . $this->conn->error);
            }

            if (!$stmt->bind_param("sssss", $imgFolderForm20, $imgFolderForm21, $gstin, $pan, $adminId)) {
                throw new Exception("Parameter binding failed: " . $stmt->error);
            }

            if (!$stmt->execute()) {
                throw new Exception("SQL execution failed: " . $stmt->error);
            }

            $updateHealthCareQuery = $stmt->affected_rows;

            $stmt->close();

            if ($updateHealthCareQuery > 0) {
                return json_encode(['status' => '1', 'message' => 'Data updated successfully']);
            } else {
                return json_encode(['status' => '2', 'message' => 'No data was changed']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }






    // used in text update
    function updateHealthCareDesc($WhatWeDoText, $appointmentBookText, $subscribeText, $footerText)
    {
        $updateDsc = "UPDATE clinic_info SET main_desc = '$WhatWeDoText', footer_desc = '$footerText', book_appointment_text = '$appointmentBookText', subscribe_text = '$subscribeText'";

        $updateDscQuery = $this->conn->query($updateDsc);
        // echo $updateDscQuery.$this->conn->error;
        // exit;
        return $updateDscQuery;
    } //eof updateHealthCareDesc function



} //Hospital Class end



// $hospital = new Hospital();
// $showHospitalDetails = $hospital-> showHospital();
// // echo count($showHospitalDetails);
// foreach($showHospitalDetails as $hospitalDetails){

//     echo $hospitalDetails['hospital_name'];
// }
