<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';
require_once CLASS_DIR.'dbconnect.php';

require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

$getPatientId = $_GET['credithistory'];
// print_r($getPatientId);

$StockOut       = new StockOut;
$LabBilling     = new LabBilling;

// for sales credit 
$salesDetails = ($StockOut->stockOutByPatientId($getPatientId));
if ($salesDetails !== null) {
    $data = json_decode($salesDetails, true);
} else {
    // Handle the error or set $data to an empty array
    $data = [];
}

// for Text Bill 
$testBillCredit =($LabBilling->labBiilingDetailsByPatientId($getPatientId));

?>




<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
        <title><?= $Name . ' - ' . $HEALTHCARENAME ?></title>

        <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css">
        <link rel="stylesheet" href="<?= CSS_PATH ?>patient-details.css" type="text/css">
        <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css">
        <script src="<?php echo PLUGIN_PATH; ?>chartjs-4.4.0/updatedChart.js"></script>

    </head>
</head>

<body>
    <ul class="nav nav-tabs customNavTab-CerditHistory" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="salesCredit-tab" data-toggle="tab" href="#salesCredit" role="tab"
                aria-controls="salesCredit" aria-selected="true">Sales Credit</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="testBillCredit-tab" data-toggle="tab" href="#testBillCredit" role="tab"
                aria-controls="testBillCredit" aria-selected="false">Pathalogy/ Test bill credit</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="salesCredit" role="tabpanel" aria-labelledby="salesCredit-tab">
            <table class="table table-hover">
                <thead class="creditHistoryThead">
                    <tr>
                        <th scope="col">Invoice Id</th>
                        <th scope="col">Due Amount</th>
                        <th scope="col">Purchase Date</th>
                        <th scope="col">Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                     $saleCreditCount = 0;
                     $testBillCreditCount = 0 ;
                     $saleCreditFound = false;
                     $testBillCreditFound = false;
                        if(!empty($data)){
                        foreach ($data as $row) {
                            // print_r($row);
                            $paymentMode = $row['payment_mode'];
                            $invoiceId = $row['invoice_id'];
                            $dueAmount = $row ['amount'];
                            $purchaseDate = $row ['bill_date'];
                            if($paymentMode === 'Credit'){
                              $saleCreditFound = true;
                              echo '<tr class="cursor-pointer"
                              onclick="openPrint(\'../invoices/print.php?name=sales&id=' . url_enc($invoiceId) . '\'); return false;">
                                <th scope="row">' . $invoiceId . '</th>
                                <td>'.$dueAmount .'</td>
                                <td>'.$purchaseDate.'</td>
                                <td>'.$purchaseDate.'</td>
                              </tr>';
                            }
                         $saleCreditCount +=$dueAmount;
                        }
                        if(!$saleCreditFound){
                          echo '<tr class="text-center text-danger font-weight-bold pb-4" >
                          <td colspan="7">Sales Credit Not Found !</td>
                         </tr>';
                        }
                      }else{
                            echo '<tr class="text-center text-danger font-weight-bold pb-4" >
                             <td colspan="7">Sales Credit Not Found !</td>
                            </tr>';    }
                      ?>
                </tbody>
            </table>
            <div class="border-top position-fixed fixed-bottom">
                <?php 
                 if($saleCreditCount && $paymentMode === 'Credit')
                 echo '<p>Total Due Amount: '. $saleCreditCount .'</p>'
                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="testBillCredit" role="tabpanel" aria-labelledby="testBillCredit-tab">
            <table class="table table-hover">
                <thead class="creditHistoryThead">
                    <tr>
                        <th scope="col">Bill Id</th>
                        <th scope="col">Due Amount</th>
                        <th scope="col"> Bill Date</th>
                        <th scope="col">Due Date/Report Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if (!empty($testBillCredit)){
                            foreach ($testBillCredit as $row) {
                              $billId = $row->bill_id;
                              $dueAmount = $row->due_amount;
                              if($dueAmount !=0 && $dueAmount !=00){
                                $testBillCreditFound = true;
                                echo '<tr class="cursor-pointer"
                                  onclick="openPrint(\'../invoices/print.php?name=lab_invoice&id=' . url_enc($billId) . '\'); return false;">
                                  <th scope="row">' . $row->bill_id . '</th>
                                  <td>' . $row->due_amount . '</td>
                                  <td>' . $row->bill_date . '</td>
                                  <td>' . $row->test_date . '</td>
                                  </tr>';
                              }
                              $testBillCreditCount +=$dueAmount;
                           }
                           if(!$testBillCreditFound){
                            echo '<tr class="text-center text-danger font-weight-bold pb-4" >
                            <td colspan="7">Test Credit Not Found !</td>
                           </tr>';
                          }
                        }else{
                            echo '<tr class="text-center text-danger font-weight-bold pb-4" >
                             <td colspan="7">Test Credit Not Found !</td>
                            </tr>'; 
                        }
                    ?>
                </tbody>
            </table>
            <div class="border-top position-fixed fixed-bottom">
                <?php 
                 if($testBillCreditCount)
                 echo '<p>Total Due Amount:   '. $testBillCreditCount .'</p>'
                ?>
            </div>
        </div>
    </div>
</body>

<!-- Bootstrap core JavaScript-->
<script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
<script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?php echo JS_PATH ?>sb-admin-2.js"></script>
<script src="<?php echo JS_PATH ?>main.js"></script>

</html>