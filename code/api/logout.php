<?php

declare(strict_types=1);

use PHPApi\Code\Database;
use PHPApi\Code\JWTCodec;
use PHPApi\Code\RefreshTokenGateway;
use PHPApi\Code\UserGateway;

require __DIR__ . '/bootstrap.php';

if($_SERVER['REQUEST_METHOD'] != 'POST') {

    http_response_code(405);
    header('Allow:  POST');
    exit;
}

$data = (array) json_decode(file_get_contents("php://input"), true);

if( ! array_key_exists("token", $data)) {

    http_response_code(400);
    echo json_encode(["message" => "missing token"]);
    exit;
}

$codec = new JWTCodec($_ENV['SECRET_KEY']);

try {
    $payload = $codec->decode($data['token']);
} catch(Exception) {
    http_response_code(400);
    echo json_encode(["message" => "invalid token"]);
    exit;
}

$user_id = $payload['sub'];

$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

$token_gateway = new RefreshTokenGateway($database, $_ENV['SECRET_KEY']);

//$refresh_token = $token_gateway->getByToken($data['token']);
//if( $refresh_token === false) {
//    http_response_code(400);
//    echo json_encode(["message" => "refresh token is not in whilelist!"]);
//    exit;
//}

//$userGateway = new UserGateway($database);
//
//$user = $userGateway->getByID($user_id);
//
//if($user === false) {
//    http_response_code(401);
//    echo json_encode(["message" => "authentication failed"]);
//    exit;
//}

//require __DIR__ . '/tokens.php';

$token_gateway->delete($data['token']);



