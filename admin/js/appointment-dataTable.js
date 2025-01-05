$(document).ready(function() {
  var dataTable = $("#appointment_table").DataTable({
    dom: 'Brtp',
    scrollX: true,
    pageLength: 10,
    columnDefs: [
      {
        targets: [0, 1, 7], // Columns without sorting
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

  $.fn.dataTable.ext.search.push(function(settings, searchData, index, rowData, counter) {
    var searchVal = input.val().toLowerCase();
    var patientName = searchData[3].toLowerCase();
    var doctorName = searchData[4].toLowerCase();

    if (patientName.indexOf(searchVal) !== -1 || doctorName.indexOf(searchVal) !== -1) {
      return true;
    }

    return false;
  });
});
