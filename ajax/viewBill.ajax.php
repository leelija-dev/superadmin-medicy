<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR."dbconnect.php";
require_once CLASS_DIR."stockOut.class.php";
require_once CLASS_DIR."patients.class.php";
// require_once "../../php_control/patients.class.php";



// CLASS INTIATING 
$StockOut = new StockOut();
$Patients = new Patients();



if (isset($_GET['invoice'])) {

    $item = $StockOut->stockOutDisplayById($_GET['invoice']);

    
    if ($item[0]['customer_id'] != 'Cash Sales') {
        $patientName = json_decode($Patients->patientsDisplayByPId($item[0]['customer_id']));
        $patientName = $patientName->name;
    }else{
        $patientName = $item[0]['customer_id'];
    }

// print_r($item);

}


?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Blank</title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <div class="row">
                    <div class="col-4">
                        <p> Patient Name: <?php echo $patientName; ?></p>
                        <p> Items No: <?php echo $item[0]['items']; ?></p>
                    </div>
                    <div class="col-4">
                        <p> Invoice Id: #<?php echo $item[0]['invoice_id']; ?></p>
                        <p> 
                            Bill Date: 
                            <?php 
                            $date=date_create($item[0]['bill_date']);
                            echo date_format($date,"d-m-Y");  
                            ?>
                        </p>
                    </div>
                    <div class="col-4">
                        <p>Amount: <?php echo $item[0]['amount']; ?></p>
                        <p>Status: <?php echo$item[0]['payment_mode']; ?></p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm mt-5 pt-5" style="font-size: 0.9rem;">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th scope="col">Item</th>
                                <th scope="col">Unit/Pack</th>
                                <th scope="col">Batch</th>
                                <th scope="col">Expiry</th>
                                <th scope="col">MRP</th>
                                <th scope="col">Qty.</th>
                                <th scope="col">Disc %</th>
                                <th scope="col">Taxable</th>
                                <th scope="col">GST %</th>
                                <th scope="col" class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $details = $StockOut->stockOutDetailsDisplayById($_GET['invoice']);
                            // print_r($details);
                            foreach ($details as $detail) {
                                // print_r($detail);
                                $weatage = $detail['weightage'];
                                $itemUnit = preg_replace('/[0-9]/','',$weatage);
                                echo $itemUnit;

                                if($itemUnit == 'tab' || $itemUnit == 'cap'){
                                    $qty = $detail['loosely_count'];
                                    $suffix = " (L)";
                                }else{
                                    $qty = $detail['qty'];
                                    $suffix = "";
                                }
                            
                                echo'<tr>
                                        <td>'.$detail['item_name'].'</td>
                                        <td>'.$detail['weightage'].$detail['unit'].'</td>
                                        <td>'.$detail['batch_no'].'</td>
                                        <td>'.$detail['exp_date'].'</td>
                                        <td>'.$detail['mrp'].'</td>
                                        <td>'.$qty.$suffix.'</td>
                                        <td>'.$detail['discount'].'</td>
                                        <td>'.$detail['taxable'].'</td>
                                        <td>'.$detail['gst'].'</td>
                                        <td class="text-right">'.$detail['amount'].'</td>
                                    </tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
</body>

</html>