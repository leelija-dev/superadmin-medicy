<?php
// require_once dirname(__DIR__).'/config/constant.php';
require_once realpath(dirname(dirname(__DIR__)). '/config/constant.php');
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'doctor.category.class.php';

$deleteDocCatId = $_POST['id']; 

$DoctorCategory = new DoctorCategory();

$deleteDocCat = $DoctorCategory->deleteDocCat($deleteDocCatId);

if ($deleteDocCat) {
    echo 1;
}else {
    echo 0;
}


?>