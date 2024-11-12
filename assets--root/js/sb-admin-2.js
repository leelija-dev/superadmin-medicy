const xmlhttp = new XMLHttpRequest(); // use this universe constant for xmlObject handeling

const masterUrl = document.getElementById('master-url');

const customDateRangeFlag = document.getElementById('date-range-control-flag');
const urlControlFlag = document.getElementById('url-control-flag');

// date picker control
const dateStartPicker = document.getElementById('from-date');
const dateEndPicker = document.getElementById('to-date');

const currentDate = new Date();
const formattedCurrentDate = currentDate.toISOString().split('T')[0];

currentDate.setDate(currentDate.getDate() + 10);
const formattedMaxDate = currentDate.toISOString().split('T')[0];


if (dateStartPicker != null || dateEndPicker != null) {
  dateStartPicker.setAttribute('max', formattedCurrentDate);
  dateEndPicker.setAttribute('max', formattedCurrentDate);
}

function pickAppintmentDate(){
  const appointmentDatePicker = document.getElementById('appointmentDate');
  appointmentDatePicker.setAttribute('min',formattedCurrentDate)
  appointmentDatePicker.setAttribute('max',formattedMaxDate);
}


window.history.forward();
function noBack() {
  window.history.forward();
}


(function($) {
  "use strict"; // Start of use strict

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    }
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    }

    // Ensure sidebar remains open when resizing below 480px
    if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
      $("body").addClass("sidebar-toggled");
      $(".sidebar").addClass("toggled");
    }
  });

  // Remove collapsing behavior on scroll for mobile
  $('body.fixed-nav .sidebar').off('mousewheel DOMMouseScroll wheel'); 

  // Scroll to top button appear
  $(document).on('scroll', function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });

})(jQuery);



// ###################......for all page input form validation.....################//

const returningPatient = document.getElementById("patientName");
const returningPatientFindBtn = document.getElementById("proceed-returning-patient");

if (returningPatient && returningPatientFindBtn != null) {
  if (returningPatient.value == "") {
    returningPatientFindBtn.disabled = true;
  }
}

document.addEventListener("DOMContentLoaded", function () {

  const form = document.querySelector("#patientForm");

  if (form) {
    form.addEventListener("submit", function (e) {
      let isFormValid = true;

      // Get all required input fields
      const requiredFields = document.querySelectorAll(".med-input[required]");

      // Loop through each field and check if it's empty
      requiredFields.forEach(function (field) {
        const errorMsg = field.parentElement.querySelector("p");

        // Check if the field is empty
        if (field.value.trim() === "") {
          isFormValid = false;
          if (errorMsg) {
            errorMsg.innerHTML = "This field is required.";
          } else {
            let newErrorMsg = document.createElement("p");
            newErrorMsg.style.color = "red";
            newErrorMsg.innerHTML = "This field is required.";
            field.parentElement.appendChild(newErrorMsg);
          }
        } else {
          if (errorMsg) {
            errorMsg.innerHTML = ""; // Clear the error message
          }
        }

        // Add input event listener to clear error message when typing
        field.addEventListener("input", function () {
          const dynamicErrorMsg = field.parentElement.querySelector("p");
          if (dynamicErrorMsg) {
            dynamicErrorMsg.innerHTML = ""; // Clear error message as the user types
          }
        });
      });

      // Prevent form submission if validation fails
      if (!isFormValid) {
        e.preventDefault();
      }
    });
  }
});

// Check email validation
const checkMail = (t) => {
  const email = t.value;
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const errorMsg = document.getElementById("emailMsg");

  if (email.length === 0 || email.length === "") {
    errorMsg.innerHTML = "";
    return;
  }
  if (!emailRegex.test(email)) {
    errorMsg.innerHTML = "Please enter a valid email address (e.g., user@example.com).";
  } else {
    errorMsg.innerHTML = "";
  }
};

// Check contact number validation
const checkContactNo = (t) => {
  const mob = document.getElementById("patientPhoneNumber").value;
  const regex = /^[0-9]{10}$/;
  const errorMsg = document.getElementById("pMsg");

  if (mob.length === 0 || mob.length === "") {
    errorMsg.innerHTML = "Phone number is required.";
    return;
  }

  if (!regex.test(mob)) {
    errorMsg.innerHTML = "Please provide a valid 10-digit mobile number.";
  } else {
    errorMsg.innerHTML = "";
  }
};


const checkWeight = (t) => {
  const errorMsg = document.getElementById("wghtMsg");
  const weight = t.value;

  if (weight.length === 0 || weight.length === "") {
    errorMsg.innerHTML = "";
    return;
  }
  if (weight.length > 3 || weight <= 0) {
    errorMsg.innerHTML = "Enter a valid weight (1-999 kg)";
  } else {
    errorMsg.innerHTML = "";
  }
};


const checkAge = (t) => {
  const errorMsg = document.getElementById("ageMsg");

  if (t.value.length === 0 || t.value.length === "") {
    errorMsg.innerHTML = "Age is required.";
    return;
  }
  if (t.value.length > 3 || t.value <= 0) {
    errorMsg.innerHTML = "Enter a valid age";
  } else {
    errorMsg.innerHTML = "";
  }
};

// Check PIN code validity
const checkPin = (t) => {
  const errorMsg = document.getElementById("pinMsg");

  if (t.value.length === 0 || t.value.length === "") {
    errorMsg.innerHTML = "Area pin is required.";
    return;
  }
  if (t.value.length !== 6) {
    errorMsg.innerHTML = "Enter a valid 6-digit PIN number.";
  } else {
    errorMsg.innerHTML = "";
  }
};
// ###################......End all page input form validation.....################//


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


///................for close date and date picker range .................///
function closePicker(currentId, selectIndex) {
  document.getElementById(currentId).style.display = 'none';
  if (selectIndex) {
    document.getElementById(selectIndex).selectedIndex = 0;
  }

  if (customDateRangeFlag != null) {
    customDateRangeFlag.value = '0';
  }

  if (urlControlFlag != null) {
    urlControlFlag.value = '0';
  }
}


// alert fucntion for dynamic table no data found
function alertFunction(){
  Swal.fire('Error','No data Found','error');
}



// date convertion function  (output yyyy-mm-dd)
// convert date format
function convertDateFormat(dateStr, toFormat) {
  const [day, month, year] = dateStr.split('-');
  return toFormat === 'YYYY-MM-DD' ? `${year}-${month}-${day}` : `${day}/${month}/${year}`;
}

/// topbar all search function
function searchFor(){

  let searchForData = document.getElementById("search-all");
  let searchResult = document.getElementById('searchAll-list');

  if (searchForData.value == "") {
      searchResult.style.display = "none";
      return;
  }

  if (searchForData.value.length > 1) {
      let searchAllUrl = `ajax/search-for-all.ajax.php?searchKey=${searchForData.value}`;

      request.onreadystatechange = function () {
          if (request.readyState == 4 && request.status == 200) {
              searchResult.innerHTML = request.responseText;
              searchResult.style.display = "block";
          }
      };

      request.open("GET", searchAllUrl, true);
      request.send();
  }
  // console.log(request.responseText);
}

function getDtls(key, id) {
  if (typeof masterUrl === 'undefined' || !masterUrl.value) {
      console.error("masterUrl is undefined or empty.");
      return;
  }

  let path = '';
  let searchParam = '';
  
  switch (key) {
      case 'appointments':
          path = 'appointments.php';
          searchParam = 'appointment_search';
          break;

      case 'patient_details':
          path = 'patients.php';
          searchParam = 'search-by-id-name';
          break;

      case 'lab_billing':
          path = 'test-appointments.php';
          searchParam = 'appointment-search';
          break;

      case 'stock_in':
          path = 'stock-in-details.php';
          searchParam = 'stock_in';
          break;

      case 'stock_out':
          path = 'stock-in-details.php';
          searchParam = 'stock_out';
          break;

      default:
          console.error("Invalid key provided: " + key);
          return;
  }


  if (path && searchParam) {
      const url = `${masterUrl.value}${path}?search=${searchParam}&searchKey=${id}`;
      window.location.href = url;
  }
}
