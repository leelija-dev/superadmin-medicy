///////////////// preventing number input field to take dot or point\\\\\\\\\\\\\\\\\\\\\
const rtnQty = document.getElementById('return-qty');
rtnQty.addEventListener('input', function (event) {
    this.value = this.value.replace('.', '');
});

const rtnFreeQty = document.getElementById('return-free-qty');
rtnFreeQty.addEventListener('input', function (event) {
    this.value = this.value.replace('.', '');
});
///////////////////////////////////////////////////////////////////


//////////////////// set distributor name /////////////////////

const distributorInput = document.getElementById("distributor-name");
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


////////////////////////////////////////////////////////////////////
var xmlhttp = new XMLHttpRequest();
// ================ SELECTING DISTRIBUTOR ==================
const setDistributor = (t) => {
    let distributirId = t.id.trim();
    let distributirName = t.innerHTML.trim();

    document.getElementById("dist-id").value = distributirId;
    document.getElementById("dist-name").value = distributirName;
    document.getElementById("distributor-name").value = distributirName;

    document.getElementsByClassName("c-dropdown")[0].style.display = "none";

    console.log(distributirId);

    // if (document.getElementById("dist-id-check").value != '') {
    //     if (document.getElementById("dist-id-check").value != document.getElementById("dist-id").value) {
    //         alert('you have change distributor');
    //         window.location.reload();
    //     } else {
    //         getItemList(distributirId);
    //     }
    // }

    getItemList(distributirId);
}




// ===================== get distributor bill number ======================
// const getBillList = (distributirId) => {
//     let id = distributirId;
//     // console.log("DIST ID FOR BILL LIST : " + id);

//     var xmlhttp = new XMLHttpRequest();
//     let distIdUrl = `ajax/return-distributor-bill-list.ajax.php?dist-id=${id}`;
//     xmlhttp.open("GET", distIdUrl, false);
//     xmlhttp.send(null);
//     document.getElementById("select-bill").innerHTML = xmlhttp.responseText;
//     // console.log(xmlhttp.responseText);
//     document.getElementById("dist-id").value = id;
//     // document.getElementById("dist-name").value = distributirName;
//     document.getElementById("select-bill").style.display = "block";
// }




// ======= fetch items data ===========
const getItemList = (distId) => {

    let billNoUrl = `ajax/return-item-list.ajax.php?dist-id=${distId}`;
    xmlhttp.open("GET", billNoUrl, false);
    xmlhttp.send(null);
    document.getElementById("product-select").innerHTML = xmlhttp.responseText;
    // alert(xmlhttp.responseText);

    document.getElementById("dist-id").value = distId;

    document.getElementById("product-select").style.display = "block";
    // document.getElementById("select-bill").style.display = "none";

}




// item search
function searchItem(input) {
    if (input != '') {
        document.getElementById("product-select").style.display = "block";
        // let input = document.getElementById('searchbar').value
        input = input.toLowerCase();
        let x = document.getElementsByClassName('item-list');

        for (i = 0; i < x.length; i++) {
            if (!x[i].innerHTML.toLowerCase().includes(input)) {
                x[i].style.display = "none";
            } else {
                x[i].style.display = "flex";
            }
        }
    } else {
        document.getElementById("product-select").style.display = "none";
    }
}

const setMode = (returnMode) => {
    document.getElementById("refund-mode").value = returnMode;
}

const getDtls = (stockInId, stokInDetialsId, batchNo, productId, productName, billdate, billNumber, t) => {

    document.getElementById('return-mode').focus();

    document.getElementById('select-item-div').value = t.id;
    document.getElementById('stockInId').value = stockInId;
    document.getElementById('stokInDetailsId').value = stokInDetialsId;
    document.getElementById('batch-number').value = batchNo;
    document.getElementById('bill-number').value = billNumber;
    document.getElementById('product-name').value = productName;
    document.getElementById('bill-date').value = billdate;

    var xmlhttp = new XMLHttpRequest();
    if (productId != "") {

        document.getElementById("product-id").value = productId;


        //==================== MFD Date ====================
        let mfdUrl = `ajax/stockIn.all.ajax.php?stock-mfd=${stokInDetialsId}`;
        // alert(expUrl);
        xmlhttp.open("GET", mfdUrl, false);
        xmlhttp.send(null);
        document.getElementById("mfd-date").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);


        //==================== Expiry Date ====================
        let expUrl = `ajax/stockIn.all.ajax.php?stock-exp=${stokInDetialsId}`;
        // alert(expUrl);
        xmlhttp.open("GET", expUrl, false);
        xmlhttp.send(null);
        document.getElementById("exp-date").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Weightage ====================
        let weatageUrl = `ajax/stockIn.all.ajax.php?weightage=${stokInDetialsId}`;
        // alert(url);
        xmlhttp.open("GET", weatageUrl, false);
        xmlhttp.send(null);
        document.getElementById("weatage").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Unit ====================
        let unitUrl = `ajax/stockIn.all.ajax.php?unit=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        document.getElementById("unit").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== PTR ====================
        let ptrUrl = `ajax/stockIn.all.ajax.php?ptr=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", ptrUrl, false);
        xmlhttp.send(null);
        document.getElementById("ptr").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== DISC ====================
        let discUrl = `ajax/stockIn.all.ajax.php?discount=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", discUrl, false);
        xmlhttp.send(null);
        document.getElementById("discount").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== GST ====================
        let gstUrl = `ajax/stockIn.all.ajax.php?gst=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", gstUrl, false);
        xmlhttp.send(null);
        document.getElementById("gst").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== GST Amount Per Quantity ====================
        let GstAmountPerQuantity = `ajax/stockIn.all.ajax.php?gstAmountPerQuantity=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", GstAmountPerQuantity, false);
        xmlhttp.send(null);
        document.getElementById("gstAmountPerQty").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        // //==================== gstAmount ====================
        // let gstAmountUrl = `ajax/stockIn.all.ajax.php?gstAmountUrl=${stokInDetialsId}`;
        // // alert(unitUrl);
        // // window.location.href = unitUrl;
        // xmlhttp.open("GET", gstAmountUrl, false);
        // xmlhttp.send(null);
        // document.getElementById("gst-amount").value = xmlhttp.responseText;
        // // alert(xmlhttp.responseText);

        //==================== taxable ====================
        let taxableUrl = `ajax/stockIn.all.ajax.php?taxable=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", taxableUrl, false);
        xmlhttp.send(null);
        document.getElementById("taxable").value = parseFloat(xmlhttp.responseText).toFixed(2);
        // alert(xmlhttp.responseText);

        //==================== base price ====================
        let baseUrl = `ajax/stockIn.all.ajax.php?base=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", baseUrl, false);
        xmlhttp.send(null);
        document.getElementById("base").value = parseFloat(xmlhttp.responseText).toFixed(2);

        //==================== MRP ====================
        let mrpUrl = `ajax/stockIn.all.ajax.php?mrp=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        document.getElementById("mrp").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Amount ====================
        let amountUrl = `ajax/stockIn.all.ajax.php?amount=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", amountUrl, false);
        xmlhttp.send(null);
        document.getElementById("amount").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== QTY ====================
        let qtyUrl = `ajax/stockIn.all.ajax.php?purchased-qty=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", qtyUrl, false);
        xmlhttp.send(null);
        document.getElementById("purchased-qty").value = xmlhttp.responseText;

        //==================== FREE QTY ====================
        let freeQtyUrl = `ajax/stockIn.all.ajax.php?free-qty=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", freeQtyUrl, false);
        xmlhttp.send(null);
        document.getElementById("free-qty").value = xmlhttp.responseText;

        //==================== NET BUY QTY ====================
        let netBuyQtyUrl = `ajax/stockIn.all.ajax.php?net-buy-qty=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", netBuyQtyUrl, false);
        xmlhttp.send(null);
        document.getElementById("net-buy-qty").value = xmlhttp.responseText;

        //==================== LIVE BUY QTY ====================
        let liveBuyQtyUrl = `ajax/stokReturn.allDetails.ajax.php?current-stock-qty=${stokInDetialsId}`;
        // alert(currentQtyUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", liveBuyQtyUrl, false);
        xmlhttp.send(null);
        document.getElementById("current-purchase-qty").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== LIVE FREE QTY ====================
        let liveFreeQtyUrl = `ajax/stokReturn.allDetails.ajax.php?current-free-qty=${stokInDetialsId}`;
        // alert(currentQtyUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", liveFreeQtyUrl, false);
        xmlhttp.send(null);
        document.getElementById("current-free-qty").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== CURRENT QTY ====================
        let currentQtyUrl = `ajax/currentStock.liveQtyDetails.ajax.php?currentQTY=${stokInDetialsId}`;
        // alert(currentQtyUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", currentQtyUrl, false);
        xmlhttp.send(null);
        document.getElementById("current-qty").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        document.getElementById("return-qty").focus();
        document.getElementById("product-select").style.display = "none";


    } else {

        document.getElementById("ptr").value = "";
        document.getElementById("unit").value = "";
        document.getElementById("mrp").value = "";
        document.getElementById("gst").value = "";
        document.getElementById("product-id").value = "";
        document.getElementById("product-id").value = "";
        document.getElementById('product-name').value = "";
        document.getElementById('gstAmountPerQty').value = "";

    }
}

const checkFQty = (returnFqty) => {
    returnFqty = parseInt(returnFqty);
    var CurrentFQty = document.getElementById("current-free-qty").value;

    if (CurrentFQty < returnFqty) {
        swal("Oops", "Return Quantity must be leser than Current Free Qantity!", "error")
        document.getElementById("return-free-qty").value = 0;
    }
}

const getRefund = (returnQty) => {
    // console.log("check return qty : "+returnQty);
    returnQty = parseInt(returnQty);
    let currentQTY = document.getElementById("current-purchase-qty").value;

    if (parseInt(currentQTY) < parseInt(returnQty)) {

        swal("Oops", "Return Quantity must be leser than Current Buy Qantity!", "error")
        document.getElementById("return-qty").value = 0;
    }

    if (isNaN(returnQty)) {
        document.getElementById("refund-amount").value = '';
        return;
    }
    if (returnQty != ' ') {
        let ptr = document.getElementById("ptr").value;
        let gst = document.getElementById("gst").value;
        let discParcent = document.getElementById("discount").value;
        let subtotal = (parseFloat(ptr) - (parseFloat(ptr) * parseFloat(discParcent) / 100)) * returnQty;
        let refund = subtotal + (subtotal * (parseFloat(gst) / 100));

        document.getElementById("refund-amount").value = refund.toFixed(2);


    } else if (parseInt(returnQty) == 0) {
        document.getElementById("refund-amount").value = '0';
    } else {
        document.getElementById("refund-amount").value = '';
    }

    // let gstPercetn = document.getElementById("");

    let returnGstAmount = document.getElementById('gstAmountPerQty').value;
    returnGstAmount = returnGstAmount * returnQty;
    document.getElementById('return-gst-amount').value = returnGstAmount;
}




// ##################################################################################
// ##################################################################################

//geeting bills by clicking on add button
function addData() {

    let seletedItemDiv = document.getElementById('select-item-div').value;
    
    var distId = document.getElementById("distributor-name");
    //var billNumber = document.getElementById("bill-number");
    var stokInDetailsId = document.getElementById("stokInDetailsId");
    var batchNumber = document.getElementById("batch-number");
    var billNumber = document.getElementById("bill-number");
    var billDate = document.getElementById("bill-date");
    var returnMode = document.getElementById("return-mode");

    var productId = document.getElementById("product-id");
    var productName = document.getElementById('product-name').value;

    var mfdDate = document.getElementById("mfd-date");
    var expDate = document.getElementById("exp-date");
    var weatage = document.getElementById("weatage");
    var unit = document.getElementById("unit");
    var ptr = document.getElementById("ptr");
    var discount = document.getElementById("discount");
    var gst = document.getElementById("gst");
    var RtrnGstAmount = document.getElementById("return-gst-amount");
    var mrp = document.getElementById("mrp");
    var amount = document.getElementById("amount");
    var purchasedQty = document.getElementById("purchased-qty");
    var freeQty = document.getElementById("free-qty");
    var currentQty = document.getElementById("current-qty");
    var returnQty = document.getElementById("return-qty");
    var returnFreeQty = document.getElementById("return-free-qty");

    var basePrice = document.getElementById("base");
    var taxableOnPurchase = document.getElementById("taxable");
    var crntPrchsQty = document.getElementById("current-purchase-qty");
    var crntFreeQty = document.getElementById("current-free-qty");

    var refundAmount = document.getElementById("refund-amount");

    var qtyVal = document.getElementById("total-return-qty");
    var totalReturnQty = parseInt(returnFreeQty.value) + parseInt(returnQty.value);


    if (distId.value == "") {
        swal("Oops", "Please select Distributor!", "error");
        distId.focus();
        return;
    }

    if (batchNumber.value == "") {
        swal("Oops", "Please select Batch Number!", "error");
        batchNumber.focus();
        return;
    }
    if (billDate.value == "") {
        swal("Oops", "Unable to Select Bill Date!", "error");
        billDate.focus();
        return;
    }
    if (returnMode.value == "") {
        swal("Oops", "Please select your refund mode!", "error");
        returnMode.focus();
        return;
    }


    if (productName == "") {
        swal("Oops", "Product name can't find!", "error");
        return;
    }
    if (productId.value == "") {
        swal("Oops", "Product name can't be empty!", "error");
        productId.focus();
        return;
    }
    if (expDate.value == "") {
        swal("Oops", "Unable to get Expiry Date!", "error");
        expDate.focus();
        return;
    }
    if (weatage.value == "") {
        weatage.focus();
        swal("Oops", "Unable to get product weatage!", "error");
        return;
    }
    if (unit.value == "") {
        unit.focus();
        swal("Oops", "Unable to get product unit!", "error");
        return;
    }
    if (ptr.value == "") {
        ptr.focus();
        swal("Oops", "Unable to get product ptr!", "error");
        return;
    }
    if (discount.value == "") {
        discount.focus();
        swal("Oops", "Unable to get product discount!", "error");
        return;
    }
    if (gst.value == "") {
        gst.focus();
        swal("Oops", "Unable to get product GST!", "error");
        return;
    }
    if (taxable.value == "") {
        taxable.focus();
        swal("Oops", "Unable to get product tax amount!", "error");
        return;
    }
    if (mrp.value == "") {
        mrp.focus();
        swal("Oops", "Unable to get product MRP!", "error");
        return;
    }
    if (amount.value == "") {
        amount.focus();
        swal("Oops", "Unable to get product amount!", "error");
        return;
    }
    if (purchasedQty.value == "") {
        purchasedQty.focus();
        swal("Oops", "Unable to get product purchased quantity!", "error");
        return;
    }
    if (freeQty.value == "") {
        freeQty.focus();
        swal("Oops", "Unable to get product free quantity!", "error");
        return;
    }
    if (currentQty.value == "") {
        currentQty.focus();
        swal("Oops", "Unable to get product current quantity!", "error");
        return;
    }
    if (returnQty.value == "") {
        returnQty.focus();
        swal("Oops", "Please Enter How many Quantity You Want to Return!", "error");
        return;
    }
    if (returnFreeQty.value == "") {
        returnQty.focus();
        swal("Oops", "Free Quantity Field can not be blank!", "error");
        return;
    }

    if (refundAmount.value == "") {
        refundAmount.focus();
        swal("Oops", "Unable to get Refund Amount!", "error");
        return;
    }


    //////////////////// dynamic id and serial contolr ///////////////////
    let slno = document.getElementById("dynamic-id").value;
    let slControl = document.getElementById("serial-control").value;
    slno++;
    slControl++;
    document.getElementById("dynamic-id").value = slno;
    document.getElementById("serial-control").value = slControl;

    //geeting total refund amount
    var refund = document.getElementById("refund");
    var refundAmt = parseFloat(refund.value) + parseFloat(refundAmount.value);
    refund.value = refundAmt.toFixed(2);

    // return gst generating
    let withoutGst = (parseFloat(ptr.value) - (parseFloat(ptr.value) * parseFloat(discount.value) / 100)) * returnQty.value;
    let taxAmount = parseFloat(refundAmount.value) - withoutGst;


    var returnGstAmount = document.getElementById("return-gst-val").value;

    returnGstAmount = parseFloat(returnGstAmount) + parseFloat(taxAmount);
    let ReturnGstAmount = returnGstAmount.toFixed(2);

    document.getElementById("return-gst-val").value = ReturnGstAmount;


    //////////////////// onclik handler data \\\\\\\\\\\\\\\\\\\
    var divElement = document.getElementById(seletedItemDiv);
    originalClickHandler = divElement.onclick;

    let flag = 0;
    // =========================================================


    const appendData = () => {

        jQuery("#dataBody")
            .append(`<tr id="table-row-${slControl}">
                    <td  style="color: red;">
                        <i class="fas fa-trash pt-3" onclick='deleteData(${slControl}, ${returnQty.value}, ${taxAmount}, ${refundAmount.value}, ${seletedItemDiv}, ${originalClickHandler}, ${flag})'></i>
                    </td>
                    <td id="row-${slControl}-col-2" style="font-size:.8rem ; padding-top:1.5rem"scope="row">${slno}</td>
                    <td class="d-none p-0 pt-3">
                        <input class="  col table-data w-6r" type="text" name="stok-in-details-id[]" value="${stokInDetailsId.value}" readonly>
                    </td>
                    <td class="p-0 pt-3" id="row-${slControl}-col-4">
                        <input class="col table-data w-10r" type="text" name="productName[]" value="${productName}" readonly style="text-align: start; font-size:0.7rem; padding-top: 0.7rem;">
                        <input class="col table-data w-10r" type="text" name="productId[]" value="${productId.value}" readonly style="text-align: start;" hidden>
                    </td>
                    <td class="p-0 pt-3" id="row-${slControl}-col-5">
                        <input class="col table-data w-6r" type="text" name="batchNo[]" value="${batchNumber.value}" readonly  style="text-align: start; font-size:0.7rem; padding-top: 0.7rem;">
                    </td>
                    <td class="p-0 pt-3" id="row-${slControl}-col-6">
                        <input class="col table-data w-5r" type="text" name="expDate[]" value="${expDate.value}" readonly  style="text-align: start; font-size:0.7rem; padding-top: 0.7rem;">
                    </td>
                    <td class="p-0 pt-3" id="row-${slControl}-col-7">
                        <input class="col table-data w-4r" type="text" name="setof[]" value="${weatage.value}${unit.value}" readonly  style="text-align: start; font-size:0.7rem;; padding-top: 0.7rem;">
                    </td>
                    <td class="p-0 pt-3" id="row-${slControl}-col-8">
                        <input class="col table-data w-3r" type="text" name="purchasedQty[]" value="${purchasedQty.value}" readonly style="text-align: start; font-size:0.7rem; padding-top: 0.7rem;">
                    </td>
                    <td class="p-0 pt-3" id="row-${slControl}-col-9">
                        <input class="col table-data w-3r" type="text" name="freeQty[]" value="${freeQty.value}" readonly  style="text-align: start; font-size:0.7rem; padding-top: 0.7rem;">
                    </td>
                    <td class="p-0 pt-3" id="row-${slControl}-col-10">
                        <input class="col table-data w-4r" type="text" name="mrp[]" value="${mrp.value}" readonly  style="text-align: start; font-size:0.7rem; padding-top: 0.7rem;">
                    </td>
                    <td class="p-0 pt-3" id="row-${slControl}-col-11">
                        <input class="col table-data w-4r" type="text" name="ptr[]" value="${ptr.value}" readonly style="text-align: start; font-size:0.7rem; padding-top: 0.7rem;">
                    </td>
                    <td class="p-0 pt-3"  id="row-${slControl}-col-12">
                        <input class="col table-data w-3r" type="text" name="disc-percent[]" value="${discount.value}%" readonly  style="text-align: start; font-size:0.7rem; padding-top: 0.7rem;">
                    </td>
                    <td class="p-0 ps-1 pt-3" id="row-${slControl}-col-13">
                        <input class="col table-data w-3r" type="text" name="gst[]" value="${gst.value}%" readonly style="border-radius: 30%; font-size: .7rem; text-align:center; padding-top: 0.7rem;">
                    </td>
                    <td class="p-0 pt-3" id="row-${slControl}-col-14">
                        <input class="col table-data w-3r" type="text" name="return-qty[]" value="${parseFloat(returnQty.value)}" readonly  style="text-align: start; font-size:0.7rem; padding-top: 0.7rem;">
                    </td>
                    <td class="p-0 pt-3" id="row-${slControl}-col-15">
                        <input class="col table-data w-3r" type="text" name="return-free-qty[]" value="${parseFloat(returnFreeQty.value)}" readonly  style="text-align: start; font-size:0.7rem; padding-top: 0.7rem;">
                    </td>
                    <td class=" amnt-td p-0 pt-3" id="row-${slControl}-col-16">
                        <input class="col table-data W-4r" type="text" name="refund-amount[]" value="${refundAmount.value}" readonly style="text-align: start; font-size:0.7rem; padding-top: 0.7rem;"></td>
                </tr>`);

        return true;
    }

    if (appendData() === true) {

        if (slno > 1) {
            let id = document.getElementById("items-qty");
            let newId = parseFloat(id.value) + 1;
            document.getElementById("items-qty").value = newId;

        } else {
            document.getElementById("items-qty").value = slno;
        }

        if (slno > 1) {
            let Qty = parseInt(qtyVal.value);

            let newQty = Qty + totalReturnQty;
            document.getElementById("total-return-qty").value = newQty;

        } else {
            document.getElementById("total-return-qty").value = totalReturnQty;
        }


        ///////////////////////////////////////////////////////////////////////////////////

        const dataTuple = {

            seletedItemDiv: seletedItemDiv,
            originalClickHandler: originalClickHandler,


            slno: slControl,
            stokInDetailsId: stokInDetailsId.value,
            productId: productId.value,
            productName: productName,
            batchNumber: batchNumber.value,
            billNumber: billNumber.value,

            billDate: billDate.value,
            mfdDate: mfdDate.value,
            expDate: expDate.value,
            weatage: weatage.value,
            unit: unit.value,
            mrp: mrp.value,
            ptr: ptr.value,
            discount: discount.value,
            gst: gst.value,

            basePrice: basePrice.value,
            taxableOnPurchase: taxableOnPurchase.value,
            RtrnGstAmount: RtrnGstAmount.value,
            crntPrchsQty: crntPrchsQty.value,
            crntFreeQty: crntFreeQty.value,

            amount: amount.value,
            purchasedQty: purchasedQty.value,
            freeQty: freeQty.value,
            currentQty: currentQty.value,
            returnQty: returnQty.value,
            returnFreeQty: returnFreeQty.value,
            refundAmount: refundAmount.value,
        };

        let tupleData = JSON.stringify(dataTuple);

        document.getElementById(`row-${slControl}-col-2`).onclick = function () {
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
        document.getElementById(`row-${slControl}-col-13`).onclick = function () {
            editItem(tupleData);
        };
        document.getElementById(`row-${slControl}-col-14`).onclick = function () {
            editItem(tupleData);
        };
        document.getElementById(`row-${slControl}-col-15`).onclick = function () {
            editItem(tupleData);
        };
        document.getElementById(`row-${slControl}-col-16`).onclick = function () {
            editItem(tupleData);
        };

        //////// document.getElementById("demo").innerHTML = await myPromise;/////////////
        //////////////////////////////////////////////////////////////////////////////////

        document.getElementById("stokInDetailsId").value = '';
        document.getElementById("product-id").value = '';
        document.getElementById('product-name').value = '';

        document.getElementById("stock-return-item-data").reset();
        event.preventDefault();


        /// ============ row modify function ===============

        disableOnClickFunction(seletedItemDiv);
    }

} //eof addData  


// ======= item onclick disable and enablel function ========
const disableOnClickFunction = (divId) => {

    let divElement = document.getElementById(divId);

    if (divElement) {
        divElement.onclick = null; // or divElement.onclick = function() {};
    }

} // eof item onclik disable 

const divOnclikActive = (divId, handelerData) => {

    // console.log(divId.id);
    // console.log(handelerData);

    let divElement = document.getElementById(divId.id);

    if (divElement) {
        // Restore the original onclick handler
        divElement.onclick = handelerData;
    }
}
// ================================ Delet Data ================================

const deleteData = (slno, itemQty, gstPerItem, refundPerItem, divId, handelerData, flag) => {

    let delRow = slno;

    jQuery(`#table-row-${slno}`).remove();
    let slval = document.getElementById("dynamic-id").value;
    document.getElementById("dynamic-id").value = parseInt(slval) - 1;

    //minus item
    let items = document.getElementById("items-qty");
    let finalItem = items.value - 1;
    items.value = finalItem;

    // minus quantity
    let qty = document.getElementById("total-return-qty");
    let finalQty = qty.value - itemQty
    qty.value = finalQty;

    // minus gst
    let gst = document.getElementById("return-gst-val");
    let finalGst = gst.value - gstPerItem;
    gst.value = finalGst.toFixed(2);

    // minus netAmount
    let net = document.getElementById("refund");
    if (net.value == null) {
        net.value = 0;
        let finalAmount = parseFloat(net.value) - parseFloat(refundPerItem);
        net.value = finalAmount.toFixed(2);
    } else {
        let finalAmount = parseFloat(net.value) - parseFloat(refundPerItem);
        net.value = finalAmount.toFixed(2);
    }

    rowAdjustment(delRow);
    // console.log(divId);

    if(flag == 0){
        divOnclikActive(divId, handelerData);
    }
}


function rowAdjustment(delRow) {

    let tableId = document.getElementById("dataBody");
    let j = 0;
    let colIndex = 1;

    for (let i = 0; i < tableId.rows.length; i++) {
        j++;
        let row = tableId.rows[i];
        let cell = row.cells[colIndex];
        cell.innerHTML = j;
    }
}


///////////////////////// item edit funtion /////////////////////////

const editItem = (tData) => {

    if (document.getElementById('product-id').value == '') {
        var tData = JSON.parse(tData);
        console.log(tData);

        document.getElementById("select-item-div").value = tData.divId;

        document.getElementById("stokInDetailsId").value = tData.stokInDetailsId;
        document.getElementById("bill-number").value = tData.billNumber;
        document.getElementById("batch-number").value = tData.batchNumber;
        document.getElementById("product-id").value = tData.productId;
        document.getElementById('product-name').value = tData.productName;
        document.getElementById("bill-date").value = tData.billDate;
        document.getElementById("mfd-date").value = tData.mfdDate;
        document.getElementById("exp-date").value = tData.expDate;

        document.getElementById("weatage").value = tData.weatage;
        document.getElementById("unit").value = tData.unit;
        document.getElementById("mrp").value = tData.mrp;
        document.getElementById("ptr").value = tData.ptr;
        document.getElementById("discount").value = tData.discount;
        document.getElementById("gst").value = tData.gst;

        document.getElementById("return-gst-amount").value = tData.RtrnGstAmount;
        document.getElementById("base").value = tData.basePrice;
        document.getElementById("taxable").value = tData.taxableOnPurchase;
        document.getElementById("current-purchase-qty").value = tData.crntPrchsQty;
        document.getElementById("current-free-qty").value = tData.crntFreeQty;

        document.getElementById("amount").value = tData.amount;
        document.getElementById("purchased-qty").value = tData.purchasedQty;
        document.getElementById("free-qty").value = tData.freeQty;
        document.getElementById("current-qty").value = tData.currentQty;
        document.getElementById("return-qty").value = tData.returnQty;
        document.getElementById("return-free-qty").value = tData.returnFreeQty;
        document.getElementById("refund-amount").value = tData.refundAmount;

        let flag = 1;
        let itemQty = parseInt(tData.returnQty) + parseInt(tData.returnFreeQty);
        deleteData(tData.slno, itemQty, tData.RtrnGstAmount, tData.refundAmount, tData.divId, tData.handelerData, flag);
    } else {
        swal("Error", "Add or remove Previous data first.", "error");
    }
}