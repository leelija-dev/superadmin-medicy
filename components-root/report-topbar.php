<?php

?>
<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <div class="p-2 bg-light" id="searchAll-list" style="max-height: 15rem; max-width:100%; position: absolute; z-index: 9999; top: 58px; overflow: scroll; display:none; margin-left: 1rem;background: rgb(255, 255, 255); border-radius: 0 0 3px 3px; margin-top: 0.1rem; transition: 3.3s ease; box-shadow: 0 5px 10px rgb(0 0 0 / 20%);">
    </div>

    <div class="col-12 d-flex">
        <!-- home icon  -->
        <div class="d-flex align-items-center p-2">
            <a class="focus-out btn btn-primary" href="<?= URL ?>" >
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a class="focus-out btn btn-secondary ms-2" href="<?= URL ?>reports.php" >
                <i class="fas fa-file-alt"></i>
                Reports
            </a>
            <!-- health care details holding area -->
            <div class="col-9 d-flex d-none text-center">
                <div class="col-sm-3">
                    <label for="" id="healthcare-name"><?= $healthCareName; ?></label>
                </div>
                <div class="col-sm-1">
                    <label for="" id="healthcare-gstin"><?= $gstinData; ?></label>
                </div>
                <div class="col-sm-6">
                    <label for="" id="healthcare-address"><?= $healthCareAddress1 . ', ' . $healthCareAddress2; ?></label>
                </div>
                <div class="col-sm-2">
                    <label for="" id="report-generation-date-time-holder"></label>
                </div>
            </div>
        </div>

        
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">


            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-lg-inline text-gray-600 small" id="userText"><?= $USERFNAME ?></span>

                    <?php

                    if (empty($userImg)) {
                        $imagePath = DEFAULT_USER_IMG_PATH;
                    } else {
                        if ($_SESSION['ADMIN']) {
                            $imagePath = ADM_IMG_PATH . $userImg;
                        } else {
                            $imagePath = EMPLOYEE_IMG_PATH . $userImg;
                        }
                    }

                    ?>

                    <img class="img-profile rounded-circle" src="<?= ($imagePath) ? $imagePath :  IMG_PATH . 'undraw_profile.svg' ?>">
                    <!-- <img class="img-profile rounded-circle" src="<?= IMG_PATH . 'undraw_profile.svg'; ?>"> -->
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="<?= URL . 'profile.php' ?>">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <a class="dropdown-item" href="<?= URL . 'clinic-setting.php' ?>">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                        Settings
                    </a>
                    <a class="dropdown-item" href="<?= URL . 'reports.php' ?>">
                        <i class="fas fa-file-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Reports
                    </a>
                    <?php if ($_SESSION['ADMIN']) : ?>
                        <a class="dropdown-item" href="<?= URL . 'employees.php' ?>">
                            <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                            Employees
                        </a>
                    <?php endif ?>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>

        </ul>
    </div>

</nav>
<!-- End of Topbar -->

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="<?= LOCAL_DIR . '_config/logout.php' ?>">Logout</a>
            </div>
        </div>
    </div>
</div>