<?php
if($_SESSION['SUPER_ADMIN'] == true){
    require_once CLASS_DIR.'admin.class.php';
    $Admin      = new Admin;

}else {
    require_once CLASS_DIR.'employee.class.php';
    $Employees  = new Employees;
    
    $user = $Employees->selectEmpByCol('emp_id', $employeeId);
}
?>

