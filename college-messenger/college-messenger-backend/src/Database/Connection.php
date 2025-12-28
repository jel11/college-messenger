<?php 

namespace App\Database;

use Exception;
use PDO;
use PDOException;

class Connection 
{
    private static ?PDO $instance = null;
    private function __construct() {}
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                $dsn = sprintf(
                        "pgsql:host=%s;port=%d;dbname=%s",
                        $_ENV['DB_HOST'],
                        $_ENV['DB_PORT'],
                        $_ENV['DB_NAME'],
                );

                self::$instance = new PDO(
                    $dsn,
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASSWORD'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                    );
            } catch (Exception $e) {
                error_log("Database connection error: " . $e->getMessage());
                throw new PDOException("Не удалось подключиться к базе данных");
            }
        }

        return self::$instance;
    }

    private function __clone(){}

    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize singleton');
    }

}
?>