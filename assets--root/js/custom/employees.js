
// employee edit modal open and show Script
viewAndEdit = (empId) => {
    let employeeId = empId;
    let url = "ajax/emp.view.ajax.php?employeeId=" + employeeId;
    $(".viewnedit").html('<iframe width="99%" height="500px" frameborder="0" allowtransparency="true" src="' +
        url + '"></iframe>');
} // end of viewAndEdit function



// employee delete scritp
$(document).ready(function () {
    $(document).on("click", ".delete-btn", function () {

        if (confirm("Are you want delete data?")) {
            empId = $(this).data("id");
            //echo $empDelete.$this->conn->error;exit;

            btn = this;
            $.ajax({
                url: "ajax/employee.Delete.ajax.php",
                type: "POST",
                data: {
                    id: empId
                },
                success: function (response) {

                    if (response == 1) {
                        $(btn).closest("tr").fadeOut()
                    } else {
                        // $("#error-message").html("Deletion Field !!!").slideDown();
                        // $("success-message").slideUp();
                        alert(response);
                    }

                }
            });
        }
        return false;
    })

})



// password show hide script
function showHide(fieldId) {
    const password = document.getElementById(fieldId);
    const toggle = document.getElementById('toggle');

    if (password.type === 'password') {
        password.setAttribute('type', 'text');
        // toggle.classList.add('hide');
    } else {
        password.setAttribute('type', 'password');
        // toggle.classList.remove('hide');
    }
}



// ========== employee username and email contol ==========

const checkEmpUsrNm = (t) => {

    let empUsrNm = t.value;

    $.ajax({
        url: "ajax/empUsernameEmailCheckExistance.ajax.php",
        type: "POST",
        data: {
            empUsrNm: empUsrNm,
        },
        success: function (data) {
            console.log("ajax employee username return data : " + data);
            if (data == '1') {
                alert('Username Exits as registered!');
                document.getElementById('emp-username').value = ' ';
                // document.getElementById('email').focus();
                return 1;
            } else {
                return 0;
            }
        }
    });
}



const checkEmpEmail = (t) => {
    var inputedMail = t.value;
    // console.log(inputedMail);

    if (inputedMail != '') {
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(inputedMail)) {
            alert('Enter valid email id!');
            document.getElementById('emp-mail').value = ' ';
        } else {
            checkEmpEmailAvailability();
        }
    }

}

const checkEmpEmailAvailability = (inputedMail) => {

    let email = inputedMail;
    // console.log("cll fucntion mail : " + inputedMail)

    $.ajax({
        url: "ajax/empUsernameEmailCheckExistance.ajax.php",
        type: "POST",
        data: {
            empEmail: email,
        },
        success: function (data) {
            console.log("ajax employee email return data : " + data);
            if (data == '1') {
                alert('Email Exits as registered!');
                document.getElementById('emp-mail').value = ' ';
                // document.getElementById('email').focus();
                return 1;
            } else {
                return 0;
            }
        }
    });
}




//  EMPLOYEE VIEW EDIT FUNCTION 
function editEmp() {
    let empId = $("#empId").val();
    let empUsername = document.getElementById("empUsername").value;
    let firstName = document.getElementById("fname").value;
    let lastName = document.getElementById("lname").value;
    let empRole = document.getElementById("empRole").value;
    let empEmail = document.getElementById("empEmail").value;
    let empContact = document.getElementById("contact").value;
    let empAddress = document.getElementById("empAddress").value;
    const formFlag = 'editEmployeeData';


    const checkboxes = document.querySelectorAll('input[name="permissions[]"]:checked');
    // Create an array to store the values of checked checkboxes
    const checkedValues = Array.from(checkboxes).map(checkbox => checkbox.value);
    const accessPermission = checkedValues.join(',')



    // Validate input values
    if (!empId || !empUsername || !firstName || !lastName || !empRole || !empEmail || !empContact || !empAddress) {
        alert("Please fill in all the required fields.");
        return;
    }

    // Additional validation for email format
    if (!isValidEmail(empEmail)) {
        alert("Please enter a valid email address.");
        return;
    }

    // Additional validation for contact number format
    if (!isValidContact(empContact)) {
        alert("Please enter a valid contact number.");
        return;
    }

    let formData = new FormData();

    formData.append('empId',empId);
    formData.append('empUsername',empUsername);
    formData.append('firstName',firstName);
    formData.append('lastName',lastName);
    formData.append('empRole',empRole);
    formData.append('permission',accessPermission);
    formData.append('empEmail',empEmail);
    formData.append('empContact',empContact);
    formData.append('formFlag',formFlag);
    
    $.ajax({
        url: 'emp.edit.ajax.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            console.log(response);
            try {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: jsonResponse.message
                    }).then(function() {
                        parent.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: jsonResponse.message
                    }).then(function() {
                        parent.location.reload();
                    });
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again later.'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while processing your request. Please try again later.'
            });
        }
    });
}

// Function to validate email format
function isValidEmail(email) {
    return true; // Placeholder, replace with your logic
}

// Function to validate contact number format
function isValidContact(contact) {
    return true; // Placeholder, replace with your logic
}

