<?php
session_start();

require_once('../vendor/autoload.php');
require_once('../classes/account.class.php');
require_once('../classes/appointment.class.php');
require_once('../classes/message.class.php');

use Google\Service\AdMob\App;
use Orhanerday\OpenAi\OpenAi;

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();

function chatbot_response($user_message)
{
    $open_ai_key = $_ENV['OPEN_AI_KEY'];

    if (empty($open_ai_key)) {
        return "OpenAI key is not set.";
    }

    $open_ai = new OpenAi($open_ai_key);

    // Doctor's Data
    $account = new Account();
    $accountArray = $account->show_doc();

    $doctorArray = array();
    foreach ($accountArray as $key => $item) {
        $doctorArray[$key] = array(
            "id" => $item['account_id'],
            "name" => (isset($item['middlename'])) ? ucwords(strtolower($item['firstname'] . ' ' . $item['middlename'] . ' ' . $item['lastname'])) : ucwords(strtolower($item['firstname'] . ' ' . $item['lastname'])),
            "specialty" => $item['specialty'],
            "working_days" => $item['start_day'] . ' to ' . $item['end_day'],
            "working_time" => date('h:i A', strtotime($item['start_wt'])) . ' - ' . date('h:i A', strtotime($item['end_wt'])),
            "contact" => $item['contact'],
            "email" => $item['email']
        );
    }

    if (empty($doctorArray)) {
        $list_of_doctor = "There are no doctors available in the system.";
    } else {
        $list_of_doctor = "";
        foreach ($doctorArray as $doctorKey => $doctorItem) {
            $list_of_doctor .= ($doctorKey + 1) . ". " . $doctorItem['name'] . " - " . $doctorItem['specialty'] .
                " (" . $doctorItem['working_days'] . " " . $doctorItem["working_time"] . ")";
        }
    }

    // User's Appointment Data
    $appointment = new Appointment();
    $appointmentArray = $appointment->get_patient_appointment_user($_SESSION['patient_id']);

    $formattedAppointments = array();
    foreach ($appointmentArray as $key => $item) {
        $formattedAppointments[$key] = array(
            "id" => $item['appointment_id'],
            "doctor_name" => ucwords(strtolower($item['doctor_name'])),
            "specialty" => $item['specialty'],
            "date" => date('F d, Y', strtotime($item['appointment_date'])),
            "time" => date('h:i A', strtotime($item['appointment_time'])),
            "status" => ucfirst($item['appointment_status']),
        );
    }

    if (empty($formattedAppointments)) {
        $appointmentList = "You have no scheduled appointments.";
    } else {
        $appointmentList = "";
        foreach ($formattedAppointments as $appointmentKey => $appointmentItem) {
            $appointmentList .= ($appointmentKey + 1) . ". " . $appointmentItem['doctor_name'] . " - " .
                $appointmentItem['specialty'] . " ( " . $appointmentItem['date'] . " at " .
                $appointmentItem['time'] . " ) - Status: " . $appointmentItem['status'] . ".";
        }
    }

    date_default_timezone_set('Asia/Manila');
    $today = date('l, F d, Y h:i A');

    $prompt = "
    
    You are an assistant bot for the Telehealth website, DocConnect. 

        Your role is to assist users within the scope of your defined responsibilities. Follow these guidelines strictly:

        Responsibilities:
        1. General Assistance & Medical Queries
        -Answer only basic medical questions related to symptoms, providing cautious responses that avoid leading users to a conclusion about their condition.
        -Provide only the explanations for a patient's symptoms and give possible diagnoses.
        -Briefly inform users to consult a doctor for a proper diagnosis and treatment.
        -If a query is outside your scope, politely inform the user that you cannot assist. Then, explain your capabilities and the type of assistance you can provide.
        -Avoid generating random responses or providing unrelated information.

        2. Doctor Recommendations & Availability
        -Recommend a doctor based on the user's symptoms.
        -Provide details on available doctors, including their name, specialty, and availability (date, time, and day).
        -Inform users if a doctor is available at their requested date and time.
        -If no doctor with the relevant specialty is available, inform the user instead of suggesting an unrelated doctor.
        -Do not recommend a doctor if their expertise does not match the user's condition. Politely inform the user that no suitable doctor is available.

        3. Website Navigation & Links
        -Help users navigate the website by providing relevant links.
        -Ensure that links are formatted correctly within <a></a> tags.
        -Do not include text outside the <a></a> tags.
        -Provide links to the following pages: Appointment Request, Doctor Information, About Us, Services, Profile & Appointment Management, and Profile Settings.
        -Always include related links when providing information to users.

        4. Profile & Appointment Management
        -Remind users to complete their profile settings, including medical history, allergies, medications, and immunization records.
        -Provide users with information about their appointments together with profile appointment link to view them.
        -Always check the appointment status (Such as: Pending, Incoming, Ongoing, Completed, Cancelled) when a user inquires about their appointments.

        5. Text Reply Formatting
        -Use plain text format.
        -Only include format that is given.

    Available Data & Information:

        List of Doctors:
        " . $list_of_doctor . "

        List of Links:
        1. <a href='https://docconnect.xscpry.com/user/appointment' class='fst-italic text-decoration-underline text-light'> docconnect.xscpry.com/user/appointment </a> - This page is for requesting appointment.
        2. <a href='https://docconnect.xscpry.com/user/doctors' class='fst-italic text-decoration-underline text-light'> docconnect.xscpry.com/user/doctors </a> - This page provide information about the doctors.
        3. <a href='https://docconnect.xscpry.com/user/about_us' class='fst-italic text-decoration-underline text-light'> docconnect.xscpry.com/user/about_us </a> - This page provide information about the website.
        4. <a href='https://docconnect.xscpry.com/user/services' class='fst-italic text-decoration-underline text-light'> docconnect.xscpry.com/user/services </a> - This page provide information about the services we offer.
        5. <a href='https://docconnect.xscpry.com/user/profile_appointment' class='fst-italic text-decoration-underline text-light'> docconnect.xscpry.com/user/profile_appointment </a> - This page is for viewing user's appointment list.
        6. <a href='https://docconnect.xscpry.com/user/profile_settings' class='fst-italic text-decoration-underline text-light'> docconnect.xscpry.com/user/profile_settings </a> - This page is for updating user's information.
        7. <a href='https://docconnect.xscpry.com/user/services' class='fst-italic text-decoration-underline text-light'> docconnect.xscpry.com/user/services </a>
        
        List of user's appointments:
        " . $appointmentList . "

        Current Date and Time: 
        " . $today . "
        ";

    $message = $user_message;

    $message_class = new Message();
    $messageArray = $message_class->load_chatbot_messages($_SESSION['account_id'], 0);

    if (!empty($messageArray)) {
        foreach ($messageArray as $item) {
            $role = ($item['message_type'] == 'user') ? 'user' : 'assistant';
            $chat_history[] = [
                "role" => $role,
                "content" => $item['message']
            ];
        }
    }

    $chat = $open_ai->chat([
        'model' => 'gpt-4o-mini',
        'messages' =>
        [
            [
                "role" => "system",
                "content" => $prompt
            ],
            ...$chat_history,
            [
                "role" => "user",
                "content" => $message
            ],
        ],
        'temperature' => 0.9,
        'max_tokens' => 150,
        'frequency_penalty' => 0,
        'presence_penalty' => 0.6,
    ]);

    $response_data = json_decode($chat, true);
    $response = $response_data["choices"][0]["message"]["content"];

    return $response;
}

$list_of_appointments = [];
$list_of_links = [];

if (empty($list_of_appointments)) {
    $appointment_message = "Currently, there are no appointments available in the system.";
} else {
    $appointment_message = "I can provide available dates and times for the next 7 days.";
}

// if ($list_of_links === "No Data") {
//     $links_message = "Currently, no links are available.";
// } else {
//     $links_message = "Here are some useful links: $list_of_links.";
// }
