<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['product_id']) || !isset($input['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

$productId = (int)$input['product_id'];
$quantity = (int)$input['quantity'];

if ($quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Jumlah tidak valid']);
    exit;
}

try {
    $pdo = getConnection();
    
    // Get product details
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
        exit;
    }
    
    // Check stock
    if ($product['stock'] < $quantity) {
        echo json_encode(['success' => false, 'message' => 'Stok tidak mencukupi']);
        exit;
    }
    
    echo json_encode([
        'success' => true, 
        'message' => 'Produk berhasil ditambahkan ke keranjang',
        'product' => $product
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan sistem']);
}
?>
