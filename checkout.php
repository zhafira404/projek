<?php
// Start session first before any output
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Checkout - Dapoer Aisyah";
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    try {
        $pdo = getConnection();
        
        // Get form data
        $delivery_address = trim($_POST['delivery_address']);
        $phone = trim($_POST['phone']);
        $notes = trim($_POST['notes'] ?? '');
        $payment_method = $_POST['payment_method'];
        
        // Validation
        if (empty($delivery_address)) {
            throw new Exception('Alamat pengiriman wajib diisi!');
        }
        
        if (empty($phone)) {
            throw new Exception('Nomor telepon wajib diisi!');
        }
        
        if (empty($payment_method)) {
            throw new Exception('Metode pembayaran wajib dipilih!');
        }
        
        // Get cart from session or localStorage (you might need to adjust this)
        $cart_items = json_decode($_POST['cart_items'] ?? '[]', true);
        
        if (empty($cart_items)) {
            throw new Exception('Keranjang kosong! Silakan pilih menu terlebih dahulu.');
        }
        
        // Calculate total
        $total_amount = 0;
        foreach ($cart_items as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }
        
        // Start transaction
        $pdo->beginTransaction();
        
        // Insert order
        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, total_amount, delivery_address, phone, notes, payment_method, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())
        ");
        $stmt->execute([
            $_SESSION['user_id'],
            $total_amount,
            $delivery_address,
            $phone,
            $notes,
            $payment_method
        ]);
        
        $order_id = $pdo->lastInsertId();
        
        // Insert order items
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_name, price, quantity, subtotal) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        foreach ($cart_items as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $stmt->execute([
                $order_id,
                $item['name'],
                $item['price'],
                $item['quantity'],
                $subtotal
            ]);
        }
        
        // Commit transaction
        $pdo->commit();
        
        $success = "Pesanan berhasil dibuat! ID Pesanan: #" . str_pad($order_id, 6, '0', STR_PAD_LEFT);
        
        // Redirect to success page
        header("Location: order-success.php?order_id=" . $order_id);
        exit;
        
    } catch(Exception $e) {
        // Rollback transaction
        if ($pdo->inTransaction()) {
            $pdo->rollback();
        }
        $error = $e->getMessage();
    }
}

require_once 'includes/header.php';
?>

<main>
    <section class="checkout-section">
        <div class="container">
            <div class="checkout-wrapper">
                <div class="checkout-header">
                    <h1>Checkout Pesanan</h1>
                    <p>Lengkapi data pengiriman dan pembayaran</p>
                </div>
                
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo htmlspecialchars($success); ?></span>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="checkout-form" id="checkoutForm">
                    <div class="checkout-content">
                        <div class="checkout-left">
                            <div class="form-section">
                                <h3>Informasi Pengiriman</h3>
                                
                                <div class="form-group">
                                    <label for="delivery_address">
                                        <i class="fas fa-map-marker-alt"></i>
                                        Alamat Pengiriman
                                    </label>
                                    <textarea id="delivery_address" name="delivery_address" rows="3" 
                                              placeholder="Masukkan alamat lengkap pengiriman"
                                              value="<?php echo isset($_POST['delivery_address']) ? htmlspecialchars($_POST['delivery_address']) : ''; ?>"></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">
                                        <i class="fas fa-phone"></i>
                                        Nomor Telepon
                                    </label>
                                    <input type="tel" id="phone" name="phone" 
                                           placeholder="08xx-xxxx-xxxx"
                                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="notes">
                                        <i class="fas fa-sticky-note"></i>
                                        Catatan (Opsional)
                                    </label>
                                    <textarea id="notes" name="notes" rows="2" 
                                              placeholder="Catatan khusus untuk pesanan"><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h3>Metode Pembayaran</h3>
                                
                                <div class="payment-methods">
                                    <label class="payment-method">
                                        <input type="radio" name="payment_method" value="cod" checked>
                                        <div class="payment-option">
                                            <i class="fas fa-money-bill-wave"></i>
                                            <div>
                                                <h4>Cash on Delivery (COD)</h4>
                                                <p>Bayar saat pesanan tiba</p>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label class="payment-method">
                                        <input type="radio" name="payment_method" value="transfer">
                                        <div class="payment-option">
                                            <i class="fas fa-university"></i>
                                            <div>
                                                <h4>Transfer Bank</h4>
                                                <p>Transfer ke rekening kami</p>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label class="payment-method">
                                        <input type="radio" name="payment_method" value="ewallet">
                                        <div class="payment-option">
                                            <i class="fas fa-mobile-alt"></i>
                                            <div>
                                                <h4>E-Wallet</h4>
                                                <p>GoPay, OVO, DANA</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="checkout-right">
                            <div class="order-summary">
                                <h3>Ringkasan Pesanan</h3>
                                
                                <div id="orderItems" class="order-items">
                                    <!-- Order items will be populated by JavaScript -->
                                </div>
                                
                                <div class="order-total">
                                    <div class="total-row">
                                        <span>Subtotal:</span>
                                        <span id="subtotal">Rp 0</span>
                                    </div>
                                    <div class="total-row">
                                        <span>Ongkos Kirim:</span>
                                        <span id="shipping">Rp 5.000</span>
                                    </div>
                                    <div class="total-row total-final">
                                        <span>Total:</span>
                                        <span id="finalTotal">Rp 5.000</span>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="cart_items" id="cartItemsInput">
                                
                                <button type="submit" name="checkout" class="btn btn-primary btn-full">
                                    <span>Buat Pesanan</span>
                                    <i class="fas fa-shopping-bag"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<style>
.checkout-section {
    padding: 2rem 0;
    min-height: 80vh;
    background: #f8f9fa;
}

.checkout-wrapper {
    max-width: 1200px;
    margin: 0 auto;
}

.checkout-header {
    text-align: center;
    margin-bottom: 3rem;
}

.checkout-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c5530;
    margin-bottom: 0.5rem;
}

.checkout-header p {
    color: #666;
    font-size: 1.1rem;
}

.checkout-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
}

.checkout-left {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.form-section {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.form-section h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    margin-bottom: 0.75rem;
    color: #333;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid #e1e5e9;
    border-radius: 12px;
    outline: none;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #fafafa;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #2c5530;
    box-shadow: 0 0 0 3px rgba(44, 85, 48, 0.1);
    background: white;
}

.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.payment-method {
    cursor: pointer;
    display: block;
}

.payment-method input[type="radio"] {
    display: none;
}

.payment-option {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border: 2px solid #e1e5e9;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: #fafafa;
}

.payment-method input[type="radio"]:checked + .payment-option {
    border-color: #2c5530;
    background: rgba(44, 85, 48, 0.05);
}

.payment-option i {
    font-size: 1.5rem;
    color: #2c5530;
    width: 30px;
    text-align: center;
}

.payment-option h4 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #333;
}

.payment-option p {
    color: #666;
    font-size: 0.9rem;
}

.checkout-right {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.order-summary {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.order-summary h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: #333;
}

.order-items {
    margin-bottom: 1.5rem;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #eee;
}

.order-item:last-child {
    border-bottom: none;
}

.item-info h4 {
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
    color: #333;
}

.item-info p {
    color: #666;
    font-size: 0.9rem;
}

.item-price {
    font-weight: 600;
    color: #2c5530;
}

.order-total {
    border-top: 2px solid #eee;
    padding-top: 1rem;
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.total-final {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2c5530;
    border-top: 1px solid #eee;
    padding-top: 0.75rem;
    margin-top: 0.75rem;
}

.btn-full {
    width: 100%;
    padding: 1.25rem;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    margin-top: 1.5rem;
    transition: all 0.3s ease;
}

.btn-full:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(44, 85, 48, 0.3);
}

@media (max-width: 768px) {
    .checkout-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .checkout-right {
        position: static;
    }
    
    .form-section,
    .order-summary {
        padding: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCheckoutCart();
    setupPhoneFormatting();
});

function loadCheckoutCart() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const orderItems = document.getElementById('orderItems');
    const cartItemsInput = document.getElementById('cartItemsInput');
    
    if (cart.length === 0) {
        orderItems.innerHTML = '<p>Keranjang kosong</p>';
        return;
    }
    
    let html = '';
    let subtotal = 0;
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        
        html += `
            <div class="order-item">
                <div class="item-info">
                    <h4>${item.name}</h4>
                    <p>${item.quantity}x Rp ${formatPrice(item.price)}</p>
                </div>
                <div class="item-price">
                    Rp ${formatPrice(itemTotal)}
                </div>
            </div>
        `;
    });
    
    orderItems.innerHTML = html;
    
    // Update totals
    const shipping = 5000;
    const total = subtotal + shipping;
    
    document.getElementById('subtotal').textContent = 'Rp ' + formatPrice(subtotal);
    document.getElementById('finalTotal').textContent = 'Rp ' + formatPrice(total);
    
    // Set cart items for form submission
    cartItemsInput.value = JSON.stringify(cart);
}

function setupPhoneFormatting() {
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('8')) {
                value = '0' + value;
            }
            if (value.length > 4) {
                value = value.substring(0, 4) + '-' + value.substring(4);
            }
            if (value.length > 9) {
                value = value.substring(0, 9) + '-' + value.substring(9, 13);
            }
            e.target.value = value;
        });
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('id-ID').format(price);
}
</script>

<?php require_once 'includes/footer.php'; ?>
