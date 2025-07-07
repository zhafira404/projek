<?php
$pageTitle = "Hubungi Kami - Dapoer Aisyah";
require_once 'config/database.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = getConnection();
        
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $subject = trim($_POST['subject']);
        $message = trim($_POST['message']);
        
        if (empty($name) || empty($email) || empty($message)) {
            throw new Exception('Nama, email, dan pesan wajib diisi!');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Format email tidak valid!');
        }
        
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $email, $phone, $subject, $message]);
        
        $success = true;
        
    } catch(Exception $e) {
        $error = $e->getMessage();
    }
}

require_once 'includes/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="hero-background"></div>
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-phone-alt"></i>
                    <span>Hubungi Kami</span>
                </div>
                <h1 class="hero-title">Siap Melayani Kebutuhan Catering Terbaik</h1>
                <p class="hero-subtitle">Tim profesional kami siap membantu mewujudkan acara istimewa Anda dengan cita rasa yang tak terlupakan</p>
                <div class="hero-features">
                    <div class="hero-feature">
                        <i class="fas fa-clock"></i>
                        <span>Respon 24/7</span>
                    </div>
                    <div class="hero-feature">
                        <i class="fas fa-shipping-fast"></i>
                        <span>Free Delivery Jakarta</span>
                    </div>
                    <div class="hero-feature">
                        <i class="fas fa-star"></i>
                        <span>1000+ Happy Customers</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info Cards -->
    <section class="contact-info-section">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-card phone-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h3>Telepon & WhatsApp</h3>
                    </div>
                    <div class="card-content">
                        <div class="contact-details">
                            <p class="primary-contact">
                                <i class="fas fa-phone"></i>
                                +62 812-3456-7890
                            </p>
                            <p class="secondary-contact">
                                <i class="fab fa-whatsapp"></i>
                                +62 821-9876-5432
                            </p>
                        </div>
                        <div class="contact-hours">
                            <i class="fas fa-clock"></i>
                            <span>Senin - Minggu: 08:00 - 22:00</span>
                        </div>
                        <div class="card-actions">
                            <a href="https://wa.me/6281234567890" class="contact-btn whatsapp-btn" target="_blank">
                                <i class="fab fa-whatsapp"></i>
                                <span>Chat WhatsApp</span>
                            </a>
                            <a href="tel:+6281234567890" class="contact-btn phone-btn">
                                <i class="fas fa-phone"></i>
                                <span>Telepon</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="contact-card email-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email</h3>
                    </div>
                    <div class="card-content">
                        <div class="contact-details">
                            <p class="primary-contact">
                                <i class="fas fa-envelope"></i>
                                info@dapoerisyah.com
                            </p>
                            <p class="secondary-contact">
                                <i class="fas fa-shopping-bag"></i>
                                order@dapoerisyah.com
                            </p>
                        </div>
                        <div class="contact-hours">
                            <i class="fas fa-reply"></i>
                            <span>Respon dalam 2-4 jam kerja</span>
                        </div>
                        <div class="card-actions">
                            <a href="mailto:info@dapoerisyah.com" class="contact-btn email-btn">
                                <i class="fas fa-envelope"></i>
                                <span>Kirim Email</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="contact-card location-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>Lokasi</h3>
                    </div>
                    <div class="card-content">
                        <div class="contact-details">
                            <p class="primary-contact">
                                <i class="fas fa-map-marker-alt"></i>
                                Jl. Raya Bogor No. 123
                            </p>
                            <p class="secondary-contact">
                                <i class="fas fa-city"></i>
                                Jakarta Timur, 13750
                            </p>
                        </div>
                        <div class="contact-hours">
                            <i class="fas fa-door-open"></i>
                            <span>Buka setiap hari: 07:00 - 21:00</span>
                        </div>
                        <div class="card-actions">
                            <a href="https://maps.google.com" class="contact-btn maps-btn" target="_blank">
                                <i class="fas fa-map"></i>
                                <span>Lihat Maps</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="contact-card social-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <h3>Social Media</h3>
                    </div>
                    <div class="card-content">
                        <div class="social-grid">
                            <a href="#" class="social-btn instagram">
                                <i class="fab fa-instagram"></i>
                                <span>Instagram</span>
                            </a>
                            <a href="#" class="social-btn facebook">
                                <i class="fab fa-facebook"></i>
                                <span>Facebook</span>
                            </a>
                            <a href="#" class="social-btn tiktok">
                                <i class="fab fa-tiktok"></i>
                                <span>TikTok</span>
                            </a>
                            <a href="#" class="social-btn youtube">
                                <i class="fab fa-youtube"></i>
                                <span>YouTube</span>
                            </a>
                        </div>
                        <div class="contact-hours">
                            <i class="fas fa-bell"></i>
                            <span>Follow untuk update menu terbaru</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-form-section">
        <div class="container">
            <div class="form-wrapper">
                <div class="form-info">
                    <div class="section-badge">
                        <i class="fas fa-paper-plane"></i>
                        <span>Kirim Pesan</span>
                    </div>
                    <h2 class="section-title">Ada Pertanyaan?</h2>
                    <p class="section-subtitle">Jangan ragu untuk menghubungi kami! Tim profesional kami siap membantu mewujudkan acara istimewa Anda.</p>
                    
                    <div class="form-features">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="feature-content">
                                <h4>Respon Cepat</h4>
                                <p>Balasan dalam 2 jam kerja</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div class="feature-content">
                                <h4>Konsultasi Gratis</h4>
                                <p>Menu & planning acara</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <div class="feature-content">
                                <h4>Quotation Detail</h4>
                                <p>Harga transparan & akurat</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="feature-content">
                                <h4>Solusi Budget</h4>
                                <p>Sesuai kebutuhan Anda</p>
                            </div>
                        </div>
                    </div>

                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <i class="fas fa-quote-left quote-icon"></i>
                            <p>"Pelayanan sangat memuaskan! Tim Dapoer Aisyah sangat responsif dan membantu dalam planning menu acara kami."</p>
                            <div class="testimonial-author">
                                <div class="author-info">
                                    <strong>Ibu Sarah</strong>
                                    <span>Jakarta Selatan</span>
                                </div>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contact-form-container">
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <div class="alert-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="alert-content">
                                <h4>Pesan Berhasil Terkirim!</h4>
                                <p>Terima kasih! Pesan Anda telah kami terima. Tim kami akan menghubungi Anda dalam 2-4 jam kerja.</p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-error">
                            <div class="alert-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="alert-content">
                                <h4>Oops! Ada Kesalahan</h4>
                                <p><?php echo htmlspecialchars($error); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="contact-form" id="contactForm">
                        <div class="form-header">
                            <h3>Form Kontak</h3>
                            <p>Isi form di bawah dengan lengkap</p>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">
                                    <i class="fas fa-user"></i>
                                    Nama Lengkap *
                                </label>
                                <input type="text" id="name" name="name" required
                                       placeholder="Masukkan nama lengkap Anda"
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
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
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i>
                                Email *
                            </label>
                            <input type="email" id="email" name="email" required
                                   placeholder="nama@email.com"
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="subject">
                                <i class="fas fa-tag"></i>
                                Subjek
                            </label>
                            <select id="subject" name="subject">
                                <option value="">Pilih subjek pesan...</option>
                                <option value="Pemesanan Catering" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Pemesanan Catering') ? 'selected' : ''; ?>>🍽️ Pemesanan Catering</option>
                                <option value="Konsultasi Menu" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Konsultasi Menu') ? 'selected' : ''; ?>>💬 Konsultasi Menu</option>
                                <option value="Pertanyaan Harga" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Pertanyaan Harga') ? 'selected' : ''; ?>>💰 Pertanyaan Harga</option>
                                <option value="Kerjasama" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Kerjasama') ? 'selected' : ''; ?>>🤝 Kerjasama</option>
                                <option value="Keluhan" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Keluhan') ? 'selected' : ''; ?>>😔 Keluhan</option>
                                <option value="Lainnya" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Lainnya') ? 'selected' : ''; ?>>📝 Lainnya</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">
                                <i class="fas fa-comment-alt"></i>
                                Pesan *
                            </label>
                            <textarea id="message" name="message" required 
                                      placeholder="Ceritakan kebutuhan catering Anda...&#10;&#10;Contoh:&#10;- Tanggal acara: &#10;- Jumlah porsi: &#10;- Jenis acara: &#10;- Budget: &#10;- Lokasi: &#10;- Menu preferensi:"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-submit">
                            <span class="btn-text">Kirim Pesan</span>
                            <i class="btn-icon fas fa-paper-plane"></i>
                        </button>

                        <p class="form-note">
                            <i class="fas fa-info-circle"></i>
                            Dengan mengirim pesan ini, Anda menyetujui bahwa kami akan menghubungi Anda untuk keperluan follow-up.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-question-circle"></i>
                    <span>FAQ</span>
                </div>
                <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
                <p class="section-subtitle">Temukan jawaban untuk pertanyaan umum seputar layanan catering kami</p>
            </div>

            <div class="faq-grid">
                <div class="faq-card">
                    <div class="faq-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h3>Berapa minimal order catering?</h3>
                    <p>Minimal order kami adalah <strong>20 porsi</strong> untuk paket catering. Untuk snack box minimal <strong>50 pcs</strong>. Kami juga melayani order dalam jumlah besar untuk event korporat.</p>
                </div>

                <div class="faq-card">
                    <div class="faq-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Berapa lama waktu pemesanan?</h3>
                    <p>Untuk pemesanan reguler, mohon pesan minimal <strong>H-2</strong>. Untuk event besar (500+ porsi) atau menu khusus, sebaiknya pesan <strong>H-7</strong> untuk hasil terbaik.</p>
                </div>

                <div class="faq-card">
                    <div class="faq-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Apakah ada biaya pengiriman?</h3>
                    <p><strong>Gratis ongkir</strong> untuk area Jakarta dengan minimal order Rp 500.000. Untuk area luar Jakarta, biaya pengiriman akan disesuaikan dengan jarak lokasi.</p>
                </div>

                <div class="faq-card">
                    <div class="faq-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3>Bagaimana sistem pembayaran?</h3>
                    <p>Kami menerima pembayaran via <strong>transfer bank, e-wallet</strong> (OVO, GoPay, DANA), atau <strong>cash on delivery</strong>. DP 50% untuk order di atas Rp 2 juta.</p>
                </div>

                <div class="faq-card">
                    <div class="faq-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h3>Apakah bisa custom menu?</h3>
                    <p><strong>Tentu saja!</strong> Kami bisa menyesuaikan menu sesuai selera, budget, dan kebutuhan khusus Anda. <strong>Konsultasi gratis</strong> dengan chef kami.</p>
                </div>

                <div class="faq-card">
                    <div class="faq-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h3>Bagaimana jika ingin cancel order?</h3>
                    <p>Pembatalan bisa dilakukan maksimal <strong>H-1</strong> dengan pengembalian 80% dari total pembayaran. Untuk pembatalan di hari H, tidak ada pengembalian dana.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="map-content">
                <div class="map-info">
                    <div class="section-badge">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Lokasi Kami</span>
                    </div>
                    <h2 class="section-title">Dapoer Aisyah Central Kitchen</h2>
                    <p class="section-subtitle">Kunjungi dapur pusat kami atau hubungi untuk delivery ke lokasi Anda</p>

                    <div class="location-details">
                        <div class="location-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Alamat</strong>
                                <p>Jl. Raya Bogor No. 123, Jakarta Timur 13750</p>
                            </div>
                        </div>

                        <div class="location-features">
                            <div class="feature">
                                <i class="fas fa-parking"></i>
                                <span>Parkir Luas</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-store"></i>
                                <span>Showroom Menu</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-eye"></i>
                                <span>Kitchen Tour</span>
                            </div>
                        </div>

                        <div class="operating-hours">
                            <h4><i class="fas fa-clock"></i> Jam Operasional:</h4>
                            <div class="hours-grid">
                                <div class="hours-item">
                                    <span>Senin - Jumat</span>
                                    <strong>07:00 - 21:00</strong>
                                </div>
                                <div class="hours-item">
                                    <span>Sabtu - Minggu</span>
                                    <strong>08:00 - 20:00</strong>
                                </div>
                            </div>
                        </div>

                        <a href="https://maps.google.com" target="_blank" class="btn btn-primary">
                            <i class="fas fa-directions"></i>
                            Buka Google Maps
                        </a>
                    </div>
                </div>

                <div class="map-placeholder">
                    <div class="map-visual">
                        <div class="map-icon">
                            <i class="fas fa-map"></i>
                        </div>
                        <h3>Interactive Map</h3>
                        <p>Klik tombol di bawah untuk melihat lokasi kami di Google Maps dengan navigasi lengkap</p>
                        <div class="map-features">
                            <span><i class="fas fa-route"></i> Navigasi GPS</span>
                            <span><i class="fas fa-street-view"></i> Street View</span>
                            <span><i class="fas fa-traffic-light"></i> Info Traffic</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Contact Page Specific Styles */
.contact-hero {
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
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.hero-features {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
}

.hero-feature {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    backdrop-filter: blur(10px);
}

.hero-feature i {
    font-size: 1.2rem;
}

/* Contact Info Section */
.contact-info-section {
    padding: 6rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.contact-card {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.contact-card::before {
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

.contact-card:hover::before {
    transform: scaleX(1);
}

.contact-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.card-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.card-header h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
}

.contact-details {
    margin-bottom: 1.5rem;
}

.primary-contact,
.secondary-contact {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    font-size: 1.1rem;
}

.primary-contact {
    font-weight: 600;
    color: #2c5530;
}

.secondary-contact {
    color: #666;
}

.contact-hours {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 2rem;
    color: #666;
    font-size: 0.9rem;
}

.card-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.contact-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    flex: 1;
    justify-content: center;
    min-width: 120px;
}

.whatsapp-btn {
    background: #25D366;
    color: white;
}

.whatsapp-btn:hover {
    background: #128C7E;
    transform: translateY(-2px);
}

.phone-btn {
    background: #007bff;
    color: white;
}

.phone-btn:hover {
    background: #0056b3;
    transform: translateY(-2px);
}

.email-btn {
    background: #dc3545;
    color: white;
}

.email-btn:hover {
    background: #c82333;
    transform: translateY(-2px);
}

.maps-btn {
    background: #28a745;
    color: white;
}

.maps-btn:hover {
    background: #1e7e34;
    transform: translateY(-2px);
}

.social-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.social-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
    justify-content: center;
}

.social-btn.instagram {
    background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);
    color: white;
}

.social-btn.facebook {
    background: #1877f2;
    color: white;
}

.social-btn.tiktok {
    background: #000;
    color: white;
}

.social-btn.youtube {
    background: #ff0000;
    color: white;
}

.social-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Contact Form Section */
.contact-form-section {
    padding: 6rem 0;
    background: white;
}

.form-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: start;
}

.form-info {
    padding-right: 2rem;
}

.form-features {
    margin: 3rem 0;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.feature-item:hover {
    background: #e9ecef;
    transform: translateX(10px);
}

.feature-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.feature-content h4 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #333;
}

.feature-content p {
    color: #666;
    font-size: 0.9rem;
}

.testimonial-card {
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    color: white;
    padding: 2rem;
    border-radius: 20px;
    position: relative;
    margin-top: 2rem;
}

.quote-icon {
    position: absolute;
    top: 1rem;
    right: 1.5rem;
    font-size: 2rem;
    opacity: 0.3;
}

.testimonial-content p {
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    font-style: italic;
}

.testimonial-author {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.author-info strong {
    display: block;
    font-size: 1.1rem;
}

.author-info span {
    opacity: 0.8;
    font-size: 0.9rem;
}

.rating {
    color: #ffd700;
}

/* Contact Form Container */
.contact-form-container {
    background: #f8f9fa;
    padding: 3rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.form-header {
    text-align: center;
    margin-bottom: 2rem;
}

.form-header h3 {
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.form-header p {
    color: #666;
}

.alert {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.alert-success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-error {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert-icon {
    font-size: 1.5rem;
}

.alert-content h4 {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.contact-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 1rem;
    border: 2px solid #e1e5e9;
    border-radius: 12px;
    outline: none;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #2c5530;
    box-shadow: 0 0 0 3px rgba(44, 85, 48, 0.1);
}

.form-group textarea {
    min-height: 120px;
    resize: vertical;
    font-family: inherit;
}

.btn-submit {
    padding: 1.25rem 2rem;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(44, 85, 48, 0.3);
}

.btn-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.form-note {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #666;
    text-align: center;
    margin-top: 1rem;
}

/* FAQ Section */
.faq-section {
    padding: 6rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-top: 4rem;
}

.faq-card {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.faq-card::before {
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

.faq-card:hover::before {
    transform: scaleX(1);
}

.faq-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.faq-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
}

.faq-card h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
}

.faq-card p {
    color: #666;
    line-height: 1.6;
}

/* Map Section */
.map-section {
    padding: 6rem 0;
    background: white;
}

.map-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.location-details {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.location-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 12px;
}

.location-item i {
    color: #2c5530;
    font-size: 1.5rem;
    margin-top: 0.25rem;
}

.location-item strong {
    display: block;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    color: #333;
}

.location-item p {
    color: #666;
    margin: 0;
}

.location-features {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.location-features .feature {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #e9ecef;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.9rem;
    color: #666;
}

.location-features .feature i {
    color: #2c5530;
}

.operating-hours {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
}

.operating-hours h4 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.1rem;
    margin-bottom: 1rem;
    color: #333;
}

.hours-grid {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.hours-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

.hours-item:last-child {
    border-bottom: none;
}

.hours-item span {
    color: #666;
}

.hours-item strong {
    color: #2c5530;
}

.map-placeholder {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 20px;
    padding: 3rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.map-visual {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.map-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    margin-bottom: 1rem;
}

.map-visual h3 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.map-visual p {
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.map-features {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 2rem;
}

.map-features span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.85rem;
    color: #666;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.map-features span i {
    color: #2c5530;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-features {
        flex-direction: column;
        gap: 1rem;
    }
    
    .contact-grid {
        grid-template-columns: 1fr;
    }
    
    .form-wrapper {
        grid-template-columns: 1fr;
        gap: 3rem;
    }
    
    .form-info {
        padding-right: 0;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .faq-grid {
        grid-template-columns: 1fr;
    }
    
    .map-content {
        grid-template-columns: 1fr;
        gap: 3rem;
    }
    
    .social-grid {
        grid-template-columns: 1fr;
    }
    
    .card-actions {
        flex-direction: column;
    }
    
    .contact-btn {
        flex: none;
    }
}

/* Animation for scroll effects */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.contact-card,
.faq-card {
    animation: fadeInUp 0.6s ease-out;
}
</style>

<script>
// Form validation and enhancement
document.getElementById('contactForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('.btn-submit');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnIcon = submitBtn.querySelector('.btn-icon');
    
    submitBtn.disabled = true;
    btnText.textContent = 'Mengirim...';
    btnIcon.className = 'btn-icon fas fa-spinner fa-spin';
    
    // Re-enable after 3 seconds if form doesn't submit
    setTimeout(() => {
        if (submitBtn.disabled) {
            submitBtn.disabled = false;
            btnText.textContent = 'Kirim Pesan';
            btnIcon.className = 'btn-icon fas fa-paper-plane';
        }
    }, 3000);
});

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
document.querySelectorAll('.contact-card, .faq-card, .feature-item').forEach(element => {
    element.style.opacity = '0';
    element.style.transform = 'translateY(30px)';
    element.style.transition = 'all 0.6s ease';
    observer.observe(element);
});

// Phone number formatting
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.startsWith('0')) {
        value = value.substring(1);
    }
    if (value.length > 0) {
        if (value.length <= 4) {
            value = value;
        } else if (value.length <= 8) {
            value = value.substring(0, 4) + '-' + value.substring(4);
        } else {
            value = value.substring(0, 4) + '-' + value.substring(4, 8) + '-' + value.substring(8, 12);
        }
        e.target.value = '0' + value;
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
