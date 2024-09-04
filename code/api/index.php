<?php

declare(strict_types=1);

use PHPApi\Code\Database;
use PHPApi\Code\TaskController;
use PHPApi\Code\TaskGateway;

require __DIR__ . '/bootstrap.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', $path);
$resource = $parts[2] ?? null;
$id = $parts[3] ?? null;


$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
$database->getConnection();
$auth = new \PHPApi\Code\Auth( new \PHPApi\Code\UserGateway($database));

if(! $auth->authenticateAPIKey() ) {
    exit;
}

$user_id = $auth->getUserId();

$task_gateway = new TaskGateway($database);

$controller = new TaskController($task_gateway, $user_id);
echo $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);