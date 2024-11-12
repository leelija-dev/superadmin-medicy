<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'/config/sessionCheck.php';//check admin loggedin or not

require_once '../php_control/hospital.class.php';
require_once '../php_control/doctors.class.php';
require_once '../php_control/appoinments.class.php';
require_once '../php_control/patients.class.php';
require_once '../php_control/sub-test.class.php';
require_once '../php_control/labAppointments.class.php';

// $patientId      = $_GET['patientId'];
// $testDate       = $_GET['testDate'];
// $patientName    = $_GET['patientName'];

//Classes Initilized
$appointments    = new Appointments();
$Patients        = new Patients();
$LabAppointments = new LabAppointments();
$SubTests        = new SubTests();
$Doctors         = new Doctors();

//Function Initilized
$showDoctors = $Doctors->showDoctors();
$showSubTests   = $SubTests->showSubTests();


  //Creating Object of Appointments Class
  $appointments = new Appointments();


  if (isset($_POST['update-lab-visit'])) {
    $patientId          = $_POST["patientId"];
    $testDate           = $_POST["testDate"];
    $patientName        = $_POST["patientName"];
    $patientGurdianName = $_POST["patientGurdianName"];
    $patientEmail       = $_POST["patientEmail"];
    $patientPhoneNumber = $_POST["patientPhoneNumber"];
    $patientAge         = $_POST["patientAge"];
    $patientWeight      = $_POST["patientWeight"];
    $gender             = $_POST["gender"];
    $patientAddress1    = $_POST["patientAddress1"];
    $patientAddress2    = $_POST["patientAddress2"];
    $patientPS          = $_POST["patientPS"];
    $patientDist        = $_POST["patientDist"];
    $patientPIN         = $_POST["patientPIN"];
    $patientState       = $_POST["patientState"];

  }elseif (isset($_POST['submit'])) {
      $testDate           = $_POST["testDate"];
      $patientName        = $_POST["patientName"];
      $patientGurdianName = $_POST["patientGurdianName"];
      $patientEmail       = $_POST["patientEmail"];
      $patientPhoneNumber = $_POST["patientPhoneNumber"];
      $patientAge         = $_POST["patientAge"];
      $patientWeight      = $_POST["patientWeight"];
      $gender             = $_POST["gender"];
      $patientAddress1    = $_POST["patientAddress1"];
      $patientAddress2    = $_POST["patientAddress2"];
      $patientPS          = $_POST["patientPS"];
      $patientDist        = $_POST["patientDist"];
      $patientPIN         = $_POST["patientPIN"];
      $patientState       = $_POST["patientState"];
  
  
          //Patient Id Generate
          $prand      = rand(100000000, 999999999);
          $patientId  = 'PE'.$prand;
        //   echo $patientId;
  
          $labVisited = '';
          // Inserting Into Patients Database
        //   $addPatients = $Patients->addLabPatients( $patientId, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $labVisited);
        //     if ($addPatients) {
        //         echo '<script>alert("Patient Details Added!");</script>';
        //     }else{
        //         echo "<script>alert('Patient Not Inserted, Something is Wrong!');</script>";
        //     }
  }else {
    header("Location: lab-entry.php");
    exit;
}



?>

<!doctype html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.css">
    <!-- <link rel="stylesheet" href="../css/patient-style.css"> -->
    <link rel="stylesheet" href="../css/custom/custom-form-style.css">


    <link rel="stylesheet" href="../css/font-awesome.css">
    <script src="../js/bootstrap-js-5/bootstrap.js"></script>
    <title>Lab Test Bill Generate - Medicy Health Care</title>


    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom/appointment.css">


</head>

<body>

    <!-- Page Wrapper -->

    <div id="wrapper">

        <!-- sidebar -->
        <?php include PORTAL_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include PORTAL_COMPONENT.'topbar.php'; ?>
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
                                                <p class="text-start"><b><?php echo $patientName; ?> </b></p>
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
                                                <p class="text-start"><b><span id="preferedDoc"> </span></b></p>
                                            </div>

                                        </div>

                                    </div>
                                </div>


                                <div class="row justify-content-between text-left">
                                    <div class="form-group col-sm-12 flex-column d-flex my-0">
                                        <label class="form-control-label" for="patientDoctor">Rreffered By</label>
                                        <select id="docList" class="form-control" name="patientDoctor"
                                            onChange="getDoc()" required>
                                            <option value="">Select</option>
                                            <option value="Self">By Self</option>

                                            <?php
                                                foreach ($showDoctors as $showDoctorDetails) {
                                                    $doctorId = $showDoctorDetails['doctor_id'];
                                                    $doctorName = $showDoctorDetails['doctor_name'];
                                                    echo'<option value="'.$doctorId.'">'. $doctorName.'</option>';
                                                }
                                                ?>
                                        </select>
                                    </div>

                                    <div class="justify-content-center text-center">
                                        or
                                    </div>

                                    <div class="form-group col-sm-12 flex-column d-flex mt-0">
                                        <input type="text" id="docName" class="form-control"
                                            placeholder="Enter Doctor Name" onkeyup="newDoctor(this.value);">
                                    </div>
                                </div>

                                <div class="row justify-content-between text-left">
                                    <div class="form-group col-sm-12 flex-column d-flex mt-0">
                                        <input type="text" id="test-name" hidden>
                                        <input type="text" id="test-id" hidden>
                                        <select id="test" class="form-control" name="test" onChange="getPrice()"
                                            required>
                                            <option disabled selected>Select Test</option>
                                            <?php
                                                foreach ($showSubTests as $rowSubTests) {
                                                    $subTestId   = $rowSubTests['id'];
                                                    $subTestName = $rowSubTests['sub_test_name'];
                                                    echo'<option value='.$subTestId.'>'. $subTestName.'</option>';
                                                }
                                                ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-between text-left">
                                    <div class="form-group col-sm-5 flex-column d-flex mt-0">
                                        <p class="form-control">Price ₹<span id="price">0</span>/Test</p>
                                    </div>
                                    <!-- <div class="form-group col-sm-3 flex-column d-flex mt-0">
                                        <input class="form-control" id="qty" onkeyup="getQty(this.value);" value="1" type="number"  disabled>
                                    </div> -->
                                    <div class="form-group col-sm-5 flex-column d-flex mt-0">
                                        <input class="form-control" id="disc" onkeyup="getDisc(this.value);"
                                            placeholder="Discount %" type="number" disabled>
                                    </div>
                                </div>
                                <div class="row justify-content-between text-left">
                                    <div class="form-group col-sm-6 flex-column d-flex mt-0">
                                        <p class="form-control">Total ₹ <span id="total"></span></p>

                                    </div>
                                    <div class="form-group col-sm-5 flex-column d-flex mt-0">
                                        <button class="btn btn-primary" id="add-bill-btn" type="button" style="background-color: #a1a8cd;"
                                            onClick="getBill()" disabled>Add to Bill <i
                                                class="fa fa-arrow-right"></i></button>
                                    </div>
                                </div>


                            </div>
                        </div>


                        <div class="col-xl-7 col-lg-7 col-md-7 text-center">
                            <div class="card shadow p-4 mt-0">
                                <form class="form-card" action="tests-bill-invoice2.php" method="post">
                                    <input type="hidden" name="patientId" value="<?php echo $patientId; ?>">
                                    <input type="hidden" name="patientName" value="<?php echo $patientName; ?>">

                                    <input type="hidden" name="patientAge" value="<?php echo $patientAge; ?>">
                                    <input type="hidden" name="patientGender" value="<?php echo $gender; ?>">
                                    <input type="hidden" name="patientPhnNo" value="<?php echo $patientPhoneNumber; ?>">
                                    <input type="hidden" name="patientTestDate" value="<?php echo $testDate;?>">
                                    <input type="hidden" name="prefferedDocId" id="prefferedDocId">
                                    <input type="hidden" name="refferedDocName" id="refferedDocName">


                                    <!-- Header Row -->
                                    <div class="row justify-content-between text-left my-0 py-0">
                                        <div class="form-group col-sm-2 flex-column my-0 py-0 d-flex">
                                            <p class="my-0 py-0">SL. No. </p>
                                        </div>
                                        <div class="form-group col-sm-3 flex-column mb-0 mt-0 d-flex">
                                            <p class="my-0 py-0 ">Description</p>
                                        </div>
                                        <div class="form-group col-sm-2 flex-column mb-0 mt-0 d-flex">
                                            <p class="my-0 py-0 ">₹Price</p>
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
                                        <!-- Items are shown here by jquery -->
                                    </div>
                                    <input type="text" id="dynamic-id" value="0" hidden>
                                    <!--/END Test List Row -->

                                    <hr>
                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <p class="mb-1">Total: </p>
                                        </div>
                                        <div class="form-group col-sm-5 flex-column d-flex ">
                                            <p class="mb-1 text-end">₹ <span id="total-p"></span></p>
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
                                            <!-- <input class="myForm" type="text"> -->
                                            <!-- <div class=" d-flex"> -->
                                                <!-- <p class="me-1 mt-3 text-end">₹</p> -->
                                                <input type="hidden" name="total" id="total-p-value" value="00.00">
                                                <input class=" myForm text-end" style="padding: 0;" type="any"
                                                    name="paid_admount" id="paid-amount" value="00.00">
                                            <!-- </div> -->
                                        </div>
                                        
                                    </div>
                                    <!-- ################################################## -->

                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-5 flex-column d-flex">
                                            <p class="mb-1">Payable: </p>
                                        </div>
                                        <div class="form-group col-sm-2 flex-column d-flex ">
                                            <div class=" d-flex">
                                                <p class="me-1 mt-3 text-end">₹</p>
                                                <input type="hidden" name="total" id="total-p-value" value="00.00">
                                                <input class="mb-1 text-end" style="padding: 0;" type="any"
                                                    name="paid_admount" id="paid-amount" value="00.00">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-1 flex-column d-flex">
                                            <p class="mb-1 text-end"> </p>
                                        </div>
                                    </div>
                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-3 flex-column d-flex">
                                            <label class="form-control-label" for="">Update</label>
                                            <select class="form-control" name="" id="">
                                                <option value="">Credit</option>
                                                <option value="">Due</option>
                                                <option value="">Pending</option>
                                                <option value="">Pending</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3 flex-column d-flex ">
                                            <label class="form-control-label" for="">Due Amount</label>
                                            <input class="myForm" type="text">
                                        </div>
                                        <div class="form-group col-sm-3 flex-column d-flex">
                                            <label class="form-control-label" for="">Less Amount</label>
                                            <input class="myForm" type="text">
                                        </div>
                                        <div class="form-group col-sm-3 flex-column d-flex">
                                            <label class="form-control-label" for="">Payable</label>
                                            <input class="myForm" type="text">
                                        </div>
                                    </div>

                                    <div class="row justify-content-end">
                                        <!-- <div class="form-group col-sm-5"> -->
                                            <button class="btn btn-primary w-25" type="submit" name="bill-generate" >Generate Bill</button>
                                        <!-- </div> -->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!--/End Part 1  -->


                </script>
                <!-- Footer -->
                <?php include PORTAL_COMPONENT.'footer-text.php'; ?>
                <!-- End of Footer -->

                <!-- Bootstrap core JavaScript-->
                <script src="vendor/jquery/jquery.min.js"></script>
                <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

                <!-- Core plugin JavaScript-->
                <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

                <!-- Custom scripts for all pages-->
                <script src="js/sb-admin-2.min.js"></script>

                <!-- Page level plugins -->
                <!-- <script src="vendor/chart.js/Chart.min.js"></script> -->

                <!-- Page level custom scripts -->
                <!-- <script src="js/demo/chart-area-demo.js"></script> -->
                <!-- <script src="js/demo/chart-pie-demo.js"></script> -->

                <script type="text/javascript">
                var todayDate = new Date();

                var date = todayDate.getDate();
                var month = todayDate.getMonth() + 1;
                var year = todayDate.getFullYear();

                if (date < 10) {
                    date = '0' + date;
                }
                if (month < 10) {
                    month = '0' + month;
                }
                var todayFullDate = year + "-" + month + "-" + date;
                console.log(todayFullDate);
                document.getElementById("appointmentDate").setAttribute("min", todayFullDate);

                //fetching doctor name  using ajax
                function getDoc() {
                    // alert('Working');
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "ajax/billingDoc.ajax.php?doctor_id=" + document.getElementById("docList")
                        .value, false);
                    xmlhttp.send(null);
                    document.getElementById("preferedDoc").innerHTML = xmlhttp.responseText;
                    var doc = document.getElementById("docList").value;


                    if (doc == 'Self') {
                        document.getElementById("preferedDoc").innerHTML = doc;
                    }



                    if (doc == '') {
                        // alert("NULL");
                        document.getElementById("prefferedDocId").value = "";
                        document.getElementById("docName").removeAttribute("disabled", true);

                    } else {
                        document.getElementById("prefferedDocId").value = doc;
                        document.getElementById("docName").setAttribute("disabled", true);
                        // alert(doc);

                    }
                }

                // action for entering new doctor name
                function newDoctor(value) {
                    if (value == '') {
                        // alert("Null");
                        document.getElementById("refferedDocName").value = "";
                        document.getElementById("docList").removeAttribute("disabled", true);
                    } else {
                        // alert("Not Null");
                        document.getElementById("preferedDoc").innerHTML = "Dr. " + value;
                        document.getElementById("refferedDocName").value = "Dr. " + value;
                        document.getElementById("docList").setAttribute("disabled", true);
                        // alert(value);
                    }
                }

                //fetching test price price using ajax
                function getPrice() {
                    //Geeting Price of the selected test
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "ajax/billingTestPrice.ajax.php?subtest_id=" + document.getElementById("test")
                        .value, false);
                    xmlhttp.send(null);
                    document.getElementById("price").innerHTML = xmlhttp.responseText;

                    let price = parseFloat(xmlhttp.responseText);
                    let disc = document.getElementById("disc").value;
                    let total = price - (disc / 100 * price);

                    document.getElementById("total").innerHTML = total;

                    //Geeting Name of the selected test into a input field
                    var xmlhttpName = new XMLHttpRequest();
                    xmlhttpName.open("GET", "ajax/billingTestName.ajax.php?subtest_id=" + document.getElementById(
                        "test").value, false);
                    xmlhttpName.send(null);
                    document.getElementById("test-name").value = xmlhttpName.responseText;

                    //Removing disabled attribute from quantity and add bil button after selecting a test name
                    // document.getElementById("qty").removeAttribute("disabled");
                    document.getElementById("disc").removeAttribute("disabled");

                    var btn = document.getElementById("add-bill-btn");
                    btn.removeAttribute("disabled");
                    btn.removeAttribute("style");

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
                    if (disc == '') {
                        disc = 00;
                    }
                    var total = parseFloat(document.getElementById("total").innerHTML);

                    //dynamic id generation
                    var count = document.getElementById("dynamic-id").value;
                    count++;
                    document.getElementById("dynamic-id").value = count;
                    // alert(count);

                    jQuery("#lists").append('<div id="box-id-' + count +
                        '" class="row justify-content-between text-left my-0 py-0"><div class="form-group col-sm-2 flex-column my-0 py-0 d-flex"><p class="my-0 py-0">' +
                        count +
                        '</p></div><div class="form-group col-sm-3 flex-column mb-0 mt-0 d-flex"><p class="my-0 py-0 ">' +
                        testName + '</p><input type="text" name="testId[]" value="' + testId +
                        '" hidden></div><div class="form-group col-sm-2 flex-column my-0 py-0 d-flex"><p class="my-0 py-0 ">' +
                        testPrice + '</p><input type="text" name="priceOfTest[]" value="' + testPrice +
                        '" hidden></div><div class="form-group col-sm-2 flex-column mb-0 mt-0 d-flex"><p class="my-0 py-0 ">' +
                        disc + '</p><input type="text" name="disc[]" value="' + disc +
                        '" hidden></div><div class="form-group col-sm-2 flex-column mb-0 mt-0 d-flex"><p class="my-0 py-0 text-end">' +
                        total + '</p><input type="text" name="amountOfTest[]" value="' + total +
                        '" hidden></div><div class="form-group col-sm-1 flex-column my-0 py-0 d-flex"><a class="my-0 py-0 text-end" onClick="removeField(' +
                        count+','+total +')"><i class="far fa-trash-alt"></i></a></div></div>');

                    //calculating total tests price
                    var totalp = parseFloat(document.getElementById("total-p-value").value);

                    totalp = totalp + total;
                    let totalPValue = document.getElementById("total-p-value").value = totalp;
                    document.getElementById("total-p").innerHTML = totalPValue;
                    document.getElementById("paid-amount").value = totalPValue;

                }

                function removeField(count, total) {
                    // alert(count);
                    // alert(total);
                    jQuery("#box-id-" + count).remove();
                    count--;
                    document.getElementById("dynamic-id").value = count;

                    let totalP = document.getElementById("total-p").innerHTML - total;
                    let payable = document.getElementById("paid-amount").value - total;
                    // alert(totalP);
                    // alert(payable);
                    document.getElementById("total-p").innerHTML = totalP;
                    document.getElementById("paid-amount").value = payable;
                }
                </script>
                <script>
                //changes after changing on discount
                getDisc = (value) => {
                    let disc = value;
                    let price = document.getElementById("price").innerHTML;
                    // let qty = document.getElementById("qty").value;
                    // let total = price*qty;
                    let total = price - (disc / 100 * price);
                    document.getElementById("total").innerHTML = total;
                }
                </script>

</body>

</html>