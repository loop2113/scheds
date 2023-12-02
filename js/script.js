$(document).ready(function() {
    $('#faculty').change(function() {
        var facultyId = $(this).val();

        $('#calendar').fullCalendar('removeEvents');

        $.ajax({
            url: 'script.php',
            type: 'GET',
            data: {
                facultyId: facultyId
            },
            success: function(response) {
                var events = JSON.parse(response);

                // Update event titles to include complete time and title
                for (var i = 0; i < events.length; i++) {
                    events[i].title = events[i].section + ' @ ' + events[i].room + ' \n ' +
                                      moment(events[i].start).format('hh:mm A') + ' to ' + ' \n ' +
                                      moment(events[i].end).format('hh:mm A')
                    events[i].desc =  'Faculty: ' + events[i].name
                    events[i].desc2 = 'Schedule: ' +
                                      moment(events[i].start).format('hh:mm A') + ' \n ' +
                                      moment(events[i].end).format('hh:mm A') + ' \n '
                    events[i].desc3 = 'Subject: ' + events[i].subject + ' \n '
                    events[i].desc4 = 'Section: ' + events[i].section + ' \n '
                    events[i].desc5 = 'Room: ' + events[i].room + ' \n '
                }

                $('#calendar').fullCalendar('addEventSource', events);
            }
        });
    });

    $('#calendar').fullCalendar({
        // FullCalendar options and configurations
        header: {
            left: 'prev,next today',
            right: 'agendaWeek,agendaDay'
        },
        // ... other FullCalendar configurations
        eventClick: function(calEvent, jsEvent, view) {
            // Handle event click here
            displayEventModal(calEvent); // Function to display the modal with event details
        }
    });
    
    function displayEventModal(events) {
        // Populate modal with event details
        $('#modalDescription').text(events.desc);
        $('#modalDescription2').text(events.desc2);
        $('#modalDescription3').text(events.desc3);
        $('#modalDescription4').text(events.desc4);
        $('#modalDescription5').text(events.desc5);
        // You can populate other modal fields if needed
    
        // Show the modal
        $('#eventModal').modal('show');
    }

});