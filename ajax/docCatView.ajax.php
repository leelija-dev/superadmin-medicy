<?php 
// require_once dirname(__DIR__).'/config/constant.php';
// require_once realpath(dirname(dirname(__DIR__)). '/config/constant.php');
require_once (dirname(__DIR__)). '/config/constant.php';
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'doctor.category.class.php';


$docSpecialization = $_GET['specializationid'];

$DoctorCategory = new DoctorCategory();

$showDoctorCategory = $DoctorCategory->showDoctorCategoryById($docSpecialization);
$showDoctorCategory = json_decode($showDoctorCategory,true);
if($showDoctorCategory && $showDoctorCategory['status'] == 1 && !empty($showDoctorCategory))
foreach ($showDoctorCategory['data'] as $rowDoctorCategory) {
    $id      = $rowDoctorCategory['doctor_category_id'];
    $catName =$rowDoctorCategory['category_name'];
    $catDsc  = $rowDoctorCategory['category_descreption'];

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.css">

</head>

<body class="mx-2">
    <form>
        <input type="hidden" id="docCatId" name="nm_option" value="<?php echo $docSpecialization;?>">
        <div class="form-group">
            <label class="form-label" for="cat-name">Specialization:</label>
            <input type="text" class="form-control" id="cat-name" name="cat-name" value="<?php echo $catName; ?>">
        </div>
        <div class="form-group my-2">
            <label class="form-label" for="cat-dsc">Specialization Description:</label>
            <textarea class="form-control" id="cat-dsc" name="cat-dsc" rows="4"><?php echo $catDsc; ?></textarea>
        </div>

        <div class="reportUpdate" id="reportUpdate">
            <!-- Ajax Update Reporet Goes Here -->
        </div>

        <div class=" justify-content-md-end">
            <button type="button" class="btn btn-sm btn-primary" name="save-changes" onclick="editDocCat()">Save changes</button>
        </div>
    </form>

    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.min.js"></script>

    <script>
    function editDocCat() {
        let docCatId   = document.getElementById("docCatId").value;
        let docCatName = document.getElementById("cat-name").value;
        let docCatDdsc = document.getElementById("cat-dsc").value;
        // console.log(editTestCategoryDsc);
        let url = "docCatEdit.ajax.php?docCatId=" + escape(docCatId) + "&docCatName=" + escape(docCatName) + "&docCatDdsc=" + escape(docCatDdsc);
        // console.log(url);
        // alert('Working');
        // $("#reportUpdate").html('<iframe width="99%" height="40px" frameborder="0" allowtransparency="true" src="'+url+'"></iframe>');
        // alert("Hello");
        request.open('GET', url, true);
        request.onreadystatechange = getEditUpdates;
        request.send(null);
    }

    function getEditUpdates() {
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

</body>

</html>