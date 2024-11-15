///////////////// preventing number input field to take dot or point\\\\\\\\\\\\\\\\\\\\\
const rtnQty = document.getElementById("return-qty");
rtnQty.addEventListener("input", function (event) {
  this.value = this.value.replace(".", "");
});

// const rtnFreeQty = document.getElementById('return-free-qty');
// rtnFreeQty.addEventListener('input', function (event) {
//     this.value = this.value.replace('.', '');
// });
///////////////////////////////////////////////////////////////////
//=============== stock in save button control ================
const purchaseReturnSave = document.getElementById("stock-return-save");
purchaseReturnSave.setAttribute("disabled", "true");

const chekForm = () => {
  var tableBody = document.getElementById("dataBody");

  if (
    document.getElementById("product-name").value == "" &&
    tableBody.getElementsByTagName("tr").length > 0
  ) {
    purchaseReturnSave.removeAttribute("disabled");
  } else {
    purchaseReturnSave.setAttribute("disabled", "true");
  }
};

chekForm();
//===============================================================

//////////////////// set distributor name /////////////////////

const distributorInput = document.getElementById("distributor-name");
const dropdown = document.getElementsByClassName("c-dropdown")[0];

distributorInput.addEventListener("focus", () => {
  dropdown.style.display = "block";

  let list = document.getElementsByClassName("lists")[0];

  let distributorURL = "ajax/distributor.list-view.ajax.php?match=all";
  // let request = new XMLHttpRequest();
  
  request.open("GET", distributorURL, true); // Asynchronous request
  console.log(request.responseText);
  request.onload = function() {
    if (request.status === 200) {
      list.innerHTML = request.responseText;
    } else {
      alert("Error fetching distributor list:", request.statusText);
    }
  };
  
  request.onerror = function() {
    alert("Request failed.");
  };

  request.send();

});

document.addEventListener("click", (event) => {
  // Check if the clicked element is not the input field or the dropdown
  if (
    !distributorInput.contains(event.target) &&
    !dropdown.contains(event.target)
  ) {
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

////////////////////////////////////////////////////////////////////
// ================ SELECTING DISTRIBUTOR ==================
const setDistributor = (t) => {
  let distributirId = t.id.trim();
  let distributirName = t.querySelector("span").innerHTML.trim();

  document.getElementById("dist-id").value = distributirId;
  document.getElementById("dist-name").value = distributirName;
  document.getElementById("distributor-name").value = distributirName;

  document.getElementsByClassName("c-dropdown")[0].style.display = "none";

  getItemList(distributirId);

};

// ======= fetch items data ===========
const getItemList = (distId) => {
  let billNoUrl = `ajax/return-item-list.ajax.php?dist-id=${distId}`;
  request.open("GET", billNoUrl, false);
  request.send(null);
  document.getElementById("product-select").innerHTML = request.responseText;
  // alert(request.responseText);

  document.getElementById("dist-id").value = distId;

  document.getElementById("product-select").style.display = "block";
  // document.getElementById("select-bill").style.display = "none";
};

// item search
function searchItem(input) {
  if (input != "") {
    document.getElementById("product-select").style.display = "block";
    // let input = document.getElementById('searchbar').value
    input = input.toLowerCase();
    let x = document.getElementsByClassName("item-list");

    for (i = 0; i < x.length; i++) {
      if (!x[i].innerHTML.toLowerCase().includes(input)) {
        x[i].style.display = "none";
      } else {
        x[i].style.display = "flex";
      }
    }
  } else {
    document.getElementById("product-select").style.display = "none";
    document.getElementById("stock-return-item-data").reset();
    event.preventDefault();
  }
}

const setMode = (returnMode) => {
  document.getElementById("refund-mode").value = returnMode;
};

// ======= sweet alert call for item select =======
// const qantityAlert = (stockInQty, stockOutQty) =>{
//     console.log(stockInQty);
//     console.log(stockOutQty);
// }

// =============== item details fetch ===============
const getItemDetails = (stockInId,stokInDetialsId,batchNo,productId,productName,billdate,billNumber,t) => {
  document.getElementById("return-mode").focus();

  document.getElementById("select-item-div").value = t.id;
  document.getElementById("stockInId").value = stockInId;
  document.getElementById("stokInDetailsId").value = stokInDetialsId;
  document.getElementById("batch-number").value = batchNo;
  document.getElementById("bill-number").value = billNumber;
  document.getElementById("product-name").value = productName;
  document.getElementById("bill-date").value = billdate;

  chekForm();

  if (productId != "") {
    document.getElementById("product-id").value = productId;

    //==================== Expiry Date ====================
    let expUrl = `ajax/stockIn.all.ajax.php?stock-exp=${stokInDetialsId}`;
    // alert(expUrl);
    request.open("GET", expUrl, false);
    request.send(null);
    document.getElementById("exp-date").value = request.responseText;
    // alert(request.responseText);

    //==================== Weightage ====================
    let weatageUrl = `ajax/stockIn.all.ajax.php?weightage=${stokInDetialsId}`;
    // alert(url);
    request.open("GET", weatageUrl, false);
    request.send(null);
    document.getElementById("weatage").value = request.responseText;
    // alert(request.responseText);

    //==================== PTR ====================
    let ptrUrl = `ajax/stockIn.all.ajax.php?ptr=${stokInDetialsId}`;
    request.open("GET", ptrUrl, false);
    request.send(null);
    document.getElementById("ptr").value = request.responseText;
    // alert(request.responseText);

    //==================== DISC ====================
    let discUrl = `ajax/stockIn.all.ajax.php?discount=${stokInDetialsId}`;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    request.open("GET", discUrl, false);
    request.send(null);
    document.getElementById("discount").value = request.responseText;
    // alert(request.responseText);

    //==================== GST ====================
    let gstUrl = `ajax/stockIn.all.ajax.php?gst=${stokInDetialsId}`;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    request.open("GET", gstUrl, false);
    request.send(null);
    document.getElementById("gst").value = request.responseText;
    // alert(request.responseText);

    //==================== GST Amount Per Quantity ====================
    let GstAmountPerQuantity = `ajax/stockIn.all.ajax.php?gstAmountPerQuantity=${stokInDetialsId}`;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    request.open("GET", GstAmountPerQuantity, false);
    request.send(null);
    document.getElementById("gstAmountPerQty").value = request.responseText;
    // alert(request.responseText);

    //==================== taxable ====================
    let taxableUrl = `ajax/stockIn.all.ajax.php?taxable=${stokInDetialsId}`;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    request.open("GET", taxableUrl, false);
    request.send(null);
    document.getElementById("taxable").value = parseFloat(
      request.responseText
    ).toFixed(2);
    // alert(request.responseText);

    //==================== dprice price ====================
    let dpriceURL = `ajax/stockIn.all.ajax.php?dprice=${stokInDetialsId}`;
    request.open("GET", dpriceURL, false);
    request.send(null);
    document.getElementById("dprice").value = parseFloat(
      request.responseText
    ).toFixed(2);

    //==================== MRP ====================
    let mrpUrl = `ajax/stockIn.all.ajax.php?mrp=${stokInDetialsId}`;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    request.open("GET", mrpUrl, false);
    request.send(null);
    document.getElementById("mrp").value = request.responseText;
    // alert(request.responseText);

    //==================== Amount ====================
    let amountUrl = `ajax/stockIn.all.ajax.php?amount=${stokInDetialsId}`;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    request.open("GET", amountUrl, false);
    request.send(null);
    document.getElementById("amount").value = request.responseText;
    // alert(request.responseText);

    //==================== PURCHES QTY ====================
    let qtyUrl = `ajax/stockIn.all.ajax.php?purchased-qty=${stokInDetialsId}`;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    request.open("GET", qtyUrl, false);
    request.send(null);
    document.getElementById("purchased-qty").value = request.responseText;

    //==================== FREE QTY ====================
    let freeQtyUrl = `ajax/stockIn.all.ajax.php?free-qty=${stokInDetialsId}`;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    request.open("GET", freeQtyUrl, false);
    request.send(null);
    document.getElementById("free-qty").value = request.responseText;

    //==================== NET BUY QTY ====================
    let netBuyQtyUrl = `ajax/stockIn.all.ajax.php?net-buy-qty=${stokInDetialsId}`;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    request.open("GET", netBuyQtyUrl, false);
    request.send(null);
    document.getElementById("net-buy-qty").value = request.responseText;

    // ==================== LIVE BUY QTY ====================
    // let liveBuyQtyUrl = `ajax/stokReturn.allDetails.ajax.php?current-stock-qty=${stokInDetialsId}`;
    // // alert(liveBuyQtyUrl);
    // // window.location.href = unitUrl;
    // request.open("GET", liveBuyQtyUrl, false);
    // request.send(null);
    // document.getElementById("current-purchase-qty").value = request.responseText;
    // console.log(request.responseText);

    //==================== LIVE FREE QTY ====================
    // let liveFreeQtyUrl = `ajax/stokReturn.allDetails.ajax.php?current-free-qty=${stokInDetialsId}`;
    // // alert(currentQtyUrl);
    // // window.location.href = unitUrl;
    // request.open("GET", liveFreeQtyUrl, false);
    // request.send(null);
    // document.getElementById("current-free-qty").value = request.responseText;
    // // alert(request.responseText);

    //==================== CURRENT QTY ====================
    let currentQtyUrl = `ajax/currentStock.liveQtyDetails.ajax.php?currentQTY=${stokInDetialsId}`;
    // alert(currentQtyUrl);
    // window.location.href = unitUrl;
    request.open("GET", currentQtyUrl, false);
    request.send(null);
    document.getElementById("current-qty").value = request.responseText;
    // alert(request.responseText);

    document.getElementById("return-qty").focus();
    document.getElementById("product-select").style.display = "none";

    // ===================== LIVE TRANSACTIONAL QTY DATA =====================

    // let stockReturnDataUrl = `ajax/currentStock.liveQtyDetails.ajax.php?stockRtnData=${stokInDetialsId}`;
    // request.open("GET", stockReturnData, false);
    // request.send(null);
    // let stockReturnQty = request.responseText;

    // let stockOutDataUrl = `ajax/currentStock.liveQtyDetails.ajax.php?stockOutData=${stokInDetialsId}`;
    // request.open("GET", stockOutDataUrl, false);
    // request.send(null);
    // let stockOutQty = request.responseText;

    // qantityAlert(stockReturnQty, stockOutQty);
  } else {
    document.getElementById("ptr").value = "";
    document.getElementById("unit").value = "";
    document.getElementById("mrp").value = "";
    document.getElementById("gst").value = "";
    document.getElementById("product-id").value = "";
    document.getElementById("product-id").value = "";
    document.getElementById("product-name").value = "";
    document.getElementById("gstAmountPerQty").value = "";
  }
};

// const checkFQty = (returnFqty) => {
//     returnFqty = parseInt(returnFqty);
//     var CurrentFQty = document.getElementById("current-free-qty").value;

//     if (CurrentFQty < returnFqty) {
//         Swal.fire("Oops", "Return Quantity must be leser than Current Free Qantity! Possible return free qutantity is "+CurrentFQty, "error")
//         document.getElementById("return-free-qty").value = 0;
//     }
// }

//==== refund calculation area =============
const getRefund = (returnQty) => {
  returnQty = parseInt(returnQty);
  let stokInDetialsId = document.getElementById("stokInDetailsId").value;

  let currentPurchaseQty = document.getElementById("current-qty").value;
  if (returnQty > parseInt(currentPurchaseQty)) {
    Swal.fire({
      title: "Alert",
      text:
        "Return quantity cannot exceed current purchase stock quantity. Maximum possible return quantity is " +
        currentPurchaseQty,
      icon: "error",
      confirmButtonText: "Ok",
    }).then((result) => {
      document.getElementById("return-qty").value = "";
    });
  } else {
    if (isNaN(returnQty)) {
      document.getElementById("refund-amount").value = "";
      return;
    }

    if (returnQty != "") {
      let basePriceURL = `ajax/stockIn.all.ajax.php?refund-amount=${stokInDetialsId}`;
      request.open("GET", basePriceURL, false);
      request.send(null);
      let amount = request.responseText;

      let refund = (amount * returnQty).toFixed(2);

      document.getElementById("refund-amount").value = refund;
    } else if (parseInt(returnQty) == 0) {
      document.getElementById("refund-amount").value = "0";
    } else {
      document.getElementById("refund-amount").value = "";
    }

    // let gstPercetn = document.getElementById("");

    let returnGstAmount = document.getElementById("gstAmountPerQty").value;
    returnGstAmount = returnGstAmount * returnQty;
    returnGstAmount = returnGstAmount.toFixed(2);
    document.getElementById("return-gst-amount").value = returnGstAmount;
  }
};

// ##################################################################################
// ##################################################################################

//geeting bills by clicking on add button
// function addData() {

//     let seletedItemDiv = document.getElementById('select-item-div').value;

//     var distId = document.getElementById("distributor-name");
//     var stokInDetailsId = document.getElementById("stokInDetailsId");
//     var batchNumber = document.getElementById("batch-number");
//     var billNumber = document.getElementById("bill-number");
//     var billDate = document.getElementById("bill-date");
//     var returnMode = document.getElementById("return-mode");

//     var productId = document.getElementById("product-id");
//     var productName = document.getElementById('product-name').value;

//     var expDate = document.getElementById("exp-date");
//     var weatage = document.getElementById("weatage");
//     var ptr = document.getElementById("ptr");
//     var discount = document.getElementById("discount");
//     var gst = document.getElementById("gst");
//     var gstAmntPrQty = document.getElementById('gstAmountPerQty');
//     var RtrnGstAmount = document.getElementById("return-gst-amount");

//     var mrp = document.getElementById("mrp");
//     var amount = document.getElementById("amount");
//     var purchasedQty = document.getElementById("purchased-qty");
//     var netBuyQty = document.getElementById("net-buy-qty");
//     var freeQty = document.getElementById("free-qty");
//     var currentQty = document.getElementById("current-qty");
//     var returnQty = document.getElementById("return-qty");

//     var dprice = document.getElementById("dprice");
//     var taxableOnPurchase = document.getElementById("taxable");
//     var refundAmount = document.getElementById("refund-amount");

//     var qtyVal = document.getElementById("total-return-qty");
//     var totalReturnQty = parseInt(returnQty.value);

//     if (distId.value == "") {
//         Swal.fire("Oops", "Please select Distributor!", "error");
//         distId.focus();
//         return;
//     }

//     if (batchNumber.value == "") {
//         Swal.fire("Oops", "Please select Batch Number!", "error");
//         batchNumber.focus();
//         return;
//     }
//     if (billDate.value == "") {
//         Swal.fire("Oops", "Unable to Select Bill Date!", "error");
//         billDate.focus();
//         return;
//     }
//     if (returnMode.value == "") {
//         Swal.fire("Oops", "Please select your refund mode!", "error");
//         returnMode.focus();
//         return;
//     }

//     if (productName == "") {
//         Swal.fire("Oops", "Product name can't find!", "error");
//         return;
//     }
//     if (productId.value == "") {
//         Swal.fire("Oops", "Product name can't be empty!", "error");
//         productId.focus();
//         return;
//     }
//     if (expDate.value == "") {
//         Swal.fire("Oops", "Unable to get Expiry Date!", "error");
//         expDate.focus();
//         return;
//     }
//     if (weatage.value == "") {
//         weatage.focus();
//         Swal.fire("Oops", "Unable to get product weatage!", "error");
//         return;
//     }

//     if (ptr.value == "") {
//         ptr.focus();
//         Swal.fire("Oops", "Unable to get product ptr!", "error");
//         return;
//     }
//     if (discount.value == "") {
//         discount.focus();
//         Swal.fire("Oops", "Unable to get product discount!", "error");
//         return;
//     }
//     if (gst.value == "") {
//         gst.focus();
//         Swal.fire("Oops", "Unable to get product GST!", "error");
//         return;
//     }
//     if (taxable.value == "") {
//         taxable.focus();
//         Swal.fire("Oops", "Unable to get product tax amount!", "error");
//         return;
//     }
//     if (mrp.value == "") {
//         mrp.focus();
//         Swal.fire("Oops", "Unable to get product MRP!", "error");
//         return;
//     }
//     if (amount.value == "") {
//         amount.focus();
//         Swal.fire("Oops", "Unable to get product amount!", "error");
//         return;
//     }
//     if (purchasedQty.value == "") {
//         purchasedQty.focus();
//         Swal.fire("Oops", "Unable to get product purchased quantity!", "error");
//         return;
//     }
//     if (freeQty.value == "") {
//         freeQty.focus();
//         Swal.fire("Oops", "Unable to get product free quantity!", "error");
//         return;
//     }
//     if (currentQty.value == "") {
//         currentQty.focus();
//         Swal.fire("Oops", "Unable to get product current quantity!", "error");
//         return;
//     }
//     if (returnQty.value == "") {
//         returnQty.focus();
//         Swal.fire("Oops", "Please Enter How many Quantity You Want to Return!", "error");
//         return;
//     }

//     if (refundAmount.value == "") {
//         refundAmount.focus();
//         Swal.fire("Oops", "Unable to get Refund Amount!", "error");
//         return;
//     }

//     //////////////////// dynamic id and serial contolr ///////////////////
//     let slno = document.getElementById("dynamic-id").value;
//     let slControl = document.getElementById("serial-control").value;
//     slno++;
//     slControl++;
//     document.getElementById("dynamic-id").value = slno;
//     document.getElementById("serial-control").value = slControl;

//     //geeting total refund amount
//     var refund = document.getElementById("refund");
//     var refundAmt = parseFloat(refund.value) + parseFloat(refundAmount.value);
//     refund.value = refundAmt.toFixed(2);

//     // return gst generating
//     let gstAmount = (refundAmount.value * (gst.value / 100));
//     console.log(gstAmount);
//     let taxAmount = parseFloat(refundAmount.value) - withoutGst;

//     document.getElementById("return-gst-val").value = gstAmount;

//     //////////////////// onclik handler data \\\\\\\\\\\\\\\\\\\
//     var divElement = document.getElementById(seletedItemDiv);
//     originalClickHandler = divElement.onclick;

//     // =========================================================

//     const appendData = () => {

//         jQuery("#dataBody")
//             .append(`<tr id="table-row-${slControl}">
//                     <td  style="color: red;">
//                         <i class="fas fa-trash pt-3" onclick='deleteData(${slControl}, ${returnQty.value}, ${taxAmount}, ${refundAmount.value}, ${seletedItemDiv}, ${originalClickHandler})'></i>
//                     </td>
//                     <td id="row-${slControl}-col-2" style="font-size:.8rem ; padding-top:1.5rem"scope="row">${slno}</td>
//                     <td class="pt-3" id="row-${slControl}-col-4">
//                         <input class="td-item" type="text" name="productName[]" value="${productName}" readonly>
//                         <input class="td-item" type="text" name="setof[]" value="${weatage.value}" readonly>
//                         <input class="d-none" type="text" name="productId[]" value="${productId.value}" readonly>
//                         <input class="d-none" type="number" name="purchasedQty[]" value="${purchasedQty.value}" readonly>
//                         <input class="d-none" type="number" name="freeQty[]" value="${freeQty.value}" readonly>
//                         <input class="d-none" type="text" name="stok-in-details-id[]" value="${stokInDetailsId.value}" readonly>
//                         <input class="d-none" type="text" name="distBillNo[]" value="${billNumber.value}" readonly>
//                     </td>
//                     <td class="pt-3" id="row-${slControl}-col-5">
//                         <input class="td-input" type="text" name="batchNo[]" value="${batchNumber.value}" readonly>
//                     </td>
//                     <td class="pt-3" id="row-${slControl}-col-6">
//                         <input class="td-input" type="text" name="expDate[]" value="${expDate.value}" readonly>
//                     </td>
//                     <td class="pt-3" id="row-${slControl}-col-10">
//                         <input class="td-input" type="text" name="mrp[]" value="${mrp.value}" readonly>
//                     </td>
//                     <td class="pt-3" id="row-${slControl}-col-11">
//                         <input class="td-input" type="text" name="ptr[]" value="${ptr.value}" readonly>
//                     </td>
//                     <td class="pt-3"id="row-${slControl}-col-12">
//                         <input class="td-input" type="text" name="disc-percent[]" value="${discount.value}%" readonly>
//                     </td>
//                     <td class="ps-1 pt-3" id="row-${slControl}-col-13">
//                         <input class="td-input" type="text" name="gst[]" value="${gst.value}%" readonly>
//                     </td>
//                     <td class="pt-3" id="row-${slControl}-col-14">
//                         <input class="td-input" type="text" name="return-qty[]" value="${parseFloat(returnQty.value)}" readonly>
//                     </td>
//                     <td class="amnt-td pt-3" id="row-${slControl}-col-16">
//                         <input class="td-input" type="text" name="refund-amount[]" value="${refundAmount.value}" readonly>
//                     </td>
//                 </tr>`);

//         return true;
//     }

//     if (appendData() === true) {

//         if (slno > 1) {
//             let id = document.getElementById("items-qty");
//             let newId = parseFloat(id.value) + 1;
//             document.getElementById("items-qty").value = newId;

//         } else {
//             document.getElementById("items-qty").value = slno;
//         }

//         if (slno > 1) {
//             let Qty = parseInt(qtyVal.value);

//             let newQty = Qty + totalReturnQty;
//             document.getElementById("total-return-qty").value = newQty;

//         } else {
//             document.getElementById("total-return-qty").value = totalReturnQty;
//         }

//         ///////////////////////////////////////////////////////////////////////////////////

//         const dataTuple = {

//             seletedItemDiv: seletedItemDiv,

//             slno: slControl,
//             stokInDetailsId: stokInDetailsId.value,
//             productId: productId.value,
//             productName: productName,
//             batchNumber: batchNumber.value,
//             billNumber: billNumber.value,

//             billDate: billDate.value,
//             expDate: expDate.value,
//             weatage: weatage.value,
//             mrp: mrp.value,
//             ptr: ptr.value,
//             discount: discount.value,
//             gst: gst.value,
//             gstAmountPerQty: gstAmntPrQty.value,

//             dprice: dprice.value,
//             taxableOnPurchase: taxableOnPurchase.value,
//             RtrnGstAmount: RtrnGstAmount.value,

//             amount: amount.value,
//             purchasedQty: purchasedQty.value,
//             ntByQty: netBuyQty.value,
//             freeQty: freeQty.value,
//             currentQty: currentQty.value,
//             returnQty: returnQty.value,
//             refundAmount: refundAmount.value,
//         };

//         let tupleData = JSON.stringify(dataTuple);

//         document.getElementById(`row-${slControl}-col-2`).onclick = function () {
//             editItem(tupleData, originalClickHandler);
//         };
//         document.getElementById(`row-${slControl}-col-4`).onclick = function () {
//             editItem(tupleData, originalClickHandler);
//         };
//         document.getElementById(`row-${slControl}-col-5`).onclick = function () {
//             editItem(tupleData, originalClickHandler);
//         };
//         document.getElementById(`row-${slControl}-col-6`).onclick = function () {
//             editItem(tupleData, originalClickHandler);
//         };
//         document.getElementById(`row-${slControl}-col-10`).onclick = function () {
//             editItem(tupleData, originalClickHandler);
//         };
//         document.getElementById(`row-${slControl}-col-11`).onclick = function () {
//             editItem(tupleData, originalClickHandler);
//         };
//         document.getElementById(`row-${slControl}-col-12`).onclick = function () {
//             editItem(tupleData, originalClickHandler);
//         };
//         document.getElementById(`row-${slControl}-col-13`).onclick = function () {
//             editItem(tupleData, originalClickHandler);
//         };
//         document.getElementById(`row-${slControl}-col-14`).onclick = function () {
//             editItem(tupleData, originalClickHandler);
//         };
//         document.getElementById(`row-${slControl}-col-16`).onclick = function () {
//             editItem(tupleData, originalClickHandler);
//         };

//         //////// document.getElementById("demo").innerHTML = await myPromise;/////////////
//         //////////////////////////////////////////////////////////////////////////////////

//         document.getElementById("stokInDetailsId").value = '';
//         document.getElementById("product-id").value = '';
//         document.getElementById('product-name').value = '';

//         document.getElementById("stock-return-item-data").reset();
//         event.preventDefault();

//         /// ============ row modify function ===============

//         disableOnClickFunction(seletedItemDiv);

//         purchaseReturnSave.removeAttribute('disabled');
//     }

// } //eof addData

function addData() {
  const distId = document.getElementById("distributor-name");
  const returnMode = document.getElementById("return-mode");
  const stokInDetailsId = document.getElementById("stokInDetailsId");
  const billNumber = document.getElementById("bill-number");
  const billDate = document.getElementById("bill-date");
  const batchNumber = document.getElementById("batch-number");
  const productId = document.getElementById("product-id");
  const productName = document.getElementById("product-name");
  const weatage = document.getElementById("weatage");
  const expDate = document.getElementById("exp-date");
  const purchasedQty = document.getElementById("purchased-qty");
  const freeQty = document.getElementById("free-qty");
  const mrp = document.getElementById("mrp");
  const ptr = document.getElementById("ptr");
  const discount = document.getElementById("discount");
  const dprice = document.getElementById("dprice");
  const GST = document.getElementById("gst");
  const amount = document.getElementById("amount");
  const gstAmntPrQty = document.getElementById("gstAmountPerQty");
  const taxableOnPurchase = document.getElementById("taxable");
  const netBuyQty = document.getElementById("net-buy-qty");
  const returnQty = document.getElementById("return-qty");
  const currentQty = document.getElementById("current-qty");
  const refundAmount = document.getElementById("refund-amount");

  //   summary elements
  const itemsQty = document.getElementById("items-qty");
  const TotalReturnQty = document.getElementById("total-return-qty");
  const TotalReturnGST = document.getElementById("return-gst-val");
  const TotalRefund = document.getElementById("refund");

  const requiredInputs = [
    { element: distId, message: "Please select Distributor!" },
    { element: productName, message: "Product name can't find!" },
    { element: "bill-date", message: "Unable to Select Bill Date!" },
    { element: "batch-number", message: "Please select Batch Number!" },
    { element: returnMode, message: "Please select your refund mode!" },
    { element: returnQty, message: "Please Enter Return Quantity!" },
    { element: refundAmount, message: "Unable to get Refund Amount!" },
  ];

  for (const input of requiredInputs) {
    if (input.element.value === "") {
      Swal.fire("Oops", input.message, "error");
      input.element.focus();
      return;
    }
  }

  // Calculate the amount that is GST% of refundAmount
  //   const taxAmount = (parseInt(GST.value) / 100) * parseFloat(refundAmount);
  //   console.log(taxAmount);
  const gstAmount = (
    parseFloat(refundAmount.value) *
    (GST.value / 100)
  ).toFixed(2);

  const seletedItemDiv = document.getElementById("select-item-div").value;
  //   var slno = document.getElementById("dynamic-id");
  var slno = document.getElementById("dataBody").childElementCount + 1;
  const slControl = document.getElementById("serial-control").value++;

  //////////////////// onclik handler data \\\\\\\\\\\\\\\\\\\
  var divElement = document.getElementById(seletedItemDiv);
  originalClickHandler = divElement.onclick;

  const dataRow = `
    <tr id="table-row-${slControl}">
                        <td  style="color: red;">
                            <i class="fas fa-trash pt-3" onclick='deleteData(
                                ${slControl},
                                ${returnQty.value},
                                ${gstAmount},
                                ${refundAmount.value},
                                ${seletedItemDiv},
                                ${originalClickHandler}
                            )'></i>
                        </td>
                        <td id="row-${slControl}-col-2" style="font-size:.8rem ; padding-top:1.5rem"scope="row">${slno}</td>
                        <td class="pt-3" id="row-${slControl}-col-4">
                            <input class="td-item" type="text" name="productName[]" value="${productName.value
    }" readonly>
                            <input class="td-item" type="text" name="setof[]" value="${weatage.value
    }" readonly>
                            <input class="d-none" type="text" name="productId[]" value="${productId.value
    }" readonly>
                            <input class="d-none" type="number" name="purchasedQty[]" value="${purchasedQty.value
    }" readonly>
                            <input class="d-none" type="number" name="free-qty[]" value="${freeQty.value
    }" readonly>
                            <input class="d-none" type="text" name="stok-in-details-id[]" value="${stokInDetailsId.value
    }" readonly>
                            <input class="d-none" type="text" name="distBillNo[]" value="${billNumber.value
    }" readonly>
                        </td>
                        <td class="pt-3" id="row-${slControl}-col-5">
                            <input class="td-input" type="text" name="batchNo[]" value="${batchNumber.value
    }" readonly>
                        </td>
                        <td class="pt-3" id="row-${slControl}-col-6">
                            <input class="td-input" type="text" name="expDate[]" value="${expDate.value
    }" readonly>
                        </td>
                        <td class="pt-3" id="row-${slControl}-col-10">
                            <input class="td-input" type="text" name="mrp[]" value="${mrp.value
    }" readonly>
                        </td>
                        <td class="pt-3" id="row-${slControl}-col-11">
                            <input class="td-input" type="text" name="ptr[]" value="${ptr.value
    }" readonly>
                        </td>
                        <td class="pt-3"id="row-${slControl}-col-12">
                            <input class="td-input" type="text" name="disc-percent[]" value="${discount.value
    }%" readonly>
                        </td>
                        <td class="ps-1 pt-3" id="row-${slControl}-col-13">
                            <input class="td-input" type="text" name="gst[]" value="${GST.value
    }%" readonly>
                        </td>
                        <td class="pt-3" id="row-${slControl}-col-14">
                            <input class="td-input" type="text" name="return-qty[]" value="${parseFloat(
      returnQty.value
    )}" readonly>
                        </td>
                        <td class="amnt-td pt-3" id="row-${slControl}-col-16">
                            <input class="td-input" type="text" name="refund-amount[]" value="${refundAmount.value
    }" readonly>
                        </td>
                    </tr>`;

  jQuery("#dataBody").append(dataRow);

  // add total items number
  itemsQty.value = slno;

  // add total quanatity of items
  TotalReturnQty.value =
    parseInt(TotalReturnQty.value) + parseInt(returnQty.value);

  // add total amount of gst
  TotalReturnGST.value = (
    parseFloat(TotalReturnGST.value) + parseFloat(gstAmount)
  ).toFixed(2);

  // add total refund
  TotalRefund.value = (
    parseFloat(TotalRefund.value) + parseFloat(refundAmount.value)
  ).toFixed(2);

  const tupleData = JSON.stringify({
    seletedItemDiv,
    slno: slControl,

    stokInDetailsId: stokInDetailsId.value,
    productId: productId.value,
    productName: productName.value,
    batchNumber: batchNumber.value,
    billNumber: billNumber.value,
    billDate: billDate.value,
    expDate: expDate.value,
    weatage: weatage.value,
    mrp: mrp.value,
    ptr: ptr.value,
    discount: discount.value,
    gst: GST.value,
    gstAmountPerQty: gstAmntPrQty.value,

    dprice: dprice.value,
    taxableOnPurchase: taxableOnPurchase.value,
    RtrnGstAmount: gstAmount,

    amount: amount.value,
    purchasedQty: purchasedQty.value,
    ntByQty: netBuyQty.value,
    freeQty: freeQty.value,
    currentQty: currentQty.value,
    returnQty: returnQty.value,
    refundAmount: refundAmount.value,
  });

  const columns = ["2", "4", "5", "6", "10", "11", "12", "13", "14", "16"];
  columns.forEach((col) => {
    document.getElementById(`row-${slControl}-col-${col}`).onclick =
      function () {
        editItem(tupleData, originalClickHandler);
      };
  });

  document.getElementById("stokInDetailsId").value = "";
  document.getElementById("product-id").value = "";
  document.getElementById("product-name").value = "";
  document.getElementById("stock-return-item-data").reset();
  event.preventDefault();
  disableOnClickFunction(seletedItemDiv);
  purchaseReturnSave.removeAttribute("disabled");
}

// ======= item onclick disable and enablel function ========
const disableOnClickFunction = (divId) => {
  let divElement = document.getElementById(divId);

  if (divElement) {
    divElement.onclick = null; // divElement.onclick = function() {};
  }
}; // eof item onclik disable

const divOnclikActive = (divId, handelerData) => {
  let divElement = document.getElementById(divId);

  if (divElement != null) {
    // restore onclick handeler data
    divElement.onclick = handelerData;
  }
};

// ================================ Delet Data ================================

const deleteData = (
  slno,
  itemQty,
  gstPerItem,
  refundPerItem,
  divId,
  handelerData
) => {
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
  let finalQty = qty.value - itemQty;
  qty.value = finalQty;

  // minus gst
  let gst = document.getElementById("return-gst-val");
  let finalGst = parseFloat(gst.value) - parseFloat(gstPerItem);
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

  divOnclikActive(divId, handelerData);

  chekForm();
};

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

const editItem = (tData, onClickData) => {
  if (document.getElementById("product-id").value == "") {
    var tData = JSON.parse(tData);

    document.getElementById("select-item-div").value = tData.seletedItemDiv;

    document.getElementById("stokInDetailsId").value = tData.stokInDetailsId;
    document.getElementById("bill-number").value = tData.billNumber;
    document.getElementById("batch-number").value = tData.batchNumber;
    document.getElementById("product-id").value = tData.productId;
    document.getElementById("product-name").value = tData.productName;
    document.getElementById("bill-date").value = tData.billDate;
    document.getElementById("exp-date").value = tData.expDate;

    document.getElementById("weatage").value = tData.weatage;
    document.getElementById("mrp").value = tData.mrp;
    document.getElementById("ptr").value = tData.ptr;
    document.getElementById("discount").value = tData.discount;
    document.getElementById("gst").value = tData.gst;
    document.getElementById("gstAmountPerQty").value = tData.gstAmountPerQty;

    document.getElementById("return-gst-amount").value = tData.RtrnGstAmount;
    document.getElementById("dprice").value = tData.dprice;
    document.getElementById("taxable").value = tData.taxableOnPurchase;

    document.getElementById("amount").value = tData.amount;
    document.getElementById("purchased-qty").value = tData.purchasedQty;
    document.getElementById("net-buy-qty").value = tData.ntByQty;
    document.getElementById("free-qty").value = tData.freeQty;
    document.getElementById("current-qty").value = tData.currentQty;
    document.getElementById("return-qty").value = tData.returnQty;
    // document.getElementById("return-free-qty").value = tData.returnFreeQty;
    document.getElementById("refund-amount").value = tData.refundAmount;

    let flag = 1;
    let itemQty = parseInt(tData.returnQty);
    deleteData(
      tData.slno,
      itemQty,
      tData.RtrnGstAmount,
      tData.refundAmount,
      tData.seletedItemDiv,
      onClickData
    );

    chekForm();
  } else {
    Swal.fire("Error", "Add or remove Previous data first.", "error");
  }
};
