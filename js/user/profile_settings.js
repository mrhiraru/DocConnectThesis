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

// -----image slect/change-----
// Preview selected image
const previewImage = (event) => {
  const fileInput = event.target;
  const filePath = fileInput.value;
  const allowedExtensions = /(\.png|\.jpeg|\.jpg)$/i;

  if (!allowedExtensions.exec(filePath)) {
    alert('Invalid file type. Only PNG and JPEG files are allowed.');
    fileInput.value = '';
    return;
  }

  const image = document.getElementById("output");
  image.src = URL.createObjectURL(fileInput.files[0]);
};

// Handle AJAX upload
document.getElementById("uploadProfileImage").addEventListener("click", function () {
  const fileInput = document.getElementById("file");
  const formData = new FormData();
  formData.append("account_image", fileInput.files[0]);

  fetch("/upload-image-endpoint", {
    method: "POST",
    body: formData,
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert("Image uploaded successfully!");
      } else {
        alert("Image upload failed: " + data.message);
      }
    })
    .catch(error => {
      console.error("Error uploading image:", error);
      alert("Error occurred while uploading image.");
    });
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