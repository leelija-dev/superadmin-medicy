<?php if (in_array(5, $permissionArray)) : ?>
    <li class="nav-item <?= $page ==  "lab-tests" || $page == "single-lab-page" ? "active" : ''; ?>">
        <a class="nav-link collapsed" href="lab-tests.php">
            <i class="fas fa-vial"></i>
            <span>Avilable Tests</span>
        </a>
    </li>
<?php endif; ?>

<?php if (in_array(6, $permissionArray)) :
    $labBillingPages = [
        "test-appointments",
        "add-patient",
        "lab-patient-selection",
        "lab-billing",
        "tests-bill-invoice",
        "test-report-generate",
        "edit-lab-billing",
        "altered-tests-bill-invoice"
    ];
?>

    <li class="nav-item <?= in_array($page, $labBillingPages) ? "active" : '' ; ?>">
        <a class="nav-link collapsed" href="test-appointments.php">
        <i class="fas fa-file-invoice"></i>
            <span>Test Invoices</span>
        </a>
    </li>
<?php endif; ?>

<?php if (in_array(7, $permissionArray)) : ?>
    <li class="nav-item <?= $page ==  "test-reports" ? "active" : ''; ?>">
        <a class="nav-link collapsed" href="test-reports.php">
        <i class="fas fa-vials"></i>
            <span>Pathalogy Report</span>
        </a>
    </li>
<?php endif; ?>