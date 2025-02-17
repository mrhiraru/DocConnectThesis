<script type="text/javascript">
    //const doctorEmail = '<?= $_SESSION['email'] ?>'; //original code,
    const doctorEmail = 'mrhiraru@gmail.com'; // for testing
    const patientEmail = '<?= $record['email'] ?>';
    const appointment_id = <?= $record['appointment_id'] ?>;
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
    const SCOPES = 'openid email https://www.googleapis.com/auth/calendar.readonly';

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

    authentication_checkbox.addEventListener('click', async function(event) {
        event.preventDefault(); // Stop the checkbox from checking immediately

        try {
            const isVerified = await handleAuthClick();
            if (isVerified) {
                authentication_checkbox.checked = true; // Only check if verification is successful
            } else {
                authentication_checkbox.checked = false; // Make sure it's unchecked if verification fails
            }
        } catch (error) {
            console.error('Authentication/Verification failed:', error);
            authentication_checkbox.checked = false; // Uncheck on error
        }
    });

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

            return true;
        }
    }

    function new_event() {
        const event = {
            'summary': 'Docconnect Consultation: ' + appointment_id, // Event Title
            'description': reason, // Event Description
            'start': {
                'dateTime': appointmentDateTime, // Start Time (ISO 8601 format)
                'timeZone': 'Asia/Manila' // Time Zone
            },
            'end': {
                'dateTime': new Date(new Date(appointmentDateTime).getTime() + 59 * 60 * 1000).toISOString(), // End Time
                'timeZone': 'Asia/Manila'
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
                    'email': patientEmail
                }, // Patient's email
                {
                    'email': doctorEmail
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

        request.execute(function(event) {
            if (event.hangoutLink) {
                console.log('Google Meet Link:', event.hangoutLink);
            } else {
                console.log('No Google Meet link created.');
            }
        });
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