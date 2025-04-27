<script type="text/javascript">
    const doctorEmail = '<?= $_SESSION['email'] ?>'; //original code
    /* hostinger auto deployment success (2) */
    /* exported gapiLoaded */
    /* exported gisLoaded */
    /* exported handleAuthClick */
    /* exported handleSignoutClick */

    // TODO(developer): Set to client ID and API key from the Developer Console
    const CLIENT_ID = '396530690721-pb1q5bs9ekjpsqlhvg6ejd1jvov9e7ab.apps.googleusercontent.com';
    const API_KEY = 'AIzaSyBXSJbLNAUQVFQzC-XCjgGclOjiQPB-YyE';

    // Discovery doc URL for APIs used by the quickstart
    const DISCOVERY_DOC = 'https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest';

    // Authorization scopes required by the API; multiple scopes can be
    // included, separated by spaces.
    const SCOPES = 'openid email https://www.googleapis.com/auth/calendar.events';

    let tokenClient;
    let gapiInited = false;
    let gisInited = false;

    /**
     * Callback after api.js is loaded.
     */
    function gapiLoaded() {
        gapi.load('client', initializeGapiClient);
    }

    /**
     * Callback after the API client is loaded. Loads the
     * discovery doc to initialize the API.
     */
    async function initializeGapiClient() {
        await gapi.client.init({
            apiKey: API_KEY,
            discoveryDocs: [DISCOVERY_DOC],
        });
        gapiInited = true;
        maybeEnableButtons();
    }

    /**
     * Callback after Google Identity Services are loaded.
     */
    function gisLoaded() {
        tokenClient = google.accounts.oauth2.initTokenClient({
            client_id: CLIENT_ID,
            scope: SCOPES,
            callback: '', // defined later
        });
        gisInited = true;
        maybeEnableButtons();
    }

    /**
     * Enables user interaction after all libraries are loaded.
     */
    function maybeEnableButtons() {
        if (gapiInited && gisInited) {
            //document.getElementById('authorize_button').style.visibility = 'visible';
        }
    }

    /**
     *  Sign in the user upon button click.
     */
    const authentication_checkbox = document.getElementById('authenticate');

    // authentication_checkbox.addEventListener('click', async function(event) {
    //     event.preventDefault(); // Stop the checkbox from checking immediately

    //     try {
    //         const isVerified = await handleAuthClick();
    //         if (isVerified) {
    //             authentication_checkbox.checked = true; // Only check if verification is successful
    //         } else {
    //             authentication_checkbox.checked = false; // Make sure it's unchecked if verification fails
    //         }
    //     } catch (error) {
    //         console.error('Authentication/Verification failed:', error);
    //         authentication_checkbox.checked = false; // Uncheck on error
    //     }
    // });

    async function handleAuthClick() {
        const tokenData = await fetch('../handlers/get_token.php');
        const tokenJson = await tokenData.json();
        let accessToken = tokenJson.access_token;

        if (!accessToken) {
            console.log('No token in session. Requesting authentication.');

            return new Promise((resolve, reject) => {
                tokenClient.callback = async (resp) => {
                    if (resp.error) {
                        console.error('Token Error:', resp);
                        reject(resp);
                        return;
                    }

                    accessToken = resp.access_token;

                    await fetch('../handlers/store_token.php', {
                        method: 'POST',
                        body: JSON.stringify({
                            access_token: accessToken,
                        }),
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        credentials: 'same-origin',
                    });

                    const isVerified = await handleUserVerification(accessToken);
                    resolve(isVerified);
                };

                tokenClient.requestAccessToken({
                    prompt: 'consent',
                    login_hint: doctorEmail,
                });
            });
        } else {
            console.log('Token found in session. Using it.');
            gapi.client.setToken({
                access_token: accessToken,
            });

            const isVerified = await handleUserVerification(accessToken);
            return isVerified;
        }
    }

    async function handleUserVerification(accessToken) {
        const userInfoResponse = await fetch('https://www.googleapis.com/oauth2/v3/userinfo', {
            headers: {
                Authorization: `Bearer ${accessToken}`
            },
        });

        const userInfo = await userInfoResponse.json();

        if (userInfo.email !== doctorEmail) {
            console.log(`Unauthorized email. Expected ${doctorEmail}, but got ${userInfo.email}`);
            handleSignoutClick();
            const confirm_modal = document.getElementById('confirmationFailed');
            if (confirm_modal) {
                var myModal = new bootstrap.Modal(confirm_modal, {});
                myModal.show();
            }
            return false;
        } else {
            console.log(`Authenticated as ${userInfo.email}`);
            //show new modal for confirm.
            return true;
        }
    }

    async function new_event(appointment_id, reason, date, time, patient_email, doctor_email) {

        var title = 'Docconnect Consultation: ' + appointment_id;
        var description = reason;
        var appointment_date_time = `${date}T${time}`;

        var start_dt = new Date(appointment_date_time);
        var end_dt = new Date(start_dt.getTime() + 59 * 60 * 1000);

        const event = {
            'summary': title, // Event Title
            'description': description, // Event Description
            'start': {
                'dateTime': start_dt.toISOString(), // Start Time (ISO 8601 format)
                'timeZone': 'UTC' // Time Zone
            },
            'end': {
                'dateTime': end_dt.toISOString(), // End Time
                'timeZone': 'UTC'
            },
            'conferenceData': {
                'createRequest': {
                    'requestId': appointment_id, // Unique request ID (random string)
                    'conferenceSolutionKey': {
                        'type': 'hangoutsMeet' // Specifies Google Meet
                    }
                }
            },
            'attendees': [{
                    'email': patient_email
                }, // Patient's email
                {
                    'email': doctor_email
                } // Doctor's email
            ],
            'reminders': {
                'useDefault': false, // Set to false to define custom reminders
                'overrides': [{
                        'method': 'email',
                        'minutes': 10
                    }, // Send an email reminder 30 mins before
                    {
                        'method': 'popup',
                        'minutes': 5
                    } // Show a popup notification 10 mins before
                ]
            }
        };

        const request = gapi.client.calendar.events.insert({
            'calendarId': 'primary', // Inserts into the user's primary calendar
            'resource': event, // Uses the event object created above
            'conferenceDataVersion': 1 // Required to generate a Google Meet link
        });

        return new Promise((resolve, reject) => {
            request.execute(function(event) {
                if (event && event.id && event.hangoutLink) {
                    resolve({
                        eventId: event.id,
                        hangoutLink: event.hangoutLink
                    });
                } else {
                    reject('No Google Meet link or event ID created.');
                }
            });
        });
    }

    async function update_event(event_id, appointment_id, purpose, date, time, patient_email, doctor_email) {
        var title = 'Docconnect Consultation: ' + appointment_id;
        var description = purpose;
        var appointment_date_time = `${date}T${time}`;

        var start_dt = new Date(appointment_date_time);
        var end_dt = new Date(start_dt.getTime() + 59 * 60 * 1000);

        const event = {
            'summary': title,
            'description': description,
            'start': {
                'dateTime': start_dt.toISOString(),
                'timeZone': 'UTC'
            },
            'end': {
                'dateTime': end_dt.toISOString(),
                'timeZone': 'UTC'
            },
            'attendees': [{
                    'email': patient_email
                },
                {
                    'email': doctor_email
                }
            ],
            'reminders': {
                'useDefault': false,
                'overrides': [{
                        'method': 'email',
                        'minutes': 10
                    },
                    {
                        'method': 'popup',
                        'minutes': 5
                    }
                ]
            }
        };

        const request = gapi.client.calendar.events.update({
            'calendarId': 'primary', // Updates the existing event
            'eventId': event_id, // Specify the event to update
            'resource': event
        });

        return new Promise((resolve, reject) => {
            request.execute(function(event) {
                if (event && event.id) {
                    resolve({
                        eventId: event.id,
                        updated: true
                    });
                } else {
                    reject('Event update failed.');
                }
            });
        });
    }

    async function delete_event(event_id) {
        const request = gapi.client.calendar.events.delete({
            'calendarId': 'primary', // Deletes from the user's primary calendar
            'eventId': event_id // Specify the event to delete
        });

        return new Promise((resolve, reject) => {
            request.execute(function(response) {
                if (!response || response.error) {
                    reject('Event deletion failed.');
                } else {
                    resolve({
                        eventId: event_id,
                        deleted: true
                    });
                }
            });
        });
    }


    $(document).ready(function() {

        $('#appointmentForm').on('submit', async function(e) {
            e.preventDefault();

            let submit_button = event.submitter.name;

            if (submit_button == "confirm") {

                const isVerified = await handleAuthClick();

                if (isVerified) {
                    const formData = {
                        appointment_id: <?= $record['appointment_id'] ?>,
                        confirm: $('#confirm').val(),
                        appointment_date: $('#appointment_date').val(),
                        appointment_time: $('#appointment_time').val(),
                        purpose: $("#purpose").val(),
                        email: $('#email').text(),
                        link: null,
                        event_id: null,
                    };

                    var created_event = await new_event(formData.appointment_id, formData.purpose, formData.appointment_date, formData.appointment_time, formData.email, doctorEmail);
                    formData.link = created_event.hangoutLink;
                    formData.event_id = created_event.eventId;

                    if (formData.event_id) {
                        $.ajax({
                            url: '../handlers/doctor.update_appointment.php',
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                if (response.trim() === 'success') { // Trim to avoid whitespace issues
                                    const updated = document.getElementById('updatedModal');
                                    message_notifcation('confirm');
                                    if (updated) {
                                        var myModal = new bootstrap.Modal(updated, {});
                                        myModal.show();
                                    }
                                } else {
                                    console.error('Error:', response);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error update appointment:', error);
                            }
                        });
                    }
                }
            } else if (submit_button == "reschedule") {

                const isVerified = await handleAuthClick();

                if (isVerified) {
                    const formData = {
                        appointment_id: <?= $record['appointment_id'] ?>,
                        reschedule: $('#reschedule').val(),
                        appointment_date: $('#appointment_date').val(),
                        appointment_time: $('#appointment_time').val(),
                        purpose: $("#purpose").val(),
                        email: $('#email').text(),
                        link: '<?= $record['appointment_link'] ?>',
                        event_id: '<?= $record['event_id'] ?>'
                    };

                    var updated_event = await update_event(formData.event_id, formData.appointment_id, formData.purpose, formData.appointment_date, formData.appointment_time, formData.email, doctorEmail);

                    if (updated_event.updated) {
                        $.ajax({
                            url: '../handlers/doctor.update_appointment.php',
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                if (response.trim() === 'success') { // Trim to avoid whitespace issues
                                    const updated = document.getElementById('rescheduleModal');
                                    message_notifcation('resched');
                                    if (updated) {
                                        var myModal = new bootstrap.Modal(updated, {});
                                        myModal.show();
                                    }
                                } else {
                                    console.error('Error:', response);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error sending message:', error);
                            }
                        });
                    }
                }
            } else if (submit_button == "cancel") {
                const updated = document.getElementById('cancelModal');
                if (updated) {
                    var myModal = new bootstrap.Modal(updated, {});
                    myModal.show();

                    document.getElementById("cancel-yes").addEventListener("click", async function() {
                        console.log("User confirmed cancellation.");

                        const isVerified = await handleAuthClick();

                        if (isVerified) {
                            const formData = {
                                appointment_id: <?= $record['appointment_id'] ?>,
                                cancel: $('#cancel').val(),
                                event_id: '<?= $record['event_id'] ?>'
                            };

                            var deleted_event = await delete_event(formData.event_id);

                            if (deleted_event.deleted) {
                                $.ajax({
                                    url: '../handlers/doctor.update_appointment.php',
                                    type: 'POST',
                                    data: formData,
                                    success: function(response) {
                                        if (response.trim() === 'success') { // Trim to avoid whitespace issues
                                            const updated = document.getElementById('cancelledModal');
                                            message_notifcation('cancel');
                                            if (updated) {
                                                var myModal = new bootstrap.Modal(updated, {});
                                                myModal.show();
                                            }
                                        } else {
                                            console.error('Error:', response);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Error sending message:', error);
                                    }
                                });

                            }

                            myModal.hide();
                        }
                    });

                    // Handle No button click
                    document.getElementById("cancel-no").addEventListener("click", function() {
                        console.log("User declined cancellation.");

                        myModal.hide();
                    });
                }
            }
        });
    });

    function decline_appointment(decline_reason) {
        $.ajax({
            url: '../handlers/doctor.update_appointment.php',
            type: 'POST',
            data: {
                appointment_id: <?= $record['appointment_id'] ?>,
                decline: 'true',
                decline_reason: decline_reason,
            },
            success: function(response) {
                if (response.trim() === 'success') { // Trim to avoid whitespace issues
                    const updated = document.getElementById('declinedModal');
                    message_notifcation('decline');
                    if (updated) {
                        var myModal = new bootstrap.Modal(updated, {});
                        myModal.show();
                    }
                } else {
                    console.error('Error:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error sending message:', error);
            }
        })
    }

    function message_notifcation(action) {
        $.ajax({
            url: '../handlers/chat.send_message.php',
            type: 'POST',
            data: {
                appointment_id: '<?= $record["appointment_id"] ?>',
                notif: 'true',
                action: action
            },
            success: function(response) {
                console.log('Message notifcation sent.');
            },
            error: function(xhr, status, error) {
                console.error('Error sending message:', error);
            }
        })
    }

    /**
     *  Sign out the user upon button click.
     */
    function handleSignoutClick() {
        fetch('../handlers/store_token.php', {
            method: 'POST',
            body: JSON.stringify({
                access_token: ''
            }), // Clear token
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin',
        });

        gapi.client.setToken(null);
        console.log('Signed out and session token cleared.');
    }

    /**
     * Print the summary and start datetime/date of the next ten events in
     * the authorized user's calendar. If no events are found an
     * appropriate message is printed.
     */
    async function listUpcomingEvents() {
        let response;
        try {
            const request = {
                'calendarId': 'primary',
                'timeMin': (new Date()).toISOString(),
                'showDeleted': false,
                'singleEvents': true,
                'maxResults': 10,
                'orderBy': 'startTime',
            };
            response = await gapi.client.calendar.events.list(request);
        } catch (err) {
            //document.getElementById('content').innerText = err.message;
            return;
        }

        const events = response.result.items;
        if (!events || events.length == 0) {
            document.getElementById('content').innerText = 'No events found.';
            return;
        }
        // Flatten to string to display
        const output = events.reduce(
            (str, event) => `${str}${event.summary} (${event.start.dateTime || event.start.date})\n`,
            'Events:\n');
        //document.getElementById('content').innerText = output;
    }
</script>
<script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
<script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>