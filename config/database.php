<?php
// Database configuration for Dapoer Aisyah
define('DB_HOST', 'localhost');
define('DB_NAME', 'dapoer_aisyah');  // Pastikan nama database sama
define('DB_USER', 'root');
define('DB_PASS', '');

function getConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch(PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Test connection
try {
    $pdo = getConnection();
    // echo "Database connected successfully!";
} catch(Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>
