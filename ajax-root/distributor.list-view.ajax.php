<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'distributor.class.php';

$Distributor        = new Distributor();

if (isset($_GET['match'])) :

    $match = $_GET['match'];
    $showDistributor    = json_decode($Distributor->distributorSearch($match));

    if ($showDistributor->status == 1) {
        $showDistributor = $showDistributor->data;

        foreach ($showDistributor as $eachDistributor) {
            echo '<div class="p-1 border-bottom small text-gray-800 lh-med list" id="' . $eachDistributor->id . '" onclick="setDistributor(this)">
                    <span class="h6 text-primary">' . $eachDistributor->name . '</span>
                    <br>
                    ' . $eachDistributor->gst_id . ' | ' . $eachDistributor->address . '
                </div>';
        }
    } else {
        echo "<p class='text-center font-weight-bold'>Distributor Not Found!</p>";
    }


elseif (isset($_POST['search'])) :

    $match = isset($_POST['search']) ? $_POST['search'] : $adminId;

    $showDistributor    = json_decode($Distributor->distCardSearch($match, $adminId));

    if ($showDistributor->status == 1) {
        $showDistributor = $showDistributor->data;
        $found = true;
    } else {
        $found = false;
        $msgElement = "<div class='p-3 border border-dashed border-dark' >
                            <p class='text-center pb-0 mb-1'>Distributor/s Not Found!</p>
                            <p class='text-center text-danger small pb-0 mb-0 '>However, You Can Search Other Distributors.</p>
                    </div>";
        // echo "<p class='text-center font-weight-bold'>Distributor Not Found!</p>";
    }


    if ($found) {
?>
        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Area PIN</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (is_array($showDistributor)) {
                        foreach ($showDistributor as $rowDistributor) {
                            $distributorId      = $rowDistributor->id;
                            $distributorName    = $rowDistributor->name;
                            $distributorPhno    = $rowDistributor->phno;
                            $distributorPin     = $rowDistributor->area_pin_code;
                            $distributorStatus  = $rowDistributor->status;
                            $addedAdmin         = $rowDistributor->admin_id;

                            $statusLabel = '';
                            $statusColor = '';
                            switch ($distributorStatus) {
                                case 2:
                                    $statusLabel = 'Disabled';
                                    $statusColor = 'danger';
                                    break;
                                case 0:
                                    $statusLabel = 'Pending';
                                    $statusColor = 'warning';
                                    break;
                                case 1:
                                    $statusLabel = 'Active';
                                    $statusColor = 'success';
                                    break;
                                default:
                                    $statusLabel = 'Disabled';
                                    break;
                            }
                            echo '<tr>
                        <td>' . $distributorName . '</td>
                        <td>' . $distributorPhno . '</td>
                        <td>' . $distributorPin . '</td>
                        <td><span class="badge badge-pill badge-' . $statusColor . '">' . $statusLabel . '</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-transparent text-primary" data-bs-target="#distRequestModal" data-bs-toggle="modal" data-bs-dismiss="modal"';

                            echo $addedAdmin != $adminId ? 'disabled' : 'onclick="distViewAndEdit(' . $distributorId . ')"';

                            echo '><i class="fas fa-edit"></i>
                            </button>
                        </td>
                       </tr>';
                        }
                        // echo $rowCount;
                    }
                    ?>
                </tbody>
            </table>
        </div>
<?php
    } else {
        echo $msgElement;
    }

endif;

?>