<?php

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => 'https://randomuser.me/api/',
    CURLOPT_RETURNTRANSFER => true
]);

$response = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // get status code
curl_close($ch);


echo $status_code , "<br>";
echo $response, '\n';