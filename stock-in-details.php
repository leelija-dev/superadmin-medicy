<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR.'dbconnect.php';
require_once SUP_ADM_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'distributor.class.php';
require_once CLASS_DIR.'stockIn.class.php';


$page = "stock-in-details";

//objects Initilization
$Distributor        = new Distributor();
$StockIn            = new StockIn();


//function calling
$showStockIn = $StockIn->showStockInDecendingOrder();
// print_r($showStockIn);
// echo "<br><br>";
if($showStockIn != null){
    $StockInId = $showStockIn[0]['id'];
    // echo $StockInId;
}

// echo "<br>check stokinid : $StockInId";
// foreach($showStockIn as $stokIn){
//     // print_r($stokIn);
//     $id = $stokIn['id'];
//     $slNo = $id - $StockInId;
//     $slNo++;
//     echo "<br>$slNo";
// }
$showDistributor       = $Distributor->showDistributor();

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
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- <link rel="stylesheet" href="../css/font-awesome-6.1.1-pro.css"> -->

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Datatable Style CSS -->
    <link href="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.css" rel="stylesheet">


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
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 booked_btn">
                            <h6 class="m-0 font-weight-bold text-primary">Number of Stock in :
                                <?php echo count($showStockIn); ?></h6>
                        </div>
                        <div class="card-body">



                            <div class="table-responsive">
                                <table class="table table-sm" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-light">
                                        <tr>
                                            <th>Sl.</th>
                                            <th>Dist. Bill No</th>
                                            <th>Dist. Name</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th class="d-flex justify-content-around">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (count($showStockIn) > 0) {
                                            $StockInId = $showStockIn[0]['id'];
                                            $id = $showStockIn[0]['id'];
                                            $slNo = $id - $StockInId;
                                            foreach ($showStockIn as $stockIn) {
                                                $distributor = json_decode($Distributor->showDistributorById($stockIn['distributor_id']));
                                                $distributor = $distributor->data;
                                                // echo $stockIn['id'];
                                                // echo "stock in id : $StockInId";
                                                // echo "<br>id : $id";
                                                // echo "<br>sl no : $slNo";
                                                $slNo++;

                                        ?>

                                                <tr>
                                                    <td onclick="stockDetails('<?php echo $stockIn['distributor_bill'] ?>','<?php echo $stockIn['id'] ?>', )" data-toggle="modal" data-target="#exampleModal"><?php echo $slNo ?>
                                                    </td>

                                                    <td onclick="stockDetails('<?php echo $stockIn['distributor_bill'] ?>','<?php echo $stockIn['id'] ?>' )" data-toggle="modal" data-target="#exampleModal"><?php echo $stockIn['distributor_bill'] ?>
                                                    </td>

                                                    <td   onclick="stockDetails('<?php echo $stockIn['distributor_bill'] ?>','<?php echo $stockIn['id'] ?>' )" data-toggle="modal" data-target="#exampleModal"><?php echo $distributor[0]->name ?>
                                                    </td>

                                                    <td   onclick="stockDetails('<?php echo $stockIn['distributor_bill'] ?>','<?php echo $stockIn['id'] ?>' )" data-toggle="modal" data-target="#exampleModal"><?php echo $stockIn['bill_date'] ?>
                                                    </td>

                                                    <td   onclick="stockDetails('<?php echo $stockIn['distributor_bill'] ?>','<?php echo $stockIn['id'] ?>' )" data-toggle="modal" data-target="#exampleModal"><?php echo $stockIn['amount'] ?>
                                                    </td>

                                                    <td   onclick="stockDetails('<?php echo $stockIn['distributor_bill'] ?>','<?php echo $stockIn['id'] ?>' )" data-toggle="modal" data-target="#exampleModal"><?php echo $stockIn['payment_mode'] ?>
                                                    </td>
                                                    
                                                    <td class="d-flex justify-content-around align-middle">
                                                        <a class="text-primary pe-auto" role="button"   onclick="stockDetails('<?php echo $stockIn['distributor_bill'] ?>','<?php echo $stockIn['id'] ?>','<?php echo $StockInId ?>' , this.value1)" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-eye"></i>
                                                        </a>
                                                        <a class="text-primary" id="<?php echo $stockIn['distributor_bill'] ?>" href="stock-in-edit.php?edit=<?php echo $stockIn['distributor_bill'] ?>&editId=<?php echo $stockIn['id'] ?>" role="button"><i class=" fas fa-edit"></i>
                                                        </a>
                                                        <a class="text-danger" role="button"><i class="fas fa-trash" id="<?php echo $stockIn['id'] ?>" onclick="deleteStock(this.id)"></i></a>
                                                    </td>
                                                </tr>
                                        <?php
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
            <!-- /.container-fluid -->
            <!-- End of Main Content -->

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Stock-in Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body stockDetails">
                        </div>
                        <!-- <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div> -->
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <?php include_once SUP_ROOT_COMPONENT.'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <script src="<?= PLUGIN_PATH ?>product-table/jquery.dataTables.js"></script>
    <script src="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script>

    <script>
        const stockDetails = (distBill,id) => {
            
            url = `ajax/stockInDetails.view.ajax.php?distBill=${distBill}`;

            $(".stockDetails").html(
                '<iframe width="99%" height="350px" frameborder="0" overflow-x: hidden; overflow-y: scroll; allowtransparency="true"  src="' +
                url + '"></iframe>');

        } // end of viewAndEdit function
        // 
        function resizeIframe(obj) {
            obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';

        }
    </script>
    <!-- Sweet Alert Js  -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

    <script>
        //=================delete stock in delete=======================

        const deleteStock = (id) => {
            // alert(id);
            swal({
                    title: "Are you sure?",
                    text: "Want to Delete This Data?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        //alert(id);
                        $.ajax({
                            url: "ajax/stockin.delete.ajax.php",
                            type: "POST",
                            data: {
                                DeleteId: id,
                            },
                            success: function(response) {
                                console.log("final response",response);
                                if (response) {
                                    swal(
                                        "Deleted",
                                        "Manufacturer Has Been Deleted",
                                        "success"
                                    ).then(function() {
                                        parent.location.reload();
                                    });

                                } else {
                                    swal("Failed", "Product Deletion Failed!",
                                        "error");
                                    $("#error-message").html("Deletion Field !!!")
                                        .slideDown();
                                    $("success-message").slideUp();
                                }

                            }
                        });
                    }
                    return false;
                });


        }
    </script>

</body>

</html>