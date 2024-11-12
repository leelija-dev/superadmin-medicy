<div class="row d-flex justify-content-center">
    <div class="col-xl-12 col-lg-12 col-md-12 text-center">
        <div class="bg-light p-4 pb-0">
            <form class="form-card" id="add-patient-form" method="post">
                <div class="row justify-content-between text-left">
                    <div class="form-group col-sm-6 flex-column d-flex">
                        <label class="form-control-label h6" for="patientName"><i class="fas fa-user" style="color: #8584e9; margin-right: 8px;"></i> Patient Name<span class="text-danger"> *</span></label>
                        <input class="newsalesAdd" type="text" id="patientName" name="patientName" placeholder="Enter Patient Name" value="" required autocomplete="off">
                    </div>

                    <div class="form-group col-sm-6 flex-column d-flex">
                        <label class="form-control-label h6" for="patientPhoneNumber"><i class="fas fa-phone" style="color: #8584e9; margin-right: 8px;"></i>Phone
                            number<span class="text-danger"> *</span></label>
                        <input class="newsalesAdd" type="number" id="patientPhoneNumber" name="patientPhoneNumber" placeholder="Phone Number" maxlength="10" minlength="10" value="" required autocomplete="off" onfocusout="contactValidation(this)">
                    </div>
                </div>

                <!-- <h6 class="text-center mb-4 mt-5">Patient Address</h6> -->
                <div class="row justify-content-between text-left">
                    <div class="form-group col-sm-12 flex-column d-flex">
                        <label class="form-control-label h6" for="patientAddress1"><i class="fas fa-home" style="color: #8584e9; margin-right: 8px;"></i>Address
                            <span class="text-danger"> *</span></label>
                        <input class="newsalesAdd" type="text" id="patientAddress1" name="patientAddress1" placeholder="Address Line " value="" required autocomplete="off">
                    </div>
                </div>
                <div class="row justify-content-center mt-5">
                    <div class="form-group col-sm-4">
                        <button type="button" name="submit" class="btn btn-block btn-primary" onclick="addCustomer()">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>