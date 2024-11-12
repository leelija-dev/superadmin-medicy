<div class="col-12">
    <form id="stock-in-data">
        <div class="row">
            <div class="col-md-7">
                <div class="row mt-4 mb-2">
                    <div class="col-md-12 ">
                        <!-- <label for="product-name" class="mb-0">Product Name</label> -->
                        <input class="upr-inp mt-2" list="datalistOptions" id="product-name" name="product-name"
                            placeholder="Search Product" onkeyup="searchItem(this.value);" autocomplete="off" value=""
                            onkeydown="chekForm()">

                        <div class="p-2 bg-light" id="product-select" style="max-height: 25rem; max-width: 100%;">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="d-none col-md-12 mt-2">
                        <label class="mb-0" for="manufacturer-id">Manufacturer</label>
                        <!-- <select class="upr-inp" id="manufacturer-id" name="manufacturer-id"
                                                required>
                                                <option value="" disabled selected>Select </option>

                                            </select> -->
                        <input class="d-none upr-inp" id="manufacturer-id" name="manufacturer-id" value="">
                        <input class="upr-inp" id="manufacturer-name" name="manufacturer-name" value="">
                    </div>
                </div>

                <div class="d-none row">
                    <div class="col-md-12 ">
                        <div class="">

                            <div class="col-sm-4 col-md-3 mt-2 ">
                                <label class="mb-0" for="weightage">Weightage</label>
                                <input type="text" class="upr-inp" id="weightage" value="" readonly>
                            </div>

                            <div class="col-sm-4 col-md-3 mt-2 ">
                                <label class="mb-0" for="unit"> Unit</label>
                                <input type="text" class="upr-inp" id="unit" value="" readonly>
                            </div>

                            <div class="col-sm-4 col-md-3 mt-2 ">
                                <label class="mb-0" for="packaging-in">Packaging-in</label>
                                <input type="text" class="upr-inp" id="packaging-in" value="" readonly>
                            </div>
                            <div class="col-sm-4 col-md-3 mt-2 ">
                                <label class="mb-0" for="medicine-power">Medicine Power</label>
                                <input class="upr-inp" type="text" name="medicine-power" id="medicine-power">
                            </div>
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

                    <div class="col-sm-4  mt-2">
                        <label class="mb-0" for="batch-no">Batch No.</label>
                        <input type="text" class="upr-inp" name="batch-no" id="batch-no"
                            style="text-transform: uppercase;">
                    </div>
                    <div class="col-sm-4  mt-2">
                        <label class="mb-0 mt-1" for="mfd-date">MFD</label>
                        <div class="d-flex date-field">
                            <input class="month " type="number" id="mfd-month" onkeyup="setMfdMonth(this);"
                                onfocusout="setmfdMonth(this);">
                            <span class="date-divider">&#47;</span>
                            <input class="year " type="number" id="mfd-year" onfocusout="setMfdYear(this);"
                                onkeyup="setMfdYEAR(this)">
                        </div>
                    </div>
                    <div class="col-sm-4 mt-2">
                        <label class="mb-0 mt-1" for="exp-date">Expiry Date</label>
                        <div class="d-flex date-field">
                            <input class="month " type="number" id="exp-month" onkeyup="setExpMonth(this);"
                                onfocusout="setexpMonth(this);">
                            <span class="date-divider">&#47;</span>
                            <input class="year " type="number" id="exp-year" onfocusout="setExpYear(this);"
                                onkeyup="setExpYEAR(this)">
                        </div>
                    </div>
                    <div class="d-none col-md-4 mt-2">
                        <label class="mb-0" for="product-id">Product Id</label>
                        <input class="upr-inp" id="product-id" name="product-id" readonly>
                    </div>
                </div>
                <!--/End Quantity Row  -->
            </div>

            <div class="col-md-5">
                <!-- Price Row -->
                <div class="row mb-2">

                    <div class="col-sm-4 col-md-4 mt-2">
                        <label class="mb-0" for="purchase-price">PTR/Package</label>
                        <input type="number" class="upr-inp" name="ptr" id="ptr" onfocusout="getBillAmount()">
                    </div>

                    <div class="col-sm-4 col-md-4 mt-2">
                        <label class="mb-0" for="qty">Quantity</label>
                        <input type="number" class="upr-inp" name="qty" id="qty" onfocusout="getBillAmount()">
                    </div>
                    <div class="col-sm-4 col-md-4 mt-2">
                        <label class="mb-0" for="free-qty">Free</label>
                        <input type="number" class="upr-inp" name="free-qty" id="free-qty">
                    </div>

                </div>
                <!--/End Price Row -->

                <div class="d-none col-sm-6 col-md-6 mt-2">
                    <label class="mb-0" for="packaging-type">Packaging Type</label>
                    <select class="upr-inp" name="packaging-type" id="packaging-type">
                        <option value="" disabled selected>Select Packaging Type </option>
                        <?php
                                                        foreach ($showPackagingUnits as $rowPackagingUnits) {
                                                            echo '<option value="' . $rowPackagingUnits['id'] . '">' . $rowPackagingUnits['unit_name'] . '</option>';
                                                        }
                                                        ?>
                    </select>
                </div>
                <!--/End Quantity Row  -->

                <!-- Price Row -->
                <div class="row mb-2">

                    <div class="col-sm-4 col-md-4 mt-2">
                        <label class="mb-0" for="discount">Discount%</label>
                        <input type="number" class="upr-inp" name="discount" id="discount" placeholder="Discount %"
                            onfocusout="getBillAmount()">
                    </div>

                    <div class="col-sm-4 col-md-4 mt-2">
                        <label class="mb-0" for="base">Base</label>
                        <input type="number" class="upr-inp" name="base" id="base" readonly>
                    </div>

                    <div class="col-sm-4 col-md-4 mt-2">
                        <label class="mb-0" for="bill-amount">Bill Amount</label>
                        <input type="any" class="upr-inp" name="bill-amount" id="bill-amount" readonly required>
                    </div>

                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                    <button type="button" class="btn btn-primary me-md-2" onclick="addData()">Add
                        <i class="fas fa-plus"></i></button>
                </div>

            </div>

        </div>

    </form>
</div>