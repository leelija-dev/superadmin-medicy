<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'UtilityFiles.class.php';
require_once CLASS_DIR.'labTestTypes.class.php';

$showLabtypeId = $_GET['labCategoryId'];
$labTypes = new LabTestTypes();
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
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.css">
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

                if (!empty($testImage)) {
                    $testImg = LABTEST_IMG_PATH . $testImage;
                } else {
                    $testImg = LABTEST_IMG_PATH . 'default-lab-test/labtest.svg';
                }
            }
            ?>
            <div class="editDiv">
                <div class="editImgDiv">
                    <input type="hidden" id="editCatDtlsId" name="nm_option" value="<?php echo $showLabtypeId;?>">
                    <div class="mb-3 editImg">
                        <img src="<?php echo $testImg; ?>" alt="Image">
                        <!-- <input type="file" class="form-control" name="editTestCategoryImage" id="editTestCategoryImage"
                            hidden> -->
                    </div>
                    <!-- <label class="btn btn-primary w-100" for="editTestCategoryImage">Change Image</label> -->
                </div>
                <div class="editContDiv">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control testEditInput" id="editTestCategoryName"
                            name="editTestCategoryName" placeholder="" value="<?php echo $testName; ?>" required
                            autocomplete="off">
                        <label for="editTestCategoryName" style="color: #5A59EB; margin-left: -14px;"><i
                                class="fas fa-vial"></i> Test Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control testEditInput" id="editTestCategoryProvidedBy"
                            name="editTestCategoryProvidedBy" value="<?php echo $testPvdBy; ?>" placeholder="">
                        <label for="editTestCategoryProvidedBy" style="color: #5A59EB; margin-left: -14px;"><i
                                class="fas fa-clinic-medical"></i> Test Provided</label>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control testEditInput" placeholder="Leave a comment here" id="editTestCategoryDsc" name="editTestCategoryDsc" style="height: 148px"><?php echo $testDsc; ?></textarea>
                        <label for="editTestCategoryDsc" style="color: #5A59EB; margin-left: -14px;"><i class="fas fa-file-medical"></i> Test Description</label>
                    </div>
                    <!-- <div class="mb-3">
                        <label for="editTestCategoryName" class="form-label"> Test Category Name</label>
                        <input type="text" class="form-control testEditInput" name="editTestCategoryName"
                            id="editTestCategoryName" value="<?php echo $testName; ?>">
                    </div> -->
                    <!-- <div class="mb-3">
                        <label for="edit-test-category-name" class="form-label">Test Category Provided By</label>
                        <input type="text" class="form-control testEditInput" name="edit-test-category-name"
                            id="editTestCategoryProvidedBy" value="<?php echo $testPvdBy; ?>">
                    </div> -->
                    <!-- <div class="">
                        <label for="edit-test-category-name" class="form-label">Test Category Description</label>
                        <textarea type="text" rows="3" class="form-control testEditInput mt-2"
                            name="edit-test-category-name" id="editTestCategoryDsc"><?php echo $testDsc; ?></textarea>
                    </div> -->
                    <div id="reportUpdate" class="p-3 bg-success text-center text-white fw-2 w-75"
                        style="display:none;">
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="button" class="btn btn-primary" onclick="editLabCatDtls()">Save
                            changes</button>

                    </div>
                </div>
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
        let url = "updateLabCat-Ajax.php?editCatDtlsId=" + escape(editCatDtlsId) + "&editTestCategoryName=" + escape(
                editTestCategoryName) + "&editTestCategoryProvidedBy=" + escape(editTestCategoryProvidedBy) +
            "&editTestCategoryDsc=" + escape(editTestCategoryDsc);
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
                document.getElementById("reportUpdate").style.display = 'block';
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
    <!-- <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.min.js"></script> -->


    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>


</body>

</html>