<?php

// ...in functions.php
add_action('wpcf7_mail_sent', function ($cf7) {
    // Run code after the email has been sent
});

// ENCRIPT HEADER
function wsse_header($email, $apiKey)
{
    $nonce = hash_hmac('sha512', uniqid(null, true), uniqid(), true);
    $created = new \DateTime('now', new \DateTimezone('UTC'));
    $created = $created->format(\DateTime::ISO8601);
    $digest = sha1($nonce . $created . $apiKey, true);
    return sprintf(
        'X-WSSE: UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
        $email,
        base64_encode($digest),
        base64_encode($nonce),
        $created
    );
}

// SEND TO UNMAZE
function saveToUnmaze($userName, $userEmail, $userPhoneNumber, $userCity)
{
    $email = "info@medicapilar.pt";
    $apiKey = "da35a5dbde5c6ed08fafbf0f9a3665a23b435752";
    $url = "https://medicapilar.unmaze.io/api/rest/latest/leads.json?_format=json";
    $headers = array('Content-Type: application/json', 'Accept: application/json');
    $headers[] = wsse_header($email, $apiKey);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $fields = json_encode(array('name' => $userName, 'email' => $userEmail, 'phoneNumber' => $userPhoneNumber, 'cidade_2' => $userCity, 'origem_campanha' => 'Site'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_POST, true);

    $response = curl_exec($ch);
    curl_close($ch);
}

// SAVE TO DATABASE
function saveLead($nome, $email, $telefone, $cidade)
{
    $db = new MysqliDb(array(
        'host' => 'localhost',
        'username' => 'camedica_medicap',
        'password' => 'w1WeL4fVMAqs',
        'db' => 'camedica_medicapilar',
        'charset' => 'utf8'
    ));

    $data = array(
        'nome' => $nome,
        'email' => $email,
        'telefone' => $telefone,
        'cidade' => $cidade
    );
    $db->insert("leads", $data);
}
