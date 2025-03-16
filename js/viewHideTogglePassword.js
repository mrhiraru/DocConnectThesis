// ---------view/hide toggle passwrod----------
document.querySelectorAll('.toggle-password').forEach(icon => {
  icon.addEventListener('click', function () {
    let target = document.getElementById(this.dataset.target);

    if (target.type === "password") {
      target.type = "text";
      this.classList.replace('bx-show', 'bx-hide'); // Switch to "hide" icon
    } else {
      target.type = "password";
      this.classList.replace('bx-hide', 'bx-show'); // Switch to "show" icon
    }
  });
});

// --------phone no input--------
// document.getElementById('contact').addEventListener('input', function() {
//   const prefix = "+63 ";
//   if (!this.value.startsWith(prefix)) {
//     this.value = prefix;
//   }

//   const rawDigits = this.value.slice(prefix.length).replace(/\D/g, '');
//   let formatted = "";

//   if (rawDigits.length > 0) {
//     formatted += rawDigits.slice(0, 3);
//   }
//   if (rawDigits.length > 3) {
//     formatted += " " + rawDigits.slice(3, 6);
//   }
//   if (rawDigits.length > 6) {
//     formatted += " " + rawDigits.slice(6, 10);
//   }

//   this.value =prefix + formatted;
// });