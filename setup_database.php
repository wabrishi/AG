<?php

$dbPath = __DIR__ . '/database.sqlite';

// Remove existing DB file to start fresh
if (file_exists($dbPath)) {
    unlink($dbPath);
}

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents(__DIR__ . '/database.sql');

    // Translate MySQL AUTO_INCREMENT to SQLite AUTOINCREMENT
    // SQLite uses "INTEGER PRIMARY KEY AUTOINCREMENT"
    $sql = str_replace('INT AUTO_INCREMENT PRIMARY KEY', 'INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
    $sql = str_replace('INTEGER PRIMARY KEY AUTO_INCREMENT', 'INTEGER PRIMARY KEY AUTOINCREMENT', $sql);

    // SQLite can execute multiple statements in one go usually
    $pdo->exec($sql);

    echo "Database setup successfully.\n";

    // Create uploads directory if it doesn't exist
    $uploadDir = __DIR__ . '/public/uploads';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
        echo "Created uploads directory.\n";
    }

    // Check if admin user exists, if not create one
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = 'admin@example.com'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES ('Admin User', 'admin@example.com', ?, 'admin')");
        $stmt->execute([$password]);
        echo "Admin user created (admin@example.com / admin123).\n";
    }

} catch (PDOException $e) {
    die("DB Setup Failed: " . $e->getMessage() . "\n");
}
