<?php

require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'employee.class.php';

$deleteEmpId = $_POST['id'];

$emp = new Employees();
$empDelete = $emp->deleteEmp($deleteEmpId);
//echo $empDelete.$this->conn->error;exit;

if ($empDelete) {
    echo 1;
}else {
    echo 0;
}

?>
