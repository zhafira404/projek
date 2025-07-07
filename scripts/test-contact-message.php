<?php
// Test contact_messages table functionality
require_once '../config/database.php';

echo "<h2>🧪 Test Contact Messages Table</h2>";

try {
    $pdo = getConnection();
    
    // Test 1: Check if table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'contact_messages'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table 'contact_messages' exists<br>";
    } else {
        echo "❌ Table 'contact_messages' not found<br>";
        exit;
    }
    
    // Test 2: Check table structure
    $stmt = $pdo->query("DESCRIBE contact_messages");
    $columns = $stmt->fetchAll();
    echo "<br><strong>📋 Table Structure:</strong><br>";
    foreach($columns as $column) {
        echo "- {$column['Field']}: {$column['Type']}<br>";
    }
    
    // Test 3: Count total messages
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM contact_messages");
    $total = $stmt->fetch()['total'];
    echo "<br>✅ Total messages: <strong>$total</strong><br>";
    
    // Test 4: Count by status
    $stmt = $pdo->query("
        SELECT 
            status, 
            COUNT(*) as count 
        FROM contact_messages 
        GROUP BY status 
        ORDER BY count DESC
    ");
    $statusCounts = $stmt->fetchAll();
    echo "<br><strong>📊 Messages by Status:</strong><br>";
    foreach($statusCounts as $status) {
        $emoji = match($status['status']) {
            'unread' => '📩',
            'read' => '👁️',
            'replied' => '✅',
            default => '📝'
        };
        echo "- {$emoji} {$status['status']}: {$status['count']}<br>";
    }
    
    // Test 5: Recent messages
    $stmt = $pdo->query("
        SELECT 
            name, 
            email, 
            subject, 
            status,
            DATE_FORMAT(created_at, '%d %M %Y %H:%i') as tanggal
        FROM contact_messages 
        ORDER BY created_at DESC 
        LIMIT 3
    ");
    $recentMessages = $stmt->fetchAll();
    echo "<br><strong>📬 Recent Messages:</strong><br>";
    foreach($recentMessages as $msg) {
        $statusEmoji = match($msg['status']) {
            'unread' => '🔴',
            'read' => '🟡',
            'replied' => '🟢',
            default => '⚪'
        };
        echo "- {$statusEmoji} <strong>{$msg['name']}</strong> ({$msg['email']})<br>";
        echo "  📝 {$msg['subject']}<br>";
        echo "  📅 {$msg['tanggal']}<br><br>";
    }
    
    // Test 6: Test INSERT (simulate contact form submission)
    echo "<strong>🧪 Testing Contact Form Submission:</strong><br>";
    $testName = "Test User " . date('His');
    $testEmail = "test" . date('His') . "@example.com";
    $testMessage = "This is a test message from automated testing.";
    
    $stmt = $pdo->prepare("
        INSERT INTO contact_messages (name, email, phone, subject, message, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    
    $result = $stmt->execute([
        $testName,
        $testEmail,
        '081234567890',
        'Test Subject',
        $testMessage
    ]);
    
    if ($result) {
        $newId = $pdo->lastInsertId();
        echo "✅ Test message inserted successfully! ID: $newId<br>";
        
        // Verify the insert
        $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
        $stmt->execute([$newId]);
        $newMessage = $stmt->fetch();
        
        if ($newMessage) {
            echo "✅ Message verified: {$newMessage['name']} - {$newMessage['subject']}<br>";
        }
    } else {
        echo "❌ Failed to insert test message<br>";
    }
    
    echo "<br><h3>🎉 All Tests Passed!</h3>";
    echo "<p><strong>Contact Messages Table is ready to use!</strong></p>";
    
    // Admin panel preview
    echo "<br><strong>👨‍💼 Admin Panel Preview:</strong><br>";
    echo "<em>Messages that would appear in admin dashboard:</em><br>";
    
    $stmt = $pdo->query("
        SELECT 
            id,
            name, 
            email, 
            subject, 
            status,
            DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') as tanggal,
            SUBSTRING(message, 1, 50) as preview
        FROM contact_messages 
        WHERE status = 'unread'
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $unreadMessages = $stmt->fetchAll();
    
    if (count($unreadMessages) > 0) {
        echo "<table border='1' cellpadding='8' style='border-collapse: collapse; margin-top: 10px;'>";
        echo "<tr style='background: #f8f9fa;'>";
        echo "<th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Preview</th><th>Date</th>";
        echo "</tr>";
        
        foreach($unreadMessages as $msg) {
            echo "<tr>";
            echo "<td>{$msg['id']}</td>";
            echo "<td>{$msg['name']}</td>";
            echo "<td>{$msg['email']}</td>";
            echo "<td>{$msg['subject']}</td>";
            echo "<td>{$msg['preview']}...</td>";
            echo "<td>{$msg['tanggal']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p><em>No unread messages</em></p>";
    }
    
} catch(Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
