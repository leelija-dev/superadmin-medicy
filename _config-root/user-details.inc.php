<?php
if($_SESSION['ADMIN'] == true){
    require_once CLASS_DIR.'admin.class.php';
    $Admin      = new Admin;

}else {
    require_once CLASS_DIR.'employee.class.php';
    $Employees  = new Employees;
    
    $user = $Employees->selectEmpByCol('emp_id', $employeeId);
}


if($_SESSION['ADMIN'] == true || $_SESSION['ADMIN'] == false){
    require_once CLASS_DIR . 'rbacController.class.php';
    $RBAC              = new RbacController;

    $permissionArray = [];
    $permissonControlData = json_decode($RBAC->selectRBACDetailsByAdminEmployee($ADMINID, $EMPID));
    if($permissonControlData->status){
        $permissionDataArray[] = explode(",", $permissonControlData->data->permission_id);
        foreach($permissionDataArray as $permission){
            array_push($permissionArray, $permission);
        }
        $permissionArray = call_user_func_array('array_merge', $permissionArray);
    }else{
        $permissionArray = [];
    }
}

?>

