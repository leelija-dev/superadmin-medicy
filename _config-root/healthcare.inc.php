<?php
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'subscription.class.php';

$HealthCare     = new HealthCare;
$Subscription   = new Subscription;

$healthCare   = json_decode($HealthCare->showHealthCare($ADMINID));
// print_r($healthCare);

// ========================== CHECK SUBSCRIPTION ========================== 
$checkSubscription = $Subscription->checkSubscription($ADMINID, NOW);
if (!$checkSubscription){
    header("Location:".URL."cheakout/plans.php");
    exit;
}


// ========================== CLINIC SETUP ========================== 
if ($healthCare->status === 1 ) {
    $healthCare = $healthCare->data;

    if (!empty($healthCare->hospital_name)){
        $healthCareName = $healthCare->hospital_name;
    }else {
        if (!str_contains(PAGE, 'clinic-setting')) {
            // header('Location: '.URL.'clinic-setup.php?setup=Please complete your Pharmacy/Healthcare setup!');
            header('Location: '.URL.'clinic-setting.php?setup=Please complete your Pharmacy/Healthcare setup!');
        }
    }

    $healthCareLogo      = $healthCare->logo;
    $healthCareLogoPathName      = empty($healthCareLogo) ? ICONS_PATH.'logo-placeholder.png' : URL.$healthCareLogo;
    $HEALTHCARELOGOPATHNAME      = empty($healthCareLogo) ? ICONS_PATH.'logo-placeholder.png' : URL.$healthCareLogo;


    $healthCareId        = $healthCare->hospital_id;
    $healthCareName      = $healthCare->hospital_name;
    $HEALTHCARENAME      = $healthCare->hospital_name;


    $form20Data          = $healthCare->form_20;
    $form21Data          = $healthCare->form_21;
    $gstinData           = $healthCare->gstin;
    $panData             = $healthCare->pan;

    $healthCareAddress1  = $healthCare->address_1;
    $healthCareAddress2  = $healthCare->address_2;
    $healthCareCity      = $healthCare->city;
    $healthCareDist      = $healthCare->dist;
    $healthCarePin       = $healthCare->pin;
    $healthCareState     = $healthCare->health_care_state;
    $healthCareEmail     = $healthCare->hospital_email;
    $healthCarePhno      = $healthCare->hospital_phno;
    $healthCareApntbkNo  = $healthCare->appointment_help_line;
    
}else {
    if (!str_contains(PAGE, 'clinic-setting')) {
        // header('Location: '.URL.'clinic-setup.php?setup=Please complete your Pharmacy/Healthcare setup!');
        header('Location: '.URL.'clinic-setting.php?setup=Please complete your Pharmacy/Healthcare setup!');
    }
}