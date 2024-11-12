<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'itemUnit.class.php';

$itemUnit       = new ItemUnit;


if (isset($_POST['search'])) {

    $match        = $_POST['search'];

    $showItemUnit = json_decode($itemUnit->itemUnitCardSearch($match, $adminId));
    
    // print_r($showItemUnit);
    
    if ($showItemUnit->status) {
        $showItemUnit = $showItemUnit->data;
        $addedUser = $showItemUnit[0]->admin_id;

    ?>
    <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Unit ID</th>
                <th>Unit Name</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (is_array($showItemUnit)) {
                foreach ($showItemUnit as $eachItemUnit) {
                    $unitId       = $eachItemUnit->id;
                    $unitName    = $eachItemUnit->name;

                    echo '<tr>
                            <td>' . $unitId   . '</td>
                            <td>' . $unitName . '</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-transparent text-primary" data-bs-target="#prodUnitReqModal" data-bs-toggle="modal" data-bs-dismiss="modal"';
                                // $addedUser != $adminId ? echo 'disabled' : echo 'onclick="unitViewAndEdit(' . $unitId . ')"';
                                echo $addedUser != $adminId ? 'disabled' : 'onclick="unitViewAndEdit(' . $unitId . ')"';

                                echo '>
                                <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>';
                }
            }
            ?>
        </tbody>
    </table>
    <?php
    } else {
        echo "
        <div class='p-3 border border-dashed border-dark' >
            <p class='text-center pb-0 mb-1'>Unit/s Not Found!</p>
            <p class='text-center text-danger small pb-0 mb-0 '>However You Can Search Unit.</p>
        </div>";
    }
    ?>


<script>
    //View and Edit Manufacturer function
    unitViewAndEdit = (unitId) => {
        let ViewAndEdit = unitId;
        let url = "ajax/unit.View.ajax.php?Id=" + ViewAndEdit;
        $(".prodUnitReqModal").html(
            '<iframe width="99%" height="250px" frameborder="0" allowtransparency="true" src="' +
            url + '"></iframe>');
    }
</script>

<?php
}else {
    echo 'Http Request Failed!';
}
?>
