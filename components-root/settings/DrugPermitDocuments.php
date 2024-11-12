<?php
$flag1 = 0;
$flag2 = 0;
if (!empty($form20Data)) {
    $flag1 = 2;
}

if (!empty($form21Data)) {
    $flag2 = 2;
}
?>

<!-- New Section -->
<input class="d-none" id="drupPermit-form20" type="text" value="<?= $form20Data; ?>">
<input class="d-none" id="drupPermit-form21" type="text" value="<?= $form21Data; ?>">
<input class="d-none" id="drupPermit-file-path" type="text" value="<?= ORGS_DRUG_PERMIT_PATH; ?>">
<div class="col">
    <div class="mt-4 mb-4">
        <div class="card-body">
            <!-- <form id="drugPermitDetails-uploadForm" enctype="multipart/form-data"> -->
            <div class="row">
                <!-- Form 20 Upload Section -->
                <div class="col-sm-6">
                    <div class="card">
                        <input type="text" value="<?= $flag1; ?>" class="d-none" id="validate-form20">
                        <div class="card-body">
                            <div id="imagePreviewForm20" class="image-preview text-gray-700" onclick="triggerFileInput('form-20')" style="z-index: 9999;">
                                <span class="mr-2">Upload Form 20</span>
                                <i class="fas fa-upload"></i>
                            </div>
                            <input id="form-20" type="file" name="form-20" class="d-none" onchange="previewFile(this, 'imagePreviewForm20')" required />
                        </div>
                    </div>
                </div>

                <!-- Form 21 Upload Section -->
                <div class="col-sm-6">
                    <input type="text" value="<?= $flag2; ?>" class="d-none" id="validate-form21">
                    <div class="card">
                        <div class="card-body">
                            <div id="imagePreviewForm21" class="image-preview text-gray-700" onclick="triggerFileInput('form-21')">
                                <span class="mr-2">Upload Form 21</span>
                                <i class="fas fa-upload"></i>
                            </div>
                            <input id="form-21" type="file" name="form-21" class="d-none" onchange="previewFile(this, 'imagePreviewForm21')" required />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <!-- GSTIN Input -->
                <div class="col-md-6">
                    <label for="gstin">Enter Organization GST number</label><span class="ml-2 text-danger" id="gstin-span">*</span>
                    <input type="text" class="form-control mb-3" id="gstin" name="gstin" maxlength="15" value="<?= $gstinData; ?>" placeholder="GSTIN" autocomplete="off" required onblur="validateFields()">
                </div>
                <!-- PAN Input -->
                <div class="col-md-6">
                    <label for="pan">Enter PAN number</label><span class="ml-2 text-danger" id="pan-span">*</span>
                    <input type="text" class="form-control mb-3" id="pan" name="pan" maxlength="10" value="<?= $panData; ?>" placeholder="PAN" autocomplete="off" required onblur="validateFields()">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end mt-2">
                    <button class="btn btn-success" type="submit" onclick="submitDrugFormData()">Update</button>
                </div>
            </div>
            <!-- </form> -->
        </div>

    </div>
</div>