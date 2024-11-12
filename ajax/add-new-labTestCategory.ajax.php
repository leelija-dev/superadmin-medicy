<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Add Items</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= CSS_PATH ?>font-awesome.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">
    <!-- sweet alert -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" type="text/css" />
    <!-- ckEditor -->
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css" />

</head>

<body id="page-top">
    <input class="d-none" id="global-flag" value="1">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content" class="container">
                <div class="mb-3">
                    <div class="card med-card" style="border: 1px solid #ced4da; padding: 1rem; width: 10rem; height: 9rem; position: relative; overflow: hidden;" id="lab-category-image">

                    </div>
                    <i class="fas fa-upload text-primary" id="upload-document1"
                        style="position: absolute; top: 25%; left: 45%; transform: translate(-50%, -50%); cursor: pointer;"
                        onclick="document.getElementById('fileInput1').click();">
                    </i>
                    <input type="file" class="d-none" name="fileInput1" id="fileInput1">
                </div>

                <div class="mb-3">
                    <label for="category-name" class="form-label">Category Name</label>
                    <input type="text" id="category-name" class="form-control" placeholder="Enter category name" autocomplete="off">
                </div>
                <div class="mb-3">
                    <label for="category-description" class="form-label">Category Description</label>
                    <textarea id="category-description" class="form-control" placeholder="Enter category description" rows="5" autocomplete="off"></textarea>
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary w-100" id="add-test-category-btn">Add Category</button>
                </div>
            </div>
            <!-- /end Add Product  -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <script type="importmap">
        {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.0.0/"
        }
    } 
    </script>


    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
    <!-- <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.js"></script> -->

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <!-- Sweet Alert Js  -->
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>
    <script type="module" src="<?= JS_PATH ?>admin-js/ckEditor-module.js" defer></script>
    <script type="module" src="<?= JS_PATH ?>admin-js/addEdit-labTestCategory-module.js" defer></script>
    
</body>

</html>