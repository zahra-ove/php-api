<?php


// you can set `user agent` header via curl options  (line 15)
$ch = curl_init();

$headers = [
    "Authorization: Client-ID blablabla"
];

curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api.unsplash.com/photos/random',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_USERAGENT => 'zizi' // you can set `user agent` header via curl options
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