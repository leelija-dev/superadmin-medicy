const xmlhttp = new XMLHttpRequest();

const validateMobileNumber = () => {

    let mobileInput = document.getElementById('mobile-number');

    var inputValue = mobileInput.value;
    var numericValue = inputValue.replace(/[^0-9]/, '');
    document.getElementById('mobile-number').value = numericValue;

}


const verifyMobileNumber = () =>{
    let cntactNumber = document.getElementById('mobile-number').value;

    if(cntactNumber.length == ' '){
        document.getElementById('contact-error').innerText = 'Enter contact number!';
    }

    if(cntactNumber.length != 10){
        document.getElementById('contact-error').innerText = 'Enter valid contact number!';
    }

    if(cntactNumber.length == 10){
        $.ajax({
            url: "ajax/admin-mail-usrnm-existance-check.ajax.php",
            type: "POST",
            data: {
                checkContact: cntactNumber,
            },
            success: function (data) {
                if (data == '1') {
                    document.getElementById('mobile-number').value = ' ';
                    document.getElementById('contact-error').innerText = 'This Number is already used!';
                    document.getElementById('mobile-number').focus();
                }else{
                    document.getElementById('contact-error').innerText = '';
                }
            }
        });
    }
}


const verifyEmail = () => {
    var inputedMail = document.getElementById('email');
    // console.log(inputedMail);

    if (inputedMail.value) {
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(inputedMail.value)) {
            alert('Enter valid email id!');
            document.getElementById('email').value = ' ';
        } else {
            checkEmailAvailability();
        }
    }

}



const checkEmailAvailability = () => {
    let mailId = document.getElementById('email').value;

    $.ajax({
        url: "ajax/admin-mail-usrnm-existance-check.ajax.php",
        type: "POST",
        data: {
            chekEmailExistance: mailId,
        },
        success: function (data) {
            // console.log("ajax admin return data : " + data);
            if (data == '1') {
                alert('Email Exits as registered!');
                document.getElementById('email').value = ' ';
                // document.getElementById('email').focus();
                return 1;
            } else {
                return 0;
            }
        }
    });
}




const verifyUsername = (t) => {
    let admUsrnm = document.getElementById("user-name").value;
    // console.log(t.value);
    $.ajax({
        url: "ajax/admin-mail-usrnm-existance-check.ajax.php",
        type: "POST",
        data: {
            chekUsrnmExistance: admUsrnm,
        },
        success: function (data) {
            // console.log("ajax return data : " + data);
            if (data == '1') {
                alert('Username Exits as registered!');
                document.getElementById('user-name').value = ' ';
            }
        }
    });
}



// === otp submit move next ===

function moveToNext(current, nextId) {
    const maxLength = parseInt(current.getAttribute('maxlength'));
    const currentLength = current.value.length;

    if (currentLength >= maxLength) {
        const nextInput = document.getElementById(nextId);
        if (nextInput) {
            nextInput.focus();
        }
    }
}


//==password validation==

