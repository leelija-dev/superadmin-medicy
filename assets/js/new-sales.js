//======================= new sell generate bill button disable and enable control ===================
var tableBody = document.getElementById('item-body');
var newSellGenerateBill = document.getElementById('new-sell-bill-generate');
newSellGenerateBill.setAttribute("disabled", "true");

//======================================================================================

const getDate = (date) => {
    document.getElementById("final-bill-date").value = date;
}
// ADD NEW CUSTOMER 
const addCustomerModal = () => {
    let url = "ajax/customer.addNew.ajax.php";
    $(".add-customer-modal").html(
        '<iframe width="99%" height="500px" frameborder="0" allowtransparency="true" src="' +
        url + '"></iframe>');
}
// GET CUSTOMER DETAILS
const getCustomer = (customer) => {
    if (customer.length > 0) {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("customer-list").style.display = "block";
                document.getElementById("customer-list").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", `ajax/customerSearch.ajax.php?data=${customer}`, true);
        xmlhttp.send();
    } else {
        document.getElementById("customer-list").style.display = "none";
    }
} // end getCustomer

const setCustomer = (id) => {
    var xmlhttp = new XMLHttpRequest();

    // ================ get Name ================
    stockCheckUrl = 'ajax/customer.getDetails.ajax.php?name=' + id;
    xmlhttp.open("GET", stockCheckUrl, false);
    xmlhttp.send(null);
    document.getElementById("customer").value = xmlhttp.responseText;
    document.getElementById("customer-name").value = xmlhttp.responseText;
    document.getElementById("customer-id").value = id;

    // ================ get Contact ================
    stockCheckUrl = 'ajax/customer.getDetails.ajax.php?contact=' + id;
    xmlhttp.open("GET", stockCheckUrl, false);
    xmlhttp.send(null);
    document.getElementById("contact").innerHTML = xmlhttp.responseText;
    document.getElementById("customer-list").style.display = "none";
}

const counterBill = () => {
    document.getElementById("contact").innerHTML = "";
    document.getElementById("customer").value = "Cash Sales";
    document.getElementById("customer-id").value = "Cash Sales";
    document.getElementById("customer-name").value = "Cash Sales";
}

const getPaymentMode = (mode) => {
    document.getElementById("final-payment").value = mode;
}

/////////////// making search item focused fist value not a space \\\\\\\\\\\\\\\\\\
const firstInput = document.getElementById('product-name');

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
//==========================================================
const searchItem = (searchFor) => {

    let searchReult = document.getElementById('searched-items');
    

    if (document.getElementById("product-name").value == "") {
        document.getElementById("searched-items").style.display = "none";
        document.getElementById("searched-batchNo").style.display = "none";
    }

    if (searchFor.length == "") {
        searchReult.innerHTML = '';

        document.getElementById("product-name").value = '';
        document.getElementById("weightage").value = '';
        document.getElementById("batch-no").value = '';
        document.getElementById("exp-date").value = '';
        document.getElementById("mrp").value = '';
        document.getElementById("gst").value = '';

        document.getElementById("item-weightage").value = '';
        document.getElementById("item-unit-type").value = '';
        document.getElementById("aqty").value = '';
        document.getElementById("type-check").value = '';
        document.getElementById("qty").value = '';
        document.getElementById("disc").value = '';
        document.getElementById("dPrice").value = '';
        document.getElementById("taxable").value = '';
        document.getElementById("amount").value = '';
    } else {
        if (searchFor.length > 2) {
            document.getElementById("searched-items").style.display = "block";
            document.getElementById("exta-details").style.display = "none";
            var XML = new XMLHttpRequest();
            XML.onreadystatechange = function () {
                if (XML.readyState == 4 && XML.status == 200) {
                    searchReult.innerHTML = XML.responseText;
                }
            };
            XML.open('GET', 'ajax/sales-item-list.ajax.php?data=' + searchFor, true);
            XML.send();
        }

        newSellGenerateBill.setAttribute("disabled", "true");
    }
}

// ========= PRODUCT BATCH NUMBER FETCH AREA ==================
const itemsBatchDetails = (prodcutId, name, stock) => {
    
    if (stock > 0) {
        // ==================== SEARCH PRODUCT NAME =====================
        document.getElementById("product-name").value = name;
        document.getElementById("searched-items").style.display = "none";
        // ==================== EOF PRODUCT NAME SEARCH ================

        let searchReult = document.getElementById('searched-batchNo');

        document.getElementById("searched-batchNo").style.display = "block";
        document.getElementById("exta-details").style.display = "none";

        document.getElementById("batch-no").value = '';
        document.getElementById("weightage").value = '';
        document.getElementById("exp-date").value = '';
        document.getElementById("mrp").value = '';
        document.getElementById("gst").value = '';

        document.getElementById("item-weightage").value = '';
        document.getElementById("item-unit-type").value = '';
        document.getElementById("aqty").value = '';
        document.getElementById("type-check").value = '';
        document.getElementById("qty").value = '';
        document.getElementById("disc").value = '';
        document.getElementById("dPrice").value = '';
        document.getElementById("taxable").value = '';
        document.getElementById("amount").value = '';

        var XML = new XMLHttpRequest();
        XML.onreadystatechange = function () {
            if (XML.readyState == 4 && XML.status == 200) {
                searchReult.innerHTML = XML.responseText;
            }
        };
        XML.open('GET', `ajax/sales-item-batch-list.ajax.php?prodId=${prodcutId}`, true);
        XML.send();
    }

    if (stock <= 0) {

        document.getElementById("product-name").value = '';
        document.getElementById("weightage").value = '';
        document.getElementById("batch-no").value = '';
        document.getElementById("exp-date").value = '';
        document.getElementById("mrp").value = '';
        document.getElementById("gst").value = '';

        document.getElementById("item-weightage").value = '';
        document.getElementById("item-unit-type").value = '';
        document.getElementById("aqty").value = '';
        document.getElementById("type-check").value = '';
        document.getElementById("qty").value = '';
        document.getElementById("disc").value = '';
        document.getElementById("dPrice").value = '';
        document.getElementById("taxable").value = '';
        document.getElementById("amount").value = '';
        document.getElementById("loose-stock").value = 'None';
        document.getElementById("loose-price").value = 'None';

        // document.getElementById("qty-type").setAttribute("disabled", true);

        document.getElementById("exta-details").style.display = "none";
        document.getElementById("searched-items").style.display = "none";

        swal({
            title: "Want Add This Item?",
            text: "This Item is not avilable in your stock, do you want to add?",
            // icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = "stock-in.php";
                }
            });
    }
}
// ========= END OF PRODUCT BATCH NUMBER FETCH AREA ==================


/////// extra detials div control function \\\\\\\\
const chekForm = () =>{
   
    if(document.getElementById('product-name').value == ''){
        document.getElementById("exta-details").style.display = "none";
        document.getElementById("searched-items").style.display = "none";
        
        tableBody = document.getElementById('item-body');

        if(tableBody.getElementsByTagName('tr') != null){
            newSellGenerateBill.removeAttribute("disabled");
        }else{
            newSellGenerateBill.setAttribute("disabled", "true");
        }
    }
}


       
////////////////////////////////////////////////////////
///////////////////////////////////////////////////////
const stockDetails = (productId, batchNo, itemId) => {

    var selectedItem = productId;
    var SelectedBatch = batchNo;

    let tableVal = document.getElementById("dynamic-id").value;

    if (tableVal > 0) {

        let tableId = document.getElementById("item-body");
        let jsTabelLength = tableId.rows.length;
        let cellIndex_1 = 3;
        let cellIndex_2 = 5;

        for (let i = 0; i < jsTabelLength; i++) {

            let row = tableId.rows[i];
            let prodIdCell = row.cells[cellIndex_1];
            let prevSelectedProdId = prodIdCell.innerHTML;

            if (prevSelectedProdId == selectedItem) {

                var prodBatchNoCell = row.cells[cellIndex_2];
                let prevSelectedBatch = prodBatchNoCell.innerHTML;

                var flag = 0;
                if (prevSelectedBatch == SelectedBatch) {
                    flag = 1;
                    exist = 0;
                    document.getElementById("product-id").value = '';
                    document.getElementById("batch_no").value = '';
                    document.getElementById("searched-batchNo").style.display = "none";

                    swal("Failed!", "You have added this item previously.", "error");

                } else {
                    document.getElementById("product-id").value = productId;
                    document.getElementById("batch_no").value = batchNo;
                    document.getElementById("batch-no").value = batchNo;
                    document.getElementById("searched-batchNo").style.display = "none";
                    document.getElementById("crnt-stck-itm-id").value = itemId;

                    var xmlhttp = new XMLHttpRequest();
                    // ============== Check Existence ==============
                    stockCheckUrl = `ajax/stock.checkExists.ajax.php?Pid=${productId}&batchNo=${batchNo}`;
                    xmlhttp.open("GET", stockCheckUrl, false);
                    xmlhttp.send(null);
                    exist = xmlhttp.responseText;
                }
            } else {
                document.getElementById("product-id").value = productId;
                document.getElementById("batch_no").value = batchNo;
                document.getElementById("batch-no").value = batchNo;
                document.getElementById("searched-batchNo").style.display = "none";
                document.getElementById("crnt-stck-itm-id").value = itemId;

                var xmlhttp = new XMLHttpRequest();

                // ============== Check Existence ==============
                stockCheckUrl = `ajax/stock.checkExists.ajax.php?Pid=${productId}&batchNo=${batchNo}`;
                xmlhttp.open("GET", stockCheckUrl, false);
                xmlhttp.send(null);
                exist = xmlhttp.responseText;
            }
            if (flag = 1) {
                break;
            } else {
                continue;
            }
        }

    } else {

        document.getElementById("product-id").value = productId;
        document.getElementById("batch_no").value = batchNo;
        document.getElementById("batch-no").value = batchNo;
        document.getElementById("searched-batchNo").style.display = "none";
        document.getElementById("crnt-stck-itm-id").value = itemId;

        var xmlhttp = new XMLHttpRequest();

        // ============== Check Existence ==============
        stockCheckUrl = `ajax/stock.checkExists.ajax.php?Pid=${productId}&batchNo=${batchNo}`;
        xmlhttp.open("GET", stockCheckUrl, false);
        xmlhttp.send(null);
        exist = xmlhttp.responseText;

    }

    if (exist == 1) {
        document.getElementById("exta-details").style.display = "block";

        // ============== Product Name ==============
        stockItemUrl = 'ajax/getProductDetails.ajax.php?id=' + productId;
        // alert(url);
        xmlhttp.open("GET", stockItemUrl, false);
        xmlhttp.send(null);
        document.getElementById("product-name").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Weightage ====================
        weightageUrl = `ajax/getProductDetails.ajax.php?weightage=${productId}`;
        // alert(url);
        xmlhttp.open("GET", weightageUrl, false);
        xmlhttp.send(null);
        let packWeightage = xmlhttp.responseText;
        document.getElementById("item-weightage").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Unit ====================
        unitUrl = 'ajax/getProductDetails.ajax.php?itemUnit=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        let packUnit = xmlhttp.responseText;
        let packOf = `${packWeightage} ${packUnit}`;
        document.getElementById("weightage").value = packOf;
        document.getElementById("item-unit-type").value = xmlhttp.responseText;
        // // alert(xmlhttp.responseText);

        //==================== Expiry Date ====================
        expDateUrl = `ajax/getProductDetails.ajax.php?exp=${productId}&batchNo=${batchNo}`;
        // alert(url);
        xmlhttp.open("GET", expDateUrl, false);
        xmlhttp.send(null);
        document.getElementById("exp-date").value = xmlhttp.responseText;

        //==================== MRP ====================
        mrpUrl = `ajax/getProductDetails.ajax.php?stockmrp=${productId}&batchNo=${batchNo}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        document.getElementById("mrp").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== PTR ====================
        ptrUrl = `ajax/getProductDetails.ajax.php?stockptr=${productId}&batchNo=${batchNo}`;
        // alert(ptrUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", ptrUrl, false);
        xmlhttp.send(null);
        document.getElementById("ptr").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Loose Stock ====================
        looseStockUrl = `ajax/getProductDetails.ajax.php?looseStock=${productId}&batchNo=${batchNo}`;
        // alert(ptrUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", looseStockUrl, false);
        xmlhttp.send(null);
        document.getElementById("loose-stock").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Loose Price ====================
        // loosePriceUrl = `ajax/getProductDetails.ajax.php?loosePrice=${productId}&batchNo=${batchNo}`;
        // // alert(ptrUrl);
        // // window.location.href = unitUrl;
        // xmlhttp.open("GET", loosePriceUrl, false);
        // xmlhttp.send(null);
        // document.getElementById("loose-price").value = xmlhttp.responseText;
        // // alert(xmlhttp.responseText);

        // ======================= AVAILIBILITY ===========================
        itemAvailibilityUrl = `ajax/getProductDetails.ajax.php?availibility=${productId}&batchNo=${batchNo}`;
        // alert(ptrUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", itemAvailibilityUrl, false);
        xmlhttp.send(null);
        document.getElementById("aqty").value = xmlhttp.responseText;

        //==================== GST ====================
        gstUrl = 'ajax/product.getGst.ajax.php?stockgst=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", gstUrl, false);
        xmlhttp.send(null);
        document.getElementById("gst").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        // =========================================================
        // ===================== XTERA DETAILS =====================
        // =========================================================

        //==================== Manufacturer Details ====================
        manufUrl = 'ajax/product.getManufacturer.ajax.php?id=' + productId;
        xmlhttp.open("GET", manufUrl, false);
        xmlhttp.send(null);
        document.getElementById("manuf").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        manufNameUrl = 'ajax/product.getManufacturer.ajax.php?manufName=' + productId;
        xmlhttp.open("GET", manufNameUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("manufName").value = xmlhttp.responseText;

        //////// STRING REPLACE IN MANUFACTURER DETAILS //////////
        // let manufactururName = document.getElementById("manufName").value;
        // manufactururName = manufactururName.replace("<", "&lt");
        // manufactururName = manufactururName.replace(">", "&gt");
        // manufName = manufactururName.replace("'", "_");
        // document.getElementById("manufNameStrngReplace").value = manufName;
        // console.log(manufName);
        //==================== Content ====================
        contentUrl = 'ajax/product.getContent.ajax.php?pid=' + productId;
        xmlhttp.open("GET", contentUrl, false);
        xmlhttp.send(null);
        document.getElementById("productComposition").value = xmlhttp.responseText;

        document.getElementById("qty").focus();

        newSellGenerateBill.setAttribute("disabled", "true"); 

    } else {
        document.getElementById("product-name").value = '';
        document.getElementById("weightage").value = '';
        document.getElementById("batch-no").value = '';
        document.getElementById("exp-date").value = '';

        document.getElementById("weightage").value = '';
        document.getElementById("batch-no").value = '';
        document.getElementById("exp-date").value = '';

        document.getElementById("mrp").value = '';
        document.getElementById("gst").value = '';

        document.getElementById("item-weightage").value = '';
        document.getElementById("item-unit-type").value = '';
        document.getElementById("aqty").value = '';
        document.getElementById("type-check").value = '';
        document.getElementById("qty").value = '';
        document.getElementById("disc").value = '';
        document.getElementById("dPrice").value = '';
        document.getElementById("taxable").value = '';
        document.getElementById("amount").value = '';

        // document.getElementById("qty-type").setAttribute("disabled", true);
        document.getElementById("loose-stock").value = 'None';
        document.getElementById("loose-price").value = 'None';
        document.getElementById("exta-details").style.display = "none";
    }
}


const onQty = (qty) => {

    var xmlhttp = new XMLHttpRequest();

    let mrp = document.getElementById("mrp").value;
    let itemWeatage = document.getElementById('item-weightage').value;
    let itemUnit = document.getElementById('item-unit-type').value;
    let loosePrice = "";
    if (itemUnit == 'tablets' || itemUnit == 'capsules') {
        loosePrice = parseFloat(mrp) / parseInt(itemWeatage);
    } else {
        loosePrice = '';
    }
    document.getElementById('loose-price').value = loosePrice;

    //=============================== AVAILIBILITY CHECK ================================
    let availibility = document.getElementById('aqty').value;
    availibility = parseInt(availibility);

    if (qty > availibility) {
        qty = '';
        document.getElementById("qty").value = qty;
        string_1 = "Please selet another batch or input ";
        string_2 = availibility;
        string_3 = " as qantity.";
        string_4 = string_1.concat(string_2).concat(string_3);
        window.alert(string_4);
    }
    // =============================== Item pack type calculation ======================
    let unitType = document.getElementById("item-unit-type").value;
    let itemWeightage = document.getElementById("item-weightage").value;
    let checkSum = '';
    let itemPackType = '';

    if (unitType == 'tablets' || unitType == 'capsules') {
        checkSum = parseInt(qty) % parseInt(itemWeightage);
        if (checkSum == 0) {
            itemPackType = 'Pack';
        } else {
            itemPackType = 'Loose';
        }
    } else {
        itemPackType = '';
    }
    document.getElementById("type-check").value = itemPackType;

    // =========================== ========================== ====================

    var pid = document.getElementById("product-id").value;
    var bno = document.getElementById("batch-no").value;
    let disc = document.getElementById("disc").value;
    let discPrice = document.getElementById('dPrice').value;
    let gst = document.getElementById('gst').value;
    let taxableAmount = '';
    let netPayble = '';

    if (disc != '') {
        disc = disc;
    }
    else {
        disc = 0;
    }


    if (qty > 0) {
        if (itemPackType == '') {
            // =========== (item except 'tab' or 'cap' calculation area) ===================
            discPrice = (parseFloat(mrp) - (parseFloat(mrp) * (parseFloat(disc) / 100)));
            netPayble = parseFloat(discPrice) * parseInt(qty);
            netPayble = parseFloat(netPayble).toFixed(2);
            discPrice = discPrice.toFixed(2);

            taxableAmount = (parseFloat(netPayble) * 100) / (parseFloat(gst) + 100);
            taxableAmount = parseFloat(taxableAmount).toFixed(2);

            document.getElementById('dPrice').value = discPrice;
            document.getElementById('taxable').value = taxableAmount;
            document.getElementById('amount').value = netPayble;
        } else {
            // =========== (item = tab or item = cap calculation area) ===================
            discPrice = (parseFloat(loosePrice) - (parseFloat(loosePrice) * (parseFloat(disc) / 100)));
            netPayble = parseFloat(discPrice) * parseInt(qty);
            netPayble = parseFloat(netPayble).toFixed(2);
            discPrice = discPrice.toFixed(2);

            taxableAmount = (parseFloat(netPayble) * 100) / (parseFloat(gst) + 100);
            taxableAmount = parseFloat(taxableAmount).toFixed(2);

            document.getElementById('dPrice').value = discPrice;
            document.getElementById('taxable').value = taxableAmount;
            document.getElementById('amount').value = netPayble;
        }
    } else {
        document.getElementById("dPrice").value = '';
        document.getElementById("amount").value = '';
        document.getElementById("type-check").value = '';
    }
    // console.log("DISCOUNT PRICE CHECK ON MARGINE  : ", discPrice);

    //==================== Margin on an Item ====================
    marginUrl = `ajax/product.stockDetails.getMargin.ajax.php?Pid=${pid}&Bid=${bno}&qtype=${itemPackType}&Mrp=${mrp}&Qty=${qty}&disc=${disc}`;
    xmlhttp.open("GET", marginUrl, false);
    xmlhttp.send(null);
    document.getElementById("margin").value = xmlhttp.responseText;
}


const ondDisc = (disc) => {

    var xmlhttp = new XMLHttpRequest();

    let mrp = document.getElementById("mrp").value;
    let itemWeatage = document.getElementById('item-weightage').value;
    let itemUnit = document.getElementById('item-unit-type').value;
    let loosePrice = "";
    if (itemUnit == 'tablets' || itemUnit == 'capsules') {
        loosePrice = parseFloat(mrp) / parseInt(itemWeatage);
    } else {
        loosePrice = '';
    }
    document.getElementById('loose-price').value = loosePrice;


    var pid = document.getElementById("product-id").value;
    var bno = document.getElementById("batch-no").value;
    let gst = document.getElementById('gst').value;
    let discPrice = document.getElementById('dPrice').value;

    let itemTypeCheck = document.getElementById("type-check").value;

    let qty = document.getElementById('qty').value;
    let availibility = document.getElementById('aqty').value;
    availibility = parseInt(availibility);

    availibility = parseInt(availibility);
    if (qty > availibility) {
        qty = availibility;
    }
    // console.log("check disc quantity : ", qty);

    if (disc != '') {
        disc = disc;
    }
    else {
        disc = 0;
    }

    if (qty > 0) {
        if (itemTypeCheck == '') {
            discPrice = (parseFloat(mrp) - (parseFloat(mrp) * (parseFloat(disc) / 100)));
            netPayble = parseFloat(discPrice) * parseInt(qty);
            netPayble = parseFloat(netPayble).toFixed(2);
            discPrice = discPrice.toFixed(2);

            taxableAmount = (parseFloat(netPayble) * 100) / (parseFloat(gst) + 100);
            taxableAmount = parseFloat(taxableAmount).toFixed(2);

            document.getElementById('dPrice').value = discPrice;
            document.getElementById('taxable').value = taxableAmount;
            document.getElementById('amount').value = netPayble;
        } else {
            discPrice = (parseFloat(loosePrice) - (parseFloat(loosePrice) * (parseFloat(disc) / 100)));
            netPayble = parseFloat(discPrice) * parseInt(qty);
            netPayble = parseFloat(netPayble).toFixed(2);
            discPrice = discPrice.toFixed(2);

            taxableAmount = (parseFloat(netPayble) * 100) / (parseFloat(gst) + 100);
            taxableAmount = parseFloat(taxableAmount).toFixed(2);

            document.getElementById('dPrice').value = discPrice;
            document.getElementById('taxable').value = taxableAmount;
            document.getElementById('amount').value = netPayble;
        }
    } else {
        document.getElementById("dPrice").value = '';
        document.getElementById("amount").value = '';
        document.getElementById("type-check").value = '';
        document.getElementById('dPrice').value = '';
    }

    //==================== Margin on an Item ====================
    marginUrl = `ajax/product.stockDetails.getMargin.ajax.php?Pid=${pid}&Bid=${bno}&qtype=${itemTypeCheck}&Mrp=${mrp}&Qty=${qty}&disc=${disc}`;
    xmlhttp.open("GET", marginUrl, false);
    xmlhttp.send(null);
    document.getElementById("margin").value = xmlhttp.responseText;
}

const addSummary = () => {

    let billDAte = document.getElementById("bill-date").value;
    let customer = document.getElementById("customer").value;
    let doctorName = document.getElementById("doctor-select").value;
    let paymentMode = document.getElementById("payment-mode").value;

    let productId = document.getElementById("product-id").value;
    let productName = document.getElementById("product-name").value;
    let batchNo = document.getElementById("batch-no").value;
    let crntStckItemId = document.getElementById("crnt-stck-itm-id").value;
    let weightage = document.getElementById("weightage").value;
    let itemWeightage = document.getElementById('item-weightage').value;
    let unitType = document.getElementById('item-unit-type').value;
    let expDate = document.getElementById("exp-date").value;
    let mrp = document.getElementById("mrp").value;
    let available = document.getElementById('aqty').value;
    let itemComposition = document.getElementById('productComposition').value;
    let qty = document.getElementById("qty").value;
    let qtyTypeCheck = document.getElementById("type-check").value;
    let Manuf = document.getElementById("manuf").value;
    let manufName = document.getElementById("manufName").value;
    // let rplceStrngManufName = document.getElementById("manufNameStrngReplace").value;
    let discPercent = document.getElementById("disc").value;
    // console.log("on add customer name check : ", customer);
    let discPrice = document.getElementById("dPrice").value;
    let gst = document.getElementById("gst").value;
    let taxable = document.getElementById("taxable").value;
    let taxableAmount = parseFloat(taxable);
    let amount = document.getElementById("amount").value;
    // let amnt = amount.toFixed(2);
    let looseStock = document.getElementById("loose-stock").value;
    let loosePrice = document.getElementById("loose-price").value;
    let ptr = document.getElementById("ptr").value;
    let marginAmount = document.getElementById("margin").value;


    // ============== per item gst amount calculation ============
    let netGstAmount = (parseFloat(amount) - parseFloat(taxable));
    netGstAmount = netGstAmount.toFixed(2);
    // console.log("net gst amount : ",netGstAmount);
    // ============ end of amount calculation ==============
    // ============ MRP SET ======================
    if (loosePrice != '') {
        calculatedMRP = loosePrice;
    } else {
        calculatedMRP = mrp;
    }

    //===========================================

    if (billDAte == '') {
        swal("Failed!", "Please Select Bill Date!", "error");
        return;
    }
    if (customer == '') {
        swal("Failed!", "Please Select Customer Name!", "error");
        return;
    }
    if (doctorName == '') {
        swal("Failed!", "Please Select/Enter Doctor Name!", "error");
        return;
    }
    if (paymentMode == '') {
        swal("Failed!", "Please Select a Payment Mode!", "error");
        return;
    }
    if (productId == '') {
        swal("Failed!", "Product ID Not Found!", "error");
        return;
    }
    if (productName == '') {
        swal("Failed!", "Product Name Not Found!", "error");
        return;
    }
    if (batchNo == '') {
        swal("Failed!", "Batch No Not Found!", "error");
        return;
    }
    if (weightage == '') {
        swal("Failed!", "Product Weatage/Unit Not Found!", "error");
        return;
    }
    if (expDate == '') {
        swal("Failed!", "Expiry Date Not Found!", "error");
        return;
    }
    if (mrp == '') {
        swal("Failed!", "MRP Not Found!", "error");
        return;
    }
    if (qty == '') {
        swal("Failed!", "Please Enter Quantity:", "error");
        return;
    }
    if (discPercent == '') {
        swal("Failed!", "Please Enter Discount Minimum: 0", "error");
        return;
    }
    if (discPrice == '') {
        swal("Failed!", "Discounted Price Not Found!", "error");
        return;
    }
    if (gst == '') {
        swal("Failed!", "GST Not Found!", "error");
        return;
    }
    if (amount == '') {
        swal("Failed!", "Total Amount Not Found!", "error");
        return;
    }

    // console.log("Working Fine");

    document.getElementById("no-item").style.display = "none";

    /////// SERIAL NUMBER SET /////////
    let slno = document.getElementById("dynamic-id").value;
    let slControl = document.getElementById("serial-control").value;
    slno++;
    slControl++;
    document.getElementById("dynamic-id").value = slno;
    document.getElementById("serial-control").value = slControl;


    ////////// ITEMS COUNT ////////////
    document.getElementById("items").value = slno;

    /// TOTAL QUANTITY COUNT CALCULATION ///
    let finalQty = document.getElementById("final-qty");
    let totalQty = parseInt(finalQty.value) + parseInt(qty);
    document.getElementById("final-qty").value = totalQty;

    ///////////TOTAL GST CALCULATION////////////
    let existsGst = parseFloat(document.getElementById("total-gst").value);
    let netGst = parseFloat(netGstAmount);
    let totalGst = existsGst + netGst;
    document.getElementById("total-gst").value = totalGst.toFixed(2);
    // =========================================

    /////////NET MRP CALCULATION//////////
    let totalPrice = document.getElementById("total-price").value;
    let existsPrice = parseFloat(totalPrice);
    var itemMrp = parseFloat(calculatedMRP);
    let itemQty = parseInt(qty);
    itemMrp = itemQty * itemMrp;
    let totalMrp = existsPrice + itemMrp;
    document.getElementById("total-price").value = totalMrp.toFixed(2);


    ////////////TOTAL PAYABLE //////////////
    let payable = document.getElementById("payable").value;
    let existsPayable = parseFloat(payable);
    let itemAmount = parseFloat(amount);
    let sum = existsPayable + itemAmount;
    document.getElementById("payable").value = sum.toFixed(2);

    jQuery("#item-body").append(`<tr id="table-row-${slControl}">

        <td><i class="fas fa-trash text-danger" onclick="deleteItem(${slControl}, ${qty}, ${netGst.toFixed(2)}, ${itemMrp.toFixed(2)}, ${amount})" style="font-size:.7rem; width: .3rem"></i></td>

        <td id="tr-${slControl}-col-2" style="font-size:.7rem; padding-top:1rem; width: .3rem" scope="row">${slno}</td>

        <td id="tr-${slControl}-col-3">
            <input class="summary-product" type="text" name="product-name[]" value="${productName}" style="word-wrap: break-word; width:9rem; font-size: .7rem;" readonly>
            <input type="text" class="d-none" name="product-id[]" value="${productId}" >
        </td>

        <td class="d-none" id="col-${slno}-prodId">${productId}</td>

        <td class="d-none">
            <input type="text" name="ManufId[]" value="${Manuf}">
            <input type="text" name="ManufName[]" value="${manufName}">
        </td>

        <td class="d-none" id="col-${slno}-batch">${batchNo}</td>

        <td class="d-none" id="tr-${slControl}-col-7">
            <input class="summary-items" type="text" name="current-stock-item-id[]" id="batch-no" value="${crntStckItemId}" style="word-wrap: break-word; width:7rem; font-size: .7rem; " readonly>
        </td>

        <td id="tr-${slControl}-col-7">
            <input class="summary-items" type="text" name="batch-no[]" id="batch-no" value="${batchNo}" style="word-wrap: break-word; width:7rem; font-size: .7rem; " readonly>
        </td>

        <td id="tr-${slControl}-col-8">
            <input class="summary-items" type="text" name="weightage[]" value="${weightage}" style="word-wrap: break-word; width:3rem; font-size: .7rem; " readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="ItemWeightage[]" value="${itemWeightage}" style="word-wrap: break-word; width:3rem; font-size: .7rem; " readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="ItemUnit[]" value="${unitType}" style="word-wrap: break-word; width:3rem; font-size: .7rem; " readonly>
        </td>
                                                
        <td id="tr-${slControl}-col-11">
            <input class="summary-items" type="text" name="exp-date[]" value="${expDate}" style="word-wrap: break-word; width:3rem; font-size: .7rem; " readonly>
        </td>

        <td id="tr-${slControl}-col-12">
            <input class="summary-items" type="text" name="mrp[]" value="${mrp}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        <td class="d-none" id="${ptr}">
            <input class="summary-items" type="text" name="itemPtr[]" value="${ptr}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="qtyTp[]" value="${qtyTypeCheck}" style="word-wrap: break-word; width:3rem; font-size: .7rem;" readonly>
        </td>

        <td class="d-none" id="col-${slno}-qty">${qty}</td>

        <td id="tr-${slControl}-col-16">
            <input class="summary-items" type="text" name="qty[]" value="${qty}" readonly>
        </td>

        <td id="tr-${slControl}-col-17">
            <input class="summary-items" type="text" name="discPercent[]" value="${discPercent}" style="word-wrap: break-word; width:3rem; font-size: .7rem; " readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="discPrice[]" value="${discPrice}" style="word-wrap: break-word; width:3rem; font-size: .7rem; " readonly>
        </td>

        <td id="tr-${slControl}-col-19">
            <input class="summary-items" type="text" name="taxable[]" value="${taxableAmount.toFixed(2)}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: end;" readonly>
        </td>

        <td id="tr-${slControl}-col-20">
            <input class="summary-items" type="text" name="gst[]" value="${gst}" style="word-wrap: break-word; width:3rem; font-size: .7rem;" readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="gstVal[]" value="${netGst.toFixed(2)}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        <td class="d-none" id="${marginAmount}">
            <input class="summary-items" type="text" name="marginAmount[]" value="${marginAmount}" style="word-wrap: break-word; width:3rem; font-size: .7rem;" readonly>
        </td>

        <td id="tr-${slControl}-col-23">
            <input class="summary-items" type="text" name="amount[]" value="${amount}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: end;" readonly>
        </td>

        /////////////////////\\\\\\\\\\\\\\\\\\\ EXTRA DATA /////////////////////\\\\\\\\\\\\\\\\\\\\

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="LooseStock[]" value="${looseStock}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="LoosePrice[]" value="${loosePrice}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="availibility[]" value="${available}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="itemComposition[]" value="${itemComposition}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        //////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    </tr>`);


    ////// TUPLE DECLEARATION and ON CLICK FUNCTION CALL///////

    taxable = taxableAmount.toFixed(2);

    const dataTuple = {
        slno: slControl,
        productId: productId,
        crntStckItemId : crntStckItemId,
        batchNo: batchNo,
        productName: productName,
        ManufId: Manuf,
        manufName: manufName,
        weightage: weightage,
        itemWeightage: itemWeightage,
        unitType: unitType,
        expDate: expDate,
        mrp: mrp,
        ptr: ptr,
        qtyTypeCheck: qtyTypeCheck,
        qty: qty,
        discPercent: discPercent,
        discPrice: discPrice,
        taxable: taxable,
        gst: gst,
        gstAmountPerItem: netGst,
        marginAmount: marginAmount,
        amount: amount,
        looseStock: looseStock,
        loosePrice: loosePrice,
        available: available,
        itemComposition: itemComposition
    };

    let tupleData = JSON.stringify(dataTuple);

    document.getElementById(`tr-${slControl}-col-2`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-3`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-7`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-8`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-11`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-12`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-16`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-17`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-19`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-20`).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-23`).onclick = function () {
        editItem(tupleData);
    };

    //////////////////////////////////////////

    document.getElementById('final-doctor-name').value = doctorName;
    document.getElementById("aqty").value = "";
    document.getElementById("exta-details").style.display = "none";
    document.getElementById("add-item-details").reset();
    event.preventDefault();
    /////////////////////////////////////////////

    newSellGenerateBill.removeAttribute("disabled");
}

const deleteItem = (slno, itemQty, gstPerItem, totalMrp, itemAmount) => {

    let delRow = slno;

    //////////////////////////////////////////////////
    jQuery(`#table-row-${slno}`).remove();
    let slVal = document.getElementById("dynamic-id").value;
    document.getElementById("dynamic-id").value = parseInt(slVal) - 1;

    // Items 
    var items = document.getElementById("items").value;
    leftItems = parseInt(items) - 1;
    document.getElementById("items").value = leftItems;

    var existQty = document.getElementById("final-qty");
    leftQty = existQty.value - itemQty;
    existQty.value = leftQty;

    var existGst = document.getElementById("total-gst");
    leftGst = existGst.value - gstPerItem;
    existGst.value = leftGst.toFixed(2);


    var existMrp = document.getElementById("total-price");
    leftMrp = existMrp.value - totalMrp;
    existMrp.value = leftMrp.toFixed(2);

    var existAmount = document.getElementById("payable");
    leftAmount = existAmount.value - parseFloat(itemAmount);
    existAmount.value = leftAmount.toFixed(2);

    rowAdjustment(delRow);

    let tBody = document.getElementById('item-body');
        console.log(tBody.getElementsByTagName('tr').length);
        if(tBody.getElementsByTagName('tr').length == 0){
            newSellGenerateBill.setAttribute("disabled", "true");
        }

}

////////////////// ROW ADJUSTMENT ///////////////////
function rowAdjustment(delRow) {
    let tableId = document.getElementById("item-body");
    let j = 0;
    let colIndex1 = 1;

    for (let i = 0; i < tableId.rows.length; i++) {
        j++;

        let row = tableId.rows[i];
        // console.log(row);
        let cell1 = row.cells[colIndex1];
        cell1.innerHTML = j;
    }
}

//////////////////////// ITEM EDIT FUNCTION /////////////////////////

const editItem = (tuple) => {
    
    // console.log(tuple);

    let checkEditOption = document.getElementById("product-id").value;

    if (checkEditOption == '') {

        Tupledata = JSON.parse(tuple);

        document.getElementById("product-id").value = Tupledata.productId;
        document.getElementById("product-name").value = Tupledata.productName;
        document.getElementById("crnt-stck-itm-id").value = Tupledata.crntStckItemId;
        document.getElementById("batch-no").value = Tupledata.batchNo;
        document.getElementById("batch_no").value = Tupledata.batchNo;

        document.getElementById("weightage").value = Tupledata.weightage;
        document.getElementById('item-weightage').value = Tupledata.itemWeightage;
        document.getElementById('item-unit-type').value = Tupledata.unitType;

        document.getElementById("exp-date").value = Tupledata.expDate;
        document.getElementById("mrp").value = Tupledata.mrp;
        document.getElementById("ptr").value = Tupledata.ptr;

        document.getElementById("qty").value = Tupledata.qty;
        document.getElementById("type-check").value = Tupledata.qtyTypeCheck;
        document.getElementById("manuf").value = Tupledata.ManufId;
        document.getElementById("manufName").value = Tupledata.manufName;

        document.getElementById("disc").value = Tupledata.discPercent;
        document.getElementById("dPrice").value = Tupledata.discPrice;
        document.getElementById("gst").value = Tupledata.gst;

        document.getElementById("taxable").value = Tupledata.taxable;
        document.getElementById("margin").value = Tupledata.marginAmount;
        document.getElementById("amount").value = Tupledata.amount;

        document.getElementById("loose-stock").value = Tupledata.looseStock;
        document.getElementById("loose-price").value = Tupledata.loosePrice;
        document.getElementById("aqty").value = Tupledata.available;
        document.getElementById("productComposition").value = Tupledata.itemComposition;

        let netMRP = '';
        if(Tupledata.unitType == 'tab' || Tupledata.unitType == 'cap'){
            netMRP = parseFloat(Tupledata.loosePrice) * parseInt(Tupledata.qty);
            netMRP = parseFloat(netMRP).toFixed(2);
        }else{
            netMRP = parseFloat(Tupledata.mrp) * parseInt(Tupledata.qty);
            netMRP = parseFloat(netMRP).toFixed(2);
        }
        

        deleteItem(Tupledata.slno, Tupledata.qty, Tupledata.gstAmountPerItem, netMRP, Tupledata.amount);


        document.getElementById("exta-details").style.display = "block";
        newSellGenerateBill.setAttribute("disabled", "true");

    } else {
        swal("Can't Edit", "Please add/edit previous item first.", "error");
        document.getElementById("qty").focus();
    }
}