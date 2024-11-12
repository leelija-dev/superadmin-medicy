<?php
// Check if a specific session variable exists to determine if the user is logged in
if (!isset($_SESSION['LOGGEDIN'])) {
    header("Location: ".URL."login.php");
    exit;
}

if($_SESSION['ADMIN']){
  // echo 'true';
  $userEmail    = $_SESSION['ADMIN_EMAIL'];
  $adminContact = $_SESSION['ADMIN_CONTACT_NO'];
  $userType     = $_SESSION['USER_TYPE'];
  $userRole     = $_SESSION['USER_ROLE'];
  $adminName    = $_SESSION['ADMIN_FNAME'];
  // $userFname    = $_SESSION['ADMIN_FNAME'];
  $adminLname   = $_SESSION['ADMIN_LNAME'];
  $userImg      = $_SESSION['ADMIN_IMG'] ;
  $adminAddress = $_SESSION['ADMIN_ADDRESS'];
  $username     = $_SESSION['ADMIN_USERNAME'];
  $adminPass    = $_SESSION['ADMIN_PASSWORD'];
  $adminId      = $_SESSION['ADMINID'];
  $employeeId   = $_SESSION['ADMINID'];
  $addedBy      = $adminId;

  $USEREMAIL    = $_SESSION['ADMIN_EMAIL'];
  $ADMINCONTACT = $_SESSION['ADMIN_CONTACT_NO'];
  $USERTYPE     = $_SESSION['USER_TYPE'];
  $USERROLE     = $_SESSION['USER_ROLE'];
  $USERFNAME    = $_SESSION['ADMIN_FNAME'];
  $USERLNAME    = $_SESSION['ADMIN_LNAME'];
  $USERIMG      = $_SESSION['ADMIN_IMG'] ;
  $ADMINADDRESS = $_SESSION['ADMIN_ADDRESS'];
  $USERNAME     = $_SESSION['ADMIN_USERNAME'];
  $ADMINID      = $_SESSION['ADMINID'];
  $EMPID        = '';
  $UPDATEDBY    = $adminId;
  $ADDEDBY      = $adminId;

}else{
  // echo 'false';
  $userEmail      = $_SESSION['EMP_EMAIL'] ;
  $empContact     = $_SESSION['EMP_CONTACT_NO'];
  $userType       = $_SESSION['USER_TYPE'];
  $userRole       = $_SESSION['EMP_ROLE'];
  // $userFname      = $_SESSION['EMP_NAME'];
  $userImg        = $_SESSION['EMP_IMG']; 
  $empAddress     = $_SESSION['EMP_ADDRESS'];
  $username       = $_SESSION['EMP_USERNAME'];
  $empPass        = $_SESSION['EMP_PASSWORD'];
  $employeeId     = $_SESSION['EMPID'];
  $adminId        = $_SESSION['ADMIN_ID'];
  $addedBy        = $employeeId;

  $USEREMAIL      = $_SESSION['EMP_EMAIL'] ;
  $EMPCONTACT     = $_SESSION['EMP_CONTACT_NO'];
  $USERTYPE       = $_SESSION['USER_TYPE'];
  $USERROLE       = $_SESSION['EMP_ROLE'];
  $USERFNAME      = $_SESSION['EMP_FNAME'];
  $USERLNAME      = $_SESSION['EMP_LNAME'];
  $USERIMG        = $_SESSION['EMP_IMG']; 
  $EMPADDRESS     = $_SESSION['EMP_ADDRESS'];
  $USERNAME       = $_SESSION['EMP_USERNAME'];
  // $EMPempPass    = $_SESSION['EMP_PASSWORD'];
  $EMPID          = $_SESSION['EMPID'];
  $ADMINID        = $_SESSION['ADMIN_ID'];
  $UPDATEDBY      = $employeeId;
  $ADDEDBY        = $employeeId;
}
