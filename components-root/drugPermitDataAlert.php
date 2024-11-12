<?php if (empty($form20Data) || empty($form21Data) && (empty($gstinData) || empty($panData))) : ?>
    <div class="mb-2" style="z-index: 999;" id="drug-alert">
        <div class="d-flex flex-column flex-md-row justify-content-end alert alert-warning border fade show" role="alert" id="drugPermitAlertDiv">
            <div class="w-100" id="drugPermitAlertMsgDiv">
                <p class="font-weight-bold mb-0">Please <a class="text-decoration-underline" href="<?= URL ?>clinic-setting.php?tab-control">Upload</a> Drug Permit Documents!</p>
            </div>
            <div class="d-none d-md-block text-right">
                <a href="<?= URL ?>clinic-setting.php?tab-control" class="btn btn-sm btn-outline-primary">
                    <!-- <i class="fas fa-upload"></i> -->
                     Upload
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>