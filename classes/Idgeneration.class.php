 <?php

class IdGeneration{
    use DatabaseConnection;


    function gettingDoctor ($appointmentDate, $patientDoctor){
        $getDoc = "SELECT * FROM appointments WHERE appointment_date =  '$appointmentDate' AND doctor_id = '$patientDoctor' ";
        
        $getDocQuery = $this->conn->query($getDoc);
        
        while ($result = $getDocQuery->fetch_array()) {
            $data[] =$result;
        }
        return $data;
    }



}

 ?>