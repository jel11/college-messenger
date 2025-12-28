<?php 

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthMiddleware
{
    public static function handle()
    {
        $headers = getallheaders();

        $authHeader = $headers['Authorization'] ?? '';

        if (!preg_match('/Bearer\s(\S=)/', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized - Token missing']);
            return false;
        }

        $token = $matches[1];

        try {
            $decoded = JWT::decode(
                $token,
                new Key($_ENV['JWT_SECRET'], 'HS256')
            );

            $GLOBALS['current_user'] = $decoded;

            return $decoded;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode([
                'error' => 'Unauthorized - Invalid token',
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public static function getCurrentUser()
    {
        return $GLOBALS['current_user'] ?? null;
    }
}

?>