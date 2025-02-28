function previewImage(event) {
  const file = event.target.files[0]; // Get the selected file
  const output = document.getElementById('output'); // Get the image preview element

  if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
          output.src = e.target.result; // Set image source to the uploaded file
      };
      reader.readAsDataURL(file); // Read the file as a data URL
  }
}