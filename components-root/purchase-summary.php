<form action="_config/form-submission/stock-in-form.php" method="post">
    <div class="card-body stock-in-summary">
        <div class="table-responsive">

            <table class="table item-table" id="stock-in-data-table" style="font-size: .7rem;">
                <thead class="thead-light text-right">
                    <tr>
                        <th scope="col" style="width: 2rem;"></th>
                        <th scope="col">
                            <input type="number" value="0" id="dynamic-id" class="d-none">
                        </th>
                        <th scope="col" class="d-none">
                            <input type="number" value="0" id="serial-control" style="width:2rem;">
                        </th>
                        <th scope="col" class="text-left">Items</th>
                        <th scope="col">Batch</th>
                        <th scope="col">Exp.</th>
                        <th scope="col">Qty.</th>
                        <th scope="col">Free</th>
                        <th scope="col">MRP</th>
                        <th scope="col">PTR</th>
                        <th scope="col">D.Price</th>
                        <th scope="col" hidden>DISC%</th>
                        <th scope="col">GST%</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody id="dataBody">

                </tbody>
            </table>
        </div>
    </div>
    <div class="m-3 p-3 pt-3 font-weight-bold text-light purchase-items-summary rounded">
        <div class="row mb-3">
            <div class="col-md-3  d-flex justify-content-start">
                <p>Distributor :

                    <input class="summary-inp w-60" name="distributor-name" id="dist-name" type="text" value=""
                        readonly>

                    <input class="d-none summary-inp w-60" name="distributor-id" id="dist-id" type="text" value=""
                        readonly>
                </p>
            </div>
            <div class="col-md-3 d-flex justify-content-start">
                <p>Dist. Bill :
                    <input class="summary-inp w-65" name="distributor-bill" id="distBill-no" type="text" value=""
                        readonly>
                </p>
            </div>
            <div class="col-md-3  d-flex justify-content-start">
                <p>Bill Date :
                    <input class="summary-inp w-65" name="bill-date-val" id="bill-date-val" type="text" value=""
                        readonly style="margin-left: 0rem;">
                </p>
            </div>
            <div class="col-md-3  d-flex justify-content-start">
                <p>Due Date :
                    <input class="summary-inp w-65" name="due-date-val" id="due-date-val" type="text"
                        value="<?= $edit == TRUE ? $stockIn[0]['due_date'] : ''; ?>" readonly
                        style="margin-left: 0rem;">
                </p>
            </div>

        </div>
        <hr class="sidebar-devider">
        <div class="row">
            <div class="col-sm-6 col-md-3 d-flex justify-content-start">
                <span>Payment :
                    <input class="summary-inp w-65" name="payment-mode-val" id="payment-mode-val" type="text" value="0"
                        readonly style="margin-left: 0rem;">
                </span>
            </div>

            <div class="col-sm-6 col-md-2  d-flex justify-content-start">
                <p>Items :
                    <input class="summary-inp w-65" name="items" id="items-val" type="text" value="0" readonly
                        style="margin-left: 0rem;">
                </p>
            </div>
            
            <div class="col-8 col-md-4 d-flex justify-content-center">
            <div class="col-sm-4 col-md-3 d-flex justify-content-start">
                <p>Qty :
                    <input class="summary-inp w-65" name="total-qty" id="qty-val" type="text" value="0" readonly
                        style="margin-left: 0rem;">
                </p>
            </div>
            <div class="col-sm-4 col-md-4 d-flex justify-content-start">
                <p>GST :
                    <input class="summary-inp w-65" name="totalGst" id="gst-val" type="text" value="0" readonly
                        style="margin-left: 0rem;">
                </p>
            </div>
            </div>
            <div class="col-sm-6 col-md-3  d-flex justify-content-start">
                <p>Net :
                    <input class="summary-inp w-65" name="netAmount" id="net-amount" type="text" value="0" readonly
                        style="margin-left: 0rem;">
                </p>
            </div>
        </div>
        <div class="d-flex  justify-content-end">
            <button class="btn btn-sm btn-primary" style="width: 8rem;" type="submit" name="stock-in"
                id="stock-in-submit">Save</button>

        </div>
    </div>
</form>