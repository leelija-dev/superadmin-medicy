
//////////////////// set distributor name /////////////////////

const distributorInput = document.getElementById("distributor-id");
const dropdown = document.getElementsByClassName("c-dropdown")[0];

distributorInput.addEventListener("focus", () => {
    dropdown.style.display = "block";
});

document.addEventListener("click", (event) => {
    // Check if the clicked element is not the input field or the dropdown
    if (!distributorInput.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = "none";
    }
});

document.addEventListener("blur", (event) => {
    // Check if the element losing focus is not the dropdown or its descendants
    if (!dropdown.contains(event.relatedTarget)) {
        // Delay the hiding to allow the click event to be processed
        setTimeout(() => {
            dropdown.style.display = "none";
        }, 100);
    }
});



distributorInput.addEventListener("keyup", () => {
    // Delay the hiding to allow the click event to be processed
    let list = document.getElementsByClassName('lists')[0];

    if (distributorInput.value.length > 2) {

        let distributorURL = 'ajax/distributor.list-view.ajax.php?match=' + distributorInput.value;
        request.open("GET", distributorURL, false);
        request.send(null);
        // console.log();
        list.innerHTML = request.responseText
    } else if (distributorInput.value == '') {

        let distributorURL = 'ajax/distributor.list-view.ajax.php?match=all';
        request.open("GET", distributorURL, false);
        request.send(null);
        // console.log();
        list.innerHTML = request.responseText
    } else {

        list.innerHTML = '';
    }
});

const setDistributor = (t) => {
    let distributirId = t.id.trim();
    let distributirName = t.innerHTML.trim();

    document.getElementById("dist-id").value = distributirId;
    document.getElementById("dist-name").value = distributirName;
    document.getElementById("distributor-id").value = distributirName;

    document.getElementsByClassName("c-dropdown")[0].style.display = "none";
}


const addDistributor = () => {

    var parentLocation = window.location.origin + window.location.pathname;

    $.ajax({
        url: "components/distributor-add.php",
        type: "POST",
        data: { urlData: parentLocation },
        success: function (response) {
            let body = document.querySelector('.add-distributor');
            body.innerHTML = response;
        },
        error: function (error) {
            console.error("Error: ", error);
        }
    });
}



function captureCurrentLocation() {
    // Get the current URL
    var currentLocation = window.location.href;

    // Log or use the current location as needed
    console.log("Current Location: " + currentLocation);

    // Your additional logic here...
}

//////////////////// set distributor bill no /////////////////////

const setDistBillNo = (t) => {
    let val = t.value.toUpperCase();
    // console.log(val);
    document.getElementById("distBill-no").value = val;
}

//////////////////// set bill date \\\\\\\\\\\\\\\\\\\\
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

// =======  bill date set ===========
const getbillDate = (billDate) => {
    billDate = billDate.value;

    document.getElementById("bill-date-val").value = billDate;

    document.getElementById("due-date").setAttribute("min", billDate);

    var date2 = todayDate.getDate() + 7;
    var todayFullDate2 = year + "-" + month + "-" + date2;
    document.getElementById("due-date").setAttribute("max", todayFullDate2);
}

//////////////////// set due date /////////////////////
const getDueDate = (t) => {
    // console.log(t.value);
    document.getElementById("due-date-val").value = t.value;
}

/////////////////////// SET PAYMENT MODE \\\\\\\\\\\\\\\\\\\\\\
const setPaymentMode = (pMode) => {
    document.getElementById("payment-mode-val").value = pMode.value;
}



//////// QANTITY AND FREE QANTITY VALUE CONTROL //////////
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

//=============== stock in save button control ==============
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
window.addEventListener('load', function () {
    firstInput.focus();
});

firstInput.addEventListener('input', function (event) {
    // Get the input value
    const inputValue = this.value;

    // Check if the first character is a space
    if (inputValue.length > 0 && inputValue[0] === ' ') {
        // Remove the leading space
        this.value = inputValue.slice(1);
    }
});


//////////////////////////// ITEM SEART START ////////////////////////////////
function searchItem(input) {

    let checkLength = input.length;

    let xmlhttp = new XMLHttpRequest();

    let searchReult = document.getElementById('product-select');

    xmlhttp.open('GET', 'ajax/purchase-item-list.ajax.php?data=' + input, true);
    xmlhttp.send();

    if (input == "") {
        document.getElementById("product-select").style.display = "none";
        document.getElementById("stock-in-data").reset();
        event.preventDefault();
    }

    if (checkLength > 2) {
        if (input != "") {
            document.getElementById("product-select").style.display = "block";
        }
    }

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            searchReult.innerHTML = xmlhttp.responseText;
        }
    };

}

const getDtls = (productId) => {

    let xmlhttp = new XMLHttpRequest();

    if (productId != "") {
        // console.log(productId);
        //==================== Manufacturere List ====================
        manufacturerurl = 'ajax/product.getManufacturer.ajax.php?id=' + productId;
        // alert(url);
        xmlhttp.open("GET", manufacturerurl, false);
        xmlhttp.send(null);
        document.getElementById("manufacturer-id").value = xmlhttp.responseText;

        manufacturerName = 'ajax/product.getManufacturer.ajax.php?name=' + productId;
        xmlhttp.open("GET", manufacturerName, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("manufacturer-name").value = xmlhttp.responseText;

        //==================== Medicine Power ====================
        powerurl = 'ajax/product.getMedicineDetails.ajax.php?power=' + productId;
        // alert(url);
        xmlhttp.open("GET", powerurl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("medicine-power").value = xmlhttp.responseText;

        //==================== Packaging Type ====================
        packTypeUrl = 'ajax/product.getMedicineDetails.ajax.php?pType=' + productId;
        // alert(url);
        xmlhttp.open("GET", packTypeUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("packaging-type").innerHTML = xmlhttp.responseText;

        packTypeFieldUrl = 'ajax/product.getMedicineDetails.ajax.php?packegeIn=' + productId;
        // // alert(url);
        xmlhttp.open("GET", packTypeFieldUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("packaging-in").value = xmlhttp.responseText;

        //==================== Weightage ====================
        weightage = 'ajax/product.getMedicineDetails.ajax.php?weightage=' + productId;
        // alert(url);
        xmlhttp.open("GET", weightage, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("weightage").value = xmlhttp.responseText;


        //==================== Unit ====================
        unitUrl = 'ajax/product.getMedicineDetails.ajax.php?unit=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        document.getElementById("unit").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== MRP ====================
        mrpUrl = 'ajax/product.getMrp.ajax.php?id=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        document.getElementById("mrp").value = xmlhttp.responseText;


        let pTr = parseFloat(xmlhttp.responseText);
        pTr = pTr.toFixed(2);
        document.getElementById("ptr").value = pTr;

        // //==================== ptr check url ===================
        chkPtr = 'ajax/product.getMrp.ajax.php?ptrChk=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", chkPtr, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        // document.getElementById("chk-ptr").value = xmlhttp.responseText;
        document.getElementById("ptr").value = xmlhttp.responseText;

        //==================== GST ====================
        gstUrl = 'ajax/product.getGst.ajax.php?id=' + productId;

        xmlhttp.open("GET", gstUrl, false);
        xmlhttp.send(null);

        // console.log("gst check : ",xmlhttp.responseText);

        if (xmlhttp.responseText != ' ' || xmlhttp.responseText != null) {
            document.getElementById("gst").value = xmlhttp.responseText;
            document.getElementById("gst-check").value = xmlhttp.responseText;
        }

        //==================== Product Id ====================
        document.getElementById("product-id").value = productId;

        // idUrl = `ajax/product.getName.ajax.php?Pid=${productId}`
        // // alert(unitUrl);
        // xmlhttp.open("GET", idUrl, false);
        // xmlhttp.send(null);
        // document.getElementById("product-ID").value = xmlhttp.responseText;
        // console.log(xmlhttp.responseText);

        //==================== Product Name ====================
        nameUrl = 'ajax/product.getMedicineDetails.ajax.php?pName=' + productId;
        // alert(unitUrl);
        xmlhttp.open("GET", nameUrl, false);
        xmlhttp.send(null);
        document.getElementById("product-name").value = xmlhttp.responseText;
        // console.log(xmlhttp.responseText);


        document.getElementById('batch-no').focus();
        // document.getElementById("gst").focus;
        stockInSave.setAttribute("disabled", "true");

    } else {

        document.getElementById("manufacturer-id").innerHTML = "";
        document.getElementById("medicine-power").value = "";
        document.getElementById("packaging-type").innerHTML = "";
        document.getElementById("packaging-in").value = "";
        document.getElementById("weightage").value = "";
        document.getElementById("unit").value = "";
        document.getElementById("mrp").value = "";
        document.getElementById("gst").value = "";
        document.getElementById("product-id").value = "";
        document.getElementById("product-name").value = "";
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

//=========================================================================================
// const updateGst = () =>{
//     
// }

const getBillAmount = () => {

    let mrp = document.getElementById('mrp').value;

    let ptr = document.getElementById('ptr').value;

    let gst = document.getElementById('gst').value;
    // console.log("change gst : "+gst);

    let prevGst = document.getElementById("gst-check").value;
    // console.log("prev gst : "+prevGst);

    let qty = document.getElementById('qty').value;
    if (qty == '') {
        qty = 0;
    }

    let disc = document.getElementById('discount').value;
    if (disc == '') {
        disc = 0;
    }


    let maxPtr = (parseFloat(mrp) * 100) / (parseInt(gst) + 100);
    maxPtr = maxPtr.toFixed(2);

    // console.log("max ptr "+ maxPtr);
    // console.log("change ptr "+ ptr);

    if (gst != prevGst) {
        document.getElementById('ptr').value = maxPtr;
        document.getElementById("gst-check").value = gst;
    }

    if (ptr > maxPtr) {
        swal({
            title: "Error Input",
            text: "PTR must be lesser than Calculated Value. Please enter proper PTR value!",
            icon: "error",
            button: false, // Hide the "OK" button
            timer: 1000 // Auto-close the alert after 2 seconds
        });

        document.getElementById("ptr").value = maxPtr;

        maxPtr = maxPtr;

        document.getElementById("bill-amount").value = " ";

        document.getElementById("ptr").focus();
    }



    let base = parseFloat(maxPtr) - (parseFloat(maxPtr) * (parseFloat(disc) / 100));
    base = parseFloat(base) + (parseFloat(base) * (parseFloat(gst) / 100));
    base = base.toFixed(2);

    let totalAmount = parseFloat(base) * parseInt(qty);
    totalAmount = totalAmount.toFixed(2);


    document.getElementById("base").value = base;
    document.getElementById("bill-amount").value = totalAmount;


    //=============================================
    //======= UPDATE GST ON PRODUCT SECTION =======
    let prodId = document.getElementById("product-id").value;

    $.ajax({
        url: 'ajax/update-product-gst.ajax.php',
        type: 'POST',
        data: {
            gstPercetn: gst,
            prodId: prodId
        },
        success: function (response) {
            // console.log(response);
        },
        error: function (error) {
            // console.error('Error removing image:', error);
        }
    });
} //eof getBillAmount function

// ##################################################################################
// ##################################################################################

//geeting bills by clicking on add button
const addData = () => {
    // alert('Clicked');

    let distId = document.getElementById("distributor-id");
    let distId2 = document.getElementById("dist-id");
    // console.log(distId.value1);
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
    let medicinePower = document.getElementById("medicine-power");
    let expMonth = document.getElementById("exp-month");
    let expYear = document.getElementById("exp-year");
    let expDate = `${expMonth.value}/${expYear.value}`;
    expDate = expDate.toString();
    let mfdMonth = document.getElementById("mfd-month");
    let mfdYear = document.getElementById("mfd-year");
    let mfdDate = `${mfdMonth.value}/${mfdYear.value}`;
    mfdDate = mfdDate.toString()
    // var producDsc       = document.getElementById("product-descreption");
    let weightage = document.getElementById("weightage");
    let unit = document.getElementById("unit");
    let packagingIn = document.getElementById("packaging-in");
    let mrp = document.getElementById("mrp");
    let ptr = document.getElementById("ptr");
    let qty = document.getElementById("qty");
    let freeQty = document.getElementById("free-qty");
    let discount = document.getElementById("discount");
    let gst = document.getElementById("gst");
    let base = document.getElementById("base");
    let billAmount = document.getElementById("bill-amount");

    if (distId.value == "" && distId2.value == "") {
        swal("Blank Field", "Please Selet Distributor First!", "error")
            .then((value) => {
                distId.focus();
            });
        return;
    }

    if (distBillid.value == "") {
        swal("Blank Field", "Please Enter Distributor Bill Number!", "error")
            .then((value) => {
                distBillid.focus();
            });
        return;
    }


    if (billDate.value == "") {
        swal("Blank Field", "Please Select Bill Date!", "error")
            .then((value) => {
                billDate.focus();
            });
        return;
    }

    if (dueDate.value == "") {
        swal("Blank Field", "Please Select Bill Payment Date!", "error")
            .then((value) => {
                dueDate.focus();
            });
        return;
    }

    if (paymentMode.value == "") {
        swal("Blank Field", "Please Select Payment Mode!", "error")
            .then((value) => {
                paymentMode.focus();
            });
        return;
    }
    if (productName.value == "") {
        swal("Blank Field", "Please Search & Select Product!", "error")
            .then((value) => {
                productName.focus();
            });
        return;
    }
    if (batch.value == "") {
        swal("Blank Field", "Please Enter Product Batch Number!", "error")
            .then((value) => {
                batch.focus();
            });
        return;
    }

    if (mfdMonth.value == "") {
        swal("Blank field", "Please Enter Manufacturing Date as MM/YY", "error")
            .then((value) => {
                mfdMonth.focus();
            });
        return;
    }
    if (expMonth.value == "") {
        swal("Blank Field", "Please Enter Expiry Date as MM/YY", "error")
            .then((value) => {
                expMonth.focus();
            });
        return;
    }
    if (weightage.value == "") {
        weightage.focus();
        return;
    }
    if (unit.value == "") {
        unit.focus();
        return;
    }
    if (packagingIn.value == "") {
        packagingIn.focus();
        return;
    } else
        if (mrp.value == "") {
            mrp.focus();
            return;
        }
    if (ptr.value == "") {
        swal("Blank Field", "Please enter PTR value", "error")
            .then((value) => {
                ptr.focus();
            });
        return;
    }

    var Ptr = parseFloat(ptr.value);
    var Mrp = parseFloat(mrp.value);

    if (Ptr > Mrp) {
        swal("Blank Field", "Please check PTR value", "error")
            .then((value) => {
                ptr.focus();
            });
        return;
    }
    if (qty.value == "" || qty.value == 0) {
        swal("Blank Field",
            "Please Enter Quantity",
            "error")
            .then((value) => {
                qty.focus();
            });
        return;
    }
    if (freeQty.value == "") {
        swal("Free Qantity value is null",
            "Free Qantity Cannot be null. Minimum value 0",
            "error")
            .then((value) => {
                freeQty.focus();
            });
        return;
    }
    if (discount.value == "") {
        swal("Blank Field",
            "Please Enter Discount at least 0",
            "error")
            .then((value) => {
                discount.focus();
            });
        return;
    }
    if (gst.value == "") {
        swal("Blank Field",
            "GST should be a number",
            "error")
            .then((value) => {
                gst.focus();
            });
        return;
    }
    if (base.value == "") {
        swal("Blank Field",
            "Base Amount can not be blank",
            "error")
            .then((value) => {
                base.focus();
            });
        return;
    }
    if (billAmount.value == "") {
        swal("Blank Field",
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

    let withoutGstAmount = parseFloat(base.value) * parseInt(qty.value);
    let gstPerItem = parseFloat(billAmount.value) - parseFloat(withoutGstAmount);
    gstPerItem = parseFloat(gstPerItem);
    gstPerItem = gstPerItem.toFixed(2);
    let gstVal = document.getElementById("gst-val").value;
    let onlyGst = parseFloat(gstVal) + parseFloat(gstPerItem);


    //////////////////////
    let totalMrp = parseFloat(mrp.value) * ((parseFloat(qty.value) + parseFloat(freeQty.value)));
    let payble = parseFloat(base.value) * parseInt(qty.value);

    let marginP = 0;
    if (parseFloat(totalMrp) > parseFloat(payble)) {
        let margin = parseFloat(totalMrp) - parseFloat(billAmount.value);
        marginP = (parseFloat(margin) / parseFloat(totalMrp)) * 100;
    } else {
        marginP = 0;
    }

    // console.log("discount percent check : ", discount.value);

    jQuery("#dataBody")
        .append(`<tr id="table-row-${slControl}" style="cursor: pointer;">
            <td style="color: red; padding-top:1.2rem; width:1rem"> <i class="fas fa-trash" onclick="deleteData(${slControl}, ${itemQty}, ${gstPerItem}, ${billAmount.value})" style="font-size:.7rem;"></i></td>

            <td class="p-0 pt-3 w-1r" id="row-${slControl}-col-2" style="font-size:.7rem; padding-top:1.2rem; width: 1rem" scope="row">${slno}</td>

            <td class="p-0 pt-3 w-8r" id="row-${slControl}-col-3">
                <input class="table-data w-8r" type="text" value="${productName.value}" style="word-wrap: break-word; font-size: .7rem;" readonly>
                <input type="text" name="productId[]" value="${productId.value}" style="display: none">
            </td>

            <td class="p-0 pt-3 w-4r" id="row-${slControl}-col-4">
                <input class="table-data w-4r" type="text" name="batchNo[]" value="${batchNo}" readonly style="font-size: .7rem;">
            </td>

            <td class="p-0 pt-3 w-3r" id="row-${slControl}-col-5">
                <input class="table-data w-3r" type="text" name="mfdDate[]" value="${mfdDate}" readonly style="font-size: .7rem;">
            </td>

            <td class="p-0 pt-3 w-3r" id="row-${slControl}-col-6">
                <input class="table-data w-3r" type="text" name="expDate[]" value="${expDate}" readonly style="font-size: .7rem;">
            </td>
            <td class="d-none pt-3">
                <input class="table-data w-2r" type="text" name="power[]" value="${medicinePower.value}" readonly style="font-size: .7rem;">
            </td>
            <td class="p-0 pt-3 w-3r" id="row-${slControl}-col-7">
                <input class="table-data w-3r" type="text" name="setof[]" value="${weightage.value}${unit.value}" readonly style="width: 3rem; font-size: .7rem;">
                <input class="table-data line-inp50" type="text" name="weightage[]" value="${weightage.value}" style="display: none" hidden>
                <input class="table-data line-inp50" type="text" name="unit[]" value="${unit.value}" style="display: none" hidden>

                <input class="table-data line-inp50" type="text" name="packagingin[]" value="${packagingIn.value}" style="display: none" hidden>
            </td>
            <td class="p-0 pt-3 w-2r" id="row-${slControl}-col-8">
                <input class="table-data w-2r" type="text" name="qty[]" value="${qty.value}" readonly style="font-size: .7rem; text-align: end">
            </td>
            <td class="p-0 pt-3 w-2r" id="row-${slControl}-col-9">
                <input class="table-data w-2r" type="text" name="freeQty[]" value="${freeQty.value}" readonly style="font-size: .7rem; text-align: end">
            </td>
            <td class="p-0 pt-3 w-3r" id="row-${slControl}-col-10">
                <input class="table-data w-3r" type="text" name="mrp[]" value="${mrp.value}" readonly style="font-size: .7rem; text-align: end">
            </td>
            <td class="p-0 pt-3 w-3r" id="row-${slControl}-col-11">
                <input class="table-data w-3r" type="text" name="ptr[]" value="${ptr.value}" readonly style="font-size: .7rem; text-align: end">

                <input class="d-none table-data w-3r" type="text" name="chkPtr[]" value="${ptr.value}" readonly style="font-size: .7rem; text-align: end">
            </td>
            <td class="p-0 pt-3 w-3r" id="row-${slControl}-col-12a">
                <input type="text" class="table-data w-3r" name="base[]" value="${base.value}" style="text-align: end;">
            </td>
            <td class="ps-0 pt-3 w-2r" id="row-${slControl}-col-12b">
                <input class="table-data w-3r" type="text" name="margin[]" value="${marginP.toFixed(2)}%" readonly style="font-size: .7rem; text-align: end">
            </td>

            <td class="p-0 pt-3 w-2r" id="row-${slControl}-col-12">
                <input class="table-data w-2r"  type="text" name="discount[]" value="${discount.value}%" style="font-size: .7rem; text-align: end">
            </td>
            
            <td class="p-0 pt-3 w-2r" id="row-${slControl}-col-13">
                <input class="table-data w-2r" type="text" name="gst[]" value="${gst.value}%" readonly style="font-size: .7rem; text-align: end">
                <input type="text" name="gstPerItem[]" value="${gstPerItem}" hidden>
            </td class="pt-3" >

            <td class="p-0 pt-3 w-4r" id="row-${slControl}-col-14">
                <input class="table-data w-4r amnt-inp" type="text" name="billAmount[]" value="${billAmount.value}" readonly style="padding: 0%; font-size: .7rem; text-align: end;">
            </td>
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
        productName: productName.value,
        batchNo: batchNo,
        ManufId: manufId.value,
        manufName: manufName.value,
        medPower: medicinePower.value,
        mfdMnth: mfdMonth.value,
        mfdYr: mfdYear.value,
        expMnth: expMonth.value,
        expYr: expYear.value,
        itemWeightage: weightage.value,
        unitType: unit.value,
        packegeinIn: packagingIn.value,
        mrp: mrp.value,
        ptr: ptr.value,

        Qty: qty.value,
        freeQty: freeQty.value,
        discPercent: discount.value,
        base: base.value,
        gst: gst.value,
        amount: billAmount.value,
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
    document.getElementById(`row-${slControl}-col-12a`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`row-${slControl}-col-12b`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`row-${slControl}-col-12`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`row-${slControl}-col-13`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`row-${slControl}-col-14`).onclick = function () {
        editItem(tupleData);
    };

    ///////////////////////////////////////////////////////////////////////////////////////

    stockInSave.removeAttribute("disabled");

    document.getElementById("stock-in-data").reset();
    event.preventDefault();
}

//==============////////////////// ADDED ITEM EDIT FUNCTION \\\\\\\\\\\\\\\\\\=====================

const editItem = (tupleData) => {

    let checkFild = document.getElementById("product-id").value;

    if (checkFild == "") {

        let TupleData = JSON.parse(tupleData);

        document.getElementById("product-name").value = TupleData.productName;
        document.getElementById("product-id").value = TupleData.productId;
        document.getElementById("batch-no").value = TupleData.batchNo;
        document.getElementById("manufacturer-id").value = TupleData.ManufId;
        document.getElementById("manufacturer-name").value = TupleData.manufName;
        document.getElementById("medicine-power").value = TupleData.medPower;

        document.getElementById("mfd-month").value = TupleData.mfdMnth;
        document.getElementById("mfd-year").value = TupleData.mfdYr;
        document.getElementById("exp-month").value = TupleData.expMnth;
        document.getElementById("exp-year").value = TupleData.expYr;

        document.getElementById("weightage").value = TupleData.itemWeightage;
        document.getElementById("unit").value = TupleData.unitType;
        document.getElementById("packaging-in").value = TupleData.packegeinIn;
        document.getElementById("mrp").value = TupleData.mrp;
        document.getElementById("ptr").value = TupleData.ptr;

        document.getElementById("qty").value = TupleData.Qty;
        document.getElementById("free-qty").value = TupleData.freeQty;
        document.getElementById("discount").value = TupleData.discPercent;
        document.getElementById("gst").value = TupleData.gst;
        document.getElementById("base").value = TupleData.base;
        document.getElementById("bill-amount").value = TupleData.amount;

        let gstPerItem = parseFloat(TupleData.amount) - (parseFloat(TupleData.base) * parseInt(TupleData.Qty));
        gstPerItem = gstPerItem.toFixed(2);

        deleteData(TupleData.slno, parseInt(TupleData.Qty) + parseInt(TupleData.freeQty), gstPerItem, TupleData.amount);

        stockInSave.setAttribute("disabled", "true");
    } else {
        swal("Can't Edit", "Please add/edit previous item first.", "error");
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

let mfdMonthInput = document.getElementById('mfd-month');
mfdMonthInput.addEventListener('keydown', function (event) {
    if (event.keyCode === 9) {
        if (mfdMonthInput.value.trim() === '') {
            event.preventDefault();
        }
    }
});
mfdMonthInput.addEventListener('input', function (event) {
    // Remove dots from the input value
    this.value = this.value.replace('.', '');
});


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

// set mfd month control
const setmfdMonth = (mnth) => {
    if (mnth.value.length != 2) {
        mnth.value = '';
        mnth.focus();
    }
}

const setMfdMonth = (month) => {
    let yr = new Date();
    let thisMonth = yr.getMonth();

    if (month.value.length > 2) {
        month.value = '';
        month.focus();
    } else if (month.value.length < 2) {
        month.focus();
    } else if (month.value.length == 2) {
        if (month.value > 12) {
            month.value = '';
            month.focus();
        } else {
            document.getElementById("mfd-year").focus();
        }
    } else {
        month.value = '';
        month.focus();
    }
}

// set exp month control
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


function setMfdYEAR(year) {
    let yr = new Date();
    let thisYear = yr.getFullYear();
    if (year.value.length == 4) {
        if (year.value > thisYear) {
            document.getElementById("mfd-month").focus();
        } else {
            document.getElementById("exp-month").focus();
        }
    } else if (year.value.length > 4) {
        year.value = '';
        year.focus();
    }
}

function setMfdYear(year) {
    let yr = new Date();
    let thisYear = yr.getFullYear();
    let thisMonth = yr.getMonth();
    let mfdMnth = document.getElementById("mfd-month").value;

    if (year.value.length < 4) {
        document.getElementById("mfd-year").value = '';
        document.getElementById("mfd-year").focus();
    }
    if (year.value.length == 4) {
        if (year.value > thisYear) {
            document.getElementById("mfd-year").value = '';
            document.getElementById("mfd-year").focus();
        }

        if (year.value < thisYear) {
            document.getElementById("exp-month").focus();
        }

        if (year.value == thisYear) {
            if (mfdMnth > thisMonth) {
                document.getElementById("mfd-month").value = '';
                document.getElementById("mfd-month").focus();
            } else if (mfdMnth <= thisMonth) {
                document.getElementById("exp-month").focus();
            }
        }
    }
}


function setExpYEAR(year) {
    if (year.value.length == 4) {
        document.getElementById('ptr').focus();
    } else if (year.value.length > 4) {
        year.value = '';
        year.focus();
    }
}

const setExpYear = (year) => {
    var MFDYR = document.getElementById("mfd-year").value;
    var mfdMnth = document.getElementById("mfd-month").value;
    var expMnth = document.getElementById("exp-month").value;

    if (year.value.length < 4) {
        year.value = '';
        year.focus();
    }

    if (year.value.length == 4) {
        if (year.value == MFDYR) {
            if (expMnth < mfdMnth) {
                document.getElementById("exp-month").value = '';
                document.getElementById("exp-month").focus();
            }
        } else if (year.value < MFDYR) {
            year.value = '';
            year.focus();
        }
    }
}

///////////////// ===== product select arrow key effect ===== \\\\\\\\\\\\\\\\\\\\\\\
