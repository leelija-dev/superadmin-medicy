<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'distributor.class.php';
require_once CLASS_DIR . 'utility.class.php';

//Class initilization
$Distributor = new Distributor();
$Utility        = new Utility;

$ticketNo = $Utility->ticketNumberGenerator(); 

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
</head>

<body>

    <?php
    
    if (isset($_POST['distributor-data-add'])) {
        $distributorName        = $_POST['distributor-name'];
        $distributorPhno        = $_POST['distributor-phno'];
        $distributorGSTID       = $_POST['distributor-gstid'];
        $distributorEmail       = $_POST['distributor-email'];
        $distributorAddress     = $_POST['distributor-address'];
        $distributorAreaPIN     = $_POST['distributor-area-pin'];
        $distributorDsc         = $_POST['distributor-dsc'];

        $distributorStatus      = 0;
        $newData                = 1;

        // echo $ticketNo;
        // exit;
        //Insert Into Distributor DB
        $addDistributor = $Distributor->addDistributor($ticketNo, $distributorName, $distributorGSTID, $distributorAddress, $distributorAreaPIN, $distributorPhno, $distributorEmail, $distributorDsc, $employeeId, NOW,  $distributorStatus,$newData, $ADMINID);
    
        $addDistributor = json_decode($addDistributor);

        if ($addDistributor->status) {
    ?>
            <script>
                swal("Success", "Distributor Insertion Requested, Now You Can Purchase From This Distributor!", "success")
                    .then((value) => {
                        window.location = `<?= URL ?>distributor.php`;
                    });
            </script>
        <?php
        } else {
        ?>
            <script>
                swal("Error", "Data Not Added!", "error")
                    .then((value) => {
                        window.location = `<?= URL ?>distributor.php`;
                    });
            </script>
    <?php
        }
    }

    ?>
</body>

</html>