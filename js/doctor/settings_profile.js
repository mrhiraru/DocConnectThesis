document.getElementById('contact').addEventListener('input', function() {
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

  this.value =prefix + formatted;
});