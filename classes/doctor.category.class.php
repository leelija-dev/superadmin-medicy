<?php
class DoctorCategory
{

    use DatabaseConnection;


    function addDoctorCategory($docCatName, $docDesc, $employee, $addedOn, $adminId)
    {

        try {
            $insertDocCat = "INSERT INTO doctor_category (`category_name`, `category_descreption`, `added_by`,  `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($insertDocCat);
            $stmt->bind_param("sssss", $docCatName, $docDesc, $employee, $addedOn, $adminId);

            if (!$stmt->execute()) {
                throw new Exception("Error in query execution: " . $stmt->error);
            }

            $stmt->close();
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }




    function showDoctorCategory()
    {
        try {
            $query = "SELECT * FROM `doctor_category`";
            $stmt = $this->conn->query($query);

            if (!$stmt) {
                throw new Exception("Error in query: " . $this->conn->error);
            }

            if ($stmt->num_rows) {
                while ($result = $stmt->fetch_assoc()) {
                    $categoryData[] = $result;
                }
                return $categoryData;
            } else {
                return [];
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }





    // function showDoctorCategoryByLikeWise($data)
    // {
    //     try {
    //         if ($data != 'all') {
    //             $selectDoctorCategory = "SELECT * FROM `doctor_category` WHERE `category_name` LIKE  CONCAT('%', ?, '%')";
    //             $stmt = $this->conn->prepare($selectDoctorCategory);
    //             $stmt->bind_param("s", $data);
    //         } else {
    //             $selectDoctorCategory = "SELECT * FROM `doctor_category`";
    //             $stmt = $this->conn->prepare($selectDoctorCategory);
    //         }

    //         if (!$stmt->execute()) {
    //             throw new Exception("Error in query execution: " . $stmt->error);
    //         }

    //         $result = $stmt->get_result();
    //         $categoryData = [];

    //         if ($result->num_rows > 0) {
    //             while ($row = $result->fetch_assoc()) {
    //                 $categoryData[] = $row;
    //             }
    //             return json_encode(['status' => '1', 'data' => $categoryData]);
    //         } else {
    //             return json_encode(['status' => '0', 'error' => 'not found!']);
    //         }
    //         $stmt->close();
    //     } catch (Exception $e) {
    //         return json_encode(['status' => '0', 'error' => $e->getMessage()]);
    //     }
    // }




    function doctorCategorySearch($data)
    {
        try {
            $query = "SELECT * FROM `doctor_category` WHERE `category_name` LIKE CONCAT('%', ?, '%')";
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }
            $stmt->bind_param("s", $data);

            if (!$stmt->execute()) {
                throw new Exception("Error in query execution: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $categoryData = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $categoryData[] = $row;
                }
                $stmt->close();
                return json_encode(['status' => '1', 'data' => $categoryData]);
            }
            return json_encode(['status' => '0', 'error' => 'not found!']);
        } catch (Exception $e) {
            if (isset($stmt) && $stmt instanceof mysqli_stmt) {
                $stmt->close();
            }
            return json_encode(['status' => '0', 'error' => $e->getMessage()]);
        }
    }



    function showDoctorCategoryByAdmin($adminId = '')
    {
        try {
            if (!empty($adminId)) {
                $selectDoctorCategory = "SELECT * FROM `doctor_category` WHERE `admin_id` = ? ";
                $stmt = $this->conn->prepare($selectDoctorCategory);
                $stmt->bind_param("s", $adminId);
            } else {
                $selectDoctorCategory = "SELECT * FROM `doctor_category` ";
                $stmt = $this->conn->prepare($selectDoctorCategory);
            }
            // $stmt = $this->conn->prepare($selectDoctorCategory);
            // $stmt->bind_param("s", $adminId); 

            if (!$stmt->execute()) {
                throw new Exception("Error in query execution: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $categoryData = [];

            while ($row = $result->fetch_assoc()) {
                $categoryData[] = $row;
            }

            $stmt->close();

            return $categoryData;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }








    // function showDoctorCategoryById($docSpecialization)
    // {
    //     $selectDoctorCategoryById = "SELECT * FROM `doctor_category` WHERE `doctor_category`.`doctor_category_id`='$docSpecialization'";
    //     $selectDoctorCategoryByIdQuery = $this->conn->query($selectDoctorCategoryById);
    //     while ($result = $selectDoctorCategoryByIdQuery->fetch_array()) {
    //         $categoryDataById[] = $result;
    //     }
    //     return $categoryDataById;
    // } //end showDoctorCategoryById function


    function showDoctorCategoryById($docSpecialization)
    {
        try {
            // Use prepared statements to prevent SQL injection
            $selectDoctorCategoryById = "SELECT * FROM `doctor_category` WHERE `doctor_category`.`doctor_category_id`=?";
            $stmt = $this->conn->prepare($selectDoctorCategoryById);

            if (!$stmt) {
                throw new Exception("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
            }

            $stmt->bind_param("s", $docSpecialization);
            $stmt->execute();

            $result = $stmt->get_result();

            if (!$result) {
                throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }

            if ($result->num_rows === 1) {
                $response = $result->fetch_assoc();
                $stmt->close();
                return json_encode(['status' => true, 'message' => 'success', 'data' => $response]);
            } else {
                $stmt->close();
                return json_encode(['status' => false, 'message' => 'empty', 'data' => '']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => "Error: " . $e->getMessage(), 'data' => '']);
        }
    }






    function showDocCatByDocCatId($docSpecialization)
    {
        try {
            $selectDoctorCategoryById = "SELECT * FROM `doctor_category` WHERE `doctor_category_id` = '$docSpecialization'";

            $stmt = $this->conn->prepare($selectDoctorCategoryById);

            if (!$stmt) {
                return json_encode(['status' => false, 'message' => "Statement preparation failed: " .  $this->conn->error]);
            }

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                // print_r($data);
                $status = true;
            } else {
                $data = '';
                $status = false;
            }

            $stmt->close();

            return $data;
            // return json_encode(['status' => $status, 'data' => $data]);
        } catch (Exception $e) {
            // return json_encode(['status' => false, 'message' => $e->getMessage()]);
            return $e->getMessage();
        }
    }








    function updateDocCateory($docCatName, $docCatDesc, /*Last Variable for id which one you want to update */ $updateDocCatId)
    {

        $editDocCat = "UPDATE  `doctor_category` SET `category_name` = '$docCatName', `category_descreption`= '$docCatDesc' WHERE `doctor_category`.`doctor_category_id` = '$updateDocCatId'";
        $editQuery = $this->conn->query($editDocCat);
        return $editQuery;
    }





    function deleteDocCat($deleteDocCatId)
    {
        try {
            $deleteDocCat = "DELETE FROM `doctor_category` WHERE `doctor_category`.`doctor_category_id` = '$deleteDocCatId'";

            $deleteDocCatQuery = $this->conn->query($deleteDocCat);

            return $deleteDocCatQuery;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } // end deleteDocCat function







}
