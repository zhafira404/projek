<?php
require_once '../config/database.php';
requireLogin();
requireAdmin();

try {
    $pdo = getConnection();
    
    // Get statistics
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
    $totalOrders = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'customer'");
    $totalCustomers = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM products WHERE is_active = 1");
    $totalProducts = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed'");
    $totalRevenue = $stmt->fetch()['total'] ?? 0;
    
    // Get recent orders
    $stmt = $pdo->prepare("
        SELECT o.*, u.name as customer_name 
        FROM orders o 
        LEFT JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC 
        LIMIT 10
    ");
    $stmt->execute();
    $recentOrders = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $totalOrders = $totalCustomers = $totalProducts = $totalRevenue = 0;
    $recentOrders = [];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Dapoer Aisyah</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
    .admin-layout {
        display: flex;
        min-height: 100vh;
        background: #f8f9fa;
    }
    
    .admin-sidebar {
        width: 250px;
        background: #1e3a21;
        color: white;
        padding: 2rem 0;
    }
    
    .admin-sidebar .logo {
        text-align: center;
        margin-bottom: 2rem;
        padding: 0 1rem;
    }
    
    .admin-sidebar .logo-icon {
        background: #2c5530;
    }
    
    .admin-nav ul {
        list-style: none;
    }
    
    .admin-nav a {
        display: block;
        padding: 1rem 1.5rem;
        color: #bdc3c7;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .admin-nav a:hover,
    .admin-nav a.active {
        background: #2c5530;
        color: white;
    }
    
    .admin-content {
        flex: 1;
        padding: 2rem;
    }
    
    .admin-header {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .stat-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #1e3a21;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #666;
        font-weight: 500;
    }
    
    .recent-orders {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .recent-orders h3 {
        padding: 1.5rem;
        margin: 0;
        background: #f8f9fa;
        border-bottom: 1px solid #e1e5e9;
        color: #1e3a21;
    }
    
    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .orders-table th,
    .orders-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .orders-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #1e3a21;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-processing {
        background: #cce5ff;
        color: #004085;
    }
    
    .status-completed {
        background: #d4edda;
        color: #155724;
    }
    
    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }
    
    @media (max-width: 768px) {
        .admin-layout {
            flex-direction: column;
        }
        
        .admin-sidebar {
            width: 100%;
        }
        
        .admin-nav {
            display: flex;
            overflow-x: auto;
        }
        
        .admin-nav ul {
            display: flex;
        }
        
        .admin-nav a {
            white-space: nowrap;
        }
    }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="logo">
                <div class="logo-icon">DA</div>
                <span class="logo-text">Admin Panel</span>
            </div>
            
            <nav class="admin-nav">
                <ul>
                    <li><a href="index.php" class="active">📊 Dashboard</a></li>
                    <li><a href="products.php">📦 Produk</a></li>
                    <li><a href="orders.php">🛒 Pesanan</a></li>
                    <li><a href="users.php">👥 Pengguna</a></li>
                    <li><a href="../index.php">🏠 Kembali ke Website</a></li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-content">
            <!-- Header -->
            <div class="admin-header">
                <div>
                    <h1>Dashboard Admin</h1>
                    <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
                </div>
                <a href="../auth/logout.php" class="btn btn-outline">Keluar</a>
            </div>
            
            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">🛒</div>
                    <div class="stat-number"><?php echo $totalOrders; ?></div>
                    <div class="stat-label">Total Pesanan</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number"><?php echo $totalCustomers; ?></div>
                    <div class="stat-label">Total Pelanggan</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">📦</div>
                    <div class="stat-number"><?php echo $totalProducts; ?></div>
                    <div class="stat-label">Total Produk</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-number">Rp <?php echo number_format($totalRevenue, 0, ',', '.'); ?></div>
                    <div class="stat-label">Total Pendapatan</div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="recent-orders">
                <h3>Pesanan Terbaru</h3>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentOrders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name'] ?: $order['customer_name']); ?></td>
                            <td>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $order['status']; ?>">
                                    <?php 
                                    $statusLabels = [
                                        'pending' => 'Menunggu',
                                        'processing' => 'Diproses',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan'
                                    ];
                                    echo $statusLabels[$order['status']] ?? $order['status'];
                                    ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
