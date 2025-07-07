<?php
require_once 'config/database.php';

// Hitung jumlah item di keranjang
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Dapoer Aisyah - Catering & Kuliner Tradisional'; ?></title>
    <meta name="description" content="Layanan catering terbaik, nasi box lezat, dan jajanan tradisional Indonesia. Melayani berbagai acara dengan cita rasa autentik sejak 2014.">
    <meta name="keywords" content="catering, nasi box, jajanan pasar, kuliner indonesia, makanan tradisional">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <a href="index.php">
                        <div class="logo-icon">DA</div>
                        <span class="logo-text">Dapoer Aisyah</span>
                    </a>
                </div>

                <!-- Navigation Desktop -->
                <nav class="nav-desktop">
                    <ul class="nav-list">
                        <li><a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Beranda</a></li>
                        <li><a href="menu.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'menu.php' ? 'active' : ''; ?>">Menu</a></li>
                        <li><a href="galeri.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'galeri.php' ? 'active' : ''; ?>">Galeri</a></li>
                        <li><a href="about.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">Cerita Kami</a></li>
                        <li><a href="contact.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Kontak</a></li>
                    </ul>
                </nav>

                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Search -->
                    <div class="search-container">
                        <button class="search-toggle" onclick="toggleSearch()">
                            <i class="fas fa-search"></i>
                        </button>
                        <div class="search-dropdown" id="searchDropdown">
                            <input type="text" id="searchInput" placeholder="Cari menu..." class="search-input">
                            <div id="searchResults" class="search-results"></div>
                        </div>
                    </div>

                    <!-- Cart -->
                    <button class="cart-btn" onclick="toggleCart()">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if ($cartCount > 0): ?>
                            <span class="cart-count" id="cartCount"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </button>

                    <!-- User -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="user-dropdown">
                            <button class="user-btn" onclick="toggleUserDropdown()">
                                <i class="fas fa-user"></i>
                                <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu">
                                <?php if ($_SESSION['user_role'] == 'admin'): ?>
                                    <a href="admin/index.php" class="dropdown-item">
                                        <i class="fas fa-tachometer-alt"></i>
                                        Dashboard Admin
                                    </a>
                                <?php endif; ?>
                                <a href="profile.php" class="dropdown-item">
                                    <i class="fas fa-user-circle"></i>
                                    Profil Saya
                                </a>
                                <a href="orders.php" class="dropdown-item">
                                    <i class="fas fa-shopping-bag"></i>
                                    Pesanan Saya
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="auth/logout.php" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Keluar
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <button class="auth-btn" onclick="openAuthModal()">
                            <i class="fas fa-user"></i>
                            <span>Masuk</span>
                        </button>
                    <?php endif; ?>

                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <nav class="nav-mobile" id="mobileNav">
                <div class="mobile-nav-content">
                    <ul class="mobile-nav-list">
                        <li><a href="index.php" class="mobile-nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                            <i class="fas fa-home"></i>
                            Beranda
                        </a></li>
                        <li><a href="menu.php" class="mobile-nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'menu.php' ? 'active' : ''; ?>">
                            <i class="fas fa-utensils"></i>
                            Menu
                        </a></li>
                        <li><a href="galeri.php" class="mobile-nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'galeri.php' ? 'active' : ''; ?>">
                            <i class="fas fa-images"></i>
                            Galeri
                        </a></li>
                        <li><a href="about.php" class="mobile-nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">
                            <i class="fas fa-heart"></i>
                            Cerita Kami
                        </a></li>
                        <li><a href="contact.php" class="mobile-nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">
                            <i class="fas fa-phone"></i>
                            Kontak
                        </a></li>
                    </ul>

                    <div class="mobile-search">
                        <input type="text" placeholder="Cari menu..." class="mobile-search-input">
                        <button class="mobile-search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Cart Sidebar -->
    <div id="cartSidebar" class="cart-sidebar">
        <div class="cart-header">
            <h3><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h3>
            <button onclick="toggleCart()" class="cart-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="cart-content" id="cartContent">
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <p>Keranjang masih kosong</p>
                <small>Yuk, pilih menu favorit kamu!</small>
            </div>
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total:</span>
                <strong>Rp <span id="cartTotal">0</span></strong>
            </div>
            <a href="checkout.php" class="checkout-btn">
                <i class="fas fa-credit-card"></i>
                Checkout
            </a>
        </div>
    </div>

    <!-- Auth Modal -->
    <div id="authModal" class="auth-modal">
        <div class="auth-modal-content">
            <div class="auth-modal-header">
                <h2 id="authTitle">Selamat Datang!</h2>
                <p id="authSubtitle">Masuk ke akun Anda</p>
                <button onclick="closeAuthModal()" class="auth-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="auth-modal-body">
                <!-- Login Form -->
                <form id="loginForm" class="auth-form active">
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="email" required placeholder="Masukkan email Anda">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Password</label>
                        <div class="password-input">
                            <input type="password" name="password" required placeholder="Masukkan password">
                            <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            Ingat saya
                        </label>
                        <a href="#" class="forgot-link">Lupa password?</a>
                    </div>
                    <button type="submit" class="auth-submit-btn">
                        <span class="btn-text">Masuk</span>
                        <span class="btn-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Memproses...
                        </span>
                    </button>
                </form>

                <!-- Register Form -->
                <form id="registerForm" class="auth-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Nama Lengkap</label>
                            <input type="text" name="name" required placeholder="Nama lengkap">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-phone"></i> No. Telepon</label>
                            <input type="tel" name="phone" required placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="email" required placeholder="email@example.com">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-lock"></i> Password</label>
                            <div class="password-input">
                                <input type="password" name="password" required placeholder="Min. 6 karakter">
                                <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-lock"></i> Konfirmasi Password</label>
                            <div class="password-input">
                                <input type="password" name="confirm_password" required placeholder="Ulangi password">
                                <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="terms" required>
                            <span class="checkmark"></span>
                            Saya setuju dengan <a href="#" class="terms-link">Syarat & Ketentuan</a>
                        </label>
                    </div>
                    <button type="submit" class="auth-submit-btn">
                        <span class="btn-text">Daftar Sekarang</span>
                        <span class="btn-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Memproses...
                        </span>
                    </button>
                </form>

                <!-- Auth Switch -->
                <div class="auth-switch">
                    <div id="loginSwitch" class="switch-option active">
                        <p>Belum punya akun? 
                            <button onclick="switchToRegister()" class="switch-btn">Daftar di sini</button>
                        </p>
                    </div>
                    <div id="registerSwitch" class="switch-option">
                        <p>Sudah punya akun? 
                            <button onclick="switchToLogin()" class="switch-btn">Masuk di sini</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Notification Container -->
    <div id="notificationContainer" class="notification-container"></div>
