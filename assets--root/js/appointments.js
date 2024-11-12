// ======= custom script area =======

// $(document).ready(function () {
//   $(document).on("click", "#delete-btn", function () {
//     if (confirm("Are You Sure?")) {
//       apntID = $(this).data("id");
//       // console.log();
      
//       btn = this;

//       $.ajax({
//         url: "ajax/appointment.delete.ajax.php",
//         type: "POST",
//         data: {
//           id: apntID,
//         },
//         success: function (data) {
//           if (data == 1) {
//             $(btn).closest("tr").fadeOut();
//           } else {
//             $("#error-message").html("Deletion Field !!!").slideDown();
//             $("success-message").slideUp();
//           }
//         },
//       });
//     }
//     return false;
//   });
// });

// const deleteAppointment = (id) =>{
 
//   let apntID = id;
//   console.log('get id',apntID);
//   $.ajax({
//             url: "ajax/appointment.delete.ajax.php",
//             type: "POST",
//             data: {
//               id: apntID,
//             },
//             success: function (data) {
//               console.log(data);
              
//               // if(data == 1){
//                 Swal.fire({
//                   title: "Do you want Delete the appointment?",
//                   showDenyButton: true,
//                   showCancelButton: true,
//                   confirmButtonText: "Delete",
//                   denyButtonText: `Don't save`
//                 }).then((data) => {
//                   /* Read more about isConfirmed, isDenied below */
//                   if (data.isConfirmed) {
//                     Swal.fire("Delete!", "", "success");
//                   } else if (data.isDenied) {
//                     Swal.fire("Changes are not delete", "", "info");
//                   }
//                 });
//               // }
//             },
//           });
  
// }

const deleteAppointment = (id) => {
  let apntID = id;
  
  Swal.fire({
    title: "Do you want to delete the appointment ?",
    // showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: "Delete",
    cancelButtonText: "Cancel",
    // denyButtonText: `Don't delete`,
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "ajax/appointment.delete.ajax.php",
        type: "POST",
        data: {
          id: apntID,
        },
        success: function (response) {
          console.log(response);
          if (response == 1) {
            $(`#row-${apntID}`).fadeOut(800, function () {
              $(this).remove(); // Remove the row after fading out
            });
            Swal.fire("Deleted!", "The appointment has been deleted.", "success");
          } else {
            Swal.fire("Error!", response, "error");
          }
        },
        error: function (xhr, status, error) {
          Swal.fire("Error!", "An error occurred: " + error, "error");
        },
      });
    } 
  });
};


// =======================================================
appointmentViewAndEditModal = (appointmentTableID) => {
  let url =
    "ajax/appointment.view.ajax.php?appointmentTableID=" + appointmentTableID;
  $(".view-and-edit-appointments").html(
    '<iframe width="99%" height="440px" frameborder="0" allowtransparency="true" src="' +
      url +
      '"></iframe>'
  );
}; // end of LabCategoryEditModal function


// for select doctor dropdown 
function toggleDropdown() {
  document.getElementById("doctorOptions").classList.toggle("show");
}

function selectDoctor(doctorId, doctorName) {
  document.getElementById("docDropdown").querySelector('.dropdown-selected').innerText = doctorName;
  document.getElementById("patientDoctor").value = doctorId;
  document.getElementById("doctorOptions").classList.remove("show");
}

// Close dropdown if clicked outside
window.onclick = function(event) {
  // if (!event.target.closest("#docDropdown")) {
  //     document.getElementById("doctorOptions").classList.remove("show");
  // }
  const doctorOptions = document.getElementById("doctorOptions");
  if (doctorOptions && !event.target.closest("#docDropdown")) {
      doctorOptions.classList.remove("show");
  }
}