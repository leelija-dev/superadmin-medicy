const goback = () => {
  window.history.back();
};

function getRandomColor() {
  const getRandomNumber = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

  const r = getRandomNumber(0, 255);
  const g = getRandomNumber(0, 255);
  const b = getRandomNumber(0, 255);

  return `rgba(${r}, ${g}, ${b}, 0.7)`;
}
/*==================================================================
    [ Focus input ]*/
const inputElements = document.querySelectorAll(".input100");
inputElements.forEach((input) => {
  input.addEventListener("blur", function () {
    if (this.value.trim() !== "") {
      this.classList.add("has-val");
    } else {
      this.classList.remove("has-val");
    }
  });
});

/*==================================================================
    [ Validate ]*/
// const validateInputElements = document.querySelectorAll(
//   ".validate-input .input100"
// );
// const validateForm = document.querySelector(".validate-form");

// validateForm.addEventListener("submit", function (event) {
//   let check = true;

//   validateInputElements.forEach((input) => {
//     if (!validate(input)) {
//       showValidate(input);
//       check = false;
//     }
//   });

//   if (!check) {
//     event.preventDefault();
//   }
// });

// validateInputElements.forEach((input) => {
//   input.addEventListener("focus", function () {
//     hideValidate(this);
//   });
// });

function validate(input) {
  if (input.type === "email" || input.name === "email") {
    const emailPattern =
      /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/;
    if (!emailPattern.test(input.value.trim())) {
      return false;
    }
  } else {
    if (input.value.trim() === "") {
      return false;
    }
  }
  return true;
}

function showValidate(input) {
  const thisAlert = input.parentElement;
  thisAlert.classList.add("alert-validate");
}

function hideValidate(input) {
  const thisAlert = input.parentElement;
  thisAlert.classList.remove("alert-validate");
}

/*==================================================================
    [ Show pass ]*/
let showPass = 0;
const showPassButtons = document.querySelectorAll(".btn-show-pass");

showPassButtons.forEach((button) => {
  button.addEventListener("click", function () {
    const input = this.nextElementSibling;
    if (showPass === 0) {
      input.type = "text";
      this.classList.add("active");
      showPass = 1;
    } else {
      input.type = "password";
      this.classList.remove("active");
      showPass = 0;
    }
  });
});

// (function ($) {
//     "use strict";

//     const goback = () =>{
//         window.history.back();
//     }

//     /*==================================================================
//     [ Focus input ]*/
//     $('.input100').each(function(){
//         $(this).on('blur', function(){
//             if($(this).val().trim() != "") {
//                 $(this).addClass('has-val');
//             }
//             else {
//                 $(this).removeClass('has-val');
//             }
//         })
//     })

//     /*==================================================================
//     [ Validate ]*/
//     var input = $('.validate-input .input100');

//     $('.validate-form').on('submit',function(){
//         var check = true;

//         for(var i=0; i<input.length; i++) {
//             if(validate(input[i]) == false){
//                 showValidate(input[i]);
//                 check=false;
//             }
//         }

//         return check;
//     });

//     $('.validate-form .input100').each(function(){
//         $(this).focus(function(){
//            hideValidate(this);
//         });
//     });

//     function validate (input) {
//         if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
//             if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
//                 return false;
//             }
//         }
//         else {
//             if($(input).val().trim() == ''){
//                 return false;
//             }
//         }
//     }

//     function showValidate(input) {
//         var thisAlert = $(input).parent();

//         $(thisAlert).addClass('alert-validate');
//     }

//     function hideValidate(input) {
//         var thisAlert = $(input).parent();

//         $(thisAlert).removeClass('alert-validate');
//     }

//     /*==================================================================
//     [ Show pass ]*/
//     var showPass = 0;
//     $('.btn-show-pass').on('click', function(){
//         if(showPass == 0) {
//             $(this).next('input').attr('type','text');
//             $(this).addClass('active');
//             showPass = 1;
//         }
//         else {
//             $(this).next('input').attr('type','password');
//             $(this).removeClass('active');
//             showPass = 0;
//         }

//     });

// })(jQuery);


///open a new tab for invoice print///
const openPrint=(url)=> {
    window.open(url, '_blank', 'width=800,height=800');
}