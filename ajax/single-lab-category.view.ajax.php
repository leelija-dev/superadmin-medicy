<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/user-details.inc.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'UtilityFiles.class.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';




if (isset($_GET['categoryId'])) {
    $showLabtypeId = $_GET['categoryId'];
    $Pathology       = new Pathology;

    $labCat = json_decode($Pathology->showLabCat($showLabtypeId));
    // print_r($labCat);
            $catImage  = $labCat->data->image;
            $catName   = $labCat->data->name;
            $catDsc    = $labCat->data->dsc;
            $catStatus = $labCat->data->status;

            if (!empty($catImage)) {
                $catImage = LABTEST_IMG_PATH .  $catImage;
            } else {
                $catImage = LABTEST_IMG_PATH . 'default-lab-test/labtest.svg';
            }

    //Fetching Sub Tests data
    $allTests = json_decode($Pathology->showTestByCat($showLabtypeId));
    if ($allTests->status) {
        $allTests = $allTests->data;
    } else {
        $allTests = [];
    }
}

?>

<div class="">
    <div class="d-flex justify-content-between ">
        <div class="border border-2 w-50 p-2 mr-1">
            <div class="w-75 mx-auto">
                <img class="img-fluid" style="min-width: 250px;" src="<?= $catImage ?>" alt="Lab Type Image">
            </div>
        </div>
        <div class="w-50 ml-1">
            <div class="d-flex justify-content-between align-items-start">
                <h3><?= $catName ?></h3>
                <span class="badge <?= $catStatus == 1 ? 'status-active px-3' : 'status-inactive px-3' ?>">
                    <?= $catStatus == 1 ? 'Active' : 'Inactive' ?>
                </span>
            </div>
            <hr class="mb-3 mt-0">
            <div class="">
                <div>
                    <p><b>Total Test : </b> <?= $allTests != 0 ? count($allTests) : '0'; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <?= $catDsc ?>
    </div>

</div>

