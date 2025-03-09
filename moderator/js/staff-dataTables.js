$(document).ready(function() {
  var dataTable = $("#staff_table").DataTable({
    dom: 'Brtp',
    scrollCollapse: true,
    scrollY: '300px',
    scrollX: true,
    pageLength: 10,
    columnDefs: [
      {
        targets: [3, 4, 5, 6, 7], // Columns without sorting
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
    var name = searchData[2].toLowerCase();
    var position = searchData[3].toLowerCase();

    if (name.indexOf(searchVal) !== -1 || position.indexOf(searchVal) !== -1) {
      return true;
    }

    return false;
  });
});
