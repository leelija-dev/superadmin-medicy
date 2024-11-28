<?php

// require_once dirname(__DIR__) . '/config/constant.php';
require_once 'config/constant.php';

require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

//Intitilizing Doctor class for fetching doctors
$Products       = new Products();
$Request        = new Request;
$Pagination     = new Pagination();
$ProductImages  = new ProductImages();




$allRequestResult = [];

$requestTypes = [
    'product_request'      => ['tableName' => 'Product Request', 'data'      => []],
    'distributor'          => ['tableName' => 'Distributer Add', 'data'      => []],
    'manufacturer'         => ['tableName' => 'manufacturer Add', 'data'     => []],
    'packaging_type'       => ['tableName' => 'packaging Add', 'data'        => []],
    'quantity_unit'        => ['tableName' => 'quantity add', 'data'         => []],
    'query_request'        => ['tableName' => 'Generate Quarry', 'data'      => []],
    'ticket_request'       => ['tableName' => 'Generate Ticket', 'data'      => []]
];

// print_r($requestTypes);


// string splitter function
function getInitials($string)
{
    $words = explode(' ', $string);
    $initials = '';
    foreach ($words as $word) {
        if (!empty($word)) {
            $initials .= strtoupper($word[0]);
        }
    }
    return $initials;
}


foreach ($requestTypes as $table => &$requestType) {

    $requestData = json_decode($Request->fetchAllRequestDataByTableName($table));
    // print_r($requestData);
    if ($requestData->status) {
        $requestType['data'] = $requestData->data;
    } else {
        $requestType['data'] = [];
    }


    foreach ($requestType['data'] as $requestDataItem) {
        // print_r($requestDataItem);
        if ($requestType['tableName'] == 'Product Request') {
            if ($requestDataItem->new_prod_req_status == 1) {
                $prdReqStatus = 'ACTIVE';
                $reqCheck = 0;
            } elseif ($requestDataItem->updated_prod_flag == 1) {
                $prdReqStatus = 'ACTIVE';
                $reqCheck = 1;
            } else {
                $prdReqStatus = 'INACTIVE';
            }
            $allRequestResult[] = [
                'id'          => $requestDataItem->ticket_no,
                'tableName'   => $requestType['tableName'],
                'name'        => $requestDataItem->name,
                'msgTitle'    => '',
                'description' => $requestDataItem->req_dsc,
                'status'      => $prdReqStatus,
                'request_check' => $reqCheck,
            ];
        } elseif ($requestType['tableName'] == 'packaging Add') {
            $allRequestResult[] = [
                'id'          => getInitials($requestType['tableName']) . $requestDataItem->id,
                'tableName'   => $requestType['tableName'],
                'name'        => $requestDataItem->unit_name,
                'msgTitle'    => '',
                'description' => 'New Packaging Unit Add',
                'status'      => '',
            ];
        } elseif ($requestType['tableName'] == 'quantity add') {
            $allRequestResult[] = [
                'id'          => getInitials($requestType['tableName']) . $requestDataItem->id,
                'tableName'   => $requestType['tableName'],
                'name'        => $requestDataItem->short_name,
                'msgTitle'    => '',
                'description' => 'New Quantity Unit Add',
                'status'      => '',
            ];
        } elseif ($requestType['tableName'] == 'Generate Quarry') {
            $allRequestResult[] = [
                'id'          => $requestDataItem->ticket_no,
                'tableName'   => $requestType['tableName'],
                'name'        => '',
                'msgTitle'    => $requestDataItem->title,
                'description' => $requestDataItem->message,
                'status'      => $requestDataItem->status,
            ];
        } elseif ($requestType['tableName'] == 'Generate Ticket') {
            $allRequestResult[] = [
                'id'          => $requestDataItem->ticket_no,
                'tableName'   => $requestType['tableName'],
                'name'        => '',
                'msgTitle'    => $requestDataItem->title,
                'description' => $requestDataItem->message,
                'status'      => $requestDataItem->status,
            ];
        } else {
            $allRequestResult[] = [
                'id'          => getInitials($requestType['tableName']) . $requestDataItem->id,
                'tableName'   => $requestType['tableName'],
                'name'        => $requestDataItem->name,
                'msgTitle'    => '',
                'description' => 'New' . ' ' . $requestType['tableName'],
                'status'      => '',
            ];
        }
    }
}

$pagination = json_decode($Pagination->arrayPagination($allRequestResult));

if ($pagination->status == 1) {
    $result = $pagination;
    $allProducts = $pagination->items;
    $totalPtoducts = $pagination->totalitem;
} else {
    // Handle the case when status is not 1
    $result = $pagination;
    $allProducts = [];
    $totalPtoducts = 0;
}

// print_r($result);
// echo "<br><br>";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Edit Requests</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/products.css">
    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include SUP_ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include SUP_ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin container-fluid -->
                <div class="container-fluid">

                    <div class="card-body shadow">
                        <div class="table-responsive">
                            <table class="table table-bordered sortable-table" id="appointments-dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="col-1">Ticket Id</th>
                                        <th class="col-2">Category</th>
                                        <th class="col-2">Title</th>
                                        <th class="col-3">Item Name</th>
                                        <th class="col-5">Description</th>
                                        <th class="col-5">Status</th>
                                        <th class="col-1">Check</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($result) && isset($result->items) && is_array($result->items)) {
                                        // print_r($result);
                                        $resultItems = $result->items;
                                        $count = 0;
                                        foreach ($resultItems as $resItems) {
                                            // print_r($resItems);
                                            $count++;
                                            if ($resItems->tableName != null) {
                                                $tableName = $resItems->tableName;
                                            } else {
                                                $tableName = '';
                                            }

                                            if ($resItems->name != null) {
                                                $itemName = $resItems->name;
                                            } else {
                                                $itemName = '';
                                            }

                                            if ($resItems->msgTitle != null) {
                                                $title = $resItems->msgTitle;
                                            } else {
                                                $title = '';
                                            }

                                            if ($resItems->description != null) {
                                                $description = $resItems->description;
                                            } else {
                                                $description = '';
                                            }
                                            // print_r($resItems);  die;
                                            if ($resItems->status != null) {
                                                $status = $resItems->status;
                                            } else {
                                                $status = '';
                                            }


                                            if ($resItems->tableName == 'Product Request') {
                                                if ($resItems->status == 'ACTIVE') {

                                                    $link = "<a href='product-request-lsit.php?check=1&tokenNo=$resItems->id&modalName=$resItems->request_check'>View</a>";
                                                } else {
                                                    $link = '';
                                                }
                                            } else if (
                                                $resItems->tableName == 'Generate Quarry' || $resItems->tableName == 'Generate Ticket'
                                            ) {
                                                if ($resItems->status == 'ACTIVE') {
                                                    $link = "<a href='view-query-ticket.php?tokenNo=$resItems->id&table=$tableName'>View</a>";
                                                } else {
                                                    $link = '';
                                                }
                                            } else {
                                                $link = '';
                                            }


                                            echo '<tr>
                                                        <td>' . $resItems->id . '</td>
                                                        <td>' . $tableName . '</td>
                                                        <td>' . $title . '</td>
                                                        <td>' . $itemName . '</td>
                                                        <td>' . $description . '</td>
                                                        <td>' . $status . '</td>
                                                        <td style="color: red;">' . $link . '</td>
                                                    </tr>';
                                        }
                                    } else {
                                        echo '<tr class="odd">
                                                 <td valign="top" colspan="6" class="dataTables_empty" style="text-align: center;">Ticket Not Found</td>
                                              </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center" id="pagination-control">
                            <?= $result->paginationHTML ?>
                        </div>
                    </div>

                </div>
                <!-- End of container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <!-- <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a> -->
    <!-- <?php include ROOT_COMPONENT . 'generateTicket.php'; ?> -->

    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

</body>

</html>