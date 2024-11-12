<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'packagingUnit.class.php';
require_once CLASS_DIR . 'utility.class.php';

//Class initilization
$PackagingUnits = new PackagingUnits();
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
        <script src="<?= JS_PATH?>sweetAlert.min.js"></script>
</head>
<body>
<?php

if( isset($_POST['add-unit'])){
    $unitName   = $_POST['uni-name'];
    $addedby    = $employeeId;
    $packStatus = 0;
    $newData    = 1;
    $addPackagingUnits = $PackagingUnits->addPackagingUnit($ticketNo, $unitName, $addedby, NOW, $packStatus, $newData, $adminId);
    if ($addPackagingUnits){
        ?>
        <script>
            swal("Success", "Unit Added!", "success")
                .then((value) => {
                    window.location = '<?= URL ?>purchase-master.php';
                });
            </script>
        <?php
    }else{
         ?>
            <script>
            swal("Error", "Packaging Unit Addition Failed!", "error")
                .then((value) => {
                    window.location = '<?= URL ?>purchase-master.php';
                });
            </script>
         <?php
    }
 }

?>