<?php

$page = "lab-tests";

// require_once dirname(__DIR__) . '/config/constant.php';
require_once 'config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR  . '_config/healthcare.inc.php';

require_once CLASS_DIR . 'UtilityFiles.class.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'pagination.class.php';

$labTypes       = new Pathology;
$subTests       = new SubTests;
$Pagination     = new Pagination;


//================================================
// ============= APPOINTMENT DATA ================
// $searchVal = '';
// $match = '';

// if (isset($_GET['search'])) {
//     $searchVal = $match = $_GET['search'];
//     $testCategories = json_decode($labTypes->searchLabTest($searchVal));
// } else {
//     $testCategories = json_decode($labTypes->showTestCategories());
// }

// if ($testCategories->status) {
//     $labData = $testCategories->data;

//     if (!empty($labData)) {

//         $allLabTestData = $labData;

//         if (is_array($allLabTestData)) {

//             $response = json_decode($Pagination->arrayPagination($allLabTestData));

//             $slicedLabTestData = '';
//             $paginationHTML = '';
//             $labTestTotalItem = $slicedLabTestData = $response->totalitem;

//             if ($response->status == 1) {
//                 $slicedLabTestData = $response->items;
//                 $paginationHTML = $response->paginationHTML;
//             }
//         } else {
//             $labTestTotalItem = 0;
//         }
//     } else {
//         $labTestTotalItem = 0;
//         $paginationHTML = '';
//     }
// } else {
//     $labTestTotalItem = 0;
//     $paginationHTML = '';
// }

$searchVal = '';
$match = '';
$resetFlagVal = 0;

if (isset($_GET['search']) && isset($_GET['flag'])) {
    $resetFlagVal = $_GET['flag'];
    $searchVal = $match = $_GET['search'];
    $testCategories = json_decode($labTypes->selectLikeCategoryName($searchVal)); // Get search results
    if($testCategories->status){
        $testList = $testCategories->data;
    }else{
        $testList = [];
    }
} else {
    $testList = $labTypes->showTestList(); // Get all test categories when no search
}

if (!empty($testList)) {
    if (is_array($testList)) {
        $response = json_decode($Pagination->arrayPagination($testList));

        $slicedLabTestData = '';
        $paginationHTML = '';
        $labTestTotalItem = $response->totalitem;

        if ($response->status == 1) {
            $slicedLabTestData = $response->items;
            $paginationHTML = $response->paginationHTML;
        }
    } else {
        $labTestTotalItem = 0;
        $paginationHTML = '';
    }
} else {
    $labTestTotalItem = 0;
    $paginationHTML = '';
}

// echo $labTestTotalItem;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Lab Test Category</title>

    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>lab-test.css" type="text/css" />
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

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="row flex-wrap-reverse" style="z-index: 999;">

                        <div class="col-12 col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 booked_btn">
                                    <div class="row">
                                        <label class="d-none" for="" id="base-url-holder"></label>
                                        <label class="d-none" for="" id="srch-btn-controler">0</label>
                                        <div class="col-md-4 col-lg-6 col-sm-12">
                                            <h6 class="m-0 font-weight-bold text-primary">Lab Test List</h6>
                                        </div>
                                        <div class="col-md-5 col-lg-6 col-sm-12 input-group ">
                                            <input class="cvx-inp w-50 px-3 rounded-left w-75" type="text" placeholder="Search test..." name="search-test" id="search-test" style="outline: none;border: 1px solid #858796;" value="<?= isset($match) ? $match : ''; ?>" autocomplete="off">

                                            <div class="input-group-append" id="dataSearch-btnDiv">
                                                <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="dataSearch-btn" onclick="testDataSearch(this)"><i class="fas fa-search"></i></button>
                                            </div>

                                            <div class="input-group-append" id="reset-searchBtn-div">
                                                <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="reset-search" onclick="testDataSearch(this)"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                        
                                        <!-- <div class="col-md-3 d-flex justify-content-end">
                                            <button class="btn btn-sm btn-primary" id="add-category-btn" onclick="openAddNewCategoryModal(this)">
                                                <i class="fas fa-plus"></i> Add Category
                                            </button>
                                        </div> -->
                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive" id="filter-table">

                                        <?php

                                        if ($labTestTotalItem == 0) {
                                            echo "<div class='text-center font-weight-bold text-danger'>No Test Type Avilable.</div>";
                                        } else {
                                        ?>

                                            <!-- table start -->
                                            <table class="table table-sm table-hover w-100">
                                                <thead class="bg-primary text-light">
                                                    <tr>
                                                    <th>Test Name</th>
                                                    <th>Department</th>
                                                    <th>Price</th>
                                                    <th>Report Type</th>
                                                    <th>Status</th>
                                                    <th class="text-center pr-3">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="table-data">
                                                <?php
                                                $testCategories = json_decode($labTypes->showTestCategories());
                                                    foreach ($slicedLabTestData as $eachCategory) {
                                                        $testID    = $eachCategory->id;
                                                        $testCatId = $eachCategory->cat_id;
                                                        $testName   = $eachCategory->name;
                                                        // $testDsc    = $eachCategory->dsc;
                                                        $testPrice  = $eachCategory->price;
                                                        $testRepTyp = $eachCategory->report_type;
                                                        $testStatus = $eachCategory->status;

                                                        // $subTestData = json_decode($labTypes->showTestByCat($testCatId));
                                                        // $subTestCount = $subTestData->status ? count($subTestData->data) : 0;
                                                        //...find department name based on $testCatId...//
                                                        $categoryName = "Unknown";
                                                        if ($testCategories->status) {
                                                            foreach ($testCategories->data as $category) {
                                                                if ($category->id == $testCatId) {
                                                                    $categoryId   = $category->id;
                                                                    $categoryName = $category->name;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    ?>

                                                <tr>
                                                    <td><?= $testName ?></td>
                                                    <td>
                                                        <a href="#" class="text-decoration-none"
                                                            onclick="openCategoryModal('<?= $categoryId ?>')"
                                                            data-toggle="modal" data-target="#catagoryViewModal">
                                                            <?= $categoryName ?>
                                                        </a>
                                                    </td>
                                                    <td><?= $testPrice ?></td>
                                                    <td>
                                                    <?= $testRepTyp == 1 ? 'Single Field' : ($testRepTyp == 2 ? 'Text Editor' : '') ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?= $testStatus == 1 ? 'status-active' : 'status-inactive' ?>">
                                                            <?= $testStatus == 1 ? 'Active' : 'Inactive' ?>
                                                        </span>
                                                    </td>
                                                    <td class='text-center'>

                                                        <a href="#" class="text-decoration-none"
                                                            onclick="openSingleTestModal('<?= $testID ?>')"
                                                            data-toggle="modal" data-target="#catagoryViewModal">
                                                            <i class="fas fa-eye" style="color: #4e73df;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                }

                                                ?>
                                            </tbody>
                                            </table>
                                    </div>
                                    <?php
                                    if ($labTestTotalItem > 16) {
                                    ?>
                                        <div class="d-flex justify-content-center" id="pagination-control">
                                            <?= $paginationHTML ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>

    <!-- Category Edit Modal -->
    <div class="modal fade" id="addEditTestCategory" tabindex="-1" aria-labelledby="addEditTestCategoryModelLabel" aria-hidden="true">
        <div class="modal-dialog" id="modal-sizeId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testCategoryAddEdit"></h5>
                    <button type="button" onClick="refreshPage()" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">
                        Close
                    </button>
                </div>
                <!-- MODAL BODY -->
                <div class="modal-body add-new-test-category-modal">

                </div>
                <!-- Modal Body end -->

            </div>
        </div>
    </div>
    <!-- End of Category Edit Modal -->


    <!-- Category Edit Modal -->
    <!-- <div class="modal fade" id="testEditModal" tabindex="-1" aria-labelledby="testEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" style="height: 200px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="testEditModalLabel">Edit Lab Test Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="refreshPage()"></button>
                </div>
                <div class="modal-body">
                    -- Your form or content goes here --
                </div>
                <div class="modal-footer">
                    <- Optional: Add action buttons if needed -->
    <!-- <button type="button" class="btn btn-sm btn-primary" onClick="updateTest()">Update</button> --
                </div>
            </div>
        </div>
    </div> -->

    <!-- for both sigle catagory detail view and single test details view modal -->
    <div class="modal fade" id="catagoryViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCategoryName"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body catagoryViewModal">

                </div>
            </div>
        </div>
    </div>


    <!-- End of Category Edit Modal -->

    <!-- Custom Javascript  -->
    <!-- LabCategoryEditModal function body gose hear -->
    <script src="<?= JS_PATH ?>custom-js.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.min.js"></script>
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <!-- <script src="<?= JS_PATH ?>lab-tests.js"></script> -->
    <script src="<?php echo JS_PATH ?>lab-test-page.js"></script>


    <!-- inline script -->
    <script>
        const baseUrl = window.location.origin + window.location.pathname;
        document.getElementById('base-url-holder').innerHTML = baseUrl;

        function testDataSearch(t) {
            console.log(t.id);
            let searchParameter = '';
            let currentUrl = window.location.origin + window.location.pathname

            if (t.id == 'dataSearch-btn') {
                let searchData = document.getElementById('search-test');
                if (searchData.value.length > 2) {

                    searchParameter += `&search=${searchData.value}`

                    let searchUrl = `${currentUrl}?${searchParameter}`;

                    window.location.replace(searchUrl);
                } else {
                    alert('Enter minimum 3 charechter');
                }

                document.getElementById('srch-btn-controler').innerHTML = '1';
            }

            if (t.id == 'reset-search') {
                window.location.replace(baseUrl);

                document.getElementById('dataSearch-btnDiv').classList.remove('d-none');
                document.getElementById('reset-searchBtn-div').classList.add('d-none');

                document.getElementById('srch-btn-controler').innerHTML == '0'
            }
        }


        // ================================================

        function deleteTestType(t) {
            // console.log(t);
            let testId = t.id;
            $.ajax({
                url: "ajax/testType-delete.php",
                type: "POST",
                data: {
                    delId: testId
                },
                success: function(data) {
                    // console.log(data);
                    if (data) {
                        $(t).closest('tr').fadeOut();
                    } else {
                        console.log('error');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error: " + textStatus, errorThrown);
                }
            });

        }
    </script>



</body>

</html>