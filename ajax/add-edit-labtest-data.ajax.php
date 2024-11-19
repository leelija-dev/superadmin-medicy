<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

$Pathology = new Pathology;
$status = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action'])) {

        if ($_POST['action'] === 'add') {
            $addResponseArray = [];
            $rowCount = $_POST['param-row-count'];

            $parentCategoryId   = url_dec($_POST['lab-cat-id']);
            $testName           = $_POST['lab-test-name'];
            $testPrice          = $_POST['lab-test-price'];
            $testDescription    = $_POST['lab-test-dsc'];
            $testProcess        = $_POST['lab-test-process'];

            // Decode JSON data once and check for errors
            $testParamNameArray     = json_decode($_POST['paramName'], true);
            $testParamUnitArray     = json_decode($_POST['paramUnit'], true);
            $childRangeArray        = json_decode($_POST['childRange'], true);
            $adultMaleRangeArray    = json_decode($_POST['adultMaleRange'], true);
            $adultFemaleRangeArray  = json_decode($_POST['adultFemaleRange'], true);
            $generalRangeArray      = json_decode($_POST['generalRange'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['status' => false, 'message' => 'Invalid JSON data']);
                exit;
            }

            // Add new test data
            $addLabTestData = $Pathology->addNewTest($parentCategoryId, $testName, $testPrice, $testDescription, $testProcess, NOW);
            $addResponse = json_decode($addLabTestData);

            if ($addResponse->status) {
                $insertedTestId = $addResponse->inserted_id;

                foreach ($testParamNameArray as $index => $paramName) {
                    $headIndex = $index + 1;
                    $status = 1; // Assuming $status is used but not defined in the provided code

                    // Add test parameters
                    $addParameters = $Pathology->addTestParameters($insertedTestId, $paramName, $testParamUnitArray[$index], $status, NOW);
                    $parameterAddStatus = json_decode($addParameters);

                    if ($parameterAddStatus->status) {
                        $insertedParameterId = $parameterAddStatus->insertId;

                        // Add test parameter headers
                        if (!empty($_POST['param_header_name_' . $headIndex])) {
                            foreach ($_POST['param_header_name_' . $headIndex] as $headerName) {
                                if (!empty(trim($headerName))) {
                                    $addTestParamHead = $Pathology->addTestParameterHead($insertedParameterId, $headerName, NOW);
                                    $headPushReport = json_decode($addTestParamHead);
                                    array_push($addResponseArray, $headPushReport->status ? 1 : 0);
                                }
                            }
                        }

                        // Add standard ranges
                        $addStandardRanges = $Pathology->addTestStandardRange(
                            $insertedParameterId,
                            $childRangeArray[$index],
                            $adultMaleRangeArray[$index],
                            $adultFemaleRangeArray[$index],
                            $generalRangeArray[$index],
                            $status,
                            NOW
                        );
                        $standardRangeAddStatus = json_decode($addStandardRanges);
                        array_push($addResponseArray, $standardRangeAddStatus->status ? 1 : 0);
                    } else {
                        array_push($addResponseArray, 0);
                    }
                }
            } else {
                array_push($addResponseArray, 0);
            }

            // Final response
            $responseStatus = !in_array(0, $addResponseArray);
            $responseMessage = $responseStatus ? 'Data added' : 'Fault occurred during data addition';
            echo json_encode(['status' => $responseStatus, 'message' => $responseMessage]);
        }


        // edit test data
        if ($_POST['action'] === 'update') {

            $updateStatusArray = [];
            $addHeadOnOldParameter = '';

            $testId     = url_dec($_POST['lab-test-id']);
            $testName   = $_POST['lab-test-name'];
            $testPrice  = $_POST['lab-test-price'];
            $testDsc    = $_POST['lab-test-dsc'];
            $testPrep   = $_POST['lab-test-process'];
            $reportType = 1;


            $testParamIdArray       = json_decode($_POST['testParamEditId']);
            $testParamNameArray     = json_decode($_POST['paramName']);
            $testParamUnitArray     = json_decode($_POST['paramUnit']);
            $rangeAddEditId         = json_decode($_POST['rangeAddEditId']);
            $childRangeArray        = json_decode($_POST['childRange']);
            $adultMaleRangeArray    = json_decode($_POST['adultMaleRange']);
            $adultFemaleRangeArray  = json_decode($_POST['adultFemaleRange']);
            $generalRangeArray      = json_decode($_POST['generalRange']);

            // Update test details (name, price, description, and process)
            $updateTestList = json_decode($Pathology->updateTestData($testId, $testName, $testPrice, $testDsc, $testPrep, $reportType));
            // print_r($updateTestList);
            if ($updateTestList->status) {
                array_push($updateStatusArray, 1);
                
                // print_r($testParamIdArray);
                // print_r($testParamNameArray);
                // print_r($testParamUnitArray);
                // print_r($rangeAddEditId);
                // print_r($childRangeArray);
                // print_r($adultMaleRangeArray);
                // print_r($adultFemaleRangeArray);
                // print_r($generalRangeArray);

                foreach ($testParamNameArray as $index => $paramName) {
                    $headIndex = $index + 1;

                    $testParamId        = $testParamIdArray[$index];
                    $rangeId            = $rangeAddEditId[$index];
                    $paramUnit          = $testParamUnitArray[$index];
                    $childRange         = $childRangeArray[$index];
                    $adultMaleRange     = $adultMaleRangeArray[$index];
                    $adultFemaleRange   = $adultFemaleRangeArray[$index];
                    $generalRange       = $generalRangeArray[$index];

                    $headerIdArray = isset($_POST['param_header_id_' . $headIndex]) ? $_POST['param_header_id_' . $headIndex] : [];
                    $headerNameArray = isset($_POST['param_header_name_' . $headIndex]) ? $_POST['param_header_name_' . $headIndex] : [];
                    if ($testParamId) {
                        if ($rangeId != 0) {
                            // Update parameter
                            $paramUpdateStatus = 1;
                            $updateParameter = json_decode($Pathology->updateParametersByParameterId($testParamId, $testId, $paramName, $paramUnit, $paramUpdateStatus, NOW));
                            if ($updateParameter->status) {
                                array_push($updateStatusArray, 1);
                                
                                // UPDATE RANGE
                                $rangestatus = 1;
                                $updateRange = json_decode($Pathology->upsertTestStandardRange($rangeId, $testParamId, $childRange, $adultMaleRange, $adultFemaleRange, $generalRange, $rangestatus, NOW));
                                
                                if ($updateRange->status) {
                                    array_push($updateStatusArray, 1);
                                } else {
                                    array_push($updateStatusArray, 0);
                                }

                                // update header
                                if (!empty($headerIdArray) && !empty($headerNameArray)) {
                                    for ($i = 0; $i < count($headerIdArray); $i++) {
                                        $headerId   = $headerIdArray[$i];
                                        $headerName = $headerNameArray[$i];
                                        if (!empty(trim($headerName))) {
                                            if ($headerId != 0) {
                                                $updateOldHead = json_decode($Pathology->updateTestParameterHead($headerId, $headerName, NOW));

                                                if ($updateOldHead->status) {
                                                    array_push($updateStatusArray, 1);
                                                } else {
                                                    array_push($updateStatusArray, 0);
                                                }
                                            } else {
                                                // Add data with param id as foreign key
                                                $addHeadOnOldParameter = $Pathology->addTestParameterHead($testParamId, $headerName, NOW);
                                                $addHeadOnOldParameter = json_decode($addHeadOnOldParameter);
                                                if ($addHeadOnOldParameter->status) {
                                                    array_push($updateStatusArray, 1);
                                                } else {
                                                    array_push($updateStatusArray, 0);
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                array_push($updateStatusArray, 0);
                            }
                        } else {
                            // ADD RANGE
                            $rangestatus = 1;
                            $addRangeOnOldParam = $Pathology->addTestStandardRange($testParamId, $childRange, $adultMaleRange, $adultFemaleRange, $generalRange, $rangestatus, NOW);
                            $rangeAddStatus = json_decode($addRangeOnOldParam);
                            if ($rangeAddStatus->status) {
                                array_push($updateStatusArray, 1);
                            } else {
                                array_push($updateStatusArray, 0);
                            }
                        }
                    } else {

                        $addNewParameters = $Pathology->addTestParameters($testId, $paramName, $testParamUnitArray[$index], $status, NOW);
                        $newParameterAddStatus = json_decode($addNewParameters);
                        if ($newParameterAddStatus->status) {
                            array_push($updateStatusArray, 1);
                            $insertedParameterId = $newParameterAddStatus->insertId;

                            // ADD RANGE
                            $rangestatus = 1;
                            $addRangeOnNewParam = $Pathology->addTestStandardRange($insertedParameterId, $childRange, $adultMaleRange, $adultFemaleRange, $generalRange, $rangestatus, NOW);
                            $rangeAddStatus = json_decode($addRangeOnNewParam);
                            if ($rangeAddStatus->status) {
                                array_push($updateStatusArray, 1);
                            } else {
                                array_push($updateStatusArray, 0);
                            }

                            // add header
                            if (!empty($headerNameArray)) {
                                for ($i = 0; $i < count($headerNameArray); $i++) {
                                    $headerName = $headerNameArray[$i];
                                    if (!empty(trim($headerName))) {
                                        $addHeadOnNewParameter = $Pathology->addTestParameterHead($insertedParameterId, $headerName, NOW);
                                        // print_r($addHeadOnNewParameter);
                                        $addHeadOnOldParameter = json_decode($addHeadOnNewParameter);
                                        if ($addHeadOnOldParameter->status) {
                                            array_push($updateStatusArray, 1);
                                        } else {
                                            array_push($updateStatusArray, 0);
                                        }
                                    }
                                }
                            }
                        } else {
                            array_push($updateStatusArray, 0);
                    echo 'Hello 5';

                        }
                    }
                }
            } else {
                echo 'Hello 6';

                array_push($updateStatusArray, 0);
            }

            // print_r($updateStatusArray);
            // Check if all operations were successful
            $message = !in_array(0, $updateStatusArray)
                ? json_encode(['status' => true, 'message' => 'Data updated successfully'])
                : json_encode(['status' => false, 'message' => 'Some error occurred during data edit']);

            print_r($message);
        }


        // delete test parameter
        if ($_POST['action'] == 'delete') {

            $title = $_POST['title'];

            if ($title === 'Delete-Parameter') {
                $testParamId = $_POST['test-param-id'];

                // Check parameter ID existence in both range and head tables
                $checkResponse = json_decode($Pathology->checkExistanceFromRangeAndHeadTable($testParamId));

                if ($checkResponse->status) {
                    // Delete from both range and head tables
                    $deleteRangeAndHeadData = $Pathology->deleteFromRangeAndHeadTable($testParamId);
                    $checkRsponce = json_decode($deleteRangeAndHeadData);
                    if ($checkRsponce->status) {
                        // Delete the parameter
                        $delParamData = $Pathology->deleteByParamId($testParamId);
                        print_r($delParamData);
                    } else {
                        print_r($deleteRangeAndHeadData);
                    }
                } else {
                    // Delete only from the range table
                    $deleteRangeData = $Pathology->deleteStandardRangeData($testParamId);
                    $delRngStatus = json_decode($deleteRangeData);
                    if ($delRngStatus->status) {
                        // Delete the parameter
                        $delParamData = $Pathology->deleteByParamId($testParamId);
                        print_r($delParamData);
                    } else {
                        print_r($deleteRangeData);
                    }
                }
            }


            if ($title === 'Delete-head') {
                $delId = $_POST['head-id'];
                // echo $delId;
                // Delete specific head
                $deleteHead = $Pathology->deleteParamHeadById($delId);
                print_r($deleteHead);
            }
        }
    }



    if (isset($_POST['report-text-format'])) {

        $testId      = url_dec($_POST['test_id']);
        $testName    = $_POST['test_name'];
        $testPrice   = $_POST['test_price'];
        $testDsc     = $_POST['test_dsc'];
        $testPrep    = $_POST['test_prep'];
        $textFormat  = $_POST['report-text-format'];
        $reportType  = 2;

        $response = json_decode($Pathology->updateTestData($testId, $testName, $testPrice, $testDsc, $testPrep, $reportType, $textFormat));
        echo json_encode(['status' => $response->status, 'message' => $response->message]);
    }
}
