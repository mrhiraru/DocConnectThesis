$(document).ready(function() {
  var dataTable = $("#profileGeneral_table").DataTable({
    dom: 'rtp',
    scrollX: true,
    pageLength: 5,
    columnDefs: [
      {
        targets: [3, 4, 5],
        orderable: false
      }
    ]
  });

  var input = $('input#keyword');
  var sortSelect = $('#sort-by');

  input.on("keyup", function() {
    dataTable.draw();
  });

  sortSelect.on('change', function() {
    var sortIndex = $(this).val();
    dataTable.order([sortIndex, 'asc']).draw();
  });
});
