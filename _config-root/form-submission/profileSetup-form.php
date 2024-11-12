<?php

require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'utilityImage.class.php';
require_once CLASS_DIR . 'empRole.class.php';

$Admin      = new Admin;
$Employees  = new Employees;
$ImageUtil  = new ImageUtil;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <script src="<?php echo JS_PATH ?>sweetAlert.min.js"></script>
</head>

<body>
    <?php
    if (isset($_FILES['profile-image'])) {

        $imageName         = $_FILES['profile-image']['name'];
        $imageTempName     = $_FILES['profile-image']['tmp_name'];

        $response = $ImageUtil->uploadAndDeleteImage($imageName, $imageTempName, ADM_IMG_DIR, 'admin', 'adm_img', 'admin_id', $adminId);
        $response = json_decode($response);
        if ($response->status == 1) {
            $_SESSION['ADMIN_IMG'] = $response->image_name;
            $flag = 1;
        } else {
            $flag = 0;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['submit'])) {

            $fname   = $_POST['fname'];
            $lname   = $_POST['lname'];
            $email   = $_POST['email'];
            $phNo    = $_POST['mobile-number'];
            $address = $_POST['address'];

            $flag = 0;

            if ($_SESSION['ADMIN']) {
                $updateAdminData = $Admin->updateAdminDetails($fname, $lname, $email, $phNo,  $address, NOW, $ADMINID);
                if ($updateAdminData['result']) {
                    $flag = 1;
                }
            } else {
                $updateEmployeeData = $Employees->updateEmpData($fname, $lname, $email, $phNo, $address, NOW, $employeeId, $ADMINID);
                if ($updateEmployeeData['result']) {
                    $flag = 1;
                }
            }
        }
    }
    ?>

    <?php if ($flag == 1) {  ?>

        <script>
            swal("Success", "Successfully Updated!", "success").then((value) => {
                window.location = '<?php echo URL ?>profile.php';
            });
        </script>

    <?php } else { ?>

        <script>
            swal("Error", "Updation Fails!", "error").then((value) => {
                window.location = '<?php echo URL ?>profile.php';
            });
        </script>

    <?php } ?>
</body>
</html>