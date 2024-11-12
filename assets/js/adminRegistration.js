let xmlhttp = new XMLHttpRequest();

// powerurl = 'ajax/product.getMedicineDetails.ajax.php?power=' + productId;
// // alert(url);
// xmlhttp.open("GET", powerurl, false);
// xmlhttp.send(null);
// // alert(xmlhttp.responseText);
// document.getElementById("medicine-power").value = xmlhttp.responseText;

// ======== code to chek mobile number input validity ===========
var mobileInput = document.getElementById('mobile-number');

mobileInput.addEventListener('input', function () {
    var inputValue = mobileInput.value;
    var numericValue = inputValue.replace(/[^0-9]/g, '');

    mobileInput.value = numericValue;
});

const validateMobileNumber = () => {
    let mobileInputLength = document.getElementById('mobile-number');

    if (mobileInputLength.value.length != 9) {
        mobileInputLength.focus();
        console.log('input 10 digits');
    }
}

const verifyEmail = () => {
    var inputedMail = document.getElementById('email');

    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (emailRegex.test(inputedMail.value)) {

        var domain = inputedMail.value.split('@')[1];

        var allowedDomains = ['gmail.com', 'yahoo.com', 'yahoo.in', 'ovi.com', 'rediffmail.com'];

        if (!emailRegex.test(inputedMail.value)) {
            alert('Email not valid');
            document.getElementById('email').value = ' ';
            if(!allowedDomains.includes(domain)){
                alert('Check Domain name');
                document.getElementById('email').value = '';
            }
        } else {
            checkEmailAvailability(inputedMail);
        }
    } else {
        alert('Email is not valid');
    }
}


const checkEmailAvailability = (inputedMail) => {
    let mailId = inputedMail.value;


    $.ajax({
        url: "ajax/email-verification-validation.ajax.php",
        type: "POST",
        data: {
            chekExistance: mailId,
        },
        success: function (data) {
            console.log(data);
            if (data == 0) {
                alert('Email Exits!');
                // document.getElementById('email').focus();
            } else {
                console.log(data);
            }
        }
    });
}

