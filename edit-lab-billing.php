<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';


//Classes Initilized
$appointments    = new Appointments();
$Patients        = new Patients();
$SubTests        = new SubTests();
$Doctors         = new Doctors();
$LabBilling      = new LabBilling();
$LabBillDetails  = new LabBillDetails();


//Function Initilized
$showDoctors    = $Doctors->showDoctors();
$showSubTests   = $SubTests->showSubTests();


//Creating Object of Appointments Class
// $appointments = new Appointments();


if (isset($_GET['invoice'])) {
   $billId =  $_GET['invoice'];


  $labBillDisplay = $LabBilling->labBillDisplayById($billId);
  $patientId = 0;
  $refDoc = 0;
  // echo $labBillDisplay;
  if (is_array($labBillDisplay)) {
    foreach ($labBillDisplay as $labBill) {
      $patientId = $labBill['patient_id'];
      $testDate  = $labBill['test_date'];
      $refDoc    = $labBill['refered_doctor'];
      $labBill['total_amount'];
      $labBill['discount'];
      $labBill['total_after_discount'];
      $labBill['paid_amount'];
      $labBill['due_amount'];

      // echo $labBill['total_amount'].'<br>';
      // echo $labBill['discount'].'<br>';
      // echo $labBill['total_after_discount'].'<br>';
      // echo $labBill['paid_amount'].'<br>';
      // echo $labBill['due_amount'].'<br>';
      // exit;
    }
  }
  $patientsDisplay = null;
  if ($patientId) {
    $patientsDisplay = json_decode($Patients->patientsDisplayByPId($patientId));
    $name = isset($patientsDisplay->name) ? $patientsDisplay->name : 'N/A';
  }

  // print_r($patientsDisplay);
  // echo $patientsDisplay[0]['name'].'<br>';
  // echo $patientsDisplay[0]['age'].'<br>';
  // echo $patientsDisplay[0]['gender'].'<br>';
  // echo $patientsDisplay[0]['phno'].'<br>';
  // echo $patientsDisplay[0][].'<br>';

  // foreach($patientsDisplay as $patients){
  //     $patientName  = $patients['name'];
  //     $patientAge   = $patients['age'];
  // }
  // exit;

  // $patientId          = $_POST["patientId"];
  // $testDate           = $_POST["testDate"];
  // $patientName        = $_POST["patientName"];
  // $patientGurdianName = $_POST["patientGurdianName"];
  // $patientEmail       = $_POST["patientEmail"];
  // $patientPhoneNumber = $_POST["patientPhoneNumber"];
  // $patientAge         = $_POST["patientAge"];
  // $patientWeight      = $_POST["patientWeight"];
  // $gender             = $_POST["gender"];
  // $patientAddress1    = $_POST["patientAddress1"];
  // $patientAddress2    = $_POST["patientAddress2"];
  // $patientPS          = $_POST["patientPS"];
  // $patientDist        = $_POST["patientDist"];
  // $patientPIN         = $_POST["patientPIN"];
  // $patientState       = $_POST["patientState"];

}
############################################################################################

$existsDoctorId = NULL;
$existsDocName = NULL;

if (is_numeric($refDoc)) {
  $existsDoctorId = $refDoc;
  $docDetails = $Doctors->showDoctorNameById($refDoc);
  $docDetails = json_decode($docDetails);
  if ($docDetails->status == 1) {
    foreach ($docDetails->data as $rowDoctor) {
      $existsDoctorName = $rowDoctor->doctor_name;
        // echo $doctorName;
    }
} 
  // if (is_array($docDetails) && isset($docDetails[0][2])) {
  //   $existsDoctorName = $docDetails[0][2];
  // }
  // $existsDoctorName = $docDetails[0][2];
} else {
  $existsDoctorName = $refDoc;
  $existsDocName    = $refDoc;
}


?>

<!doctype html>
<html lang="en">

<head>

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.css">
  <!-- <link rel="stylesheet" href="../css/patient-style.css"> -->
  <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/custom-form-style.css">


  <link rel="stylesheet" href="<?php echo CSS_PATH ?>font-awesome.css">
  <title>Lab Test Bill Generate - Medicy Health Care</title>


  <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="<?php echo CSS_PATH ?>sb-admin-2.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/appointment.css">


</head>

<body>

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
        <!-- End of top bar -->


        <div class="container-fluid px-1  mx-auto">
          <div class="row d-flex justify-content-center">
            <div class="col-xl-5 col-lg-5 col-md-5 text-center">
              <div class="card shadow p-4 mt-0">
                <div class="row justify-content-between text-left">
                  <div class="form-group col-sm-12 flex-column d-flex">
                    <div class="row justify-content-start">
                      <div class="col-md-5 mb-0">
                        <p>Patient Name: </p>
                      </div>
                      <div class="col-md-7 mb-0 justify-content-start">
                        <p class="text-start"><b><?php echo $name; ?> </b></p>
                      </div>

                      <div class="col-md-5 mb-0">
                        <p>Patient ID: </p>
                      </div>
                      <div class="col-md-7 mb-0 justify-content-start">
                        <p class="text-start"><b><?php echo $patientId; ?></b></p>
                      </div>
                      <div class="col-md-5 mb-0">
                        <p>Test Date: </p>
                      </div>
                      <div class="col-md-7 mb-0 justify-content-start">
                        <p class="text-start"><b><?php echo $testDate; ?> </b></p>
                      </div>

                      <div class="col-md-5 mb-0">
                        <p>Rrefered Doctor: </p>
                      </div>
                      <div class="col-md-7 mb-0 justify-content-start">
                        <p class="text-start"><b><span id="preferedDoc"><?php echo $existsDoctorName ?> </span></b></p>
                      </div>

                    </div>

                  </div>
                </div>


                <div class="row justify-content-between text-left">
                  <div class="form-group col-sm-12 flex-column d-flex my-0">
                    <label class="form-control-label" for="patientDoctor">Rreffered By</label>
                    <select id="docList" class="form-control" name="patientDoctor" onChange="getDoc()" required>

                      <?php echo '<option value="' . $refDoc . '">' . $existsDoctorName . '</option>' ?>
                      <option value="">New Doctor</option>
                      <option value="Self">By Self</option>

                      <?php
                      $showDoctors = json_decode($showDoctors, true);
                      print_r($showDoctors);
                      foreach ($showDoctors as $showDoctorDetails) {
                        $doctorId = $showDoctorDetails['doctor_id'];
                        $doctorName = $showDoctorDetails['doctor_name'];
                        echo '<option value="' . $doctorId . '">' . $doctorName . '</option>';
                      }
                      ?>
                    </select>
                  </div>

                  <div class="justify-content-center text-center">
                    or
                  </div>

                  <div class="form-group col-sm-12 flex-column d-flex mt-0">
                    <input type="text" id="docName" class="form-control" placeholder="Enter Doctor Name" onkeyup="newDoctor(this.value);">
                  </div>
                </div>

                <div class="row justify-content-between text-left">
                  <div class="form-group col-sm-12 flex-column d-flex mt-0">
                    <input type="text" id="test-name" hidden>
                    <input type="text" id="test-id" hidden>
                    <select id="test" class="form-control" name="test" onChange="getPrice()" required>
                      <option disabled selected>Select Test</option>
                      <?php
                      foreach ($showSubTests as $rowSubTests) {
                        $subTestId   = $rowSubTests['id'];
                        $subTestName = $rowSubTests['sub_test_name'];
                        echo '<option value=' . $subTestId . '>' . $subTestName . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="row justify-content-between text-left">
                  <div class="form-group col-sm-5 flex-column d-flex mt-0">
                    <p class="form-control">Price ₹<span id="price">0</span>/Test</p>
                  </div>

                  <div class="form-group col-sm-5 flex-column d-flex mt-0">
                    <input class="form-control" id="disc" onkeyup="getDisc(this.value);" placeholder="Discount %" type="number" disabled>
                  </div>
                </div>
                <div class="row justify-content-between text-left">
                  <div class="form-group col-sm-6 flex-column d-flex mt-0">
                    <p class="form-control">Total ₹ <span id="total"></span></p>
                  </div>
                  <div class="form-group col-sm-5 flex-column d-flex mt-0">
                    <button class="btn btn-primary" id="add-bill-btn" type="button" onClick="getBill()" disabled>Add to Bill <i class="fa fa-arrow-right"></i></button>
                  </div>
                </div>


              </div>
            </div>


            <div class="col-xl-7 col-lg-7 col-md-7 text-center">
              <div class="card shadow p-4 mt-0">
                <form class="form-card" action="altered-tests-bill-invoice.php" method="post">
                  <input type="hidden" name="patientId" value="<?php echo $patientId; ?>">
                  <input type="hidden" name="billId" value="<?php echo $billId; ?>">

                  <input type="hidden" name="patientName" value="<?php echo $patientsDisplay->name; ?>">

                  <input type="hidden" name="patientAge" value="<?php echo $patientsDisplay->age; ?>">
                  <input type="hidden" name="patientGender" value="<?php echo $patientsDisplay->gender; ?>">
                  <input type="hidden" name="patientPhnNo" value="<?php echo $patientsDisplay->phno; ?>">
                  <input type="hidden" name="patientTestDate" value="<?php echo $testDate; ?>">
                  <input type="hidden" name="prefferedDocId" id="prefferedDocId" value="<?php echo $existsDoctorId; ?>">
                  <input type="hidden" name="refferedDocName" id="refferedDocName" value="<?php echo $existsDocName; ?>">


                  <!-- Header Row -->
                  <div class="row justify-content-between text-left my-0 py-0">
                    <div class="form-group col-sm-2 flex-column my-0 py-0 d-flex">
                      <p class="my-0 py-0">SL. No. </p>
                    </div>
                    <div class="form-group col-sm-3 flex-column mb-0 mt-0 d-flex">
                      <p class="my-0 py-0 ">Description</p>
                    </div>
                    <div class="form-group col-sm-2 flex-column mb-0 mt-0 d-flex">
                      <p class="my-0 py-0 ">Price ₹</p>
                    </div>
                    <div class="form-group col-sm-2 flex-column mb-0 mt-0 d-flex">
                      <p class="my-0 py-0 ">Disc %</p>
                    </div>
                    <div class="form-group col-sm-2 flex-column my-0 py-0 d-flex">
                      <p class="my-0 py-0 text-end">Amount</p>
                    </div>
                    <div class="form-group col-sm-1 flex-column my-0 py-0 d-flex">
                      <p class="my-0 py-0 text-end"></p>
                    </div>
                  </div>
                  <!--/END Header Row -->
                  <hr>
                  <!-- Test List Row -->
                  <div id="lists">
                    <?php $count = 0; ?>
                    <?php
                    $subTests = $LabBillDetails->testsNum($billId);
                    foreach ($subTests as $rowsubTests) {

                      $subTestId = $rowsubTests['test_id'];
                      $subTest = $SubTests->showSubTestsId($rowsubTests['test_id']);
                      // print_r($subTestName);

                      $subTestPrice = $rowsubTests['test_price'];
                      $disc         = $rowsubTests['percentage_of_discount_on_test'];
                      $afterDisc    = $rowsubTests['price_after_discount'];

                      $count++;
                      echo "
                                        <div id='box-id-" . $count . "' class='row justify-content-between text-left my-0 py-0'>
                                            <div class='form-group col-sm-2 flex-column my-0 py-0 d-flex'>
                                            <p class='my-0 py-0'>" . $count . "</p>
                                        </div>
                                        <div class='form-group col-sm-3 flex-column mb-0 mt-0 d-flex'>
                                            <p class='my-0 py-0 '>" . $subTest[0][1] . "</p>
                                            <input type='text' name='testId[]' value='" . $subTestId . "' hidden>
                                        </div>
                                        <div class='form-group col-sm-2 flex-column my-0 py-0 d-flex'>
                                            <p class='my-0 py-0 '>" . $subTestPrice . "</p>
                                            <input type='text' name='priceOfTest[]' value='" . $subTestPrice . "' hidden>
                                        </div>
                                        <div class='form-group col-sm-2 flex-column mb-0 mt-0 d-flex'>
                                            <p class='my-0 py-0 '>" . $disc . "</p>
                                            <input type='text' name='disc[]' value='" . $disc . "' hidden>
                                        </div>
                                        <div class='form-group col-sm-2 flex-column mb-0 mt-0 d-flex'>
                                            <p class='my-0 py-0 text-end'>" . $afterDisc . " </p>
                                            <input type='text' name='amountOfTest[]' value='" . $afterDisc . "' hidden>
                                        </div>
                                        <div class='form-group col-sm-1 flex-column my-0 py-0 d-flex'>
                                            <a class='my-0 py-0 text-end' onClick='removeField(" . $count . "," . $afterDisc . ")'>
                                                <i class='far fa-trash-alt'></i>
                                            </a>
                                        </div>
                                        </div>
                                        ";
                    }
                    ?>
                    <input type="text" id="dynamic-id" value="<?php echo (int)$count; ?>" hidden>

                    <!-- Items are shown here by jquery -->
                  </div>
                  <!--/END Test List Row -->

                  <hr>
                  <div class="row justify-content-between text-left">
                    <div class="form-group col-sm-6 flex-column d-flex">
                      <p class="mb-1">Total: </p>
                    </div>
                    <div class="form-group col-sm-5 flex-column d-flex ">
                      <input type="number" name="total" id="total-test-price" value="<?php echo floatval($labBill['total_amount']); ?>" hidden>
                      <p class="mb-1 text-end">₹ <span id="total-view"><?php echo floatval($labBill['total_amount']); ?></span></p>
                    </div>
                    <div class="form-group col-sm-1 flex-column d-flex">
                      <p class="mb-1 text-end"> </p>
                    </div>
                  </div>

                  <!-- ################################################## -->
                  <div class="row justify-content-between text-left calculation">
                    <div class="form-group col-sm-9 flex-column d-flex">
                      <p class="mb-1">Payable: </p>
                    </div>
                    <div class="form-group col-sm-3 flex-column d-flex ">
                      <input class="myForm text-center" id="payable" name="payable" onkeyup="getLessAmount(this.value)" type="number" value="<?php echo  floatval($labBill['total_after_discount']); ?>" required>
                    </div>

                  </div>
                  <!-- ################################################## -->

                  <div onload="disabledField();" class="row justify-content-between text-left">
                    <div class="form-group col-sm-3 flex-column d-flex">
                      <label class="form-control-label" for="">Update</label>
                      <select class="form-control" onchange="updateBill(this.value)" name="status" id="update" required>
                        <option value="" disabled selected>Select Update</option>
                        <option value="Completed">Completed</option>
                        <option value="Partial Due">Partial Due</option>
                        <option value="Credit">Credit</option>
                      </select>

                      <!-- <span style="color:red;">*Update Status </span> -->

                    </div>
                    <div class="form-group col-sm-3 flex-column d-flex ">
                      <label class="form-control-label" for="">Due Amount</label>
                      <input class="myForm text-center" name="due" id="due" type="number" onkeyup="dueAmount(this.value)" required readonly>
                    </div>
                    <div class="form-group col-sm-3 flex-column d-flex">
                      <label class="form-control-label" for="less-amount">Less Amount</label>
                      <input class="myForm text-center" id="less-amount" name="less_amount" type="any" value="<?php echo floatval($labBill['discount']); ?>" readonly>
                    </div>
                    <div class="form-group col-sm-3 flex-column d-flex">
                      <label class="form-control-label" for="">Paid Amount</label>
                      <input class="myForm text-center" name="paid_amount" id="paid-amount" type="number" onkeyup="paidAmount(this.value)" required readonly>
                    </div>
                  </div>

                  <div class="row justify-content-end">
                    <button class="btn btn-primary w-25" type="submit" id="bill-generate" name="bill-generate" disabled>Update Bill</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!--/End Part 1  -->


        </script>
        <!-- Footer -->
        <?php include SUP_ROOT_COMPONENT . 'footer-text.php'; ?>
        <!-- End of Footer -->

        <!-- Bootstrap core JavaScript-->
        <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
        <script src="<?php echo CSS_PATH ?>bootstrap-js-5/bootstrap.js"></script>


        <!-- Core plugin JavaScript-->
        <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="<?php echo CSS_PATH ?>sb-admin-2.min.js"></script>
        <!-- <script src="../js/custom/lab-billing.js"></script> -->
        <script>
          /* ##############################################
This Javascript Page is only For Lab Billing Page
###############################################*/

          //fetching doctor name  using ajax
          function getDoc() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET", "ajax/billingDoc.ajax.php?doctor_id=" + document.getElementById("docList").value, false);

            xmlhttp.send(null);
            document.getElementById("preferedDoc").innerHTML = xmlhttp.responseText;
            var doc = document.getElementById("docList").value;


            let testDropDown = document.getElementById("test");
            let addBillBtn = document.getElementById("add-bill-btn");


            if (doc == "Self") {
              document.getElementById("preferedDoc").innerHTML = doc;
            }

            if (doc == "") {
              document.getElementById("prefferedDocId").value = "";
              document.getElementById("docName").removeAttribute("disabled", true);
              testDropDown.disabled = true;
              // alert(doc);
              if (testDropDown.value == 'Select Test' || doc == '') {
                addBillBtn.disabled = true;
              } else {
                addBillBtn.disabled = false;
              }

            } else {
              document.getElementById("prefferedDocId").value = doc;
              document.getElementById("docName").setAttribute("disabled", true);
              testDropDown.disabled = false;
              // alert(doc);
              if (testDropDown.value == 'Select Test' || doc == '') {
                addBillBtn.disabled = true;
              } else {
                addBillBtn.disabled = false;
              }

            }
            document.getElementById("bill-generate").disabled = false;

          }

          // action for entering new doctor name
          function newDoctor(value) {
            let testDropDown = document.getElementById("test");
            if (value == "") {
              // alert("Null");
              document.getElementById("refferedDocName").value = "";
              document.getElementById("preferedDoc").innerHTML = "";

              document.getElementById("docList").removeAttribute("disabled", true);
              testDropDown.disabled = true;
              document.getElementById("add-bill-btn").disabled = true;

            } else {
              // alert("Not Null");
              document.getElementById("preferedDoc").innerHTML = "Dr. " + value;
              document.getElementById("refferedDocName").value = "Dr. " + value;
              document.getElementById("docList").setAttribute("disabled", true);
              testDropDown.disabled = false;

              document.getElementById("add-bill-btn").disabled = false;


              // alert(value);
            }
          }

          //fetching test price price using ajax
          function getPrice() {
            //Geeting Price of the selected test
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open(
              "GET",
              "ajax/billingTestPrice.ajax.php?subtest_id=" +
              document.getElementById("test").value,
              false
            );
            xmlhttp.send(null);
            document.getElementById("price").innerHTML = xmlhttp.responseText;

            let price = parseFloat(xmlhttp.responseText);
            let disc = document.getElementById("disc").value;
            let total = price - (disc / 100) * price;

            document.getElementById("total").innerHTML = total;

            //Geeting Name of the selected test into a input field
            var xmlhttpName = new XMLHttpRequest();
            xmlhttpName.open(
              "GET",
              "ajax/billingTestName.ajax.php?subtest_id=" +
              document.getElementById("test").value,
              false
            );
            xmlhttpName.send(null);
            document.getElementById("test-name").value = xmlhttpName.responseText;

            //Removing disabled attribute from quantity and add bil button after selecting a test name
            // document.getElementById("qty").removeAttribute("disabled");
            document.getElementById("disc").removeAttribute("disabled");

            var btn = document.getElementById("add-bill-btn");
            btn.removeAttribute("disabled");

            //Geeting id of the selected test into a input field
            var test_id = document.getElementById("test").value;
            document.getElementById("test-id").value = test_id;
          }

          //geeting bills by clicking on add button
          function getBill() {
            var testName = document.getElementById("test-name").value;
            var testId = document.getElementById("test-id").value;
            var testPrice = document.getElementById("price").innerHTML;
            var disc = document.getElementById("disc").value;
            if (disc == "") {
              disc = '00';
            }
            var total = parseFloat(document.getElementById("total").innerHTML);

            //dynamic id generation
            var count = document.getElementById("dynamic-id").value;
            count++;
            document.getElementById("dynamic-id").value = count;
            // alert(count);

            jQuery("#lists").append(
              '<div id="box-id-' +
              count +
              '" class="row justify-content-between text-left my-0 py-0"><div class="form-group col-sm-2 flex-column my-0 py-0 d-flex"><p class="my-0 py-0">' +
              count +
              '</p></div><div class="form-group col-sm-3 flex-column mb-0 mt-0 d-flex"><p class="my-0 py-0 ">' +
              testName +
              '</p><input type="text" name="testId[]" value="' +
              testId +
              '" hidden></div><div class="form-group col-sm-2 flex-column my-0 py-0 d-flex"><p class="my-0 py-0 ">' +
              testPrice +
              '</p><input type="text" name="priceOfTest[]" value="' +
              testPrice +
              '" hidden></div><div class="form-group col-sm-2 flex-column mb-0 mt-0 d-flex"><p class="my-0 py-0 ">' +
              disc +
              '</p><input type="text" name="disc[]" value="' +
              disc +
              '" hidden></div><div class="form-group col-sm-2 flex-column mb-0 mt-0 d-flex"><p class="my-0 py-0 text-end">' +
              total +
              '</p><input type="text" name="amountOfTest[]" value="' +
              total +
              '" hidden></div><div class="form-group col-sm-1 flex-column my-0 py-0 d-flex"><a class="my-0 py-0 text-end" onClick="removeField(' +
              count +
              "," +
              total +
              ')"><i class="far fa-trash-alt"></i></a></div></div>'
            );

            //calculating total tests price
            var payable = parseFloat(document.getElementById("payable").value);
            var totalPValue = parseFloat(document.getElementById("total-test-price").value);
            // alert(totalPValue);

            payable = payable + total;
            document.getElementById("payable").value = payable;

            totalPValue = totalPValue + total;
            let totalView = (document.getElementById("total-test-price").value =
              totalPValue);
            document.getElementById("total-view").innerHTML = totalView;

            //update status
            let update = document.getElementById("update").value;
            if (update == "Completed") {
              let payable = document.getElementById("payable").value;
              document.getElementById("paid-amount").value = payable;
              document.getElementById("due").value = 0;
            }

            if (update == "Credit") {
              let payable = document.getElementById("payable").value;
              document.getElementById("due").value = payable;
              document.getElementById("paid-amount").value = '00';
            }
            document.getElementById("bill-generate").disabled = false;
          }

          function removeField(count, total) {
            jQuery("#box-id-" + count).remove();
            count--;
            document.getElementById("dynamic-id").value = count;

            let totalP = document.getElementById("total-test-price").value - total;
            let payable = document.getElementById("payable").value - total;

            let totalView = document.getElementById("total-test-price").value = totalP;
            document.getElementById("total-view").innerHTML = totalView;

            let getPayable = document.getElementById("payable").value = payable;

            //update status
            let update = document.getElementById("update").value;
            if (update == "Completed") {
              document.getElementById("paid-amount").value = getPayable;
              document.getElementById("due").value = '00';
            } else if (update == "Credit") {
              document.getElementById("due").value = getPayable;
              document.getElementById("paid-amount").value = '00';

            } else {
              document.getElementById("paid-amount").value = '';
              document.getElementById("due").value = '';
            }

            //update field if no test avilable
            if (totalP == "" || totalP <= 0) {
              document.getElementById("payable").value = '';
              document.getElementById("due").value = '';
              document.getElementById("paid-amount").value = '';
              document.getElementById("less-amount").value = '';
              document.getElementById("bill-generate").disabled = true;
            }
            document.getElementById("bill-generate").disabled = false;

          }

          //changes after changing on discount
          getDisc = (value) => {
            let disc = value;
            let price = document.getElementById("price").innerHTML;
            // let qty = document.getElementById("qty").value;
            // let total = price*qty;
            let total = price - (disc / 100) * price;
            document.getElementById("total").innerHTML = total;
          };

          getLessAmount = (payable) => {
            let totalAmount = parseFloat(document.getElementById("total-test-price").value);
            let lessAmount = parseFloat(document.getElementById("less-amount").value);
            payable = parseFloat(payable);
            // alert(totalAmount);
            // alert(payable);
            // if (payable <= totalAmount){
            //   alert('payable is less or equal');
            // }else{
            //   alert('payable is grater or not equal');
            // }
            if (payable < totalAmount || payable == totalAmount) {
              lessAmount = totalAmount - payable;
              document.getElementById("less-amount").value = lessAmount;

              //update status
              let update = document.getElementById("update").value;
              if (update == "Completed") {
                let payable = document.getElementById("payable").value;
                document.getElementById("paid-amount").value = payable;
              } else if (update == "Partial Due") {
                document.getElementById("due").value = '';
                document.getElementById("paid-amount").value = '';
              } else {
                document.getElementById("due").value = '';
                document.getElementById("paid-amount").value = '';
              }

            } else if (payable > totalAmount) {
              alert("Entered Value is Greterthan Total.");
              document.getElementById("payable").value = totalAmount;
              document.getElementById("less-amount").value = "";
            } else {
              document.getElementById("less-amount").value = "";
            }
          };

          updateBill = (value) => {
            // alert(update);
            if (value == "Completed") {
              let payable = parseFloat(document.getElementById("payable").value);
              document.getElementById("paid-amount").value = payable;
              document.getElementById("paid-amount").setAttribute("readonly", true);

              document.getElementById("due").value = "00";
              // document.getElementById("less-amount").value = "00";
              document.getElementById("due").setAttribute("readonly", true);
            }

            if (value == "Credit") {
              let payable = document.getElementById("payable").value;
              document.getElementById("due").value = payable;
              document.getElementById("due").setAttribute("readonly", true);;

              document.getElementById("paid-amount").value = "00";
              document.getElementById("paid-amount").setAttribute("readonly", true);;
            }

            if (value == "Partial Due") {
              // let payable = document.getElementById("payable").value;
              document.getElementById("due").value = "00";
              document.getElementById("due").removeAttribute("readonly", true);

              let paidField = document.getElementById("paid-amount");
              paidField.value = "";
              // paidField.readonly = false;
              paidField.removeAttribute("readonly", true);
              paidField.focus();
            }
            document.getElementById("bill-generate").disabled = false;

          };

          const dueAmount = (dueAmount) => {
            let payable = parseFloat(document.getElementById("payable").value);
            let paidAmount = parseFloat(document.getElementById("paid-amount").value = 0);
            dueAmount = parseFloat(dueAmount);

            if (dueAmount < payable || dueAmount == payable) {
              document.getElementById("paid-amount").value = payable - dueAmount;
            } else if (dueAmount > payable) {
              alert("Due Amount can not be more than Payable Amount");
              paidAmount.value = '';
              document.getElementById("due").value = "";

            } else {
              alert("Can not be blank");
              paidAmount.value = '';
              document.getElementById("due").value = "";
            }

          }

          const paidAmount = (paidAmount) => {
            let payable = parseFloat(document.getElementById("payable").value);
            let due = document.getElementById("due");
            paidAmount = parseFloat(paidAmount);

            if (paidAmount <= payable) {
              // let dueAmount = payable - paidAmount;

              due.value = payable - paidAmount;
            } else if (paidAmount > payable) {
              alert("Paid Amount can not be more than Payable Amount");
              document.getElementById("paid-amount").value = '';
              due.value = '';
            } else {
              document.getElementById("paid-amount").value = '';
              due.value = '';
            }
          };
        </script>

</body>

</html>