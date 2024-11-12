<?php
    $currentURL = $_SERVER['REQUEST_URI'];
    $url = substr($currentURL, strrpos($currentURL, '/') + 1);
    $parts = explode('.', $url);
    $updatedUrl = $parts[0];
    $page = $updatedUrl;
    ?>

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= URL ?>">
            <div class="sidebar-brand-icon">
                <div class="text-center"><img class="img-fluid" src="<?= IMAGES_PATH ?>logo.png" alt="">
                </div>
            </div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item For Healthcare -->
        <!-- <li class="nav-item <?php if ($currentURL  ==  LOCAL_DIR) {
                                echo "active";
                            } ?>">
            <a class="nav-link" href="<?= URL ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li> -->

        <!-- Divider -->
        <hr class="sidebar-divider">

        <?php
        if ($userRole == 2 || $userRole == 'ADMIN') : ?>

            <!-- Heading -->
            <div class="sidebar-heading">
                Health Care
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item <?php if ($page ==  "appointments" || $page == "add-patient" || $page == "patient-selection") { echo "active"; } ?>">
                <a class="nav-link collapsed" href="appointments.php">
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Appointments</span>
                </a>
            </li>


            <!-- Lab section  -->

            <li class="nav-item <?= $page == "lab-tests" || $page ==  "test-appointments" || $page ==  "test-reports" || $page == "test-report-generate" ? "active" : ''; ?>">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTest" aria-expanded="<?= $page ==  "lab-tests" || $page ==  "test-appointments" || $page ==  "test-reports" ? "true" : ''; ?>" aria-controls="collapsePages">
                    <i class="fas fa-vial"></i>
                    <span>Lab Tests</span>
                </a>

                <div id="collapseTest" class="collapse <?= $page ==  "lab-tests" || $page ==  "test-appointments" || $page ==  "test-reports" || $page == "single-lab-page" || $page == "add-patient" || $page == "lab-patient-selection" || $page == "lab-billing" || $page == "tests-bill-invoice" || $page == "test-report-generate" || $page == 'edit-lab-billing' ? "show" : ''; ?>" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $page ==  "lab-tests" || $page == "single-lab-page" ? "active" : ''; ?>" href="lab-tests.php">Avilable
                            Tests</a>
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
        <?php endif; ?>


        <?php if ($userRole == 1 || $userRole == 'ADMIN') : ?>


            <!-- Heading -->
            <div class="sidebar-heading">
                Pharmacy
            </div>


            <!-- Products -->
            <li class="nav-item <?php if ($page ==  "products" || $page ==  "add-new-product") {
                                    echo "active";
                                } ?>">
                <a active class="nav-link <?php if ($page !=  "sales") {
                                                echo "collapsed";
                                            } ?>" href="#" data-toggle="collapse" data-target="#productsManagement" aria-expanded="<?php if ($page ==  "products" || $page ==  "add-new-product") {
                                                                                                                                                                                    echo "true";
                                                                                                                                                                                } ?>" aria-controls="productsManagement">
                    <i class="fas fa-pills"></i>
                    <span>Products</span>
                </a>
                <div id="productsManagement" class="collapse <?php if ($page ==  "products" ||  $page ==  "add-new-product") {
                                                                    echo "show";
                                                                } ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?php if ($page ==  "products") {
                                                    echo "active";
                                                } ?>" href="products.php">All
                            Products </a>
                        <a class="collapse-item <?php if ($page ==  "add-new-product") {
                                                    echo "active";
                                                } ?>" href="add-new-product.php ">Add Product</a>
                    </div>
                </div>
            </li>
            <!--/end Products Menu  -->


            <!-- Product Management collapsed Menu  -->
            <li class="nav-item <?php if ($page ==  "sales" || $page ==  "sales-returns" || $page == "new-sales" || $page == "sales-returns-items" || $page == 'update-sales' || $page == 'sales-return-edit') {
                                    echo "active";
                                } ?>">
                <a active class="nav-link <?php if ($page !=  "sales") {
                                                echo "collapsed";
                                            } ?>" href="#" data-toggle="collapse" data-target="#collapseSalesManagement" aria-expanded="<?php if ($page ==  "sales" || $page ==  "sales-returns" || $page == "new-sales" || $page == "sales-returns-items" || $page == 'update-sales' || $page == 'sales-return-edit') {
                                                                                                                                                                                        echo "true";
                                                                                                                                                                                    } ?>" aria-controls="collapseSalesManagement">
                    <i class="fas fa-clinic-medical"></i>
                    <span>Sales Management</span>
                </a>
                <div id="collapseSalesManagement" class="collapse <?php if ($page ==  "sales" ||  $page ==  "sales-returns" || $page == "new-sales" || $page == "sales-returns-items" || $page == 'update-sales' || $page == 'sales-return-edit') {
                                                                        echo "show";
                                                                    } ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?php if ($page ==  "sales" || $page == "new-sales" || $page == 'update-sales') {
                                                    echo "active";
                                                } ?>" href="sales.php">Sales
                        </a>
                        <a class="collapse-item <?php if ($page ==  "sales-returns" || $page == "sales-returns-items" || $page == 'sales-return-edit') {
                                                    echo "active";
                                                } ?>" href="sales-returns.php ">Returns </a>
                    </div>
                </div>
            </li>
            <!--/end Purchase Master collapsed Menu  -->


            <!-- Purchase Management  -->
            <li class="nav-item <?php if ($page ==  "stock-in" || $page ==  "purchase-details" || $page == "stock-in-edit" || $page == "stock-return" || $page == "stock-return-item") { echo "active"; } ?>">
                <a active class="nav-link <?php if ($page !=  "stock-in" || $page !=  "purchase-details") { echo "collapsed";} ?>" 
                href="#" data-toggle="collapse" data-target="#collapsePurchaseManagement" aria-expanded="<?php if ($page ==  "stock-in" || $page ==  "purchase-details" || $page == "stock-in-edit" || $page == "stock-return" || $page == "stock-return-item") {
                echo "true";} ?>" aria-controls="collapsePurchaseManagement">
                    <i class="fas fa-store-alt"></i>
                    <span>Purchase Management</span>
                </a>
                <div id="collapsePurchaseManagement" class="collapse <?php if ($page ==  "stock-in" || $page ==  "purchase-details" || $page == "stock-in-edit" || $page == "stock-return" || $page == "stock-return-item") { echo "show"; } ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Purchase Management:</h6>
                        <a class="collapse-item <?php if ($page ==  "stock-in") { echo "active"; } ?>" href="stock-in.php ">New </a>
                        <a class="collapse-item <?php if ($page ==  "purchase-details" || $page == "stock-in-edit") { echo "active"; } ?>" href="purchase-details.php ">Purchase </a>
                        <a class="collapse-item <?php if ($page ==  "stock-return" || $page == "stock-return-item") { echo "active"; } ?>" href="
                        stock-return.php">Purchase Return</a>
                    </div>
                </div>
            </li>
            <!--/end Purchase Master collapsed Menu  -->


            <!-- Product Management collapsed Menu  -->
            <li class="nav-item <?php if ($page ==  "current-stock" || $page ==  "stock-expiring" || $page ==  "stock-in-details") { echo "active"; } ?>">
                <a class="nav-link <?php if ($page !=  "current-stock") { echo "collapsed"; } ?>" href="#" data-toggle="collapse" data-target="#collapseStock" aria-expanded="<?php if ($page ==  "current-stock" || $page ==  "stock-expiring" || $page ==  "stock-in-details") { echo "true"; } ?>" aria-controls="collapseStock">
                    <i class="fas fa-store-alt"></i>
                    <span>Stock Details</span>
                </a>
                <div id="collapseStock" class="collapse <?php if ($page ==  "current-stock" || $page ==  "stock-expiring" || $page ==  "stock-in-details") { echo "show"; } ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Purchase Master:</h6>
                        <a class="collapse-item <?php if ($page ==  "current-stock") { echo "active"; } ?>" href="current-stock.php">Current Stock </a>
                        <a class="collapse-item <?php if ($page ==  "stock-expiring") { echo "active"; } ?>" href="stock-expiring.php">Stock Expiring </a>
                    </div>
                </div>
            </li>
            <!--/end product management collapsed Menu  -->



            <!-- Nav Item - Distributo -->
            <li class="nav-item <?php if ($page ==  "distributor") {
                                    echo "active";
                                }  ?>">
                <a class="nav-link" href="distributor.php">
                    <i class="fas fa-clinic-medical"></i>
                    <span>Distributor</span></a>
            </li>


            <!-- Purchase Master collapsed Menu  -->
            <li class="nav-item <?php if ($page ==  "purchesmaster") {
                                    echo "active";
                                } ?>">
                <a class="nav-link collapsed" href="purchesmaster.php">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Purchase Master</span>
                </a>
            </li>
            
        <?php endif; ?>


        <?php if ($userRole == 'ADMIN') : ?>
            <!-- TICKET MENUE -->
            <li class="nav-item <?php if ($page ==  "ticket-details" ) { echo "active"; } ?>">
                <a class="nav-link collapsed" href="ticket-details.php">
                    <i class="fas fa-hand-paper"></i>
                    <span>All Ticket</span>
                </a>
            </li>
        <?php endif; ?>


        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>

    <!-- End of Sidebar -->