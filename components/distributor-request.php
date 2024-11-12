<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'distributor.class.php';


//Class Initilizing
$Distributor = new Distributor();


$showDistRequest  = json_decode($Distributor->showDistRequest());
$showDistRequest  = $showDistRequest->data;
// print_r($showDistRequest);
?>


<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- table div start  -->
    <div class="table-responsive">
        <table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>SL.</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (is_array($showDistRequest)) {
                    foreach ($showDistRequest  as $rowDistributor) {
                        $requestId      = $rowDistributor->id;
                        $distributorId      = $rowDistributor->dist_id;
                        // print_r($distributorId);
                        $distributorName    = $rowDistributor->name;
                        $distributorPhno    = $rowDistributor->phno;
                        $distributorPin     = $rowDistributor->req_dsc;
                        $distributorStatus  = $rowDistributor->status;

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
                                     <td>' . $distributorId . '</td>
                                    <td>' . $distributorName . '</td>
                                    <td>' . $distributorPhno . '</td>
                                    <td>' . $distributorPin . '</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm dropdown-toggle border-0 text-'.$statusColor.'" type="button" id="statusDropdown' . $distributorId . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $statusLabel . '</button>

                                                <div class="dropdown-menu" aria-labelledby="statusDropdown' . $distributorId . '">
                                                    <a class="dropdown-item" href="#" onclick="updateReqStatus(' . $distributorId . ', 2, this)">Disabled</a>
                                                    <a class="dropdown-item" href="#" onclick="updateReqStatus(' . $distributorId . ', 0, this)">Pending</a>
                                                    <a class="dropdown-item" href="#" onclick="updateReqStatus(' . $distributorId . ', 1, this)">Active</a>
                                                </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span role="button" class="badge badge-primary" onclick="getRequestedData('.$requestId.')">View</span>
                                        <span role="button" class="badge badge-danger" onclick="deleteReq('.$distributorId.')">Delete</span>
                                    </td>
                                </tr>';
                    }
                } else {
                    echo '<tr class="odd">
                                <td valign="top" colspan="6" class="dataTables_empty" style="text-align: center;">Distributor Request Not Found</td>
                        </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- table div start  -->
</div>
<!-- /.container-fluid -->