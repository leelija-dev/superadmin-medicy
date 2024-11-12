
function showToggleBtn(inputId, toggleBtnId) {
    var passwordInput = document.getElementById(inputId);
    var toggleBtn = document.getElementById(toggleBtnId);

    if (passwordInput.value.trim() !== '') {
        toggleBtn.style.display = 'inline-block';
    } else {
        toggleBtn.style.display = 'none';
    }
    // checkPasswordLength(inputId);
}

function togglePassword(inputId, toggleBtnId) {
    var passwordInput = document.getElementById(inputId);
    var toggleBtn = document.getElementById(toggleBtnId);

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.className = 'fas fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        toggleBtn.className = 'fas fa-eye';
    }
}

function checkPasswordLength(passwordId) {
    const passwordInput = document.getElementById(passwordId);
    const errorMsg = document.getElementById(passwordId + '-error');
    if (passwordInput.value.length > 0 && passwordInput.value.length < 8) {
        errorMsg.style.display = 'inline';
    } else {
        errorMsg.style.display = 'none';
    }
}