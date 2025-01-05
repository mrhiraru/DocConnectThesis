document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
    themeSystem: 'Lux',
    editable: true,
    selectable: true,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },
    events: [
      {
        title: 'All Day Event',
        start: '2024-04-01'
      },
      {
        title: 'Long Event',
        start: '2024-05-07',
        end: '2024-05-10'
      },
      {
        groupId: '999',
        title: 'Repeating Event',
        start: '2024-05-09T16:00:00'
      },
      {
        groupId: '999',
        title: 'Repeating Event',
        start: '2024-05-16T16:00:00'
      },
      {
        title: 'Conference',
        start: '2024-09-11',
        end: '2024-09-13'
      },
      {
        title: 'Meeting',
        start: '2024-05-12T10:30:00',
        end: '2024-05-12T12:30:00'
      },
      {
        title: 'Lunch',
        start: '2024-05-12T12:00:00'
      },
      {
        title: 'Meeting',
        start: '2024-05-12T14:30:00'
      },
      {
        title: 'Birthday Party',
        start: '2024-05-13T07:00:00'
      },
      {
        title: 'Click for Google',
        url: 'http://google.com/',
        start: '2024-09-18'
      }
    ],
    windowResize: function(view) {
      if (window.innerWidth < 768) {
        calendar.changeView('listWeek');

      } else {
        calendar.changeView('dayGridMonth');
      }
    }
  });

  calendar.render();
});
