// ---Modal for Viewing Table Details---
document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
  button.addEventListener('click', () => {
    const modal = document.getElementById('editAllergy');

    modal.querySelector('#allergyType').value = button.getAttribute('data-type');
    modal.querySelector('#allergyLevel').value = button.getAttribute('data-level');
  });
});