<div class="col-12">
    <form id="stock-in-data">
        <div class="row">
            <div class="col-md-7">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label for="product-name" class="mb-0">Item Name</label>
                        <span class="text-danger">*</span>
                        <input class="upr-inp mt-2" list="datalistOptions" id="product-name" name="product-name" placeholder="Search Product" onkeyup="searchItem(this.value);" autocomplete="off" value="" onkeydown="chekForm()">
                        <input class="upr-inp mt-2 d-none" id="cnf-product-name" name="cnf-product-name" autocomplete="off" value="" readonly>
                        <div class="p-2 bg-light" id="product-select" style="max-height: 25rem; max-width: 100%;">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-md-6 mt-2">
                        <label class="mb-0" for="mrp">MRP/Package</label>
                        <input type="number" class="upr-inp" name="mrp" id="mrp">
                    </div>

                    <div class="col-sm-6 col-md-6 mt-2">
                        <label class="mb-0" for="gst">GST%</label>
                        <span class="text-danger">*</span>
                        <input type="number" class="upr-inp" name="gst-check" id="gst-check" hidden>

                        <select class="upr-inp" name="gst" id="gst" onchange="getBillAmount(this)">
                            <option value="" selected disabled>Select GST%</option>
                            <?php
                            foreach ($gstData as $gstData) {
                                echo '<option value="' . $gstData->percentage . '" >' . $gstData->percentage . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row">

                    <div class="col-sm-6  mt-2">
                        <label class="mb-0" for="batch-no">Batch No.</label>
                        <span class="text-danger">*</span>
                        <input type="text" class="upr-inp" name="batch-no" id="batch-no" style="text-transform: uppercase;">
                    </div>

                    <div class="col-sm-6 mt-2">
                        <label class="mb-0 mt-1" for="exp-date">Expiry Date</label>
                        <span class="text-danger">*</span>
                        <div class="d-flex data-field">
                            <input class="month " type="number" id="exp-month" onkeyup="setExpMonth(this);" onfocusout="setexpMonth(this);">
                            <span class="date-divider">&#47;</span>
                            <input class="year " type="number" id="exp-year" onkeyup="setExpYear(this)" onfocusout="setExpYEAR(this)">
                        </div>
                    </div>
                </div>
                <!--/End Quantity Row  -->
            </div>

            <div class="col-md-5">
                <!-- Price Row -->
                <div class="row mb-2">

                    <div class="col-sm-4 col-md-4 mt-2">
                        <label class="mb-0" for="purchase-price">PTR/Package</label>
                        <span class="text-danger">*</span>
                        <input type="number" class="upr-inp" name="ptr" id="ptr" onfocusout="getBillAmount()">
                    </div>

                    <div class="col-sm-4 col-md-4 mt-2">
                        <label class="mb-0" for="qty">Quantity</label>
                        <span class="text-danger">*</span>
                        <input type="number" class="upr-inp" name="qty" id="qty" onfocusout="getBillAmount()">
                    </div>
                    <div class="col-sm-4 col-md-4 mt-2">
                        <label class="mb-0" for="free-qty">Free</label>
                        <span class="text-danger">*</span>
                        <input type="number" class="upr-inp" name="free-qty" id="free-qty">
                    </div>

                </div>

                <!-- Price Row -->
                <div class="row mb-2">

                    <div class="col-sm-4 col-md-4 mt-2">
                        <label class="mb-0" for="discount">Discount%</label>
                        <span class="text-danger">*</span>
                        <input type="number" class="upr-inp" name="discount" id="discount" placeholder="Discount %" onfocusout="getBillAmount()">
                    </div>

                    <div class="col-sm-4 col-md-4 mt-2">
                        <label class="mb-0" for="dprice">D Price</label>
                        <input type="number" class="upr-inp" name="dprice" id="dprice" readonly>
                    </div>

                    <div class="col-sm-4 col-md-4 mt-2">
                        <label class="mb-0" for="bill-amount">Bill Amount</label>
                        <input type="any" class="upr-inp" name="bill-amount" id="bill-amount" readonly required>
                    </div>

                </div>

                <div class="row  d-flex">
                    <div class="col-9 mt-3 d-flex justify-content-sm-end ">
                        <button type="button" class="btn btn-primary me-md-2" id="add-product-details" onclick="resetData()">Reset
                            <i class="fas fa-undo"></i></button>
                    </div>

                    <div class="col-0 mt-3 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary me-md-2" id="add-product-details" onclick="addData()">Add
                            <i class="fas fa-plus"></i></button>
                    </div>
                </div>

            </div>
            <div class="row mt-2" hidden>
                <div class=" col-md-3 mt-2">
                    <label class="mb-0" for="product-id">Product Id</label>
                    <input class="upr-inp" id="product-id" name="product-id" readonly>
                </div>

                <div class=" col-md-3 mt-2">
                    <label class="mb-0" for="manufacturer-id">Manuf Id</label>
                    <input class="upr-inp" id="manufacturer-id" name="manufacturer-id" readonly>
                </div>
                <div class=" col-md-3 mt-2">
                    <label class="mb-0" for="manufacturer-name">Manuf Name</label>
                    <input class="upr-inp" id="manufacturer-name" name="manufacturer-name" readonly>
                </div>
                <div class=" col-md-3 mt-2">
                    <label class="mb-0" for="medicine-power">Med Power</label>
                    <input class="upr-inp" id="medicine-power" name="medicine-power" readonly>
                </div>

                <div class=" col-md-3 mt-2">
                    <label class="mb-0" for="weightage">Weightage</label>
                    <input class="upr-inp" id="weightage" name="weightage" readonly>
                </div>
                <div class=" col-md-3 mt-2">
                    <label class="mb-0" for="packaging-in">Unit</label>
                    <input class="upr-inp" id="unit" name="unit" readonly>
                </div>
                <div class=" col-md-3 mt-2">
                    <label class="mb-0" for="edit-request-flag">Edit Request Flag</label>
                    <input class="upr-inp" id="edit-request-flag" name="edit-request-flag" readonly>
                </div>
            </div>


        </div>

    </form>
</div>