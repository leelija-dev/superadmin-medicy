<?php

class Pathology
{

    use DatabaseConnection;

    /********************************************************************************************
     *                                  Test Category Table                                     *
     ********************************************************************************************/



    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */

    function addTestCategory($testName, $testDsc, $image, $status, $addedOn)
    {
        try {
            $addLabType = "INSERT INTO `test_category` (`name`, `dsc`, `image`, `status`, `added_on`) VALUE(?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addLabType);

            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('sssis', $testName, $testDsc, $image, $status, $addedOn);

            $result = $stmt->execute();

            if ($result && $stmt->affected_rows > 0) {
                $response = ['status' => true, 'message' => 'Success'];
            } else {
                $response = ['status' => false, 'message' => 'Data insertion failed'];
            }

            $stmt->close();
            return json_encode($response);  // Only encode once
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }




    



    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function showTestCategories()
    {
        try {
            $data = [];
            $selectLabType = "SELECT * FROM `test_category`";
            $labTypeQuery = $this->conn->query($selectLabType);
            $rows = $labTypeQuery->num_rows;

            if ($rows > 0) {
                while ($result = $labTypeQuery->fetch_array()) {
                    $data[] = $result;
                }
                return json_encode(['status' => 1, 'message' => 'success', 'data' => $data]);
            } else {
                return json_encode(['status' => 0]);
            }
        } catch (Exception $e) {
            $e->getMessage();
        }
    } // end showLabTypes function




    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function selectLikeCategoryName($search)
    {
        try {
            $data = [];

            $selectLabType = "SELECT * FROM `test_list` WHERE `name` LIKE ?";
            $labTypeQuery = $this->conn->prepare($selectLabType);

            if ($labTypeQuery) {
                $searchTerm = "%$search%";
                $labTypeQuery->bind_param('s', $searchTerm);

                $labTypeQuery->execute();
                $result = $labTypeQuery->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    return json_encode(['status' => true, 'message' => 'success', 'data' => $data]);
                } else {
                    return json_encode(['status' => false, 'message' => 'No records found', 'data' => '']);
                }
            } else {
                throw new Exception("Failed to prepare the query");
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }






    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function searchLabTest($search)
    {
        try {
            $data = [];
            $searchLabTestData = "SELECT * FROM `tests_types` WHERE `test_type_name` LIKE '%$search%'";
            $stmt = $this->conn->prepare($searchLabTestData);

            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($resultData = $result->fetch_array()) {
                        $data[] = $resultData;
                    }
                    return json_encode(['status' => 1, 'data' => $data]);
                } else {
                    return json_encode(['status' => 0]);
                }
                $stmt->close();
            } else {
                throw new Exception("Failed to prepare statement");
            }
        } catch (Exception $e) {
            return json_encode(['status' => 0, 'message' => $e->getMessage()]);
        }
    } // end searchLabTest function





    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function showLabCat($showLabtypeId)
    {
        try {
            $data = [];
            $selectLabType = "SELECT * FROM test_category WHERE `id` = ?";

            $stmt = $this->conn->prepare($selectLabType);
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('i', $showLabtypeId);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $data = $row;
                }
                $stmt->close();
                return json_encode(['status' => true, 'data' => $data]);
            } else {
                $stmt->close();
                return json_encode(['status' => false, 'message' => 'No data found']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }






    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function updateLabTypes($id, $name, $dsc, $image)
    {
        try {
            $editLabType = "UPDATE `test_category` SET `name` = ?, `dsc` = ?, `image` = ? WHERE `id` = ?";

            $stmt = $this->conn->prepare($editLabType);
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('sssi', $name, $dsc, $image, $id);

            $result = $stmt->execute();
            if ($result === false) {
                throw new Exception('Execute failed: ' . $stmt->error);
            }

            $stmt->close();

            return json_encode(['status' => true, 'message' => 'Data updated successfully.']);
        } catch (Exception $e) {
            error_log('Error updating lab type: ' . $e->getMessage());

            return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }





    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function deleteLabTypes($delTestTypeId)
    {
        try {
            $deletelabType = "DELETE FROM `tests_types` WHERE `id` = ?";

            $stmt = $this->conn->prepare($deletelabType);

            if ($stmt === false) {
                throw new Exception('Statement preparation failed: ' . $this->conn->error);
            }

            $stmt->bind_param("i", $delTestTypeId);

            $stmt->execute();

            if ($stmt->error) {
                throw new Exception('Statement execution failed: ' . $stmt->error);
            }

            if ($stmt->affected_rows > 0) {
                return json_encode(['status' => true, 'message' => 'success']);
            } else {
                return json_encode(['status' => false, 'message' => 'Image Deleted But Details Not Deleted!']);
            }

            $stmt->close();
        } catch (Exception $e) {
            error_log($e->getMessage()); // Log the error message
            return false; // Return false in case of error
        }
    } // end deleteLabTypes function


    /********************************************************************************************
     *                                      Test List Table                                     *
     ********************************************************************************************/



     /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function addNewTest($catId, $testName, $testPrice, $testDsc, $testPrep, $addedOn)
    {
        try {
            $addTest = "INSERT INTO `test_list`(`cat_id`, `name`, `preparation`, `dsc`, `price`, `added_on`) VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addTest);
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('isssss', $catId, $testName, $testPrep, $testDsc, $testPrice, $addedOn);

            $result = $stmt->execute();
            if ($result === false) {
                throw new Exception('Execute failed: ' . $stmt->error);
            }

            $insertedId = $stmt->insert_id;

            $stmt->close();

            return json_encode(['status' => true, 'inserted_id' => $insertedId]);
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }





    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function updateTestData($testId, $testName, $testPrice, $testDsc, $testPrep, $reportType='', $textFormat='')
    {
        try {
            if (empty($reportType)) {
                $updateTest = "UPDATE `test_list` SET `name`=?, `preparation`=?, `dsc`=?, `price`=? WHERE `id`=?";
                $stmt = $this->conn->prepare($updateTest);
                
                if ($stmt === false) {
                    throw new Exception('Prepare failed: ' . $this->conn->error);
                }
                
                $stmt->bind_param('ssssi', $testName, $testPrep, $testDsc, $testPrice, $testId);
                $successMsg = 'Test Details Updated';

                $result = $stmt->execute();
                if ($result === false) {
                    throw new Exception('Execute failed: ' . $stmt->error);
                }
                
                $affectedRows = $stmt->affected_rows;
                
                $stmt->close();
                
                return json_encode(['status' => true, 'affected_rows' => $affectedRows, 'message' => $successMsg]);

            }else {
                $updateTest = "UPDATE `test_list` SET `name`=?, `preparation`=?, `dsc`=?, `price`=?, `report_type`=?, `report_text_format` = ? WHERE `id`=?";

                $stmt = $this->conn->prepare($updateTest);
                
                if ($stmt === false) {
                    throw new Exception('Prepare failed: ' . $this->conn->error);
                }
                
                $stmt->bind_param('ssssisi', $testName, $testPrep, $testDsc, $testPrice, $reportType, $textFormat, $testId);
                $successMsg = 'Test Details Updated with Report Format';
                
                $result = $stmt->execute();
                if ($result === false) {
                    throw new Exception('Execute failed: ' . $stmt->error);
                }
                
                $affectedRows = $stmt->affected_rows;
                
                $stmt->close();
                
                return json_encode(['status' => true, 'affected_rows' => $affectedRows, 'message' => $successMsg]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }





    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    // function updateDscAndPrcs($tid, $testName, $price, $dsc, $prep){

    //     try {
    //         $updateqry = "UPDATE `test_list` SET `name` = ?, `price` = ?, `dsc` = ?, `preparation` = ? WHERE `id` = ?";
    //         $stmt = $this->conn->prepare($updateqry);

    //         if ($stmt === false) {
    //             throw new Exception('Prepare failed: ' . $this->conn->error);
    //         }

    //         $stmt->bind_param('ssssi', $testName, $price, $dsc, $prep, $tid);
    //         $result = $stmt->execute();

    //         if ($result === false) {
    //             throw new Exception('Execute failed: ' . $stmt->error);
    //         }

    //         $response = ['status' => 1, 'affected_rows' => $stmt->affected_rows, 'message' => 'Data Updated'];

    //         $stmt->close();
    //         return json_encode($response);
    //     } catch (Exception $e) {
    //         return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
    //     }
    // }





    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function showTestList()
    {
        try {
            $selectTest = "SELECT * FROM `test_list`";
            $testQuery = $this->conn->query($selectTest);
            while ($result = $testQuery->fetch_assoc()) {
                $data[] = $result;
            }
            return $data;
        } catch (Exception $e) {
            $e->getMessage();
        }
    } // end showSubTests function







    /******************** Used In ********************
     *                                   
     *  1. /components/TestReportBody.inc.php
     *  2. 
     * */
    function showTestById($testId)
    {
        try {
            $selectTestById = "SELECT * FROM test_list WHERE `id` = ?";

            $stmt = $this->conn->prepare($selectTestById);
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('i', $testId);

            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $data = $result->fetch_object();
                return json_encode(['status' => true, 'data' => $data]);
            } else {
                return json_encode(['status' => false, 'message' => 'No data found']);
            }

            $stmt->close();
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }







    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function showTestByCat($catId)
    {
        try {
            $query = "SELECT * FROM `test_list` WHERE `cat_id` = '$catId'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return json_encode(['status' => true, 'message' => 'Data retrieved successfully', 'data' => $data]);
            } else {
                return json_encode(['status' => false, 'message' => 'No data found']);
            }
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }


    /********************************************************************************************
     *                                     Test Parameter Heading Table                                 *
     ********************************************************************************************/



     /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function addTestParameterHead($paramId, $name, $addedOn)
    {
        try {
            $addQuery = "INSERT INTO `test_parameter_head`(`parameter_id`, `name`, `added_on`) VALUES (?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);

            $stmt->bind_param("iss", $paramId, $name, $addedOn);

            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $insertId = $stmt->insert_id;

                return json_encode([
                    "status" => true,
                    "message" => "Parameter added successfully.",
                    "insertId" => $insertId
                ]);
            } else {
                return json_encode([
                    "status" => false,
                    "message" => "Failed to add parameter."
                ]);
            }

            $stmt->close();
        } catch (Exception $e) {
            error_log("Error inserting test parameter: " . $e->getMessage());
            return json_encode([
                "status" => false,
                "message" => "An error occurred while adding the parameter."
            ]);
        }
    }






    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function updateTestParameterHead($id, $name, $addedOn)
    {
        $response = array('status' => false, 'message' => '');

        try {

            $updateQuery = "UPDATE `test_parameter_head` SET `name` = ?, `added_on` = ? WHERE `id` = ?";

            if ($stmt = $this->conn->prepare($updateQuery)) {

                $stmt->bind_param("ssi", $name, $addedOn, $id);

                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $response['status'] = true;
                        $response['message'] = 'Parameter updated successfully.';
                    } else {
                        $response['status'] = false;
                        $response['message'] = 'No changes were made or parameter not found.';
                    }
                } else {
                    throw new Exception('Failed to execute the query: ' . $stmt->error);
                }

                $stmt->close();
            } else {
                throw new Exception('Failed to prepare the query: ' . $this->conn->error);
            }
        } catch (Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }
        return json_encode($response);
    }






    /******************** Used In ********************
     *                                   
     *  1. /components/TestReportBody.inc.php
     *  2. 
     * */
    function showHeadByParameterId($paramId)
    {
        try {
            $query = "SELECT * FROM `test_parameter_head` WHERE `parameter_id` = '$paramId'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return json_encode(['status' => true, 'message' => 'Data retrieved successfully', 'data' => $data]);
            } else {
                return json_encode(['status' => false, 'message' => 'No data found']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }





    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function deleteParamHeadById($delId)
    {
        try {
            $delQuery = "DELETE FROM `test_parameter_head` WHERE id=?";
            $stmt = $this->conn->prepare($delQuery);

            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('i', $delId);
            $result = $stmt->execute();

            if ($result === false) {
                throw new Exception('Execute failed: ' . $stmt->error);
            }

            if ($stmt->affected_rows > 0) {
                return json_encode(['status' => true, 'message' => 'Parameter deleted successfully']);
            } else {
                return json_encode(['status' => false, 'message' => 'No record found to delete']);
            }

            $stmt->close();
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            if (strpos($errorMsg, 'Cannot delete or update a parent row: a foreign key constraint fails') !== false) {
                return json_encode(['status' => false, 'message' => 'Cannot delete as parameter used in old test records.']);
            } else {
                return json_encode(['status' => false, 'message' => 'Error: ' . $errorMsg]);
            }
        }
    }


    /********************************************************************************************
     *                                     Test Parameters Table                                 *
     ********************************************************************************************/



     /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function addTestParameters($testId, $paramName, $unit, $status, $addedOn)
    {
        try {
            $addQuery = "INSERT INTO `test_parameters`(`test_id`, `name`, `unit`, `status`, `added_on`) VALUES (?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);

            $stmt->bind_param("issis", $testId, $paramName, $unit, $status, $addedOn);

            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $insertId = $stmt->insert_id;

                return json_encode([
                    "status" => true,
                    "message" => "Parameter added successfully.",
                    "insertId" => $insertId
                ]);
            } else {
                return json_encode([
                    "status" => false,
                    "message" => "Failed to add parameter."
                ]);
            }

            $stmt->close();
        } catch (Exception $e) {
            error_log("Error inserting test parameter: " . $e->getMessage());
            return json_encode([
                "status" => false,
                "message" => "An error occurred while adding the parameter." . $e->getMessage()
            ]);
        }
    }






    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    // function updateParametersByParameterId($paramid, $paramName, $unit, $status, $addedOn){
    // echo $paramid,$paramName, $unit, $status, $addedOn;
    //     $response = array('status' => false, 'message' => '');

    //     try {

    //         $updateQuery = "UPDATE `test_parameters` SET `name` = ?, `unit` = ?, `status` = ?, `added_on` = ? WHERE `id` = ?";

    //         if ($stmt = $this->conn->prepare($updateQuery)) {

    //             $stmt->bind_param("ssisi", $paramName, $unit, $status, $addedOn, $paramid);

    //             if ($stmt->execute()) {
    //                 if ($stmt->affected_rows > 0) {
    //                     $response['status'] = true;
    //                     $response['message'] = 'Parameter updated successfully.';
    //                 } else {
    //                     $response['message'] = 'No changes were made or parameter not found.';
    //                 }
    //             } else {
    //                 throw new Exception('Failed to execute the query: ' . $stmt->error);
    //             }

    //             $stmt->close();
    //         } else {
    //             throw new Exception('Failed to prepare the query: ' . $this->conn->error);
    //         }
    //     } catch (Exception $e) {
    //         $response['message'] = $e->getMessage();
    //     }
    //     return json_encode($response);
    // }


    /******************** Used In ********************
     *                                   
     *  1. admin/ajax/add-edit-labtest-data.ajax.php
     *  2. 
     * 
     * ******************** Changes ********************
     *  Description : Changed to update and insert if not exists
     * 
     * */
    function updateParametersByParameterId($paramid, $testId, $paramName, $unit, $status, $addedOn) {
        $response = array('status' => false, 'message' => '');
    
        try {
            // Use ON DUPLICATE KEY UPDATE to insert or update in one query
            $query = "INSERT INTO `test_parameters` (`id`, `test_id`, `name`, `unit`, `status`, `added_on`)
                      VALUES (?, ?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE
                      `name` = VALUES(`name`), `test_id` = VALUES(`test_id`), `unit` = VALUES(`unit`), `status` = VALUES(`status`), `added_on` = VALUES(`added_on`)";
    
            if ($stmt = $this->conn->prepare($query)) {
                $stmt->bind_param("isssis", $paramid, $testId, $paramName, $unit, $status, $addedOn);
                $stmt->execute();
    
                // Check if the operation was successful
                if ($stmt->affected_rows > 0) {
                    $response['status'] = true;
                    $response['message'] = ($stmt->insert_id > 0) ? 'Parameter updated successfully.' : 'Parameter updated successfully.';
                } else {
                    $response['message'] = 'No changes were made.';
                }
                
                $stmt->close();
            } else {
                throw new Exception('Failed to prepare the query: ' . $this->conn->error);
            }
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
    
        return json_encode($response);
    }
    


    /******************** Used In ********************
     *                                   
     *  1. /components/TestReportBody.inc.php
     *  2. 
     * */
    function showParametersByTest($testId)
    {
        try {
            $query = "SELECT * FROM `test_parameters` WHERE `test_id` = '$testId'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return json_encode(['status' => true, 'message' => 'Data retrieved successfully', 'data' => $data]);
            } else {
                return json_encode(['status' => false, 'message' => 'No data found']);
            }
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }


    /******************** Used In ********************
     *                                   
     *  1. /components/TestReportBody.inc.php
     *  2. 
     * */
    function showTestByParameter($paramId)
    {
        try {
            $query = "SELECT * FROM `test_parameters` WHERE `id` = '$paramId'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data = $row;
                }
                return json_encode(['status' => true, 'message' => 'Data retrieved successfully', 'data' => $data]);
            } else {
                return json_encode(['status' => false, 'message' => 'No data found']);
            }
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    function testIdByParameter($paramId)
    {
        try {
            $query = "SELECT test_id FROM `test_parameters` WHERE `id` = '$paramId'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                return json_encode(['status' => true, 'message' => 'Data retrieved successfully', 'data' => $data]);
            } else {
                return json_encode(['status' => false, 'message' => 'No data found']);
            }
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }



    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function deleteByParamId($delId)
    {
        try {
            $delQuery = "DELETE FROM `test_parameters` WHERE id=?";
            $stmt = $this->conn->prepare($delQuery);

            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('i', $delId);
            $result = $stmt->execute();

            if ($result === false) {
                throw new Exception('Execute failed: ' . $stmt->error);
            }

            if ($stmt->affected_rows > 0) {
                return json_encode(['status' => true, 'message' => 'Parameter deleted successfully']);
            } else {
                return json_encode(['status' => false, 'message' => 'No record found to delete']);
            }

            $stmt->close();
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            if (strpos($errorMsg, 'Cannot delete or update a parent row: a foreign key constraint fails') !== false) {
                return json_encode(['status' => false, 'message' => 'Cannot delete as parameter used in old test records.']);
            } else {
                return json_encode(['status' => false, 'message' => 'Error: ' . $errorMsg]);
            }
        }
    }




    /********************************************************************************************
     *                                   test_standard_range Table                               *
     ********************************************************************************************/

/******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function addTestStandardRange($paramId, $childData, $adultMaleData, $adultFemaleData, $generalData, $status, $addedOn)
    {
        try {
            $addQuery = "INSERT INTO `test_standard_range`(`parameter_id`, `child`, `adult_male`, `adult_female`, `general`, `status`, `added_on`) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($addQuery);

            $stmt->bind_param("issssis", $paramId, $childData, $adultMaleData, $adultFemaleData, $generalData, $status, $addedOn);

            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $insertId = $stmt->insert_id;

                return json_encode([
                    "status" => true,
                    "message" => "Test standard range added successfully.",
                    "insertId" => $insertId
                ]);
            } else {
                return json_encode([
                    "status" => false,
                    "message" => "Failed to add test standard range."
                ]);
            }

            $stmt->close();
        } catch (Exception $e) {
            error_log("Error inserting test standard range: " . $e->getMessage());
            return json_encode([
                "status" => false,
                "message" => "An error occurred while adding the test standard range." . $e->getMessage()
            ]);
        }
    }




    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    // function updateTestStandardRange($rangeId, $childData, $adultMaleData, $adultFemaleData, $generalData, $status, $addedOn)
    // {
    //     $response = ['status' => false, 'message' => ''];
    //     try {

    //         $stmt = $this->conn->prepare("UPDATE `test_standard_range` SET `child`=?, `adult_male`=?, `adult_female`=?, `general`=?, `status`=?, `added_on`=? WHERE `id` = ?");
    //         if (!$stmt) {
    //             throw new Exception('Failed to prepare the statement: ' . $this->conn->error);
    //         }

    //         if (!$stmt->bind_param("ssssisi", $childData, $adultMaleData, $adultFemaleData, $generalData, $status, $addedOn, $rangeId)) {
    //             throw new Exception('Failed to bind parameters: ' . $stmt->error);
    //         }

    //         if (!$stmt->execute()) {
    //             throw new Exception('Failed to execute the statement: ' . $stmt->error);
    //         }

    //         $response['status'] = ($stmt->affected_rows > 0);
    //         $response['message'] = $response['status'] ? 'Test standard range updated successfully.' : 'No changes were made or range not found.';
    //     } catch (Exception $e) {
    //         $response['message'] = $e->getMessage();
    //     } finally {
    //         if (isset($stmt) && $stmt) {
    //             $stmt->close();
    //         }
    //     }
    //     return json_encode($response);
    // }

    function upsertTestStandardRange($rangeId, $parameterId, $childData, $adultMaleData, $adultFemaleData, $generalData, $status, $addedOn)
{
    $response = ['status' => false, 'message' => ''];
    try {
        // Use MySQL's INSERT...ON DUPLICATE KEY UPDATE to handle both insertion and update in a single query
        $stmt = $this->conn->prepare("
            INSERT INTO `test_standard_range` (`id`, `parameter_id`, `child`, `adult_male`, `adult_female`, `general`, `status`, `added_on`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                `child` = VALUES(`child`),
                `parameter_id` = VALUES(`parameter_id`),
                `adult_male` = VALUES(`adult_male`),
                `adult_female` = VALUES(`adult_female`),
                `general` = VALUES(`general`),
                `status` = VALUES(`status`),
                `added_on` = VALUES(`added_on`)
        ");

        if (!$stmt) {
            throw new Exception('Failed to prepare the statement: ' . $this->conn->error);
        }

        // Bind parameters for the statement
        if (!$stmt->bind_param("iissssis", $rangeId, $parameterId, $childData, $adultMaleData, $adultFemaleData, $generalData, $status, $addedOn)) {
            throw new Exception('Failed to bind parameters: ' . $stmt->error);
        }

        // Execute the statement
        if (!$stmt->execute()) {
            throw new Exception('Failed to execute the statement: ' . $stmt->error);
        }

        // Determine if it was an update or insert by checking affected rows
        $response['status'] = true;
        $response['message'] = ($stmt->affected_rows > 1) ? 'Test standard range updated successfully.' : 'Test standard range inserted successfully.';
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    } finally {
        if (isset($stmt) && $stmt) {
            $stmt->close();
        }
    }
    return json_encode($response);
}






/******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function showParameterById($range_id)
    {
        try {
            $query = "SELECT * FROM `test_standard_range` WHERE `id` = '$range_id'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data = $row;
                }
                return json_encode(['status' => true, 'message' => 'Data retrieved successfully', 'data' => $data]);
            } else {
                return json_encode(['status' => false, 'message' => 'No data found']);
            }
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }




/******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function showRangeByParameter($paramId)
    {
        try {
            $query = "SELECT * FROM `test_standard_range` WHERE `parameter_id` = '$paramId'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $result = $stmt->get_result();

            $data = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data = $row;
                }
                return json_encode(['status' => true, 'message' => 'Data retrieved successfully', 'data' => $data]);
            } else {
                return json_encode(['status' => false, 'message' => 'No data found']);
            }
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }




    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function deleteStandardRangeData($delId)
    {
        try {
            $delQuery = "DELETE FROM `test_standard_range` WHERE parameter_id=?";
            $stmt = $this->conn->prepare($delQuery);

            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('i', $delId);
            $result = $stmt->execute();

            if ($result === false) {
                throw new Exception('Execute failed: ' . $stmt->error);
            }

            if ($stmt->affected_rows > 0) {
                return json_encode(['status' => true, 'message' => 'Parameter deleted successfully']);
            } else {
                return json_encode(['status' => false, 'message' => 'No record found to delete']);
            }

            $stmt->close();
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            if (strpos($errorMsg, 'Cannot delete or update a parent row: a foreign key constraint fails') !== false) {
                return json_encode(['status' => false, 'message' => 'Cannot delete as parameter used in old test records.']);
            } else {
                return json_encode(['status' => false, 'message' => 'Error: ' . $errorMsg]);
            }
        }
    }





    /*====================================================================
    //                     GENERAL EDIT QUERY                           //           ==================================================================== */


    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function updateDataOnTableAttributeValue($tableName, $editId, $attributeName, $value)
    {
        try {
            $updateQry = "UPDATE `$tableName` SET `$attributeName` = ? WHERE id = ?";
            $stmt = $this->conn->prepare($updateQry);

            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('si', $value, $editId);

            if (!$stmt->execute()) {
                throw new Exception('Execute failed: ' . $stmt->error);
            }

            if ($stmt->affected_rows > 0) {
                return json_encode(['status' => true, 'message' => 'Record updated successfully.']);
            } else {
                return json_encode([
                    'status' => false,
                    'message' => 'No record updated. Data may be used in another table or no change was made.'
                ]);
            }
        } catch (Exception $e) {
            return json_encode([
                'status' => false,
                'message' => 'Error updating record: ' . $e->getMessage()
            ]);
        }
    }




    // delete parameter and head query
    // SPECIAL DELETE FUNCTION
    // DELETE FROM BOTH RANGE TABLE AND HEAD TABLE
    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function checkExistanceFromRangeAndHeadTable($testParamId)
    {
        try {
            $selectQuery = "SELECT test_standard_range.id, test_parameter_head.id 
                            FROM test_standard_range
                            INNER JOIN test_parameter_head ON test_standard_range.parameter_id = test_parameter_head.parameter_id
                            WHERE test_parameter_head.parameter_id = ?";

            $stmt = $this->conn->prepare($selectQuery);

            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('i', $testParamId);

            if (!$stmt->execute()) {
                throw new Exception('Execute failed: ' . $stmt->error);
            }

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return json_encode(['status' => true, 'message' => 'Record found successfully.']);
            } else {
                return json_encode(['status' => false, 'message' => 'No record found.']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }





    /******************** Used In ********************
     *                                   
     *  1. 
     *  2. 
     * */
    function deleteFromRangeAndHeadTable($delId)
    {
        try {
            $delQuery = "DELETE test_standard_range, test_parameter_head
                         FROM test_standard_range
                         INNER JOIN test_parameter_head 
                         ON test_standard_range.parameter_id = test_parameter_head.parameter_id
                         WHERE test_standard_range.parameter_id = ?";

            $stmt = $this->conn->prepare($delQuery);

            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param('i', $delId);

            $result = $stmt->execute();

            if ($result === false) {
                throw new Exception('Execute failed: ' . $stmt->error);
            }

            if ($stmt->affected_rows > 0) {
                $response = json_encode(['status' => true, 'message' => 'Parameter deleted successfully']);
            } else {
                $response = json_encode(['status' => false, 'message' => 'No record found to delete']);
            }

            $stmt->close();

            return $response;
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            if (strpos($errorMsg, 'Cannot delete or update a parent row: a foreign key constraint fails') !== false) {
                return json_encode(['status' => false, 'message' => 'Cannot delete as parameter is used in old test records.']);
            } else {
                return json_encode(['status' => false, 'message' => 'Error: ' . $errorMsg]);
            }
        }
    }
} //end of LabTypes Class
