document.getElementById('togglePassword').addEventListener('change', function() {
  var oldPassword = document.getElementById('oldPass');
  var newPassword = document.getElementById('newPass');
  var confirmNewPassword = document.getElementById('confirm_newPass');

  if (this.checked) {
    oldPassword.type = 'text';
    newPassword.type = 'text';
    confirmNewPassword.type = 'text';
  } else {
    oldPassword.type = 'password';
    newPassword.type = 'password';
    confirmNewPassword.type = 'password';
  }
});

// -----view|hide password-----
const togglePassword = document.getElementById('togglePassword');
const togglePasswordLabel = document.getElementById('togglePasswordLabel');

togglePassword.addEventListener('change', function() {
  const passwordFields = ['oldPass', 'newPass', 'confirm_newPass'];
  passwordFields.forEach(id => {
    const field = document.getElementById(id);
    field.type = this.checked ? 'text' : 'password';
  });
  togglePasswordLabel.textContent = this.checked ? 'Hide Password' : 'Show Password';
});