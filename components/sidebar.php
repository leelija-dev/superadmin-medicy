    <?php
    $currentURL = $_SERVER['REQUEST_URI'];
    $url = substr($currentURL, strrpos($currentURL, '/') + 1);
    $parts = explode('.', $url);
    $updatedUrl = $parts[0];
    $page = $updatedUrl;
    // print_r($page);
    ?>
    <!-- Sidebar -->

    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= ADM_URL ?>">
            <div class="sidebar-brand-icon">
                <div class="text-center"><img class="img-fluid" src="<?php echo ASSETS_PATH ?>img/logo.png" alt="">
                </div>
            </div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item For Healthcare -->

        <li class="nav-item <?php if ($currentURL  ==  ADM_URL) {
                                echo "active";
                            } ?>">
            <a class="nav-link" href="<?= ADM_URL ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <li class="nav-item <?php if ($page == "allcustomers" || $page == 'employees' || $page == 'customer-report') {
                                echo "active";
                            } ?>">
            <a class="nav-link collapsed" href="<?= ADM_URL ?>allcustomers.php">
                <i class="fas fa-fw fa-users"></i>
                <span>Customer</span>
            </a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Health Care
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <!-- <li class="nav-item < ?php if ($page == "appointments" || $page == 'add-patient' || $page == 'patient-selection') {
                                echo "active";
                            } ?>">
            <a class="nav-link collapsed" href="< ?= ADM_URL ?>appointments.php">
                <i class="fas fa-fw fa-cog"></i>
                <span>Appointments</span>
            </a>
        </li> -->


        <!-- Lab section  -->

        <li class="nav-item <?= $page ==  "lab-tests" || $page ==  "test-appointments" || $page ==  "test-reports" || $page == "test-report-generate" ? "active" : ''; ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTest" aria-expanded="<?= $page ==  "lab-tests" || $page ==  "test-appointments" || $page ==  "test-reports" ? "true" : ''; ?>" aria-controls="collapsePages">
                <i class="fas fa-vial"></i>
                <span>Lab Tests</span>
            </a>

            <div id="collapseTest" class="collapse <?=$page == "lab-test-list" || $page ==  "lab-tests" || $page ==  "test-appointments" || $page ==  "test-reports" || $page == "single-lab-page" || $page == "add-patient" || $page == "lab-patient-selection" || $page == "lab-billing" || $page == "tests-bill-invoice" || $page == "test-report-generate" || $page == 'edit-lab-billing' ? "show" : ''; ?>" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item <?= $page ==  "lab-test-list" || $page == "single-lab-page" ? "active" : ''; ?>" href="lab-test-list.php">Avilable
                        Tests</a>
                    <a class="collapse-item <?= $page ==  "lab-tests" || $page == "single-lab-page" ? "active" : ''; ?>" href="lab-tests.php">Category</a>
                    <a class="collapse-item <?= $page ==  "test-appointments" || $page == "add-patient" || $page == "lab-patient-selection" || $page == "lab-billing" || $page == "tests-bill-invoice" || $page == "test-report-generate" || $page == 'edit-lab-billing' ? "active" : ''; ?>" href="test-appointments.php">Test Bill Details</a>
                    <a class="collapse-item <?= $page ==  "test-reports" ? "active" : ''; ?>" href="test-reports.php">Test Reports</a>
                </div>
            </div>

        </li>

        <!-- Nav Item - Doctors -->
        <li class="nav-item <?= $page ==  "doctors" ||  $page ==  "doc-specialization" ? "active" : ''; ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDoctor" aria-expanded="<?= $page ==  "doctors" ||  $page ==  "doc-specialization" ? "true" : ''; ?>" aria-controls="collapsePages">
                <i class="fas fa fa-users"></i>
                <span>Doctors</span>
            </a>

            <div id="collapseDoctor" class="collapse <?= $page ==  "doctors" ||  $page ==  "doc-specialization" || $page == "doctor-specialization" ? "show" : ''; ?>" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item <?= $page ==  "doctors" ? "active" : ''; ?>" href="doctors.php">Doctors</a>
                    <a class="collapse-item <?= $page ==  "doc-specialization"  || $page == "doctor-specialization" ? "active" : ''; ?>" href="doctor-specialization.php">Specializations</a>
                </div>
            </div>

        </li>

        <!-- Nav Item - Employees -->
        <li class="nav-item <?= $page ==  "patients" || $page == 'patient-details' ? "active" : '' ?>">
            <a class="nav-link" href="patients.php">
                <i class="fas fa-users"></i>
                <span>Patients</span></a>
        </li>



        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">



        <!-- Heading -->
        <div class="sidebar-heading">
            Pharmacy
        </div>


        <!-- Products -->
        <li class="nav-item 
            <?php
            if ($page ==  "products" || $page ==  "add-new-product" || $page == "product-request-lsit") {
                echo "active";
            }
            ?>">
            <a active class="nav-link 
            <?php
            if ($page !=  "sales") {
                echo "collapsed";
            } ?>" href="#" data-toggle="collapse" data-target="#productsManagement" aria-expanded="<?php if ($page ==  "products" || $page ==  "add-new-product" || $page ==  "product-request-lsit") {
                                                                                                        echo "true";
                                                                                                    } ?>" aria-controls="productsManagement">
                <i class="fas fa-pills"></i>
                <span>Products</span>
            </a>
            <div id="productsManagement" class="collapse <?php if ($page ==  "products" ||  $page ==  "add-products" || $page ==  "product-request-lsit") {
                                                                echo "show";
                                                            } ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item <?php if ($page ==  "products") {
                                                echo "active";
                                            } ?>" href="products.php">All
                        Products </a>
                    <a class="collapse-item <?php if ($page ==  "product-request-lsit") {
                                                echo "active";
                                            } ?>" href="product-request-lsit.php">All
                        Product Request </a>
                    <a class="collapse-item <?php if ($page ==  "add-products") {
                                                echo "active";
                                            } ?>" href="add-products.php ">Add Product</a>
                </div>
            </div>
        </li>
        <!--/end Products Menu  -->

        <!-- Purchase Master collapsed Menu  -->
        <li class="nav-item <?php if ($page ==  "distributor" || $page ==  "manufacturer" || $page ==  "pack-unit" || $page ==  "product-unit" || $page ==  "item-unit") {
                                echo "active";
                            } ?>">
            <a class="nav-link <?php if ($page !=  "distributor") {
                                    echo "collapsed";
                                } ?>" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="<?php if ($page ==  "distributor" || $page ==  "manufacturer" || $page ==  "pack-unit" || $page ==  "product-unit" || $page ==  "item-unit") {
                                                                                                                            echo "true";
                                                                                                                        } ?>" aria-controls="collapseUtilities">
                <i class="fas fa-shopping-basket"></i>
                <span>Purchase Master</span>
            </a>
            <div id="collapseUtilities" class="collapse <?php if ($page ==  "distributor" || $page ==  "manufacturers" || $page ==  "packaging-unit" || $page ==  "product-unit" || $page ==  "item-unit") {
                                                            echo "show";
                                                        } ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Purchase Master:</h6>
                    <a class="collapse-item <?php if ($page ==  "distributor") {
                                                echo "active";
                                            } ?>" href="distributor.php">Distributor </a>
                    <a class="collapse-item <?php if ($page ==  "manufacturers") {
                                                echo "active";
                                            } ?>" href="manufacturers.php">Manufacturer </a>
                    <a class="collapse-item <?php if ($page ==  "packaging-unit") {
                                                echo "active";
                                            } ?>" href="packaging-unit.php">Packageing Unit </a>
                    <a class="collapse-item <?php if ($page ==  "product-unit") {
                                                echo "active";
                                            } ?>" href="product-unit.php">Product Unit </a>
                    <a class="collapse-item <?php if ($page ==  "item-unit") {
                                                echo "active";
                                            } ?>" href="item-unit.php">Item Unit </a>
                </div>
            </div>
        </li>
        <!--/end Purchase Master collapsed Menu  -->

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">



        <!-- Heading -->
        <div class="sidebar-heading">
            Setup
        </div>


        <!-- subcriptions & plans -->
        <li class="nav-item <?= $page ==  "subscriptions" || $page ==  "plans" ? "active" : ''; ?>">
            <a active class="nav-link <?= $page !=  "subscriptions" ? "collapsed" : '' ?>" href="#" data-toggle="collapse" data-target="#subscriptionManagement" aria-expanded="<?= ($page ==  "subscriptions" || $page ==  "plans") ? "true" : '' ?>" aria-controls="subscriptionManagement">
                <i class="fas fa-pills"></i>
                <span>Subscriptions</span>
            </a>
            <div id="subscriptionManagement" class="collapse <?= ($page ==  "subscriptions" ||  $page ==  "plans") ? "show" : ''; ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item <?= $page ==  "subscriptions" ? "active" : ''; ?>" href="subscriptions.php">Subscriptions </a>
                    <a class="collapse-item <?= $page ==  "plans" ? "active" : ''; ?>" href="plans.php">Plans</a>
                </div>
            </div>
        </li>
        <!--/end subscription Menu  <i class="fas fa-ticket-alt"></i> -->


        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Heading -->
        <div class="sidebar-heading">
            Service
        </div>

        <li class="nav-item <?= $page ==  "requests" ? "active" : '' ?>">
            <a class="nav-link" href="requests.php">
                <i class="fas fa-ticket-alt"></i>
                <span>All Tickets</span></a>
        </li>



        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>

    <!-- End of Sidebar -->
     