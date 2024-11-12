<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'manufacturer.class.php';


// $match = $_POST['search'];
$match = isset($_POST['search']) ? $_POST['search'] : $adminId;

$Manufacturer        = new Manufacturer();

if ($match == 'all') {
    $showmanufacturer   = json_decode($Manufacturer->manufCardSearch($match, $adminId));
} else {
    $showmanufacturer   = json_decode($Manufacturer->manufCardSearch($match, $adminId));
}


if ($showmanufacturer->status !== 1) :
    echo "<div class='p-3 border border-dashed border-dark' >
            <p class='text-center pb-0 mb-1'>Manufacturer/s Not Found!</p>
            <p class='text-center text-danger small pb-0 mb-0 '>However, You Can Search Other Distributors.</p>
        </div>";
else :
    $showmanufacturer = $showmanufacturer->data;
?>
    <div class="table-responsive">
        <table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>SL.</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (is_array($showmanufacturer)) {
                    foreach ($showmanufacturer as $rowmanufacturer) {
                        $manufacturerId      = $rowmanufacturer->id;
                        $manufacturerName    = $rowmanufacturer->name;
                        $manufacturerDsc     = $rowmanufacturer->dsc;
                        $manufacturerStatus  = $rowmanufacturer->status;
                        $addedAdmin          = $rowmanufacturer->admin_id;

                        $statusLabel = '';
                        $statusColor = '';
                        switch ($manufacturerStatus) {
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
                                <td>' . $manufacturerId  . '</td>
                                <td>' . $manufacturerName . '</td>
                                <td>' . $manufacturerDsc . '</td>
                                <td><span class="badge badge-pill badge-' . $statusColor . '">' . $statusLabel . '</span></td>

                                <td class="text-center">
                                    <button class="btn btn-sm btn-transparent text-primary p-0" data-bs-target="#manufacturerModal" data-bs-toggle="modal" data-bs-dismiss="modal"';
                        echo $addedAdmin != $adminId ? 'disabled' : 'onclick="manufactViewRequest(' . $manufacturerId . ')"';
                        echo '><i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
endif;
?>