<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';//check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR."dbconnect.php";
require_once SUP_ADM_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR."encrypt.inc.php";
require_once CLASS_DIR."stockOut.class.php";
require_once CLASS_DIR."patients.class.php";


// CLASS INTIATING 
$StockOut = new StockOut();
$Patients = new Patients();

$soldItems = $StockOut->stockOutDisplay('');
// print_r($soldItems);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sales</title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Default styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.css" rel="stylesheet">

    <!-- Custom styles for this Page-->
    <link rel="stylesheet" href="<?= CSS_PATH ?>sales.css">

    <!-- Data Table CSS  -->
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

                    <!-- Page Heading -->
                    <!-- <h1 class="h3 mb-4 text-gray-800">Sell Products</h1> -->


                    <!-- Showing Sell Items  -->
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex d-flex justify-content-between">
                            <!-- <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6> -->
                            <a class="btn btn-sm btn-primary" href="new-sales.php"> New Sell <i
                                    class="fas fa-plus"></i></a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm " id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-light">
                                        <tr class="text-center">
                                            <th>Invoice</th>
                                            <th>Patient</th>
                                            <th>Bill Date</th>
                                            <th>Item</th>
                                            <th>Amount</th>
                                            <th>Payment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                        <?php
                                        if (count($soldItems) > 0):
                                            foreach ($soldItems as $soldItem) {
                                                $invoice    = $soldItem['invoice_id'];
                                                $patient    = $soldItem['customer_id'];
                                                $billDate   = date_create($soldItem['bill_date']);
                                                $billDate   = date_format($billDate, "d-m-Y");
                                                $billAmount = $soldItem['amount'];
                                                $paymentMode= $soldItem['payment_mode'];

                                                if ($patient != 'Cash Sales') {
                                                    $patientName = json_decode($Patients->patientsDisplayByPId($patient));

                                                    if($patientName!= null){
                                                        $patientName = $patientName->name;
                                                    }
                                                    else{
                                                        $patientName = "";
                                                    }
                                                }else{
                                                    $patientName = $patient;
                                                }

                                                echo "<tr class='text-center sales-table";
                                                ?>
                                            <?php
                                                $creditIcon = "";
                                                if ($paymentMode == "Credit") {
                                                    echo "text-danger";
                                                    $creditIcon = "<i class='ml-1 fas fa-exclamation-circle' data-toggle='tooltip' data-placement='top' title='This payment is due, Collect all the due payments.'></i>";
                                                }
                                                ?>
                                            <?php echo "'data-toggle='modal' data-target='#viewBillModal'>
                                                        <td onclick='viewBills(".$invoice.")'>".$invoice."</td>
                                                        <td onclick='viewBills(".$invoice.")'>".$patientName."</td>
                                                        <td onclick='viewBills(".$invoice.")'>".$billDate."</td>
                                                        <td onclick='viewBills(".$invoice.")'>".$soldItem['items']."</td>
                                                        <td onclick='viewBills(".$invoice.")'>".$billAmount."</td>
                                                        <td onclick='viewBills(".$invoice.")'>".$paymentMode, $creditIcon."</td>
                                                        <td>
                                                        <a class='ml-2' href='update-sales.php?id=".url_enc($invoice)."'><i class='fas fa-edit'></i></a>
                                                        
                                                        <a class='ml-2' href='_config/form-submission/item-invoice-reprint.php?id=".url_enc($invoice)."'><i class='fas fa-print'></i></a>

                                                        <a class='ml-2' data-id=".$invoice."><i class='fab fa-whatsapp'></i></i></a>

                                                    </td>
                                                    </tr>";

                                            }
                                        endif;
                                       ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End of Showing Sell Items  -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once SUP_ROOT_COMPONENT.'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- View Bill Modal -->

    <div class="modal fade" id="viewBillModal" tabindex="-1" aria-labelledby="viewBillModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewBillModalLabel">View Bill Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body viewBillModal">
                    
                </div>
            </div>
        </div>
    </div>
    <!-- View Bill Modal -->



    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    
    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>
    
    <!-- Core plugin JavaScript-->
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <!--Data Table plugins -->
    <script src="<?= PLUGIN_PATH ?>product-table/jquery.dataTables.js"></script>
    <script src="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script>

    <script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })

    const viewBills = (invoice) => {
        // alert(invoice);
        url = `ajax/viewBill.ajax.php?invoice=${invoice}`;
        $(".viewBillModal").html(
            '<iframe width="99%" height="340px" frameborder="0" allowtransparency="true" src="' +
            url + '"></iframe>');
    }
    </script>

</body>

</html>