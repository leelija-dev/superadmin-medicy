//////////////////// set distributor name /////////////////////
const distributorInput = document.getElementById("distributor-id");
const dropdown = document.getElementsByClassName("c-dropdown")[0];

distributorInput.addEventListener("focus", () => {
  dropdown.style.display = "block";
});

document.addEventListener("click", (event) => {
  if (
    !distributorInput.contains(event.target) &&
    !dropdown.contains(event.target)
  ) {
    dropdown.style.display = "none";
  }
});

document.addEventListener("blur", (event) => {
  if (!dropdown.contains(event.relatedTarget)) {
    setTimeout(() => {
      dropdown.style.display = "none";
    }, 100);
  }
});

distributorInput.addEventListener("keyup", () => {
  let list = document.getElementsByClassName("lists")[0];

  if (distributorInput.value.length > 2) {
    let distributorURL =
      "ajax/distributor.list-view.ajax.php?match=" + distributorInput.value;
    request.open("GET", distributorURL, false);
    request.send(null);
    list.innerHTML = request.responseText;
  } else if (distributorInput.value == "") {
    let distributorURL = "ajax/distributor.list-view.ajax.php?match=all";
    request.open("GET", distributorURL, false);
    request.send(null);
    list.innerHTML = request.responseText;
  } else {
    list.innerHTML = "";
  }
});

const setDistributor = (t) => {
  let distributirId = t.id.trim();
  let distributirName = t.innerHTML.trim();

  document.getElementById("updated-dist-id").value = distributirId;
  document.getElementById("distributor-name").value = distributirName;
  document.getElementById("distributor-id").value = distributirName;

  document.getElementsByClassName("c-dropdown")[0].style.display = "none";
};



const addDistributor = () => {
  const parentLocation = window.location.origin + window.location.pathname;
  const distBillNo = document.getElementById('data-holder-1').value;
  const editId = document.getElementById('data-holder-2').value;

  $.ajax({
    url: "components/distributor-add.php",
    type: "POST",
    data: {
      urlData: parentLocation,
      distBill: distBillNo,
      editid: editId,
      flag: 'dist-add',
    },
    success: function (response) {
      const body = document.querySelector(".add-distributor");
      body.innerHTML = response;
    },
    error: function (error) {
      console.error("Error: ", error);
    },
  });
};


///////////////// STOCK IN EDIT UPDATE BUTTON CONTROL \\\\\\\\\\
const stockInSave = document.getElementById("stockInEdit-update-btn");

const chekForm = () => {
  var tableBody = document.getElementById("dataBody");

  if (
    document.getElementById("product-name").value == "" &&
    tableBody.getElementsByTagName("tr").length > 0
  ) {
    stockInSave.removeAttribute("disabled");
  } else {
    stockInSave.setAttribute("disabled", "true");
  }
};

//////// QANTITY AND FREE QANTITY VALUE CONTROL //////////
const Qty = document.getElementById("qty");
Qty.addEventListener("input", function (event) {
  this.value = this.value.replace(".", "");
});

const FreeQty = document.getElementById("free-qty");
FreeQty.addEventListener("input", function (event) {
  this.value = this.value.replace(".", "");
});

//////// batch number input contorl \\\\\\\\\\
const batchNumber = document.getElementById("batch-no");
batchNumber.addEventListener("input", function (event) {
  this.value = this.value.replace(".", "");
  this.value = this.value.replace("*", "");
});

const addToForm = (rowNo, prodId, billNo, batchNo) => {
  // console.log(rowNo + ' ' + prodId + ' ' + billNo + ' ' + batchNo);

  $.ajax({
    url: "ajax/stokInEditAll.ajax.php",
    type: "POST",
    data: {
      pId: prodId,
      blNo: billNo,
      bhNo: batchNo,
    },
    success: function (respones) {
      // console.log(respones);
      // return;
      var dataObject = JSON.parse(respones);
      // return;

      var totalItmQty = parseInt(dataObject.qty) + parseInt(dataObject.FreeQty);
      var gstPerItem = parseFloat(dataObject.GstAmount);
      var totalAmnt = parseFloat(dataObject.amnt);

      var slno = rowNo;
      slno = slno.replace(/\D/g, "");
      var itemQty = totalItmQty;
      gstPerItem = gstPerItem.toFixed(2);
      var total = totalAmnt.toFixed(2);

      var purchaseDetailsExpDate = dataObject.expDate;
      var expMonth = purchaseDetailsExpDate.slice(0, 2);
      var expYear = purchaseDetailsExpDate.slice(3, 7);
      var manuf = dataObject.manufacturer;

      manuf = manuf.replace(/&#39/g, "'");
      manuf = manuf.replace(/&lt/g, "<");
      manuf = manuf.replace(/&gt/g, ">");

      var totalQty = parseInt(dataObject.qty) + parseInt(dataObject.FreeQty);

      ///////////////////////////////// check ptr set ///////////////////////////////////
      let mrp = dataObject.mrp;
      let gst = dataObject.gst;
      let chkptr = (parseFloat(mrp) * 100) / (parseFloat(gst) + 100);
      chkptr = chkptr.toFixed(2);
      // //+++++++------  Adding data to is subsequent form body  ---------++++++++++++++++

      document.getElementById("purchase-id").value = dataObject.purchaseId;
      document.getElementById("product-id").value = dataObject.productId;
      document.getElementById("batch-no").value = dataObject.batchNo;

      document.getElementById("product-name").value = dataObject.productName;
      document.getElementById("manufacturer-id").value = dataObject.manufId;
      document.getElementById("manufacturer-name").value = manuf;

      document.getElementById("weightage").value = dataObject.weightage;
      document.getElementById("unit").value = dataObject.unit;

      document.getElementById("packaging-in").value = dataObject.packageType;

      document.getElementById("medicine-power").value = dataObject.power;

      document.getElementById("exp-month").value = expMonth;
      document.getElementById("exp-year").value = expYear;

      document.getElementById("mrp").value = dataObject.mrp;
      document.getElementById("ptr").value = dataObject.ptr;
      document.getElementById("chk-ptr").value = chkptr;
      document.getElementById("qty").value = dataObject.qty;
      document.getElementById("free-qty").value = dataObject.FreeQty;
      document.getElementById("updtQTYS").value = totalQty;

      document.getElementById("purchsed-qty").value = dataObject.purchasedQty;
      document.getElementById("current-qty").value = dataObject.currentStockQty;

      document.getElementById("packaging-type").value = dataObject.packageType;
      document.getElementById("packaging-type-edit").value =
        dataObject.packageType;

      document.getElementById("discount").value = dataObject.disc;
      document.getElementById("gst-check").value = dataObject.gst;
      document.getElementById("gst").value = dataObject.gst;
      document.getElementById("crntGstAmnt").value = dataObject.GstAmount;
      document.getElementById("d_price").value = dataObject.dPrice;
      document.getElementById("bill-amount").value = dataObject.amnt;
      document.getElementById("temp-bill-amount").value = dataObject.amnt;

      document.getElementById("del-flag").value = dataObject.delflag;

      //++++++++++++++++++---  removing selected row  -----+++++++++++++++++++

      deleteData(slno, itemQty, gstPerItem, total, 0);

      document.getElementById("add-button").removeAttribute("disabled");
    },
  });
};

// const customClick = (event, rowNo, prodId, billNo, batchNo) => {
//   stockInSave.setAttribute("disabled", "true");

//   var checkFieldBlank = document.getElementById("product-id");

//   if (checkFieldBlank.value.trim() === "") {
//     addToForm(rowNo, prodId, billNo, batchNo);
//   } else {
//     document.getElementById("data-details").reset();
//     let rowNo = event.target.parentNode.parentNode.id;
//     let productId = document.getElementById("product-id");
//     let distBillid = document.getElementById("distributor-bill");
//     let batchNo = document.getElementById("batch-no");

//     addToForm(rowNo, productId, distBillid, batchNo);
//   }
// }

const customClick = (event, rowNo, prodId, distBillid, batchNo) => {
  stockInSave.setAttribute("disabled", "true");

  var checkFieldBlank = document.getElementById("product-id");

  if (checkFieldBlank.value.trim() === "") {
    addToForm(rowNo, prodId, distBillid, batchNo);
  } else {
    // document.getElementById("data-details").reset();

    let mainElement = event.target.parentNode.parentNode;
    let newRowNo = mainElement.id;
    let newProduct = mainElement.getAttribute("productid");
    let newBatchNo = mainElement.getAttribute("me-batch-no");
    addToForm(newRowNo, newProduct, distBillid, newBatchNo); // Passed new variables to addToForm function
    addData();
  }
};

//========================================================================================================
const firstInput = document.getElementById("product-name");

window.addEventListener("load", function () {
  firstInput.focus();
});

firstInput.addEventListener("input", function (event) {
  const inputValue = this.value;

  if (inputValue.length > 0 && inputValue[0] === " ") {
    this.value = inputValue.slice(1);
  }
});

///////////// set distributo bill no \\\\\\\\\\\\
const setDistBillNo = (t) => {
  let val = t.value.toUpperCase();
  // console.log(val);
  document.getElementById("distributor-bill-no").value = val;
};

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

//////////// set payment mode \\\\\\\\\\\\\\\\\
const setPaymentMode = (pMode) => {
  document.getElementById("payment-mode-val").value = pMode.value;
};

function searchItem(input) {
  let searchReult = document.getElementById("product-select");

  if (input == "") {
    document.getElementById("product-select").style.display = "none";

    document.getElementById("data-details").reset();
    event.preventDefault();
  }

  if (input.length > 2) {
    if (input != "") {
      document.getElementById("product-select").style.display = "block";
    }
  } else {
    document.getElementById("product-select").style.display = "none";
  }

  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      searchReult.innerHTML = xmlhttp.responseText;
    }
  };
  xmlhttp.open("GET", "ajax/purchase-item-list.ajax.php?data=" + input, true);
  xmlhttp.send();
}

const getDtls = (value) => {

  if (value != "") {
    //==================== Product Id ====================
    manufacturerurl = "ajax/product.getManufacturer.ajax.php?id=" + value;
    // alert(url);
    xmlhttp.open("GET", manufacturerurl, false);
    xmlhttp.send(null);
    document.getElementById("manufacturer-id").value = xmlhttp.responseText;

    //==================== Manufacturere List ====================
    manufacturerurl = "ajax/product.getManufacturer.ajax.php?id=" + value;
    // alert(url);
    xmlhttp.open("GET", manufacturerurl, false);
    xmlhttp.send(null);
    document.getElementById("manufacturer-id").value = xmlhttp.responseText;

    manufacturerName = "ajax/product.getManufacturer.ajax.php?name=" + value;
    // alert(url);
    xmlhttp.open("GET", manufacturerName, false);
    xmlhttp.send(null);
    document.getElementById("manufacturer-name").value = xmlhttp.responseText;

    //==================== Medicine Power ====================
    powerurl = "ajax/product.getMedicineDetails.ajax.php?power=" + value;
    // alert(url);
    xmlhttp.open("GET", powerurl, false);
    xmlhttp.send(null);
    document.getElementById("medicine-power").value = xmlhttp.responseText;
    // alert(xmlhttp.responseText);

    //==================== Packaging Type ====================
    packTypeUrl = "ajax/product.getMedicineDetails.ajax.php?pType=" + value;
    // alert(url);
    xmlhttp.open("GET", packTypeUrl, false);
    xmlhttp.send(null);
    document.getElementById("packaging-type").innerHTML = xmlhttp.responseText;

    packTypeFieldUrl =
      "ajax/product.getMedicineDetails.ajax.php?packegeIn=" + value;
    // // alert(url);
    xmlhttp.open("GET", packTypeFieldUrl, false);
    xmlhttp.send(null);
    document.getElementById("packaging-in").value = xmlhttp.responseText;

    // alert(xmlhttp.responseText);

    //==================== Weightage ====================
    weightage = "ajax/product.getMedicineDetails.ajax.php?weightage=" + value;
    // alert(url);
    xmlhttp.open("GET", weightage, false);
    xmlhttp.send(null);
    document.getElementById("weightage").value = xmlhttp.responseText;
    // alert(xmlhttp.responseText);

    //==================== Unit ====================
    unitUrl = "ajax/product.getMedicineDetails.ajax.php?unit=" + value;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    xmlhttp.open("GET", unitUrl, false);
    xmlhttp.send(null);
    document.getElementById("unit").value = xmlhttp.responseText;
    // alert(xmlhttp.responseText);

    //==================== MRP ====================
    mrpUrl = "ajax/product.getMrp.ajax.php?id=" + value;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    xmlhttp.open("GET", mrpUrl, false);
    xmlhttp.send(null);
    document.getElementById("mrp").value = xmlhttp.responseText;
    // alert(xmlhttp.responseText);

    //==================== ptr check url ===================

    chkPtr = "ajax/product.getMrp.ajax.php?ptrChk=" + value;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    xmlhttp.open("GET", chkPtr, false);
    xmlhttp.send(null);
    // alert(xmlhttp.responseText);
    document.getElementById("chk-ptr").value = xmlhttp.responseText;
    document.getElementById("ptr").value = xmlhttp.responseText;

    //==================== GST ====================
    gstUrl = "ajax/product.getGst.ajax.php?id=" + value;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    xmlhttp.open("GET", gstUrl, false);
    xmlhttp.send(null);
    document.getElementById("gst").value = xmlhttp.responseText;
    // alert(xmlhttp.responseText);

    //==================== Product Id ====================
    document.getElementById("product-id").value = value;

    //==================== Product Name ====================
    nameUrl = "ajax/product.getMedicineDetails.ajax.php?pName=" + value;
    // alert(unitUrl);
    xmlhttp.open("GET", nameUrl, false);
    xmlhttp.send(null);
    document.getElementById("product-name").value = xmlhttp.responseText;
    // alert(xmlhttp.responseText);
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
};

firstInput.addEventListener("keydown", function (event) {
  if (firstInput.value !== "") {
    if (document.getElementById("del-flag").value == 1) {
      event.preventDefault();
      Swal.fire(
        "Error",
        "You can only edit its transactional value.",
        "info"
      );
    }
  }
});

const getBillAmount = () => {
  let mrp = document.getElementById("mrp").value;
  let ptr = document.getElementById("ptr").value;
  let gst = document.getElementById("gst").value;
  // console.log("change gst : "+gst);

  let prevGst = document.getElementById("gst-check").value;
  // console.log("prev gst : "+prevGst);

  let qty = document.getElementById("qty").value;
  if (qty == "") {
    qty = 0;
  }

  let disc = document.getElementById("discount").value;
  if (disc == "") {
    disc = 0;
  }

  let maxPtr = (parseFloat(mrp) * 100) / (parseInt(gst) + 100);
  maxPtr = maxPtr.toFixed(2);

  if (gst != prevGst) {
    console.log(Number(ptr));
    console.log(Number(maxPtr));
    if(Number(ptr) > Number(maxPtr)){
      document.getElementById("ptr").value = maxPtr;
      document.getElementById("gst-check").value = gst;
    }
  }

  if (parseFloat(ptr) > parseFloat(maxPtr)) {
    // console.log("max ptr "+ maxPtr);
    // console.log("change ptr "+ ptr);

    Swal.fire({
      title: "Error Input",
      text: "PTR must be lesser than Calculated Value. Please enter proper PTR value!",
      icon: "error",
      button: false, // Hide the "OK" button
      timer: 1000, // Auto-close the alert after 2 seconds
    });

    document.getElementById("ptr").value = maxPtr;

    maxPtr = maxPtr;

    document.getElementById("bill-amount").value = " ";

    document.getElementById("ptr").focus();
  }

  let modifiedPtr = document.getElementById("ptr").value;

  let d_price = (parseFloat(modifiedPtr) - parseFloat(modifiedPtr) * (parseFloat(disc) / 100)).toFixed(2);
  // base = parseFloat(base) + (parseFloat(base) * (parseFloat(gst) / 100));

  let totalAmount = ((parseFloat(d_price) + parseFloat(d_price) * (parseFloat(gst) / 100)) *
  parseInt(qty)).toFixed(2);

  document.getElementById("d_price").value = d_price;
  document.getElementById("bill-amount").value = totalAmount;

  //=============================================
  //======= UPDATE GST ON PRODUCT SECTION =======
  let prodId = document.getElementById("product-id").value;

  $.ajax({
    url: "ajax/update-product-gst.ajax.php",
    type: "POST",
    data: {
      gstPercetn: gst,
      prodId: prodId,
    },
    success: function (response) {
      // console.log(response);
    },
    error: function (error) {
      // console.error('Error removing image:', error);
    },
  });
}; //eof getBillAmount function

// ============= QTY CALCULETION ON FREE QTY UPDATE ==================
const editQTY = () => {
  var crntQTY = document.getElementById("qty").value;
  var crntFreeQTY = document.getElementById("free-qty").value;
  document.getElementById("updtQTYS").value =
    Number(crntQTY) + Number(crntFreeQTY);
};
// ##################################################################################

// ====== qty check control ======
const qtyCheck = (t) => {
  editQTY();
  if (t.value == 0) {
    // document.getElementById('add-button').setAttribute("disabled", "true");
    // Swal.fire('Alert','Enter valid qantity','info')
  } else {
    // document.getElementById('add-button').removeAttribute("disabled");
  }
};
// ##################################################################################

const validateField = (fieldId, message) => {
  let field = document.getElementById(fieldId);
  if (field.value === "") {
    Swal.fire("Blank Field", message, "error").then((value) => {
      field.focus();
    });
    return false;
  }
  return true;
};

const addData = () => {
  var productName = document.getElementById("product-name");
  // firstInput.removeAttribute('readonly');
  var productId = document.getElementById("product-id");
  var batch = document.getElementById("batch-no");
  var batchNo = batch.value.toUpperCase();
  var manufId = document.getElementById("manufacturer-id");
  var manufName = document.getElementById("manufacturer-name");
  var medicinePower = document.getElementById("medicine-power");
  var expMonth = document.getElementById("exp-month");
  var expYear = document.getElementById("exp-year");
  var expDate = `${expMonth.value}/${expYear.value}`;
  expDate = expDate.toString();
  var weightage = document.getElementById("weightage");
  var unit = document.getElementById("unit");
  var packagingIn = document.getElementById("packaging-in");
  var mrp = document.getElementById("mrp");
  var ptr = document.getElementById("ptr");
  var qty = document.getElementById("qty");
  var freeQty = document.getElementById("free-qty");

  var discount = document.getElementById("discount");
  var gst = document.getElementById("gst");
  var d_price = document.getElementById("d_price");
  var billAmount = document.getElementById("bill-amount");
  var prevAmount = document.getElementById("temp-bill-amount");
  var purchaseId = document.getElementById("purchase-id");
  var crntGstAmount = document.getElementById("crntGstAmnt");
  var itemQty = document.getElementById("updtQTYS").value;

  var rows = document.querySelectorAll("#dataBody tr");

  rows.forEach(function (row) {
    var existingProductId = row.getAttribute("productId");
    if (existingProductId) {
      if (productId.value === existingProductId) {
        let deleteItem = row.firstElementChild;
        deleteItem.click();
        // console.log(deleteItem)
      }
    }
  });

  const fieldsToValidate = [
    { field: "distributor-id", message: "Please Select Distributor First!" },
    {
      field: "distributor-bill",
      message: "Please Enter Distributor Bill Number!",
    },
    {
      field: "prev-distributor-bill-no",
      message: "Previous Distributor Bill is empty!",
    },
    { field: "bill-date", message: "Please Select Bill Date!" },
    { field: "due-date", message: "Please Select Bill Due Date!" },
    { field: "payment-mode", message: "Please Select Payment Mode!" },
    { field: "product-name", message: "Please Search & Select an item!" },
    {
      field: "product-id",
      message: "Invalid Product ID, Please Search & Select an item!",
    },
    { field: "batch-no", message: "Please Enter Product Batch Number!" },
    { field: "exp-month", message: "Please Enter Complete Expiry Date!" },
    { field: "exp-year", message: "Please Enter Complete Expiry Date!" },
    { field: "mrp", message: "Enter  MRP of the item!" },
    { field: "ptr", message: "Enter Valid PTR!" },
    { field: "qty", message: "Please Enter Valid Quantity!" },
    { field: "free-qty", message: "Please enter free quantity at least 0!" },
    { field: "discount", message: "Please enter discount at least 0" },
    { field: "gst", message: "Select GST!" },
    { field: "bill-amount", message: "Invalid Bill Amount" },
    { field: "d_price", message: "Discount Price Invalid!" },
  ];

  // ==============================================
  const allFieldsValid = fieldsToValidate.every(({ field, message }) =>
    validateField(field, message)
  );
  if (!allFieldsValid) return;

  // Rest of the code for adding data

  var byuQty = document.getElementById("purchsed-qty").value;
  if (byuQty == "") {
    byuQty = 0;
  }
  var curQty = document.getElementById("current-qty").value;
  if (curQty == "") {
    curQty = 0;
  }
  var delflag = document.getElementById("del-flag").value;

  var Ptr = parseFloat(ptr.value);
  var Mrp = parseFloat(mrp.value);

  if (Ptr > Mrp) {
    Swal.fire("Blank Field", "Please check PTR value", "error").then(
      (value) => {
        ptr.focus();
      }
    );
    return;
  }

  //// sl control for row
  let slno = document.getElementById("dynamic-id").value;
  let slControl = document.getElementById("serial-control").value;
  slno++;
  slControl++;
  document.getElementById("dynamic-id").value = slno;
  document.getElementById("serial-control").value = slControl;


  // ====================== GST AMOUNT CALCULATION ======================
  let dpriceAmt = d_price.value;
  let gstPerItem = (((parseInt(gst.value) / 100) * parseFloat(dpriceAmt)) * qty.value).toFixed(2);
  // let gstPerItem = ((parseFloat(dpriceAmt) * (parseFloat(gst.value) / 100)) * parseInt(qty.value)).toFixed(2);

  let gstVal = document.getElementById("gst-val").value;
  gstVal = parseFloat(gstVal) + parseFloat(gstPerItem);
  onlyGst = gstVal.toFixed(2);
  
  
  // del falg checking ===
  if (delflag == "") {
    delflag = 0;
  } else {
    delflag = delflag;
  }
  // console.log('del flag val check : '+delflag);

  jQuery("#dataBody")
    .append(`<tr id="table-row-${slControl}" style="cursor: pointer;">
            <td style="color: red; width: 1rem;"><i class="fas fa-trash" style="padding-top: .5rem;" onclick="deleteData(${slControl}, ${itemQty}, ${gstPerItem}, ${billAmount.value}, ${byuQty}, ${curQty}, ${delflag})"></i></td>
           
            <td class="p-0 pt-3" id="row-${slControl}-col-1" style="font-size:.75rem ; padding-top:1rem; width: .75rem">${slno}</td>

            <td class="d-none p-0 pt-3">
                <input class="table-data w-6r" type="text" name="purchaseId[]" value="${purchaseId.value}" readonly>
            </td>

            <td class="p-0 pt-3" id="row-${slControl}-col-3">
                <input class="col table-data w-.65r" type="text" name="productNm[]" value="${productName.value}" readonly style="text-align: start; font-size:0.65rem;">

                <input class="col table-data w-4r" type="text" name="setof[]" value="${weightage.value} ${unit.value}" readonly style="font-size:0.65rem;">
                <input class="d-none col table-data w-4r" type="text" name="weightage[]" value="${weightage.value}">
                <input class="d-none col table-data w-4r" type="text" name="unit[]" value="${unit.value}">

                <input class="d-none" type="text" name="productId[]" value="${productId.value}">
                <input class="d-none" type="text" name="discount[]" value="${discount.value}">
                
            </td>

            <td class="p-0 pt-3" id="row-${slControl}-col-4">
                <input class="col table-data w-6r" type="text" name="batchNo[]" value="${batchNo}" readonly style="font-size:0.65rem;">
            </td>

            <td class="p-0 pt-3" id="row-${slControl}-col-6">
                <input class="col table-data w-4r" type="text" name="expDate[]" value="${expDate}" readonly style="font-size:0.65rem;">
            </td>

            <td class="p-0 pt-3" id="row-${slControl}-col-9">
                <input class="col table-data w-3r" type="text" name="qty[]" value="${qty.value}" readonly style="font-size:0.65rem; text-align:end;">
            </td>

            <td class="p-0 pt-3" id="row-${slControl}-col-10">
                <input class="col table-data w-3r" type="text" name="freeQty[]" value="${freeQty.value}" readonly style="font-size:0.65rem; text-align:end;">
            </td>

            <td class="p-0 pt-3" id="row-${slControl}-col-11">
                <input class="col table-data w-4r" type="text" name="mrp[]" value="${mrp.value}" readonly style="font-size:0.65rem; text-align:end;">
            </td>

            <td class="p-0 pt-3" id="row-${slControl}-col-12">
                <input class="col table-data w-4r" type="text" name="ptr[]" value="${ptr.value}" readonly style="font-size:0.65rem; text-align:end;">
            </td>

            <td class="p-0 pt-3" id="row-${slControl}-col-13">
                <input class="col table-data text-right w-4r" type="text" name="d_price[]" value="${d_price.value}" style="font-size:0.65rem; text-align: end;">
                <span class="badge badge-pill badge-primary">${discount.value}%</span>
            </td>

            <td class="p-0 pt-3" id="row-${slControl}-col-15">
                <input class="col table-data w-3r" type="text" name="gst[]" value="${gst.value}%" readonly style="font-size:0.65rem; text-align:end;">
                <input class="d-none col table-data w-3r" type="text" name="gstPerItem[]" value="${gstPerItem}">
            </td>

            <td class="p-0 pt-3" id="row-${slControl}-col-16">
                <input class="col table-data w-5r amnt-inp" type="text" name="billAmount[]" value="${billAmount.value}" readonly style="font-size:0.65rem; text-align:end;">
            </td>

        </tr>`);

  stockInSave.removeAttribute("disabled");

  //item-table

  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  if (slno > 1) {
    let id = document.getElementById("items-val");
    let newId = parseFloat(id.value) + 1;
    document.getElementById("items-val").value = newId;
  } else {
    document.getElementById("items-val").value = slno;
  }

  document.getElementById("gst-val").value = onlyGst;

  // item qantity
  // var qtyVal = document.getElementById("qty-val").value;
  // totalQty = parseInt(qty.value) + parseInt(freeQty.value) + parseInt(qtyVal);
  // document.getElementById("qty-val").value = totalQty;

  // net amount calculation
  calculateSummary(parseFloat(billAmount.value));

  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  const dataTuple = {
    slno: slControl,
    productName: productName.value,
    productId: productId.value,
    batchNo: batchNo,
    ManufId: manufId.value,
    manufName: manufName.value,

    expMnth: expMonth.value,
    expYr: expYear.value,
    weightage: weightage.value,
    unitType: unit.value,
    packaging: packagingIn.value,
    medPower: medicinePower.value,

    mrp: mrp.value,
    ptr: ptr.value,
    qty: qty.value,
    freeQty: freeQty.value,

    discPercent: discount.value,
    gst: gst.value,
    d_price: d_price.value,
    billAMNT: billAmount.value,
    prevAmount: prevAmount.value,
    purchaseId: purchaseId.value,
    crntGstAmount: crntGstAmount.value,
    itemQty: itemQty,

    byuQty: byuQty,
    curQty: curQty,
    delflag: delflag,
  };

  let tupleData = JSON.stringify(dataTuple);

  document.getElementById(`row-${slControl}-col-3`).onclick = function () {
    editItem(tupleData);
  };
  document.getElementById(`row-${slControl}-col-4`).onclick = function () {
    editItem(tupleData);
  };

  document.getElementById(`row-${slControl}-col-6`).onclick = function () {
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
  document.getElementById(`row-${slControl}-col-15`).onclick = function () {
    editItem(tupleData);
  };
  document.getElementById(`row-${slControl}-col-16`).onclick = function () {
    editItem(tupleData);
  };

  ///////////////////////////////////////////////////////////////////////////////////

  document.getElementById("data-details").reset();
  event.preventDefault();
};

// const calculateSummary = (addAmount) => {
//   var billAmount = 0;
//   const billAmounts = document.querySelectorAll('input[name="billAmount[]"]');
//   billAmounts.forEach(cell => {
//     var eachAmount = cell.value;
//   });
// }

const calculateSummary = (addAmount) => {
  var billAmount = 0;
  var totalQty = 0;
  var totalFQty = 0;

  const billAmounts = document.querySelectorAll('input[name="billAmount[]"]');
  billAmounts.forEach((cell) => {
    var eachAmount = parseFloat(cell.value);
    if (!isNaN(eachAmount)) {
      billAmount += eachAmount;
    }
  });

  let netBillAmount = billAmount.toFixed(2);
  document.getElementById("net-amount").value = netBillAmount;

  const eachQtys = document.querySelectorAll('input[name="qty[]"]');
  eachQtys.forEach((cell) => {
    var eachQty = parseFloat(cell.value);
    if (!isNaN(eachQty)) {
      totalQty += eachQty;
    }
  });

  const freeQtys = document.querySelectorAll('input[name="freeQty[]"]');
  freeQtys.forEach((cell) => {
    var eachFQty = parseFloat(cell.value);
    if (!isNaN(eachFQty)) {
      totalFQty += eachFQty;
    }
  });

  document.getElementById("qty-val").value = totalQty + totalFQty;
};

//=============================== ADDED ITEM EDIT FUNCTION ==============================

const editItem = (tData) => {
  // console.log(tData);
  let checkFild = document.getElementById("product-id").value;
  // console.log(checkFild);

  if (checkFild == "") {
    let tuple = JSON.parse(tData);

    document.getElementById("product-name").value = tuple.productName;
    // firstInput.setAttribute('readonly', true);
    document.getElementById("product-id").value = tuple.productId;
    document.getElementById("batch-no").value = tuple.batchNo;
    document.getElementById("manufacturer-id").value = tuple.ManufId;
    document.getElementById("manufacturer-name").value = tuple.manufName;
    // document.getElementById("mfd-month").value = tuple.mfdMnth;
    // document.getElementById("mfd-year").value = tuple.mfdYr;
    document.getElementById("exp-month").value = tuple.expMnth;
    document.getElementById("exp-year").value = tuple.expYr;

    document.getElementById("medicine-power").value = tuple.medPower;
    document.getElementById("weightage").value = tuple.weightage;
    document.getElementById("unit").value = tuple.unitType;
    document.getElementById("packaging-in").value = tuple.packaging;
    document.getElementById("packaging-type-edit").value = tuple.packaging;

    document.getElementById("mrp").value = tuple.mrp;
    document.getElementById("ptr").value = tuple.ptr;
    document.getElementById("qty").value = tuple.qty;
    document.getElementById("free-qty").value = tuple.freeQty;

    document.getElementById("purchsed-qty").value = tuple.byuQty;
    document.getElementById("current-qty").value = tuple.curQty;

    document.getElementById("discount").value = tuple.discPercent;
    document.getElementById("gst").value = tuple.gst;
    document.getElementById("d_price").value = tuple.d_price;
    document.getElementById("bill-amount").value = tuple.billAMNT;
    document.getElementById("temp-bill-amount").value = tuple.prevAmount;

    document.getElementById("purchase-id").value = tuple.purchaseId;
    document.getElementById("crntGstAmnt").value = tuple.crntGstAmount;
    document.getElementById("updtQTYS").value = tuple.itemQty;

    document.getElementById("del-flag").value = tuple.delflag;

    let gstPerItem =
      parseFloat(tuple.billAMNT) -
      parseFloat(tuple.d_price) * parseInt(tuple.qty);
    gstPerItem = gstPerItem.toFixed(2);

    deleteData(
      tuple.slno,
      tuple.itemQty,
      gstPerItem,
      tuple.billAMNT,
      tuple.delflag
    );
  } else {
    // Swal.fire("Can't Edit", "Please add/edit previous item first.", "error");
    addData();
    // document.getElementById("ptr").focus();
  }
};

// ================================ Delet Data ================================

function deleteData(slno, itemQty, gstPerItem, total, pQty, cQty, delflag) {
  if (delflag == 1) {
    // let purchaedQty = document.getElementById('purchsed-qty').value;
    // let currentQty = document.getElementById('current-qty').value;

    if (parseInt(pQty) == parseInt(cQty)) {
      let delRow = slno;

      jQuery(`#table-row-${slno}`).remove();
      let slVal = document.getElementById("dynamic-id").value;
      document.getElementById("dynamic-id").value = parseInt(slVal) - 1;

      //minus item
      let items = document.getElementById("items-val");
      let finalItem = parseInt(items.value) - 1;
      items.value = finalItem;

      // minus quantity
      let qty = document.getElementById("qty-val");
      let finalQty = qty.value - itemQty;
      qty.value = finalQty;

      // minus gst
      let gst = document.getElementById("gst-val");
      let finalGst = gst.value - gstPerItem;
      gst.value = finalGst.toFixed(2);

      // minus netAmount
      let net = document.getElementById("net-amount");
      let finalAmount = net.value - total;
      net = finalAmount.toFixed(2);
      document.getElementById("net-amount").value = net;

      rowAdjustment(delRow);

      if (document.getElementById("items-val").value == 0) {
        stockInSave.setAttribute("disabled", "true");
      }
    } else {
      Swal.fire("error", "not possible", "error");
    }
  } else {
    let delRow = slno;

    jQuery(`#table-row-${slno}`).remove();
    let slVal = document.getElementById("dynamic-id").value;
    document.getElementById("dynamic-id").value = parseInt(slVal) - 1;

    //minus item
    let items = document.getElementById("items-val");
    let finalItem = parseInt(items.value) - 1;
    items.value = finalItem;

    // minus quantity
    let qty = document.getElementById("qty-val");
    let finalQty = qty.value - itemQty;
    qty.value = finalQty;

    // minus gst
    let gst = document.getElementById("gst-val");
    let finalGst = gst.value - gstPerItem;
    gst.value = finalGst.toFixed(2);

    // minus netAmount
    let net = document.getElementById("net-amount");
    let finalAmount = net.value - total;
    net = finalAmount.toFixed(2);
    document.getElementById("net-amount").value = net;

    rowAdjustment(delRow);

    if (document.getElementById("items-val").value == 0) {
      stockInSave.setAttribute("disabled", "true");
    }
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

// ======================= Manufacturing date setting ===================
let expMonthInput = document.getElementById("exp-month");
let expYearInput = document.getElementById("exp-year");
let today = new Date();
let currentMonth = today.getMonth();
let currentYear = today.getFullYear();

expMonthInput.addEventListener("keydown", function (event) {
  if (event.keyCode === 9) {
    if (expMonthInput.value.trim() === "") {
      event.preventDefault();
    }
  }
});

expMonthInput.addEventListener("input", function (event) {
  // Remove dots from the input value
  this.value = this.value.replace(".", "");
});

expYearInput.addEventListener("keydown", function (event) {
  if (event.keyCode === 9) {
    if (expMonthInput.value.trim() === "") {
      event.preventDefault();
    }
  }
});

expYearInput.addEventListener("input", function (event) {
  // Remove dots from the input value
  this.value = this.value.replace(".", "");
});

// set exp month control
const setexpMonth = (mnth) => {
  if (mnth.value.length != 2) {
    mnth.value = "";
    mnth.focus();
    Swal.fire("Alert", "Month must be two digit.", "info");
  }
};

const setExpMonth = (month) => {
  if (month.value <= 12) {
    if (month.value.length > 2) {
      month.value = "";
      month.focus();
    } else if (month.value.length < 2) {
      month.focus();
    } else if (month.value.length == 2) {
      if (month.value == 0) {
        month.value = "";
        month.focus();
      } else {
        document.getElementById("exp-year").focus();
      }
    } else {
      month.value = "";
      month.focus();
    }
  } else if (month.value == "") {
    month.focus();
    Swal.fire("Alert", "Month must be less or equal 12.", "info");
  } else {
    month.value = "";
    month.focus();
  }
};

const setExpYEAR = (year) => {
  expMnth = document.getElementById("exp-month").value;

  let today = new Date();
  let currentMnth = today.getMonth();
  let curretnYr = today.getFullYear();

  if (year.value.length == 4) {
    if (year.value < curretnYr) {
      document.getElementById("exp-year").value = "";
      document.getElementById("exp-year").focus();
    } else if (year.value == curretnYr) {
      if (expMnth < currentMnth) {
        document.getElementById("exp-month").value = "";
        document.getElementById("exp-year").value = "";
        document.getElementById("exp-month").focus();
      }
    } else {
      document.getElementById("ptr").focus();
    }
  } else {
    document.getElementById("exp-year").value = "";
    document.getElementById("exp-year").focus();
  }
};
