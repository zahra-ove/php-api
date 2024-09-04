<?php

namespace PHPApi\Code;

class Auth
{
    private int $user_id;
    public function __construct(Private UserGateway $userGateway)
    {
    }

    public function authenticateAPIKey(): bool
    {
        if( empty($_SERVER['HTTP_X_API_KEY']) ) {
            http_response_code(401);
            echo json_encode(["message" => "unauthenticated"]);
            return false;
        }

        $user = $this->userGateway->getByAPIKey($_SERVER['HTTP_X_API_KEY']);
        if( $user === false) {
            http_response_code(401);
            echo json_encode(["message" => "unauthenticated"]);
            return false;
        }

        $this->user_id = $user['id'];
        return true;
    }

    public function getUserId(): int | null
    {
        return $this->user_id;
    }
}