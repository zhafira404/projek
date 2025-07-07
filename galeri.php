<?php
$pageTitle = "Galeri - Dapoer Aisyah";
require_once 'config/database.php';

// Ambil gambar dari database
try {
    $pdo = getConnection();
    
    // Get filter parameter
    $category = isset($_GET['category']) ? cleanInput($_GET['category']) : '';
    
    // Build query
    $whereConditions = ["is_active = 1"];
    $params = [];
    
    if ($category) {
        $whereConditions[] = "category = ?";
        $params[] = $category;
    }
    
    $whereClause = implode(' AND ', $whereConditions);
    
    $stmt = $pdo->prepare("
        SELECT * FROM gallery 
        WHERE $whereClause 
        ORDER BY sort_order ASC, created_at DESC
    ");
    $stmt->execute($params);
    $galleryItems = $stmt->fetchAll();
    
    // Get categories for filter
    $stmt = $pdo->prepare("
        SELECT category, COUNT(*) as count 
        FROM gallery 
        WHERE is_active = 1 
        GROUP BY category 
        ORDER BY count DESC
    ");
    $stmt->execute();
    $categories = $stmt->fetchAll();
    
    // Get statistics
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM gallery WHERE is_active = 1");
    $stmt->execute();
    $totalItems = $stmt->fetch()['total'];
    
} catch(PDOException $e) {
    $galleryItems = [];
    $categories = [];
    $totalItems = 0;
}

require_once 'includes/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="gallery-hero">
        <div class="hero-background"></div>
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-camera"></i>
                    <span>Galeri Kuliner</span>
                </div>
                <h1 class="hero-title">Nikmati Keindahan Setiap Hidangan</h1>
                <p class="hero-subtitle">Koleksi foto menu terbaik kami yang menggugah selera dan memanjakan mata</p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="stat-number"><?php echo $totalItems; ?>+</span>
                        <span class="stat-label">Foto Menu</span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-number"><?php echo count($categories); ?>+</span>
                        <span class="stat-label">Kategori</span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Halal</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="gallery-filter-section">
        <div class="container">
            <div class="filter-header">
                <h2>Jelajahi Menu Berdasarkan Kategori</h2>
                <p>Temukan hidangan favorit Anda dari berbagai kategori yang tersedia</p>
            </div>
            
            <div class="filter-tabs">
                <a href="galeri.php" class="filter-tab <?php echo !$category ? 'active' : ''; ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Semua</span>
                    <span class="count"><?php echo $totalItems; ?></span>
                </a>
                
                <?php foreach($categories as $cat): ?>
                    <a href="galeri.php?category=<?php echo urlencode($cat['category']); ?>" 
                       class="filter-tab <?php echo $category === $cat['category'] ? 'active' : ''; ?>">
                        <i class="<?php 
                            echo match($cat['category']) {
                                'makanan-utama' => 'fas fa-utensils',
                                'makanan-sehat' => 'fas fa-leaf',
                                'snack' => 'fas fa-cookie-bite',
                                'dessert' => 'fas fa-ice-cream',
                                'minuman' => 'fas fa-glass-whiskey',
                                'paket-acara' => 'fas fa-birthday-cake',
                                default => 'fas fa-utensils'
                            };
                        ?>"></i>
                        <span><?php echo ucwords(str_replace('-', ' ', $cat['category'])); ?></span>
                        <span class="count"><?php echo $cat['count']; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-main-section">
        <div class="container">
            <?php if ($category): ?>
                <div class="category-info">
                    <h3>
                        <i class="<?php 
                            echo match($category) {
                                'makanan-utama' => 'fas fa-utensils',
                                'makanan-sehat' => 'fas fa-leaf',
                                'snack' => 'fas fa-cookie-bite',
                                'dessert' => 'fas fa-ice-cream',
                                'minuman' => 'fas fa-glass-whiskey',
                                'paket-acara' => 'fas fa-birthday-cake',
                                default => 'fas fa-utensils'
                            };
                        ?>"></i>
                        <?php echo ucwords(str_replace('-', ' ', $category)); ?>
                    </h3>
                    <p>Menampilkan <?php echo count($galleryItems); ?> hidangan dalam kategori ini</p>
                </div>
            <?php endif; ?>

            <?php if (count($galleryItems) > 0): ?>
                <div class="gallery-grid">
                    <?php foreach ($galleryItems as $item): ?>
                        <div class="gallery-card" data-category="<?php echo htmlspecialchars($item['category']); ?>">
                            <div class="gallery-image-wrapper">
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
                                     alt="<?php echo htmlspecialchars($item['alt_text'] ?: $item['title']); ?>"
                                     class="gallery-image"
                                     loading="lazy">
                                <div class="gallery-overlay">
                                    <div class="gallery-actions">
                                        <button class="gallery-action-btn zoom-btn" 
                                                onclick="openLightbox('<?php echo htmlspecialchars($item['image_url']); ?>', '<?php echo htmlspecialchars($item['title']); ?>', '<?php echo htmlspecialchars($item['description']); ?>')">
                                            <i class="fas fa-search-plus"></i>
                                        </button>
                                        <button class="gallery-action-btn share-btn" 
                                                onclick="shareImage('<?php echo htmlspecialchars($item['title']); ?>', '<?php echo htmlspecialchars($item['image_url']); ?>')">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="gallery-category-badge">
                                    <?php echo ucwords(str_replace('-', ' ', $item['category'])); ?>
                                </div>
                            </div>
                            <div class="gallery-content">
                                <h3 class="gallery-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                                <p class="gallery-description"><?php echo htmlspecialchars($item['description']); ?></p>
                                <div class="gallery-footer">
                                    <button class="btn btn-outline btn-sm" onclick="addToWishlist(<?php echo $item['id']; ?>)">
                                        <i class="far fa-heart"></i>
                                        <span>Favorit</span>
                                    </button>
                                    <a href="menu.php?search=<?php echo urlencode($item['title']); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span>Pesan</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-gallery-items">
                    <div class="no-items-content">
                        <i class="fas fa-images no-items-icon"></i>
                        <h3>Tidak ada foto dalam kategori ini</h3>
                        <p>Coba pilih kategori lain atau lihat semua galeri kami</p>
                        <a href="galeri.php" class="btn btn-primary">
                            <i class="fas fa-th-large"></i>
                            Lihat Semua Galeri
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Instagram Section -->
    <section class="instagram-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fab fa-instagram"></i>
                    <span>Follow Instagram</span>
                </div>
                <h2 class="section-title">@dapoerisyah</h2>
                <p class="section-subtitle">Lihat update menu terbaru dan behind the scenes dapur kami</p>
            </div>
            
            <div class="instagram-grid">
                <?php 
                $instagramPosts = [
                    'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=300&h=300&fit=crop',
                    'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=300&h=300&fit=crop',
                    'https://images.unsplash.com/photo-1529563021893-cc83c992d75d?w=300&h=300&fit=crop',
                    'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=300&h=300&fit=crop',
                    'https://images.unsplash.com/photo-1547592180-85f173990554?w=300&h=300&fit=crop',
                    'https://images.unsplash.com/photo-1541544181051-e46607bc22a4?w=300&h=300&fit=crop'
                ];
                
                foreach($instagramPosts as $index => $post): ?>
                    <div class="instagram-post" onclick="openInstagram()">
                        <img src="<?php echo $post; ?>" alt="Instagram Post <?php echo $index + 1; ?>">
                        <div class="instagram-overlay">
                            <div class="instagram-icon">
                                <i class="fab fa-instagram"></i>
                            </div>
                            <div class="instagram-stats">
                                <span><i class="fas fa-heart"></i> <?php echo rand(50, 200); ?></span>
                                <span><i class="fas fa-comment"></i> <?php echo rand(5, 30); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="instagram-cta">
                <a href="https://instagram.com/dapoerisyah" target="_blank" class="btn btn-primary btn-lg">
                    <i class="fab fa-instagram"></i>
                    <span>Follow @dapoerisyah</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="testimonial-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-quote-left"></i>
                    <span>Testimoni</span>
                </div>
                <h2 class="section-title">Kata Mereka Tentang Kami</h2>
                <p class="section-subtitle">Kepuasan pelanggan adalah prioritas utama kami</p>
            </div>
            
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="testimonial-info">
                            <h4>Sari & Budi</h4>
                            <span>Pernikahan - Jakarta</span>
                        </div>
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <p>"Makanannya enak banget! Semua tamu di acara pernikahan kami puas. Pelayanannya juga sangat profesional dan tepat waktu."</p>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="testimonial-info">
                            <h4>Pak Agus</h4>
                            <span>Corporate Event - Bekasi</span>
                        </div>
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <p>"Sudah 3 kali order untuk acara kantor. Selalu memuaskan! Harga reasonable, rasa mantap, porsi pas."</p>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="testimonial-info">
                            <h4>Ibu Ratna</h4>
                            <span>Arisan Keluarga - Depok</span>
                        </div>
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <p>"Menu tradisionalnya autentik banget! Bikin kangen kampung halaman. Highly recommended!"</p>
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
                    <h2>Tertarik dengan Menu Kami?</h2>
                    <p>Jangan ragu untuk menghubungi kami dan konsultasikan kebutuhan catering Anda</p>
                </div>
                <div class="cta-actions">
                    <a href="menu.php" class="btn btn-primary">
                        <i class="fas fa-utensils"></i>
                        Lihat Menu Lengkap
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

<!-- Lightbox Modal -->
<div id="lightboxModal" class="lightbox-modal">
    <div class="lightbox-overlay" onclick="closeLightbox()"></div>
    <div class="lightbox-content">
        <button class="lightbox-close" onclick="closeLightbox()">
            <i class="fas fa-times"></i>
        </button>
        <div class="lightbox-image-container">
            <img id="lightboxImage" src="/placeholder.svg" alt="" class="lightbox-image">
        </div>
        <div class="lightbox-info">
            <h3 id="lightboxTitle"></h3>
            <p id="lightboxDescription"></p>
            <div class="lightbox-actions">
                <button class="btn btn-outline btn-sm" onclick="downloadImage()">
                    <i class="fas fa-download"></i>
                    Download
                </button>
                <button class="btn btn-primary btn-sm" onclick="shareCurrentImage()">
                    <i class="fas fa-share-alt"></i>
                    Share
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Gallery Page Specific Styles */
.gallery-hero {
    background: linear-gradient(135deg, rgba(44, 85, 48, 0.9), rgba(62, 123, 62, 0.8)), 
                url('https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=1200&h=800&fit=crop') center/cover;
    min-height: 70vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=1200&h=800&fit=crop') center/cover;
    z-index: -2;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(44, 85, 48, 0.1) 0%, rgba(62, 123, 62, 0.1) 100%);
    z-index: -1;
}

.hero-content {
    text-align: center;
    color: white;
    position: relative;
    z-index: 2;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 25px;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.hero-subtitle {
    font-size: 1.3rem;
    margin-bottom: 3rem;
    opacity: 0.9;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 3rem;
}

.hero-stat {
    text-align: center;
}

.hero-stat .stat-number {
    display: block;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.hero-stat .stat-label {
    font-size: 1rem;
    opacity: 0.8;
}

/* Filter Section */
.gallery-filter-section {
    padding: 4rem 0;
    background: white;
}

.filter-header {
    text-align: center;
    margin-bottom: 3rem;
}

.filter-header h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #333;
}

.filter-header p {
    font-size: 1.2rem;
    color: #666;
}

.filter-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

.filter-tab {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    border: 2px solid #e1e5e9;
    border-radius: 25px;
    text-decoration: none;
    color: #666;
    font-weight: 500;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.filter-tab:hover,
.filter-tab.active {
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    color: white;
    border-color: #2c5530;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(44, 85, 48, 0.3);
}

.filter-tab .count {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.filter-tab.active .count {
    background: rgba(255, 255, 255, 0.3);
}

/* Gallery Main Section */
.gallery-main-section {
    padding: 4rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.category-info {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.category-info h3 {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #2c5530;
}

.category-info p {
    font-size: 1.1rem;
    color: #666;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
}

.gallery-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.gallery-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.gallery-image-wrapper {
    position: relative;
    overflow: hidden;
}

.gallery-image {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-card:hover .gallery-image {
    transform: scale(1.1);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.gallery-card:hover .gallery-overlay {
    opacity: 1;
}

.gallery-actions {
    display: flex;
    gap: 1rem;
}

.gallery-action-btn {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-action-btn:hover {
    background: white;
    color: #2c5530;
    transform: scale(1.1);
}

.gallery-category-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.gallery-content {
    padding: 2rem;
}

.gallery-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
}

.gallery-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.gallery-footer {
    display: flex;
    gap: 1rem;
    justify-content: space-between;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

/* No Gallery Items */
.no-gallery-items {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.no-items-icon {
    font-size: 4rem;
    color: #ccc;
    margin-bottom: 1.5rem;
}

.no-items-content h3 {
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
}

.no-items-content p {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

/* Instagram Section */
.instagram-section {
    padding: 6rem 0;
    background: white;
}

.instagram-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin: 3rem 0;
}

.instagram-post {
    position: relative;
    aspect-ratio: 1;
    border-radius: 15px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
}

.instagram-post:hover {
    transform: scale(1.05);
}

.instagram-post img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.instagram-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.instagram-post:hover .instagram-overlay {
    opacity: 1;
}

.instagram-icon {
    font-size: 2rem;
    color: white;
    margin-bottom: 1rem;
}

.instagram-stats {
    display: flex;
    gap: 1rem;
    color: white;
    font-size: 0.9rem;
}

.instagram-cta {
    text-align: center;
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1.1rem;
}

/* Testimonial Section */
.testimonial-section {
    padding: 6rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.testimonial-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 4rem;
}

.testimonial-card {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.testimonial-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.testimonial-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.testimonial-info h4 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #333;
}

.testimonial-info span {
    font-size: 0.9rem;
    color: #666;
}

.testimonial-rating {
    margin-left: auto;
    color: #ffd700;
}

.testimonial-content p {
    color: #666;
    line-height: 1.6;
    font-style: italic;
}

/* CTA Section */
.cta-section {
    padding: 6rem 0;
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    color: white;
}

.cta-content {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.cta-content h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-content p {
    font-size: 1.2rem;
    margin-bottom: 3rem;
    opacity: 0.9;
    line-height: 1.6;
}

.cta-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

/* Lightbox Modal */
.lightbox-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: rgba(0, 0, 0, 0.9);
    align-items: center;
    justify-content: center;
}

.lightbox-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.lightbox-content {
    position: relative;
    max-width: 90vw;
    max-height: 90vh;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.lightbox-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 40px;
    height: 40px;
    background: rgba(0, 0, 0, 0.7);
    border: none;
    border-radius: 50%;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
}

.lightbox-close:hover {
    background: rgba(0, 0, 0, 0.9);
    transform: scale(1.1);
}

.lightbox-image-container {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}

.lightbox-image {
    max-width: 100%;
    max-height: 70vh;
    object-fit: contain;
}

.lightbox-info {
    padding: 2rem;
    background: white;
}

.lightbox-info h3 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
}

.lightbox-info p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.lightbox-actions {
    display: flex;
    gap: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-stats {
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .filter-tabs {
        justify-content: flex-start;
        overflow-x: auto;
        padding-bottom: 1rem;
    }
    
    .gallery-grid {
        grid-template-columns: 1fr;
    }
    
    .instagram-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .testimonial-grid {
        grid-template-columns: 1fr;
    }
    
    .cta-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .lightbox-content {
        max-width: 95vw;
        max-height: 95vh;
    }
}
</style>

<script>
// Lightbox functionality
let currentImageSrc = '';
let currentImageTitle = '';
let currentImageDescription = '';

function openLightbox(imageSrc, title, description) {
    currentImageSrc = imageSrc;
    currentImageTitle = title;
    currentImageDescription = description;
    
    const modal = document.getElementById('lightboxModal');
    const image = document.getElementById('lightboxImage');
    const titleEl = document.getElementById('lightboxTitle');
    const descEl = document.getElementById('lightboxDescription');
    
    image.src = imageSrc;
    titleEl.textContent = title;
    descEl.textContent = description;
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const modal = document.getElementById('lightboxModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Share functionality
function shareImage(title, imageUrl) {
    if (navigator.share) {
        navigator.share({
            title: title,
            text: `Lihat menu lezat dari Dapoer Aisyah: ${title}`,
            url: window.location.href
        });
    } else {
        // Fallback - copy to clipboard
        const text = `${title} - ${window.location.href}`;
        navigator.clipboard.writeText(text).then(() => {
            alert('Link berhasil disalin ke clipboard!');
        });
    }
}

function shareCurrentImage() {
    shareImage(currentImageTitle, currentImageSrc);
}

function downloadImage() {
    const link = document.createElement('a');
    link.href = currentImageSrc;
    link.download = currentImageTitle.replace(/[^a-z0-9]/gi, '_').toLowerCase() + '.jpg';
    link.click();
}

// Instagram functionality
function openInstagram() {
    window.open('https://instagram.com/dapoerisyah', '_blank');
}

// Wishlist functionality
function addToWishlist(itemId) {
    // In a real app, this would save to database
    alert('Menu berhasil ditambahkan ke favorit!');
}

// Parallax effect for hero
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const parallax = document.querySelector('.hero-background');
    if (parallax) {
        const speed = scrolled * 0.5;
        parallax.style.transform = `translateY(${speed}px)`;
    }
});

// Animate elements on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe elements for animation
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.gallery-card, .testimonial-card, .instagram-post').forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'all 0.6s ease';
        observer.observe(element);
    });
});

// Keyboard navigation for lightbox
document.addEventListener('keydown', function(e) {
    if (document.getElementById('lightboxModal').style.display === 'flex') {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
