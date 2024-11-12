<?php

class SearchForAll{
    use DatabaseConnection;

    function searchAllFilterForAppointment($searchData, $adminId){
        $appointmentsResultData = array();
        $searchPattern = "%".$searchData."%";

        try {
            // ===== QUERY FOR APPOINTMENTS TABLE =====
            $searchAllForAppointments = "SELECT * FROM appointments WHERE `admin_id` = ? AND `appointment_id`   LIKE ? OR `patient_id` LIKE ? OR `patient_phno` LIKE ? LIMIT 6";

            $stmt = $this->conn->prepare($searchAllForAppointments);
            $stmt->bind_param("ssss",$adminId, $searchPattern, $searchPattern, $searchPattern);
            $stmt->execute();
            $appointmentsStatement = $stmt->get_result();

            if($appointmentsStatement->num_rows > 0){
                while ($appointmentsResult = $appointmentsStatement->fetch_assoc()) {
                    $appointmentsResultData[] = $appointmentsResult;
                }
            
                return json_encode(['status' => '1', 'message' => 'Data found', 'data' =>   $appointmentsResultData]);
            }else{
                return json_encode(['status' => '0', 'message' => 'Data not found', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => ' ', 'message' => $e->getMessage(), 'data' => '']);
        }
    }



    function searchAllFilterForPatient($searchData, $adminId){

        $patientData = array();
        $searchPattern = "%".$searchData."%";

        try {
            // ===== QUERY FOR APPOINTMENTS TABLE =====
            $searchAllForPatients = "SELECT * FROM patient_details WHERE `admin_id` = ? AND `patient_id`    LIKE ? OR `phno` LIKE ? OR `name` LIKE ? LIMIT 6";

            $stmt = $this->conn->prepare($searchAllForPatients);
            $stmt->bind_param("ssss",$adminId, $searchPattern, $searchPattern,$searchPattern);
            $stmt->execute();
            $patientStatement = $stmt->get_result();

            if($patientStatement->num_rows > 0){
                while ($patientResult = $patientStatement->fetch_assoc()) {
                    $patientData[] = $patientResult;
                }
                return json_encode(['status' => '1', 'message' => 'Data found', 'data' => $patientData]);
            }else{
                return json_encode(['status' => '0', 'message' => 'Data not found', 'data' => '']);
            }


        } catch (Exception $e) {
            return json_encode(['status' => ' ', 'message' => $e->getMessage(), 'data' => '']);
        }
    }



    function searchAllFilterForStockIn($searchData, $adminId){

        $stockinResultData = array();
        $searchPattern = "%".$searchData."%";

        try {
            // ===== QUERY FOR APPOINTMENTS TABLE =====
            $searchAllForStockIn = "SELECT * FROM stock_in WHERE `admin_id` = ? AND `distributor_bill` LIKE ? LIMIT 6";

            $stmt = $this->conn->prepare($searchAllForStockIn);
            $stmt->bind_param("ss",$adminId, $searchPattern);
            $stmt->execute();
            $stockinStatement = $stmt->get_result();

            if($stockinStatement->num_rows > 0){
                while ($stockInResult = $stockinStatement->fetch_assoc()) {
                    $stockinResultData[] = $stockInResult;
               }
               return json_encode(['status' => '1', 'message' => 'Data found', 'data' =>  $stockinResultData]);
            }else {
                return json_encode(['status' => '0', 'message' => 'Data not found', 'data' => '']);
            }

        } catch (Exception $e) {
            return json_encode(['status' => ' ', 'message' => $e->getMessage(), 'data' => '']);
        }
    }





    function searchAllFilterForStockOut($searchData, $adminId){

        $stockOutResultData = array();
        $searchPattern = "%".$searchData."%";

        try {
            // ===== QUERY FOR APPOINTMENTS TABLE =====
            $searchAllForStockOut = "SELECT * FROM stock_out WHERE `admin_id` = ? AND `customer_id` LIKE ? OR `amount` LIKE ? OR `payment_mode` LIKE ? LIMIT 6";

            $stmt = $this->conn->prepare($searchAllForStockOut);
            $stmt->bind_param("ssds",$adminId, $searchPattern, $searchPattern, $searchPattern);
            $stmt->execute();
            $stockOutStatement = $stmt->get_result();

            if($stockOutStatement->num_rows > 0){
                while ($stockOutResult = $stockOutStatement->fetch_assoc()) {
                    $stockOutResultData[] = $stockOutResult;
               }
               return json_encode(['status' => '1', 'message' => 'Data found', 'data' =>  $stockOutResultData]);
            }else {
                return json_encode(['status' => '0', 'message' => 'Data not found', 'data' => '']);
            }

        } catch (Exception $e) {
            return json_encode(['status' => ' ', 'message' => $e->getMessage(), 'data' => '']);
        }
    }






    function searchAllFilterForLabdata($searchData, $adminId){

        $stockinResultData = array();
        $searchPattern = "%".$searchData."%";
    
        try {
            // ===== QUERY FOR APPOINTMENTS TABLE =====
            $searchAllForStockIn = "SELECT * FROM lab_billing WHERE `admin_id` = ? AND `bill_id` LIKE ? OR `patient_id` LIKE ? LIMIT 6";
    
            $stmt = $this->conn->prepare($searchAllForStockIn);
            $stmt->bind_param("sss",$adminId, $searchPattern, $searchPattern);
            $stmt->execute();
            $stockinStatement = $stmt->get_result();
    
            if($stockinStatement->num_rows > 0){
                while ($stockInResult = $stockinStatement->fetch_assoc()) {
                    $stockinResultData[] = $stockInResult;
               }
               return json_encode(['status' => '1', 'message' => 'Data found', 'data' =>  $stockinResultData]);
            }else {
                return json_encode(['status' => '0', 'message' => 'Data not found', 'data' => '']);
            }
                    
        } catch (Exception $e) {
            return json_encode(['status' => ' ', 'message' => $e->getMessage(), 'data' => '']);
        }
    }

}