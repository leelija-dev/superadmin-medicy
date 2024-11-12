<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'hospital.class.php';


//Classes Initilizing
$Patients        = new Patients();
$IdsGeneration   = new IdsGeneration();

$email      = '';
$district   = '';
$pin        = '';
$state      = '';
$visited    = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['flag'] == 'contact-existence-check') {
        $contact = $_POST['phNo'];
        
        $col = 'phno';
        $checkExistance = json_decode($Patients->chekPatientsDataOnColumn($col, $contact, $ADMINID));
        // print_r($checkExistance);
        if($checkExistance->status){
            echo 1;
        }else{
            echo 0;
        }
    }



    if ($_POST['flag'] == 'add-patient-details') {

        $patientId = $IdsGeneration->patientidGenerate();

        $added = $Patients->addPatients($patientId, $_POST['patientName'], ' ', $email, $_POST['patientPhoneNumber'], ' ', ' ', $_POST['patientAddress1'], ' ', $district, $pin, $state, $visited, $EMPID, NOW, $ADMINID);
        if ($added) {
            echo json_encode(['status' => 1, 'pid' => $patientId]);
        } else {
            echo json_encode(['status' => 0, 'message' => 'Failed to add patient details']);
        }
    }
}
/*
?>

        <script>
            swal({
                title: "Success",
                text: "Customer data added Successfully!",
                icon: "success",
                button: "OK"
            }).then(() => {
                parent.location.reload();
            });
        </script>

    <?php
    } else {
    ?>

        <script>
            swal({
                title: "Failed",
                text: "Customer data cannot be added!",
                icon: "error",
                button: "OK"
            }).then(() => {
                parent.location.reload();
            });
        </script>

<?php
    }
}*/
