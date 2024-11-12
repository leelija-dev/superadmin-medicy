<?php
require_once 'dbconnect.php';

class SubTests
{

    use DatabaseConnection;


    // function showSubTests()
    // {
    //     try {
    //         $data = [];
    //         $selectTest = "SELECT * FROM `sub_tests`";
    //         $testQuery = $this->conn->query($selectTest);
    //         while ($result = $testQuery->fetch_array()) {
    //             $data[] = $result;
    //         }
    //         return $data;
    //     } catch (Exception $e) {
    //         $e->getMessage();
    //     }
    // } // end showSubTests function




    function addSubTests($subTestName, $subTestUnit, $parentTestId, $ageGroup, $subTestPrep, $subTestDsc, $price)
    {
        try {
            $insertTest = "INSERT INTO sub_tests (sub_test_name, unit, parent_test_id, age_group, test_preparation, test_dsc, price) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($insertTest);

            $stmt->bind_param("ssisssd", $subTestName, $subTestUnit, $parentTestId, $ageGroup, $subTestPrep, $subTestDsc, $price);

            $stmt->execute();

            if($stmt->affected_rows > 0){
                $result = ['status'=>true, 'message'=>'data insert success'];
            }else{
                $result = ['status'=>false, 'message'=>'insertion fails'];
            }
            $stmt->close();

            return json_encode($result);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    function subTestById($testId)
    {
        try {
            $data = null;
            $sql = "SELECT * FROM `sub_tests` where `id` = '$testId'";
            $query = $this->conn->query($sql);
            while ($result = $query->fetch_object()) {
                $data = $result;
            }
            $dataset = json_encode($data);
            return $dataset;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    // function showSubTestsId($subTestId)
    // {
    //     $data = [];
    //     $selectTestById = "SELECT * FROM sub_tests WHERE `sub_tests`.`id` = '$subTestId'";
    //     $subTestQuery = $this->conn->query($selectTestById);
    //     while ($result = $subTestQuery->fetch_array()) {
    //         $data[] = $result;
    //     }
    //     return $data;
    // } // end showLabTypesById function



    // function showSubTestsByCatId($showLabtypeId)
    // {
    //     try {
    //         $selectTestByCatId = "SELECT * FROM `sub_tests` WHERE `parent_test_id` = '$showLabtypeId'";

    //         $stmt = $this->conn->prepare($selectTestByCatId);
    //         $stmt->execute();

    //         $result = $stmt->get_result();

    //         if ($result->num_rows > 0) {
    //             $data = [];
    //             while ($row = $result->fetch_assoc()) {
    //                 $data[] = $row;
    //             }
    //             return json_encode(['status' => true, 'message' => 'Data retrieved successfully', 'data' => $data]);
    //         } else {
    //             return json_encode(['status' => false, 'message' => 'No data found']);
    //         }
    //     } catch (Exception $e) {
    //         return ['status' => false, 'message' => $e->getMessage()];
    //     }
    // }



    // function updateSubTests($testTypeName, $pvdBy, $dsc, /*Last Veriable to select the id of the lab tyoe whichi we wants to delete*/ $updateLabType){
    //     $editLabType = "UPDATE  `tests_types` SET `test_type_name` = '$testTypeName', `provided_by`='$pvdBy', `dsc`= '$dsc' WHERE `tests_types`.`id` = '$updateLabType'";
    //     $editLabTypeQuery = $this->conn->query($editLabType);
    //     // echo $editLabType.$this->conn->error;
    //     // exit;
    //     return $editLabTypeQuery; 
    // }// end editLabTypes function





    function deleteSubTests($delTestTypeId)
    {
        try {
            $deletelabType = "DELETE FROM `sub_tests` WHERE `parent_test_id` = '$delTestTypeId'";

            $stmt = $this->conn->prepare($deletelabType);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = ['status' => true, 'message' => 'deleted'];
            } else {
                $result = ['status' => false, 'message' => 'action fails'];
            }
    
            $stmt->closeCursor();
    
            return json_encode($result);
    
        } catch (PDOException $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    


} //eof SubTests class
