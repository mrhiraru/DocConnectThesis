<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apiKey = 'sk-svcacct-dOb_EQn7fHZJKYW2TDmmrxDTgMu_JfsItDdStAOe8BEMDY880di1kwyVSZmIQDn7mT3BlbkFJabD6Zei8bZsG3B_zicdcjU3CACg3wFmd-w0x8ZT35yoyERvy6FNJdqSPmGJ-zATAA';  // Replace with your OpenAI API key
    $data = json_decode(file_get_contents('php://input'), true);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: ' . 'Bearer ' . $apiKey
    ]);

    $result = curl_exec($ch);
    curl_close($ch);

    echo $result;
}
?>
