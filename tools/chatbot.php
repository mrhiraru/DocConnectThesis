<?php
session_start();

require_once('../vendor/autoload.php');
require_once('../classes/account.class.php');
require_once('../classes/appointment.class.php');
require_once('../classes/message.class.php');

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
            $list_of_doctor .= $doctorKey + 1 . ". " . $doctorItem['name'] . " - " . $doctorItem['specialty'] . "( " . $doctorItem['working_days'] . " " . $doctorItem["working_time"] . " )" . "\n";
        }
    }

    $prompt = "You are an assistant bot for a clinic website. Your responsibilities are as follows:

            1. Answer only simple medical questions related to the user's symptoms without giving any medical conclusions.
            2. Provide information of doctors, date, time, days, and availability from this list: \n" . $list_of_doctor . ". 
            3. Recommend a doctor from ///list_of_doctors based on the symptoms provided by the user, provide the available date and time of the recommended doctor for the next 7 days based on the ////list_of_appointments.
            4. Provide links to the appointment page or other related pages (if available), but no other pages.
            
            Only provide response based on the given data or information, don't make random information.
            If the user asks anything outside of these responsibilities, politely inform them that you are unable to assist with that request. Additionally, if the answer to the user's query is not provided in the available data, politely inform them that there is no data available for their query.";

    $new_prompt = "
    
    You are an assitant bot for Telehealth website Docconnect. 
    
    Your responsibilities are as follows:

        Responsibility 1:
        -Inform users about your responsibilities you can do to help them.
        -Answer only simple medical related questions related to user's symptoms.
        -Do not give any medical conclusions.
        -Do not answer queries that is not related to your responsibilities.
        -Politely inform them that you are unable to assist with the request outside your responsibilities.
        -Do not make random answer that is not provided for this role.

        Responsibility 2:
        -Recommend a doctor that might be able to help the user.
        -Provide information about the available doctors.
        -Provide the date, time, and day which doctors is available.
        -Provide the name and specialty of the doctor that is related to user's symptoms.
        -Inform users about the availability of doctor that they possibly need.
        -Inform user if the date and time they ask has available doctor.
        -Suggest other possible doctor that can help them if there is no exact doctor for their symptoms.

        Responsibility 3:
        -Assist user navigate through the website by providing links related to their queries.
        -Make sure to provide link how it is formatted.
        -Do not include the text outside the <a> </a> tag.

        Responsibility 4:
        -Inform user to provide and fill up his/her relevant information on profile settings.
        -Ask user to provide his medical history, allergies, medications, immunization on profile settings.
        -Provide user information about his/her appointments and the link where he/she can view it.
        
    These are data and informations that you can provide:

        List of Doctors:
        " . $list_of_doctor . "

        List of Links:
        1. <a href='https://docconnect.xscpry.com/user/appointment' class='font-italic text-decoration-underline'> https://docconnect.xscpry.com/user/appointment </a> - This page is for requesting appointment.
        2. <a href='https://docconnect.xscpry.com/user/doctors' class='font-italic text-decoration-underline'> https://docconnect.xscpry.com/user/doctors </a> - This page provide information about the doctors.
        3. <a href='https://docconnect.xscpry.com/user/about_us' class='font-italic text-decoration-underline'> https://docconnect.xscpry.com/user/about_us </a> - This page provide information about the website.
        4. <a href='https://docconnect.xscpry.com/user/services' class='font-italic text-decoration-underline'> https://docconnect.xscpry.com/user/services </a> - This page provide information about the services we offer.
        5. <a href='https://docconnect.xscpry.com/user/profile_appointment' class='font-italic text-decoration-underline'> https://docconnect.xscpry.com/user/profile_appointment </a> - This page is for viewing user's appointment list.
        6. <a href='https://docconnect.xscpry.com/user/profile_settings' class='font-italic text-decoration-underline'> https://docconnect.xscpry.com/user/profile_settings </a> - This page is for updating user's information.
        7. <a href='https://docconnect.xscpry.com/user/services' class='font-italic text-decoration-underline'> https://docconnect.xscpry.com/user/services </a>
        
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
                "content" => $new_prompt
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
