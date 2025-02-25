
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