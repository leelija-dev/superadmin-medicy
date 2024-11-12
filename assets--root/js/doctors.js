/*==================== ADD NEW DOCTOR ====================*/
const addDocDetails = () => {
  const fields = [
    "doc-name",
    "doc-reg-no",
    "doc-speclz-id",
    "doc-degree",
    "email",
    "doc-phno",
    "doc-address",
    "doc-with",
  ];
  const data = {};

  let isEmpty = false;
  var errMsg = "Mandatory Fields Must be Field!";

  fields.forEach((field) => {
    data[field] = document.getElementById(field).value.trim();
    if (
      !data[field] &&
      (field === "doc-name" ||
        field === "doc-reg-no" ||
        field === "doc-speclz-id" ||
        field === "doc-degree")
    ) {
      isEmpty = true;
    }
  });

  if (!data["doc-speclz-id"]) {
    Swal.fire("Failed", "Select Specialization From Dropdown!", "error");
    return;
  }

  if (data["email"]) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data["email"])) {
      Swal.fire("Failed", "Provide Correct Email Address!", "error");
      return;
    }
  }

  if (data["doc-phno"]) {
    if (data["doc-phno"].length != 10) {
      Swal.fire("Failed", "Enter 10 Digit Contact Number!", "error");
      return;
    }
  }

  if (isEmpty) {
    Swal.fire("Failed", errMsg, "info");
    return;
  }

  $.ajax({
    url: "ajax/doctors.new.data.add.ajax.php",
    type: "POST",
    data: {
      docName: data["doc-name"],
      docRegNo: data["doc-reg-no"],
      docSpecialization: data["doc-speclz-id"],
      docDegree: data["doc-degree"],
      docEmail: data["email"],
      docMob: data["doc-phno"],
      docAddress: data["doc-address"],
      docAlsoWith: data["doc-with"],
    },
    success: function (response) {

      try {
        // Assuming the response is JSON with doctor data
        const responseData = JSON.parse(response);
        if (responseData.success) {
          const doctorDetails = responseData.doctor;
          // if (response == 1) {
          Swal.fire({
            title: "Success",
            text: "Data added successfully.",
            icon: "success",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ok",
          }).then((result) => {
            // console.log('result--', result);
            if (result.isConfirmed) {
              // ===========this code for tes appointment edit ==========//
              // console.log("Added Doctor Details:", doctorDetails);
              const preferedDoc = document.getElementById('preferedDoc');
              const appointmentNewDocAdd = document.getElementById('appointDocAdd');
              // console.log('get appointmentNewDocAdd--',appointmentNewDocAdd);

              if (preferedDoc) {
                preferedDoc.innerHTML = doctorDetails.doctor_name;
                preferedDoc.value = doctorDetails.doctor_name;
                document.getElementById("prefferedDocId").value = doctorDetails.doctor_id;
                document.getElementById("refferedDocName").value = doctorDetails.doctor_name;

                const docList = document.getElementById("docList");
                const option = document.createElement("option");
                option.value = doctorDetails.doctor_id;
                option.text = doctorDetails.doctor_name;
                option.selected = true;
                docList.appendChild(option);
                //.........for enable the test selection tag.......//
                const testSelect = document.getElementById("test");
                if (testSelect.disabled) {
                  testSelect.disabled = false;
                }// ===========end tes appointment edit ==========//
              } else if (appointmentNewDocAdd) {
                //{===this code for add new appointment time new doctor add 
                const doctorOptions = document.getElementById("doctorOptions");
                const newOption = document.createElement("div");
                newOption.className = "dropdown-option";
                newOption.textContent = doctorDetails.doctor_name;
                // newOption.onclick = () => selectDoctor(doctorDetails.doctor_id, doctorDetails.doctor_name);
                doctorOptions.appendChild(newOption);
                //...Set the dropdown-selected to the new doctor...//
                document.querySelector("#docDropdown .dropdown-selected").textContent = doctorDetails.doctor_name;
                document.getElementById("patientDoctor").value = doctorDetails.doctor_id;
                //...after doctor add hide add modal...//
                $('#addDoctorDataModal').modal('hide').fadeOut();
                // addDoctorButton.style.display= 'none';
                //===}//
              }
              else {
                window.location.reload();
              }
              // window.location.reload();
            }
          });

          // Clear input fields after success
          fields.forEach((field) => {
            document.getElementById(field).value = "";
          });
        } else {
          Swal.fire("Success", "Unable to add data", "error");
        }
      } catch (error) {
        console.error("Error parsing response:", error);
        Swal.fire("Error", "Invalid response format", "error");
      }
    },
    error: function () {
      Swal.fire("Error", "Ajax request failed", "error");
    },
  });
};
//...this is for appointmentNewDocAdd selectDoctor function...//
// function selectDoctor(doctorId, doctorName) {
//   document.querySelector("#docDropdown .dropdown-selected").innerText = doctorName;
//   document.getElementById("patientDoctor").value = doctorId;
//   document.getElementById("doctorOptions").classList.remove("show");
// }
/*==================== EOF ADD NEW DOCTOR ====================*/

/*==================== DOCTOR DATA VIEW ====================*/
const docViewAndEdit = (docId) => {
  let url = "ajax/doctors.view.ajax.php?docId=" + docId;

  fetch(url)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok " + response.statusText);
      }
      return response.text();
    })
    .then((data) => {
      // Assuming there is an element with the class "docViewAndEditModal"
      let modalElement = document.querySelector(".docViewAndEditModal");
      if (modalElement) {
        modalElement.innerHTML = data;
      } else {
        Swal.fire("Error", "Modal element not found", "error");
      }
    })
    .catch((error) => {
      Swal.fire("Error", "Fetch operation error!", "error");
      console.error(
        "There has been a problem with your fetch operation:",
        error
      );
    });
};
/*==================== EOF DOCTOR DATA VIEW ====================*/

// delete doctor data ----------
$(document).ready(function () {
  $(document).on("click", ".delete-btn", function () {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!",
    }).then((result) => {
      if (result.isConfirmed) {
        docId = $(this).data("id");
        btn = this;
        // alert(btn);

        $.ajax({
          url: "ajax/doctors.delete.ajax.php",
          type: "POST",
          data: {
            id: docId,
          },
          success: function (data) {
            if (data == 1) {
              $(btn).closest("tr").fadeOut();
            } else {
              $("#error-message").html("Deletion Field !!!").slideDown();
              $("success-message").slideUp();
            }
          },
        });
      }
    });
  });
});

// ======== script for ductors data edit update form doctors.view.ajax.php ========

const editDoc = () => {
  const fields = [
    "u-doctor-id",
    "u-doctor-name",
    "u-doc-reg-no",
    "u-doc-speclz-id",
    "u-doc-degree",
    "u-doc-email",
    "u-doc-phno",
    "u-doc-address",
    "u-doc-with",
  ];

  const data = Object.fromEntries(fields.map(field => [field, document.getElementById(field).value.trim()]));

  // Check for mandatory fields
  const mandatoryFields = ["u-doctor-name", "u-doc-reg-no", "u-doc-speclz-id", "u-doc-degree"];

  const isEmpty = mandatoryFields.some(field => !data[field]);
  if (isEmpty) {
    Swal.fire("Failed", "Mandatory Fields Must be Field!", "info");
    return;
  }

  // Specialization validation
  if (!data["u-doc-speclz-id"]) {
    Swal.fire("Failed", "Select Specialization From Dropdown!", "error");
    return;
  }

  // Email validation
  if (data["u-doc-email"] && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data["u-doc-email"])) {
    Swal.fire("Failed", "Provide Correct Email Address!", "error");
    return;
  }

  // Phone number validation
  if (data["u-doc-phno"] && data["u-doc-phno"].length !== 10) {
    Swal.fire("Failed", "Enter 10 Digit Contact Number!", "error");
    return;
  }

  $.ajax({
    url: "ajax/doctors.edit.ajax.php",
    type: "POST",
    data: {
      docId: data["u-doctor-id"],
      docName: data["u-doctor-name"],
      docSpecialization: data["u-doc-speclz-id"],
      docRegNo: data["u-doc-reg-no"],
      docDegree: data["u-doc-degree"],
      docEmail: data["u-doc-email"],
      docPhno: data["u-doc-phno"],
      docAddress: data["u-doc-address"],
      docAlsoWith: data["u-doc-with"],
    },
    success: function (response) {
      const message = response == 1 ? ["Success", "Updated Successfully!", "success"] : ["Failed", "Something is Wrong!", "error"];
      Swal.fire(...message);
      if (response != 1) console.error(response);
    },
  });
}



// ===================================================
// for open doctor add modal 

const openDocModal = () => {
  fetch("components/doctor-add-modal.php", {
    method: "POST"
  })
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok ' + response.statusText);
      }
      return response.text();
    })
    .then(data => {
      let body = document.querySelector('#new-doctor-modal');
      body.innerHTML = data;
      // console.log(data);

    })
    .catch(error => {
      // console.error("Error: ", error);
      alert(error);
    });

};

// =======================================================================================
// //////////////////// set specialization /////////////////////

const observer = new MutationObserver((mutationsList, observer) => {
  // Loop through the mutations
  for (let mutation of mutationsList) {
    if (mutation.type === 'childList') {
      // Check if the element is now present in the DOM
      const docSpecializationId = document.getElementById("doc-speclz-id");
      const docSpecializationInput = document.getElementById("doc-speclz");
      const dropdown = document.getElementsByClassName("c-dropdown")[0];


      if (docSpecializationInput && docSpecializationId && dropdown) {

        docSpecializationInput.addEventListener("focus", () => {
          dropdown.style.display = "block";

          let list = document.getElementsByClassName("lists")[0];
          var reqUrl = `ajax/doc-specialization-list-view.ajax.php?match=*`;
          request.open("GET", reqUrl, false);
          request.send(null);
          list.innerHTML = request.responseText;
        });
        // Stop observing once the element is found and event is attached
        // observer.disconnect();
        // }

        document.addEventListener("click", (event) => {
          if (
            !docSpecializationInput.contains(event.target) &&
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


        docSpecializationInput.addEventListener("keyup", () => {
          let list = document.getElementsByClassName("lists")[0];
          docSpecializationId.value = "";

          if (docSpecializationInput.value.length > 2) {
            var reqUrl = `ajax/doc-specialization-list-view.ajax.php?match=${docSpecializationInput.value}`;
            request.open("GET", reqUrl, false);
            request.send(null);
            list.innerHTML = request.responseText;
          } else {
            var reqUrl = `ajax/doc-specialization-list-view.ajax.php?match=*`;
            request.open("GET", reqUrl, false);
            request.send(null);
            list.innerHTML = request.responseText;
          }
          // console.log(reqUrl);
          // console.log("check return : "+request.responseText);
        });

      }


    }
  }
});

const setDocSpecialization = (t) => {
  let specializationId = t.id.trim();
  let specializationName = t.innerHTML.trim();

  document.getElementById("doc-speclz-id").value = specializationId;
  document.getElementById("doc-speclz").value = specializationName;
  // document.getElementById("distributor-id").value = distributirName;

  document.getElementsByClassName("c-dropdown")[0].style.display = "none";
};

// Start observing the document for changes
observer.observe(document.body, {
  childList: true,
  subtree: true
});




// //////////////////// set specialization /////////////////////
// const docSpecializationId = document.getElementById("doc-speclz-id");
// const docSpecializationInput = document.getElementById("doc-speclz");
// const dropdown = document.getElementsByClassName("c-dropdown")[0];
// console.log('docSpecializationInput-', docSpecializationInput);
// console.log('dropdown', dropdown);

// console.log(docSpecializationInput);

// if(docSpecializationInput  && dropdown){
// docSpecializationInput.addEventListener("focus", () => {
//   dropdown.style.display = "block";
// });

// document.addEventListener("click", (event) => {
//   if (
//     !docSpecializationInput.contains(event.target) &&
//     !dropdown.contains(event.target)
//   ) {
//     dropdown.style.display = "none";
//   }
// });

// document.addEventListener("blur", (event) => {
//   if (!dropdown.contains(event.relatedTarget)) {
//     setTimeout(() => {
//       dropdown.style.display = "none";
//     }, 100);
//   }
// });

// docSpecializationInput.addEventListener("keyup", () => {
//   let list = document.getElementsByClassName("lists")[0];
//   docSpecializationId.value = "";

//   if (docSpecializationInput.value.length > 2) {
//     var reqUrl = `ajax/doc-specialization-list-view.ajax.php?match=${docSpecializationInput.value}`;
//     request.open("GET", reqUrl, false);
//     request.send(null);
//     list.innerHTML = request.responseText;
//   } else {
//     var reqUrl = `ajax/doc-specialization-list-view.ajax.php?match=*`;
//     request.open("GET", reqUrl, false);
//     request.send(null);
//     list.innerHTML = request.responseText;
//   }
//   // console.log(reqUrl);
//   // console.log("check return : "+request.responseText);
// });
// }
// const setDocSpecialization = (t) => {
//   console.log(t);

//   let specializationId = t.id.trim();
//   let specializationName = t.innerHTML.trim();

//   document.getElementById("doc-speclz-id").value = specializationId;
//   document.getElementById("doc-speclz").value = specializationName;
//   // document.getElementById("distributor-id").value = distributirName;

//   document.getElementsByClassName("c-dropdown")[0].style.display = "none";
// };