<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR.'dbconnect.php';
require_once SUP_ADM_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'doctor.category.class.php';

$nodataFound = 'NO DATA FOUND';
// INITILIZATION CLASSES
$DoctorCategory = new DoctorCategory();

if (isset($_POST['add'])) {
    $docCatNme = $_POST['splz-name'];
    $docDesc   = $_POST['splz-dsc'];

    $addDoctorCategory = $DoctorCategory->addDoctorCategory($docCatNme, $docDesc, $employeeId, NOW, $adminId);
    if ($addDoctorCategory) {
        echo '<script> alert("Doctor Specialization Added!");</script>';
    }else {
        echo '<script> alert("Failed!");</script>';
    }
}


// FUNCTION INITILIZATION 
$showDoctorCategory = $DoctorCategory->showDoctorCategoryByAdmin();
// print_r($showDoctorCategory);



?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Doctor Specialization - Medicy Health Care</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> -->


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include SUP_ROOT_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include SUP_ROOT_COMPONENT.'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Doctor Specialization</h1>
                    <div class="row">

                        <div class="col-md-5">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <form method="post">

                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="splz-name">Specialization</Address></label>
                                            <input class="form-control" id="splz-name" name="splz-name" placeholder="Doctor Specialization Name" required>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <label class="mb-0 mt-1" for="splz-dsc">Description</Address></label>
                                            <textarea class="form-control" name="splz-dsc" id="splz-dsc" cols="30" rows="4"></textarea>
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                                            <button class="btn btn-primary me-md-2" name="add" type="submit">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <!-- Showing Unit Table -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Specialization</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if(is_array($showDoctorCategory) && $showDoctorCategory != null){
                                                foreach ($showDoctorCategory as $rowDoctorCategory) {
                                                    $specializationid = $rowDoctorCategory['doctor_category_id'];
                                                    $specializationName = $rowDoctorCategory['category_name'];
                                                    $specializationDsc = $rowDoctorCategory['category_descreption'];

                                                    echo '<tr>
                                                    <td>'.$specializationid.'</td>
                                                    <td>'.$specializationName.'</td>
                                                    <td>'.substr($specializationDsc, 0, 65).'..</td>
                                                    <td>
                                                        <a class="text-primary" data-toggle="modal" data-target=".docCatModal"
                                                            onclick="viewAndEdit('.$specializationid.')"><i
                                                                class="fas fa-edit"></i></a>

                                                        <a class="text-primary delete-btn" data-id="'.$specializationid.'"><i class="far fa-trash-alt"></i></a>
                                                    </td>
                                                </tr>';

                                                }
                                            }
                                            ?>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include SUP_ROOT_COMPONENT.'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- View & Edit Doctor Category Modal -->
    <div class="modal fade docCatModal" tabindex="-1" role="dialog"
        aria-labelledby="docCatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Doctor Specialization Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body view-and-edit-specialization">
                    <!-- Appointments Details Goes Here By Ajax -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="window.location.reload()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /End View & Edit Doctor Category Modal -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>
    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo JS_PATH ?>demo/datatables-demo.js"></script>

    <script>
        //View and Update Doctor Specialization
        viewAndEdit = (specializationid) =>{
            let url = "ajax/docCatView.ajax.php?specializationid=" + specializationid;
            $(".view-and-edit-specialization").html(
            '<iframe width="99%" height="340px" frameborder="0" allowtransparency="true" src="' +
            url + '"></iframe>');
        }


        // Delete Doctor Specialization
        $(document).ready(function() {
        $(document).on("click", ".delete-btn", function() {

            if (confirm("Are You Sure?")) {
                CatId = $(this).data("id");
                // alert(CatId);
                btn = this;

                $.ajax({
                    url: "ajax/docCat.delete.ajax.php", 
                    type: "POST",
                    data: {
                        id: CatId
                    },
                    success: function (response) {
                        if (response == 1) {
                            $(btn).closest("tr").fadeOut()
                        } else {
                        alert("Deletion Failed!");
                            $("#error-message").html("Deletion Failed !!!").slideDown();
                            $("success-message").slideUp();
                        }

                    }
                });
            }
            return false;
        })

    })
    </script>


</body>

</html>