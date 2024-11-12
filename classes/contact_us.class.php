<?php

class insert{

    use DatabaseConnection;

    
    function adddata( $name,$email,$subject,$message){
        $insertquery = "INSERT INTO `contact_details` ( `name`, `email`, `subject`, `message`) 
        VALUES ('$name', '$email', '$subject', '$message')";

       
        $insert = $this->conn->query($insertquery);
        // echo $insert.$this->conn->error;exit;

        return $insert;
    }



  

}//end class


?>