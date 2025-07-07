<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    echo json_encode([]);
    exit;
}

$query = cleanInput($_GET['q']);

try {
    $pdo = getConnection();
    
    $stmt = $pdo->prepare("
        SELECT p.id, p.name, p.price, p.image, c.name as category_name
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.is_active = 1 
        AND (p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?)
        ORDER BY p.name ASC 
        LIMIT 5
    ");
    
    $searchTerm = "%$query%";
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    $results = $stmt->fetchAll();
    
    echo json_encode($results);
    
} catch(PDOException $e) {
    echo json_encode([]);
}
?>
