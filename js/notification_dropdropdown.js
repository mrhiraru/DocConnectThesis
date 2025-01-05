document.addEventListener('DOMContentLoaded', function () {
  const notificationDropdown = document.getElementById('notificationDropdown');
  const dotsDropdown = document.getElementById('dotsDropdown');
  
  dotsDropdown.addEventListener('click', function (event) {
    event.stopPropagation();
  });
});