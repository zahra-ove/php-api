<?php


// post request
$ch = curl_init();

$headers = [
    "Authorization: Client-ID blablabla"
];

$payload = json_encode([
    "name" => "zizi",
    "age" => "36"
]);

curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api.unsplash.com/photos/random',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $headers,
//    CURLOPT_CUSTOMREQUEST => "POST",    // set method as post (solution 1)
    CURLOPT_POST => true,    // set method as post (solution 2)
    CURLOPT_POSTFIELDS => $payload,     // send payload
]);

$response = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // get status code

curl_close($ch);


echo 'status code: ' . $status_code , "<br>";
echo 'response: ' . $response, "<br>";