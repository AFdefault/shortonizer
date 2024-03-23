<?php

namespace App\Core;

final class Helpers
{
    private array $config;

    public function __construct()
    {
        $this->config = [
            'app_url' => 'http://localhost:8080',
        ];
    }

    public static function errorResponse(string $message, int $code = 500): void
    {
        http_response_code($code);
        echo $message;
        exit();
    }

    public function getConfig($key): string|array
    {
        if(!array_key_exists($key, $this->config)) {
            self::errorResponse('Config key '. $key .'not defined', 500);
        }
        return $this->config[$key];
    }

    public function jsonResponse(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode([
            $data
        ]);
    }
}