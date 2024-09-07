<?php

declare(strict_types=1);

use PHPApi\Code\Auth;
use PHPApi\Code\Database;
use PHPApi\Code\JWTCodec;
use PHPApi\Code\TaskController;
use PHPApi\Code\TaskGateway;
use PHPApi\Code\UserGateway;

require __DIR__ . '/bootstrap.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', $path);
$resource = $parts[2] ?? null;
$id = $parts[3] ?? null;

$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
$database->getConnection();

$userGateway = new UserGateway($database);
$codec = new JWTCodec($_ENV['SECRET_KEY']);
$auth = new Auth($userGateway, $codec);

if(! $auth->authenticateAccessToken() ) {
    exit;
}

$user_id = $auth->getUserId();

$task_gateway = new TaskGateway($database);

$controller = new TaskController($task_gateway, $user_id);
echo $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);