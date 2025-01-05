var validateFile = function(event) {
  var fileInput = event.target;
  var filePath = fileInput.value;
  var allowedExtensions = /(\.png|\.jpeg|\.jpg)$/i;

  if (!allowedExtensions.exec(filePath)) {
    alert('Invalid file type. Only PNG and JPEG files are allowed.');
    fileInput.value = '';
    return false;
  }

  var image = document.getElementById("output");
  image.src = URL.createObjectURL(event.target.files[0]);
};

$(document).ready(function() {
  $('#upload_profile').click(function() {
    var formData = new FormData($('#profileForm')[0]);
    
    $.ajax({
      url: '#',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        alert(response);
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
        alert('Error occurred while uploading image: ' + error);
      }
    });
  });
});