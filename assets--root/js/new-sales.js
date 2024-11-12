// document.getElementById("exta-details").style.display = "block";
//============= constant data declaretion =============
// const xmlhttp = new XMLHttpRequest();
const allowedUnits = ["tablets", "tablet", "capsules", "capsule"];

//======================= new sell generate bill button disable and enable control ===================
var tableBody = document.getElementById("item-body");
var newSellGenerateBill = document.getElementById("new-sell-bill-generate");
newSellGenerateBill.setAttribute("disabled", "true");

//======================================================================================
var setDate = new Date();
var today = setDate.toISOString().slice(0, 10);

// Set default value to today
document.getElementById("bill-date").value = today;
document.getElementById("final-bill-date").value = today;

// Set max attribute to today
document.getElementById("bill-date").max = today;
document.getElementById("final-bill-date").max = today;

// Function to set date, ensuring it does not exceed today
const getDate = (date) => {
  if (new Date(date) > setDate) {
    alert("Date cannot be beyond the current date.");
    document.getElementById("final-bill-date").value = today;
  } else {
    document.getElementById("final-bill-date").value = date;
  }
};


// patient contact existance check
function checkExistance(contact) {
  const flag = 'contact-existence-check'; 

  $.ajax({
      url: 'ajax/customer.addNew.ajax.php', 
      type: 'POST',
      data: {
          phNo: contact,
          flag: flag
      },
      success: function(response) {
          // console.log(response);
          if (response == 1) { 
              document.getElementById('patientPhoneNumber').value = '';
              Swal.fire('Alert','Contact Exist. Please search customer properly or use different number.','info');
          } else {
              console.log('No existing data found');
          }
      },
      error: function(xhr, status, error) {
          console.error('AJAX request failed:', status, error);
          Swal.fire('Error', 'An unexpected error occurred. Please try again later.', 'error');
      }
  });
}

// contact validation
function contactValidation(t) {
  let enteredContact = t.value;

  if (enteredContact.length !== 10 || !/^\d{10}$/.test(enteredContact)) {
      t.value = '';
      Swal.fire('Error', 'Enter a valid 10-digit phone number!', 'error');
  }else{
    checkExistance(enteredContact);
  }
}

// ADD NEW CUSTOMER =======================================================================
function addCustomerModal(){
  let url = "components/AddCustomer.php";
  $(".add-customer-modal").load(url);
}

function addCustomer() {
  const patientName = document.getElementById('patientName');
  const patientContact = document.getElementById('patientPhoneNumber');
  const patientAddress = document.getElementById('patientAddress1');
  const flag = 'add-patient-details';

  if (patientName.value == '') {
      Swal.fire('Error', 'Enter patient name!', 'info');
      return;
  }

  if (patientContact.value == '') {
      Swal.fire('Error', 'Enter patient contact number!', 'info');
      return;
  }

  if (patientAddress.value == '') {
      Swal.fire('Error', 'Enter patient address!', 'info');
      return;
  }

  let formData = new FormData();
  formData.append('patientName', patientName.value);
  formData.append('patientPhoneNumber', patientContact.value);
  formData.append('patientAddress1', patientAddress.value);
  formData.append('flag', flag);

  $.ajax({
      url: 'ajax/customer.addNew.ajax.php', 
      type: 'POST',
      data: formData,
      contentType: false, 
      processData: false, 
      success: function(response) {
          const result = JSON.parse(response);
          console.log(result);
          if (result.status === 1) {
              Swal.fire('Success', 'Patient Details added successfully!', 'success').then(()=>{
                $('#add-customer-modal').modal('hide');
                setCustomer(result.pid);
              });
          } else {
              Swal.fire('Error', 'Unable to add patient details', 'error');
          }
      },
      error: function(xhr, status, error) {
          console.error('AJAX request failed:', status, error);
      }
  });
}


// GET CUSTOMER DETAILS
const getCustomer = (customer) => {
  if (customer.length > 0) {
    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById("customer-list").style.display = "block";
        // console.log("check xmlhttp responce : "+xmlhttp.responseText);
        document.getElementById("customer-list").innerHTML =
          xmlhttp.responseText;
      }
    };
    xmlhttp.open("GET", `ajax/customerSearch.ajax.php?data=${customer}`, true);
    xmlhttp.send();
  } else {
    document.getElementById("customer-list").style.display = "none";
  }
}; // end getCustomer

function setCustomer(id){
  // ================ get Name ================
  stockCheckUrl = "ajax/customer.getDetails.ajax.php?name=" + id;
  xmlhttp.open("GET", stockCheckUrl, false);
  xmlhttp.send(null);
  document.getElementById("customer").value = xmlhttp.responseText;
  document.getElementById("customer-name").value = xmlhttp.responseText;
  document.getElementById("customer-id").value = id;

  // ================ get Contact ================
  stockCheckUrl = "ajax/customer.getDetails.ajax.php?contact=" + id;
  xmlhttp.open("GET", stockCheckUrl, false);
  xmlhttp.send(null);
  document.getElementById("contact").innerHTML = xmlhttp.responseText;
  document.getElementById("customer-list").style.display = "none";
};

const counterBill = () => {
  document.getElementById("contact").innerHTML = "";
  document.getElementById("customer").value = "Cash Sales";
  document.getElementById("customer-id").value = "Cash Sales";
  document.getElementById("customer-name").value = "Cash Sales";
  document.getElementById("final-doctor-name").value = "Cash Sales";

  let selectElement = document.getElementById("doctor-select");
  selectElement.value = "Cash Sales";
};

//======= payment meode =========
document.getElementById("payment-mode").value = "Cash";
document.getElementById("final-payment").value = "Cash";

const getPaymentMode = (mode) => {
  document.getElementById("final-payment").value = mode;
};

/////////////// making search item focused fist value not a space \\\\\\\\\\\\\\\\\\
const firstInput = document.getElementById("product-name");

firstInput.addEventListener("input", function (event) {
  const inputValue = this.value;
  // Check if the first character is a space
  if (inputValue.length > 0 && inputValue[0] === " ") {
    this.value = inputValue.slice(1);
  }
});


// Cache DOM elements outside the function
const productName = document.getElementById("product-name");
const searchReult = document.getElementById("searched-items");
const searchedBatchNo = document.getElementById("searched-batchNo");
const extaDetails = document.getElementById("exta-details");

const elementsToClear = [
  "product-name",
  "weightage",
  "batch-no",
  "exp-date",
  "mrp",
  "gst",
  "item-weightage",
  "item-unit-type",
  "aqty",
  "type-check",
  "qty",
  "disc",
  "dPrice",
  "taxable",
  "amount",
].map((id) => document.getElementById(id));

const clearFields = () => {
  elementsToClear.forEach((element) => (element.value = ""));
  searchReult.innerHTML = "";
  searchedBatchNo.innerHTML = "";
};

// Debounce function to limit the rate of function execution
const debounce = (func, delay) => {
  let debounceTimer;
  return function () {
    const context = this;
    const args = arguments;
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => func.apply(context, args), delay);
  };
};

const searchItem = debounce((searchFor) => {
  if (productName.value === "") {
    searchReult.style.display = "none";
    searchedBatchNo.style.display = "none";
    clearFields();
    return;
  }

  if (searchFor.length === 0) {
    clearFields();
  } else if (searchFor.length > 2) {
    // const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
        searchReult.innerHTML = xmlhttp.responseText;
        searchReult.style.display = "block";
        extaDetails.style.display = "none";
      }
    };
    xmlhttp.open(
      "GET",
      "ajax/sales-item-list.ajax.php?data=" + encodeURIComponent(searchFor),
      true
    );
    xmlhttp.send();
  }

  newSellGenerateBill.setAttribute("disabled", "true");
}, 300); // 300ms delay for debounce


// ========= PRODUCT BATCH NUMBER FETCH AREA ==================
function itemsBatchDetails(prodcutId, name, stock){

  searchReult.style.display = "none";
  searchedBatchNo.style.display = "none";

  if (stock > 0) {
    
    // ==================== SEARCH PRODUCT NAME =====================
    productName.value = name;
    searchedBatchNo.style.display = "none";
    // ==================== EOF PRODUCT NAME SEARCH ================

    extaDetails.style.display = "none";

    document.getElementById("batch-no").value = "";
    document.getElementById("weightage").value = "";
    document.getElementById("exp-date").value = "";
    document.getElementById("mrp").value = "";
    document.getElementById("gst").value = "";

    document.getElementById("item-weightage").value = "";
    document.getElementById("item-unit-type").value = "";
    document.getElementById("aqty").value = "";
    document.getElementById("type-check").value = "";
    document.getElementById("qty").value = "";
    document.getElementById("disc").value = "";
    document.getElementById("dPrice").value = "";
    document.getElementById("taxable").value = "";
    document.getElementById("amount").value = "";

    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        searchedBatchNo.innerHTML = xmlhttp.responseText;
        searchedBatchNo.style.display = "block";
      }
    };
    xmlhttp.open(
      "GET",
      `ajax/sales-item-batch-list.ajax.php?prodId=${prodcutId}`,
      true
    );
    xmlhttp.send();
  }

  if (stock <= 0) {
    productName.value = "";
    document.getElementById("weightage").value = "";
    document.getElementById("batch-no").value = "";
    document.getElementById("exp-date").value = "";
    document.getElementById("mrp").value = "";
    document.getElementById("gst").value = "";

    document.getElementById("item-weightage").value = "";
    document.getElementById("item-unit-type").value = "";
    document.getElementById("aqty").value = "";
    document.getElementById("type-check").value = "";
    document.getElementById("qty").value = "";
    document.getElementById("disc").value = "";
    document.getElementById("dPrice").value = "";
    document.getElementById("taxable").value = "";
    document.getElementById("amount").value = "";
    document.getElementById("loose-stock").value = "";
    document.getElementById("loose-price").value = "";

    // document.getElementById("qty-type").setAttribute("disabled", true);

    extaDetails.style.display = "none";
    searchReult.style.display = "none";

    Swal.fire({
      title: "Want to add this itme?",
      text: "This Item is not avilable in your stock, do you want to add?",
      icon: "info",
      showDenyButton: false,
      showCancelButton: true,
      confirmButtonText: "Ok",
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "stock-in.php";
      }
    });
  }
};
// ========= END OF PRODUCT BATCH NUMBER FETCH AREA ==================

////////////////////////////////////////////////////////
///////////////////////////////////////////////////////
// function stockDetails(productId, batchNo, itemId){

//   searchReult.style.display = "none";
// searchedBatchNo.style.display = "none";

//   console.log(searchedBatchNo.style.display);
    
//   // document.getElementById("searched-batchNo").innerHTML = "";
//   // searchedBatchNo.innerHTML = "";
//   // searchedBatchNo.style.display = "none";

//   var selectedItem = productId;
//   var SelectedBatch = batchNo;

//   let tableVal = document.getElementById("dynamic-id").value;

//   if (tableVal > 0) {
//     let tableId = document.getElementById("item-body");
//     let jsTabelLength = tableId.rows.length;
//     let cellIndex_1 = 3;
//     let cellIndex_2 = 5;

//     for (let i = 0; i < jsTabelLength; i++) {
//       let row = tableId.rows[i];
//       let prodIdCell = row.cells[cellIndex_1];
//       let prevSelectedProdId = prodIdCell.innerHTML;

//       if (prevSelectedProdId == selectedItem) {
//         var prodBatchNoCell = row.cells[cellIndex_2];
//         let prevSelectedBatch = prodBatchNoCell.innerHTML;

//         var flag = 0;
//         if (prevSelectedBatch == SelectedBatch) {
//           flag = 1;
//           exist = 0;
//           document.getElementById("product-id").value = "";
//           document.getElementById("batch_no").value = "";
//           searchedBatchNo.style.display = "none";

//           Swal.fire("Failed!", "You have added this item previously.", "error");
//         } else {
//           document.getElementById("product-id").value = productId;
//           document.getElementById("batch_no").value = batchNo;
//           document.getElementById("batch-no").value = batchNo;
//           searchedBatchNo.style.display = "none";
//           document.getElementById("crnt-stck-itm-id").value = itemId;

//           // var xmlhttp = new XMLHttpRequest();
//           // ============== Check Existence ==============
//           stockCheckUrl = `ajax/stock.checkExists.ajax.php?Pid=${productId}&batchNo=${batchNo}`;
//           xmlhttp.open("GET", stockCheckUrl, false);
//           xmlhttp.send(null);
//           exist = xmlhttp.responseText;
//         }
//       } else {
//         document.getElementById("product-id").value = productId;
//         document.getElementById("batch_no").value = batchNo;
//         document.getElementById("batch-no").value = batchNo;
//         // document.getElementById("searched-batchNo").style.display = "none";
//         document.getElementById("crnt-stck-itm-id").value = itemId;

//         // var xmlhttp = new XMLHttpRequest();

//         // ============== Check Existence ==============
//         stockCheckUrl = `ajax/stock.checkExists.ajax.php?Pid=${productId}&batchNo=${batchNo}`;
//         xmlhttp.open("GET", stockCheckUrl, false);
//         xmlhttp.send(null);
//         exist = xmlhttp.responseText;
//       }
//       if ((flag = 1)) {
//         break;
//       } else {
//         continue;
//       }
//     }
//   } else {
//     document.getElementById("product-id").value = productId;
//     document.getElementById("batch_no").value = batchNo;
//     document.getElementById("batch-no").value = batchNo;
//     searchReult.style.display = "none";
//     searchedBatchNo.style.display = "none";
//     // document.getElementById("searched-batchNo").style.display = "none";
//     document.getElementById("crnt-stck-itm-id").value = itemId;

//     // ============== Check Existence ==============
//     stockCheckUrl = `ajax/stock.checkExists.ajax.php?Pid=${productId}&batchNo=${batchNo}`;
//     xmlhttp.open("GET", stockCheckUrl, false);
//     xmlhttp.send(null);
//     exist = xmlhttp.responseText;
//   }

//   if (exist == 1) {
//     extaDetails.style.display = "block";

//     // ============== Product Name ==============
//     stockItemUrl = "ajax/getProductDetails.ajax.php?id=" + productId;
//     // alert(url);
//     xmlhttp.open("GET", stockItemUrl, false);
//     xmlhttp.send(null);
//     productName.value = xmlhttp.responseText;
//     // alert(xmlhttp.responseText);

//     //==================== Weightage ====================
//     itemWeightageUrl = `ajax/getProductDetails.ajax.php?itemWeightage=${productId}`;
//     // alert(url);
//     xmlhttp.open("GET", itemWeightageUrl, false);
//     xmlhttp.send(null);
//     let packWeightage = xmlhttp.responseText;
//     document.getElementById("item-weightage").value = xmlhttp.responseText;
//     // alert(xmlhttp.responseText);

//     //==================== Unit ====================
//     unitUrl = "ajax/getProductDetails.ajax.php?itemUnit=" + productId;
//     // alert(unitUrl);
//     // window.location.href = unitUrl;
//     xmlhttp.open("GET", unitUrl, false);
//     xmlhttp.send(null);
//     let packUnit = xmlhttp.responseText;
//     let packOf = `${packWeightage} ${packUnit}`;
//     document.getElementById("weightage").value = packOf;
//     document.getElementById("item-unit-type").value = xmlhttp.responseText;
//     // // alert(xmlhttp.responseText);

//     //==================== Expiry Date ====================
//     expDateUrl = `ajax/getProductDetails.ajax.php?exp=${productId}&batchNo=${batchNo}`;
//     // alert(url);
//     xmlhttp.open("GET", expDateUrl, false);
//     xmlhttp.send(null);
//     document.getElementById("exp-date").value = xmlhttp.responseText;

//     //==================== MRP ====================
//     mrpUrl = `ajax/getProductDetails.ajax.php?stockmrp=${productId}&batchNo=${batchNo}`;
//     // alert(unitUrl);
//     // window.location.href = unitUrl;
//     xmlhttp.open("GET", mrpUrl, false);
//     xmlhttp.send(null);
//     document.getElementById("mrp").value = xmlhttp.responseText;
//     // alert(xmlhttp.responseText);

//     //==================== PTR ====================
//     ptrUrl = `ajax/getProductDetails.ajax.php?stockptr=${productId}&currentStockId=${itemId}`;
//     // alert(ptrUrl);
//     // window.location.href = unitUrl;
//     xmlhttp.open("GET", ptrUrl, false);
//     xmlhttp.send(null);
//     document.getElementById("purchased-cost").value = xmlhttp.responseText;
//     // alert(xmlhttp.responseText);

//     //==================== Loose Stock ====================
//     looseStockUrl = `ajax/getProductDetails.ajax.php?looseStock=${productId}&batchNo=${batchNo}`;
//     // alert(ptrUrl);
//     // window.location.href = unitUrl;
//     xmlhttp.open("GET", looseStockUrl, false);
//     xmlhttp.send(null);
//     document.getElementById("loose-stock").value = xmlhttp.responseText;
//     // alert(xmlhttp.responseText);
//     // ======================= AVAILIBILITY ===========================
//     itemAvailibilityUrl = `ajax/getProductDetails.ajax.php?availibility=${productId}&batchNo=${batchNo}`;
//     xmlhttp.open("GET", itemAvailibilityUrl, false);
//     xmlhttp.send(null);
//     document.getElementById("aqty").value = xmlhttp.responseText;

//     //==================== GST ====================
//     gstUrl = "ajax/product.getGst.ajax.php?stockgst=" + productId;
//     // alert(unitUrl);
//     // window.location.href = unitUrl;
//     xmlhttp.open("GET", gstUrl, false);
//     xmlhttp.send(null);
//     document.getElementById("gst").value = xmlhttp.responseText;
//     // alert(xmlhttp.responseText);

//     // =========================================================
//     // ===================== XTERA DETAILS =====================
//     // =========================================================

//     //==================== Manufacturer Details ====================
//     manufUrl = "ajax/product.getManufacturer.ajax.php?id=" + productId;
//     xmlhttp.open("GET", manufUrl, false);
//     xmlhttp.send(null);
//     document.getElementById("manuf").value = xmlhttp.responseText;
//     // alert(xmlhttp.responseText);

//     manufNameUrl =
//       "ajax/product.getManufacturer.ajax.php?manufName=" + productId;
//     xmlhttp.open("GET", manufNameUrl, false);
//     xmlhttp.send(null);
//     // alert(xmlhttp.responseText);
//     document.getElementById("manufName").value = xmlhttp.responseText;

//     //////=======================================\\\\\\
//     //==================== Content ====================
//     contentUrl = "ajax/product.getContent.ajax.php?prodComposition=" + productId;
//     xmlhttp.open("GET", contentUrl, false);
//     xmlhttp.send(null);
//     document.getElementById("productComposition").value = xmlhttp.responseText;

//     document.getElementById("qty").focus();

//     newSellGenerateBill.setAttribute("disabled", "true");
//   } else {
//     document.getElementById("product-name").value = "";
//     document.getElementById("weightage").value = "";
//     document.getElementById("batch-no").value = "";
//     document.getElementById("exp-date").value = "";

//     document.getElementById("weightage").value = "";
//     document.getElementById("exp-date").value = "";

//     document.getElementById("mrp").value = "";
//     document.getElementById("gst").value = "";

//     document.getElementById("item-weightage").value = "";
//     document.getElementById("item-unit-type").value = "";
//     document.getElementById("aqty").value = "";
//     document.getElementById("type-check").value = "";
//     document.getElementById("qty").value = "";
//     document.getElementById("disc").value = "";
//     document.getElementById("dPrice").value = "";
//     document.getElementById("taxable").value = "";
//     document.getElementById("amount").value = "";

//     // document.getElementById("qty-type").setAttribute("disabled", true);
//     document.getElementById("loose-stock").value = "";
//     document.getElementById("loose-price").value = "";
//     extaDetails.style.display = "none";
//   }
// }



function stockDetails(productId, batchNo, itemId) {
  searchReult.style.display = "none";
  searchedBatchNo.style.display = "none";

  let selectedItem = productId;
  let SelectedBatch = batchNo;
  let tableVal = document.getElementById("dynamic-id").value;

  const xmlhttp = new XMLHttpRequest();
  
  // Helper function to make AJAX calls
  const makeAjaxCall = (url) => {
    xmlhttp.open("GET", url, false);
    xmlhttp.send(null);
    return xmlhttp.responseText;
  };

  const updateFields = (data) => {
    const fields = [
      { id: "product-id", value: productId },
      { id: "batch_no", value: batchNo },
      { id: "batch-no", value: batchNo },
      { id: "crnt-stck-itm-id", value: itemId }
    ];
    fields.forEach(field => document.getElementById(field.id).value = field.value);
    searchedBatchNo.style.display = "none";
  };

  if (tableVal > 0) {
    let tableId = document.getElementById("item-body");
    let jsTabelLength = tableId.rows.length;
    let exists = false;

    for (let i = 0; i < jsTabelLength; i++) {
      let row = tableId.rows[i];
      let prevSelectedProdId = row.cells[3].innerHTML;
      let prevSelectedBatch = row.cells[5].innerHTML;

      if (prevSelectedProdId === selectedItem) {
        if (prevSelectedBatch === SelectedBatch) {
          exists = true;
          Swal.fire("Failed!", "You have added this item previously.", "error");
          break;
        }
      }
    }

    if (!exists) updateFields();
  } else {
    updateFields();
  }

  // Check stock existence
  let exist = makeAjaxCall(`ajax/stock.checkExists.ajax.php?Pid=${productId}&batchNo=${batchNo}`);
  
  if (exist == 1) {
    extaDetails.style.display = "block";

    const urls = {
      productName: `ajax/getProductDetails.ajax.php?id=${productId}`,
      itemWeightage: `ajax/getProductDetails.ajax.php?itemWeightage=${productId}`,
      itemUnit: `ajax/getProductDetails.ajax.php?itemUnit=${productId}`,
      expDate: `ajax/getProductDetails.ajax.php?exp=${productId}&batchNo=${batchNo}`,
      mrp: `ajax/getProductDetails.ajax.php?stockmrp=${productId}&batchNo=${batchNo}`,
      ptr: `ajax/getProductDetails.ajax.php?stockptr=${productId}&currentStockId=${itemId}`,
      looseStock: `ajax/getProductDetails.ajax.php?looseStock=${productId}&batchNo=${batchNo}`,
      availability: `ajax/getProductDetails.ajax.php?availibility=${productId}&batchNo=${batchNo}`,
      gst: `ajax/product.getGst.ajax.php?stockgst=${productId}`,
      manuf: `ajax/product.getManufacturer.ajax.php?id=${productId}`,
      manufName: `ajax/product.getManufacturer.ajax.php?manufName=${productId}`,
      content: `ajax/product.getContent.ajax.php?prodComposition=${productId}`
    };

    // Populate fields from AJAX responses
    document.getElementById("product-name").value = makeAjaxCall(urls.productName);
    document.getElementById("item-weightage").value = makeAjaxCall(urls.itemWeightage);
    
    let packUnit = makeAjaxCall(urls.itemUnit);
    let packWeightage = document.getElementById("item-weightage").value;
    document.getElementById("weightage").value = `${packWeightage} ${packUnit}`;
    document.getElementById("item-unit-type").value = packUnit;

    document.getElementById("exp-date").value = makeAjaxCall(urls.expDate);
    document.getElementById("mrp").value = makeAjaxCall(urls.mrp);
    document.getElementById("purchased-cost").value = makeAjaxCall(urls.ptr);
    document.getElementById("loose-stock").value = makeAjaxCall(urls.looseStock);
    document.getElementById("aqty").value = makeAjaxCall(urls.availability);
    document.getElementById("gst").value = makeAjaxCall(urls.gst);
    
    document.getElementById("manuf").value = makeAjaxCall(urls.manuf);
    document.getElementById("manufName").value = makeAjaxCall(urls.manufName);
    document.getElementById("productComposition").value = makeAjaxCall(urls.content);

    document.getElementById("qty").focus();
    newSellGenerateBill.setAttribute("disabled", "true");
  } else {
    // Reset fields
    const fieldsToReset = [
      "product-name", "weightage", "batch-no", "exp-date", "mrp", "gst",
      "item-weightage", "item-unit-type", "aqty", "type-check", "qty", 
      "disc", "dPrice", "taxable", "amount", "loose-stock", "loose-price"
    ];
    
    fieldsToReset.forEach(id => document.getElementById(id).value = "");
    extaDetails.style.display = "none";
  }
}


/////// extra detials div control function \\\\\\\\
const chekForm = (t) => {
  if (t.value.length == 0 || t.value.length == 1) {
    newSellGenerateBill.setAttribute("disabled", "true");
  }

  if (productName.value == "") {
    extaDetails.style.display = "none";
    searchReult.style.display = "none";

    tableBody = document.getElementById("item-body");

    if (tableBody.getElementsByTagName("tr") != null) {
      newSellGenerateBill.removeAttribute("disabled");
    } else {
      newSellGenerateBill.setAttribute("disabled", "true");
    }
  }
};


const checkQty = (t) => {
  searchedBatchNo.style.display = "none";
  if (t.value <= 0) {
    document.getElementById("add-button").setAttribute("disabled", "true");
    Swal.fire("Alert", "Enter valid qantity.", "info");
  } else {
    document.getElementById("add-button").removeAttribute("disabled");
  }
};

// old codes ==============
// function onQty (qty){
//   if (qty == "") {
//     qty = 0;
//   }

//   let mrp = document.getElementById("mrp").value;
//   let itemWeatage = document.getElementById("item-weightage").value;
//   let unitType = document.getElementById("item-unit-type").value;
//   let loosePrice = "";

//   if (
//     allowedUnits
//       .map((unit) => unit.toLowerCase())
//       .includes(unitType.toLowerCase())
//   ) {
//     loosePrice = parseFloat(mrp) / parseInt(itemWeatage);
//   } else {
//     loosePrice = "";
//   }

//   document.getElementById("loose-price").value = loosePrice;

//   //=============================== AVAILIBILITY CHECK ================================
//   let availibility = document.getElementById("aqty").value;
//   availibility = parseInt(availibility);

//   if (qty > availibility) {
//     qty = "";
//     document.getElementById("qty").value = qty;
//     string_1 = "Unable to input this value. Available qantity is";
//     string_2 = availibility;
//     string_3 = " in this batch.";
//     string_4 = string_1.concat(string_2).concat(string_3);
//     window.alert(string_4);
//   }

//   // =============================== Item pack type calculation ======================
//   let itemWeightage = document.getElementById("item-weightage").value;
//   let checkSum = "";
//   let itemPackType = "";

//   if (
//     allowedUnits
//       .map((unit) => unit.toLowerCase())
//       .includes(unitType.toLowerCase())
//   ) {
//     checkSum = parseInt(qty) % parseInt(itemWeightage);
//     if (checkSum == 0) {
//       itemPackType = "Pack";
//     } else {
//       itemPackType = "Loose";
//     }
//   } else {
//     itemPackType = "others";
//   }

//   document.getElementById("type-check").value = itemPackType;

//   // console.log("item pack type : "+document.getElementById("type-check").value);
//   // =========================== ========================== ====================

//   var pid = document.getElementById("product-id").value;
//   var bno = document.getElementById("batch-no").value;
//   let disc = document.getElementById("disc").value;
//   let discPrice = document.getElementById("dPrice").value;
//   let gst = document.getElementById("gst").value;
//   let taxableAmount = "";
//   let netPayble = "";

//   if (disc != "") {
//     disc = disc;
//   } else {
//     disc = 0;
//   }

//   if (qty > 0) {
//     if (itemPackType == "others") {
//       // =========== (item except 'tab' or 'cap' calculation area) ===================
//       discPrice = parseFloat(mrp) - parseFloat(mrp) * (parseFloat(disc) / 100);
//       netPayble = parseFloat(discPrice) * parseInt(qty);
//       netPayble = parseFloat(netPayble).toFixed(2);
//       discPrice = discPrice.toFixed(2);

//       taxableAmount = (parseFloat(netPayble) * 100) / (parseFloat(gst) + 100);
//       taxableAmount = parseFloat(taxableAmount).toFixed(2);

//       document.getElementById("dPrice").value = discPrice;
//       document.getElementById("taxable").value = taxableAmount;
//       document.getElementById("amount").value = netPayble;
//     } else {
//       // =========== (item = tab or item = cap calculation area) ===================
//       discPrice =
//         parseFloat(loosePrice) -
//         parseFloat(loosePrice) * (parseFloat(disc) / 100);
//       netPayble = parseFloat(discPrice) * parseInt(qty);
//       netPayble = parseFloat(netPayble).toFixed(2);
//       discPrice = discPrice.toFixed(2);

//       taxableAmount = (parseFloat(netPayble) * 100) / (parseFloat(gst) + 100);
//       taxableAmount = parseFloat(taxableAmount).toFixed(2);

//       document.getElementById("dPrice").value = discPrice;
//       document.getElementById("taxable").value = taxableAmount;
//       document.getElementById("amount").value = netPayble;
//     }
//   } else {
//     document.getElementById("dPrice").value = "0";
//     document.getElementById("amount").value = "0";
//     document.getElementById("type-check").value = "0";
//   }

//   // console.log(itemPackType);
//   var currentItemId = document.getElementById("crnt-stck-itm-id").value;
//   // if (itemPackType == "Pack") {
//   //==================== purchased-cost on an Item ====================

//   purchased_cost_url = `ajax/getPurchasedCost.ajax.php?qtype=${itemPackType}&Qty=${qty}&currentItemId=${currentItemId}`;
//   xmlhttp.open("GET", purchased_cost_url, false);
//   xmlhttp.send(null);
//   document.getElementById("purchased-cost").value = xmlhttp.responseText;
//   // console.info(xmlhttp.responseText);

//   //==================== Margin on an Item ====================
//   marginUrl = `ajax/product.stockDetails.getMargin.ajax.php?Pid=${pid}&Bid=${bno}&qtype=${itemPackType}&Mrp=${mrp}&Qty=${qty}&disc=${disc}&taxable=${taxableAmount}&sellAmount=${netPayble}&currentItemId=${currentItemId}`;
//   xmlhttp.open("GET", marginUrl, false);
//   xmlhttp.send(null);
//   document.getElementById("margin").value = xmlhttp.responseText;

//   // check margine amount alert
//   if (parseFloat(document.getElementById("margin").value) < 0) {
//     const swalWithBootstrapButtons = Swal.mixin({
//       customClass: {
//         confirmButton: "btn btn-success",
//         cancelButton: "btn btn-danger",
//       },
//       buttonsStyling: false,
//     });
//     swalWithBootstrapButtons
//       .fire({
//         title: "Are you sure?",
//         text: "Check discount percent. This returns negative margine.",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonText: "Yes",
//         cancelButtonText: "No, cancel!",
//         reverseButtons: true,
//       })
//       .then((result) => {
//         if (result.isConfirmed) {
//           swalWithBootstrapButtons.fire({
//             title: "Check Margin",
//             text: "Margin value is now negative",
//             icon: "info",
//           });
//         } else if (result.dismiss === Swal.DismissReason.cancel) {
//           document.getElementById("disc").value = "";
//           swalWithBootstrapButtons.fire({
//             title: "Cancelled",
//             text: "Enter discount percent...",
//             icon: "info",
//           });
//         }
//       });
//   }

//   // ================ sales margin calculation area ==============

//   var payble = document.getElementById("amount").value;
//   var pAmount = document.getElementById("purchased-cost").value; // purchased cost
//   var salesMargin = parseFloat(payble) - parseFloat(pAmount);
//   document.getElementById("s-margin").value = salesMargin.toFixed(2);
// }


function showMarginAlert() {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    buttonsStyling: false,
  });

  swalWithBootstrapButtons.fire({
    title: "Are you sure?",
    text: "Check discount percent. This returns negative margin.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes",
    cancelButtonText: "No, cancel!",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      swalWithBootstrapButtons.fire({
        title: "Check Margin",
        text: "Margin value is now negative",
        icon: "info",
      });
    } else {
      document.getElementById("disc").value = "";
      swalWithBootstrapButtons.fire({
        title: "Cancelled",
        text: "Enter discount percent...",
        icon: "info",
      });
    }
  });
}


function checkMarginAlert(margin) {
  if (margin < 0) {
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger",
      },
      buttonsStyling: true,
    });
    
    swalWithBootstrapButtons.fire({
      title: "Are you sure?",
      text: "Check discount percent. This returns negative margin.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes",
      cancelButtonText: "No, cancel!",
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        swalWithBootstrapButtons.fire({
          title: "Check Margin",
          text: "Margin value is now negative",
          icon: "info",
        });
      } else {
        document.getElementById("disc").value = "";
        swalWithBootstrapButtons.fire({
          title: "Cancelled",
          text: "Enter discount percent...",
          icon: "info",
        });
      }
    });
  }
}

function onQty(qty) {
  qty = qty === "" ? 0 : parseInt(qty);
  
  const mrp = parseFloat(document.getElementById("mrp").value);
  const itemWeightage = parseInt(document.getElementById("item-weightage").value);
  const unitType = document.getElementById("item-unit-type").value;
  
  const isAllowedUnit = allowedUnits.map(unit => unit.toLowerCase()).includes(unitType.toLowerCase());
  const loosePrice = isAllowedUnit ? (mrp / itemWeightage).toFixed(2) : "";

  document.getElementById("loose-price").value = loosePrice;

  // Availability Check
  let availability = parseInt(document.getElementById("aqty").value);
  if (qty > availability) {
    qty = "";
    document.getElementById("qty").value = qty;
    alert(`Unable to input this value. Available quantity is ${availability} in this batch.`);
  }

  // Item Pack Type Calculation
  const itemPackType = isAllowedUnit 
    ? (qty % itemWeightage === 0 ? "Pack" : "Loose") 
    : "others";

  document.getElementById("type-check").value = itemPackType;

  // Calculating prices and amounts
  let disc = parseFloat(document.getElementById("disc").value) || 0;
  let netPayable = "0";
  let taxableAmount = "0";
  let discPrice = "0";

  if (qty > 0) {
    if (itemPackType === "others") {
      discPrice = (mrp * (1 - disc / 100)).toFixed(2);
    } else {
      discPrice = (parseFloat(loosePrice) * (1 - disc / 100)).toFixed(2);
    }
    netPayable = (parseFloat(discPrice) * qty).toFixed(2);
    taxableAmount = ((parseFloat(netPayable) * 100) / (parseFloat(document.getElementById("gst").value) + 100)).toFixed(2);
  }

  // Update fields
  document.getElementById("dPrice").value = discPrice;
  document.getElementById("taxable").value = taxableAmount;
  document.getElementById("amount").value = netPayable;

  // Purchased Cost Calculation
  const currentItemId = document.getElementById("crnt-stck-itm-id").value;
  const purchasedCostUrl = `ajax/getPurchasedCost.ajax.php?qtype=${itemPackType}&Qty=${qty}&currentItemId=${currentItemId}`;
  xmlhttp.open("GET", purchasedCostUrl, false);
  xmlhttp.send(null);
  document.getElementById("purchased-cost").value = xmlhttp.responseText;

  // Margin Calculation
  const pid = document.getElementById("product-id").value;
  const bno = document.getElementById("batch-no").value;
  const marginUrl = `ajax/product.stockDetails.getMargin.ajax.php?Pid=${pid}&Bid=${bno}&qtype=${itemPackType}&Mrp=${mrp}&Qty=${qty}&disc=${disc}&taxable=${taxableAmount}&sellAmount=${netPayable}&currentItemId=${currentItemId}`;
  xmlhttp.open("GET", marginUrl, false);
  xmlhttp.send(null);
  document.getElementById("margin").value = xmlhttp.responseText;

  // Margin Alert
  if (parseFloat(margin) < 0) {
    showMarginAlert();
  }

  // Sales Margin Calculation
  const payble = parseFloat(document.getElementById("amount").value);
  const pAmount = parseFloat(document.getElementById("purchased-cost").value);
  const salesMargin = (payble - pAmount).toFixed(2);
  
  document.getElementById("s-margin").value = salesMargin;

  searchedBatchNo.style.display = "none";
}



function onDisc(disc) {
  const mrp = parseFloat(document.getElementById("mrp").value);
  const itemWeightage = parseInt(document.getElementById("item-weightage").value);
  const unitType = document.getElementById("item-unit-type").value;
  const qtyInput = document.getElementById("qty");
  const availability = parseInt(document.getElementById("aqty").value);
  const gst = parseFloat(document.getElementById("gst").value);
  
  // Calculate loose price if applicable
  const loosePrice = allowedUnits.map(unit => unit.toLowerCase()).includes(unitType.toLowerCase())
    ? (mrp / itemWeightage).toFixed(2)
    : "";

  document.getElementById("loose-price").value = loosePrice;

  let qty = parseInt(qtyInput.value) || 0;
  if (qty > availability) qty = availability;

  // Set discount to 0 if not provided
  disc = parseFloat(disc) || 0;

  let netPayable = "0";
  let discPrice = "0";
  let taxableAmount = "0";

  if (qty > 0) {
    if (document.getElementById("type-check").value === "others") {
      discPrice = (mrp * (1 - disc / 100)).toFixed(2);
    } else {
      discPrice = (parseFloat(loosePrice) * (1 - disc / 100)).toFixed(2);
    }
    netPayable = (parseFloat(discPrice) * qty).toFixed(2);
    taxableAmount = ((parseFloat(netPayable) * 100) / (gst + 100)).toFixed(2);

    // Update UI
    document.getElementById("dPrice").value = discPrice;
    document.getElementById("taxable").value = taxableAmount;
    document.getElementById("amount").value = netPayable;
  } else {
    // Reset fields when qty is 0
    document.getElementById("dPrice").value = "";
    document.getElementById("amount").value = "";
    document.getElementById("type-check").value = "";
  }

  // Calculate Margin
  const pid = document.getElementById("product-id").value;
  const bno = document.getElementById("batch-no").value;
  const currentItemId = document.getElementById("crnt-stck-itm-id").value;
  
  const marginUrl = `ajax/product.stockDetails.getMargin.ajax.php?Pid=${pid}&Bid=${bno}&qtype=${document.getElementById("type-check").value}&Mrp=${mrp}&Qty=${qty}&disc=${disc}&taxable=${taxableAmount}&sellAmount=${netPayable}&currentItemId=${currentItemId}`;
  xmlhttp.open("GET", marginUrl, false);
  xmlhttp.send(null);
  document.getElementById("margin").value = xmlhttp.responseText;

  // Check Margin Alert
  checkMarginAlert(parseFloat(margin));

  // Sales Margin Calculation
  const payable = parseFloat(netPayable);
  const purchasedCost = parseFloat(document.getElementById("purchased-cost").value);
  const salesMargin = (payable - purchasedCost).toFixed(2);
  
  document.getElementById("s-margin").value = salesMargin;

  searchedBatchNo.style.display = "none";
}

// old codes==========
// function onDisc(disc){

//   let mrp = document.getElementById("mrp").value;
//   let itemWeatage = document.getElementById("item-weightage").value;
//   let unitType = document.getElementById("item-unit-type").value;
//   let loosePrice = "";

//   if (
//     allowedUnits
//       .map((unit) => unit.toLowerCase())
//       .includes(unitType.toLowerCase())
//   ) {
//     loosePrice = parseFloat(mrp) / parseInt(itemWeatage);
//   } else {
//     loosePrice = "";
//   }

//   document.getElementById("loose-price").value = loosePrice;

//   var pid = document.getElementById("product-id").value;
//   var bno = document.getElementById("batch-no").value;
//   let gst = document.getElementById("gst").value;
//   let discPrice = document.getElementById("dPrice").value;

//   let itemTypeCheck = document.getElementById("type-check").value;
//   // console.log("on disc item type check : "+itemTypeCheck);

//   let qty = document.getElementById("qty").value;

//   // console.log("on disc qty : "+qty);
//   if (qty == "") {
//     qty = 0;
//   }
//   // console.log("on disc qty : "+qty);

//   let availibility = document.getElementById("aqty").value;
//   availibility = parseInt(availibility);

//   availibility = parseInt(availibility);
//   if (qty > availibility) {
//     qty = availibility;
//   }
//   // console.log("check disc quantity : ", qty);

//   if (disc != "") {
//     disc = disc;
//   } else {
//     disc = 0;
//   }

//   // console.log("disc value on disc function : "+disc);

//   if (qty > 0) {
//     if (itemTypeCheck == "others") {
//       discPrice = parseFloat(mrp) - parseFloat(mrp) * (parseFloat(disc) / 100);
//       netPayble = parseFloat(discPrice) * parseInt(qty);
//       netPayble = parseFloat(netPayble).toFixed(2);
//       discPrice = discPrice.toFixed(2);

//       taxableAmount = (parseFloat(netPayble) * 100) / (parseFloat(gst) + 100);
//       taxableAmount = parseFloat(taxableAmount).toFixed(2);

//       document.getElementById("dPrice").value = discPrice;
//       document.getElementById("taxable").value = taxableAmount;
//       document.getElementById("amount").value = netPayble;
//     } else {
//       discPrice =
//         parseFloat(loosePrice) -
//         parseFloat(loosePrice) * (parseFloat(disc) / 100);
//       netPayble = parseFloat(discPrice) * parseInt(qty);
//       netPayble = parseFloat(netPayble).toFixed(2);
//       discPrice = discPrice.toFixed(2);

//       taxableAmount = (parseFloat(netPayble) * 100) / (parseFloat(gst) + 100);
//       taxableAmount = parseFloat(taxableAmount).toFixed(2);

//       document.getElementById("dPrice").value = discPrice;
//       document.getElementById("taxable").value = taxableAmount;
//       document.getElementById("amount").value = netPayble;
//     }
//   } else {
//     document.getElementById("dPrice").value = "";
//     document.getElementById("amount").value = "";
//     document.getElementById("type-check").value = "";
//     document.getElementById("dPrice").value = "";
//   }

//   //==================== Margin on an Item ====================
//   var currentItemId = document.getElementById("crnt-stck-itm-id").value;

//   marginUrl = `ajax/product.stockDetails.getMargin.ajax.php?Pid=${pid}&Bid=${bno}&qtype=${itemTypeCheck}&Mrp=${mrp}&Qty=${qty}&disc=${disc}&taxable=${taxableAmount}&sellAmount=${netPayble}&currentItemId=${currentItemId}`;
//   xmlhttp.open("GET", marginUrl, false);
//   xmlhttp.send(null);
//   document.getElementById("margin").value = xmlhttp.responseText;

//   // check margine amount alert
//   if (parseFloat(document.getElementById("margin").value) < 0) {
//     const swalWithBootstrapButtons = Swal.mixin({
//       customClass: {
//         confirmButton: "btn btn-success",
//         cancelButton: "btn btn-danger",
//       },
//       buttonsStyling: true,
//     });
//     swalWithBootstrapButtons
//       .fire({
//         title: "Are you sure?",
//         text: "Check discount percent. This returns negative margine.",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonText: "Yes",
//         cancelButtonText: "No, cancel!",
//         reverseButtons: true,
//       })
//       .then((result) => {
//         if (result.isConfirmed) {
//           swalWithBootstrapButtons.fire({
//             title: "Check Margin",
//             text: "Margin value is now negative",
//             icon: "info",
//           });
//         } else if (result.dismiss === Swal.DismissReason.cancel) {
//           document.getElementById("disc").value = "";
//           swalWithBootstrapButtons.fire({
//             title: "Cancelled",
//             text: "Enter discount percent...",
//             icon: "info",
//           });
//         }
//       });
//   }

//   // ================ sales margin calculation area ==============
//   var payble = document.getElementById("amount").value;
//   var pAmount = document.getElementById("purchased-cost").value; // purchased cost
//   var salesMargin = parseFloat(payble) - parseFloat(pAmount);
//   document.getElementById("s-margin").value = salesMargin.toFixed(2);
// }

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
  let itemWeightage = document.getElementById("item-weightage").value;
  let unitType = document.getElementById("item-unit-type").value;
  let expDate = document.getElementById("exp-date").value;
  let mrp = document.getElementById("mrp").value;
  let available = document.getElementById("aqty").value;
  let itemComposition = document.getElementById("productComposition").value;
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
  let purchasedCost = document.getElementById("purchased-cost").value;
  let marginAmount = document.getElementById("margin").value;
  let salesMarginAmount = document.getElementById("s-margin").value;

  // ============== per item gst amount calculation ============
  let netGstAmount = parseFloat(amount) - parseFloat(taxable);
  netGstAmount = netGstAmount.toFixed(2);
  // console.log("net gst amount : ",netGstAmount);
  // ============ end of amount calculation ==============
  // ============ MRP SET ======================
  if (loosePrice != "") {
    calculatedMRP = loosePrice;
  } else {
    calculatedMRP = mrp;
  }

  //===========================================

  if (billDAte == "") {
    Swal.fire("Failed!", "Select Bill Date!", "error");
    return;
  }
  if (customer == "") {
    Swal.fire("Failed!", "Select/Enter Customer Details!", "error");
    return;
  }
  if (doctorName == "") {
    Swal.fire("Failed!", "Select Doctor!", "error");
    return;
  }
  if (paymentMode == "") {
    Swal.fire("Failed!", "Select Payment Mode!", "error");
    return;
  }
  if (productId == "") {
    Swal.fire("Failed!", "Product ID Not Found!", "error");
    return;
  }
  if (productName == "") {
    Swal.fire("Failed!", "Product Name Not Found!", "error");
    return;
  }
  if (batchNo == "") {
    Swal.fire("Failed!", "Item batch number not found!", "error");
    return;
  }
  if (weightage == "") {
    Swal.fire("Failed!", "Product Weatage/Unit Not Found!", "error");
    return;
  }
  if (expDate == "") {
    Swal.fire("Failed!", "Item expiery date not found!", "error");
    return;
  }
  if (mrp == "") {
    Swal.fire("Failed!", "Item MRP not found!", "error");
    return;
  }
  if (qty == "") {
    Swal.fire("Failed!", "Enter Sell Quantity:", "error");
    return;
  }
  if (discPercent == "") {
    Swal.fire("Failed!", "Enter Discount Minimum value 0", "error");
    return;
  }
  if (discPrice == "") {
    Swal.fire("Failed!", "Discounted Price Not Found!", "error");
    return;
  }
  if (gst == "") {
    Swal.fire("Failed!", "Item GST Not Found!", "error");
    return;
  }
  if (amount == "") {
    Swal.fire("Failed!", "Net Amount Not Found!", "error");
    return;
  }

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

        <td><i class="fas fa-trash text-danger" onclick="deleteItem(${slControl}, ${qty}, ${netGst.toFixed(
    2
  )}, ${itemMrp.toFixed(
    2
  )}, ${amount})" style="font-size:.7rem; width: .3rem"></i></td>

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

        <td class="d-none" id="${purchasedCost}">
            <input class="summary-items" type="text" name="itemPtr[]" value="${purchasedCost}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
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
            <input class="summary-items" type="text" name="taxable[]" value="${taxableAmount.toFixed(
              2
            )}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: end;" readonly>
        </td>

        <td id="tr-${slControl}-col-20">
            <input class="summary-items" type="text" name="gst[]" value="${gst}" style="word-wrap: break-word; width:3rem; font-size: .7rem;" readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="gstVal[]" value="${netGst.toFixed(
              2
            )}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        <td class="d-none" id="${marginAmount}">
            <input class="summary-items" type="text" name="marginAmount[]" value="${marginAmount}" style="word-wrap: break-word; width:3rem; font-size: .7rem;" readonly>
        </td>

        <td id="tr-${slControl}-col-23">
            <input class="summary-items" type="text" name="amount[]" value="${amount}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: end;" readonly>
        </td>

        /////////////////////\\\\\\\\\\\\\\\\\\\ EXTRA DATA /////////////////////\\\\\\\\\\\\\\\\\\\\

        <td class="d-none" id="${salesMarginAmount}">
            <input class="d-none summary-items" type="text" name="salesMargin[]" value="${salesMarginAmount}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: end;" readonly>
        </td>

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
    crntStckItemId: crntStckItemId,
    batchNo: batchNo,
    productName: productName,
    ManufId: Manuf,
    manufName: manufName,
    weightage: weightage,
    itemWeightage: itemWeightage,
    unitType: unitType,
    expDate: expDate,
    mrp: mrp,
    purchasedCost: purchasedCost,
    qtyTypeCheck: qtyTypeCheck,
    qty: qty,
    discPercent: discPercent,
    discPrice: discPrice,
    taxable: taxable,
    gst: gst,
    gstAmountPerItem: netGst,
    marginAmount: marginAmount,
    salesMarginAmount: salesMarginAmount,
    amount: amount,
    looseStock: looseStock,
    loosePrice: loosePrice,
    available: available,
    itemComposition: itemComposition,
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

  document.getElementById("final-doctor-name").value = doctorName;
  document.getElementById("aqty").value = "";
  extaDetails.style.display = "none";
  document.getElementById("add-item-details").reset();
  event.preventDefault();
  /////////////////////////////////////////////

  newSellGenerateBill.removeAttribute("disabled");
};

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

  let tBody = document.getElementById("item-body");
  // console.log(tBody.getElementsByTagName('tr').length);
  if (tBody.getElementsByTagName("tr").length == 0) {
    newSellGenerateBill.setAttribute("disabled", "true");
  }
};

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

  if (checkEditOption == "") {
    Tupledata = JSON.parse(tuple);
    // console.log(Tupledata);

    document.getElementById("product-id").value = Tupledata.productId;
    document.getElementById("product-name").value = Tupledata.productName;
    document.getElementById("crnt-stck-itm-id").value =
      Tupledata.crntStckItemId;
    document.getElementById("batch-no").value = Tupledata.batchNo;
    document.getElementById("batch_no").value = Tupledata.batchNo;

    document.getElementById("weightage").value = Tupledata.weightage;
    document.getElementById("item-weightage").value = Tupledata.itemWeightage;
    document.getElementById("item-unit-type").value = Tupledata.unitType;

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

    let netMRP = "";

    if (
      allowedUnits
        .map((unit) => unit.toLowerCase())
        .includes(Tupledata.unitType.toLowerCase())
    ) {
      // if(Tupledata.unitType == 'tab' || Tupledata.unitType == 'cap'){
      netMRP = parseFloat(Tupledata.loosePrice) * parseInt(Tupledata.qty);
      netMRP = parseFloat(netMRP).toFixed(2);
    } else {
      netMRP = parseFloat(Tupledata.mrp) * parseInt(Tupledata.qty);
      netMRP = parseFloat(netMRP).toFixed(2);
    }

    deleteItem(
      Tupledata.slno,
      Tupledata.qty,
      Tupledata.gstAmountPerItem,
      netMRP,
      Tupledata.amount
    );

    extaDetails.style.display = "block";
    newSellGenerateBill.setAttribute("disabled", "true");
  } else {
    Swal.fire("Can't Edit", "Please add/edit previous item first.", "error");
    document.getElementById("qty").focus();
  }
};

// reset button function ---------------
const reset = () => {
  document.getElementById("aqty").value = "";
  // document.getElementById("exta-details").style.display = "none";
  document.getElementById("add-item-details").reset();
  event.preventDefault();
};
