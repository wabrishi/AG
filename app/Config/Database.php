<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            // =================================================================
            // DATABASE CONFIGURATION
            // =================================================================

            // Set this to 'mysql' for Hostinger/Production
            $dbDriver = 'sqlite'; // Options: 'sqlite', 'mysql'

            // MySQL Credentials (Fill this in for Hostinger)
            $mysqlConfig = [
                'host' => 'localhost',
                'dbname' => 'u123456789_database_name',
                'user' => 'u123456789_database_user',
                'password' => 'YourPasswordHere'
            ];

            // Environment Variable Overrides (Optional)
            if (getenv('DB_DRIVER')) $dbDriver = getenv('DB_DRIVER');

            // =================================================================

            if ($dbDriver === 'sqlite') {
                $dbPath = __DIR__ . '/../../database.sqlite';
                $this->pdo = new PDO("sqlite:$dbPath");
            } else {
                $host = getenv('DB_HOST') ?: $mysqlConfig['host'];
                $db   = getenv('DB_NAME') ?: $mysqlConfig['dbname'];
                $user = getenv('DB_USER') ?: $mysqlConfig['user'];
                $pass = getenv('DB_PASS') ?: $mysqlConfig['password'];

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
