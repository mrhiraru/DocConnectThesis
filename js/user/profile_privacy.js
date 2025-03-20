document.getElementById('togglePassword').addEventListener('change', function() {
  var oldPassword = document.getElementById('oldPassword');
  var newPassword = document.getElementById('newPassword');
  var confirmNewPassword = document.getElementById('confirmNewPassword');

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

togglePassword.addEventListener('change', function () {
  const passwordFields = ['oldPassword', 'newPassword', 'confirmNewPassword'];
  passwordFields.forEach(id => {
    const field = document.getElementById(id);
    field.type = this.checked ? 'text' : 'password';
  });
  togglePasswordLabel.textContent = this.checked ? 'Hide Password' : 'Show Password';
});