/* ##############################################
This Javascript Page is only For Lab Billing Page
###############################################*/

//fetching doctor name  using ajax
function getDoc() {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open("GET", "ajax/billingDoc.ajax.php?doctor_id=" + document.getElementById("docList").value, false);

  xmlhttp.send(null);
  document.getElementById("preferedDoc").innerHTML = xmlhttp.responseText;
  var doc = document.getElementById("docList").value;


  let testDropDown = document.getElementById("test");
  let addBillBtn = document.getElementById("add-bill-btn");


  if (doc == "Self") {
    document.getElementById("preferedDoc").innerHTML = doc;
  }

  if (doc == "") {
    document.getElementById("prefferedDocId").value = "";
    document.getElementById("docName").removeAttribute("disabled", true);
    testDropDown.disabled = true;
    // alert(doc);
    if(testDropDown.value == 'Select Test' || doc == ''){
      addBillBtn.disabled = true;
    }else{
      addBillBtn.disabled = false;
    }

  } else {
    document.getElementById("prefferedDocId").value = doc;
    document.getElementById("docName").setAttribute("disabled", true);
    testDropDown.disabled = false;
    // alert(doc);
    if(testDropDown.value == 'Select Test' || doc == ''){
      addBillBtn.disabled = true;
    }else{
      addBillBtn.disabled = false;
    }

  }
}

// action for entering new doctor name
function newDoctor(value) {
  let testDropDown = document.getElementById("test");
  if (value == "") {
    // alert("Null");
    document.getElementById("refferedDocName").value = "";
    document.getElementById("preferedDoc").innerHTML = "";

    document.getElementById("docList").removeAttribute("disabled", true);
    testDropDown.disabled = true;
    document.getElementById("add-bill-btn").disabled = true;

  } else {
    // alert("Not Null");
    document.getElementById("preferedDoc").innerHTML = "Dr. " + value;
    document.getElementById("refferedDocName").value = "Dr. " + value;
    document.getElementById("docList").setAttribute("disabled", true);
    testDropDown.disabled = false;

    document.getElementById("add-bill-btn").disabled = false;


    // alert(value);
  }
}

//fetching test price price using ajax
function getPrice() {
  //Geeting Price of the selected test
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open(
    "GET",
    "ajax/billingTestPrice.ajax.php?subtest_id=" +
      document.getElementById("test").value,
    false
  );
  xmlhttp.send(null);
  document.getElementById("price").innerHTML = xmlhttp.responseText;

  let price = parseFloat(xmlhttp.responseText);
  let disc = document.getElementById("disc").value;
  let total = price - (disc / 100) * price;

  document.getElementById("total").innerHTML = total;

  //Geeting Name of the selected test into a input field
  var xmlhttpName = new XMLHttpRequest();
  xmlhttpName.open(
    "GET",
    "ajax/billingTestName.ajax.php?subtest_id=" +
      document.getElementById("test").value,
    false
  );
  xmlhttpName.send(null);
  document.getElementById("test-name").value = xmlhttpName.responseText;

  //Removing disabled attribute from quantity and add bil button after selecting a test name
  // document.getElementById("qty").removeAttribute("disabled");
  document.getElementById("disc").removeAttribute("disabled");

  var btn = document.getElementById("add-bill-btn");
  btn.removeAttribute("disabled");

  //Geeting id of the selected test into a input field
  var test_id = document.getElementById("test").value;
  document.getElementById("test-id").value = test_id;
}
 
//geeting bills by clicking on add button
function getBill() {
  var testName = document.getElementById("test-name").value;
  var testId = document.getElementById("test-id").value;
  var testPrice = document.getElementById("price").innerHTML;
  var disc = document.getElementById("disc").value;
  if (disc == "") {
    disc = 00;
  }
  var total = parseFloat(document.getElementById("total").innerHTML);

  //dynamic id generation
  var count = document.getElementById("dynamic-id").value;
  count++;
  document.getElementById("dynamic-id").value = count;
  // alert(count);

  jQuery("#lists").append(
    '<div id="box-id-' +
      count +
      '" class="row justify-content-between text-left my-0 py-0"><div class="form-group col-sm-2 flex-column my-0 py-0 d-flex"><p class="my-0 py-0">' +
      count +
      '</p></div><div class="form-group col-sm-3 flex-column mb-0 mt-0 d-flex"><p class="my-0 py-0 ">' +
      testName +
      '</p><input type="text" name="testId[]" value="' +
      testId +
      '" hidden></div><div class="form-group col-sm-2 flex-column my-0 py-0 d-flex"><p class="my-0 py-0 ">' +
      testPrice +
      '</p><input type="text" name="priceOfTest[]" value="' +
      testPrice +
      '" hidden></div><div class="form-group col-sm-2 flex-column mb-0 mt-0 d-flex"><p class="my-0 py-0 ">' +
      disc +
      '</p><input type="text" name="disc[]" value="' +
      disc +
      '" hidden></div><div class="form-group col-sm-2 flex-column mb-0 mt-0 d-flex"><p class="my-0 py-0 text-end">' +
      total +
      '</p><input type="text" name="amountOfTest[]" value="' +
      total +
      '" hidden></div><div class="form-group col-sm-1 flex-column my-0 py-0 d-flex"><a class="my-0 py-0 text-end" onClick="removeField(' +
      count +
      "," +
      total +
      ')"><i class="far fa-trash-alt"></i></a></div></div>'
  );

  //calculating total tests price
  var payable = parseFloat(document.getElementById("payable").value);
  var totalPValue = parseFloat(document.getElementById("total-test-price").value);
  // alert(totalPValue);

  payable = payable + total;
  document.getElementById("payable").value = payable;

  totalPValue = totalPValue + total;
  let totalView = (document.getElementById("total-test-price").value =
    totalPValue);
  document.getElementById("total-view").innerHTML = totalView;

  //update status
  let update = document.getElementById("update").value;
  if (update == "Completed") {
    let payable = document.getElementById("payable").value;
    document.getElementById("paid-amount").value = payable;
    document.getElementById("due").value = 0;
  }

  if (update == "Credit") {
    let payable = document.getElementById("payable").value;
    document.getElementById("due").value = payable;
    document.getElementById("paid-amount").value = 00;
  }
  document.getElementById("bill-generate").disabled = false;
}

function removeField(count, total) {
  jQuery("#box-id-" + count).remove();
  count--;
  document.getElementById("dynamic-id").value = count;

  let totalP = document.getElementById("total-test-price").value - total;
  let payable = document.getElementById("payable").value - total;

  let totalView = document.getElementById("total-test-price").value = totalP;
  document.getElementById("total-view").innerHTML = totalView;

  let getPayable = document.getElementById("payable").value = payable;

  //update status
  let update = document.getElementById("update").value;
  if (update == "Completed") {
    document.getElementById("paid-amount").value = getPayable;
    document.getElementById("due").value = '00';
  }else if(update == "Credit"){
    document.getElementById("due").value = getPayable;
    document.getElementById("paid-amount").value = '00';

  }else{
    document.getElementById("paid-amount").value = '';
    document.getElementById("due").value = '';
  }

  //update field if no test avilable
  if (totalP == "" || totalP <= 0) {
    document.getElementById("payable").value = '';
    document.getElementById("due").value = '';
    document.getElementById("paid-amount").value = '';
    document.getElementById("less-amount").value = '';
    document.getElementById("bill-generate").disabled = true;
  }
}

//changes after changing on discount
getDisc = (value) => {
  let disc = value;
  let price = document.getElementById("price").innerHTML;
  // let qty = document.getElementById("qty").value;
  // let total = price*qty;
  let total = price - (disc / 100) * price;
  document.getElementById("total").innerHTML = total;
};

getLessAmount = (payable) => {
  let totalAmount = parseFloat(document.getElementById("total-test-price").value);
  let lessAmount  = parseFloat(document.getElementById("less-amount").value);
      payable     = parseFloat(payable);
  // alert(totalAmount);
  // alert(payable);
  // if (payable <= totalAmount){
  //   alert('payable is less or equal');
  // }else{
  //   alert('payable is grater or not equal');
  // }
  if (payable < totalAmount || payable == totalAmount) {
    lessAmount = totalAmount - payable;
    document.getElementById("less-amount").value = lessAmount;

    //update status
    let update = document.getElementById("update").value;
    if (update == "Completed") {
      let payable = document.getElementById("payable").value;
      document.getElementById("paid-amount").value = payable;
    }else if(update == "Partial Due"){
      document.getElementById("due").value = '';
      document.getElementById("paid-amount").value = '';
    }else{
      document.getElementById("due").value = '';
      document.getElementById("paid-amount").value = '';
    }

  } else if(payable > totalAmount){
    alert("Entered Value is Greterthan Total.");
    document.getElementById("payable").value = totalAmount;
    document.getElementById("less-amount").value = "";
  }else{
    document.getElementById("less-amount").value = "";
  }
};

updateBill = (value) => {
  // alert(update);
  if (value == "Completed") {
    let payable = parseFloat(document.getElementById("payable").value);
    document.getElementById("paid-amount").value = payable;
    document.getElementById("paid-amount").setAttribute( "readonly", true );

    document.getElementById("due").value = "00";
    // document.getElementById("less-amount").value = "00";
    document.getElementById("due").setAttribute( "readonly", true );
  }

  if (value == "Credit") {
    let payable = document.getElementById("payable").value;
    document.getElementById("due").value = payable;
    document.getElementById("due").setAttribute( "readonly", true );;

    document.getElementById("paid-amount").value = "00";
    document.getElementById("paid-amount").setAttribute( "readonly", true );;
  }

  if (value == "Partial Due") {
    // let payable = document.getElementById("payable").value;
    document.getElementById("due").value = "00";
    document.getElementById("due").removeAttribute( "readonly", true );

    let paidField = document.getElementById("paid-amount");
    paidField.value = "";
    // paidField.readonly = false;
    paidField.removeAttribute( "readonly", true );
    paidField.focus();
  }
};

const dueAmount = (dueAmount) =>{
  let payable    = parseFloat(document.getElementById("payable").value);
  let paidAmount = parseFloat(document.getElementById("paid-amount").value = 0);
      dueAmount  = parseFloat(dueAmount);

  if(dueAmount < payable || dueAmount == payable){
    document.getElementById("paid-amount").value = payable - dueAmount;
  }
  else if(dueAmount > payable) {
    alert("Due Amount can not be more than Payable Amount");
    paidAmount.value = '';
    document.getElementById("due").value = "";

  }else{
    alert("Can not be blank");
    paidAmount.value = '';
    document.getElementById("due").value = "";
  }

}

const paidAmount = (paidAmount) => {
  let payable = parseFloat(document.getElementById("payable").value);
  let due = document.getElementById("due");
      paidAmount = parseFloat(paidAmount);

  if (paidAmount <= payable) {
    // let dueAmount = payable - paidAmount;

    due.value = payable - paidAmount;
  }
  else if(paidAmount > payable){
    alert("Paid Amount can not be more than Payable Amount");
    document.getElementById("paid-amount").value = '';
    due.value = '';
  }else{
    document.getElementById("paid-amount").value = '';
    due.value = '';
  }
};