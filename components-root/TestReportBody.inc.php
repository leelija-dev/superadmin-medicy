<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'PathologyReport.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

$Pathology          = new Pathology;
$PathologyReport    = new PathologyReport;
$LabBillDetails     = new LabBillDetails;


if (isset($_POST['testId']) && isset($_POST['billId'])) {
    $responseTestId = $_POST['testId'];
    $responseBillId = $_POST['billId'];
}

if ($responseTestId != '') :
    // Check if a comma exists in the string
    if (strpos($responseTestId, ',') !== false) {
        // Comma exists, explode the string into an array
        $testIdArray = explode(',', $responseTestId);
    } else {
        $testIdArray[] = $responseTestId;
    }

    $arrCount = count($testIdArray);
    $count = 0;

    if ($arrCount > 0) :
        foreach ($testIdArray as $eachTestId) {
            $checkReportFormatType = json_decode($PathologyReport->getReportFormatByIdAndTestId($responseBillId, $eachTestId));
            if($checkReportFormatType->status){
                if ($checkReportFormatType->data->report_format_type == 2) {
                    $existingTests[] = $eachTestId;
                } else {
                    $reportDetails = $PathologyReport->getReportParamsByBill($responseBillId);
                    if (!empty($reportDetails)) {
                        foreach ($reportDetails as $eachParam) {
                            // print_r($eachParam);
                            $details = json_decode($Pathology->showTestByParameter($eachParam));
                            // print_r($details);
                            if ($details->status) {
                                $existingTests[] = $details->data->test_id;
                            }
                        }
                        $existingTests = array_unique($existingTests);
                    } else {
                        $existingTests = [];
                    }
                }
            }else{
                // echo 'no body found';
                $existingTests = [];
            }
            // print_r($existingTests);


            if (in_array($eachTestId, $existingTests)) {

?>
                <div class="bg-light text-center border border-2 border-dashed py-5">
                    <h4 class="pb-0 font-weight-bold text-danger">Already exists!</h4>
                    <a title="show" onclick="window.open(this.href, '_blank', 'width=800,height=800'); return false;" href="invoices/lab-invoice.php?billId=<?= url_enc($responseBillId) ?>">View Report</a>

                </div>
                <?php
            } else {
                $showTestName   = json_decode($Pathology->showTestById($eachTestId));
                $testId         = $showTestName->data->id;
                $subTestName    = $showTestName->data->name;
                $reportType     = $showTestName->data->report_type;
                $textformatLH   = $showTestName->data->report_text_format;

                if ($reportType === 2) {
                    echo '<div class="border rounded mt-5 mb-4 py-2" data-med-paramid="' . $testId . '">
                        <h4 class="text-center py-3"><u> Report of ' . $subTestName . '</u></h4>
                        <input type="hidden" name="textTestId[]" value="' . $testId . '">
                        <div class="border-1p border-primary min-h-100 mt-2 p-1" style="min-height:200px">
                          <textarea class="form-control auto-resize-textarea" name="textField[]" placeholder="Enter text here" rows="12">' . $textformatLH . '</textarea>
                        </div>
                    </div>';
                } else {

                    $parameters = json_decode($Pathology->showParametersByTest($eachTestId));
                    if ($parameters->status) {
                        $parameters = $parameters->data;
                ?>
                        <div class='border rounded mt-5 mb-4 py-2' data-med-paramid="<?= $testId ?>">
                            <h4 class="text-center py-3"><u> Report of <?= $subTestName ?></u></h4>

                            <?php
                            // Generate input boxes based on the count of unit values
                            foreach ($parameters as $eachParameter) {
                                // echo $eachParameter->id;
                                // head data fetch
                                $headFlag = 0;
                                $headData = json_decode($Pathology->showHeadByParameterId($eachParameter->id));
                                if ($headData->status) {
                                    $headFlag = 1;
                                    $headDetails = $headData->data;
                                } else {
                                    $headDetails = [];
                                }

                                // test standered data range fetch
                                $rangeData = json_decode($Pathology->showRangeByParameter($eachParameter->id));
                                // print_r($rangeData);
                                if ($rangeData->status) {
                                    $standardRangId = $rangeData->data->id;
                                } else {
                                    $standardRangId = '';
                                }
                            ?>
                                <div class='d-flex justify-content-between px-3' id="parameter">
                                    <div class="w-50">
                                        <p><?= $eachParameter->name ?></p>
                                        <input type='hidden' name='params[]' value="<?= $eachParameter->id ?>" required>
                                    </div>
                                    <div class="w-50">
                                        <div class='d-flex justify-content-start align-items-baseline'>
                                            <?php
                                            if ($headFlag == 1) {
                                                foreach ($headDetails as $headDetailsData) {

                                                    echo "<input type='text' class='lab-val-inp col' name='values[" . $eachParameter->id . "][]' required>&nbsp&nbsp&nbsp";
                                                    echo "<input type='hidden' name='headId[$eachParameter->id][]' value='$headDetailsData->id' required>";
                                                    echo "<input type='hidden' name='rangeId[$eachParameter->id][]' value='$standardRangId' required>";
                                                    echo "<input type='hidden' name='testId[]' value='" . $testId . "'>";
                                                }
                                            } else {
                                                echo "<input type='hidden' name='rangeId[$eachParameter->id][]' value='$standardRangId' required>";
                                                echo "<input type='hidden' name='headId[$eachParameter->id][]' value='' required>";
                                                echo "<input type='text' class='lab-val-inp col' name='values[" . $eachParameter->id . "][]' required>";
                                                echo "<input type='hidden' name='testId[]' value='" . $testId . "'>";
                                            }
                                            ?>
                                            <span class="col"><?= $eachParameter->unit ?></span>
                                            <span class="col cursor-pointer text-danger" onclick="toggleParameter(this)"><i class="far fa-eye-slash"></i></span>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="border-1p border-primary border-dashed min-h-100 mt-5 py-5">
                            <h5 class="text-center text-danger"> Parameter Not Avilable for <?= $subTestName ?> Test</h5>
                            <div class="pt-3 text-center">
                                <a href="tel:7699753019" class="btn btn-sm btn-success">Request to Add</a>
                                <a href="<?= URL . 'ticket-query-generator.php' ?>" class="btn btn-sm btn-primary">Contact Now</a>
                            </div>
                        </div>
<?php
                    }
                }
            }
        }
    endif;

else :
    echo '
        <div class="border-1p border-muted border-dashed bg-light text-secondary min-h-100 mt-5 py-5">
            <h5 class="text-center"> Select a Test from Dropdown to Generate Report</h5>
        </div>';
endif;
