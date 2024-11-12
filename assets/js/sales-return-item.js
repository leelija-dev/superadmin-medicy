const xmlhttp = new XMLHttpRequest();

//========================= return submit button disable and enable control ======================
var returnSubmitBtn = document.getElementById('sales-return-btn');
returnSubmitBtn.setAttribute("disabled", "true");

// =============================================================

let listArea = document.getElementById("bills-list");

let patientName = document.getElementById("patient-name");
let billDate = document.getElementById("bill-date");
let reffBy = document.getElementById("reff-by");

var itemList = document.getElementById("items-list");

let stockOutDetailsItemid = document.getElementById('stock-out-details-item-id');
let pharmacyItemid = document.getElementById('pharmacy-invoice-item-details-id');
let currentItemID = document.getElementById("item-id");
let ProductID = document.getElementById("prod-id");
let expDate = document.getElementById("exp-date");
let unit = document.getElementById("unit");
let itemUnit = document.getElementById("item-unit");
let itemWeatage = document.getElementById("item-weatage");
let batch = document.getElementById("batch-no");

let mrp = document.getElementById("mrp");
let ptr = document.getElementById("ptr");
let purchaseQuantity = document.getElementById("purchase-qty");
let currentQty = document.getElementById("qty");
let discount = document.getElementById("discount");
let gst = document.getElementById("gst")
let taxable = document.getElementById("taxable");
let billAmount = document.getElementById("bill-amount");

let invoiceNo = document.getElementById("invoice-no");
let refundMode = document.getElementById("refund-mode");
let returnDate = document.getElementById("select-return-date");

let returnQtyVal = document.getElementById("return");
let refundTaxable = document.getElementById("refund-taxable");
let refundAmount = document.getElementById("refund");

var todayDate = new Date();

var date = todayDate.getDate();
var month = todayDate.getMonth() + 1;
var year = todayDate.getFullYear();

if (date < 10) {
    date = '0' + date;
}
if (month < 10) {
    month = '0' + month;
}
var todayFullDate = year + "-" + month + "-" + date;
// console.log(todayFullDate);
document.getElementById("bill-date").setAttribute("max", todayFullDate);

/////////////////////////////// data search start \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
const firstInput = document.getElementById('invoice-no');

window.addEventListener('load', function () {
    firstInput.focus();
});

firstInput.addEventListener('input', function (event) {

    const inputValue = this.value;

    // Check if the first character is a space
    if (inputValue.length > 0 && inputValue[0] === ' ') {
        this.value = inputValue.slice(1);
    }
});

const getCustomer = (invoice) => {

    if (invoice != "") {
        let invoiceUrl = `ajax/return-item-list.ajax.php?invoice=${invoice}`;
        // alert(invoiceUrl);
        xmlhttp.open("GET", invoiceUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        listArea.style.display = 'block';
        listArea.innerHTML = xmlhttp.responseText;
    } else {
        listArea.style.display = 'none';

        patientName.value = "";
        billDate.value = "";
        reffBy.value = "";

        stockOutDetailsItemid.value = "";
        pharmacyItemid.value = "";
        currentItemID.value = "";
        ProductID.value = "";
        expDate.value = "";
        unit.value = "";
        itemUnit.value = "";
        itemWeatage.value = "";
        batch.value = "";
        mrp.value = "";
        purchaseQuantity.value = "";
        currentQty.value = "";
        discount.value = "";
        gst.value = "";
        taxable.value = "";
        billAmount.value = "";
        refundAmount.value = "";
        itemList.innerHTML = '<option value="" selected disabled>Select Invoice Number First</option>';
    };
};

const getRefundMode = (ref) => {
    document.getElementById("refund-mode-val").value = ref;
}

const getReturnDate = (date) => {
    document.getElementById('return-date').value = date;
};


const getDtls = (invoiceId, customerId) => {

    if (invoiceId != "" && customerId != "") {

        //==================== Reff By ====================
        patientUrl = 'ajax/stockOut.all.ajax.php?patient=' + invoiceId;
        // alert(url);
        xmlhttp.open("GET", patientUrl, false);
        xmlhttp.send(null);
        patientName.value = xmlhttp.responseText;

        //==================== Bill Date ====================
        billDateUrl = 'ajax/stockOut.all.ajax.php?bill-date=' + invoiceId;
        // alert(url);
        xmlhttp.open("GET", billDateUrl, false);
        xmlhttp.send(null);
        billDate.value = xmlhttp.responseText;
        document.getElementById('purchased-date').value = xmlhttp.responseText;

        //==================== Reff By ====================
        reffUrl = 'ajax/stockOut.all.ajax.php?reff-by=' + invoiceId;
        // alert(url);
        xmlhttp.open("GET", reffUrl, false);
        xmlhttp.send(null);
        reffBy.value = xmlhttp.responseText;

        //==================== Products List ====================
        productsUrl = 'ajax/stockOut.all.ajax.php?products=' + invoiceId;
        xmlhttp.open("GET", productsUrl, false);
        xmlhttp.send(null);
        itemList.innerHTML = xmlhttp.responseText;

        //================ extra details =======================
        // chkInvoiceIdUrl = ;
        //======================================================

        document.getElementById('invoice-no').value = invoiceId;

        listArea.style.display = 'none';

    } else {

        patientName.value = "";
        billDate.value = "";
        reffBy.value = "";
        stockOutDetailsItemid.value = "";
        pharmacyItemid.value = "";
        currentItemID.value = "";
        ProductID.value = "";
        expDate.value = "";
        unit.value = "";
        itemUnit.value = "";
        itemWeatage.value = "";
        batchNo.value = "";
        mrp.value = "";
        purchaseQuantity.value = "";
        currentQty.value = "";
        discount.value = "";
        gst.value = "";
        taxable.value = "";
        billAmount.value = "";
    }
}

const getItemDetails = (t) => {

    let stockOutDetailsDataId = t.selectedOptions[0].getAttribute('stokOutDetails-data-id');
    let pharmacyItemDetailsId = t.selectedOptions[0].getAttribute('pharmacy-data-id');
    let invoice = t.selectedOptions[0].getAttribute('data-invoice');
    let itemId = t.value;
    // console.log("stock out details item id : "+itemId);
    let batchNo = t.selectedOptions[0].getAttribute('data-batch');

    if (itemId != "") {

        //==================== pharmacy invoice item id ==========
        stockOutDetailsItemid.value = stockOutDetailsDataId;

        //=========== stock out details item id =================
        pharmacyItemid.value = pharmacyItemDetailsId;

        //==================== Product id ====================
        let productId = `ajax/stockOut.all.ajax.php?prod-id=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", productId, false);
        xmlhttp.send(null);
        ProductID.value = xmlhttp.responseText;

        //==================== Exp Date ====================
        let expUrl = `ajax/stockOut.all.ajax.php?exp-date=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", expUrl, false);
        xmlhttp.send(null);
        expDate.value = xmlhttp.responseText;

        //==================== Unit ====================
        let unitUrl = `ajax/stockOut.all.ajax.php?unit=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        unit.value = xmlhttp.responseText;

        let itemUnitUrl = `ajax/stockOut.all.ajax.php?itemUnit=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", itemUnitUrl, false);
        xmlhttp.send(null);
        itemUnit.value = xmlhttp.responseText;

        let itemWeatageUrl = `ajax/stockOut.all.ajax.php?itemWeatage=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", itemWeatageUrl, false);
        xmlhttp.send(null);
        itemWeatage.value = xmlhttp.responseText;

        //==================== Batch ====================
        batch.value = batchNo;

        // ================= ITEM ID ================
        currentItemID.value = t.value;
        //==================== Mrp ====================
        let mrpUrl = `ajax/stockOut.all.ajax.php?mrp=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        mrp.value = xmlhttp.responseText;

        //==================== PTR ====================
        let ptrUrl = `ajax/stockOut.all.ajax.php?ptr=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", ptrUrl, false);
        xmlhttp.send(null);
        ptr.value = xmlhttp.responseText;

        //==================== PURCHASE QTY ====================
        let purchaseqtyUrl = `ajax/stockOut.all.ajax.php?p_qty=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", purchaseqtyUrl, false);
        xmlhttp.send(null);
        purchaseQuantity.value = xmlhttp.responseText;

        //==================== QTY ====================
        let qtyUrl = `ajax/stockOut.all.ajax.php?qty=${invoice}&p-id=${itemId}&batch=${batchNo}`;
        xmlhttp.open("GET", qtyUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText)
        currentQty.value = xmlhttp.responseText;

        //==================== DISC ====================
        let discUrl = `ajax/stockOut.all.ajax.php?disc=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", discUrl, false);
        xmlhttp.send(null);
        discount.value = xmlhttp.responseText;

        //==================== GST ====================
        let gstUrl = `ajax/stockOut.all.ajax.php?gst=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", gstUrl, false);
        xmlhttp.send(null);
        gst.value = xmlhttp.responseText;

        //==================== Taxable ====================
        let taxableUrl = `ajax/stockOut.all.ajax.php?taxable=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", taxableUrl, false);
        xmlhttp.send(null);
        taxable.value = xmlhttp.responseText;

        //==================== AMOUNT ====================
        let amountUrl = `ajax/stockOut.all.ajax.php?amount=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", amountUrl, false);
        xmlhttp.send(null);
        billAmount.value = xmlhttp.responseText;

        listArea.style.display = 'none';

        returnQtyVal.value = "";
        refundAmount.value = "";

        document.getElementById('return').focus();

        returnSubmitBtn.setAttribute("disabled", "true"); // set return button disable

    } else {
        stockOutDetailsItemid.value = "";
        pharmacyItemid.value = "";
        currentItemID.value = "";
        ProductID.value = "";
        expDate.value = "";
        unit.value = "";
        itemUnit.value = "";
        itemWeatage.value = "";
        batchNo.value = "";
        mrp.value = "";
        purchaseQuantity.value = "";
        currentQty.value = "";
        discount.value = "";
        gst.value = "";
        taxable.value = "";
        refundAmount.value = "";
        billAmount.value = "";
    }
}


const getRefund = (returnQty) => {
    // console.log("return qantity test", returnQty);

    let currenQty = document.getElementById('qty').value;
    let mrp = document.getElementById('mrp').value;
    let disc = document.getElementById('discount').value;
    let gst = document.getElementById('gst').value;
    let weatage = document.getElementById('item-weatage').value;
    let itemUnit = document.getElementById('item-unit').value;

    if (parseInt(returnQty) <= parseInt(currenQty)) {
        if (itemUnit == 'tab' || itemUnit == 'cap') {
            let refundAmount = ((parseFloat(mrp) / parseInt(weatage)) - ((parseFloat(mrp) / parseInt(weatage)) * parseFloat(disc) / 100)) * parseInt(returnQty);

            refundTaxable = (parseFloat(refundAmount) * 100) / (parseFloat(gst) + 100);

            document.getElementById('refund').value = refundAmount.toFixed(2);
            document.getElementById('refund-taxable').value = refundTaxable.toFixed(2);
        } else {
            let refundAmount = (parseFloat(mrp) - (parseFloat(mrp) * parseFloat(disc) / 100)) * returnQty;

            refundTaxable = (parseFloat(refundAmount) * 100) / (parseFloat(gst) + 100);

            document.getElementById('refund').value = refundAmount.toFixed(2);
            document.getElementById('refund-taxable').value = refundTaxable.toFixed(2);
        }
    }

    if (parseInt(returnQty) > parseInt(currenQty)) {
        document.getElementById("refund").value = '';
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Inserted value might be grater than sold qty.',
        })
        document.getElementById('return').value = '';
    }
}

// ##################################################################################
// ##################################################################################

//geeting bills by clicking on add button
const addData = () => {

    let StockOutDetailsItemId = document.getElementById('stock-out-details-item-id').value;
    let PharmacyInvoiceItemId = document.getElementById('pharmacy-invoice-item-details-id').value;
    let salesReturnDetailsItemId = document.getElementById('sales-return-details-item-id').value;
    let currentItemID = document.getElementById("item-id").value;
    let pId = document.getElementById("prod-id").value;
    let expDate = document.getElementById("exp-date").value;
    let unit = document.getElementById("unit").value;
    let ItemUnit = document.getElementById("item-unit").value;
    let ItemWeatage = document.getElementById("item-weatage").value;
    let batch = document.getElementById("batch-no").value;
    let mrp = document.getElementById("mrp").value;
    let ptr = document.getElementById("ptr").value;
    let purchaseQuantity = document.getElementById("purchase-qty").value;
    let currentQty = document.getElementById("qty").value;
    let discount = document.getElementById("discount").value;
    let gst = document.getElementById("gst").value;
    let taxable = document.getElementById("taxable").value;
    let billAmount = document.getElementById("bill-amount").value;

    //============================ set and filter invoice number ==================================
    let invoiceNo = document.getElementById("invoice-no").value;
    let returnInvoiceId = document.getElementById('invoice').value;
    if (returnInvoiceId != "") {
        if (returnInvoiceId != invoiceNo) {
            Swal.fire({
                title: 'Are you sure? Do you want to chang Invoice id?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Okay'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Changed!',
                        'Invoice id reset.',
                        'success'
                    )
                }
                window.location.reload();
            })
        }
    } else {
        document.getElementById('invoice').value = invoiceNo;
    }

    //=============================================================================================

    let refundMode = document.getElementById("refund-mode").value;
    let returnDate = document.getElementById('return-date');

    let returnQtyVal = document.getElementById("return").value;
    let refundTaxable = document.getElementById("refund-taxable").value;
    refundTaxable = parseFloat(refundTaxable);
    let refundAmount = document.getElementById("refund").value;
    refundAmount = parseFloat(refundAmount);



    if (invoiceNo.value == "") {
        Swal.fire("Failed!", "Please Select invoice no!", "error");
        invoiceNo.focus();
        return;
    }

    if (patientName.value == "") {
        Swal.fire("Failed!", "Patient name must be not noull", "error");
        patientName.focus();
        return;
    }

    if (billDate.value == "") {
        Swal.fire("Failed!", "Please enter Date!", "error");
        billDate.focus();
        return;
    }

    if (reffBy.value == "") {
        Swal.fire.fire("Failed!", "Doctor name must be not null", "error");
        reffBy.focus();
        return;
    }

    if (returnDate.value == "") {
        Swal.fire("Failed!", "Please Select return date!", "error");
        return;
    }

    if (refundMode == "") {
        Swal.fire("Failed!", "Please Select refund mode!", "error");
        return;
    }

    if (itemList.value == "") {
        Swal.fire("Failed!", "Please Select returning item!", "error");
        itemList.focus();
        return;
    } else { }

    if (currentItemID.value == "") {
        Swal.fire("Failed!", "Please select an item", "error");
        currentItemID.focus();
        return;
    }

    if (expDate.value == "") {
        Swal.fire("Failed!", "Expiary date must be not null!", "error");
        expDate.focus();
        return;
    }

    if (unit.value == "") {
        Swal.fire("Failed!", "Unit value must be not null!", "error");
        unit.focus();
        return;
    }

    if (batch.value == "") {
        Swal.fire("Failed!", "Batch number must be not null", "error");
        batch.focus();
        return;
    }

    if (mrp.value == "") {
        Swal.fire("Failed!", "MRP must be not null!", "error");
        mrp.focus();
        return;
    }

    if (currentQty.value == "") {
        Swal.fire("Failed!", "Qantity must be not null", "error");
        currentQty.focus();
        return;
    }

    if (discount.value == "") {
        Swal.fire("Failed!", "Discount must be not null", "error");
        discount.focus();
        return;
    }

    if (gst.value == "") {
        Swal.fire("Failed!", "GST must be not null!", "error");
        gst.focus();
        return;
    }

    if (taxable.value == "") {
        Swal.fire("Failed!", "taxable must be not null!", "error");
        taxable.focus();
        return;
    }

    if (billAmount.value == "") {
        Swal.fire("Failed!", "bill amount must be not null!", "error");
        billAmount.focus();
        return;
    }

    if (returnQtyVal == "") {
        Swal.fire("Failed!", "return qantity must be not null!", "error");
        returnQtyVal.focus();
        return;
    }

    if (refundTaxable == "") {
        Swal.fire("Failed!", "refund amount must be not null!", "error");
        refund.focus();
        return;
    }

    if (refundAmount == "") {
        Swal.fire("Failed!", "refund amount must be not null!", "error");
        refund.focus();
        return;
    }


    let existsItems = document.querySelectorAll('tr');
    for (let i = 0; i < existsItems.length; i++) {
        if (i > 0) {

            const item = existsItems[i];
            if (item.childNodes[5].childNodes[3].value == itemList.value) {
                Swal.fire("You can not add same item more than one!");
                stockOutDetailsItemid.value = "";
                pharmacyItemid.value = "";
                ProductID.value = "";
                expDate.value = "";
                unit.value = "";
                itemUnit.value = "";
                itemWeatage.value = "";
                batch.value = "";
                mrp.value = "";
                currentQty.value = "";
                discount.value = "";
                gst.value = "";
                taxable.value = "";
                billAmount.value = "";
                refundTaxable.value = "";
                refundAmount.value = "";
                return;
            }
        }

    }

    let itemName = itemList.selectedOptions[0].text;
    // let items = document.querySelectorAll('tr');
    // let slno = items.length;

    let slno = document.getElementById("dynamic-id").value;
    let slControl = document.getElementById("serial-control").value;
    slno++;
    slControl++;
    document.getElementById("dynamic-id").value = slno;
    document.getElementById("serial-control").value = slControl;

    ///////// == items count ======
    let items = document.getElementById("total-items");
    let finalItem = parseInt(items.value) + 1;
    items.value = finalItem;

    // total Refund Amount
    var totalRefund = document.getElementById("refund-amount");
    let netRefund = parseFloat(totalRefund.value) + parseFloat(refundAmount);
    // console.log(netRefund);
    totalRefund.value = netRefund.toFixed(2);

    //total item qty
    var totalQty = document.getElementById("total-qty");
    let totalQtyTemp = parseFloat(totalQty.value) + parseFloat(returnQtyVal);
    totalQty.value = totalQtyTemp;

    // generate gst amount on refund
    var netGstAmount = document.getElementById("gst-amount").value;
    var totalGstAmount = parseFloat(refundAmount) - parseFloat(refundTaxable);
    var updatedNetGstAmount = parseFloat(netGstAmount) + parseFloat(totalGstAmount);
    document.getElementById("gst-amount").value = updatedNetGstAmount.toFixed(2);
    let gstPerItem = totalGstAmount.toFixed(2);


    const appendData = () => {
        jQuery("#dataBody")
            .append(`<tr id="table-row-${slControl}">
            <td><i class="fas fa-trash text-danger" onclick="deleteData(${slControl}, ${parseFloat(returnQtyVal)}, ${gstPerItem}, ${refundAmount.toFixed(2)})"></i></td>

            <td class="pt-3" id="row-${slControl}-col-1" style="font-size: 0.7rem;">${slno}</td>
            <td class="pt-3" id="row-${slControl}-col-2">
                <input class="table-data w-10r" type="text" value="${itemName}" readonly style="font-size: .65rem;">
                <input class="d-none" type="text" name="itemId[]" value="${itemList.value}">

            </td>

            <td class="d-none pt-3">
                <input class="table-data w-6r" type="text" name="stockOutDetailsItemIds[]" value="${StockOutDetailsItemId}" readonly>
                <input class="table-data w-6r" type="text" name="pharmacyInvoiceItemIds[]" value="${PharmacyInvoiceItemId}" readonly>
                <input class="table-data w-6r" type="text" name="salesReturnDetailsItemIds[]" value="${salesReturnDetailsItemId}" readonly>
            </td>

            <td class="d-none pt-3">
                <input class="table-data w-6r" type="text" name="curretnItemId[]" value="${currentItemID}" readonly style="font-size: 0.65rem;">
            </td>

            <td class="d-none pt-3">
                <input class="table-data w-6r" type="text" name="productId[]" value="${pId}" readonly style="font-size: 0.65rem;">
            </td>
            <td class="pt-3" id="row-${slControl}-col-3">
                <input class="table-data w-6r" type="text" name="batchNo[]" value="${batch}" readonly style="font-size: 0.65rem;">
            </td>
            <td class="pt-3" id="row-${slControl}-col-4">
                <input class="table-data w-3r" type="text" name="expDate[]" value="${expDate}" readonly style="font-size: 0.65rem;">
            </td>

            <td class="pt-3" id="row-${slControl}-col-5">
                <input class="table-data w-3r" type="text" name="setof[]" value="${unit}" readonly style="font-size: 0.65rem;">
            </td>

            <td class="d-none pt-3" id="row-${slControl}-col-6">
                <input class="table-data w-3r" type="text" name="qty[]" value="${currentQty}" readonly style="font-size: 0.65rem; text-align: end;">

                <input class="   table-data w-3r" type="text" name="p_Qty[]" value="${purchaseQuantity}" readonly style="font-size: 0.65rem; text-align: end;">
            </td>
            <td class="pt-3" id="row-${slControl}-col-7">
                <input class="table-data w-3r" type="text" name="mrp[]" value="${mrp}" readonly style="font-size: 0.65rem;">
            </td>
            <td class="pt-3" id="row-${slControl}-col-7">
                <input class="table-data w-3r" type="text" name="ptr[]" value="${ptr}" readonly style="font-size: 0.65rem;">
            </td>
            <td class="pt-3" id="row-${slControl}-col-8">
                <input class="table-data w-2r" type="text" name="disc[]" value="${discount}" readonly style="font-size: 0.65rem;">
            </td>
            <td class="pt-3" id="row-${slControl}-col-9">
                <input class="table-data w-2r" type="text" name="gst[]" value="${gst}" readonly style="font-size: 0.65rem;">
            </td>

            <td class="pt-3" id="row-${slControl}-col-10">
                <input class="table-data w-2r" type="text" name="return[]" value="${returnQtyVal}" readonly style="font-size: 0.65rem; text-align: end;">
            </td>

            <td class="pt-3" id="row-${slControl}-col-11">
                <input class="table-data w-4r" type="text" name="taxable[]" value="${refundTaxable.toFixed(2)}"  style="font-size: 0.65rem; text-align: end;">
            </td>
            
            <td class="pt-3" id="row-${slControl}-col-12">
            <input class="table-data w-4r" type="any" name="refundPerItem[]" value="${refundAmount.toFixed(2)}" readonly style="font-size: 0.65rem; text-align: end;">
            </td>
        </tr>`);

        return true;
    };

    if (appendData() == true) {
        itemList.remove(itemList.selectedIndex);
        itemList.options[0].selected = true;

        const dataTuple = {
            slno: slControl,
            invoiceNo: invoiceNo,
            ProductName: itemName,
            StockOutDetailsItemId: StockOutDetailsItemId,
            PharmacyInvoiceItemId: PharmacyInvoiceItemId,
            salesReturnDetailsItemId: salesReturnDetailsItemId,
            currentItemID: currentItemID,
            pId: pId,
            batch: batch,
            expDate: expDate,
            unit: unit,
            ItemUnit: ItemUnit,
            ItemWeatage: ItemWeatage,
            mrp: mrp,
            ptr: ptr,
            purchaseQuantity: purchaseQuantity,
            currentQty: currentQty,

            discount: discount,
            gst: gst,
            SellingTimeTaxable: taxable,
            SellingBillAmount: billAmount,
            returnQtyVal: returnQtyVal,
            refundTaxable: refundTaxable,
            refundAmount: refundAmount,

        };

        let tupleData = JSON.stringify(dataTuple);

        document.getElementById(`row-${slControl}-col-1`).onclick = function () {
            editItem(tupleData);
        };
        document.getElementById(`row-${slControl}-col-2`).onclick = function () {
            editItem(tupleData);
        };
        document.getElementById(`row-${slControl}-col-3`).onclick = function () {
            editItem(tupleData);
        };
        document.getElementById(`row-${slControl}-col-4`).onclick = function () {
            editItem(tupleData);
        };
        document.getElementById(`row-${slControl}-col-5`).onclick = function () {
            editItem(tupleData);
        };
        document.getElementById(`row-${slControl}-col-6`).onclick = function () {
            editItem(tupleData);
        };
        document.getElementById(`row-${slControl}-col-7`).onclick = function () {
            editItem(tupleData);
        };
        document.getElementById(`row-${slControl}-col-8`).onclick = function () {
            editItem(tupleData);
        };
        document.getElementById(`row-${slControl}-col-9`).onclick = function () {
            editItem(tupleData);
        };
        document.getElementById(`row-${slControl}-col-10`).onclick = function () {
            editItem(tupleData);
        };
        document.getElementById(`row-${slControl}-col-11`).onclick = function () {
            editItem(tupleData);
        };
        document.getElementById(`row-${slControl}-col-12`).onclick = function () {
            editItem(tupleData);
        };
    }

    // ======== form submit button enable action ============
    let invoiceCheck = document.getElementById('invoice').value;
    let stockReturnCheck = document.getElementById('stock-out-details-item-id').value;
    console.log("invoice number check : "+invoiceCheck);
    console.log("stock out details item id check : "+stockReturnCheck);


    returnSubmitBtn.removeAttribute("disabled");
   
    //========================================================

    document.getElementById("return-item-details").reset();
    event.preventDefault();
} //eof addData  


// ========================= added item edit optin ============================

const editItem = (tuple) => {
    returnSubmitBtn.setAttribute("disabled", "true");
    // console.log(tuple);
    if (document.getElementById('item-id').value == '') {
        tData = JSON.parse(tuple);

        let editData = document.createElement("option");

        editData.setAttribute("stokOutDetails-data-id", tData.StockOutDetailsItemId);
        editData.setAttribute("pharmacy-data-id", tData.PharmacyInvoiceItemId);
        editData.setAttribute("data-invoice", tData.invoiceNo);
        editData.setAttribute("data-batch", tData.batch);
        editData.setAttribute("value", tData.currentItemID);
        editData.text = tData.ProductName;
        itemList.appendChild(editData);

        // -----------------------------------------------------------------------------
        let gstPerItem = parseFloat(tData.refundAmount) - parseFloat(tData.refundTaxable);
        gstPerItem = parseFloat(gstPerItem).toFixed(2);
        deleteData(tData.slno, tData.returnQtyVal, gstPerItem, tData.refundAmount);

    } else {
        Swal.fire("Failed!", "Add previous data first", "error");
    }

}

// ================================ Delet Data ================================

function deleteData(slno, returnQty, gstPerItem, itemRefund) {

    //====
    let delRow = slno;
    //====================
    jQuery(`#table-row-${slno}`).remove();
    let slVal = document.getElementById("dynamic-id").value;
    document.getElementById("dynamic-id").value = parseInt(slVal) - 1;

    // let existitems = document.querySelectorAll('tr');
    // for (let i = 1; i < existitems.length; i++) {
    //     existitems[i].id = `table-row-${i}`;
    //     existitems[i].childNodes[1].childNodes[1].id = i;
    //     existitems[i].childNodes[3].innerText = i;
    // }

    //////////////minus item
    let items = document.getElementById("total-items");
    let finalItem = items.value - 1;
    items.value = finalItem;

    ///////////// minus quantity
    let qty = document.getElementById("total-qty");
    let finalQty = qty.value - returnQty
    qty.value = finalQty;


    // minus netAmount
    let gst = document.getElementById("gst-amount");
    let finalGst = parseFloat(gst.value) - parseFloat(gstPerItem);
    gst.value = finalGst.toFixed(2);


    // minus netAmount
    let refundAmount = document.getElementById("refund-amount");
    let finalAmount = refundAmount.value - itemRefund;
    refundAmount.value = finalAmount.toFixed(2);

    rowAdjustment(delRow);
}

//========= row number adjustment ========
function rowAdjustment(delRow) {
    let tableId = document.getElementById("dataBody");
    let j = 0;
    let colIndex1 = 1;

    for (let i = 0; i < tableId.rows.length; i++) {
        j++;

        let row = tableId.rows[i];
        let cell1 = row.cells[colIndex1];
        cell1.innerHTML = j;
    }
}



    




