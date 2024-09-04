<?php

// using `file_get_contents` to consume an API
// using this function to read an api or website,requires that the `allow_url_fopen` setting in PHP be enabled.
// so better approach is to use `CURL` library.
// PHP supports `libcurl`

$response = file_get_contents('https://randomuser.me/api/');
$data = json_decode($response, true);

echo "<pre>";
var_export($data['results'][0]['gender']);
echo "</pre>";

exit();
