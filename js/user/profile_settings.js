document.getElementById('phoneNo').addEventListener('input', function () {
  const prefix = "+63 ";
  if (!this.value.startsWith(prefix)) {
    this.value = prefix;
  }

  const rawDigits = this.value.slice(prefix.length).replace(/\D/g, '');
  let formatted = "";

  if (rawDigits.length > 0) {
    formatted += rawDigits.slice(0, 3);
  }
  if (rawDigits.length > 3) {
    formatted += " " + rawDigits.slice(3, 6);
  }
  if (rawDigits.length > 6) {
    formatted += " " + rawDigits.slice(6, 10);
  }

  this.value = prefix + formatted;
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