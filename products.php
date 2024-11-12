<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


// print_r([$_SESSION]);
$page = "products";

//Intitilizing Doctor class for fetching doctors
$Products       = new Products();
$Pagination     = new Pagination();
$ProductImages  = new ProductImages();


// $productsData = json_decode($Products->showAllProducts());
// print_r($productsData->data);

if (isset($_GET['search'])) {

    $prodId = $_GET['search'];
    $productList = json_decode($Products->showProductsById($prodId));
    $newproductList[] = $productList->data;
    // print_r($newproductList);

    $pagination = json_decode($Pagination->arrayPagination($newproductList));
    // print_r($pagination);


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
} else {

    // Function INitilized 
    $col = 'admin_id';
    $result = json_decode($Pagination->productsWithPagination()); //showAllProducts
    $allProducts    = $result->products;
    $totalPtoducts  = $result->totalPtoducts;

    $productList = json_decode($Products->showProductsByLimit());
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Medicy Items</title>

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

                    <!-- New Section -->
                    <div class="col">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <!-- <div class="d-flex col-12"> -->
                                <div class="col-md-3 mt-2 p-2">
                                    <h6 class="m-0 font-weight-bold text-primary">Total Items:
                                        <?= $totalPtoducts ?>
                                    </h6>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="col-md-7">
                                        <input type="text" name="prodcut-search" id="prodcut-search" class="form-control w-100" style="justify-content: center;" placeholder="Search Products (Product Name / Product Composition)">

                                        <div class="p-2 bg-light col-md-10 c-dropdown" id="product-list">
                                            <div class="lists" id="lists">
                                                <?php
                                                if (!empty($productList->data) && is_array($productList->data)) {
                                                    foreach ($productList->data as $eachProd) {
                                                        // print_r($eachProd);
                                                ?>
                                                        <div class="p-1 border-bottom list">
                                                            <div class="" id="<?= $eachProd->product_id ?>" onclick="searchProduct(this)">
                                                                <?= $eachProd->name ?>
                                                            </div>
                                                            <div class="">
                                                                <small><?= $eachProd->comp_1 ?> , <?= $eachProd->comp_2 ?></small>
                                                            </div>
                                                        </div>

                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-sm btn-primary" href="add-products.php" style="margin-left: 4rem;"><i class="fas fa-plus"></i> Add</a>
                                    </div>
                                </div>
                                <!-- </div> -->
                            </div>
                            <div class="card-body">

                                <div class="d-flex justify-content-center">
                                    <div class="row card-div">

                                        <section>
                                            <div class="row ">

                                                <?php
                                                // print_r($allProducts);
                                                // echo count($allProducts);
                                                if ($allProducts != null) {
                                                    foreach ($allProducts as $item) {
                                                        // print_r($item);
                                                        $image = json_decode($ProductImages->showImagesByProduct($item->product_id));

                                                        // print_r($image);
                                                        if ($image->status) {
                                                            $imgData = $image->data[0];

                                                            $productImage = $imgData->image;
                                                        } else {
                                                            $productImage = 'default-product-image/medicy-default-product-image.jpg';
                                                        }

                                                        if ($item->dsc == null) {
                                                            $dsc = '';
                                                        } else {
                                                            $dsc = $item->dsc . '...';
                                                        }



                                                        $modalTitle = "Existing Product View / Edit";

                                                ?>

                                                        <div class="item col-12 col-sm-6 col-md-4 col-lg-3 ">
                                                            <div class="card  mb-3 p-3" style="min-width: 14rem; min-height: 11rem; max-width: 14rem; max-height: 21rem;">
                                                                <img src="<?php echo PROD_IMG_PATH ?><?php echo $productImage ?>" class="card-img-top" alt="..." style="max-height: 8rem;">
                                                                <div class="card-body">
                                                                    <label><b><?php echo $item->name; ?></b></label>
                                                                    <p class="mb-0"><b><?php $item->name ?></b></p>
                                                                    <small class="card-text mt-0" style="text-align: justify;"><?php echo substr($dsc, 0,25) .'...' ?></small>

                                                                </div>


                                                                <div class="row px-3 pb-2">
                                                                    <div class="col-6">₹ <?php echo $item->mrp ?></div>
                                                                    <div class="col-6 d-flex justify-content-end">
                                                                        <button class="btn btn-sm border border-info" data-toggle="modal" data-target="#productViewModal" id="<?php echo $item->product_id ?>" value="<?php echo $modalTitle; ?>" onclick="viewItem(this)">View</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php
                                                    }
                                                } else {
                                                    echo "No Item Avilable";
                                                }

                                                ?>

                                            </div>
                                            <div class="d-flex justify-content-center mt-3">
                                                <nav aria-label="Page navigation">
                                                    <?= $result->paginationHTML ?>
                                                </nav>
                                            </div>
                                        </section>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- End of Wrapper -->

                    </div>
                    <!-- End of Container -->

                </div>
                <!-- End of container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once SUP_ROOT_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!--End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Product Modal -->
    <div class="modal fade" id="productViewModal" tabindex="-1" aria-labelledby="product-view-edit-modal" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="product-view-edit-modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body productViewModal">
                    <!-- Product Details goes here by ajax  -->
                </div>
            </div>
        </div>
    </div>
    <!--End of Product Modal -->

    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>


    <script>
        var xmlhttp = new XMLHttpRequest();

        // =============== modal size control funcion ==============
        // function changeModalSize(flag, modalId) {



        //     let modal = document.getElementById(modalId);

        //     if (modal) {
        //         if (flag == 0) {
        //             modal.querySelector('.modal-dialog').classList.remove('modal-sm', 'modal-md', 'modal-lg', 'modal-xl');

        //             modal.querySelector('.modal-dialog').classList.add('modal-xl'); 
        //         }

        //         if (flag == 1) {
        //             modal.querySelector('.modal-dialog').classList.remove('modal-sm', 'modal-md', 'modal-lg', 'modal-xl');

        //             modal.querySelector('.modal-dialog').classList.add('modal-xl'); 
        //         }
        //     }
        // }
        // ================ end of modal size control =============

        // ========================== view and edit fucntion =========================
        const viewItem = (t) => {
            let prodId = t.id;
            let modalHeading = t.value;
            // console.log(modalHeading);

            let modalTitle = document.getElementById('product-view-edit-modal-title');
            modalTitle.textContent = modalHeading;

            url = `ajax/product-view-modal.ajax.php?id=${prodId}&table=${'products'}`;
            // if (verifiedValue) {
            //     changeModalSize('0', 'productViewModal');
            //     url = 'ajax/product-view-modal-for-user.ajax.php?id=' + prodId;
            // } 

            $(".productViewModal").html(
                '<iframe width="100%" height="500px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        }
        // === end of view and edit ==================================================


        // ========================== PRODUCT SEARCH START ===========================

        const productsSearch = document.getElementById("prodcut-search");
        const productsDropdown = document.getElementsByClassName("c-dropdown")[0];


        // productsSearch.addEventListener("focus", () => {
        //     productsDropdown.style.display = "block";
        // });


        document.addEventListener("click", (event) => {
            // Check if the clicked element is not the input field or the manufDropdown
            if (!productsSearch.contains(event.target) && !productsDropdown.contains(event.target)) {
                productsDropdown.style.display = "none";
            }
        });


        document.addEventListener("blur", (event) => {
            // Check if the element losing focus is not the manufDropdown or its descendants
            if (!productsDropdown.contains(event.relatedTarget)) {
                // Delay the hiding to allow the click event to be processed
                setTimeout(() => {
                    productsDropdown.style.display = "none";
                }, 100);
            }
        });



        productsSearch.addEventListener("keydown", () => {

            let list = document.getElementsByClassName('lists')[0];
            let searchVal = document.getElementById("prodcut-search").value;

            if (searchVal.length > 2) {

                let manufURL = `ajax/products.list-view.ajax.php?match=${searchVal}`;
                xmlhttp.open("GET", manufURL, false);
                xmlhttp.send(null);

                list.innerHTML = xmlhttp.responseText;
                document.getElementById('product-list').style.display = 'block';
            } else if (searchVal == '') {

                searchVal = 'all';

                let manufURL = `ajax/products.list-view.ajax.php?match=${searchVal}`;
                xmlhttp.open("GET", manufURL, false);
                xmlhttp.send(null);
                // console.log();
                list.innerHTML = xmlhttp.responseText;
                document.getElementById('product-list').style.display = 'block';
            } else {
                document.getElementById('product-list').style.display = 'none';
                list.innerHTML = '';
                productsDropdown.style.display = "none";
            }

        });

        //================================================================

        const searchProduct = (t) => {
            var prodId = t.id.trim();
            var prodName = t.innerHTML.trim();

            var currentURLWithoutQuery = window.location.origin + window.location.pathname;

            let newUrl = `${currentURLWithoutQuery}?search=${prodId}`;

            localStorage.setItem('prodName', prodName);

            window.location.href = newUrl;

            // document.getElementById("prodcut-search").value = prodName;
            // productsDropdown.style.display = "none";

        }


        document.addEventListener('DOMContentLoaded', function() {

            let storedProdName = localStorage.getItem('prodName');

            if (storedProdName !== null) {
                document.getElementById("prodcut-search").value = storedProdName;
                localStorage.setItem('prodName', '');
            }
        });
    </script>

</body>

</html>