<?php 

namespace App\Middleware;

class CorsMiddleware
{
    public static function handle(): bool
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        $allowedOrigins = [
            'http://localhost:4200',
            'http://localhost:3000',
            $_ENV['APP_URL'] ?? ''
        ];

        if (in_array($origin, $allowedOrigins)) {
            header("Access-Control-Allow-Origin: $origin");
        }

        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Max-Age: 86400');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit(0);
        }

        return true;
    }
}

?>