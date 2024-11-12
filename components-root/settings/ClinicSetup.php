<!-- New Section -->
<div class="col">
    <div class="mt-4 mb-4">

        <div class="card-body rpadding">
            <?php if (isset($_GET['setup']) && isset($_GET['flag']) && $_GET['flag'] == '1') : ?>
                <div class="alert alert-success" role="alert">
                    <?= $_GET['setup'] ?>
                </div>
            <?php elseif (isset($_GET['setup']) && isset($_GET['flag']) && $_GET['flag'] == '0') :  ?>
                <div class="alert alert-warning" role="alert">
                    <?= $_GET['setup'] ?>
                </div>
            <?php endif; ?>
            <form class="needs-validation" novalidate action="<?= PAGE ?>" method="post" enctype="multipart/form-data">

                <div class="row ">
                    <div class="col-md-6  rpadding">
                        <div class="alert alert-danger d-none" id="err-show" role="alert">
                            Only jpg/jpeg and png files are allowed!
                        </div>
                        <div class="  ml-2 settingslogo  d-res  " >
                            <img class="mb-0 mt-3 rounded img-uv-view border border-primary " src="<?= $healthCareLogoPathName ?>" style=" min-height:180px;  " alt="">
                            <input class="med-input d-none" type="text" name="helthcare-logo-name" id="helthcare-logo-name" value="<?= $healthCareLogo; ?>">
                            <div class="">
                                <input type="file" style="display:none;" id="img-uv-input" accept=".jpg,.jpeg,.png" name="site-logo" onchange="validateFileType()">
                                <label for="img-uv-input" class="btn btn-primary">Change
                                    Logo</label>
                            </div>
                        </div>
                        <div class="form-group col-md-12 mt-5">
                            <input class="med-input" type="text" name="helthcare-name" id="helthcare-name" value="<?= $healthCareName; ?>" placeholder="" required>
                            <label class="med-label" style="left:20px" for="helthcare-name">Organization/Health Care
                                Name <span class="text-danger font-weight-bold">*</span></label>
                                <div class="invalid-feedback">Please enter the healthcare name.</div>
                        </div>

                        <div class="form-group col-md-12">
                            <input class="med-input" type="text" maxlength="10" name="helpline-no" id="helpline-no" value="<?= $healthCarePhno; ?>" placeholder="" required pattern="\d{10}">
                            <label class="med-label" style="left:20px" for="helpline-no">Help Line Number <span class="text-danger font-weight-bold">*</span></Address>
                            </label>
                            <div class="invalid-feedback">Please enter a valid 10-digit phone number.</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input class="med-input" type="text" maxlength="10" name="apnt-booking-no" id="apnt-booking-no" value="<?= $healthCareApntbkNo; ?>" placeholder="" required pattern="\d{10}">
                            <label class="med-label" style="left:20px" for="apnt-booking-no">Appointment Help
                                Line <span class="text-danger font-weight-bold">*</span></label>
                                <div class="invalid-feedback">Please enter a valid 10-digit appointment number.</div>
                        </div>

                        <div class="form-group col-md-12">
                            <input class="med-input" type="email" name="email" id="email" value="<?= $healthCareEmail; ?>" placeholder="">
                            <label class="med-label" style="left:20px" for="email">Health Care Email</label>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>

                    </div>

                    <div class="col-md-6  rpadding" style="padding-top:5.5rem;">
                        <div class="form-group col-sm-12">

                            <input class="med-input" type="text" maxlength="50" name="address-1" id="address-1" value="<?= $healthCareAddress1; ?>" placeholder="" required>
                            <label class="med-label" style="left:20px" for="address-1">Address 1 <span class="text-danger font-weight-bold">*</span></label>
                            <div class="invalid-feedback">Please enter Address 1.</div>
                            <!-- <textarea class="med-input" type="text" maxlength="50" name="address-1" id="address-1" value="" placeholder="" required><?= $healthCareAddress1; ?></textarea>
                            <label class="med-label" style="left:20px" for="address-1">Address 1 <span class="text-danger font-weight-bold">*</span></label> -->
                        </div>

                        <div class="form-group col-md-12">
                            <input class="med-input" type="text" maxlength="50" name="address-2" id="address-2" value="<?= $healthCareAddress2; ?>" placeholder="">
                            <label class="med-label" style="left:20px" for="address-2">Address 2</label>

                            <!-- <textarea class="med-input" type="text" maxlength="50" name="address-2" id="address-2" value="" placeholder=""><?= $healthCareAddress2; ?></textarea>
                            <label class="med-label" style="left:20px" for="address-2">Address 2</label> -->
                        </div>
                        <div class="form-group col-md-12">
                            <input class="med-input" type="text" maxlength="50" name="city" id="city" value="<?php echo $healthCareCity; ?>" placeholder="" required>
                            <label class="med-label" style="left:20px" for="city">City <span class="text-danger font-weight-bold">*</span></label>
                            <div class="invalid-feedback">Please enter a city</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input class="med-input" type="text" maxlength="50" name="dist" id="dist" value="<?php echo $healthCareDist; ?>" placeholder="" required>
                            <label class="med-label" style="left:20px" for="dist">Dist <span class="text-danger font-weight-bold">*</span></label>
                            <div class="invalid-feedback">Please enter a district</div>
                        </div>
                        <div class="form-group col-md-12">
                            <select class="med-input" name="state" id="state" required>
                                <?php echo '<option value="' . $healthCareState . '">' . $healthCareState . '</option>'; ?>
                                <option value="West Bengal">West Bengal</option>
                                <option value="Others">Others</option>
                            </select>
                            <label class="med-label" style="left:20px" for="state">Select State <span class="text-danger font-weight-bold">*</span></label>
                            <div class="invalid-feedback">Please select a state.</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input class="med-input" type="text" maxlength="6" minlength="6" name="pin" id="pin" value="<?php echo $healthCarePin; ?>" placeholder="" required pattern="\d{6}">
                            <label class="med-label" style="left:20px" for="pin">PIN <span class="text-danger font-weight-bold">*</span></label>
                            <div class="invalid-feedback">Please enter a valid 6-digit PIN.</div>
                        </div>

                        <div class="form-group col-md-12">
                            <select class="med-input" name="country" id="country" required>
                                <option value="India">India</option>
                                <option value="Others">Others</option>
                            </select>
                            <label class="med-label" style="left:20px" for="country">Country <span class="text-danger font-weight-bold">*</span></label>
                            <div class="invalid-feedback">Please select a country.</div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2 me-md-2">
                    <button class="btn btn-success me-md-2" name="update" type="submit">Update</button>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
    // function validateFileType() {
    //     var fileName = document.getElementById("img-uv-input").value;
    //     console.log(fileName);
    //     var idxDot = fileName.lastIndexOf(".") + 1;
    //     var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
    //     if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
    //         document.getElementById("err-show").classList.add("d-none");
    //     } else {
    //         document.getElementById("err-show").classList.remove("d-none");
    //         // Show current image when error occurs
    //         document.querySelector('.img-uv-view').src = "<?= $healthCareLogo; ?>";
    //     }
    // }

    (() => {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()
</script>