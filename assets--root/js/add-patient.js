const xmlhttp = new XMLHttpRequest();

const returningPatient = document.getElementById("patientName");
const returningPatientFindBtn = document.getElementById(
  "proceed-returning-patient"
);

if (returningPatient.value == "") {
  returningPatientFindBtn.disabled = true;
}

// check mail validity
const checkMail = (t) => {
  let email = t.value;

  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    // checkMailExistance(email);
    document.getElementById("emailMsg").innerHTML = "Enter valid email id.";
  } else {
    document.getElementById("emailMsg").innerHTML = "";
    // Swal.fire({
    //     title: "Alert",
    //     text: "Enter valid email id.",
    //     icon: "warning",
    //     confirmButtonColor: "#3085d6",
    //     confirmButtonText: "Ok"
    //   }).then((result) => {
    //     if (result.isConfirmed) {
    //         t.value = '';
    //         t.focus();
    //     }
    //   });
  }
};

// check mail existance
const checkMailExistance = (t) => {
  let checkEmail = t;
  // console.log(t);
  $.ajax({
    url: "ajax/mobile-email-existance-check.ajax.php",
    type: "POST",
    data: {
      email: checkEmail,
    },
    success: function (data) {
      if (data == 1) {
        Swal.fire({
          title: "Alert",
          text: "Email exist.",
          icon: "warning",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Ok",
        }).then((result) => {
          if (result.isConfirmed) {
            document.getElementById("email").value = "";
            document.getElementById("email").focus();
          }
        });
      }
    },
  });
};

// mobile verification and validation =======

const checkContactNo = (t) => {
  // if (t.value.length != 10) {
  //     Swal.fire({
  //         title: "Alert",
  //         text: "Mobile number must be 10 digits.",
  //         icon: "warning",
  //         confirmButtonColor: "#3085d6",
  //         confirmButtonText: "Ok"
  //       }).then((result) => {
  //         if (result.isConfirmed) {
  //             t.value='';
  //             t.focus();
  //         }
  //       });
  // }
  var mob = document.getElementById("patientPhoneNumber").value;
  var regex = /^[0-9]{10}$/;

  if (!regex.test(mob)) {
    document.getElementById("pMsg").innerHTML =
      "Please provide a valid 10-digit mobile number.";
    // Swal.fire({
    //     title:"Alert",
    //     text: "Please provide a valid 10-digit mobile number.",
    //     icon: "warning",
    //     confirmButtonColor: "#3085d6",
    //     confirmButtonText: "Ok"
    //   }).then((result) => {
    //     if (result.isConfirmed) {
    //         t.value='';
    //         t.focus();
    //     }
    //   });
  } else {
    document.getElementById("pMsg").innerHTML = "";
  }
  // else {
  //     let contactNo = t.value;
  //     $.ajax({
  //         url: 'ajax/mobile-email-existance-check.ajax.php',
  //         type: 'POST',
  //         data: {
  //             checkMobNo: contactNo,
  //         },
  //         success: function (data) {
  //             // alert(data);
  //             if (data == 1) {
  //                 Swal.fire({
  //                     title: "Alert",
  //                     text: "Mobile number exist.",
  //                     icon: "warning",
  //                     confirmButtonColor: "#3085d6",
  //                     confirmButtonText: "Ok"
  //                   }).then((result) => {
  //                     if (result.isConfirmed) {
  //                         t.value='';
  //                         t.focus();
  //                     }
  //                   });
  //             }
  //         },
  //     });
  // }
};

// patient weight validity
const checkWeight = (t) => {
  if (t.value.length > 3) {
    Swal.fire("Error", "Enter valid weight", "error");
    t.value = "";
  }
};

// patient age validity
const checkAge = (t) => {
  if (t.value.length > 3) {
    document.getElementById("ageMsg").innerHTML = "Enter valid age";
    // Swal.fire('Error','Enter valid age','error');
    // t.value = '';
  } else {
    document.getElementById("ageMsg").innerHTML = "";
  }
};

// pin validity check
const checkPin = (t) => {
  if (t.value.length != 6 && t.value.length < 6) {
    document.getElementById("pinMsg").innerHTML =
      "Enter valid PIN number(maximum 6 digit).";
    // Swal.fire('Error','Enter valid PIN number(maximum 6 digit).','error');
    // t.value = '';
  } else {
    document.getElementById("pinMsg").innerHTML = "";
  }
};

// ==================================================================

const getPatient = (patient) => {
  // console.log('patient name set : ',document.getElementById('patientName').value);

  if (patient.length > 0) {
    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById("patient-list").style.display = "block";
        document.getElementById("patient-list").innerHTML =
          xmlhttp.responseText;
      }
    };
    xmlhttp.open("GET", `ajax/patientSearch.ajax.php?data=${patient}`, true);
    xmlhttp.send();
  } else {
    document.getElementById("patient-list").style.display = "none";
  }
};

const setPatient = (id) => {
  // ================ get Name ================
  stockCheckUrl = "ajax/patient.getDetails.ajax.php?name=" + id;
  xmlhttp.open("GET", stockCheckUrl, false);
  xmlhttp.send(null);
  // document.getElementById("patientId").value = xmlhttp.responseText;
  returningPatient.value = xmlhttp.responseText + " - " + id;
  document.getElementById("patientId").value = id;
  document.getElementById("patient-list").style.display = "none";

  returningPatientFindBtn.disabled = false;
};
