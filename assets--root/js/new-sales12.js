// Document elements
const extaDetails = document.getElementById("exta-details");
const tableBody = document.getElementById('item-body');
const newSellGenerateBill = document.getElementById('new-sell-bill-generate');
const billDate = document.getElementById("bill-date");
const finalBillDate = document.getElementById('final-bill-date');
const paymentMode = document.getElementById("payment-mode");
const finalPayment = document.getElementById("final-payment");
const customerList = document.getElementById("customer-list");
const firstInput = document.getElementById('product-name');
const searchedItems = document.getElementById('searched-items');
const searchedBatchNo = document.getElementById('searched-batchNo');

// Disable new bill generation by default
newSellGenerateBill.setAttribute("disabled", "true");

// Set date to today
const setDate = new Date();
const today = setDate.toISOString().slice(0, 10);

// Set default and max date to today
[billDate, finalBillDate].forEach(dateField => {
    dateField.value = today;
    dateField.max = today;
});

// Function to set date, ensuring it does not exceed today
const getDate = (date) => {
    if (new Date(date) > setDate) {
        alert("Date cannot be beyond the current date.");
        finalBillDate.value = today;
    } else {
        finalBillDate.value = date;
    }
}

// ADD NEW CUSTOMER =======================================================================
const addCustomerModal = () => {
    const url = "ajax/customer.addNew.ajax.php";
    $(".add-customer-modal").html(
        `<iframe width="99%" height="330px" frameborder="0" allowtransparency="true" src="${url}"></iframe>`
    );
}

// GET CUSTOMER DETAILS
const getCustomer = (customer) => {
    if (customer.length > 0) {
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                customerList.style.display = "block";
                customerList.innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", `ajax/customerSearch.ajax.php?data=${customer}`, true);
        xmlhttp.send();
    } else {
        customerList.style.display = "none";
    }
}

// Set customer details
const setCustomer = (id) => {
    const xmlhttp = new XMLHttpRequest();
    const setCustomerDetail = (type, id) => {
        xmlhttp.open("GET", `ajax/customer.getDetails.ajax.php?${type}=${id}`, false);
        xmlhttp.send(null);
        return xmlhttp.responseText;
    }

    document.getElementById("customer").value = setCustomerDetail("name", id);
    document.getElementById("customer-name").value = setCustomerDetail("name", id);
    document.getElementById("customer-id").value = id;
    document.getElementById("contact").innerHTML = setCustomerDetail("contact", id);
    customerList.style.display = "none";
}

// Reset customer details to Cash Sales
const counterBill = () => {
    ["customer", "customer-id", "customer-name", "final-doctor-name"].forEach(field => {
        document.getElementById(field).value = "Cash Sales";
    });
    document.getElementById("doctor-select").value = 'Cash Sales';
}

// Set payment mode
paymentMode.value = 'Cash';
finalPayment.value = 'Cash';
const getPaymentMode = (mode) => {
    finalPayment.value = mode;
}

// Focus first input on load
window.addEventListener('load', () => firstInput.focus());

// Prevent space as the first character in input
firstInput.addEventListener('input', function () {
    if (this.value.startsWith(' ')) {
        this.value = this.value.trimStart();
    }
});

// Clear batch list when product name is empty
const searchItem = (searchFor) => {
    if (!searchFor) {
        ["searched-items", "searched-batchNo"].forEach(id => {
            document.getElementById(id).style.display = "none";
        });

        ["product-name", "weightage", "batch-no", "exp-date", "mrp", "gst", "item-weightage", "item-unit-type", "aqty", "type-check", "qty", "disc", "dPrice", "taxable", "amount"].forEach(id => {
            document.getElementById(id).value = '';
        });

        searchedItems.innerHTML = '';
        document.getElementById('searched-batchNo').innerHTML = '';
        return;
    }

    if (searchFor.length > 2) {
        searchedItems.style.display = "block";
        extaDetails.style.display = "none";

        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                searchedItems.innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open('GET', `ajax/sales-item-list.ajax.php?data=${searchFor}`, true);
        xmlhttp.send();
    }

    newSellGenerateBill.setAttribute("disabled", "true");
}

// Handle item batch details fetch
const itemsBatchDetails = (productId, name, stock) => {
    const resetFields = () => {
        ["batch-no", "weightage", "exp-date", "mrp", "gst", "item-weightage", "item-unit-type", "aqty", "type-check", "qty", "disc", "dPrice", "taxable", "amount", "loose-stock", "loose-price"].forEach(id => {
            document.getElementById(id).value = '';
        });
    };

    if (stock > 0) {
        document.getElementById("product-name").value = name;
        searchedItems.style.display = "none";
        searchedBatchNo.style.display = "block";
        extaDetails.style.display = "none";
        resetFields();

        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                searchedBatchNo.innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open('GET', `ajax/sales-item-batch-list.ajax.php?prodId=${productId}`, true);
        xmlhttp.send();
    } else {
        resetFields();
        Swal.fire({
            title: "Want to add this item?",
            text: "This Item is not available in your stock, do you want to add?",
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Ok"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "stock-in.php";
            }
        });
    }
}

// Extra details div control function
const chekForm = (t) => {
    if (t.value.length <= 1) {
        newSellGenerateBill.setAttribute("disabled", "true");
    }

    if (!document.getElementById('product-name').value) {
        extaDetails.style.display = "none";
        searchedItems.style.display = "none";

        if (tableBody.getElementsByTagName('tr').length > 0) {
            newSellGenerateBill.removeAttribute("disabled");
        } else {
            newSellGenerateBill.setAttribute("disabled", "true");
        }
    }
}

// Stock details function
const stockDetails = (productId, batchNo, itemId) => {
    const updateFields = (fieldValues) => {
        Object.keys(fieldValues).forEach(field => {
            document.getElementById(field).value = fieldValues[field];
        });
    };

    const requestDetails = (url) => {
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET", url, false);
        xmlhttp.send(null);
        return xmlhttp.responseText;
    };

    document.getElementById('searched-batchNo').innerHTML = '';
    document.getElementById("product-id").value = productId;
    document.getElementById("batch_no").value = batchNo;
    document.getElementById("batch-no").value = batchNo;
    document.getElementById("searched-batchNo").style.display = "none";
    document.getElementById("crnt-stck-itm-id").value = itemId;

    const stockCheckUrl = `ajax/stock.checkExists.ajax.php?Pid=${productId}&batchNo=${batchNo}`;
    const exist = requestDetails(stockCheckUrl);

    if (exist == 1) {
        extaDetails.style.display = "block";
        updateFields({
            "product-name": requestDetails(`ajax/getProductDetails.ajax.php?id=${productId}`),
            "item-weightage": requestDetails(`ajax/getProductDetails.ajax.php?itemWeightage=${productId}`),
            "item-unit-type": requestDetails(`ajax/getProductDetails.ajax.php?itemUnit=${productId}`),
            "weightage": `${requestDetails(`ajax/getProductDetails.ajax.php?itemWeightage=${productId}`)} ${requestDetails(`ajax/getProductDetails.ajax.php?itemUnit=${productId}`)}`,
            "exp-date": requestDetails(`ajax/getProductDetails.ajax.php?exp=${productId}&batchNo=${batchNo}`),
            "mrp": requestDetails(`ajax/getProductDetails.ajax.php?stockmrp=${productId}&batchNo=${batchNo}`),
            "purchased-cost": requestDetails(`ajax/getProductDetails.ajax.php?stockptr=${productId}&batchNo=${batchNo}`),
            "gst": requestDetails(`ajax/getProductDetails.ajax.php?gst=${productId}&batchNo=${batchNo}`),
            "aqty": requestDetails(`ajax/getProductDetails.ajax.php?stockqty=${productId}&batchNo=${batchNo}`),
            "loose-stock": requestDetails(`ajax/getProductDetails.ajax.php?stockloose=${productId}&batchNo=${batchNo}`),
            "loose-price": requestDetails(`ajax/getProductDetails.ajax.php?stocklooseprc=${productId}&batchNo=${batchNo}`)
        });

        ["qty", "type-check", "disc", "dPrice", "taxable", "amount"].forEach(field => {
            document.getElementById(field).value = '';
        });

        document.getElementById("dPrice").value = '0.00';
        document.getElementById("taxable").value = '0.00';
        document.getElementById("amount").value = '0.00';
        document.getElementById('qty').focus();
    } else {
        extaDetails.style.display = "none";
    }
}


const addSummary = () => {
    // Retrieve input values
    const getValue = id => document.getElementById(id).value;

    let billDate = getValue("bill-date");
    let customer = getValue("customer");
    let doctorName = getValue("doctor-select");
    let paymentMode = getValue("payment-mode");
    let productId = getValue("product-id");
    let productName = getValue("product-name");
    let batchNo = getValue("batch-no");
    let crntStckItemId = getValue("crnt-stck-itm-id");
    let weightage = getValue("weightage");
    let itemWeightage = getValue('item-weightage');
    let unitType = getValue('item-unit-type');
    let expDate = getValue("exp-date");
    let mrp = getValue("mrp");
    let available = getValue('aqty');
    let itemComposition = getValue('productComposition');
    let qty = getValue("qty");
    let qtyTypeCheck = getValue("type-check");
    let Manuf = getValue("manuf");
    let manufName = getValue("manufName");
    let discPercent = getValue("disc");
    let discPrice = getValue("dPrice");
    let gst = getValue("gst");
    let taxable = parseFloat(getValue("taxable"));
    let amount = getValue("amount");
    let looseStock = getValue("loose-stock");
    let loosePrice = getValue("loose-price");
    let purchasedCost = getValue("purchased-cost");
    let marginAmount = getValue("margin");
    let salesMarginAmount = getValue("s-margin");

    // Validate inputs
    const validateField = (value, message) => {
        if (value === '') {
            Swal.fire("Failed!", message, "error");
            return false;
        }
        return true;
    };

    if (!validateField(billDate, "Select Bill Date!") ||
        !validateField(customer, "Select/Enter Customer Details!") ||
        !validateField(doctorName, "Select Doctor!") ||
        !validateField(paymentMode, "Select Payment Mode!") ||
        !validateField(productId, "Product ID Not Found!") ||
        !validateField(productName, "Product Name Not Found!") ||
        !validateField(batchNo, "Item batch number not found!") ||
        !validateField(weightage, "Product Weightage/Unit Not Found!") ||
        !validateField(expDate, "Item expiry date not found!") ||
        !validateField(mrp, "Item MRP not found!") ||
        !validateField(qty, "Enter Sell Quantity:") ||
        !validateField(discPercent, "Enter Discount Minimum value 0") ||
        !validateField(discPrice, "Discounted Price Not Found!") ||
        !validateField(gst, "Item GST Not Found!") ||
        !validateField(amount, "Net Amount Not Found!")) {
        return;
    }

    // Calculations
    let netGstAmount = (parseFloat(amount) - taxable).toFixed(2);
    let calculatedMRP = loosePrice !== '' ? loosePrice : mrp;

    // Increment serial numbers
    let slno = parseInt(getValue("dynamic-id")) + 1;
    let slControl = parseInt(getValue("serial-control")) + 1;
    document.getElementById("dynamic-id").value = slno;
    document.getElementById("serial-control").value = slControl;

    // Update totals
    document.getElementById("items").value = slno;
    document.getElementById("final-qty").value = parseInt(getValue("final-qty")) + parseInt(qty);
    document.getElementById("total-gst").value = (parseFloat(getValue("total-gst")) + parseFloat(netGstAmount)).toFixed(2);
    document.getElementById("total-price").value = (parseFloat(getValue("total-price")) + (parseFloat(calculatedMRP) * parseInt(qty))).toFixed(2);
    document.getElementById("payable").value = (parseFloat(getValue("payable")) + parseFloat(amount)).toFixed(2);

    // Append new row to the table
    let tableRow = `
        <tr id="table-row-${slControl}">
            <td><i class="fas fa-trash text-danger" onclick="deleteItem(${slControl}, ${qty}, ${netGstAmount}, ${(parseFloat(calculatedMRP) * parseInt(qty)).toFixed(2)}, ${amount})" style="font-size:.7rem; width: .3rem"></i></td>
            <td style="font-size:.7rem; padding-top:1rem; width: .3rem" scope="row">${slno}</td>
            <!-- Other cells here -->
        </tr>`;
    jQuery("#item-body").append(tableRow);

    // Set click event for each cell
    const cellIds = [
        `tr-${slControl}-col-2`, `tr-${slControl}-col-3`, `tr-${slControl}-col-7`,
        `tr-${slControl}-col-8`, `tr-${slControl}-col-11`, `tr-${slControl}-col-12`,
        `tr-${slControl}-col-16`, `tr-${slControl}-col-17`, `tr-${slControl}-col-19`,
        `tr-${slControl}-col-20`, `tr-${slControl}-col-23`
    ];
    cellIds.forEach(id => document.getElementById(id).onclick = () => editItem(JSON.stringify({
        slno: slControl,
        productId, crntStckItemId, batchNo, productName, Manuf, manufName, weightage, 
        itemWeightage, unitType, expDate, mrp, purchasedCost, qtyTypeCheck, qty, 
        discPercent, discPrice, taxable: taxable.toFixed(2), gst, gstAmountPerItem: netGstAmount, 
        marginAmount, salesMarginAmount, amount, looseStock, loosePrice, available, 
        itemComposition
    })));

    // Reset form fields
    document.getElementById('final-doctor-name').value = doctorName;
    document.getElementById("aqty").value = "";
    document.getElementById("exta-details").style.display = "none";
    document.getElementById("add-item-details").reset();
    event.preventDefault();

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
        // console.log(tBody.getElementsByTagName('tr').length);
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
    
    let checkEditOption = document.getElementById("product-id").value;

    if (checkEditOption == '') {

        Tupledata = JSON.parse(tuple);
        // console.log(Tupledata);

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
        document.getElementById("purchased-cost").value = Tupledata.purchasedCost;

        document.getElementById("qty").value = Tupledata.qty;
        document.getElementById("type-check").value = Tupledata.qtyTypeCheck;
        document.getElementById("manuf").value = Tupledata.ManufId;
        document.getElementById("manufName").value = Tupledata.manufName;

        document.getElementById("disc").value = Tupledata.discPercent;
        document.getElementById("dPrice").value = Tupledata.discPrice;
        document.getElementById("gst").value = Tupledata.gst;

        document.getElementById("taxable").value = Tupledata.taxable;
        document.getElementById("margin").value = Tupledata.marginAmount;
        document.getElementById("s-margin").value = Tupledata.salesMarginAmount;
        document.getElementById("amount").value = Tupledata.amount;

        document.getElementById("loose-stock").value = Tupledata.looseStock;
        document.getElementById("loose-price").value = Tupledata.loosePrice;
        document.getElementById("aqty").value = Tupledata.available;
        document.getElementById("productComposition").value = Tupledata.itemComposition;

        let netMRP = '';
        
        if (allowedUnits.map(unit => unit.toLowerCase()).includes(Tupledata.unitType.toLowerCase())){
        // if(Tupledata.unitType == 'tab' || Tupledata.unitType == 'cap'){
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
        Swal.fire("Can't Edit", "Please add/edit previous item first.", "error");
        document.getElementById("qty").focus();
    }
}


// reset button function ---------------
const reset = () =>{
    document.getElementById("aqty").value = "";
    // document.getElementById("exta-details").style.display = "none";
    document.getElementById("add-item-details").reset();
    event.preventDefault();
}