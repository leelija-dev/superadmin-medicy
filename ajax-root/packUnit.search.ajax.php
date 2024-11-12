<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
// require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';

$packUnit       = new PackagingUnits();

// $match = $_POST['search'];
if (isset($_POST['search'])) :

    $match =    $_POST['search'];
    $showPackUnit = json_decode($packUnit->packUnitCardSearch($match, $adminId));

    if ($showPackUnit->status !== 1) {

        echo "
        <div class='p-3 border border-dashed border-dark ' >
            <p class='text-center pb-0 mb-1'>Packaging Unit/s Not Found!</p>
            <p class='text-center text-danger small pb-0 mb-0 '>However You Can Search Unit.</p>
        </div>";
    } else {

        $showPackUnit = $showPackUnit->data;
        if (is_array($showPackUnit)) {

?>

            <table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>SL.</th>
                        <th>Unit Name</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($showPackUnit as $rowPackUnit) {
                        $packUnitId      = $rowPackUnit->id;
                        $packUnitName    = $rowPackUnit->unit_name;
                        $packUnitStatus  = $rowPackUnit->status;
                        $addedUser       = $rowPackUnit->admin_id;

                        $statusLabel = '';
                        $statusColor = '';
                        switch ($packUnitStatus) {
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
                        <td>' . $packUnitId  . '</td>
                        <td>' . $packUnitName . '</td>
                        <td><span class="badge badge-pill badge-' . $statusColor . '">' . $statusLabel . '</span></td>

                        <td class="text-center">
                            <button class="btn btn-sm btn-transparent text-primary p-0" data-bs-target="#unitModal" data-bs-toggle="modal" data-bs-dismiss="modal"';
                        echo $addedUser != $adminId ? 'disabled' : 'onclick="packUnitRequest(' . $packUnitId . ')"';
                        echo '><i class="fas fa-edit"></i></button>
                        </td>
                       </tr>';
                    }
                    ?>
                </tbody>
            </table>
<?php
        }
    }
else :
    echo "
    <div class='p-3 border border-dashed border-dark bg-danger' >
        <p class='text-center pb-0 mb-1'>Request Failed!</p>
    </div>";
endif;
?>



<script>
    // packaging unit rerquest function //
    packUnitRequest = (unitId) => {
        let ViewAndEdit = unitId;
        let url = "ajax/packagingUnit.request.ajax.php?Id=" + ViewAndEdit;
        $(".unitModal").html(
            '<iframe width="99%" height="120rem" frameborder="0" allowtransparency="true" src="' +
            url + '"></iframe>');
    }

    function closeModal() {
        $('#unitModal').modal('hide');
    }
</script>