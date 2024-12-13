<?php
require_once CLASS_DIR.'encrypt.inc.php';

class SuperAdminLoginForm{
    use DatabaseConnection;

    function supAdminLogin($email, $password){

        $sql = "SELECT * FROM `super_admin` WHERE `email` = '$email' OR `username` = '$email'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_object()) {
                
                $dbPasshash = $data->password;
                $x_password = pass_dec($dbPasshash, ADMIN_PASS);
                // exit;

                if ($password === $x_password) {
                    // print_r($data);  die;
                    session_start();
                    $_SESSION['SUPERADMINLOGGEDIN']       = true;
                    $_SESSION['SUPER_ADMIN']              = true;
                    $_SESSION['USER_TYPE']                = 'NOT USER';
                    $_SESSION['SUPER_ADMIN_EMAIL']        = $data->email;
                    $_SESSION['USER_ROLE']                = 'SUPER ADMIN';
                    $_SESSION['SUPER_ADMIN_FNAME']        = $data->fname;
                    $_SESSION['SUPER_ADMIN_LNAME']        = $data->lname;
                    $_SESSION['SUPER_ADMIN_IMG']          = $data->adm_img;    
                    $_SESSION['SUPER_ADMIN_CONTACT_NO']   = $data->mobile_no;
                    $_SESSION['SUPER_ADMIN_USERNAME']     = $data->username;
                    $_SESSION['SUPER_ADMIN_ADDRESS']      = $data->address;
                    $_SESSION['SUPER_ADMINID']            = $data->id;
                    
                    
                    header("Location:". ADM_URL);
                    exit;

                } else {
                    return 'Wrong Password';
                }
            }
        }
        return 'not found'; 
    }

}
?>