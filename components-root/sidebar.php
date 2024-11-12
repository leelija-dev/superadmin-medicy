<?php
$currentURL = $_SERVER['REQUEST_URI'];
$url        = substr($currentURL, strrpos($currentURL, '/') + 1);
$parts      = explode('.', $url);
$updatedUrl = $parts[0];
$page       = $updatedUrl;

?>

<div class="Nsidebar " id="Nsidebar">
    <div class="logo">
        <a href="<?= URL ?>">
            <img class="img-fluid logo-full" src="<?= IMAGES_PATH ?>logo.png" alt="">
            <img class="img-fluid logo-favicon" src="<?= IMG_PATH ?>/site-imgs/favicon.ico" alt="">
        </a>
    </div>

    <hr class="Nsidebar-divider sidebarExpand-devider my-0">
    <ul class="Nmenu">
        <?php if (in_array(1, $permissionArray)) : ?>
            <!-- ($permissionArray) from _config/user-details.inc.php -->
            <!-- Nav Item For Healthcare -->
            <li class="nav-item <?= $currentURL  ==  LOCAL_DIR ? "active" : ''; ?>">
                <a class="nav-link mb-n3 p-3" href="<?= URL ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider sidebarExpand-devider">
        <?php endif; ?>

        <!-- ==================================== OPD AREA START ==================================== -->
        <!-- Heading -->
        <?php if (in_array(2, $permissionArray) || in_array(3, $permissionArray) || in_array(4, $permissionArray)) : ?>
            <div class="head-titles">HEALTH CARE</div>
            <?php if (in_array(2, $permissionArray)) : ?>

                <!-- Nav Item - Pages Collapse Menu -->
                <li class="nav-item <?= $page ==  "appointments" || $page == "add-patient" || $page == "patient-selection" ? "active" : ''; ?>">
                    <a class="nav-link " href="appointments.php">
                        <i class="fas fa-fw fa-calendar"></i>
                        <span>Appointments</span>
                    </a>
                </li>
            <?php endif; ?>
            <!-- ==================================== OPD AREA START ==================================== -->
            <?php //if ($userRole == 2 || $userRole == 3 || $userRole == 'ADMIN') : 
            ?>
            <?php if (in_array(3, $permissionArray)) : ?>
                <!-- Nav Item - Employees -->
                <li class="nav-item <?= $page ==  "patients" || $page == 'patient-details' ? "active" : '' ?>">
                    <a class="nav-link" href="patients.php">
                        <i class="fas fa-users"></i>
                        <span>Patients</span></a>
                </li>
            <?php endif; ?>


            <?php if (in_array(4, $permissionArray)) : ?>
                <li class="nav-item  <?= $page == "doctors" || $page == "doctor-specialization" ? "active" : ''; ?>">
                    <a class="nav-link Nmenu-item " href="#" data-toggle="collapse" data-target="#collapseDoctor" aria-expanded="<?= $page == "doctors" || $page == "doc-specialization" ? "true" : ''; ?>" aria-controls="collapseDoctor">
                    <i class="fas fa-user-md"></i>

                        <span>Doctors</span><i class="fas fa-chevron-right NSarrow"></i>

                    </a>

                    <div id="colDoctor" class="Nsubmenu hidesubmenu <?= $page == "doctors" || $page == "doctor-specialization" ? "show" : ''; ?>" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                        <a class="<?= $page == "doctors" ? "active" : ''; ?>" href="doctors.php">Doctors</a>
                        <a class=" mt-n3<?= $page == "doc-specialization" ? "active" : ''; ?>" href="doctor-specialization.php">Specializations</a>
                    </div>
                </li>
            <?php endif; ?>
        <?php endif; ?>
        <!-- ==================================== PATHALOGY AREA START ==================================== -->
        <?php if (in_array(5, $permissionArray) || in_array(6, $permissionArray) || in_array(7, $permissionArray)) : ?>
            <hr>
            <div class="head-titles">Pathalogy Lab</div>
            <?php include ROOT_COMPONENT . '/sidebar/PathalogySidebar.php';   ?>
        <?php endif; ?>
        <!-- ==================================== PATHALOGY AREA END ==================================== -->

        <!-- ==================================== PHARMACY AREA START ==================================== -->
        <?php if (in_array(8, $permissionArray) || in_array(9, $permissionArray) || in_array(10, $permissionArray) || in_array(11, $permissionArray) || in_array(12, $permissionArray) || in_array(13, $permissionArray)) : ?>
            <!-- Divider -->
            <hr>
            <div class="head-titles">PHARMACY</div>
            <?php include ROOT_COMPONENT . '/sidebar/PharmacySidebar.php'; ?>
        <?php endif; ?>

        <!-- TICKET MENUE -->
        <?php if (in_array(14, $permissionArray)) : ?>
            <li class="nav-item mb-4 <?= $page ==  "ticket-details" ? "active" : ''; ?>">
                <a class="nav-link " href="ticket-details.php">
                    <i class="fas fa-hand-paper"></i>
                    <span>All Ticket</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="text-center d-md-inline">
        <button class=" border-0  Nexpand-btn" id="NexpandSidebarBtn"> <i class="fas fa-chevron-left"></i> <i class="fas fa-chevron-right"></i> </button>
    </div>
</div>



<script src="<?php echo JS_PATH; ?>custom/sidebar.js"></script>