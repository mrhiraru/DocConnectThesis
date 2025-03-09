document.addEventListener('DOMContentLoaded', function() {
  const deleteButtons = document.querySelectorAll('.delete-btn');
  const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

  let deleteSubjectId;

  deleteButtons.forEach(button => {
    button.addEventListener('click', function() {
      deleteSubjectId = this.getAttribute('data-subject-id');
      $('#deleteConfirmationModal').modal('show');
    });
  });

  confirmDeleteBtn.addEventListener('click', function() {
    $.ajax({
      url: '#deleteLink', //Tool/delete tapos ganyan
      method: 'POST',
      data: { delete_subject: deleteSubjectId },
      success: function(response) {
      
        window.location.reload();
      },
      error: function(xhr, status, error) {

        console.error(xhr.responseText);
      }
    });
  });
});