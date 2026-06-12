<?php

require_once 'twilio_config.php';

function normalizePhone($phone) {

    $phone = trim($phone);

    $phone = str_replace(
        [' ', '-', '(', ')'],
        '',
        $phone
    );

    if (strpos($phone, '+') === 0) {
        return $phone;
    }

    if (strpos($phone, '0') === 0) {
        return '+38' . $phone;
    }

    if (strpos($phone, '380') === 0) {
        return '+' . $phone;
    }

    return $phone;
}

function sendSms($to, $message) {

    $to = normalizePhone($to);

    $url = 'https://api.twilio.com/2010-04-01/Accounts/' 
        . TWILIO_SID 
        . '/Messages.json';

    $data = [
        'From' => TWILIO_FROM,
        'To' => $to,
        'Body' => $message
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        http_build_query($data)
    );

    curl_setopt(
        $ch,
        CURLOPT_USERPWD,
        TWILIO_SID . ':' . TWILIO_TOKEN
    );

    curl_setopt(
        $ch,
        CURLOPT_RETURNTRANSFER,
        true
    );

    $response = curl_exec($ch);

    $error = curl_error($ch);

    $httpCode = curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );

    curl_close($ch);

    file_put_contents(
        'sms_log.txt',
        date('Y-m-d H:i:s')
        . " | TO: $to | HTTP: $httpCode | RESPONSE: $response | ERROR: $error\n",
        FILE_APPEND
    );

    return $httpCode >= 200 && $httpCode < 300;
}
?>