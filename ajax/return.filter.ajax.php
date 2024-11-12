<?php

    require_once dirname(__DIR__).'/config/constant.php';
    
    require_once CLASS_DIR.'dbconnect.php';
    require_once CLASS_DIR."stockReturn.class.php";
    require_once CLASS_DIR."distributor.class.php";


    $StockReturn    = new StockReturn();
    $Distributor = new Distributor();


    $today = date("Y-m-d");
    $value1 = date("Y-m-d");
    $value2 = date("Y-m-d");

    if ($_GET['table'] !== null && $_GET['value'] !== null && $_GET['fromDate'] !== null && $_GET['toDate'] !== null) {

        $table = ($_GET['table']);
        $value = ($_GET['value']);
        $from_date = ($_GET['fromDate']);
        $to_date = ($_GET['toDate']);

        // echo "<br>Table Name : $table";
        // echo "<br>Table Value : $value";
        // echo "<br>From Date : $from_date";
        // echo "<br>To Date : $to_date";

        if ($table == 'added_by' || $table == 'distributor_id' || $table == 'refund_mode') {
            $n = 1;
        } elseif ($table == 'added_on' && $value != 'CR') {
            $n = 2;
        } elseif ($table == 'added_on' && $value == 'CR') {
            $n = 3;
        }

        // echo "<br>check switch : $n";

        switch ($n) {
            case 1:
                // echo "<br>this is case 1";
                $data1 = $StockReturn->stockReturnFilter($table, $value);
                $data = $data1;
                break;
            case 2:
                // echo "<br>this is case 2";
                if ($value == 'T') {
                    $fromDate = date("Y-m-d");
                    $toDate = date("Y-m-d");
                } elseif ($value == 'Y') {
                    $fromDate = date("Y-m-d", strtotime("yesterday"));
                    $toDate = date("Y-m-d", strtotime("yesterday"));
                } elseif ($value == 'LW') {
                    $fromDate = date("Y-m-d", strtotime("-7 days"));
                    $toDate = date("Y-m-d");
                } elseif ($value == 'LM') {
                    $fromDate = date("Y-m-d", strtotime("-30 days"));
                    $toDate = date("Y-m-d");
                } elseif ($value == 'LQ') {
                    $fromDate = date("Y-m-d", strtotime("-90 days"));
                    $toDate = date("Y-m-d");
                } elseif ($value == 'CFY') {
                    $crntYear = date("Y");
                    $crntMnth = date("m");
                    if ($crntMnth < 4) {
                        $yr = $crntYear - 1;
                        $fromDate = date("$yr-04-01");
                        $toDate = date("Y-m-d");
                    } else {
                        $fromDate = date("Y-04-01");
                        $toDate = date("Y-m-d");
                    }
                } elseif ($value == 'PFY') {
                    $crntYear = date("Y");
                    $crntMnth = date("m");
                    $yr = $crntYear - 1;
                    if ($crntMnth < 4) {
                        $frmYr = $crntYear - 2;
                        $toYr = $crntYear - 1;
                        $fromDate = date("$frmYrr-04-01");
                        $toDate = date("$toYr-03-31");
                    } else {
                        $frmYr = $crntYear - 1;
                        $toYr = $crntYear;
                        $fromDate = date("$frmYr-04-01");
                        $toDate = date("$toYr-03-31");
                    }
                }

                // echo "<br>from date : $fromDate";
                // echo "<br>to date : $toDate";

                $data2 = $StockReturn->stockReturnFilterbyDate($table, $fromDate, $toDate);
                $data = $data2;
               
                break;
            case 3:
                // echo "<br>this is case 3";
                $fromDate = $from_date;
                $toDate = $to_date;
                if ($fromDate <= $toDate) {
                    $data3 = $StockReturn->stockReturnFilterbyDate($table, $fromDate, $toDate);
                    $data = $data3;
                } else {
                    echo "DATE RANGE IS NOT ACCURATE"; 
                }
                break;
            default:
                echo "<br>default case";
        }
        // print_r($data);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Filter</title>
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.css">

</head>

<body>

    <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
        <thead class="bg-primary text-light">
            <tr>
                <th>Return Id</th>
                <th>Distributor</th>
                <th>Return Date</th>
                <th>Entry Date</th>
                <th>Entry By</th>
                <th>Payment Mode</th>
                <th>Amount</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $data = json_decode($data);
            if ($data->status) {
                $data = $data->data;
                // print_r($data);
                foreach ($data as $row) { 
                    
                    $check = '';
                    if ($row->status == "cancelled") {
                        $check  = 'style="background-color:#ff0000; color:#fff"';
                    }

                    $distId = $row->distributor_id;
                    $distributorData = json_decode($Distributor->showDistributorById($distId));
                    $distributorData = $distributorData->data;
                    // print_r($distData);
                    foreach($distributorData as $distData){
                        $distName = $distData->name;
                    }

                    ?>
    <tr <?php echo $check ?>>
        <td><?php echo $row->id ?></td>
        <td><?php echo $distName ?></td>
        <td><?php echo $row->return_date ?></td>
        <td><?php echo $row->added_on ?></td>
        <td><?php echo $row->added_by ?></td>
        <td><?php echo $row->refund_mode ?></td>
        <td><?php echo $row->refund_amount ?></td>
        <td >
            <?php echo '
                <a class="text-primary ml-4" id="edit-btn-' . $row->id . '" onclick="editReturnItem(' . $row->id . ', this)"><i class="fas fa-edit" ></i></a>'; 
            ?>
            <a class="text-danger ml-2" onclick="cancelPurchaseReturn('<?php echo $row->id ?>', this)" ><i class="fas fa-window-close"></i></a>
        </td>
    </tr>
    <?php
                }
            } else { ?>
        <tr>
            <td> <?php echo "No Data" ?></td>
         </tr>
         <?php
            }
            ?>
        </tbody>
    </table>
    <?php
    // }


    ?>


    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <script src="<?= PLUGIN_PATH ?>product-table/jquery.dataTables.js"></script>
    <script src="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script>

    <!-- custom script for stock return page -->
    <script src="<?= JS_PATH ?>stock-return-control.js"></script>

</body>

</html>