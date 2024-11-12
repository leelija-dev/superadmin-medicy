<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';
require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR. 'dbconnect.php';
require_once CLASS_DIR . 'empRole.class.php';

$desigRole = new Emproles();

if (isset($_POST['add-designation']) == true) {

    $desigName = $_POST['desig-name'];

    $addDesigRole = $desigRole->addDesigRole($desigName, $adminId);

    if ($addDesigRole) {
        echo "<script>alert('Role Inserted!')</script>";
    } else {
        echo "<script>alert('Role Insertion Failed!')</script>";
    }
}

$data = $desigRole->designationRole($adminId);
$showDesigRole = json_decode($data);
// foreach($showDesigRole as $show){
//     echo $show->desig_name;
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Medicy Designation</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/employees.css">
    <style>
        #toggle {
            /* position: absolutte;
            top: 25%;
            left: 200px; */
            position: relative;
            float: right;
            transform: translateY(-115%);
            width: 30px;
            height: 30px;
            background: url(img/hide-password.png);
            /* background-color: black; */
            background-size: cover;
            cursor: pointer;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Employees</h1>

                    <!-- DataTales Example -->

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="d-flex">
                                <h6 class="m-0 font-weight-bold text-primary">Designation Role List</h6>
                                <?php
                                if (isset($_GET['action'])) {
                                    if (isset($_GET['msg'])) {
                                        echo "<p><strong>{$_GET['msg']}</strong></p>";
                                    }
                                }
                                ?>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bd-example-modal-lg">Add New Role</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Added_By</th>
                                            <th>Added_On</th>
                                            <th>Admin</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        foreach ($showDesigRole as $show) {
                                            $designId = $show->id;
                                            echo "<tr>";
                                            echo "<td>{$designId}</td>";
                                            echo "<td>{$show->desig_name}</td>";
                                            echo "<td>{$show->add_by}</td>";
                                            echo "<td>{$show->add_on}</td>";
                                            echo "<td>{$show->admin_id}</td>";
                                            // You can add an action column here if needed
                                            echo "<td>
                                                 <a class='text-primary' onclick='viewAndEdit(" . $designId . ")' title='Edit' data-toggle='modal' data-target='#designViewAndEditModal'><i class='fas fa-edit'></i></a>
                                                  <a class='delete-btn' data-id='" . $designId . "'  title='Delete'><i class='far fa-trash-alt'></i></a>
                                                 </td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
                <!--Entry Section-->
                <div class="col" style="margin: 0 auto; width:98%;">
                    <div class="card shadow mb-4">

                    </div>
                    <!-- ...........modal start........ -->
                    <div class="modal fade bd-example-modal-lg " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add New Role</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="empRole.php" method="post">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="desig-name"> Designation Name:</label>
                                                    <input class="form-control" type="text" name="desig-name" id="desig-name" maxlength="30" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2 me-md-2"> -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button class="btn btn-success me-md-2" type="submit" name="add-designation">Add Now</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- ...........modal end........ -->
                    </div>
                </div>
                <!--End Entry Section-->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include ROOT_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Emp Edit and View Modal -->
    <div class="modal fade" id="designViewAndEditModal" tabindex="-1" role="dialog" aria-labelledby="designViewAndEditModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="designViewAndEditModalLabel">Designation Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body viewnedit">
                    <!-- MODAL CONTENT GOES HERE BY AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="refreshPage()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Emp Edit and View Modal End -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Custom Javascript -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>
    <script>
        viewAndEdit = (designId) => {
            let designationId = designId;
            let url = "ajax/emprole.view.ajax.php?designationId=" + designationId;
            $(".viewnedit").html('<iframe width="99%" height="440px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } // end of viewAndEdit function
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

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
        $(document).ready(function() {
            $(document).on("click", ".delete-btn", function() {

                if (confirm("Are you want delete data?")) {
                    designId = $(this).data("id");
                    console.log(designId);
                    // echo $empDelete.$this->conn->error;exit;

                    btn = this;
                    $.ajax({
                        url: "ajax/emprole.Delete.ajax.php",
                        type: "POST",
                        data: {
                            id: designId
                        },
                        success: function(response) {
                            if (response == 1) {
                                $(btn).closest("tr").fadeOut();
                            } else {
                                // alert("Error deleting data: " + response);
                                console.error("Error deleting data: " + response);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: " + status + " - " + error);
                        }
                    });
                }
                return false;

            })

        })

    </script>



</body>

</html>