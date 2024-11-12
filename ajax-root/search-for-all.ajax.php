<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; // Check if admin is logged in
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'searchForAll.class.php';

$SearchForAll = new SearchForAll;

if (isset($_GET['searchKey'])) {
    $searchFor = $_GET['searchKey'];
    $results = [
        'appointments' => json_decode($SearchForAll->searchAllFilterForAppointment($searchFor, $adminId)),
        'patients' => json_decode($SearchForAll->searchAllFilterForPatient($searchFor, $adminId)),
        'stock_in' => json_decode($SearchForAll->searchAllFilterForStockIn($searchFor, $adminId)),
        'stock_out' => json_decode($SearchForAll->searchAllFilterForStockOut($searchFor, $adminId)),
        'lab_billing' => json_decode($SearchForAll->searchAllFilterForLabdata($searchFor, $adminId)),
    ];

    $margedResultData = [];
    $status = 1; // Default to "no data found"

    foreach ($results as $key => $result) {
        $margedResultData[$key] = [
            'token' => $key,
            'status' => $result->status,
            'data' => $result->status ? $result->data : $result->message,
        ];
        // Check if any status is true
        if ($result->status) {
            $status = 0;
        }
    }

    if ($status) {
        echo '<div class="row border-bottom border-primary small mx-0 mb-2">
                <div class="col-md-12">No Data Found</div>
              </div>';
    } else {
        // Function to render each section
        function renderSection($title, $columns, $data) {
            echo '<div class="row border-bottom border-primary small mx-0 mb-2">';
            foreach ($columns as $col) {
                echo "<div class='col-md-{$col['size']}'>{$col['label']}</div>";
            }
            echo '</div>';
            foreach ($data as $result) {
                echo '<div class="row mx-0 py-2 border-bottom p-row item-list" tabindex="0" onclick="getDtls(\'' . $title['token'] . '\', \'' . $result->{$title['idField']} . '\');">';
                foreach ($columns as $col) {
                    echo "<div class='col-md-{$col['size']}'><small>{$result->{$col['field']}}</small></div>";
                }
                echo '</div>';
            }
        }

        // Render appointments
        if ($margedResultData['appointments']['status']) {
            renderSection(
                ['token' => $margedResultData['appointments']['token'], 'idField' => 'appointment_id'],
                [
                    ['label' => 'Appointment Id', 'size' => 6, 'field' => 'appointment_id'],
                    ['label' => 'Patient Id', 'size' => 3, 'field' => 'patient_id'],
                    ['label' => 'Contact', 'size' => 3, 'field' => 'patient_phno'],
                ],
                $margedResultData['appointments']['data']
            );
        } else {
            echo '<div class="row border-bottom border-primary small mx-0 mb-2"><div class="col-md-12">No Appointment Data Found</div></div>';
        }

        // Render patients
        if ($margedResultData['patients']['status']) {
            renderSection(
                ['token' => $margedResultData['patients']['token'], 'idField' => 'patient_id'],
                [
                    ['label' => 'Patient Id', 'size' => 5, 'field' => 'patient_id'],
                    ['label' => 'Name', 'size' => 4, 'field' => 'name'],
                    ['label' => 'Contact', 'size' => 3, 'field' => 'phno'],
                ],
                $margedResultData['patients']['data']
            );
        } else {
            echo '<div class="row border-bottom border-primary small mx-0 mb-2"><div class="col-md-12">No Patient Data Found</div></div>';
        }

        // Render stock in
        if ($margedResultData['stock_in']['status']) {
            renderSection(
                ['token' => $margedResultData['stock_in']['token'], 'idField' => 'id'],
                [
                    ['label' => 'Invoice Id', 'size' => 4, 'field' => 'id'],
                    ['label' => 'Distributor Name', 'size' => 5, 'field' => 'distributor_id'],
                    ['label' => 'Bill No', 'size' => 3, 'field' => 'distributor_bill'],
                ],
                $margedResultData['stock_in']['data']
            );
        } else {
            echo '<div class="row border-bottom border-primary small mx-0 mb-2"><div class="col-md-12">No Stock In Data Found</div></div>';
        }

        // Render stock out
        if ($margedResultData['stock_out']['status']) {
            renderSection(
                ['token' => $margedResultData['stock_out']['token'], 'idField' => 'invoice_id'],
                [
                    ['label' => 'Invoice Id', 'size' => 2, 'field' => 'invoice_id'],
                    ['label' => 'Customer Id', 'size' => 4, 'field' => 'customer_id'],
                    ['label' => 'Amount', 'size' => 3, 'field' => 'amount'],
                    ['label' => 'Payment Mode', 'size' => 3, 'field' => 'payment_mode'],
                ],
                $margedResultData['stock_out']['data']
            );
        } else {
            echo '<div class="row border-bottom border-primary small mx-0 mb-2"><div class="col-md-12">No Stock Out Data Found</div></div>';
        }

        // Render lab billing
        if ($margedResultData['lab_billing']['status']) {
            renderSection(
                ['token' => $margedResultData['lab_billing']['token'], 'idField' => 'bill_id'],
                [
                    ['label' => 'Bill Id', 'size' => 3, 'field' => 'bill_id'],
                    ['label' => 'Patient Id', 'size' => 6, 'field' => 'patient_id'],
                    ['label' => 'Bill Date', 'size' => 3, 'field' => 'bill_date'],
                ],
                $margedResultData['lab_billing']['data']
            );
        } else {
            echo '<div class="row border-bottom border-primary small mx-0 mb-2"><div class="col-md-12">No Lab Data Found</div></div>';
        }
    }
}
?>
