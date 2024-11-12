<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'utility.class.php';

//Class initilization
$Manufacturer = new Manufacturer();
$Utility        = new Utility;

$ticketNo = $Utility->ticketNumberGenerator();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manufacturer</title>
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
</head>

<body>

    <?php

    //Class initilization
    $Manufacturer = new Manufacturer();

    if (isset($_POST['add-manufacturer'])) {
        $manufacturerName   = $_POST['manuf-name'];
        $manufacturerDsc    = $_POST['manuf-dsc'];
        $shortName          = $_POST['manuf-mark'];
        $manufactureStatus  = 0;
        $newData            = 1;


        // last inserted manufacturer data fetch ---------
        $manufData = json_decode($Manufacturer->lastManufDataFetch());
        if ($manufData != null) {
            $manufId = intval($manufData->id) + 1;
        } else {
            $manufId = 1;
        }


        $addManufacturer = $Manufacturer->addManufacturer($manufId, $ticketNo, $manufId, $manufacturerName, $shortName, $manufacturerDsc, $employeeId, NOW, $manufactureStatus, $newData, $adminId);
        if ($addManufacturer) {
    ?>
            <script>
                swal("Success", "Manufacturer Added!", "success")
                    .then((value) => {
                        window.location = '<?= URL ?>purchase-master.php';
                    });
            </script>
        <?php
        } else {
        ?>
            <script>
                swal("Error", "Manufacturer Addition Failed!", "error")
                    .then((value) => {
                        window.location = '<?= URL ?>purchase-master.php';
                    });
            </script>
    <?php
        }
    }

    ?>
</body>

</html>