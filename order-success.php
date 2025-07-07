<?php
session_start();
$pageTitle = "Pesanan Berhasil - Dapoer Aisyah";
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login-page.php');
    exit;
}

$orderId = $_GET['order_id'] ?? null;
if (!$orderId) {
    header('Location: index.php');
    exit;
}

try {
    $pdo = getConnection();
    
    // Get order details
    $stmt = $pdo->prepare("
        SELECT o.*, u.name as customer_name, u.email as customer_email
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.id = ? AND o.user_id = ?
    ");
    $stmt->execute([$orderId, $_SESSION['user_id']]);
    $order = $stmt->fetch();
    
    if (!$order) {
        echo "Order not found. Order ID: $orderId, User ID: " . $_SESSION['user_id'];
        exit; // Debug: jangan langsung redirect
    }
    
    // Get order items - FIX: gak perlu JOIN ke products
    $stmt = $pdo->prepare("
        SELECT * FROM order_items 
        WHERE order_id = ?
        ORDER BY id
    ");
    $stmt->execute([$orderId]);
    $orderItems = $stmt->fetchAll();
    
} catch(Exception $e) {
    // Debug: tampilkan error instead of redirect
    echo "Error: " . $e->getMessage();
    echo "<br>Order ID: $orderId";
    echo "<br>User ID: " . ($_SESSION['user_id'] ?? 'not set');
    exit;
}

require_once 'includes/header.php';
?>

<main>
    <section class="success-section">
        <div class="container">
            <div class="success-content">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                
                <h1>Pesanan Berhasil Dibuat!</h1>
                <p class="success-subtitle">Terima kasih atas kepercayaan Anda. Pesanan Anda sedang diproses.</p>
                
                <div class="order-info">
                    <div class="order-number">
                        <strong>Nomor Pesanan: #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></strong>
                    </div>
                    <div class="order-date">
                        Dibuat pada: <?php echo date('d F Y, H:i', strtotime($order['created_at'])); ?>
                    </div>
                    <div class="order-total">
                        <strong>Total: Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></strong>
                    </div>
                </div>
                
                <!-- Tampilkan detail pesanan -->
                <div class="order-details">
                    <h3>Detail Pesanan:</h3>
                    <div class="customer-info">
                        <p><strong>Nama:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                        <p><strong>Telepon:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                        <p><strong>Alamat:</strong> <?php echo htmlspecialchars($order['delivery_address']); ?></p>
                        <p><strong>Pembayaran:</strong> <?php echo strtoupper($order['payment_method']); ?></p>
                    </div>
                    
                    <?php if (!empty($orderItems)): ?>
                    <div class="order-items">
                        <h4>Item Pesanan:</h4>
                        <?php foreach ($orderItems as $item): ?>
                        <div class="item-row">
                            <span><?php echo htmlspecialchars($item['product_name']); ?></span>
                            <span><?php echo $item['quantity']; ?>x</span>
                            <span>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="next-steps">
                    <h3>Langkah Selanjutnya:</h3>
                    <div class="steps">
                        <div class="step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h4>Konfirmasi Pesanan</h4>
                                <p>Tim kami akan menghubungi Anda dalam 1-2 jam untuk konfirmasi</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4>Pembayaran</h4>
                                <p>Lakukan pembayaran sesuai metode yang dipilih</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h4>Pengiriman</h4>
                                <p>Pesanan akan dikirim sesuai jadwal yang telah ditentukan</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="menu.php" class="btn btn-outline">
                        <i class="fas fa-utensils"></i>
                        Pesan Lagi
                    </a>
                    <a href="index.php" class="btn btn-primary">
                        <i class="fas fa-home"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.success-section {
    min-height: 80vh;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 4rem 0;
}

.success-content {
    text-align: center;
    max-width: 700px;
    margin: 0 auto;
    background: white;
    padding: 3rem;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.success-icon {
    font-size: 4rem;
    color: #28a745;
    margin-bottom: 2rem;
    animation: successPulse 2s infinite;
}

@keyframes successPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.success-content h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #333;
}

.success-subtitle {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 2rem;
}

.order-info {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.order-number {
    font-size: 1.3rem;
    color: #2c5530;
    margin-bottom: 0.5rem;
}

.order-date {
    color: #666;
    margin-bottom: 0.5rem;
}

.order-total {
    font-size: 1.2rem;
    color: #2c5530;
}

.order-details {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    text-align: left;
}

.order-details h3 {
    text-align: center;
    margin-bottom: 1.5rem;
    color: #333;
}

.customer-info {
    margin-bottom: 1.5rem;
}

.customer-info p {
    margin-bottom: 0.5rem;
    color: #333;
}

.order-items h4 {
    margin-bottom: 1rem;
    color: #333;
}

.item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #ddd;
}

.item-row:last-child {
    border-bottom: none;
}

.next-steps h3 {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    color: #333;
}

.steps {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.step {
    display: flex;
    align-items: center;
    gap: 1rem;
    text-align: left;
}

.step-number {
    width: 40px;
    height: 40px;
    background: #2c5530;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    flex-shrink: 0;
}

.step-content h4 {
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
    color: #333;
}

.step-content p {
    color: #666;
    font-size: 0.95rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(44, 85, 48, 0.3);
}

.btn-outline {
    background: white;
    color: #2c5530;
    border: 2px solid #2c5530;
}

.btn-outline:hover {
    background: #2c5530;
    color: white;
}

@media (max-width: 768px) {
    .success-content {
        padding: 2rem;
        margin: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .item-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}
</style>

<script>
// Clear cart setelah berhasil checkout
localStorage.removeItem('cart');

// Debug info
console.log('Order Success Page Loaded');
console.log('Order ID:', <?php echo json_encode($orderId); ?>);
console.log('User ID:', <?php echo json_encode($_SESSION['user_id'] ?? null); ?>);
</script>

<?php require_once 'includes/footer.php'; ?>
