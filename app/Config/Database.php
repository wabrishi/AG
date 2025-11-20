<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            // Use SQLite for development/sandbox, MySQL for production
            // In a real app, these would come from .env
            $dbDriver = getenv('DB_DRIVER') ?: 'sqlite'; // 'mysql' or 'sqlite'

            if ($dbDriver === 'sqlite') {
                $dbPath = __DIR__ . '/../../database.sqlite';
                $this->pdo = new PDO("sqlite:$dbPath");
            } else {
                $host = getenv('DB_HOST') ?: 'localhost';
                $db   = getenv('DB_NAME') ?: 'ecommerce_db';
                $user = getenv('DB_USER') ?: 'root';
                $pass = getenv('DB_PASS') ?: '';
                $charset = 'utf8mb4';
                $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
                $this->pdo = new PDO($dsn, $user, $pass);
            }

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
