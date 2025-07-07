<?php
$pageTitle = "Tentang Kami - Dapoer Aisyah";
require_once 'config/database.php';

// Ambil statistik untuk halaman about
try {
    $pdo = getConnection();
    
    // Hitung total produk
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM products WHERE is_active = 1");
    $stmt->execute();
    $totalProducts = $stmt->fetch()['total'];
    
    // Hitung total pelanggan
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users WHERE role = 'customer'");
    $stmt->execute();
    $totalCustomers = $stmt->fetch()['total'];
    
    // Hitung total pesanan
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM orders");
    $stmt->execute();
    $totalOrders = $stmt->fetch()['total'];
    
} catch(PDOException $e) {
    $totalProducts = 100;
    $totalCustomers = 500;
    $totalOrders = 1000;
}

require_once 'includes/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="page-hero about-hero">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-heart"></i>
                    <span>Cerita Kami</span>
                </div>
                <h1 class="hero-title">Perjalanan Panjang Menuju Kelezatan</h1>
                <p class="hero-subtitle">Dari dapur kecil hingga menjadi pilihan utama catering terpercaya di Indonesia</p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="stat-number"><?php echo $totalProducts; ?>+</span>
                        <span class="stat-label">Menu</span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-number"><?php echo $totalCustomers; ?>+</span>
                        <span class="stat-label">Pelanggan</span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-number">10+</span>
                        <span class="stat-label">Tahun</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Story -->
    <section class="about-story">
        <div class="container">
            <div class="story-content">
                <div class="story-text">
                    <div class="section-badge">
                        <i class="fas fa-book-open"></i>
                        <span>Cerita Kami</span>
                    </div>
                    <h2 class="section-title">Dimulai dari Dapur Kecil</h2>
                    <div class="story-paragraphs">
                        <p class="story-paragraph">
                            <strong>Dapoer Aisyah dimulai dari sebuah dapur kecil di rumah pada tahun 2014.</strong> 
                            Dengan modal nekat dan kecintaan terhadap masakan tradisional Indonesia, Ibu Aisyah mulai 
                            menerima pesanan catering untuk acara-acara kecil di lingkungan sekitar.
                        </p>
                        
                        <p class="story-paragraph">
                            Dari mulut ke mulut, reputasi masakan yang lezat dan pelayanan yang ramah mulai tersebar. 
                            Apa yang dimulai sebagai usaha sampingan, kini telah berkembang menjadi bisnis catering 
                            terpercaya yang melayani berbagai acara, mulai dari gathering keluarga hingga event korporat besar.
                        </p>
                        
                        <p class="story-paragraph">
                            <em>Kami percaya bahwa makanan bukan hanya sekedar mengenyangkan, tetapi juga cara untuk 
                            menyatukan orang-orang dan menciptakan kenangan indah.</em> Setiap hidangan yang kami sajikan 
                            dibuat dengan penuh cinta dan perhatian terhadap detail.
                        </p>
                    </div>
                    
                    <div class="story-highlights">
                        <div class="highlight-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <strong>2014</strong>
                                <span>Tahun Berdiri</span>
                            </div>
                        </div>
                        <div class="highlight-item">
                            <i class="fas fa-award"></i>
                            <div>
                                <strong>1000+</strong>
                                <span>Event Sukses</span>
                            </div>
                        </div>
                        <div class="highlight-item">
                            <i class="fas fa-users"></i>
                            <div>
                                <strong>500+</strong>
                                <span>Pelanggan Setia</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="story-image">
                    <div class="image-wrapper">
                        <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=500&fit=crop" 
                             alt="Dapur Dapoer Aisyah" class="main-image">
                        <div class="image-overlay">
                            <div class="play-button" onclick="openVideoModal()">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                    </div>
                    <div class="image-decoration">
                        <div class="decoration-item decoration-1"></div>
                        <div class="decoration-item decoration-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="values-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-gem"></i>
                    <span>Nilai-Nilai Kami</span>
                </div>
                <h2 class="section-title">Prinsip yang Selalu Kami Pegang</h2>
                <p class="section-subtitle">Komitmen kami dalam setiap pelayanan dan hidangan yang disajikan</p>
            </div>
            
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3 class="value-title">Bahan Segar</h3>
                    <p class="value-description">
                        Kami selalu menggunakan bahan-bahan segar dan berkualitas terbaik untuk setiap hidangan yang kami sajikan.
                    </p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h3 class="value-title">Resep Autentik</h3>
                    <p class="value-description">
                        Setiap resep yang kami gunakan adalah warisan turun temurun yang telah teruji kelezatannya.
                    </p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="value-title">Dibuat dengan Cinta</h3>
                    <p class="value-description">
                        Setiap hidangan dibuat dengan penuh perhatian dan kasih sayang, seperti memasak untuk keluarga sendiri.
                    </p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3 class="value-title">Pelayanan Prima</h3>
                    <p class="value-description">
                        Kami berkomitmen memberikan pelayanan terbaik dengan pengiriman tepat waktu dan kemasan yang rapi.
                    </p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3 class="value-title">Kualitas Terjamin</h3>
                    <p class="value-description">
                        Standar kebersihan dan kualitas yang tinggi selalu kami jaga dalam setiap proses produksi.
                    </p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="value-title">Kepercayaan</h3>
                    <p class="value-description">
                        Kepercayaan pelanggan adalah aset terbesar kami yang selalu kami jaga dengan baik.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="statistics-section">
        <div class="container">
            <div class="stats-content">
                <div class="stats-header">
                    <h2>Pencapaian Kami</h2>
                    <p>Angka-angka yang membanggakan dalam perjalanan Dapoer Aisyah</p>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card" data-count="<?php echo $totalProducts; ?>">
                        <div class="stat-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="stat-number">0</div>
                        <div class="stat-label">Menu Pilihan</div>
                        <div class="stat-description">Berbagai hidangan lezat</div>
                    </div>
                    
                    <div class="stat-card" data-count="<?php echo $totalCustomers; ?>">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number">0</div>
                        <div class="stat-label">Pelanggan Setia</div>
                        <div class="stat-description">Yang mempercayai kami</div>
                    </div>
                    
                    <div class="stat-card" data-count="<?php echo $totalOrders; ?>">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="stat-number">0</div>
                        <div class="stat-label">Pesanan Terlayani</div>
                        <div class="stat-description">Dengan kepuasan tinggi</div>
                    </div>
                    
                    <div class="stat-card" data-count="10">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-number">0</div>
                        <div class="stat-label">Tahun Pengalaman</div>
                        <div class="stat-description">Melayani dengan dedikasi</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="team-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-users"></i>
                    <span>Tim Kami</span>
                </div>
                <h2 class="section-title">Orang-Orang Hebat di Balik Kelezatan</h2>
                <p class="section-subtitle">Tim profesional yang berdedikasi untuk memberikan yang terbaik</p>
            </div>
            
            <div class="team-grid">
                <div class="team-card">
                    <div class="member-image">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=300&h=300&fit=crop&crop=face" 
                             alt="Ibu Aisyah">
                        <div class="member-overlay">
                            <div class="social-links">
                                <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="member-info">
                        <h3 class="member-name">Ibu Aisyah</h3>
                        <p class="member-role">Founder & Head Chef</p>
                        <p class="member-description">
                            Pendiri Dapoer Aisyah dengan pengalaman memasak lebih dari 20 tahun. 
                            Ahli dalam masakan tradisional Indonesia.
                        </p>
                        <div class="member-skills">
                            <span class="skill-tag">Traditional Cooking</span>
                            <span class="skill-tag">Recipe Development</span>
                        </div>
                    </div>
                </div>
                
                <div class="team-card">
                    <div class="member-image">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=300&h=300&fit=crop&crop=face" 
                             alt="Pak Budi">
                        <div class="member-overlay">
                            <div class="social-links">
                                <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="member-info">
                        <h3 class="member-name">Pak Budi</h3>
                        <p class="member-role">Operations Manager</p>
                        <p class="member-description">
                            Mengelola operasional harian dan memastikan setiap pesanan terlaksana 
                            dengan sempurna dan tepat waktu.
                        </p>
                        <div class="member-skills">
                            <span class="skill-tag">Operations</span>
                            <span class="skill-tag">Quality Control</span>
                        </div>
                    </div>
                </div>
                
                <div class="team-card">
                    <div class="member-image">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=300&h=300&fit=crop&crop=face" 
                             alt="Mbak Sari">
                        <div class="member-overlay">
                            <div class="social-links">
                                <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="member-info">
                        <h3 class="member-name">Mbak Sari</h3>
                        <p class="member-role">Customer Service</p>
                        <p class="member-description">
                            Menangani semua komunikasi dengan pelanggan dan memastikan kepuasan 
                            pelanggan selalu terjaga.
                        </p>
                        <div class="member-skills">
                            <span class="skill-tag">Customer Care</span>
                            <span class="skill-tag">Communication</span>
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
                    <h2>Siap Merasakan Kelezatan Kami?</h2>
                    <p>Bergabunglah dengan ribuan pelanggan yang telah mempercayai Dapoer Aisyah untuk acara spesial mereka</p>
                </div>
                <div class="cta-actions">
                    <a href="menu.php" class="btn btn-primary">
                        <i class="fas fa-utensils"></i>
                        Lihat Menu
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

<style>
/* About Page Specific Styles */
.about-hero {
    background: linear-gradient(135deg, rgba(44, 85, 48, 0.9), rgba(62, 123, 62, 0.8)), 
                url('https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=1200&h=800&fit=crop') center/cover;
    min-height: 70vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(44, 85, 48, 0.1) 0%, rgba(62, 123, 62, 0.1) 100%);
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

/* Story Section */
.about-story {
    padding: 6rem 0;
    background: white;
}

.story-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.story-text {
    padding-right: 2rem;
}

.story-paragraphs {
    margin: 2rem 0;
}

.story-paragraph {
    font-size: 1.1rem;
    line-height: 1.8;
    margin-bottom: 1.5rem;
    color: #555;
}

.story-highlights {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 3rem;
}

.highlight-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    border-left: 4px solid #2c5530;
}

.highlight-item i {
    color: #2c5530;
    font-size: 1.5rem;
}

.highlight-item strong {
    display: block;
    font-size: 1.2rem;
    color: #2c5530;
}

.highlight-item span {
    color: #666;
    font-size: 0.9rem;
}

.story-image {
    position: relative;
}

.image-wrapper {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.main-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.image-wrapper:hover .image-overlay {
    opacity: 1;
}

.play-button {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.play-button:hover {
    transform: scale(1.1);
    background: white;
}

.play-button i {
    font-size: 1.5rem;
    color: #2c5530;
    margin-left: 4px;
}

/* Values Section */
.values-section {
    padding: 6rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 4rem;
}

.value-card {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.value-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.value-card:hover::before {
    transform: scaleX(1);
}

.value-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.value-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    transition: all 0.3s ease;
}

.value-card:hover .value-icon {
    transform: scale(1.1);
}

.value-icon i {
    font-size: 2rem;
    color: white;
}

.value-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
}

.value-description {
    color: #666;
    line-height: 1.6;
}

/* Statistics Section */
.statistics-section {
    padding: 6rem 0;
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    color: white;
}

.stats-header {
    text-align: center;
    margin-bottom: 4rem;
}

.stats-header h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.stats-header p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.stat-card {
    text-align: center;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-10px);
    background: rgba(255, 255, 255, 0.15);
}

.stat-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.stat-icon i {
    font-size: 1.5rem;
}

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.stat-description {
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Team Section */
.team-section {
    padding: 6rem 0;
    background: white;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 3rem;
    margin-top: 4rem;
}

.team-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.team-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.member-image {
    position: relative;
    overflow: hidden;
}

.member-image img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.team-card:hover .member-image img {
    transform: scale(1.1);
}

.member-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(44, 85, 48, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.team-card:hover .member-overlay {
    opacity: 1;
}

.social-links {
    display: flex;
    gap: 1rem;
}

.social-link {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: white;
    color: #2c5530;
    transform: scale(1.1);
}

.member-info {
    padding: 2rem;
}

.member-name {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.member-role {
    color: #2c5530;
    font-weight: 500;
    margin-bottom: 1rem;
}

.member-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.member-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.skill-tag {
    background: #f8f9fa;
    color: #2c5530;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

/* CTA Section */
.cta-section {
    padding: 6rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
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
    color: #333;
}

.cta-content p {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 3rem;
    line-height: 1.6;
}

.cta-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
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
    
    .story-content {
        grid-template-columns: 1fr;
        gap: 3rem;
    }
    
    .story-text {
        padding-right: 0;
    }
    
    .values-grid,
    .stats-grid,
    .team-grid {
        grid-template-columns: 1fr;
    }
    
    .cta-actions {
        flex-direction: column;
        align-items: center;
    }
}

/* Animation for counting numbers */
@keyframes countUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card.animate .stat-number {
    animation: countUp 0.6s ease-out;
}
</style>

<script>
// Counter animation
function animateCounters() {
    const counters = document.querySelectorAll('.stat-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.dataset.count);
                const numberElement = counter.querySelector('.stat-number');
                
                let current = 0;
                const increment = target / 100;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    numberElement.textContent = Math.floor(current) + (target >= 1000 ? '+' : '');
                }, 20);
                
                counter.classList.add('animate');
                observer.unobserve(counter);
            }
        });
    });
    
    counters.forEach(counter => observer.observe(counter));
}

// Video modal function
function openVideoModal() {
    alert('Video modal akan dibuka di sini');
}

// Initialize animations when page loads
document.addEventListener('DOMContentLoaded', function() {
    animateCounters();
});
</script>

<?php require_once 'includes/footer.php'; ?>
