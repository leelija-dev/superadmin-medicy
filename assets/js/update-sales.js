var updateSalesBtn = document.getElementById("update-sales-btn");

/////////////////////////////// edit item from table select \\\\\\\\\\\\\\\\\\\\\\\\

const editItem = (stockOutId, itemId, slno, itemQty, gstamnt, mrpPerItem, payblePerItem) => {
    if (document.getElementById('product-id').value == '') {

        $.ajax({
            url: "ajax/newSalesEdit.ajax.php",
            type: "POST",
            data: {
                stock_out_details_id: stockOutId,
                Stock_out_item_id: itemId
            },
            success: function (data) {
                // alert(data);
                var dataObject = JSON.parse(data);

                var sellQty = parseInt(dataObject.sellQty);
                var mrp = parseFloat(dataObject.Mrp);
                var itemUnit = dataObject.itemUnit;
                var itemWeatage = parseInt(dataObject.itemWeatage);
                var discPercent = parseFloat(dataObject.dicPercent);
                var looseStock = '';
                var loosePrice = '';
                var typeCheck = '';

                if (itemUnit == 'tab' || itemUnit == 'cap') {
                    looseStock = dataObject.availableQty;;
                    loosePrice = parseFloat(mrp) / parseInt(itemWeatage);
                    if (sellQty % itemWeatage == 0) {
                        typeCheck = 'Pack';
                    } else {
                        typeCheck = 'Loose';
                    }
                } else {
                    looseStock = '';
                    loosePrice = '';
                    typeCheck = '';
                }

                var discPrice = parseFloat(mrp) - (parseFloat(mrp) * parseFloat(discPercent) / 100);
                //==============================================================
                document.getElementById('invoice-id').value = dataObject.invoiceId;
                document.getElementById('stock-out-details-id').value = dataObject.stockOutDetailsId;
                document.getElementById('item-id').value = dataObject.itemId;
                document.getElementById('product-id').value = dataObject.productId;
                document.getElementById('search-Item').value = dataObject.productName;
                document.getElementById('manuf').value = dataObject.manufId;
                document.getElementById('manufName').value = dataObject.manufName;
                document.getElementById('productComposition').value = dataObject.productComposition;
                document.getElementById('batch-no').value = dataObject.batchNo;
                document.getElementById('weightage').value = dataObject.packOf;
                document.getElementById('item-weightage').value = dataObject.itemWeatage;
                document.getElementById('item-unit').value = dataObject.itemUnit;
                document.getElementById('exp-date').value = dataObject.expDate;
                document.getElementById('mrp').value = dataObject.Mrp;
                document.getElementById('loose-price').value = loosePrice;
                document.getElementById('ptr').value = dataObject.Ptr;
                document.getElementById('aqty').value = dataObject.availableQty;
                document.getElementById('loose-stock').value = looseStock;
                document.getElementById('qty').value = dataObject.sellQty;
                document.getElementById('type-check').value = typeCheck;
                document.getElementById('disc').value = dataObject.dicPercent;
                document.getElementById('dPrice').value = discPrice;
                document.getElementById('gst').value = dataObject.gstPercent;
                document.getElementById('margin').value = dataObject.margin;
                document.getElementById('taxable').value = dataObject.taxable;
                document.getElementById('amount').value = dataObject.paybleAmount;
                //==============================================================
                document.getElementById("exta-details").style.display = "block";

                deleteItem(slno, itemQty, gstamnt, mrpPerItem, payblePerItem);

                updateSalesBtn.setAttribute("disabled", "true");
            }
        })
    } else {
        swal("Failed!", "Please ADD previous data first!", "error");
    }

}

///////////////////////////////////////////////////////////////////////////////////////

const getDate = (date) => {
    // alert(date);
    document.getElementById("final-bill-date").value = date;
}

const addCustomerModal = () => {
    let url = "ajax/customer.addNew.ajax.php";
    $(".add-customer-modal").html(
        '<iframe width="99%" height="500px" frameborder="0" allowtransparency="true" src="' +
        url + '"></iframe>');
}

const getCustomer = (customer) => {
    if (customer.length > 0) {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            // console.log(customer);
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
    // console.log(id);
    // document.getElementById("contact-box").style.display = "block";

    var xmlhttp = new XMLHttpRequest();

    // ================ get Name ================
    stockCheckUrl = 'ajax/customer.getDetails.ajax.php?name=' + id;
    // alert(url);
    xmlhttp.open("GET", stockCheckUrl, false);
    xmlhttp.send(null);
    document.getElementById("customer").value = xmlhttp.responseText;
    document.getElementById("customer-name").value = xmlhttp.responseText;
    document.getElementById("customer-id").value = id;

    // ================ get Contact ================
    stockCheckUrl = 'ajax/customer.getDetails.ajax.php?contact=' + id;
    // alert(url);
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


const getDoctor = (doctor) => {
    document.getElementById("final-doctor-name").value = doctor;
} // end getCustomer

const getPaymentMode = (mode) => {
    document.getElementById("final-payment").value = mode;
}

/////////////////////////////// ITEM SEARCH STARTS HEAR \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/////////////// making search item focused fist value not a space \\\\\\\\\\\\\\\\\\
const firstInput = document.getElementById('search-Item');

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
//==============================================================
const searchItem = (searchFor) => {

    let searchReult = document.getElementById('searched-items');
    document.getElementById("searched-items").style.display = "block";
    document.getElementById("exta-details").style.display = "none";
    document.getElementById("select-batch").style.display = "none";

    if (document.getElementById("search-Item").value == "") {
        document.getElementById("searched-items").style.display = "none";
        document.getElementById("exta-details").style.display = "none";
        document.getElementById("select-batch").style.display = "none";
    }

    if (searchFor.length == 0) {
        document.getElementById("search-Item").value = '';
        document.getElementById("weightage").value = '';
        document.getElementById("batch-no").value = '';
        document.getElementById("exp-date").value = '';
        document.getElementById("mrp").value = '';
        document.getElementById("gst").value = '';

    } else {
       if(searchFor.length > 2){
        var XML = new XMLHttpRequest();
        XML.onreadystatechange = function () {
            if (XML.readyState == 4 && XML.status == 200) {
                searchReult.innerHTML = XML.responseText;
            }
        };
        XML.open('GET', 'ajax/sales-item-list.ajax.php?data=' + searchFor, true);
        XML.send();
       }
    }
}

const itemsBatchDetails = (prodcutId, name, stock) => {
    // console.log(prodcutId);

    if (parseInt(stock) > 0) {

        // document.getElementById("product-id").value = prodcutId;
        // ==================== SEARCH PRODUCT NAME =====================
        document.getElementById("search-Item").value = name;
        document.getElementById("searched-items").style.display = "none";
        // ==================== EOF PRODUCT NAME SEARCH ================

        let searchReult = document.getElementById('select-batch');

        document.getElementById("select-batch").style.display = "block";
        document.getElementById("exta-details").style.display = "none";

        document.getElementById("batch-no").value = '';
        document.getElementById("weightage").value = '';
        document.getElementById("exp-date").value = '';
        document.getElementById("mrp").value = '';
        document.getElementById("gst").value = '';

        // document.getElementById("item-weightage").value = '';
        // document.getElementById("item-unit-type").value = '';
        // document.getElementById("aqty").value = '';
        // document.getElementById("type-check").value = '';
        document.getElementById("qty").value = '';
        document.getElementById("disc").value = '';
        document.getElementById("dPrice").value = '';
        // document.getElementById("taxable").value = '';
        document.getElementById("amount").value = '';

        var XML = new XMLHttpRequest();
        XML.onreadystatechange = function () {
            if (XML.readyState == 4 && XML.status == 200) {
                searchReult.innerHTML = XML.responseText;
            }
        };
        XML.open('GET', 'ajax/sales-item-batch-list.ajax.php?prodId=' + prodcutId, true);
        XML.send();
    }


    if (parseInt(stock) <= 0) {

        document.getElementById("search-Item").value = '';
        document.getElementById("weightage").value = '';
        document.getElementById("batch-no").value = '';
        document.getElementById("exp-date").value = '';
        document.getElementById("mrp").value = '';
        document.getElementById("gst").value = '';

        // document.getElementById("item-weightage").value = '';
        // document.getElementById("item-unit-type").value = '';
        // document.getElementById("aqty").value = '';
        // document.getElementById("type-check").value = '';
        document.getElementById("qty").value = '';
        document.getElementById("disc").value = '';
        document.getElementById("dPrice").value = '';
        // document.getElementById("taxable").value = '';
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

/////////////////////////// END OF ITEM SEARCH \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//////////////////////////////////////////////////////////////////////////////

const stockDetails = (productId, batchNo, itemId) => {

    document.getElementById("product-id").value = productId;
    document.getElementById("batch-no").value = batchNo;

    document.getElementById("exta-details").style.display = "block";
    document.getElementById("searched-items").style.display = "none";
    document.getElementById("select-batch").style.display = "none";

    let currenStockItemId = itemId;
    document.getElementById("item-id").value = currenStockItemId;
    // console.log(currenStockItemId);

    var xmlhttp = new XMLHttpRequest();

    // ============== Check Existence ==============
    stockCheckUrl = `ajax/stock.checkExists.ajax.php?Pid=${productId}&batchNo=${batchNo}`;
    xmlhttp.open("GET", stockCheckUrl, false);
    xmlhttp.send(null);
    exist = xmlhttp.responseText;

    if (exist == 1) {
        document.getElementById("exta-details").style.display = "block";

        // ============== Product Name ==============
        stockItemUrl = 'ajax/getProductDetails.ajax.php?id=' + productId;
        // alert(url);
        xmlhttp.open("GET", stockItemUrl, false);
        xmlhttp.send(null);
        let prodName = xmlhttp.responseText;
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
        unitUrl = 'ajax/getProductDetails.ajax.php?unit=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        let packUnit = xmlhttp.responseText;
        let packOf = `${packWeightage}${packUnit}`;
        document.getElementById("weightage").value = packOf;
        document.getElementById("item-unit").value = xmlhttp.responseText;
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
        // document.getElementById("loose-stock").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Loose Price ====================
        loosePriceUrl = `ajax/getProductDetails.ajax.php?loosePrice=${productId}&batchNo=${batchNo}`;
        // alert(ptrUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", loosePriceUrl, false);
        xmlhttp.send(null);
        document.getElementById("loose-price").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

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

        //==================== Content ====================
        contentUrl = 'ajax/product.getContent.ajax.php?pid=' + productId;
        xmlhttp.open("GET", contentUrl, false);
        xmlhttp.send(null);
        document.getElementById("productComposition").value = xmlhttp.responseText;
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
        document.getElementById("item-unit").value = '';
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
    console.log("item qty input check : "+qty);
    var xmlhttp = new XMLHttpRequest();

    let mrp = document.getElementById("mrp").value;
    let itemWeatage = document.getElementById('item-weightage').value;
    let itemUnit = document.getElementById('item-unit').value;
    let loosePrice = "";
    if (itemUnit == 'tab' || itemUnit == 'cap') {
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
        document.getElementById('taxable').value = '';
        string_1 = "Please selet another batch or input ";
        string_2 = availibility;
        string_3 = " as qantity.";
        string_4 = string_1.concat(string_2).concat(string_3);
        window.alert(string_4);
    }
    // =============================== Item pack type calculation ======================

    let unitType = document.getElementById("item-unit").value;
    
    let itemWeightage = document.getElementById("item-weightage").value;
    
    let checkSum = '';
    let itemPackType = '';

    if (unitType == 'tab' || unitType == 'cap') {
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
        document.getElementById("dPrice").value ='0';
        document.getElementById("amount").value = '0';
        document.getElementById('disc').value = '0';
        document.getElementById('taxable').value = '0';
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
    let itemUnit = document.getElementById('item-unit').value;
    let loosePrice = "";
    if (itemUnit == 'tab' || itemUnit == 'cap') {
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
        document.getElementById("dPrice").value = '0';
        document.getElementById('disc').value = '0';
        document.getElementById("amount").value = '0';
        document.getElementById('taxable').value = '0';
        document.getElementById("type-check").value = '';
        document.getElementById('dPrice').value = '0';
    }

    //==================== Margin on an Item ====================
    marginUrl = `ajax/product.stockDetails.getMargin.ajax.php?Pid=${pid}&Bid=${bno}&qtype=${itemTypeCheck}&Mrp=${mrp}&Qty=${qty}&disc=${disc}`;
    xmlhttp.open("GET", marginUrl, false);
    xmlhttp.send(null);
    document.getElementById("margin").value = xmlhttp.responseText;
}


/////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

const addSummary = () => {

    let billDAte = document.getElementById("bill-dt").value;
    let customer = document.getElementById("customer").value;
    let doctorName = document.getElementById("doctor-select").value;
    let paymentMode = document.getElementById("payment-mode").value;

    let invoiceId = document.getElementById('invoice-id').value;
    let stockOutDetailsId = document.getElementById("stock-out-details-id").value;
    let itemId = document.getElementById("item-id").value;


    let Manuf = document.getElementById("manuf").value;
    let ManufName = document.getElementById('manufName').value;
    let productId = document.getElementById("product-id").value;
    let productName = document.getElementById("search-Item").value;
    let prodComposition = document.getElementById('productComposition').value;
    let batchNo = document.getElementById("batch-no").value;

    let weightage = document.getElementById("weightage").value;
    let itemUnit = document.getElementById("item-unit").value;
    let itemPower = document.getElementById("item-weightage").value;
    let expDate = document.getElementById("exp-date").value;
    let mrp = document.getElementById("mrp").value;
    mrp = parseFloat(mrp);

    let availabel = document.getElementById('aqty').value;
    let qty = document.getElementById("qty").value;
    let qtyType = document.getElementById("type-check").value;


    let disc = document.getElementById("disc").value;
    let dPrice = document.getElementById("dPrice").value;
    let gst = document.getElementById("gst").value;
    let taxable = document.getElementById('taxable').value;
    let amount = document.getElementById("amount").value;
    let looseStock = document.getElementById("loose-stock").value;
    let loosePrice = document.getElementById("loose-price").value;
    let itemPtr = document.getElementById("ptr").value;
    let margin = document.getElementById("margin").value;

    // console.log("item ptr check : ",itemPtr);
    // console.log("item margin check : ",margin);

    // ============== per item gst amount calculation ============
    let netGstAmount = (parseFloat(amount) - parseFloat(taxable));
    netGstAmount = netGstAmount.toFixed(2);
    // console.log("net gst amount : ",netGstAmount);
    // ============ end of amount calculation ==============

    // // ============ MRP SET ======================
    // if (loosePrice != '') {
    //     mrp = loosePrice;
    // }else{
    //     mrp = mrp;
    // }

    // mrp = parseFloat(mrp);


    ////////////////////////////////////////////////////////////////////////////////////////////
    if (billDAte == '') {
        swal("Failed!", "Please Enter Bill Date!", "error");
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
    if (expDate == '') {
        swal("Failed!", "Expiry Date Not Found!", "error");
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
    if (mrp == '') {
        swal("Failed!", "MRP Not Found!", "error");
        return;
    }
    if (qty == '') {
        swal("Failed!", "Please Enter Quantity:", "error");
        return;
    }
    if (disc == '') {
        swal("Failed!", "Please Enter Discount Minimum: 0", "error");
        return;
    }
    if (dPrice == '') {
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



    /////////////////// sl no control area \\\\\\\\\\\\\\\\\\\\\

    let slno = document.getElementById("dynamic-id").value;
    let slControl = document.getElementById("serial-control").value;
    slno++;
    slControl++;
    document.getElementById("dynamic-id").value = slno;
    document.getElementById("items").value = slno;
    document.getElementById("serial-control").value = slControl;

    ////////////////////// total qantity count \\\\\\\\\\\\\\\\\\\\\\\\\\
    let finalQty = document.getElementById("final-qty");
    let totalQty = parseFloat(finalQty.value) + parseFloat(qty);
    // console.log(totalQty);
    finalQty.value = totalQty;

    ////////////////TOTAL GST CALCULATION//////////////////////
    let existsGst = parseFloat(document.getElementById("total-gst").value);
    var itemAmount = parseFloat(amount);
    var itemTaxable = parseFloat(taxable);
    let netGst = parseFloat(itemAmount) - parseFloat(itemTaxable);
    let totalGst = existsGst + netGst;
    // console.log(totalGst);
    document.getElementById("total-gst").value = totalGst.toFixed(2);
    // =========================================
    // dPrice.value * qty.value

    ////////////////TOTAL MRP CALCULATION//////////////////////
    let totalPrice = document.getElementById("total-price").value;
    let existsPrice = parseFloat(totalPrice);
    var itemMrp = parseFloat(mrp);
    itemQty = parseFloat(qty);
    if (itemUnit == 'tab' || itemUnit == 'cap') {
        itemMrp = itemQty * (itemMrp / parseFloat(itemPower));
    } else {
        itemMrp = itemQty * itemMrp;
    }
    // console.log("item type : ",itemUnit);
    // console.log("item mrp : ",itemMrp);
    let totalMrp = existsPrice + itemMrp;
    // console.log(totalMrp);
    document.getElementById("total-price").value = totalMrp.toFixed(2);


    /////////////////TOTAL PAYABLE ///////////////////////
    let payable = document.getElementById("payable").value;
    let existsPayable = parseFloat(payable);
    itemAmount = parseFloat(amount);
    let sum = existsPayable + itemAmount;
    // console.log(sum);
    document.getElementById("payable").value = sum.toFixed(2);

    jQuery("#item-body").append(`<tr id="table-row-${slControl}">

            <td style="color: red;"><i class="fas fa-trash text-danger" onclick="deleteItem(${slControl}, ${qty}, ${netGst}, ${itemMrp.toFixed(2)}, ${amount})" style="font-size:.9rem; width: .3rem"></i></td>

            <td id="tr-${slControl}-col-1" style="font-size:.9rem; padding-top:1rem; width: .3rem" scope="row">${slno}</td>

            <td id="tr-${slControl}-col-2">
                <input class="summary-items" type="text" name="product-name[]" value="${productName}" readonly style="width: 12rem;">
                <input class="d-none summary-items" type="text" name="product-id[]" value="${productId}"   >
                <input class="d-none summary-items" type="text" name="item-id[]" value="${itemId}"   >
                <input class="d-none summary-items" type="text" name="Manuf[]" value="${Manuf}"   >
            </td>

            <td class="d-none">
                <input type="text" name="stockOut-details-id[]" value="${stockOutDetailsId}" readonly>
            </td>

            <td id="tr-${slControl}-col-3">
                <input class="summary-items" type="text" name="batch-no[]" value="${batchNo}" readonly style="width: 6rem;">
            </td>

            <td id="tr-${slControl}-col-4">
                <input class="summary-items" type="text" name="weightage[]" value="${weightage}" readonly>
                <input class="d-none summary-items" type="text" name="ItemUnit[]" value="${itemUnit}" readonly style="width:3rem">
                <input class="d-none summary-items" type="text" name="ItemPower[]" value="${itemPower}" readonly style="width:3rem">
            </td>
            
            <td id="tr-${slControl}-col-5">
                <input class="summary-items" type="text" name="exp-date[]" value="${expDate}" readonly style="width : 4rem;">
            </td>

            <td id="tr-${slControl}-col-6">
                <input class="summary-items" type="text" name="mrp[]" value="${mrp}" readonly>
            </td>

            <td id="tr-${slControl}-col-7">
                <input class="summary-items" type="text" name="disc[]" value="${disc}" readonly>
                <input class="d-none summary-items" type="text" name="dPrice[]" value="${parseFloat(dPrice).toFixed(2)}" readonly>
            </td>

            <td id="tr-${slControl}-col-8">
                <input class="summary-items" type="text" name="gst[]" value="${gst}%" readonly>
                <input class="d-none summary-items" type="text" name="gst-amount[]" value="${netGstAmount}" style="width:3rem">
            </td>
            
            <td id="tr-${slControl}-col-9">
                <input class="summary-items" type="text" name="qty[]" value="${qty}" readonly>
                <input class="d-none summary-items" type="text" name="qty-type[]" value="${qtyType}" readonly style="width:3rem">
            </td>

            <td id="tr-${slControl}-col-10">
                <input class="summary-items" type="text" name="taxable[]" value="${taxable}" readonly>
            </td>
            
            <td id="tr-${slControl}-col-11">
                <input class="summary-items" type="text" name="amount[]" value="${amount}" readonly>
            </td>


            /////////////////////////////// EXTRA DATA /////////////////////////////

            <td class=" ">
                
            </td>
            <td class=" ">
                
            </td>

            <td class="d-none">
                <input class="summary-items" type="text" name="ptr[]" value="${itemPtr}" readonly>
            </td>
            <td class="d-none">
                <input class="summary-items" type="text" name="margin[]" value="${margin}" readonly>
            </td>
        </tr>`);

    //===================================================

    const dataTuple = {
        slno: slControl,
        invoiceId: invoiceId,
        stockOutDetailsId: stockOutDetailsId,
        itemId: itemId,
        Manuf: Manuf,
        ManufName: ManufName,
        productId: productId,
        productName: productName,
        prodComposition: prodComposition,
        batchNo: batchNo,
        weightage: weightage,
        itemUnit: itemUnit,
        itemPower: itemPower,
        expDate: expDate,
        availabel: availabel,
        qty: qty,
        qtyType: qtyType,
        mrp: mrp,
        gst: gst,
        disc: disc,
        dPrice: dPrice,
        taxable: taxable,
        amount: amount,
        looseStock: looseStock,
        loosePrice: loosePrice,
        itemPtr: itemPtr,
        margin: margin,
    };

    let tupleData = JSON.stringify(dataTuple);

    document.getElementById(`tr-${slControl}-col-1`).onclick = function () {
        itemEditOption(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-2`).onclick = function () {
        itemEditOption(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-3`).onclick = function () {
        itemEditOption(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-4`).onclick = function () {
        itemEditOption(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-5`).onclick = function () {
        itemEditOption(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-6`).onclick = function () {
        itemEditOption(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-7`).onclick = function () {
        itemEditOption(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-8`).onclick = function () {
        itemEditOption(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-9`).onclick = function () {
        itemEditOption(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-10`).onclick = function () {
        itemEditOption(tupleData);
    };
    document.getElementById(`tr-${slControl}-col-11`).onclick = function () {
        itemEditOption(tupleData);
    };

    /////////////////////////////////////////////////////

    document.getElementById('sales-edit-form').reset();
    document.getElementById("exta-details").style.display = "none";
    updateSalesBtn.removeAttribute("disabled");

}


/////////////////////////////////// delete item from table row \\\\\\\\\\\\\\\\\\\\\\\\\\\\\

const deleteItem = (slno, itemQty, gstPerItem, totalMrp, itemAmount) => {

    let delRow = slno;

    jQuery(`#table-row-${slno}`).remove();
    let slVal = document.getElementById("dynamic-id").value;
    document.getElementById("dynamic-id").value = parseInt(slVal) - 1;

    ////////////// details data control on delete \\\\\\\\\\\\\
    var items = document.getElementById("items");
    leftItems = items.value - 1;
    items.value = leftItems;

    var existQty = document.getElementById("final-qty");
    leftQty = existQty.value - itemQty;
    existQty.value = leftQty;

    var existGst = document.getElementById("total-gst");
    leftGst = parseFloat(existGst.value) - parseFloat(gstPerItem).toFixed(2);
    // leftGst = parseFloat(leftGst);
    leftGst = leftGst.toFixed(2);
    existGst.value = leftGst;

    var existMrp = document.getElementById("total-price");
    leftMrp = parseFloat(existMrp.value) - parseFloat(totalMrp);
    leftMrp = parseFloat(leftMrp);
    existMrp.value = leftMrp.toFixed(2);

    var existAmount = document.getElementById("payable");
    leftAmount = parseFloat(existAmount.value) - parseFloat(itemAmount);
    leftAmount = parseFloat(leftAmount);
    existAmount.value = leftAmount.toFixed(2);

    // document.getElementById("no-item").style.display = "none";

    rowAdjustment(delRow);


    //update button contorl ================ 
    
    let tBody = document.getElementById('item-body');
        if(tBody.getElementsByTagName('tr').length == 0){
            updateSalesBtn.setAttribute("disabled", "true");
        }
}

function rowAdjustment(delRow) {

    let tableId = document.getElementById("item-body");
    let j = 0;
    let colIndex = 1;

    for (let i = 0; i < tableId.rows.length; i++) {
        j++;
        let row = tableId.rows[i];
        let cell = row.cells[colIndex];
        cell.innerHTML = j;
    }
}


const itemEditOption = (tuple) => {
    // console.log(tuple);

    if (document.getElementById('product-id').value == '') {
        let tData = JSON.parse(tuple);

        document.getElementById('invoice-id').value = tData.invoiceId;
        document.getElementById('stock-out-details-id').value = tData.stockOutDetailsId;

        document.getElementById('item-id').value = tData.itemId;
        document.getElementById('product-id').value = tData.productId;
        document.getElementById('search-Item').value = tData.productName;
        document.getElementById('manuf').value = tData.Manuf;
        document.getElementById('manufName').value = tData.ManufName;

        document.getElementById('productComposition').value = tData.prodComposition;

        document.getElementById('batch-no').value = tData.batchNo;
        document.getElementById('weightage').value = tData.weightage;
        document.getElementById('item-weightage').value = tData.itemPower;
        document.getElementById('item-unit').value = tData.itemUnit;
        document.getElementById('exp-date').value = tData.expDate;
        document.getElementById('aqty').value = tData.availabel;

        document.getElementById('qty').value = tData.qty;
        document.getElementById('type-check').value = tData.qtyType;

        document.getElementById('mrp').value = tData.mrp;
        document.getElementById('ptr').value = tData.itemPtr;
        document.getElementById('gst').value = tData.gst;
        document.getElementById('disc').value = tData.disc;
        document.getElementById('dPrice').value = tData.dPrice;
        document.getElementById('taxable').value = tData.taxable;
        document.getElementById('amount').value = tData.amount;

        document.getElementById('loose-stock').value = tData.looseStock;
        document.getElementById('loose-price').value = tData.loosePrice;
        document.getElementById('margin').value = tData.margin;

        //----------------------------------------------------
        let gstPerItem = parseFloat(tData.amount) - parseFloat(tData.taxable);
        let MRP = '';
        if(tData.itemUnit == 'tab' || tData.itemUnit == 'cap'){
            MRP = tData.loosePrice;
        }else{
            MRP = tData.mrp;
        }
        let totalMrp = parseFloat(MRP) * parseInt(tData.qty);
        totalMrp = parseFloat(totalMrp).toFixed(2);
        deleteItem(tData.slno, tData.qty, gstPerItem, totalMrp, tData.amount);
        
        document.getElementById("exta-details").style.display = "block";

    }else{
        swal("Failed!", "Please ADD previous data first!", "error");
    }

}