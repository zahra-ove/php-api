<?php

namespace PHPApi\Code;

class ErrorHandler
{
    public static function handleError(
        int $errono,
        string $errstr,
        string $errfile,
        int $errline): void
    {
        throw new \ErrorException($errstr, 0, $errono, $errfile, $errline);
    }

    public static function handleException(\Throwable $exception): void
    {
//        http_response_code(500);
        echo json_encode([
            "code"    => $exception->getCode(),
            "message" => $exception->getMessage(),
            "file"    => $exception->getFile(),
            "line"    => $exception->getline(),
            "trace"   => $exception->getTrace()
        ]);
    }
}