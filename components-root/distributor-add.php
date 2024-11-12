<?php require_once dirname(__DIR__) . '/config/constant.php'; ?>

<div class="card-body pt-0">
    <form method="post" action="<?= URL ?>ajax/distributor.add.ajax.php">
        <div class="col-sm-12 mt-2">
            <div class="form-group">
                <input type="text" class=" med-input" id="distributor-name" name="distributor-name" placeholder="" autocomplete="off" maxlength="155" required>
                <label class=" med-label" for="distributor-name">Distributor Name<span class="form-asterisk"></span></label>

            </div>
        </div>

        <div class="col-sm-12 mt-2">
            <div class="form-group">
                <input type="number" class=" med-input" id="distributor-phno" name="distributor-phno" placeholder="" autocomplete="off" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="10" oninput="javascript: if (this.value.length > this.minLength) this.value = this.value.slice(0, this.minLength);" minlength="10" required>
                <label class=" med-label" for="distributor-phno">Mobile Number<span class="form-asterisk"></span></label>
                <p id="pMsg" style="color: red;"></p>
            </div>
        </div>

        <div class="col-sm-12 mt-2">
            <div class="form-group">
                <input type="text" class=" med-input" id="distributor-gstid" name="distributor-gstid" placeholder="" autocomplete="off" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="15" oninput="javascript: if (this.value.length > this.minLength) this.value = this.value.slice(0, this.minLength);" minlength="15" required>
                <label class=" med-label" for="distributor-gstid">GST ID<span class="form-asterisk"></span></label>

            </div>
        </div>

        <div class="col-sm-12 mt-2">
            <div class="form-group">
                <input type="email" class=" med-input" id="distributor-email" name="distributor-email" placeholder="" autocomplete="off" required>
                <label class=" med-label" for="distributor-email">Email Address<span class="form-asterisk"></span></label>

            </div>
        </div>


        <div class="col-sm-12 mt-2">
            <div class="form-group">
                <input type="number" class=" med-input" id="distributor-area-pin" name="distributor-area-pin" placeholder="" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" oninput="javascript: if (this.value.length > this.minLength) this.value = this.value.slice(0, this.minLength);" minlength="6">
                <label class=" med-label" for="distributor-area-pin">Area PIN Code<span class="form-asterisk"></span></label>

            </div>
        </div>




        <div class="col-sm-12 mt-2">
            <div class="form-group">
                <textarea class="med-input " data-gramm="false" name="distributor-address" placeholder="" id="distributor-address" cols="30" rows="3" maxlength="255" required></textarea>
                <label class=" med-label" for="distributor-address">Address<span class="form-asterisk"></span></label>
            </div>
        </div>



        <div class="col-sm-12 mt-2">
            <div class="form-group">
                <textarea class="med-input distributor-dsc" data-gramm="false" name="distributor-dsc" placeholder="" id="distributor-dsc" rows="3" maxlength="355"></textarea>
                <label class=" med-label" for="distributor-dsc">Description</label>
            </div>
        </div>





        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
            <button class="btn btn-primary me-md-2" name="distributor-data-add" type="submit">Add
                Distributor</button>
        </div>
    </form>

</div>
<!-- /end Add Distributor  -->