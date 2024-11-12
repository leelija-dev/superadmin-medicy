<?php

class Search
{
    use DatabaseConnection;

    function searchForSale($data)
    {
        $res = array();
        $searchSql = "SELECT * FROM `products` WHERE `products`.`name` LIKE '%$data%'";
        $query     = $this->conn->query($searchSql);
        while ($result = $query->fetch_assoc()) {
            $res = $result;
        }
        return $res;
    }



    function searchCustomer($data)
    {
        $res = array();
        $searchSql = "SELECT * FROM `patient_details` WHERE `patient_details`.`name` LIKE '%$data%' OR `patient_details`.`phno` LIKE '%$data%'";
        $query     = $this->conn->query($searchSql);
        while ($result = $query->fetch_array()) {
            $res[] = $result;
        }
        return $res;
    }



    function searchCustomerByAdmin($data, $adminId)
    {
        $res = array();
        try {
            $searchSql = "SELECT * FROM `patient_details` WHERE (`name` LIKE ? OR `phno` LIKE ?) AND `admin_id` = ?";
            $stmt = $this->conn->prepare($searchSql);

            if ($stmt === false) {
                throw new Exception("Failed to prepare the SQL statement: " . $this->conn->error);
            }
            $likeData = '%' . $data . '%';
            $stmt->bind_param("sss", $likeData, $likeData, $adminId);
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute the SQL statement: " . $stmt->error);
            }
            $query = $stmt->get_result();
            while ($result = $query->fetch_array(MYSQLI_ASSOC)) {
                $res[] = $result;
            }
            $stmt->close();
        } catch (Exception $e) {
            return array("error" => $e->getMessage());
        }
        return $res;
    }




    function searchFor($table, $column, $data)
    {
        $res = array();
        $searchSql = "SELECT * FROM `$table` WHERE `$table`.`$column` LIKE '%$data%'";
        $query     = $this->conn->query($searchSql);
        while ($result = $query->fetch_array()) {
            $res[] = $result;
        }
        return $res;
    }

}//eof Products class

// $Search = new Search();

// $result = $Search->searchCustomer("Dip");
// // echo var_dump($result).'<br>';

// foreach ($result as $row) {
//     echo $row['name'].'<br>';
// }