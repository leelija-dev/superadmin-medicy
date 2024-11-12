<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';
require_once CLASS_DIR.'dbconnect.php';
require_once SUP_ADM_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR. 'appoinments.class.php';
require_once CLASS_DIR.'doctors.class.php';
require_once CLASS_DIR.'doctor.category.class.php';
require_once CLASS_DIR. 'encrypt.inc.php';
require_once CLASS_DIR. 'admin.class.php';


// Fetching Appointments Info
$customerId = url_dec($_GET['prescription']);
// print_r($customerId);
$adminDetails   = new Admin();
$Appointments   = new Appointments();
$doctors        = new Doctors();

$showCustomer   = $adminDetails->adminDetails($customerId);
$showCustomer   = json_decode($showCustomer,true);
// print_r($showCustomer);
foreach ($showCustomer['data'] as $customer) {
    $customerId    = $customer['admin_id'];
    $customerName  = $customer['username'];
    $customerEmail = $customer['email'];
    $customerCont  = $customer['mobile_no'];
    $customerAddr  = $customer['address'];
}

$showAppointment   = $Appointments->allAppointmentByAdmin($customerId);
$showAppointment   = json_decode($showAppointment,true);
// print_r($showAppointment);


// Fetching Doctor Info
$showDoctorByid = $doctors->showDoctors($customerId);
$showDoctor     = json_decode($showDoctorByid,true);
$totalDoct = 0;
if ($showDoctor && isset($showDoctor['data']) && is_array($showDoctor['data'])) {
    $doctorData = $showDoctor['data'];
    foreach ($doctorData as $doctor) {
        $totalDoct++;
    }
} 
// print_r("count-".$totalDoct);

// for($showDoctor['data'] as $docts){
//     $doctorId  = $docts['doctor_id'];
// }

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>prescription.css">
    <title>Prescription - <?= url_enc($customerId) ?></title>
</head>

<body>
    <div style="box-shadow:none" class="card">
        <div class="hospitslDetails mb-0">
            <div class="row">
                <!-- <div class="col-1 headerHospitalLogo">
                    <img class="mt-4" src="<?= $healthCareLogo ?>" alt="<?= $healthCareName ?>">
                </div> -->
                <div class="col-5 headerHospitalDetails">
                    <h1 class="text-primary text-start fw-bold mb-2 mt-4 me-3"><?= $healthCareName ?></h1>
                    <p class="text-start  me-3">
                        <small><?php echo $healthCareAddress1 . ', ' . $healthCareCity . ', <br>' . $healthCareState . ', ' . $healthCarePin; ?></small>
                    </p>
                </div>
                <div class="col-2 header-doc-img"> <img src="<?=$healthCareLogo ?>" alt="<?= $healthCareName?>"> </div>
                <div class="text-danger  col-5">
                    <h2 class="text-end mt-3  mb-0"><?= $customerName ?></h2>
                    <p class="text-end  mb-0 ">
                        <small> <?= $customerId; ?></small>
                    </p>
                    <p class="text-end  mb-0 ">
                        <small><?= $customerEmail ?></small>
                    </p>
                    <p class="text-end  mb-0"> <?= $customerAddr ?></p>
                    <h6 class="text-end text-primary">
                        <strong>Phone No: <?= $customerCont ?></strong>
                    </h6>

                </div>
            </div>
        </div>
        <hr class="mb-0 mt-0" style="color: #00f;">
        <div>
            <div class="row justify-content-between text-left mt-0">

            </div>
        </div>
        <!-- <hr> -->
        <!-- <div class="space">
        </div> -->
        <!-- <div class="row space mt-1">
            <div class="col-3 border-end " style="border-color: #0000ff59 !important;">
                <small>
                    A-ID: <?php echo $apntId ?>
                    <br>
                    P-ID: <?php echo $patientId ?>
                    <div class="mt-2">
                        BP:
                        <br>
                        WT:
                    </div>
                </small>
                <div class="mt-5">
                    <h6 class="text-center"><u> DIAGNOSIS </u></h6>
                    TC,DC,Hb%,ESR
                    <br>
                    BT,CT
                    <br>
                    BI,Sugar(F. & P.P)
                    <br>
                    GR. & Rh.type
                    <br>
                    VDRL
                    <br>
                    Lipid Profile
                    <br>
                    HIV-I & II
                    <br>
                    HBsAg
                    <br>
                    Urea
                    <br>
                    Creatine
                    <br>
                    TSH,T3,T4
                    <br>
                    Bilirubin
                    <br>
                    M.P.
                    <br>
                    L.F.T
                    <br>
                    Urine (RE/ME/CS)
                    <br>
                    Urine Pregnency
                    <br>
                    X-Ray Chest = PA
                    <br>
                    E.C.G
                    <br>
                    Serum PSA Titre
                    <br>
                    USG-W/A-L/A,FPP

                </div> -->
            </div>
            <div class="col-9">
                <div class="row mt-1">
                    <div class="col-12 d-flex justify-content-between">
                        <!-- <p class="mb-0 mt-0">
                            Name: <?php echo $patientName; ?>
                            <span class="ms-3"> Age: <?php echo $patientDob; ?> </span>
                            <span class="ms-3"> Sex: <?php echo $patientGender; ?> </span>
                        </p>

                        <p class="mb-0 mt-0 text-end">
                            Date:
                            <?php
                            $date = date_create($appointmentDate);
                            echo date_format($date, "d-m-Y");
                            ?>
                        </p> -->
                    </div>
                </div>
                <hr class="row mt-2 m-auto" style="color: #00f;">
            </div>
        </div>
        <div class=" footer ">
            <div class="row border border-primary pt-2 pb-0 d-flex justify-content-between">

                <div class="col-md-4 custom-width-name mb-0">
                    <ul style="margin-bottom: 8px">
                        <li class=" list-unstyled"><img id="healthcare-name-box" class="pe-2"
                                src="<?= IMG_PATH ?>icons/hospital.png" alt="Healt Care"
                                style="width:28px; height:20px;" /><?php echo $healthCareName ?></li>
                    </ul>
                </div>

                <div class="col-md-4 custom-width-email mb-0">
                    <ul style="margin-bottom: 8px">
                        <li class="list-unstyled"><img id="email-box" class="pe-2"
                                src="<?= IMG_PATH ?>icons/email-logo.png" alt="Email"
                                style="width:28px; height:20px;" /><?= $healthCareEmail ?></li>

                    </ul>
                </div>

                <div class="col-md-4 custom-width-number mb-0">
                    <ul style="margin-bottom: 8px">
                        <li class="list-unstyled"><img id="number-box" class="pe-2"
                                src="<?= IMG_PATH ?>icons/call-logo.png" alt="Contact"
                                style="width:28px; height:20px;" /><span><?= $healthCareApntbkNo.', '.$healthCarePhno ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            <p class="text-center text-info"><strong>বিঃ দ্রঃ - যেকোন জরুরি অবস্থায় অনুগ্রহ করে নিকটবর্তি হাসপাতালে
                    যোগাযোগ করুন।</strong></p>
        </div>
        <!-- <div class="row">
        </div> -->
    </div>
    <div class="printButton mb-5">
        <button class="btn btn-primary" onclick="history.back()">Go Back</button>
        <button class="btn btn-primary" onclick="window.print()">Print Prescription</button>
    </div>
</body>

</html>