<?php

declare(strict_types=1);

use PHPApi\Code\Database;
use PHPApi\Code\TaskController;
use PHPApi\Code\TaskGateway;

require dirname(__DIR__) . '/vendor/autoload.php';
set_error_handler(['PHPApi\Code\ErrorHandler', 'handleError']);
set_exception_handler(['PHPApi\Code\ErrorHandler', 'handleException']);

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', $path);
$resource = $parts[2] ?? null;
$id = $parts[3] ?? null;



header("Content-type: application/json; Charset=UTF-8");



$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
$database->getConnection();

$task_gateway = new TaskGateway($database);

$controller = new TaskController($task_gateway);
echo $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);