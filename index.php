<?php
$pageTitle = 'Dapoer Aisyah - Catering & Kuliner Tradisional Indonesia';
require_once 'includes/header.php';

// Ambil produk unggulan
try {
    $pdo = getConnection();
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.is_active = 1 
        ORDER BY p.rating DESC 
        LIMIT 8
    ");
    $stmt->execute();
    $featuredProducts = $stmt->fetchAll();
    
    // Ambil kategori
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE is_active = 1 ORDER BY name");
    $stmt->execute();
    $categories = $stmt->fetchAll();
} catch(PDOException $e) {
    $featuredProducts = [];
    $categories = [];
}
?>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-background"></div>
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        Kelezatan <span class="highlight">Autentik</span><br>
                        Dapoer Aisyah
                    </h1>
                    <p class="hero-subtitle">
                        Nikmati cita rasa khas Indonesia dengan layanan catering terbaik, 
                        nasi box lezat, dan berbagai jajanan pasar yang menggugah selera.
                    </p>
                    <div class="hero-buttons">
                        <a href="menu.php" class="btn btn-primary">
                            <i class="fas fa-utensils"></i>
                            Lihat Menu
                        </a>
                        <a href="contact.php" class="btn btn-outline">
                            <i class="fas fa-phone"></i>
                            Konsultasi Sekarang
                        </a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=600&h=400&fit=crop" 
                         alt="Makanan Indonesia" 
                         onerror="this.src='https://via.placeholder.com/600x400/2c5530/ffffff?text=Dapoer+Aisyah'">
                    <div class="hero-badge">
                        <div class="status-indicator">
                            <div class="status-dot"></div>
                            <span>Tersedia Hari Ini</span>
                        </div>
                        <p>100+ Menu Pilihan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured-products">
        <div class="container">
            <div class="section-header">
                <h2>Menu Unggulan</h2>
                <p>Pilihan terbaik dari dapur kami yang paling disukai pelanggan</p>
            </div>
            
            <div class="products-grid">
                <?php if (!empty($featuredProducts)): ?>
                    <?php foreach($featuredProducts as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo $product['image'] ?: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=300&h=200&fit=crop'; ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></div>
                            <button class="product-favorite" onclick="toggleFavorite(<?php echo $product['id']; ?>)">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-description"><?php echo htmlspecialchars(substr($product['description'], 0, 80)) . '...'; ?></p>
                            <div class="product-rating">
                                <div class="rating-stars">
                                    <?php 
                                    $rating = $product['rating'];
                                    for($i = 1; $i <= 5; $i++): 
                                        echo $i <= $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                    endfor; 
                                    ?>
                                </div>
                                <span class="rating-text"><?php echo $rating; ?> (<?php echo rand(10, 50); ?> ulasan)</span>
                            </div>
                            <div class="product-footer">
                                <div class="product-price">
                                    <span class="price-label">Mulai dari</span>
                                    <span class="price-amount">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></span>
                                </div>
                                <button class="add-to-cart-btn" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>, '<?php echo $product['image']; ?>')">
                                    <i class="fas fa-cart-plus"></i>
                                    <span>Tambah</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback products jika database kosong -->
                    <div class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=300&h=200&fit=crop" alt="Nasi Gudeg">
                            <div class="product-category">Makanan Utama</div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Nasi Gudeg Jogja</h3>
                            <p class="product-description">Gudeg autentik dengan cita rasa manis khas Yogyakarta...</p>
                            <div class="product-rating">
                                <div class="rating-stars">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                                </div>
                                <span class="rating-text">4.5 (25 ulasan)</span>
                            </div>
                            <div class="product-footer">
                                <div class="product-price">
                                    <span class="price-label">Mulai dari</span>
                                    <span class="price-amount">Rp 25.000</span>
                                </div>
                                <button class="add-to-cart-btn" onclick="addToCart(1, 'Nasi Gudeg Jogja', 25000, 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=300&h=200&fit=crop')">
                                    <i class="fas fa-cart-plus"></i>
                                    <span>Tambah</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Add more fallback products here -->
                <?php endif; ?>
            </div>
            
            <div class="section-footer">
                <a href="menu.php" class="btn btn-outline">
                    <i class="fas fa-utensils"></i>
                    Lihat Semua Menu
                </a>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories">
        <div class="container">
            <div class="section-header">
                <h2>Kategori Menu</h2>
                <p>Temukan berbagai pilihan menu sesuai kebutuhan Anda</p>
            </div>
            
            <div class="categories-grid">
                <?php if (!empty($categories)): ?>
                    <?php foreach($categories as $category): ?>
                    <a href="menu.php?category=<?php echo $category['slug']; ?>" class="category-card">
                        <div class="category-image">
                            <img src="https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=300&h=160&fit=crop" 
                                 alt="<?php echo htmlspecialchars($category['name']); ?>">
                            <div class="category-overlay">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                        <div class="category-info">
                            <h3 class="category-name"><?php echo htmlspecialchars($category['name']); ?></h3>
                            <p class="category-description"><?php echo htmlspecialchars($category['description']); ?></p>
                            <span class="category-count"><?php echo rand(5, 20); ?> menu</span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback categories -->
                    <a href="menu.php?category=makanan-utama" class="category-card">
                        <div class="category-image">
                            <img src="https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=300&h=160&fit=crop" alt="Makanan Utama">
                            <div class="category-overlay">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                        <div class="category-info">
                            <h3 class="category-name">Makanan Utama</h3>
                            <p class="category-description">Nasi, lauk pauk, dan hidangan lengkap</p>
                            <span class="category-count">15 menu</span>
                        </div>
                    </a>
                    <!-- Add more fallback categories -->
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-preview">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Cerita Kami</h2>
                    <p class="about-lead">
                        Dapoer Aisyah dimulai dari kecintaan terhadap masakan tradisional Indonesia. 
                        Kami berkomitmen untuk menyajikan cita rasa autentik dengan sentuhan modern 
                        yang memanjakan lidah setiap pelanggan.
                    </p>
                    <p>
                        Dengan pengalaman lebih dari satu dekade, kami telah melayani ribuan pelanggan 
                        untuk berbagai acara, mulai dari gathering keluarga hingga event korporat besar.
                    </p>
                    
                    <div class="features-grid">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="feature-content">
                                <h3>Berpengalaman</h3>
                                <p>Lebih dari 10 tahun melayani pelanggan dengan dedikasi tinggi</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="feature-content">
                                <h3>Kualitas Terjamin</h3>
                                <p>Menggunakan bahan-bahan segar dan berkualitas terbaik</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="feature-content">
                                <h3>Pelayanan Cepat</h3>
                                <p>Proses pemesanan mudah dengan pengiriman tepat waktu</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="feature-content">
                                <h3>Dibuat dengan Cinta</h3>
                                <p>Setiap hidangan dibuat dengan penuh perhatian dan kasih sayang</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="about-actions">
                        <a href="about.php" class="btn btn-primary">
                            <i class="fas fa-heart"></i>
                            Baca Cerita Lengkap
                        </a>
                        <a href="contact.php" class="btn btn-outline">
                            <i class="fas fa-phone"></i>
                            Hubungi Kami
                        </a>
                    </div>
                </div>
                
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=500&h=400&fit=crop" 
                         alt="Dapur Dapoer Aisyah">
                    <div class="about-badge">
                        <span class="badge-number">10+</span>
                        <span class="badge-text">Tahun Pengalaman</span>
                    </div>
                    <div class="about-stats">
                        <div class="stat-item">
                            <span class="stat-number">1000+</span>
                            <span class="stat-label">Pelanggan Puas</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">100+</span>
                            <span class="stat-label">Menu Pilihan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2>Kata Mereka</h2>
                <p>Testimoni dari pelanggan yang puas dengan layanan kami</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">
                            "Makanannya enak banget! Semua tamu di acara pernikahan kami puas. 
                            Pelayanannya juga sangat profesional dan tepat waktu."
                        </p>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=60&h=60&fit=crop&crop=face" alt="Sari">
                            </div>
                            <div class="author-info">
                                <h4>Sari & Budi</h4>
                                <p>Pernikahan - Jakarta</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">
                            "Sudah 3 kali order untuk acara kantor. Selalu memuaskan! 
                            Harga reasonable, rasa mantap, porsi pas."
                        </p>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=60&h=60&fit=crop&crop=face" alt="Agus">
                            </div>
                            <div class="author-info">
                                <h4>Pak Agus</h4>
                                <p>Corporate Event - Bekasi</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">
                            "Menu tradisionalnya autentik banget! Bikin kangen kampung halaman. 
                            Recommended untuk yang suka masakan Indonesia asli."
                        </p>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=60&h=60&fit=crop&crop=face" alt="Ratna">
                            </div>
                            <div class="author-info">
                                <h4>Ibu Ratna</h4>
                                <p>Arisan Keluarga - Depok</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <div class="cta-text">
                    <h2>Siap Memesan?</h2>
                    <p>Hubungi kami sekarang untuk konsultasi menu dan harga terbaik untuk acara Anda!</p>
                </div>
                <div class="cta-actions">
                    <a href="https://wa.me/6281234567890" class="btn btn-primary" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp Sekarang
                    </a>
                    <a href="contact.php" class="btn btn-outline">
                        <i class="fas fa-phone"></i>
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
