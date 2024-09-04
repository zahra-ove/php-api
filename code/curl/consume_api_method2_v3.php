<?php

$ch = curl_init();

$headers = [
    "Authorization: Client-ID blablabla"
];

curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api.unsplash.com/photos/random',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $headers,   // to send request http header, send them via this curl option
    CURLOPT_HEADER => true,      // to see all response headers, set this option to true
]);



$response = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // get status code


// we can get some important response headers via `curl_getinfo` method:
// there are some few http headers that we can get via this `curl_getinfo` function
// we can use `CURLOPT_HEADERFUNCTION` and call a callback to filter and customize response headers
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$content_length = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);


curl_close($ch);


echo 'status code: ' . $status_code , "<br>";
echo 'content type: ' . $content_type, "<br>";
echo 'content length: ' . $content_length, "<br>";
echo 'response: ' . $response, "<br>";