<?php

declare(strict_types=1);

use PHPApi\Code\Database;
use PHPApi\Code\JWTCodec;
use PHPApi\Code\RefreshTokenGateway;

require __DIR__ . '/bootstrap.php';

if($_SERVER['REQUEST_METHOD'] != 'POST') {

    http_response_code(405);
    header('Allow:  POST');
    exit;
}

$data = (array) json_decode(file_get_contents("php://input"), true);

if( ! array_key_exists("username", $data) ||
    ! array_key_exists("password", $data) ) {

    http_response_code(400);
    echo json_encode(["message" => "missing login credentials."]);
    exit;
}


$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
$database->getConnection();
$user_gateway = new \PHPApi\Code\UserGateway($database);

$user = $user_gateway->getByUsername($data["username"]);

if( $user === false) {

    http_response_code(401);
    echo json_encode(["message" => "invalid authentication1"]);
    exit;
}

if( ! password_verify($data["password"], $user["password"]) ) {

    http_response_code(401);
    echo json_encode(["message" => "invalid authentication2"]);
    exit;
}


$codec = new JWTCodec($_ENV['SECRET_KEY']);

require __DIR__ . '/tokens.php';


$token_gateway = new RefreshTokenGateway($database, $_ENV['SECRET_KEY']);
$token_gateway->create($refresh_token, $refresh_token_expiry);

