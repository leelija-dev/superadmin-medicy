<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once dirname(__DIR__) . '/config/service.const.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . "dbconnect.php";
require_once CLASS_DIR . "stockOut.class.php";
require_once CLASS_DIR . "patients.class.php";
require_once CLASS_DIR . "utility.class.php";

// CLASS INTIATING 
$StockOut = new StockOut();
$Patients = new Patients();
$Utility  = new Utility();



if (isset($_GET['invoice'])) {

    $item = $StockOut->stockOutDisplayById($_GET['invoice']);


    if ($item[0]['customer_id'] != 'Cash Sales') {
        $patientName = json_decode($Patients->patientsDisplayByPId($item[0]['customer_id']));
        $patientName = $patientName->name;
    } else {
        $patientName = $item[0]['customer_id'];
    }
}


?>



<!-- Main Content -->
<div id="content">
    <div class="row">
        <div class="col-4">
            <p> Invoice: <b>#<?= $item[0]['invoice_id']; ?></b></p>
            <p> Patient Name: <b><?= $patientName; ?></b></p>
        </div>
        <div class="col-4">
            <p> Bill Date: <b><?= formatDateTime($item[0]['bill_date']);?></b></p>
            <p> Items No: <b><?= $item[0]['items']; ?></b></p>
        </div>
        <div class="col-4">
            <p>Amount: <b><?= $item[0]['amount']; ?></b></p>
            <p>Status: <b><?= $item[0]['payment_mode']; ?></b></p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-sm mt-1" style="font-size: 0.9rem;">
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
                    $weightage = $detail['weightage'];

                    if (in_array(strtolower(trim($detail['unit'])), LOOSEUNITS)) {

                        $LQty   = $detail['loosely_count'];
                        $result = $LQty / $weightage;

                        if (is_int($result)) {
                            $qty = $detail['qty'];
                            $suffix = "";
                        } else {
                            $qty = $detail['loosely_count'];
                            $suffix = " (L)";
                        }
                    } else {
                        $qty = $detail['qty'];
                        $suffix = "";
                    }

                    echo '<tr>
                                        <td>' . $detail['item_name'] . '</td>
                                        <td>' . $detail['weightage'] . $detail['unit'] . '</td>
                                        <td>' . $detail['batch_no'] . '</td>
                                        <td>' . $detail['exp_date'] . '</td>
                                        <td>' . $detail['mrp'] . '</td>
                                        <td>' . $qty . $suffix . '</td>
                                        <td>' . $detail['discount'] . '</td>
                                        <td>' . $detail['taxable'] . '</td>
                                        <td>' . $detail['gst'] . '</td>
                                        <td class="text-right">' . $detail['amount'] . '</td>
                                    </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<!-- End of Main Content -->