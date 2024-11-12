<?php
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');
require_once SUP_ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'measureOfUnit.class.php';

//Class initilization
$MeasureOfUnits = new MeasureOfUnits();

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

    if (isset($_POST['add-unit'])) {
        // echo $supAdminId;
        $srtName  = $_POST['unit-srt-name'];
        $fullName = $_POST['unit-full-name'];
        $newData  = 1;
        $addMeasureOfUnits = $MeasureOfUnits->addMeasureOfUnits($srtName, $fullName, $employeeId, NOW,$newData, $supAdminId);

        if ($addMeasureOfUnits) {
           // echo "<script>alert('Unit Added!'); window.location='../../product-unit.php';</script>";
    ?>

            <script>
                swal("Success", "Unit Added!", "success")
                    .then((value) => {
                        window.location = '<?= ADM_URL ?>product-unit.php';
                    });
            </script>
        <?php
        } else {
        ?>
            <script>
                swal("Error", "Unit Addition Failed!", "error")
                    .then((value) => {
                        window.location = '<?= ADM_URL ?>product-unit.php';
                    });
            </script>
    <?php
        }
    }

    ?>

</body>

</html>