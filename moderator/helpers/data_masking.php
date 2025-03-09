<?php
function maskEmail($email)
{
    $parts = explode("@", $email);
    $username = $parts[0];
    $domain = $parts[1];

    $usernameLength = strlen($username);

    if ($usernameLength > 4) {
        $maskedUsername = substr($username, 0, 2) . str_repeat('*', max(0, $usernameLength - 4)) . substr($username, -2);
    } elseif ($usernameLength > 2) {
        $maskedUsername = substr($username, 0, 2) . str_repeat('*', $usernameLength - 2);
    } else {
        $maskedUsername = str_repeat('*', $usernameLength);
    }

    return $maskedUsername . "@" . $domain;
}


function maskPhone($phone)
{
    if (empty($phone)) {
        return "";
    }
    $phoneLength = strlen($phone);

    if ($phoneLength <= 7) {
        return $phone;
    }

    return substr($phone, 0, 4) . str_repeat('*', max(0, $phoneLength - 7)) . substr($phone, -3);
}
