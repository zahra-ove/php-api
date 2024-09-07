<?php

namespace PHPApi\Code;

use PHPApi\Code\Exceptions\InvalidSignatureException;
use PHPApi\Code\Exceptions\TokenExpiredException;

class Auth
{
    private int $user_id;
    public function __construct(private UserGateway $userGateway,
                                private JWTCodec $codec)
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

    public function authenticateAccessToken(): bool
    {
        if( ! preg_match("/^Bearer\s+(.*)$/", $_SERVER['HTTP_AUTHORIZATION'], $matches) ) {
            http_response_code(400);
            echo json_encode(["message" => "incomplete authorization header"]);
            return false;
        }

        try {
            $payload = $this->codec->decode($matches[1]);

        } catch(InvalidSignatureException) {
            http_response_code(401);
            echo json_encode(['message' => 'invalid signature.']);
            return false;

        } catch(TokenExpiredException) {

            http_response_code(401);
            echo json_encode(['message' => 'token has expired.']);
            return false;

        } catch(\Exception $e) {

            http_response_code(400);
            echo json_encode(['message' => $e->getMessage()]);
            return false;
        }

        $this->user_id = $payload['sub'];
        return true;
    }
}