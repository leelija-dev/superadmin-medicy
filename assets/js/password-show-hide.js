
function showToggleBtn(inputId, toggleBtnId) {
    var passwordInput = document.getElementById(inputId);
    var toggleBtn = document.getElementById(toggleBtnId);

    if (passwordInput.value.trim() !== '') {
        toggleBtn.style.display = 'inline-block';
    } else {
        toggleBtn.style.display = 'none';
    }
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