<?php
// require_once dirname(__DIR__).'/config/constant.php';
require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'labtypes.class.php';

$showLabtypeId = $_GET['labCategoryId'];
$labTypes = new LabTypes();
$showLabTypes = $labTypes->showLabTypesById($showLabtypeId);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>lab-test.css">

</head>

<body>
    <div class="mx-2">
        <form>
            <?php
            foreach ($showLabTypes as $lab) {
                $testImage = $lab['image'];
                $testName = $lab['test_type_name'];
                $testPvdBy = $lab['provided_by'];
                $testDsc = $lab['dsc'];

            }
            ?>
            <input type="hidden" id="editCatDtlsId" name="nm_option" value="<?php echo $showLabtypeId;?>">
            <div class="mb-3">
                <img src="<?php echo LOCAL_DIR .$testImage; ?>" alt="No Image">
                <!-- <input type="file" class="form-control" name="editTestCategoryImage" id="editTestCategoryImage"> -->
            </div>
            <div class="mb-3">
                <label for="editTestCategoryName" class="form-label"> Test Category Name</label>
                <input type="text" class="form-control" name="editTestCategoryName" id="editTestCategoryName"
                    value="<?php echo $testName; ?>">
            </div>
            <div class="mb-3">
                <label for="edit-test-category-name" class="form-label">Test Category Provided By</label>
                <textarea type="text" rows="3" class="form-control" name="edit-test-category-name"
                    id="editTestCategoryProvidedBy"><?php echo $testPvdBy; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="edit-test-category-name" class="form-label">Test Category Description</label>
                <textarea type="text" rows="4" class="form-control" name="edit-test-category-name"
                    id="editTestCategoryDsc"><?php echo $testDsc; ?></textarea>
            </div>
            <div id="reportUpdate" class="mb-3">

            </div>
            <div class="d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-sm btn-primary" onclick="editLabCatDtls()">Save changes</button>

            </div>
        </form>

    </div>

    <script>
    function editLabCatDtls() {
        let editCatDtlsId = $("#editCatDtlsId").val();
        // let editTestCategoryImage = document.getElementById("editTestCategoryImage").value;
        // "&editTestCategoryImage=" + escape(editTestCategoryImage) + 
        let editTestCategoryName = document.getElementById("editTestCategoryName").value;
        let editTestCategoryProvidedBy = document.getElementById("editTestCategoryProvidedBy").value;
        let editTestCategoryDsc = document.getElementById("editTestCategoryDsc").value;
        // console.log(editTestCategoryDsc);
        let url = "updateLabCat-Ajax.php?editCatDtlsId=" + escape(editCatDtlsId) + "&editTestCategoryName=" + escape(editTestCategoryName) + "&editTestCategoryProvidedBy=" + escape(editTestCategoryProvidedBy) + "&editTestCategoryDsc=" + escape(editTestCategoryDsc);
        // console.log(url);
        // alert('Working');
        // $("#reportUpdate").html('<iframe width="99%" height="40px" frameborder="0" allowtransparency="true" src="'+url+'"></iframe>');
        // alert("Hello");
        request.open('GET', url, true);

        request.onreadystatechange = getEditUpdates;

        request.send(null);
    }

    getEditUpdates = () => {
        if (request.readyState == 4) {
            // alert("Hello");

            if (request.status == 200) {
                // alert("Hello");

                var xmlResponse = request.responseText;
                // alert(xmlResponse);

                document.getElementById('reportUpdate').innerHTML = xmlResponse;
            } else if (request.status == 404) {
                alert("Request page doesn't exist");
            } else if (request.status == 403) {
                alert("Request page doesn't exist");
            } else {
                alert("Error: Status Code is " + request.statusText);
            }
        }
    } //eof
    </script>

    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.min.js"></script>


    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>


</body>

</html>