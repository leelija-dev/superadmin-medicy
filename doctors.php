<?php
// require_once dirname(__DIR__) . '/config/constant.php';
require_once 'config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';//check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR.'dbconnect.php';
require_once SUP_ADM_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'doctors.class.php';
require_once CLASS_DIR.'doctor.category.class.php';
require_once CLASS_DIR. 'encrypt.inc.php';


//Intitilizing Doctor class for fetching doctors
$doctors        = new Doctors();
$DoctorCategory = new DoctorCategory;


if(isset($_POST['add-doc']) == true){

    $docName            = $_POST['docName'];
    $docName            = 'Dr. '.$docName;
    $docSpecialization  = $_POST['docSpecialization'];
    $alsoWith           = $_POST['docAlsoWith'];
    $docEmail           = $_POST['docEmail'];
    $docRegNo           = $_POST['docRegNo'];
    $docDegree          = $_POST['docDegree'];
    $docPhno            = $_POST['docMob'];
    $docAddress         = $_POST['docAddress'];
    
    $addDoctors = $doctors->addDoctor($docRegNo, $docName, $docSpecialization, $docDegree, $alsoWith, $docAddress, $docEmail, $docPhno, $adminId);

}

$showDoctors = $doctors->showDoctors();
$showDoctors = json_decode($showDoctors, true);
// print_r($showDoctors);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Medicy Doctors</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/doctors.css">

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
                    <h1 class="h3 mb-2 text-gray-800">Doctors</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Doctors</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Specialization</th>
                                            <th>PH. No</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if($showDoctors && isset($showDoctors['status']) && $showDoctors['status'] == 1){
                                            $showDoctors = $showDoctors['data'];
                                            foreach ($showDoctors as $doctors) {
                                                $docId              = $doctors['doctor_id'];
                                                $docRegNo           = $doctors['doctor_reg_no'];
                                                $docName            = $doctors['doctor_name'];
                                                $docSpecialization  = $doctors['doctor_specialization'];
                                                $docDeg             = $doctors['doctor_degree'];
                                                $docAlsoWith        = $doctors['also_with'];
                                                $docAddrs           = $doctors['doctor_address'];
                                                $docEmail           = $doctors['doctor_email'];
                                                $docPhno            = $doctors['doctor_phno'];
    
                                                //initilizing Doctors Category
                                                $docSplz = $DoctorCategory->showDoctorCategoryById($docSpecialization);
                                                $docSplz = json_decode($docSplz, true);
                                                if($docSplz && $docSplz['status'] == 1 && !empty($docSplz))
                                                foreach($docSplz['data'] as $docSplzShow){
                                                    $docSpecializn = $docSplzShow['category_name'];
    
                                                    echo'<tr>
                                                        <td>'.$docId.'</td>
                                                        <td>'.$docName.'</td>
                                                        <td>'.$docSpecializn.'</td>
                                                        <td>'.$docPhno.'</td>
                                                        <td>'.$docEmail.'</td>
                                                        <td>
                                                        <a href="dr-prescription.php?prescription='.url_enc($docId).'" class="text-primary" title="View and Print"><i class="fas fa-print"></i></a>
                                                        
                                                        <a class="" data-toggle="modal" data-target="#docModal" onclick="docViewAndEdit('.$docId.')"><i class="fas fa-edit"></i></a>
                                                        
                                                        <a class="delete-btn" data-id="'.$docId.'"  title="Delete"><i class="far fa-trash-alt"></i></a>
    
                                                        
                                                            </td>
                                                    </tr>';
                                                }
                                            }
                                        }
                                        
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
                <!--New Doctor Entry Section-->
                <div class="col" style="margin: 0 auto; width:98%;">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Add New Doctor</h6>
                        </div>
                        <div class="card-body">
                            <form action="doctors.php" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="docName">Doctor Name</label>
                                            <input class="form-control" type="text" name="docName" id="docName"
                                                required>
                                        </div>


                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="docSpecialization">Doctor
                                                Specialization</label>
                                            <select class="form-control" name="docSpecialization" id="docSpecialization" required>
                                                <option value="" disabled selected>Select Doctor Specialization</option>
                                                <?php
                                                $showDocSplz = $DoctorCategory->showDoctorCategory();
                                                foreach($showDocSplz as $docSplzShow){
                                                    $docSpecializnID    = $docSplzShow['doctor_category_id'];
                                                    $docSpecializn      = $docSplzShow['category_name'];

                                                    echo '<option value="'.$docSpecializnID.'">'.$docSpecializn.'</option>';

                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="docAlsoWith">Also With</label>
                                            <input class="form-control" type="text" name="docAlsoWith" id="docAlsoWith"
                                                >
                                        </div>

                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="docEmail">Doctor Email</label>
                                            <input class="form-control" type="text" name="docEmail" id="docEmail">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="docMob">Doctor Mob No</label>
                                            <input class="form-control" type="text" name="docMob" id="docMob"
                                                maxlength="10" minlength="10" >
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="docRegNo">Doctor Reg No</label>
                                            <input class="form-control" type="text" name="docRegNo" id="docRegNo"
                                            maxlength="10" >
                                        </div>

                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="docDegree">Doctor Degree</label>
                                            <input class="form-control" type="text" name="docDegree" id="docDegree"
                                                >
                                        </div>

                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="docAddress">Full Address</label>
                                            <textarea class="form-control" name="docAddress" id="docAddress" cols="30"
                                                rows="6" ></textarea>
                                        </div>

                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2 me-md-2">
                                    <button class="btn btn-success me-md-2" type="submit" name="add-doc">Add
                                        Now</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--End New Doctor Entry Section-->
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

        <!-- Doctor View and Edit Modal -->
        <div class="modal fade" id="docModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Doctor Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body docViewAndEdit">
                        <!-- Doctors Details Will Appeare Here By AJAX -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
                        <button type="button" class="btn btn-primary" onclick="refreshPage()">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Doctor View and Edit Modal -->

        <!-- Bootstrap core JavaScript-->
        <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

        <!-- Custom Javascript  -->
        <script src="<?php echo JS_PATH ?>custom-js.js"></script>
        <script>
        $(document).ready(function() {
            $(document).on("click", ".delete-btn", function() {

                if (confirm("Are You Sure?")) {
                    docId = $(this).data("id");
                    btn = this;
                    // alert(btn);

                    $.ajax({
                        url: "ajax/doctors.delete.ajax.php",
                        type: "POST",
                        data: {
                            id: docId
                        },
                        success: function(data) {
                            if (data == 1) {
                                $(btn).closest("tr").fadeOut()
                            } else {
                                $("#error-message").html("Deletion Field !!!").slideDown();
                                $("success-message").slideUp();
                            }

                        }
                    });
                }
                return false;

            })

        })


        
        const docViewAndEdit = (docId) =>{
            let ViewAndEditdocId = docId;
            // alert(ViewAndEditdocId);
            let url = "ajax/doctors.view.ajax.php?docId=" + ViewAndEditdocId;
            $(".docViewAndEdit").html('<iframe width="99%" scrolling="no" frameborder="0"   allowtransparency="true" onload="resizeIframe(this)" src="' + url + '"></iframe>');
            }// end of viewAndEdit function


        function resizeIframe(obj) {
            obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
        }

        </script>



        <!-- Core plugin JavaScript-->
        <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="<?php echo PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="<?php echo JS_PATH ?>demo/datatables-demo.js"></script>

</body>

</html>