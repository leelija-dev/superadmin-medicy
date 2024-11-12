<?php

class Gst
{
    use DatabaseConnection;

    function seletGst(){
        try {
            $addClinicData = "SELECT * FROM gst";

            $stmt = $this->conn->prepare($addClinicData);

            if (!$stmt) {
                throw new Exception("Error in preparing statement: " . $this->conn->error);
            }

            if ($stmt->execute()) {

                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $gstData = array();
                    while ($row = $result->fetch_object()) {
                        $gstData[] = $row;
                    }
                    $stmt->close();
                    return json_encode(['status' => 1, 'msg' => 'success', 'data' => $gstData]);
                } else {
                    $stmt->close();
                    return json_encode(['status' => 0, 'msg' => 'empty', 'data' => '']);
                }
            } else {
                return "execution fails"; // Return null if the query execution fails
            }
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'msg' => $e->getMessage(), 'data' => '']);
        }
        return 0;
    }


    function seletGstByColVal($col, $val){
        try {
            $addClinicData = "SELECT * FROM gst WHERE `$col` = $val";

            $stmt = $this->conn->prepare($addClinicData);

            if (!$stmt) {
                throw new Exception("Error in preparing statement: " . $this->conn->error);
            }

            if ($stmt->execute()) {

                $result = $stmt->get_result();

                if ($result->num_rows > 0) {    
                    while ($row = $result->fetch_object()) {
                        $gstData[] = $row;
                    }
                    $stmt->close();
                    return json_encode(['status' => 1, 'msg' => 'success', 'data' => $gstData]);
                } else {
                    $stmt->close();
                    return json_encode(['status' => 0, 'msg' => 'empty', 'data' => '']);
                }
            } else {
                return "execution fails"; // Return null if the query execution fails
            }
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'msg' => $e->getMessage(), 'data' => '']);
        }
        return 0;
    }
}
