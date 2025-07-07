<?php
/**
 * Script untuk setup database secara otomatis
 * Jalankan script ini untuk membuat database dan insert data sample
 */

// Configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'dapoer_aisyah';

// SQL files to execute in order
$sqlFiles = [
    '01-create-database.sql',
    '02-insert-sample-data.sql', 
    '03-insert-orders-reviews.sql'
];

try {
    echo "🚀 Starting database setup...\n\n";
    
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to MySQL server\n";
    
    // Execute each SQL file
    foreach ($sqlFiles as $index => $file) {
        echo "\n📁 Executing $file...\n";
        
        $filePath = __DIR__ . '/' . $file;
        
        if (!file_exists($filePath)) {
            throw new Exception("File not found: $filePath");
        }
        
        $sql = file_get_contents($filePath);
        
        // Split SQL by semicolon and execute each statement
        $statements = explode(';', $sql);
        
        $executedCount = 0;
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                try {
                    $pdo->exec($statement);
                    $executedCount++;
                } catch (PDOException $e) {
                    // Skip "database exists" and similar warnings
                    if (strpos($e->getMessage(), 'already exists') === false) {
                        echo "⚠️  Warning in statement: " . substr($statement, 0, 50) . "...\n";
                        echo "   Error: " . $e->getMessage() . "\n";
                    }
                }
            }
        }
        
        echo "   ✅ Executed $executedCount statements\n";
    }
    
    // Verify setup
    echo "\n🔍 Verifying database setup...\n";
    
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "   📊 Created " . count($tables) . " tables\n";
    
    // Check sample data
    $checks = [
        'users' => 'SELECT COUNT(*) FROM users',
        'categories' => 'SELECT COUNT(*) FROM categories', 
        'products' => 'SELECT COUNT(*) FROM products',
        'orders' => 'SELECT COUNT(*) FROM orders',
        'reviews' => 'SELECT COUNT(*) FROM reviews'
    ];
    
    foreach ($checks as $table => $query) {
        $stmt = $pdo->query($query);
        $count = $stmt->fetchColumn();
        echo "   📋 $table: $count records\n";
    }
    
    echo "\n🎉 Database setup completed successfully!\n\n";
    
    echo "📝 Login credentials:\n";
    echo "   Admin: admin@dapoerisyah.com / admin123\n";
    echo "   Manager: manager@dapoerisyah.com / admin123\n";
    echo "   Customer: budi@example.com / password123\n\n";
    
    echo "🔗 You can now access:\n";
    echo "   Website: http://localhost/dapoer-aisyah/\n";
    echo "   Admin: http://localhost/dapoer-aisyah/admin/\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
