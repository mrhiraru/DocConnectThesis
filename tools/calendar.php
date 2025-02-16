<script type="text/javascript">
    const userEmail = '<?= $_SESSION['email'] ?>';
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
    async function handleAuthClick() {
        const authenticate = document.getElementById('authenticate');
        const confirm = document.getElementById('confirm');
        const proceed = document.getElementById('proceed');

        const tokenData = await fetch('../handlers/get_token.php');
        const tokenJson = await tokenData.json();
        let accessToken = tokenJson.access_token;

        if (!accessToken) {
            console.log("No token in session. Requesting authentication.");

            tokenClient.callback = async (resp) => {
                if (resp.error) throw resp;
                accessToken = resp.access_token;

                await fetch('../handlers/store_token.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        access_token: accessToken
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin',
                });

                await handleUserVerification(accessToken, authenticate, confirm, proceed);
            };

            tokenClient.requestAccessToken({
                login_hint: userEmail
            });
        } else {
            console.log("Token found in session. Using it.");
            gapi.client.setToken({
                access_token: accessToken
            });
            await handleUserVerification(accessToken, authenticate, confirm, proceed);
        }
    }

    async function handleUserVerification(accessToken, authenticate, confirm, proceed) {
        const userInfoResponse = await fetch('https://www.googleapis.com/oauth2/v3/userinfo', {
            headers: {
                Authorization: `Bearer ${accessToken}`
            },
        });

        const userInfo = await userInfoResponse.json();

        if (userInfo.email !== userEmail) {
            console.log(`Unauthorized email. Expected ${userEmail}, but got ${userInfo.email}`);
            handleSignoutClick();
        } else {
            console.log(`Authenticated as ${userInfo.email}`);
            if (authenticate) {
                authenticate.toggleAttribute('hidden');
            }
            if (proceed) {
                proceed.toggleAttribute('hidden');
            }
            await listUpcomingEvents();
        }
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