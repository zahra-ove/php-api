<?php

require dirname(__DIR__) . '/vendor/autoload.php';
set_error_handler(['PHPApi\Code\ErrorHandler', 'handleError']);
set_exception_handler(['PHPApi\Code\ErrorHandler', 'handleException']);

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

header("Content-type: application/json; Charset=UTF-8");
