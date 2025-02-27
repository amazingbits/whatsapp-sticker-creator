<?php

namespace Src\Core;

class ErrorHandler
{
    public static function register(): void
    {
        set_exception_handler([self::class, "handleException"]);
        set_error_handler([self::class, "handleError"]);
    }

    public static function handleException($exception): void
    {
        $statusCode = ($exception instanceof CustomError) ? $exception->getCode() : 500;
        self::errorResponse($statusCode, $exception->getMessage());
    }

    public static function handleError($severity, $message, $file, $line)
    {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    private static function errorResponse(int $statusCode, string $message): void
    {
        http_response_code($statusCode);

        echo json_encode([
            "status" => $statusCode,
            "error" => self::getErrorDescription($statusCode),
            "message" => $message
        ], JSON_PRETTY_PRINT);

        exit;
    }

    private static function getErrorDescription(int $statusCode): string
    {
        $erros = [
            400 => "Request is invalid",
            401 => "Not authorized",
            403 => "Access denied",
            404 => "Not found",
            500 => "Server internal error"
        ];

        return $erros[$statusCode] ?? "Unknown error";
    }
}