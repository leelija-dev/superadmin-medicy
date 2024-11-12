// const  xmlhttp = new XMLHttpRequest();
// ----------------------------------------------------------------------------------
// data fields
const manufId = document.getElementById("manufacturer-id");
const manufName = document.getElementById("manufacturer-name");
const medPower = document.getElementById("medicine-power");
const weatage = document.getElementById("weightage");
const unit = document.getElementById("unit");

//-----------------------------------------------------------------------------------

const distributorInput = document.getElementById("distributor-id");
const dropdown = document.querySelector(".c-dropdown");
const list = document.querySelector('.lists');
const distIdInput = document.getElementById("dist-id");
const distNameInput = document.getElementById("dist-name");

// ============================================================
const productSelect = document.getElementById('product-select');
const confirmProductName = document.getElementById('cnf-product-name');

// =============================================================
// Show dropdown on focus
distributorInput.addEventListener("focus", () => {
    dropdown.style.display = "block";
});

// Hide dropdown on click outside
document.addEventListener("click", (event) => {
    if (!distributorInput.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = "none";
    }
});

// Hide dropdown on blur with a delay
distributorInput.addEventListener("blur", (event) => {
    setTimeout(() => {
        if (!dropdown.contains(document.activeElement)) {
            dropdown.style.display = "none";
        }
    }, 100);
});

// Update dropdown list based on input value
distributorInput.addEventListener("keyup", async () => {
    const query = distributorInput.value;
    let distributorURL = 'ajax/distributor.list-view.ajax.php?match=' + (query.length > 2 ? query : 'all');

    try {
        const response = await fetch(distributorURL);
        const data = await response.text();
        list.innerHTML = data;
    } catch (error) {
        console.error("Error fetching distributors: ", error);
        list.innerHTML = '';
    }
});

// Set distributor details
const setDistributor = (t) => {
    // console.log(t)
    var hasExecuted = false;
    const distributirId = t.id.trim();
    const distributirName = t.querySelector('span').textContent.trim();

    distIdInput.value = distributirId;
    distNameInput.value = distributirName;
    distributorInput.value = distributirName;
    hasExecuted = true;

    if (hasExecuted) {
        dropdown.style.display = "none";
    }

};

// Add new distributor
const addDistributor = async () => {
    const parentLocation = window.location.origin + window.location.pathname;

    try {
        const response = await fetch("components/distributor-add.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ urlData: parentLocation })
        });
        const data = await response.text();
        document.querySelector('.add-distributor').innerHTML = data;
    } catch (error) {
        console.error("Error adding distributor: ", error);
    }
};


// function captureCurrentLocation() {
//     // Get the current URL
//     var currentLocation = window.location.href;
// }



//////////////////// set distributor bill no /////////////////////

const setDistBillNo = (t) => {
    let val = t.value.toUpperCase();
    document.getElementById("distBill-no").value = val;
}

//////////////////// set bill date due date \\\\\\\\\\\\\\\\\\\\
// Set current date for bill date and due date
const todayDate = new Date();
const formatDate = (date) => date.toISOString().slice(0, 10);


// Initialize bill date and due date
const billDateInput = document.getElementById('bill-date');
const dueDateInput = document.getElementById('due-date');
const billDateValInput = document.getElementById('bill-date-val');
const dueDateValInput = document.getElementById('due-date-val');

// Set default values
billDateInput.value = formatDate(todayDate);
billDateValInput.value = formatDate(todayDate);

// Set due date to be the current date
dueDateInput.value = formatDate(todayDate);
dueDateValInput.value = formatDate(todayDate);

// Set the maximum bill date to today
billDateInput.setAttribute("max", formatDate(todayDate));

// Update bill date and due date when bill date changes
const updateDates = () => {
    const billDate = new Date(billDateInput.value);
    const maxDueDate = new Date(billDate);
    maxDueDate.setDate(billDate.getDate() + 10);

    // Update due date constraints
    dueDateInput.setAttribute("min", billDateInput.value);
    dueDateInput.setAttribute("max", formatDate(maxDueDate));

    // Set due date value to a date within the allowed range if necessary
    if (new Date(dueDateInput.value) < billDate) {
        dueDateInput.value = formatDate(billDate);
        dueDateValInput.value = formatDate(billDate);
    }
}

// Set event listeners
billDateInput.addEventListener('change', () => {
    billDateValInput.value = billDateInput.value;
    updateDates();
});

dueDateInput.addEventListener('change', () => {
    dueDateValInput.value = dueDateInput.value;
});
updateDates();
// =========== eof bill date due date control =============


/////////////////////// SET PAYMENT MODE \\\\\\\\\\\\\\\\\\\\\\
document.getElementById('payment-mode').value = 'Cash';
document.getElementById("payment-mode-val").value = 'Cash';

const setPaymentMode = (pMode) => {
    document.getElementById("payment-mode-val").value = pMode.value;
}
// ================= eof payment mode control ===================


///////// QANTITY AND FREE QANTITY VALUE CONTROL //////////
const Qty = document.getElementById('qty');
Qty.addEventListener('input', function (event) {
    this.value = this.value.replace('.', '');
});

const FreeQty = document.getElementById('free-qty');
FreeQty.addEventListener('input', function (event) {
    this.value = this.value.replace('.', '');
});

//////// batch number input contorl \\\\\\\\\\
const batchNumber = document.getElementById('batch-no');
batchNumber.addEventListener('input', function (event) {
    this.value = this.value.replace('.', '');
    this.value = this.value.replace('*', '');
});

//////// distributo bill input contorl \\\\\\\\\\
const distBillNo = document.getElementById('dist-bill-no');
distBillNo.addEventListener('input', function (event) {
    this.value = this.value.replace('.', '');
    this.value = this.value.replace('*', '');
});
//================================\\

///////////////////////////////////////////////////////////

//=============== stock-in save button control(active/inactive) ==============
var stockInSave = document.getElementById('stock-in-submit');
stockInSave.setAttribute("disabled", "true");
const chekForm = () => {
    var tableBody = document.getElementById('dataBody');
    if (document.getElementById('product-name').value == '' && tableBody.getElementsByTagName('tr').length > 0) {
        stockInSave.removeAttribute("disabled");
    } else {
        stockInSave.setAttribute("disabled", "true");
    }
}
//==========================================================

////////////////////// set coursor as pointer on table row hover /////////////////////
///////////////////////////////////////////////////////////
const firstInput = document.getElementById('product-name');

firstInput.addEventListener('input', function (event) {
    // Get the input value
    const inputValue = this.value;

    // Check if the first character is a space
    if (inputValue.length > 0 && inputValue[0] === ' ') {
        this.value = inputValue.slice(1);
    }
});


//////////////////////////// ITEM SEARCH START ////////////////////////////////
function searchItem(input) {
    const stockInDataForm = document.getElementById("stock-in-data");
    const checkLength = input.length;

    if (input === "") {
        productSelect.style.display = "none";
        stockInDataForm.reset();
        return;
    }

    if (checkLength > 2) {
        productSelect.style.display = "block";
    }

    fetch('ajax/purchase-item-list.ajax.php?data=' + encodeURIComponent(input))
        .then(response => {
            if (response.ok) {
                return response.text();
            }
            throw new Error('Network response was not ok');
        })
        .then(data => {
            productSelect.innerHTML = data;
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}




const getDetails = (productId, prodReqStatus, oldProdReqStatus, edtiRequestFlag) => {
    // console.log(productId +' 1. '+ prodReqStatus +' 2. '+ oldProdReqStatus +' 3. '+ edtiRequestFlag);
    if (productId != "") {
        //==================== edit request flag ====================
        document.getElementById('edit-request-flag').value = edtiRequestFlag;
        //==================== Manufacturere List ====================
        manufacturerurl = `ajax/product.getManufacturer.ajax.php?id=${productId}&prodReqStatus=${prodReqStatus}&oldProdReqStatus=${oldProdReqStatus}&edtiRequestFlag=${edtiRequestFlag}`;
        xmlhttp.open("GET", manufacturerurl, false);
        xmlhttp.send(null);
        manufId.value = xmlhttp.responseText;

        manufacturerName = `ajax/product.getManufacturer.ajax.php?name=${productId}&prodReqStatus=${prodReqStatus}&oldProdReqStatus=${oldProdReqStatus}&edtiRequestFlag=${edtiRequestFlag}`;
        xmlhttp.open("GET", manufacturerName, false);
        xmlhttp.send(null);
        manufName.value = xmlhttp.responseText;

        //==================== Medicine Power ====================
        powerurl = `ajax/product.getMedicineDetails.ajax.php?power=${productId}&prodReqStatus=${prodReqStatus}&oldProdReqStatus=${oldProdReqStatus}&edtiRequestFlag=${edtiRequestFlag}`;
        xmlhttp.open("GET", powerurl, false);
        xmlhttp.send(null);
        medPower.value = xmlhttp.responseText;

        //==================== Weightage ====================
        weightage = `ajax/product.getMedicineDetails.ajax.php?weightage=${productId}&prodReqStatus=${prodReqStatus}&oldProdReqStatus=${oldProdReqStatus}&edtiRequestFlag=${edtiRequestFlag}`;
        xmlhttp.open("GET", weightage, false);
        xmlhttp.send(null);
        weatage.value = xmlhttp.responseText;


        //==================== Unit ====================
        unitUrl = `ajax/product.getMedicineDetails.ajax.php?unit=${productId}&prodReqStatus=${prodReqStatus}&oldProdReqStatus=${oldProdReqStatus}&edtiRequestFlag=${edtiRequestFlag}`;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        unit.value = xmlhttp.responseText;

        //==================== MRP ====================
        mrpUrl = `ajax/product.getMrp.ajax.php?id=${productId}&prodReqStatus=${prodReqStatus}&oldProdReqStatus=${oldProdReqStatus}&edtiRequestFlag=${edtiRequestFlag}`;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        document.getElementById("mrp").value = xmlhttp.responseText;
        // console.log(xmlhttp.responseText);
        

        let pTr = parseFloat(xmlhttp.responseText);
        pTr = pTr.toFixed(2);
        document.getElementById("ptr").value = pTr;
        // console.log(pTr)
        // //==================== ptr check url ===================
        chkPtr = `ajax/product.getMrp.ajax.php?ptrChk=${productId}&prodReqStatus=${prodReqStatus}&oldProdReqStatus=${oldProdReqStatus}&edtiRequestFlag=${edtiRequestFlag}`;
        xmlhttp.open("GET", chkPtr, false);
        xmlhttp.send(null);
        // console.log(xmlhttp.responseText);
        document.getElementById("ptr").value = xmlhttp.responseText;
        
        //==================== GST ====================
        gstUrl = `ajax/product.getGst.ajax.php?id=${productId}&prodReqStatus=${prodReqStatus}&oldProdReqStatus=${oldProdReqStatus}&edtiRequestFlag=${edtiRequestFlag}`;
        xmlhttp.open("GET", gstUrl, false);
        xmlhttp.send(null);
        
        if (xmlhttp.responseText != ' ' || xmlhttp.responseText != null) {
            document.getElementById("gst").value = xmlhttp.responseText;
            document.getElementById("gst-check").value = xmlhttp.responseText;
        }

        //==================== Product Id ====================
        document.getElementById("product-id").value = productId;

        //==================== Product Name ====================
        nameUrl = `ajax/product.getMedicineDetails.ajax.php?pName=${productId}&prodReqStatus=${prodReqStatus}&oldProdReqStatus=${oldProdReqStatus}&edtiRequestFlag=${edtiRequestFlag}`;
        xmlhttp.open("GET", nameUrl, false);
        xmlhttp.send(null);
        document.getElementById("product-name").value = xmlhttp.responseText;
        document.getElementById('batch-no').focus();
        stockInSave.setAttribute("disabled", "true");

        confirmProductName.value = xmlhttp.responseText;
    } else {
        document.getElementById("manufacturer-id").innerHTML = "";
        document.getElementById("medicine-power").value = "";
        document.getElementById("weightage").value = "";
        document.getElementById("unit").value = "";
        document.getElementById("mrp").value = "";
        document.getElementById("gst").value = "";
        document.getElementById("product-id").value = "";
        document.getElementById("product-name").value = "";
        confirmProductName.value = '';
    }
    document.getElementById("product-select").style.display = "none";
}





//==================== INPUT FUILD BLOCK FUNCTION ==================================
// ===== ON QTY ========
let QtyInput = document.getElementById('qty');
QtyInput.addEventListener('keydown', function (event) {
    if (event.keyCode === 9) {
        if (QtyInput.value.trim() === '' || QtyInput.value.trim() === 0) {
            event.preventDefault();
        }
    }
});

// ========= ON FREE QTY ==========
let FreeQtyInput = document.getElementById('free-qty');
FreeQtyInput.addEventListener('keydown', function (event) {
    if (event.keyCode === 9) {
        if (FreeQtyInput.value.trim() === '') {
            event.preventDefault();
        }
    }
});

// ========= ON DISCOUNT ==========
let DiscPercentInput = document.getElementById('discount');
DiscPercentInput.addEventListener('keydown', function (event) {
    if (event.keyCode === 9) {
        if (DiscPercentInput.value.trim() === '') {
            event.preventDefault();
        }
    }
});

// ========= ON BATCH NUMBER ==========
let BatchNoInput = document.getElementById('batch-no');
BatchNoInput.addEventListener('keydown', function (event) {
    if (event.keyCode === 9) {
        if (BatchNoInput.value.trim() === '') {
            event.preventDefault();
        }
    }
});

// ========= ON PTR ==========
let ptrInput = document.getElementById('ptr');
ptrInput.addEventListener('keydown', function (event) {
    if (event.keyCode === 9) {
        if (ptrInput.value.trim() === '') {
            event.preventDefault();
        }
    }
});




const getBillAmount = () => {
    let mrp = parseFloat(document.getElementById('mrp').value);
    let ptr = parseFloat(document.getElementById('ptr').value);
    let gst = parseInt(document.getElementById('gst').value);
    let prevGst = parseInt(document.getElementById("gst-check").value);
    let qty = parseInt(document.getElementById('qty').value);
    if (isNaN(qty)) {
        qty = 0;
    }
    
    let disc = parseFloat(document.getElementById('discount').value);
    if (isNaN(disc)) {
        disc = 0;
    }

    let maxPtr = (parseFloat(mrp) * 100) / (parseInt(gst) + 100);
    maxPtr = maxPtr.toFixed(2);
    maxPtr = parseFloat(maxPtr);

    if (gst != prevGst) {
        if(ptr > maxPtr){
            document.getElementById('ptr').value = maxPtr;
            document.getElementById("gst-check").value = gst;
        }
    }

    if (ptr > maxPtr) {
        Swal.fire({
            title: "Error Input",
            text: "PTR must be lower than MRP with GST Calculated Price!",
            icon: "error",
        });

        document.getElementById("ptr").value = maxPtr;
        maxPtr = maxPtr;
        document.getElementById("bill-amount").value = " ";
        document.getElementById("ptr").focus();
    }

    let modifiedPtr = document.getElementById("ptr").value;
    
    let dprice = (parseFloat(modifiedPtr) - (parseFloat(modifiedPtr) * (parseFloat(disc) / 100))).toFixed(2);
    let totalAmount = ((parseFloat(dprice) + (parseFloat(dprice) * (parseFloat(gst) / 100))) * parseInt(qty)).toFixed(2);

    document.getElementById("dprice").value = dprice;
    document.getElementById("bill-amount").value = totalAmount;

    //=============================================
    //======= UPDATE GST ON PRODUCT SECTION =======
    let prodId = document.getElementById("product-id").value;

    $.ajax({
        url: 'ajax/update-product-gst.ajax.php',
        type: 'POST',
        data: {
            gstPercent: gst,
            prodId: prodId
        },
        success: function (response) {
            if(!response){
                console.log(response);
            }
        },
        error: function (error) {
            console.error('Error :', error);
        }
    });
    
} //eof getBillAmount function

// ##################################################################################
// ##################################################################################

//geeting bills by clicking on add button
const addData = () => {
    productSelect.style.display = 'none';
    // alert('Clicked');
    let editRequestFlag = document.getElementById('edit-request-flag');
    let distId = document.getElementById("distributor-id");
    let distBillid = document.getElementById("dist-bill-no");

    let billDate = document.getElementById("bill-date");
    let dueDate = document.getElementById("due-date");
    let paymentMode = document.getElementById("payment-mode");

    let productName = document.getElementById("product-name");
    let productId = document.getElementById("product-id");
    let batch = document.getElementById("batch-no");
    let batchNo = batch.value.toUpperCase();
    let manufId = document.getElementById("manufacturer-id");
    let manufName = document.getElementById("manufacturer-name");
    // let medicinePower = document.getElementById("medicine-power");
    let expMonth = document.getElementById("exp-month");
    let expYear = document.getElementById("exp-year");
    let expDate = `${expMonth.value}/${expYear.value}`;
    expDate = expDate.toString();
    let weightage = document.getElementById("weightage");
    let unit = document.getElementById("unit");

    let mrp = document.getElementById("mrp");
    let ptr = document.getElementById("ptr");
    let qty = document.getElementById("qty");
    let freeQty = document.getElementById("free-qty");
    let discount = document.getElementById("discount");
    let gst = document.getElementById("gst");
    let dprice = document.getElementById("dprice");
    
    let billAmount = document.getElementById("bill-amount");

    if (distId.value == "") {
        Swal.fire("Blank Field", "Please Selet Distributor First!", "error")
            .then((value) => {
                distId.focus();
            });
        return;
    }

    if (distBillid.value == "") {
        Swal.fire("Blank Field", "Please Enter Distributor Bill Number!", "error")
            .then((value) => {
                distBillid.focus();
            });
        return;
    }


    if (billDate.value == "") {
        Swal.fire("Blank Field", "Please Select Bill Date!", "error")
            .then((value) => {
                billDate.focus();
            });
        return;
    }

    if (dueDate.value == "") {
        Swal.fire("Blank Field", "Please Select Bill Payment Date!", "error")
            .then((value) => {
                dueDate.focus();
            });
        return;
    }

    if (paymentMode.value == "") {
        Swal.fire("Blank Field", "Please Select Payment Mode!", "error")
            .then((value) => {
                paymentMode.focus();
            });
        return;
    }
    if (productName.value == "") {
        Swal.fire("Blank Field", "Please Search & Select Product!", "error")
            .then((value) => {
                productName.focus();
            });
        return;
    }
    if (batch.value == "") {
        Swal.fire("Blank Field", "Please Enter Product Batch Number!", "error")
            .then((value) => {
                batch.focus();
            });
        return;
    }


    if (expMonth.value == "") {
        Swal.fire("Blank Field", "Please Enter Expiry Date as MM/YY", "error")
            .then((value) => {
                expMonth.focus();
            });
        return;
    }
    if (weightage.value == "") {
        weightage.focus();
        return;
    }

   
    if (mrp.value == "") {
        mrp.focus();
        return;
    }

    if (ptr.value == "") {
        Swal.fire("Blank Field", "Please enter PTR value", "error")
            .then((value) => {
                ptr.focus();
            });
        return;
    }

    var Ptr = parseFloat(ptr.value);
    var Mrp = parseFloat(mrp.value);

    if (Ptr > Mrp) {
        Swal.fire("Blank Field", "Please check PTR value", "error")
            .then((value) => {
                ptr.focus();
            });
        return;
    }
    if (qty.value == "" || qty.value == 0) {
        Swal.fire("Blank Field",
            "Please Enter Quantity",
            "error")
            .then((value) => {
                qty.focus();
            });
        return;
    }
    if (freeQty.value == "") {
        Swal.fire("Free Qantity value is null",
            "Free Qantity Cannot be null. Minimum value 0",
            "error")
            .then((value) => {
                freeQty.focus();
            });
        return;
    }
    if (discount.value == "") {
        Swal.fire("Blank Field",
            "Please Enter Discount at least 0",
            "error")
            .then((value) => {
                discount.focus();
            });
        return;
    }
    if (gst.value == "") {
        Swal.fire("Blank Field",
            "GST should be a number",
            "error")
            .then((value) => {
                gst.focus();
            });
        return;
    }
    if (dprice.value == "") {
        Swal.fire("Blank Field",
            "Discounted Price Amount can not be blank",
            "error")
            .then((value) => {
                dprice.focus();
            });
        return;
    }
    if (billAmount.value == "") {
        Swal.fire("Blank Field",
            "Bil Amount can nit be blank",
            "error")
            .then((value) => {
                billAmount.focus();
            });
        return;
    }

    /////// item serial add \\\\\\\\\\\\\\\
    let slno = document.getElementById("dynamic-id").value;
    let slControl = document.getElementById("serial-control").value;
    slno++;
    slControl++;
    document.getElementById("dynamic-id").value = slno;
    document.getElementById("serial-control").value = slControl;

    ////////////// item qty calculateion \\\\\\\\\\\\\
    var qtyVal = document.getElementById("qty-val").value;
    let itemQty = parseFloat(qty.value) + parseFloat(freeQty.value);
    totalQty = parseFloat(qtyVal) + itemQty;

    //////////// net amout calculation \\\\\\\\\\\\\\\\\
    var net = document.getElementById("net-amount").value;
    netAmount = parseFloat(net) + parseFloat(billAmount.value);

    ///////// gst amount calculation \\\\\\\\\\\\\\\\\\\\\
    let withoutGstAmount = parseFloat(dprice.value) * parseInt(qty.value);
    let gstPerItem = (parseFloat(billAmount.value) - parseFloat(withoutGstAmount)).toFixed(2);
    
    let gstVal = document.getElementById("gst-val").value;
    let onlyGst = parseFloat(gstVal) + parseFloat(gstPerItem);


    //////////////////////
    let totalMrp = parseFloat(mrp.value) * ((parseFloat(qty.value) + parseFloat(freeQty.value)));
    let payble = (((parseFloat(dprice.value)) + (parseFloat(dprice.value) * (parseInt(gst.value) / 100))) * parseInt(qty.value)).toFixed(2);

    jQuery("#dataBody")
        .append(`<tr id="table-row-${slControl}" style="cursor: pointer; text-align: right;">
            <td style="color: red; padding-top:1.2rem; width:1rem"> <i class="fas fa-trash" onclick="deleteData(${slControl}, ${itemQty}, ${gstPerItem}, ${billAmount.value})" style="font-size:.7rem;"></i></td>

            <td class="p-0 pt-3 w-1r" id="row-${slControl}-col-2" style="font-size:.7rem; padding-top:1.2rem; width: 1rem" scope="row">${slno}</td>

            <td class="w-10r text-left" id="row-${slControl}-col-3">
                <input class="table-data text-left w-10r" type="text" value="${confirmProductName.value}" style="word-wrap: break-word; font-size: .7rem;" readonly>
                <input type="text" name="productId[]" value="${productId.value}" style="display: none">
                <br>
                <input class="table-data" type="text" name="setof[]" value="${weightage.value}${unit.value}" readonly style="width: 3rem; font-size: .7rem;">
                <input class="table-data line-inp50" type="text" name="weightage[]" value="${weightage.value}" style="display: none" hidden>
                <input class="table-data line-inp50" type="text" name="unit[]" value="${unit.value}" style="display: none" hidden>

                <input class="d-none table-data w-2r"  type="text" name="discount[]" value="${discount.value}%" style="font-size: .7rem; text-align: end">

            </td>

            <td class="" id="row-${slControl}-col-4">
                <input class="table-data text-right w-5r" type="text" name="batchNo[]" value="${batchNo}" readonly style="font-size: .7rem;">
            </td>

            <td class="" id="row-${slControl}-col-6">
                <input class="table-data text-right w-3r" type="text" name="expDate[]" value="${expDate}" readonly style="font-size: .7rem;">
            </td>
            <td class="" id="row-${slControl}-col-8">
                <input class="table-data text-right w-2r" type="text" name="qty[]" value="${qty.value}" readonly style="font-size: .7rem; text-align: end">
            </td>
            <td class="" id="row-${slControl}-col-9">
                <input class="table-data text-right w-2r" type="text" name="freeQty[]" value="${freeQty.value}" readonly style="font-size: .7rem; text-align: end">
            </td>
            <td class="" id="row-${slControl}-col-10">
                <input class="table-data text-right w-3r" type="text" name="mrp[]" value="${mrp.value}" readonly style="font-size: .7rem; text-align: end">
            </td>
            <td class="" id="row-${slControl}-col-11">
                <input class="table-data text-right w-3r" type="text" name="ptr[]" value="${ptr.value}" readonly style="font-size: .7rem; text-align: end">

                <input class="d-none table-data w-3r" type="text" name="chkPtr[]" value="${ptr.value}" readonly style="font-size: .7rem; text-align: end">
            </td>
            <td class="" id="row-${slControl}-col-12a">
                <input type="text" class="table-data text-right w-3r" name="dprice[]" value="${dprice.value}" style="font-size: .7rem; text-align: end;">
                <span class="badge badge-primary">${discount.value}%</span>
            </td>
            
            <td class="" id="row-${slControl}-col-13">
                <input class="table-data text-right w-2r" type="text" name="gst[]" value="${gst.value}%" readonly style="font-size: .7rem; text-align: end">
                <input type="text" name="gstPerItem[]" value="${gstPerItem}" hidden>
            </td class="pt-3" >

            <td class="" id="row-${slControl}-col-14">
                <input class="table-data text-right w-4r amnt-inp" type="text" name="billAmount[]" value="${billAmount.value}" readonly style="padding: 0%; font-size: .7rem; text-align: end;">
            </td>

            <td class="d-none " id="row-${slControl}-col-15">
                <input class="table-data text-right w-4r amnt-inp" type="text" name="edit-req-flag[]" value="${editRequestFlag.value}" readonly style="padding: 0%; font-size: .7rem; text-align: end;">
            </td>editRequestFlag
        </tr>`);


    if (slno > 1) {
        let id = document.getElementById("items-val");
        let newId = parseFloat(id.value) + 1;
        document.getElementById("items-val").value = newId;
    }
    else {
        document.getElementById("items-val").value = slno;
    }

    document.getElementById("qty-val").value = totalQty;
    onlyGst = parseFloat(onlyGst);
    document.getElementById("gst-val").value = onlyGst.toFixed(2);
    document.getElementById("net-amount").value = netAmount.toFixed(2);

    //////////////////////////////////////////////////////////////////////////////////////

    const dataTuple = {
        slno: slControl,
        productId: productId.value,
        productName: confirmProductName.value,
        batchNo: batchNo,
        ManufId: manufId.value,
        manufName: manufName.value,
        expMnth: expMonth.value,
        expYr: expYear.value,
        itemWeightage: weightage.value,
        unitType: unit.value,
        mrp: mrp.value,
        ptr: ptr.value,

        Qty: qty.value,
        freeQty: freeQty.value,
        discPercent: discount.value,
        dprice: dprice.value,
        gst: gst.value,
        amount: billAmount.value,
        editRequestFlag: editRequestFlag.value
    };

    let tupleData = JSON.stringify(dataTuple);

    document.getElementById(`row-${slControl}-col-2`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`row-${slControl}-col-3`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`row-${slControl}-col-4`).onclick = function () {
        editItem(tupleData);
    };
    
    document.getElementById(`row-${slControl}-col-6`).onclick = function () {
        editItem(tupleData);
    };
    // document.getElementById(`row-${slControl}-col-7`).onclick = function () {
    //     editItem(tupleData);
    // };
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
    document.getElementById(`row-${slControl}-col-12a`).onclick = function () {
        editItem(tupleData);
    };
    // document.getElementById(`row-${slControl}-col-12b`).onclick = function () {
    //     editItem(tupleData);
    // };
    // document.getElementById(`row-${slControl}-col-12`).onclick = function () {
    //     editItem(tupleData);
    // };
    document.getElementById(`row-${slControl}-col-13`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`row-${slControl}-col-14`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`row-${slControl}-col-15`).onclick = function () {
        editItem(tupleData);
    };

    ///////////////////////////////////////////////////////////////////////////////////////

    stockInSave.removeAttribute("disabled");

    resetData();
    event.preventDefault();
}

//==============////////////////// ADDED ITEM EDIT FUNCTION \\\\\\\\\\\\\\\\\\=====================

const editItem = (tupleData) => {
    // console.log(tupleData);
    
    let checkFild = document.getElementById("product-id").value;

    if (checkFild == "") {

        let TupleData = JSON.parse(tupleData);

        document.getElementById("product-name").value = TupleData.productName;
        confirmProductName.value = TupleData.productName;
        document.getElementById("product-id").value = TupleData.productId;
        document.getElementById("batch-no").value = TupleData.batchNo;
        document.getElementById("manufacturer-id").value = TupleData.ManufId;
        document.getElementById("manufacturer-name").value = TupleData.manufName;

        document.getElementById("exp-month").value = TupleData.expMnth;
        document.getElementById("exp-year").value = TupleData.expYr;

        document.getElementById("weightage").value = TupleData.itemWeightage;
        document.getElementById("unit").value = TupleData.unitType;
        document.getElementById("mrp").value = TupleData.mrp;
        document.getElementById("ptr").value = TupleData.ptr;

        document.getElementById("qty").value = TupleData.Qty;
        document.getElementById("free-qty").value = TupleData.freeQty;
        document.getElementById("discount").value = TupleData.discPercent;
        document.getElementById("gst").value = TupleData.gst;
        document.getElementById("dprice").value = TupleData.dprice;
        document.getElementById("bill-amount").value = TupleData.amount;
        document.getElementById("edit-request-flag").value = TupleData.editRequestFlag;

        let gstPerItem = parseFloat(TupleData.amount) - (parseFloat(TupleData.dprice) * parseInt(TupleData.Qty));
        gstPerItem = gstPerItem.toFixed(2);

        deleteData(TupleData.slno, parseInt(TupleData.Qty) + parseInt(TupleData.freeQty), gstPerItem, TupleData.amount);

        stockInSave.setAttribute("disabled", "true");
    } else {
        Swal.fire("Can't Edit", "Please add/edit previous item first.", "error");
        document.getElementById("ptr").focus();
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////
// ================================ Delet Data ================================

function deleteData(slno, itemQty, gstPerItem, total) {

    // == tabel row lenth and deleted row number ===
    let delRow = slno;
    //  ============================================

    jQuery(`#table-row-${slno}`).remove();
    let slVal = document.getElementById("dynamic-id").value;
    document.getElementById("dynamic-id").value = parseInt(slVal) - 1;

    //minus item
    let items = document.getElementById("items-val");
    let finalItem = items.value - 1;
    items.value = finalItem;

    // minus quantity
    let qty = document.getElementById("qty-val");
    let finalQty = qty.value - itemQty
    qty.value = finalQty;

    // minus netAmount
    let gst = document.getElementById("gst-val");
    let finalGst = gst.value - gstPerItem;
    gst.value = finalGst.toFixed(2);

    // minus netAmount
    let net = document.getElementById("net-amount");
    let finalAmount = net.value - total;
    net.value = finalAmount.toFixed(2);

    rowAdjustment(delRow);

    let tBody = document.getElementById('dataBody');

    if (tBody.getElementsByTagName('tr').length == 0) {
        stockInSave.setAttribute("disabled", "true");
    }
}


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


// ========================= Mfd and Expiry Date Setting =========================

let expMonthInput = document.getElementById('exp-month');
expMonthInput.addEventListener('keydown', function (event) {
    if (event.keyCode === 9) {
        if (expMonthInput.value.trim() === '') {
            event.preventDefault();
        }
    }
});
expMonthInput.addEventListener('input', function (event) {
    // Remove dots from the input value
    this.value = this.value.replace('.', '');
});


// ===set exp month control
const setexpMonth = (mnth) => {
    if (mnth.value.length != 2) {
        mnth.value = '';
        mnth.focus();
    }
}

const setExpMonth = (month) => {

    if (month.value <= 12) {
        if (month.value.length > 2) {
            month.value = '';
            month.focus();
        } else if (month.value.length < 2) {
            month.focus();
        } else if (month.value.length == 2) {
            if (month.value == 0) {
                month.value = '';
                month.focus();
            } else {
                document.getElementById("exp-year").focus();
            }
        } else {
            month.value = '';
            month.focus();
        }
    } else if (month.value == '') {
        month.focus();
    } else {
        month.value = '';
        month.focus();
    }
}



// ===set exp YEAR control
const setExpYear = (year) => {
    if (year.value.length == 4) {
        document.getElementById("ptr").focus();
    }

    if(year.value.length != 4){
        year.focus();
    }
}


const setExpYEAR = (year) => {
    let expMnth = document.getElementById("exp-month").value;
    expMnth = Number(expMnth);
    let today = new Date();
    var currentDate = new Date();
    let currentMnth = currentDate.getMonth();
    currentMnth += 1;
    // alert(currentMnth);
    let curretnYr = today.getFullYear();

    if (year.value.length == 4) {
        if (year.value < curretnYr) {
            document.getElementById('exp-year').value = '';
            document.getElementById('exp-year').focus();
        }

        if (year.value == curretnYr) {
            if (parseInt(expMnth) < parseInt(currentMnth)) {
                Swal.fire({
                    title: "Error",
                    text: "Enter valid expiry date",
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('exp-month').value = '';
                        document.getElementById('exp-year').value = '';
                        document.getElementById('exp-month').focus();
                    }
                });
            }
        }
    } else {
        document.getElementById('exp-year').value = '';
        document.getElementById('exp-year').focus();
    }

}

///////////////// ===== product select arrow key effect ===== \\\\\\\\\\\\\\\\\\\\\\\


// reset data field =========================
const resetData = () =>{
    document.getElementById("stock-in-data").reset();
    event.preventDefault();
}