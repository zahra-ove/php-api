<?php

$ch = curl_init();

$headers = [
    "Authorization: Client-ID blablabla"
];

// customize and filter out response headers
$response_headers = [];
$header_callback = function($ch, $header) use (&$response_headers) {
    $len = strlen($header);

    $parts = explode(':', $header, 2);
    if(count($parts) < 2)
        return $len;

    $response_headers[$parts[0]] = trim($parts[1]);
    return $len;
};

curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api.unsplash.com/photos/random',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $headers,   // to send request http header, send them via this curl option
    CURLOPT_HEADERFUNCTION => $header_callback,    // customize and return all or some part of response headers
]);



$response = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // get status code


// we can get some important response headers via `curl_getinfo` method:
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$content_length = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);


curl_close($ch);


echo 'status code: ' . $status_code , "<br>";
echo 'content type: ' . $content_type, "<br>";
echo 'content length: ' . $content_length, "<br>";
print_r($response_headers);
echo 'response: ' . $response, "<br>";