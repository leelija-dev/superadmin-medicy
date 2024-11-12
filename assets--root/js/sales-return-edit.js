
// const xmlhttp = new XMLHttpRequest();
const listArea = document.getElementById("bills-list");

const allowedUnits = ["tablets", "tablet", "capsules", "capsule"];

//========================= return submit button disable and enable control ======================
var returnSubmitBtn = document.getElementById('return-btn');
returnSubmitBtn.setAttribute("disabled", "true");


var invoiceID = document.getElementById("invoiceID").value;
var salesReturnId = document.getElementById("sales-return-id").value;

var patientName = document.getElementById("patient-name");
var billDate = document.getElementById("bill-date");
var reffBy = document.getElementById("reff-by");

var itemList = document.getElementById("items-list");
var returnDetailsitemid = document.getElementById('sales-return-details-item-id');
var CurrentStockItemId = document.getElementById('current-stock-item-id');
var expDate = document.getElementById("exp-date");
var unit = document.getElementById("unit");
var unitType = document.getElementById("unit-type");
var itemWeatage = document.getElementById("item-weatage");

var prodId = document.getElementById('product-id');
var batch = document.getElementById("batch-no");
var mrp = document.getElementById("mrp");
var ptr = document.getElementById("ptr");
var pqty = document.getElementById("P-qty");
var currentQty = document.getElementById("current-qty");
var prevReturnQty = document.getElementById("prev-rtrn-qty");

var rtnqty = document.getElementById("return-qty");
var discount = document.getElementById("discount");

var gst = document.getElementById("gst")
var taxable = document.getElementById("taxable");
var billAmount = document.getElementById("refund");

var invoiceNo = document.getElementById("invoice-no");
var refundMode = document.getElementById("refund-mode");

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
document.getElementById("bill-date").setAttribute("max", todayFullDate);

const getReturnDate = (date) => {
    document.getElementById('return-date').value = date;
};


if(invoiceID != null){
    document.getElementById('invoice').value = `#${invoiceID}`;
    productsUrl = `ajax/salesReturnEdit.ajax.php?products=${invoiceID}&salesreturnID=${salesReturnId}`;
    xmlhttp.open("GET", productsUrl, false);
    xmlhttp.send(null);
    // console.log(xmlhttp.responseText);
    itemList.innerHTML = xmlhttp.responseText;
}

const getRefundMode = (ref) => {
    document.getElementById("refund-mode-val").value = ref;
}

const getEditItemDetails = (t) => {

    let fieldId = t.id;
    let productId = t.value;

    let invoice = t.selectedOptions[0].getAttribute('data-invoice');
    let batchNo = t.selectedOptions[0].getAttribute('data-batch');
    let salesReturnId = t.selectedOptions[0].getAttribute('sales-return-id');
    let currentStockItemId = t.selectedOptions[0].getAttribute('current-stock-item-id');
    let returndItemId = t.selectedOptions[0].getAttribute('returned-item-id');
    
    if (t != "") {
        // ====== sales return details id ======
        document.getElementById('sales-return-id').value = salesReturnId;
        document.getElementById('sales-return-details-item-id').value = returndItemId;
        CurrentStockItemId.value = currentStockItemId;
        //==================== Exp Date Date ====================
        let expUrl = `ajax/salesReturnEdit.ajax.php?exp-date=${returndItemId}`;
        xmlhttp.open("GET", expUrl, false);
        xmlhttp.send(null);
        expDate.value = xmlhttp.responseText;

        //==================== Unit ====================
        let unitUrl = `ajax/salesReturnEdit.ajax.php?unit=${returndItemId}`;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        unit.value = xmlhttp.responseText;

        let unitTypeUrl = `ajax/salesReturnEdit.ajax.php?unitType=${returndItemId}`;
        xmlhttp.open("GET", unitTypeUrl, false);
        xmlhttp.send(null);
        unitType.value = xmlhttp.responseText;

        let itemWeatageUrl = `ajax/salesReturnEdit.ajax.php?itemWeatage=${returndItemId}`;
        xmlhttp.open("GET", itemWeatageUrl, false);
        xmlhttp.send(null);
        itemWeatage.value = xmlhttp.responseText;

        // ================== product id ===============
        prodId.value = productId;

        //==================== Batch ====================
        let batchUrl = `ajax/salesReturnEdit.ajax.php?batchNo=${returndItemId}`;
        xmlhttp.open("GET", batchUrl, false);
        xmlhttp.send(null);
        batch.value = xmlhttp.responseText;

        //==================== Mrp ====================
        let mrpUrl = `ajax/salesReturnEdit.ajax.php?mrp=${invoice}&p-id=${currentStockItemId}`;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        mrp.value = xmlhttp.responseText;

        //==================== ptr ====================
        let ptrUrl = `ajax/salesReturnEdit.ajax.php?ptr=${invoice}&p-id=${currentStockItemId}`;
        xmlhttp.open("GET", ptrUrl, false);
        xmlhttp.send(null);
        ptr.value = xmlhttp.responseText;

        //==================== Purchase QTY ====================
        let purchaseqtyUrl = `ajax/salesReturnEdit.ajax.php?pqty=${invoice}&p-id=${currentStockItemId}`;
        xmlhttp.open("GET", purchaseqtyUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText)
        pqty.value = xmlhttp.responseText;

        //==================== current QTY ====================
        let crntQty = `ajax/salesReturnEdit.ajax.php?cqty=${invoice}&p-id=${currentStockItemId}`;
        xmlhttp.open("GET", crntQty, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText)
        currentQty.value = xmlhttp.responseText;

        //==================== Return QTY ====================
        let rtnQtyUrl = `ajax/salesReturnEdit.ajax.php?rtnqty=${returndItemId}`;
        xmlhttp.open("GET", rtnQtyUrl, false);
        xmlhttp.send(null);
        let returnQantity = xmlhttp.responseText;
        returnQantity = returnQantity.replace(/\D/g, '');
     
        document.getElementById('return-qty').value = returnQantity;
        prevReturnQty.value = returnQantity;

        //==================== DISC ====================
        let discUrl = `ajax/salesReturnEdit.ajax.php?disc=${returndItemId}`;
        xmlhttp.open("GET", discUrl, false);
        xmlhttp.send(null);
        discount.value = xmlhttp.responseText;

        //==================== GST ====================
        let gstUrl = `ajax/salesReturnEdit.ajax.php?gst=${returndItemId}`;
        xmlhttp.open("GET", gstUrl, false);
        xmlhttp.send(null);
        gst.value = xmlhttp.responseText;

        //==================== taxable ====================
        let taxableUrl = `ajax/salesReturnEdit.ajax.php?taxable=${returndItemId}`;
        xmlhttp.open("GET", taxableUrl, false);
        xmlhttp.send(null);
        taxable.value = xmlhttp.responseText;

        //==================== refund amount ====================
        let amountUrl = `ajax/salesReturnEdit.ajax.php?amount=${returndItemId}`;
        xmlhttp.open("GET", amountUrl, false);
        xmlhttp.send(null);
        billAmount.value = xmlhttp.responseText;

        listArea.style.display = 'none';

        document.getElementById('return-qty').focus();

        returnSubmitBtn.setAttribute("disabled", "true");

    } else {

        CurrentStockItemId.value = '';
        returnDetailsitemid.value = "";
        expDate.value = "";
        unit.value = "";
        unitType.value = "";
        weatage.value = "";
        prodId.value = "";
        batchNo.value = "";
        mrp.value = "";
        pqty.value = "";
        currentQty.value = "";
     
        rtnqty.value = "";
        discount.value = "";
        gst.value = "";
        taxable.value = "";
        billAmount.value = "";

    }
}

const getRefund = (returnQty) => {

    let mrp = document.getElementById('mrp').value;
    let disc = document.getElementById('discount').value;
    let gst = document.getElementById('gst').value;
    let unitType = document.getElementById('unit-type').value;
    let itemWeatage = document.getElementById('item-weatage').value;
    let pruchsQty = document.getElementById('P-qty').value;
    // console.log("chk current qty : "+maxRtrnQty);
    // console.log("chk prevReturn qty : "+prevReturnQty);
    let maxRtrnQty = parseInt(currentQty.value) + parseInt(prevReturnQty.value);
    // console.log("chk max return qty : "+maxRtrnQty);
    let reviceTaxable = '';
    let reviceRefund = '';
    let reviceDiscAmt = '';

    if (returnQty != '') {

       
        if (parseFloat(returnQty) < 0) {
            // alert("Return Quantity must be lesser than current quantity!");
            Swal.fire("Error", "Enter valid qantity", "error");
            document.getElementById('return-qty').value = '';
        }
        else if (parseFloat(returnQty) > parseFloat(maxRtrnQty)) {
            // alert("Return Quantity must be lesser than current quantity!");
            Swal.fire("Error", "Return quantity must be lesser or equals to current available Qty! This item current max return qty is "+maxRtrnQty, "error");
            document.getElementById('return-qty').value = '';
        }

        else if (parseFloat(returnQty) >= 0) {
            // if(unitType == 'Tablets' || unitType == 'Capsules')
            if (allowedUnits.map(unit => unit.toLowerCase()).includes(unitType.toLowerCase())){

                console.log("disc parcent : "+disc);
                // console.log("ptr : "+disc);
                console.log("mrp : "+mrp);
                console.log("gst : "+gst);
                console.log("item weatage : "+itemWeatage);
                
                reviceDiscAmt = (parseFloat(mrp) - (parseFloat(mrp)*parseFloat(disc)/100)) / parseInt(itemWeatage);
                console.log("disc amount : "+reviceDiscAmt);

                reviceRefund = parseFloat(reviceDiscAmt) * parseInt(returnQty);
                console.log("refund amount : "+reviceRefund);

                reviceTaxable = (parseFloat(reviceRefund) * 100) / (parseFloat(gst) + 100);
                console.log("taxable amount : "+reviceTaxable);

                reviceRefund = parseFloat(reviceRefund).toFixed(2);
                reviceTaxable = parseFloat(reviceTaxable).toFixed(2);

                // document.getElementById('taxable').value = reviceTaxable;
                // document.getElementById('refund').value = reviceRefund;
                 
                // document.getElementById("add-btn").disabled = false; 
            }else{
                reviceDiscAmt = (parseFloat(mrp) - (parseFloat(mrp)*parseFloat(disc)/100));
                reviceRefund = parseFloat(reviceDiscAmt) * parseInt(returnQty);
                reviceTaxable = (parseFloat(reviceRefund) * 100) / (parseFloat(gst) + 100);

                reviceRefund = parseFloat(reviceRefund).toFixed(2);
                reviceTaxable = parseFloat(reviceTaxable).toFixed(2);

                // document.getElementById('taxable').value = reviceTaxable;
                // document.getElementById('refund').value = reviceRefund;
                
                // document.getElementById("add-btn").disabled = false;
            }
                
        } else if (parseFloat(returnQty) == 0) {
            
            // if(unitType == 'Tablets' || unitType == 'Capsules')
            if (allowedUnits.map(unit => unit.toLowerCase()).includes(unitType.toLowerCase())){
                
                reviceDiscAmt = (parseFloat(mrp) - (parseFloat(mrp)*parseFloat(disc)/100)) / parseInt(itemWeatage);
                reviceRefund = parseFloat(reviceDiscAmt) * parseInt(returnQty);
                reviceTaxable = (parseFloat(reviceRefund) * 100) / (parseFloat(gst) + 100);

                reviceRefund = parseFloat(reviceRefund).toFixed(2);
                reviceTaxable = parseFloat(reviceTaxable).toFixed(2);

                // document.getElementById('taxable').value = reviceTaxable;
                // document.getElementById('refund').value = reviceRefund;
                
                // document.getElementById("add-btn").disabled = false;  
            }else{
                reviceDiscAmt = (parseFloat(mrp) - (parseFloat(mrp)*parseFloat(disc)/100));
                reviceRefund = parseFloat(reviceDiscAmt) * parseInt(returnQty);
                reviceTaxable = (parseFloat(reviceRefund) * 100) / (parseFloat(gst) + 100);

                reviceRefund = parseFloat(reviceRefund).toFixed(2);
                reviceTaxable = parseFloat(reviceTaxable).toFixed(2);

                // document.getElementById('taxable').value = reviceTaxable;
                // document.getElementById('refund').value = reviceRefund;
                
                // document.getElementById("add-btn").disabled = false;
            }
            
        } else {
            reviceRefund = '';
            // swal("Inserted value might be grater than sold qty.");
        }
    } else {
        // swal("Return Quantity can not be blank.");
        reviceRefund = '';
        
    }
    document.getElementById('taxable').value = reviceTaxable;
    document.getElementById('refund').value = reviceRefund;
    //================checking return quantity is not exceded than purchase quantity==================

}



// ##################################################################################
// ##################################################################################

//geeting bills by clicking on add button
const addData = () => {

    let returnDate = document.getElementById('return-date').value;

    if (patientName.value == "") {
        patientName.focus();
        return;
    }

    if (returnDate == "") {
        Swal.fire("Failed!", "Select Return Date", "info");
        return;
    }

    if (billDate.value == "") {
        Swal.fire("Failed!", "Bill date not found!", "error");
        billDate.focus();
        return;
    }

    if (reffBy.value == "") {
        Swal.fire("Failed!", "Doctor name not found!", "error");
        reffBy.focus();
        return;
    }

    if (refundMode.value == "") {
        Swal.fire("Failed!", "Select Refund Mode", "error");
        refundMode.focus();
        return;
    }

    if (itemList.value == "") {
        itemList.focus();
        return;
    } 

    if(returnDetailsitemid.value == ""){
        returnDetailsitemid.focus();
        return;
    }

    if (expDate.value == "") {
        expDate.focus();
        return;
    }

    if (unit.value == "") {
        unit.focus();
        return;
    }

    if(prodId.value == ""){
        prodId.focus();
        return;
    }

    if (batch.value == "") {
        batch.focus();
        return;
    }

    if (mrp.value == "") {
        mrp.focus();
        return;
    }

    if (pqty.value == "") {
        pqty.focus();
        return;
    }

    if(currentQty.value = ""){
        currentQty.focus();
        return;
    }

    if (rtnqty.value == "") {
        rtnqty.focus();
        return;
    }

    if (discount.value == "") {
        discount.focus();
        return;
    }

    if (gst.value == "") {
        gst.focus();
        return;
    }

    if (taxable.value == "") {
        taxable.focus();
        return;
    }

    if (billAmount.value == "") {
        billAmount.focus();
        return;
    }

    
    let existsItems = document.querySelectorAll('tr');
    for (let i = 0; i < existsItems.length; i++) {
        if (i > 0) {

            const item = existsItems[i];
            if (item.childNodes[5].childNodes[3].value == itemList.value) {
                swal("You can not add same item more than once!");

                CurrentStockItemId.value = '';
                returnDetailsitemid.value = "";
                expDate.value = "";
                unit.value = "";
                unitType.value = "";
                itemWeatage.value = "";
                prodId.value = "";
                batch.value = "";
                mrp.value = "";
                pqty.value = "";
                currentQty.value = "";
                rtnqty.value = "";
                discount.value = "";
                gst.value = "";
                taxable.value = "";
                billAmount.value = "";

                return;
            }
        }

    }


    ///////// SL CONTROL \\\\\\\\
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

    //get total Refund Amount
    var refundAmount = document.getElementById("refund-amount");
    let totalRefund = parseFloat(refundAmount.value) + parseFloat(refund.value);
    refundAmount.value = totalRefund.toFixed(2);

    //get total item qty
    var totalQty = document.getElementById("total-qty");
    let totalQtyOnEdit = parseInt(totalQty.value) + parseFloat(rtnqty.value);
    totalQty.value = totalQtyOnEdit;


    // generate gstamount per item and store 
    let gstPerItem = parseFloat(billAmount.value) - parseFloat(taxable.value);
    let gstAmount = document.getElementById("gst-amount");
    let totalGstAmount = parseFloat(gstAmount.value) + parseFloat(gstPerItem);
    let taxableAmnt = gstPerItem.toFixed(2);
    gstAmount.value = totalGstAmount.toFixed(2);


    const appendData = () => {

        jQuery("#dataBody")
            .append(`<tr id="table-row-${slControl}">
            <td><i class="fas fa-trash text-danger" onclick="deleteData(${slControl}, ${parseFloat(rtnqty.value)}, ${gstPerItem}, ${parseFloat(refund.value)})"></i></td>

            <td class="pt-3" id="row-${slControl}-col-1">${slno}</td>
            
            <td class="pt-3" id="row-${slControl}-col-2">
                <input class="table-data w-12r" type="text" value="${itemName}" readonly style="font-size: 0.7rem;">
                <input class="d-none" type="text" name="productId[]" value="${prodId.value}">
            </td>

            <td class="d-none pt-3">
                <input class="table-data w-5r" type="text" name="return-Item-Id[]" value="${returnDetailsitemid.value}" readonly style="font-size: 0.7rem;">
            </td>

            <td class="pt-3" id="row-${slControl}-col-3">
                <input class="table-data w-6r" type="text" name="batchNo[]" value="${batch.value}" readonly style="font-size: 0.7rem;">
            </td>

            <td class="pt-3" id="row-${slControl}-col-4">
                <input class="table-data w-3r" type="text" name="expDate[]" value="${expDate.value}" readonly style="font-size: 0.7rem;">
            </td>

            <td class="pt-3" id="row-${slControl}-col-5">
                <input class="table-data w-3r" type="text" name="setof[]" value="${unit.value}" readonly style="font-size: 0.7rem;">
            </td>

            <td class="pt-3" id="row-${slControl}-col-6">
                <input class="table-data w-2r" type="text" name="p-qty[]" value="${pqty.value}" readonly style="font-size: 0.7rem;">
            </td>

            <td class="d-none pt-3" id="row-${slControl}-col-6">
                <input class="table-data w-2r" type="text" name="current-qty[]" value="${currentQty.value}" readonly style="font-size: 0.7rem;">
            </td>
            
            <td class="pt-3" id="row-${slControl}-col-7">
                <input class="table-data w-3r" type="text" name="mrp[]" value="${mrp.value}" readonly style="font-size: 0.7rem;">
            </td>

            <td class="pt-3" id="row-${slControl}-col-8">
                <input class="table-data w-2r" type="text" name="disc[]" value="${discount.value}%" readonly style="font-size: 0.7rem;">
            </td>

            <td class="pt-3" id="row-${slControl}-col-9">
                <input class="table-data w-2r" type="text" name="gst[]" value="${gst.value}%" readonly style="font-size: 0.7rem;">
            </td>

            <td class="pt-3" id="row-${slControl}-col-10">
                <input class="table-data w-3r" type="text" name="taxable[]" value="${taxable.value}"  style="font-size: 0.7rem;">
            </td>

            <td class="pt-3" id="row-${slControl}-col-11">
                <input class="table-data w-3r" type="text" name="return[]" value="${rtnqty.value}" readonly style="font-size: 0.7rem;">
            </td>
            
            <td class="pt-3" id="row-${slControl}-col-12">
                <input class="table-data w-4r" type="any" name="refund[]" value="${billAmount.value}" readonly style="font-size: 0.7rem;">
            </td>
        </tr>`);
        return true;
    };

    if (appendData() == true) {
        itemList.remove(itemList.selectedIndex)
        itemList.options[0].selected = true;
        
        const dataTuple = {
            slno: slControl,
            invoiceNo: invoiceID,
            salesReturnId : salesReturnId,
            // ProductName: itemName,
            returnDetailsitemid: returnDetailsitemid.value,
            CurrentStockItemId: CurrentStockItemId.value,
            pId: prodId.value,
            prodName: itemName,
            batch: batch.value,
            expDate: expDate.value,
            unit: unit.value,
            unitType: unitType.value,
            itemWeatage: itemWeatage.value,

            mrp: mrp.value,
            purchaseQuantity: pqty.value,
            currentQty: currentQty.value,
            rtnqty: rtnqty.value,

            discount: discount.value,
            gst: gst.value,
            taxable: taxable.value,
            billAmount: billAmount.value,

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

    document.getElementById("sales-return-edit-item-details").reset();
    event.preventDefault();

    returnSubmitBtn.removeAttribute("disabled");

}//eof addData  


// ------------ edit item function ------------

const editItem = (tuple) => {
    // returnSubmitBtn.setAttribute("disabled", "true");
    // console.log(tuple);
    if (document.getElementById('sales-return-details-item-id').value == '') {
        tData = JSON.parse(tuple);
        
        let editData = document.createElement("option");

        editData.setAttribute("data-invoice", tData.invoiceNo);
        editData.setAttribute("sales-return-id", tData.salesReturnId);
        editData.setAttribute("value", tData.pId);
        editData.setAttribute("returned-item-id", tData.returnDetailsitemid);
        editData.setAttribute("current-stock-item-id", tData.CurrentStockItemId);
        editData.text = tData.prodName;

        itemList.appendChild(editData);

        // -----------------------------------------------------------------------------
        let gstPerItem = parseFloat(tData.billAmount) - parseFloat(tData.taxable);
        gstPerItem = parseFloat(gstPerItem).toFixed(2);

        deleteData(tData.slno, tData.rtnqty, gstPerItem, tData.billAmount);
        
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

    //minus item
    let items = document.getElementById("total-items");
    let finalItem = items.value - 1;
    items.value = finalItem;

    // minus quantity
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

// ==================== BACK TO MAIN PAGE (GO BACK) ====================
 