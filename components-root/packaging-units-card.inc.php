<div class="col-sm-6 mt-4">
    <div class="card bg-gradient-primary shadow-sm card-hover">
        <div class="card-body mb-0 pb-0">
            <div class="d-flex justify-content-between">
                <h5 class="card-title text-white">Packaging Unit</h5>
                <a class="btn btn-sm text-white bg-transparent" data-bs-toggle="modal" href="#PackUnitModal" role="button" onclick="findPackUnit('all')"> Find</a>
            </div>
            <div class="d-flex justify-content-between">
                <img src="<?= IMG_PATH . 'packUnit.png' ?>" class="ml-0" style="width: 80px; height: 60px; opacity: 0.5;" alt="">
                <h4 class=" text-white" style="margin-right: 50%;"><?= $countPackagingUnits ?></h4>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button class="btn btn-sm text-white bg-transparent mt-n2 mr-3 mb-2" data-toggle="modal" data-target="#add-packagingUnit">
                Add new
            </button>
        </div>
    </div>
</div>

<!-- Packaging unit search modal -->
<div class="modal fade" id="PackUnitModal" aria-hidden="true" aria-labelledby="exampleModalToggle" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Search Packaging Unit: &nbsp;</h5>
                <div class="input-group w-50">
                    <input id="packSearchInput" type="search" class="form-control form-control-sm" placeholder="Search by name" aria-label="Search">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-sm btn-success" onclick="packSearch('all')"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body PackUnitModal">

            </div>
        </div>
    </div>
</div>
<!-- Packaging unit search modal end -->


<!-- add packaging unit -->
<div class="modal fade" id="add-packagingUnit" tabindex="-1" role="dialog" aria-labelledby="packUnitModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="packUnitModalLabel">Add Packaging Unit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body add-packagingUnit">
                <form method="post" action="ajax/packagingUnit.add.ajax.php">

                    <div class="col-md-12">
                        <label class="mb-0 mt-1" for="unit-name">Unit Name</Address></label>
                        <input class="form-control" id="unit-name" name="uni-name" placeholder="Unit Name" required>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                        <button class="btn btn-primary me-md-2" name="add-unit" type="submit">Add
                            Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end packaging unit -->