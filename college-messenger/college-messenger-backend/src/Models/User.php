<?php 

    namespace App\Models;

    use App\Database\Connection;
    use PDO;

    class User
    {
        private PDO $db;

        public function __construct()
        {
            $this->db = Connection::getInstance();
        }

        public function getAll(): array
        {
            $stmt = $this->db->query("
                SELECT id, email, full_name, role, created_at
                FROM users
                ORDER BY created_at DESC
            ");

            return $stmt->fetchAll();
        }
    }
?>