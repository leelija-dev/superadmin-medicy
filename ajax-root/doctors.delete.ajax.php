<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'doctors.class.php';


$deleteDocId = $_POST['id'];
// $deleteDocId = 5418;

$doctors = new Doctors();
$docDelete = $doctors->deleteDoc($deleteDocId);

if ($docDelete) {
    echo 1;
}else {
    echo 0;
}


?>