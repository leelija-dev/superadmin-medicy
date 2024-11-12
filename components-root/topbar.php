<?php
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


$Request = new Request;


$allRequestResult = [];

$tables = ['query_response','ticket_response'];
    
$responceData = [];
$badgeCounter = 0;
foreach ($tables as $tableName) {
    $checkResponse = $Request->adminResponseCheck($tableName);
    if ($checkResponse['status']) {
        $responce = $checkResponse['data'];
        for ($i = 0; $i < count($responce); $i++) {
            $responceData[] = $responce[$i];
            $badgeCounter++;
        }
    }
}

// print_r($responceData);

?>

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm">

    <!-- Sidebar Toggle (Topbar) -->
    <button class="Ntoggle-btn" id="NsidebarToggle" style="
    border-radius: 50px;
    background: lightblue;">
        <i class="fa fa-bars"></i>
    </button>
    <input class="d-none" id="master-url" value="<?= URL; ?>">
    <div class="d-flex">
        <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" id="search-all-form">
            <div class="input-group">
                <input type="text" class="form-control bg-light border-1 small shadow-none" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" onkeydown="searchFor()" id="search-all">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="p-2 bg-light" id="searchAll-list" style="max-height: 15rem; max-width:100%; position: absolute; z-index: 9999; top: 58px; overflow: scroll; display:none; margin-left: 1rem;background: rgb(255, 255, 255); border-radius: 0 0 3px 3px; margin-top: 0.1rem; transition: 3.3s ease; box-shadow: 0 5px 10px rgb(0 0 0 / 20%);">
    </div>







    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter"><?= $badgeCounter ?></span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>
                <?php
                for ($i = 0; $i < count($responceData); $i++) {
                    $addedOn = $responceData[$i]['added_on'];
                    $messageTitle = $responceData[$i]['title'];
                    $messageData = $responceData[$i]['response'];
                    $masterTicketNo = url_enc($responceData[$i]['ticket_no']);

                    echo '<div class="dropdown-item d-flex align-items-center" id='.$responceData[$i]["ticket_no"].' data-ticket='.$masterTicketNo.' onclick="updateResponseTable(this)" style="cursor: pointer;">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">' . date('F j, Y', strtotime($addedOn)) . '</div>
                                    <span class="font-weight-bold">' . $messageTitle . '</span><br>
                                    <span>' . $messageData . '</span>
                                </div>
                            </div>';
                }
                ?>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
            </div>
        </li>

        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter">7</span>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                    Message Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="<?php echo ASSETS_PATH ?>img/undraw_profile_1.svg" alt="...">
                        <div class="status-indicator bg-success"></div>
                    </div>
                    <div class="font-weight-bold">
                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                            problem I've been having.</div>
                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="<?php echo ASSETS_PATH ?>img/undraw_profile_2.svg" alt="...">
                        <div class="status-indicator"></div>
                    </div>
                    <div>
                        <div class="text-truncate">I have the photos that you ordered last month, how
                            would you like them sent to you?</div>
                        <div class="small text-gray-500">Jae Chun · 1d</div>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="<?php echo ASSETS_PATH ?>img/undraw_profile_3.svg" alt="...">
                        <div class="status-indicator bg-warning"></div>
                    </div>
                    <div>
                        <div class="text-truncate">Last month's report looks great, I am very happy with
                            the progress so far, keep up the good work!</div>
                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60" alt="...">
                        <div class="status-indicator bg-success"></div>
                    </div>
                    <div>
                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                            told me that people say this to all dogs, even if they aren't good...</div>
                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                    </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <p class="mr-2 mb-0 d-lg-inline text-gray-800 small" id="userText">
                    <span><?= $USERFNAME ?></span>
                </p>

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
                <a class="btn btn-primary" href="<?= URL . '_config/logout.php' ?>">Logout</a>
            </div>
        </div>
    </div>
</div>


<!-- script contorl onclick alert notification -->
<script>
function updateResponseTable(element) {

    let ticketNo = element.getAttribute('data-ticket');
    // console.log(ticketNo);
    const xhr = new XMLHttpRequest();
    let alertContorl = `ajax/alertControl.ajax.php?ticket=${ticketNo}`;
    xhr.open("GET", alertContorl, false);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(null);
    let report = xhr.responseText;
    // console.log(report);
    if(report){
        let url = `<?= URL ?>ticket-response-check.php?ticket=` + ticketNo;
        window.location.href = url;
    }else{
        alert('Server Problem!');
    }
}

function toggleSidebar() {
    const toggleTopbtn = document.getElementById("NsidebarToggle");
    const Nsidebar = document.querySelector(".Nsidebar");

    toggleTopbtn.addEventListener("click", () => {
        Nsidebar.classList.toggle("active");
    });
}

toggleSidebar();
</script>
