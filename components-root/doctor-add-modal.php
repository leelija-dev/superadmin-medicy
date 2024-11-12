
<div class="row justify-content-between text-left">

    <div class="col-sm-6">
        <div class="form-group ">

            <input type="text" class="med-input" id="doc-name" placeholder="" autocomplete="off" required>
            <label for="doc-name" class="med-label">Doctor Name:<span class="text-danger small">*</span></label>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            <input type="text" class="med-input" id="doc-reg-no" placeholder="" autocomplete="off" required>
            <label for="doc-reg-no" class="med-label">Reg. No:<span class="text-danger small">*</span></label>
        </div>
    </div>

    <div class="col-sm-6 mt-2">
        <div class="form-group">
            <input type="text" name="doc-speclz" id="doc-speclz" class="med-input" placeholder="" autocomplete="off"
                required>
            <label for="doc-speclz" class="med-label">Specialization: <span class="text-danger small">*</span></label>
            <input type="text" name="doc-speclz-id" id="doc-speclz-id" class="form-control" autocomplete="off" hidden>

            <div class="p-2 bg-light col-md-6 c-dropdown w-auto" id="doc-specialization-list">
                <div class="lists" id="lists">
                    <?php if (!empty($docSplzList)) : ?>
                    <?php foreach ($docSplzList as $docSplzList) { ?>
                    <div class="p-1 border-bottom list" id="<?= $docSplzList['doctor_category_id'] ?>"
                        onclick="setDocSpecialization(this)">
                        <?= $docSplzList['category_name'] ?>
                    </div>
                    <?php } ?>

                    <div class="d-flex flex-column justify-content-center mt-1" onclick="addDocSpecialization()">
                        <button type="button" id="add-specialization" class="text-primary border-0">
                            <i class="fas fa-plus-circle"></i> Add Now</button>
                    </div>
                    <?php else : ?>
                    <p class="text-center font-weight-bold mb-1">Not Found!</p>
                    <div class="d-flex flex-column justify-content-center" onclick="addDocSpecialization()">
                        <button type="button" id="add-specialization" class="text-primary border-0">
                            <i class="fas fa-plus-circle"></i>Add Now</button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>


    <div class="col-sm-6 mt-2">
        <div class="form-group ">
            <input type="text" class="med-input" id="doc-degree" placeholder="" autocomplete="off" required>
            <label for="doc-degree" class="med-label">Degree:<span class="text-danger small">*</span></label>
        </div>
    </div>

    <div class="col-sm-6 mt-2">
        <div class="form-group">
            <input type="email" class="med-input" id="email" placeholder="" autocomplete="off">
            <label for="doc-email" class="med-label">Email:</label>
        </div>
    </div>

    <div class="col-sm-6 mt-2">
        <div class="form-group">
            <input type="text" class="med-input" id="doc-phno" placeholder="" autocomplete="off" maxlength="10"
                pattern="\d{10}">
            <label for="doc-phno" class="med-label">Contact
                Number:</label>
        </div>
    </div>

    <div class="col-sm-6 mt-2">
        <div class="form-group">
            <textarea class="med-input" id="doc-address" rows="1" autocomplete="off" placeholder=""></textarea>
            <label for="doc-address" class="med-label">Address:</label>
        </div>
    </div>

    <div class="col-sm-6 mt-2">
        <div class="form-group">
            <input type="text" class="med-input" id="doc-with" placeholder="" autocomplete="off">
            <label for="doc-with" class="med-label">Doctor Also With:</label>
        </div>
    </div>

    <div class="col-12 text-center py-3">
        <button type="button" class="btn btn-sm btn-primary" onclick="addDocDetails()"
            style="width: 200px;">Add</button>
    </div>

</div>
