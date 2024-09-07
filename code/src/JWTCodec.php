<?php

namespace PHPApi\Code;



use InvalidArgumentException;
use PHPApi\Code\Exceptions\InvalidSignatureException;

class JWTCodec
{
    public function __construct(private string $key)
    {
    }

    public function encode(array $payload): string
    {
        $header = [
            "typ"  => "JWT",
            "algo" => "HS256"
        ];

        $header = $this->base64UrlEncoded(json_encode($header));
        $payload = $this->base64UrlEncoded(json_encode($payload));

        $signature = $this->base64UrlEncoded(hash_hmac(
                'sha256',
                $header . '.' . $payload,
                     $this->key,
            true
        ));

        return $header . '.' . $payload . '.' . $signature;
    }

    public function decode(string $jwtToken): array
    {
        if( preg_match('/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/', $jwtToken, $matches) !== 1) {
            throw new InvalidArgumentException('JWT Token is invalid');
        }

        $expected_signature = hash_hmac(
            'sha256',
            $matches['header'] . '.' . $matches['payload'],
            $this->key,
            true
        );

        $received_signature = $this->base64UrlDecode($matches['signature']);
        if( ! hash_equals($expected_signature, $received_signature)) {
            throw new InvalidSignatureException;
        }

        return json_decode($this->base64UrlDecode($matches['payload']), true);
    }

    private function base64UrlEncoded(string $text): string
    {
        return str_replace(
            ['+','/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }

    private function base64UrlDecode(string $text): string
    {
        return base64_decode(str_replace(
            ['-', '_', ''],
            ['+', '/', '='],
            $text)
        );
    }
}