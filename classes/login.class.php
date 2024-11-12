<?php
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'idsgeneration.class.php';



class LoginForm
{
    use DatabaseConnection;

    function login($email, $password, $roleData)
    {

        // ======== employee login =========
        $sql = "SELECT * FROM `employees` WHERE `emp_email` = '$email' OR `emp_username` = '$email'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_object()) {

                $dbPasshash = $data->emp_password;
                $x_password = pass_dec($dbPasshash, EMP_PASS);

                if ($x_password === $password) {

                    session_start();
                    $_SESSION['LOGGEDIN']       = true;
                    $_SESSION['ADMIN']          = false;
                    $_SESSION['USER_TYPE']      = 'USER';
                    $_SESSION['EMP_EMAIL']      = $data->emp_email;
                    $_SESSION['EMP_CONTACT_NO'] = $data->contact;
                    $_SESSION['EMP_ROLE']       = $data->emp_role;
                    $_SESSION['EMP_FNAME']      = $data->fname;
                    $_SESSION['EMP_LNAME']      = $data->lname;
                    $_SESSION['EMP_IMG']        = $data->emp_img;
                    $_SESSION['EMP_ADDRESS']    = $data->emp_address;
                    $_SESSION['EMP_USERNAME']   = $data->emp_username;
                    $_SESSION['EMP_PASSWORD']   = $data->emp_password;
                    $_SESSION['EMPID']          = $data->emp_id;
                    $_SESSION['ADMIN_ID']       = $data->admin_id;
                    $_SESSION['ACCESS_PERMISSION']       = $data->permission_id;


                    $this->insertLoginTime($data->admin_id, $data->emp_id, NOW);

                    header("Location: " . URL);
                    exit;
                } else {
                    return 'Wrong Password';
                }
            }
        } else {

            // =============== ADMIN LOGIN ===============
            $admSql1 = "SELECT * FROM `admin` WHERE (`email` = '$email' OR `username` = '$email') AND `reg_status`= 1";

            $result1 = $this->conn->query($admSql1);

            // ======== unsuccessfull admin registration process =========
            if ($result1->num_rows == 0) {

                $admSql2 = "SELECT * FROM `admin` WHERE (`email` = '$email' OR `username` = '$email') AND `reg_status`= 0";

                $result2 = $this->conn->query($admSql2);



                if ($result2->num_rows > 0) {
                    $OTP = otpGgenerator();
                    $timeout_duration = 600; // 10 * 60 seconds

                    while ($admData2 = $result2->fetch_object()) {
                        // print_r($admData2);

                        $dbPasshash = $admData2->password;
                        $x_password = pass_dec($dbPasshash, ADMIN_PASS);

                        if ($x_password === $password) {

                            session_start();

                            $_SESSION['REGISTRATION']       = true;
                            $_SESSION['ADMIN_REGISER']      = true;
                            $_SESSION['PRIMARY_REGISTER']   = false;
                            $_SESSION['SECONDARY_REGISTER'] = true;
                            $_SESSION['session_start_time'] = date('H:i:s');
                            $_SESSION['time_out']           = $timeout_duration;
                            $_SESSION['verify_key']         = $OTP;
                            $_SESSION['first-name']         = $admData2->fname;
                            $_SESSION['email']              = $admData2->email;
                            $_SESSION['username']           = $admData2->username;
                            $_SESSION['adm_id']             = $admData2->admin_id;

                            header("Location: register-mail.inc.php");
                            exit;
                        }
                        return 'Wrong Password';
                    }
                }
                return 'not found';
            }

            // ======== admin login =========
            if ($result1->num_rows > 0) {
                while ($admData1 = $result1->fetch_object()) {

                    $dbPasshash = $admData1->password;
                    $x_password = pass_dec($dbPasshash, ADMIN_PASS);
                    // exit;

                    if ($x_password === $password) {

                        session_start();
                        $_SESSION['LOGGEDIN']           = true;
                        $_SESSION['ADMIN']              = true;
                        $_SESSION['USER_TYPE']          = 'NOT USER';
                        $_SESSION['ADMIN_EMAIL']        = $admData1->email;
                        $_SESSION['USER_ROLE']          = 'ADMIN';
                        $_SESSION['ADMIN_FNAME']        = $admData1->fname;
                        $_SESSION['ADMIN_LNAME']        = $admData1->lname;
                        $_SESSION['ADMIN_IMG']          = $admData1->adm_img;
                        $_SESSION['ADMIN_CONTACT_NO']   = $admData1->mobile_no;
                        $_SESSION['ADMIN_USERNAME']     = $admData1->username;
                        $_SESSION['ADMIN_PASSWORD']     = $admData1->password;
                        $_SESSION['ADMIN_ADDRESS']      = $admData1->address;
                        $_SESSION['ADMINID']            = $admData1->admin_id;
                        $_SESSION['ACCESS_PERMISSION']  = $admData1->permission_id;
                        

                        // Insert login time into login_time table
                        $this->insertLoginTime($admData1->admin_id, null, NOW);


                        header("Location: " . URL);
                        exit;
                    } else {
                        return 'Wrong Password';
                    }
                }
            }
        }
    }


    /// =======for login time =========///
    function insertLoginTime($adminId, $empId, $loginTime)
    {
        $sql = "INSERT INTO login_activity (admin_id, emp_id, login_time) VALUES ('$adminId', '$empId', '$loginTime')";
        $result = $this->conn->query($sql);
        if ($result) {
            return true;
        } else {
            error_log("Error inserting login time: " . $this->conn->error);
            return false; 
        }
    }    
}
