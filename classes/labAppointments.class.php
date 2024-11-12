<?php
class LabAppointments
{
    use DatabaseConnection;



    function addLabtestByInternal($billId, $patientId, $docId, $testId, $prices, $testDisc, $testAmount, $totalBill, $cgst, $sgst, $paidAmount, $testDate, $billingDate)
    {
        $insertLabAppointment = "INSERT INTO `lab_appointments` (`bill_id`, `patient_id`, `prefered_doctor_id`, `test_ids`, `prices`, `discount`, `after_discount`, `total_amount`, `cgst`, `sgst`, `paid_amount`, `test_date`, `added_on`) VALUES ('$billId', '$patientId', '$docId', '$testId', '$prices', '$testDisc', '$testAmount', '$totalBill', '$cgst', '$sgst', '$paidAmount', '$testDate', '$billingDate')";

        // echo $insertLabAppointment.$this->conn->error;
        // exit;

        $insertLabAppointmentQuery = $this->conn->query($insertLabAppointment);
        // echo var_dump($insertLabAppointmentQuery);
        return $insertLabAppointmentQuery;
    } // end addAppointments function





    function showLabAppointments()
    {
        $select = "SELECT * FROM lab_appointments";
        $selectQuery = $this->conn->query($select);
        $rows = $selectQuery->num_rows;
        if ($rows == 0) {
            return 0;
        } else {
            while ($result = $selectQuery->fetch_array()) {
                $data[]    = $result;
            }
            return $data;
        }
    } //end appointmentsDisplay function





    function showLabAppointmentsById($patientId)
    {
        $select = "SELECT * FROM lab_appointments WHERE `lab_appointments`.`patient_id` = '$patientId'";
        $selectQuery = $this->conn->query($select);
        $rows = $selectQuery->num_rows;
        if ($rows == 0) {
            return 0;
        } else {
            while ($result = $selectQuery->fetch_array()) {
                $data[]    = $result;
            }
            return $data;
        }
    } //end appointmentsDisplay function

    function labAppointmentNos($adminId='')
    {
        try {
            if(!empty($adminId)){
                $select = "SELECT  COUNT(*) as count  FROM lab_appointments WHERE `lab_appointments`.`admin_id` = '$adminId'";
            }else{
                $select = "SELECT  COUNT(*) as count  FROM lab_appointments";
            }
            echo $select;
            $selectQuery = $this->conn->query($select);
            $result = $selectQuery->fetch_assoc();
            return $result['count'];
        } catch (Exception $e) {
            $e->getMessage();
        }
    } //end appointmentsDisplay by adminId function



} //end class
