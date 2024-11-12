<?php
require_once realpath(dirname(dirname(__DIR__))) . '/config/constant.php';

if (isset($_POST['urlData'])){
    $url = $_POST['urlData'];
}
?>

<div class="card-body pt-0">
    <form method="post" action="<?= ADM_URL ?>ajax/distributor.add.ajax.php">

        <div class="col-md-12">
            <label class="mb-0 mt-1" for="distributor-name">Distributor Name</label>
            <input class="form-control" id="distributor-name" name="distributor-name" placeholder="Distributor Name" maxlength="155" required>
        </div>

        <div class="col-md-12">
            <label class="mb-0 mt-1" for="distributor-phno">Mobile Number</label>
            <input type="number" class="form-control" id="distributor-phno" name="distributor-phno" placeholder="Distributor Mobile Number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="10" oninput="javascript: if (this.value.length > this.minLength) this.value = this.value.slice(0, this.minLength);" minlength="10" required>
        </div>

        <div class="col-md-12">
            <label class="mb-0 mt-1" for="distributor-phno">GST ID</label>
            <input type="text" class="form-control" id="distributor-gstid" name="distributor-gstid" placeholder="Distributor GST ID" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="18" oninput="javascript: if (this.value.length > this.minLength) this.value = this.value.slice(0, this.minLength);" minlength="18" required>
        </div>

        <div class="col-md-12">
            <label class="mb-0 mt-1" for="distributor-email">Email Address</label>
            <input type="email" class="form-control" id="distributor-email" name="distributor-email" placeholder="Distributor Email Address" maxlength="50">
        </div>

        <div class="col-md-12">
            <label class="mb-0 mt-1" for="distributor-area-pin">Area PIN Code</label>
            <input type="number" class="form-control" id="distributor-area-pin" name="distributor-area-pin" placeholder="Distributor Area PIN Code" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" oninput="javascript: if (this.value.length > this.minLength) this.value = this.value.slice(0, this.minLength);" minlength="6">
        </div>

        <div class="col-md-12">
            <label class="mb-0 mt-1" for="distributor-address">Address</label>
            <textarea name="distributor-address" id="distributor-address" class="form-control" cols="30" rows="3" maxlength="255"></textarea>
        </div>

        <div class="col-md-12">
            <label class="mb-0 mt-1" for="distributor-dsc">Description</label>
            <textarea name="distributor-dsc" id="distributor-dsc" class="form-control" cols="30" rows="3" maxlength="355"></textarea>
        </div>

        <div class="d-none col-md-12">
            <input type="text" class="form-control" id="parent-window-location" name="parent-window-location"  maxlength="100" value="<?php echo $url ?>">
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
            <button class="btn btn-primary me-md-2" name="add-distributor" type="submit">Add
                Distributor</button>
        </div>
    </form>

</div>
<!-- /end Add Distributor  -->