<?php
// Cek database dan table users
echo "<h1>🔍 Cek Database Dapoer Aisyah</h1>";

// Test connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=dapoer_aisyah;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "<p>✅ <strong>Database connection: SUCCESS</strong></p>";
} catch(PDOException $e) {
    echo "<p>❌ <strong>Database connection: FAILED</strong></p>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p><strong>🚨 SOLUSI: Pastikan database 'dapoer_aisyah' sudah dibuat!</strong></p>";
    exit;
}

// Check if users table exists
try {
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll();
    echo "<p>✅ <strong>Table 'users': EXISTS</strong></p>";
    
    echo "<h3>📋 Struktur Table Users:</h3>";
    echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
    echo "<tr style='background: #f8f9fa;'><th>Column</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    foreach($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch(PDOException $e) {
    echo "<p>❌ <strong>Table 'users': NOT FOUND</strong></p>";
    echo "<p><strong>🚨 SOLUSI: Jalankan script SQL untuk buat table users!</strong></p>";
    exit;
}

// Check existing users
try {
    $stmt = $pdo->query("SELECT id, name, email, role, is_active FROM users");
    $users = $stmt->fetchAll();
    
    echo "<h3>👥 Users yang sudah ada:</h3>";
    if (count($users) > 0) {
        echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
        echo "<tr style='background: #f8f9fa;'><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Active</th></tr>";
        foreach($users as $user) {
            $status = $user['is_active'] ? '✅' : '❌';
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['name']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['role']}</td>";
            echo "<td>{$status}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>⚠️ <strong>Belum ada users di database</strong></p>";
    }
    
} catch(PDOException $e) {
    echo "<p>❌ Error reading users: " . $e->getMessage() . "</p>";
}

// Test password hashing
echo "<h3>🔐 Test Password System:</h3>";
$testPassword = "admin123";
$hashedPassword = password_hash($testPassword, PASSWORD_DEFAULT);
$isValid = password_verify($testPassword, $hashedPassword);

echo "<p>Original: <code>$testPassword</code></p>";
echo "<p>Hashed: <code>" . substr($hashedPassword, 0, 50) . "...</code></p>";
echo "<p>Verification: " . ($isValid ? "✅ VALID" : "❌ INVALID") . "</p>";

echo "<h2>🎯 Status Database:</h2>";
echo "<div style='background: #d4edda; padding: 15px; border-radius: 8px;'>";
echo "<p style='color: #155724; margin: 0;'><strong>✅ Database siap untuk registration & login!</strong></p>";
echo "</div>";
?>
