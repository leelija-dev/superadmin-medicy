<?php
require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR.'encrypt.inc.php';
require_once ROOT_DIR.'_config/healthcare.inc.php';

require_once CLASS_DIR.'doctors.class.php';
require_once CLASS_DIR.'labBilling.class.php';
require_once CLASS_DIR.'labBillDetails.class.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'idsgeneration.class.php';


//  INSTANTIATING CLASS
$Doctors         = new Doctors();
$Patients        = new Patients();
$LabBilling      = new LabBilling();
$LabBillDetails  = new LabBillDetails();
$IdsGeneration   = new IdsGeneration;

if (isset($_POST['bill-generate'])) {

    $testIds         = $_POST['testId'];        // each test id
    $priceOfTest     = $_POST['priceOfTest'];   // each test
    $testDisc        = $_POST['disc'];          //of % of each test
    $testAmount      = $_POST['amountOfTest'];  // of each test after discount
    $totalAmount     = $_POST['total'];         // of all tests
    $payable         = $_POST['payable'];       //payable by customer
    $paidAmount      = $_POST['paid_amount'];   //paid by customer
    $dueAmount       = $_POST['due'];
    $discountOnTotal = $_POST['less_amount'];
    $status          = $_POST['status'];


    $patientId       = $_POST['patientId'];
    $patientName     = $_POST['patientName'];
    $patientAge      = $_POST['patientAge'];
    $patientGender   = $_POST['patientGender'];
    $patientPhno     = $_POST['patientPhnNo'];
    $testDate        = $_POST['patientTestDate'];
    $docId           = $_POST['prefferedDocId'];
    $referedDocName  = $_POST['refferedDocName'];

    ###################### Patient Visit Update ######################
    $labVisited = $Patients->labVisists($patientId);

    if($labVisited == NULL){
        $labVisited = 1;
    }else{
        $labVisited = $labVisited +1;
    }

    $updateVisit = $Patients->updateLabVisiting($patientId, $labVisited);
    if ($updateVisit) {

        ##################################################################
        ######################### Bill Insertion #########################
        ##################################################################
        $testDiscBck   = $testDisc;
        $testAmountBck = $testAmount;

        ################ Bill id/ invoice id generation #############

        // $billId = $IdsGeneration->generateLabBillId();
        
        ############ End Of Bill ID / Invoice Id Generagtion #########

        ################ Doctor Selection ###############
        $referedDoc = '';
        $doctorName = '';
        $doctorReg  = '';
        if ($docId == 'Self') {
            $referedDoc = $docId;
            $doctorName = 'Self';
            $doctorReg  = NULL;
        }else{
            if ($docId != NULL) {
                //function calling
                $showDoctorById = $Doctors->showDoctorNameById($docId);
                $showDoctorById = json_decode($showDoctorById);
                // print_r($showDoctorById);
                if($showDoctorById->status == 1){
                    foreach($showDoctorById->data as $rowDoctor){
                        $referedDoc = $docId;
                        $doctorName = $rowDoctor->doctor_name;
                        // print_r($doctorName);
                        $doctorReg  = $rowDoctor->doctor_reg_no;
                    }
                } 
            }
        }
        
        if ($referedDocName != NULL) {
            $referedDoc = $referedDocName;
            $doctorName = $referedDocName;
            $doctorReg  = NULL;
        }
        ############# End of Doctor Selection ############


        ############# CGST & SGST Generation #############
        
        $cgst = 0;
        
        $sgst = 0;
        ########## End of CGST & SGST Generation ##########

        $totalAfterDiscount = $payable;

        $addLabBill = $LabBilling->addLabBill(NOW, $patientId, $referedDoc, $testDate, $totalAmount, $discountOnTotal, $totalAfterDiscount, $cgst, $sgst, $paidAmount, $dueAmount, $status, $employeeId, NOW, $adminId);
       
        $addLabBill = json_decode($addLabBill);
        // print_r($addLabBill);
        // exit;

        if ($addLabBill->status) {
            ##################################################################
            ###################### Bill Details Insertion ####################
            ##################################################################
            $billId         = $addLabBill->insertId;
            $testDiscsBck   = $testDisc;
            $testAmountsBck = $testAmount;
            $priceOfTestBck = $priceOfTest;

            foreach ($testIds as $testId) {
                $percentageOfDiscount   = array_shift($testDiscsBck);
                $priceAfterDiscount     = array_shift($testAmountsBck);
                $testPrice              = array_shift($priceOfTestBck);

                $addBillDetails = $LabBillDetails->addLabBillDetails($billId, NOW, $testDate, $testId, $testPrice, $percentageOfDiscount, $priceAfterDiscount);
            }

            $addBillDetails = json_decode($addBillDetails);
            if($addBillDetails->status){
                // $redirectUrl = URL."invoices/lab-invoice.php?billId=" . url_enc($billId);
                $redirectUrl = URL."altered-tests-bill-invoice.php?billId=" . url_enc($billId);
                header("Location: " . $redirectUrl);
                // header("Location: reprint-test-bill.php?billId=".url_enc($billId));
                exit;
            }else{
                echo "<script>alert('Bill details Not added!!, Something is Wrong!');</script>";
                header("Location: lab-patient-selection.php?test=true");
                exit;
            }
        }else{
            echo "<script>alert('Unable to generate lab bill !!, Something is Wrong!');</script>";
            header("Location: lab-patient-selection.php?test=true");
            exit;
        }

    }else{
        echo "<script>alert('Patient Visiting Not Updated!!, Something is Wrong!');</script>";
        header("Location: lab-patient-selection.php?test=true");
        exit;
    } 
}else{
    header("Location: lab-patient-selection.php?test=true&msg=Request Not Found");
    exit;
}