<?php
require_once CLASS_DIR . 'encrypt.inc.php';

class recoverPass
{
    use DatabaseConnection;


    // function recoverPassword($user)
    // {
    //     try {

    //         $chkAdmin = " SELECT * FROM `admin` WHERE `username`= '$user' OR `email` = '$user'";

    //         $admStmt = $this->conn->prepare($chkAdmin);
    //         $admStmt->execute();            
    //         $admRes = $admStmt->get_result();


    //         $chkEmployee = " SELECT * FROM `employees` WHERE `emp_username`= '$user' OR `emp_email` = '$user' ";

    //         $empStmt = $this->conn->prepare($chkEmployee);
    //         $empStmt->execute();
    //         $empRes = $empStmt->get_result();


    //         if ($admRes->num_rows > 0) {
    //             $data = array();
    //             while ($result = $admRes->fetch_object()) {
    //                 $data[] = $result;
    //             }
    //             $admStmt->close();
    //             return json_encode(['status' => '1', 'message' => 'adminData', 'data' => $data]);
    //         } elseif ($empRes->num_rows > 0) {
    //             $data = array();
    //             while ($result = $empRes->fetch_object()) {
    //                 $data[] = $result;
    //             }
    //             $empStmt->close();
    //             return json_encode(['status' => '1', 'message' => 'empData', 'data' => $data]);
    //         } else {
    //             $admStmt->close();
    //             $empStmt->close();
    //             return json_encode(['status' => '0', 'message' => 'no data', 'data' => '']);
    //         }
    //     } catch (Exception $e) {
    //         return json_encode(['status' => '', 'message' => $e->getMessage(), 'data' => '']);
    //     }
    //     return 0;
    // }





    function recoverPassword($user)
    {
        try {
            $chkAdmin = "SELECT * FROM `admin` WHERE `username`= ? OR `email` = ?";
            $admStmt = $this->conn->prepare($chkAdmin);
            $admStmt->bind_param("ss", $user, $user);
            $admStmt->execute();
            $admRes = $admStmt->get_result();
            $admStmt->close();

            if ($admRes->num_rows > 0) {
                $data = array();
                while ($result = $admRes->fetch_object()) {
                    $data[] = $result;
                }
                return json_encode(['status' => '1', 'message' => 'adminData', 'data' => $data]);
            } else {
                $chkEmployee = "SELECT * FROM `employees` WHERE `emp_username`= ? OR `emp_email` = ?";
                $empStmt = $this->conn->prepare($chkEmployee);
                $empStmt->bind_param("ss", $user, $user);
                $empStmt->execute();
                $empRes = $empStmt->get_result();
                $empStmt->close();

                if ($empRes->num_rows > 0) {
                    $data = array();
                    while ($result = $empRes->fetch_object()) {
                        $data[] = $result;
                    }
                    return json_encode(['status' => '1', 'message' => 'empData', 'data' => $data]);
                }
            }

            return json_encode(['status' => '0', 'message' => 'no data', 'data' => '']);
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => $e->getMessage(), 'data' => '']);
        }
    }











    // function adminPassRecover($user)
    // {
    //     try {
    //         $stmt = $this->conn->prepare("SELECT * FROM `admin` WHERE (`email` = ? OR `username` = ?) AND `reg_status` = 1");

    //         $stmt->bind_param("ss", $user, $user);

    //         $stmt->execute();

    //         $result = $stmt->get_result();

    //         if ($result->num_rows > 0) {
    //             $admData = array();
    //             while ($res = $result->fetch_object()) {
    //                 $admData[] = $res;
    //             }
    //             $response = ['status' => '1', 'data' => $admData];
    //         } else {
    //             $response = ['status' => '0', 'message' => 'No data found', 'data' => ''];
    //         }

    //         $stmt->close();

    //         return json_encode($response);
    //     } catch (Exception $e) {
    //         return "Error: " . $e->getMessage();
    //     }
    // }







    function adminData($adm)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM `admin` WHERE (`email` = ? OR `username` = ?) AND `reg_status` = 1");

            $stmt->bind_param("ss", $adm, $adm);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $admData = array();
                while ($res = $result->fetch_object()) {
                    $admData[] = $res;
                }
                $response = ['status' => '1', 'data' => $admData];
            } else {
                $response = ['status' => '0', 'message' => 'No data found', 'data' => ''];
            }

            $stmt->close();

            return json_encode($response);
        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => 'Error: ' . $e->getMessage(), 'data' => '']);
        }
    }






    function employeePassRecover($adminId, $user)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM `employees` WHERE (`emp_email` = ? OR `emp_username` = ?) AND `admin_id` = ?");

            $stmt->bind_param("sss", $user, $user, $adminId);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $empData = array();
                while ($res = $result->fetch_object()) {
                    $empData[] = $res;
                }
                $response = ['status' => '1', 'data' => $empData];
            } else {
                $response = ['status' => '0', 'message' => 'No data found', 'data' => ''];
            }

            $stmt->close();

            return json_encode($response);
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
} //eof recover password Class
