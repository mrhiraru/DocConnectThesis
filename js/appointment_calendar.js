document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    events: <?php echo json_encode($events); ?>, // Load events from PHP array
    eventClick: function(info) {
      // Check if the event has a URL
      if (info.event.url) {
        window.open(info.event.url, "_blank"); // Open the URL in a new tab
        info.jsEvent.preventDefault(); // Prevent default behavior (navigation)
      } else {
        // If no URL, event is not clickable (show a message or ignore)
        alert("This is a Face-to-Face event and does not have an online link.");
      }
    }
  });
  calendar.render();

  // Handle form submission
  document.getElementById('addEventForm').addEventListener('submit', function(e) {
    e.preventDefault();

    var eventTitle = document.getElementById('eventTitle').value;
    var eventDate = document.getElementById('eventDate').value;
    var startTime = document.getElementById('startTime').value;
    var endTime = document.getElementById('endTime').value;
    var eventUrl = document.getElementById('eventUrl').value;
    var isRepeating = document.getElementById('isRepeating').checked;
    var meetingType = document.getElementById('meetingTypeSwitch').checked ? 'online' : 'face-to-face';

    // Create event object
    var event = {
      title: eventTitle,
      start: eventDate + 'T' + startTime,
      end: endTime ? eventDate + 'T' + endTime : null,
      url: meetingType === 'online' ? eventUrl : null
    };

    // Handle repeating events
    if (isRepeating) {
      var groupId = 'group' + Math.floor(Math.random() * 1000); // Random groupId for repeating events
      event.groupId = groupId;

      for (let i = 0; i < 3; i++) {
        let repeatingEvent = Object.assign({}, event);
        repeatingEvent.start = new Date(new Date(event.start).getTime() + i * 7 * 24 * 60 * 60 * 1000).toISOString(); // Add 7 days for each repeat
        calendar.addEvent(repeatingEvent);
      }
    } 
    
    else {
      calendar.addEvent(event);
    }

    document.getElementById('addEventForm').reset();
    var modal = bootstrap.Modal.getInstance(document.getElementById('addEventModal'));
    modal.hide();
  });

  // Switch between Face-to-Face and Online Meeting
  var meetingTypeSwitch = document.getElementById('meetingTypeSwitch');
  var eventUrlField = document.getElementById('eventUrl');
  var meetingTypeLabel = document.getElementById('meetingTypeLabel');

  meetingTypeSwitch.addEventListener('change', function() {
    if (meetingTypeSwitch.checked) {
      eventUrlField.disabled = false;
      eventUrlField.required = true;
      meetingTypeLabel.innerHTML = 'Online';
    } 
    
    else {
      eventUrlField.disabled = true;
      eventUrlField.required = false;
      meetingTypeLabel.innerHTML = 'Face-to-Face';
    }
  });
});