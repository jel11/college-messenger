<?php 

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Database\Connection;
use App\Services\ValidationService;
use Firebase\JWT\JWT;
use PDO;

class AuthController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function register(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $errors = ValidationService::validateRegistration($data);

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");

        $stmt->execute(['email' => $data['email']]);

        if ($stmt->fetch()) {
            http_response_code(409);
            echo json_encode(['error' => 'Email already exists']);
            return;
        }

        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (email, pasword_hash, full_name, role, created_at)
                VALUES (:email, :password_hash, :full_name, 'student', NOW())
                RETURNING id, email, full_name, role, created_at
                ");

                $stmt->execute([
                    'email' => $data['email'],
                    'password_hash' => $passwordHash,
                    'full_name' => $data['full_name']
                ]);

                $user = $stmt->fetch();

                $token = $this->generateToken($user);
            
                http_response_code(201);
                echo json_encode([
                    'message' => 'User registered successfully',
                    'token' => $token,
                    'user' => [
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'full_name' => $user['full_name'],
                        'role' => $user['role']
                    ]
                    
                    ]);
            } catch (\PDOException $e) {
                error_log("Registration error: " . $e->getMessage());

                http_response_code(500);
                echo json_encode(['error' => 'Registration failed']);
            }
     }

     public function login(): void
     {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Email and password are required']);
            return;
        }

        $stmt = $this->db->prepare("
            SELECT id, email, password_hash, full_name, role
            FROM users
            WHERE email = :email
        ");

        $stmt->execute(['email' => $data['email']]);

        $user = $stmt->fetch();

        if (!$user || !password_verify($data['password'],
        $user['password_hash'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            return;
        }

        $token = $this->generateToken($user);

        http_response_code(200);
        echo json_encode([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'full_name' => $user['full_name'],
                'role' => $user['role']
            ]
            ]);

     }

     public function logout(): void
     {
        http_response_code(200);
        echo json_encode([
            'message' => 'Logout successful'
        ]);

        $userId = AuthMiddleware::getCurrentUser()->sub;
        error_log("User $userId logged out");
     }

     private function generateToken(array $user): string
     {
        $payload = [
            'iat' => time(),
            'exp' => time() + (int)$_ENV['JWT_EXPIRATION'],
            'sub' => $user['id'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'role' => $user['role']
        ];

        return JWT::encode(
            $payload,
            $_ENV['JWT_SECRET'],
            'HS256'
        );
     }
}

?>