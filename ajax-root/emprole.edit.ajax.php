<?php
require_once dirname(__DIR__) . '/config/constant.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'empRole.class.php';

$desigName = $_GET['desigName'];
$designId   = $_GET['designId'];

$designation = new Emproles();
$editDesignation = $designation->editDesign($desigName, $designId);

if ($editDesignation) {
    echo "<div class='alert alert-primary alert-dismissible fade show' role='alert'>
            <strong>Success</strong>Your Designation Role Has been Updateed!
        </div>";
} else {
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <strong>Failed!</strong> Designation Role Not Updated!
        </div>";
}
