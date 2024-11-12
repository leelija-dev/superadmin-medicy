<?php

if (!isset($_SESSION['SUPERADMINLOGGEDIN'])) {
  header("Location: " . URL . "login-superAdmin.php");
  exit;
}


if ($_SESSION['SUPER_ADMIN']) {
  $supAdminEmail    = $_SESSION['SUPER_ADMIN_EMAIL'];
  $supAdminContact  = $_SESSION['SUPER_ADMIN_CONTACT_NO'];
  $userType         = $_SESSION['USER_TYPE'];
  $userRole         = $_SESSION['USER_ROLE'];
  $supAdminFname    = $_SESSION['SUPER_ADMIN_FNAME'];
  $supAdminLname    = $_SESSION['SUPER_ADMIN_LNAME'];
  $supAdminImg      = $_SESSION['SUPER_ADMIN_IMG'];
  $supAdminAddress  = $_SESSION['SUPER_ADMIN_ADDRESS'];
  $supAdminusername = $_SESSION['SUPER_ADMIN_USERNAME'];
  // $supAdminPass     = $_SESSION['SUPER_ADMIN_PASSWORD'];
  $supAdminId       = $_SESSION['SUPER_ADMINID'];
  $employeeId       = '';
  $addedBy          = $supAdminId;
  

  $SUPER_ADMIN_EMAIL        = $_SESSION['SUPER_ADMIN_EMAIL'];
  $SUPER_ADMIN_CONTACT_NO   = $_SESSION['SUPER_ADMIN_CONTACT_NO'];
  $USER_TYPE                = $_SESSION['USER_TYPE'];
  $USER_ROLE                = $_SESSION['USER_ROLE'];
  $SUPER_ADMIN_FNAME        = $_SESSION['SUPER_ADMIN_FNAME'];
  $SUPER_ADMIN_LNAME        = $_SESSION['SUPER_ADMIN_LNAME'];
  $SUPER_ADMIN_IMG          = $_SESSION['SUPER_ADMIN_IMG'];
  $SUPER_ADMIN_ADDRESS      = $_SESSION['SUPER_ADMIN_ADDRESS'];
  $SUPER_ADMIN_USERNAME     = $_SESSION['SUPER_ADMIN_USERNAME'];
  $SUPER_ADMINID            = $_SESSION['SUPER_ADMINID'];
}
