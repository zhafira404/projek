-- Create gallery table for gallery page
CREATE TABLE IF NOT EXISTS gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    category VARCHAR(50) NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    alt_text VARCHAR(200),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_category (category),
    INDEX idx_is_active (is_active),
    INDEX idx_sort_order (sort_order),
    INDEX idx_created_at (created_at)
);

-- Insert sample gallery data
INSERT INTO gallery (title, description, category, image_url, alt_text, sort_order, is_active) VALUES
('Nasi Gudeg Jogja', 'Gudeg autentik dengan cita rasa manis khas Yogyakarta, dimasak dengan santan dan bumbu rempah pilihan', 'makanan-utama', 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=600&h=400&fit=crop', 'Nasi Gudeg Jogja dengan lauk lengkap', 1, TRUE),

('Rendang Daging Sapi', 'Rendang dengan bumbu rempah pilihan, dimasak hingga empuk dan bumbu meresap sempurna', 'makanan-utama', 'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=600&h=400&fit=crop', 'Rendang daging sapi dengan bumbu khas Padang', 2, TRUE),

('Sate Ayam Madura', 'Sate ayam dengan bumbu kacang khas Madura yang gurih dan pedas pas di lidah', 'makanan-utama', 'https://images.unsplash.com/photo-1529563021893-cc83c992d75d?w=600&h=400&fit=crop', 'Sate ayam Madura dengan bumbu kacang', 3, TRUE),

('Gado-gado Jakarta', 'Salad sayuran segar dengan bumbu kacang yang lezat, dilengkapi kerupuk dan lontong', 'makanan-sehat', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop', 'Gado-gado Jakarta dengan sayuran segar', 4, TRUE),

('Soto Betawi', 'Soto khas Betawi dengan kuah santan yang gurih, daging sapi dan jeroan pilihan', 'makanan-utama', 'https://images.unsplash.com/photo-1547592180-85f173990554?w=600&h=400&fit=crop', 'Soto Betawi dengan kuah santan gurih', 5, TRUE),

('Es Cendol', 'Minuman segar dengan cendol, santan, dan gula merah, cocok untuk cuaca panas', 'minuman', 'https://images.unsplash.com/photo-1541544181051-e46607bc22a4?w=600&h=400&fit=crop', 'Es cendol dengan santan dan gula merah', 6, TRUE),

('Klepon', 'Kue tradisional dengan isian gula merah dan kelapa parut, manis dan legit', 'dessert', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=600&h=400&fit=crop', 'Klepon dengan kelapa parut dan gula merah', 7, TRUE),

('Lumpia Semarang', 'Lumpia segar dengan isian rebung dan udang, disajikan dengan saus kacang', 'snack', 'https://images.unsplash.com/photo-1563379091339-03246963d96a?w=600&h=400&fit=crop', 'Lumpia Semarang dengan isian rebung udang', 8, TRUE),

('Nasi Tumpeng', 'Tumpeng untuk acara syukuran dan perayaan, dilengkapi lauk pauk tradisional', 'paket-acara', 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=600&h=400&fit=crop', 'Nasi tumpeng untuk acara syukuran', 9, TRUE),

('Ayam Bakar Taliwang', 'Ayam bakar dengan bumbu pedas khas Lombok, dibakar hingga matang sempurna', 'makanan-utama', 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?w=600&h=400&fit=crop', 'Ayam bakar Taliwang dengan bumbu pedas', 10, TRUE),

('Pecel Lele', 'Lele goreng dengan sambal pecel yang pedas, disajikan dengan lalapan segar', 'makanan-utama', 'https://images.unsplash.com/photo-1565299507177-b0ac66763828?w=600&h=400&fit=crop', 'Pecel lele dengan sambal pedas', 11, TRUE),

('Es Dawet', 'Minuman tradisional dengan dawet dan santan, manis dan menyegarkan', 'minuman', 'https://images.unsplash.com/photo-1541544181051-e46607bc22a4?w=600&h=400&fit=crop', 'Es dawet dengan santan manis', 12, TRUE),

('Rawon Surabaya', 'Rawon khas Surabaya dengan kuah hitam yang kaya rempah dan daging empuk', 'makanan-utama', 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=600&h=400&fit=crop', 'Rawon Surabaya dengan kuah hitam', 13, TRUE),

('Bakso Malang', 'Bakso dengan berbagai varian isi, kuah kaldu sapi yang gurih dan mie kuning', 'makanan-utama', 'https://images.unsplash.com/photo-1547592180-85f173990554?w=600&h=400&fit=crop', 'Bakso Malang dengan kuah kaldu', 14, TRUE),

('Kerak Telor', 'Kerak telor khas Betawi dengan telur, beras ketan, dan serundeng kelapa', 'snack', 'https://images.unsplash.com/photo-1563379091339-03246963d96a?w=600&h=400&fit=crop', 'Kerak telor Betawi tradisional', 15, TRUE),

('Martabak Manis', 'Martabak manis dengan berbagai topping cokelat, keju, dan kacang', 'dessert', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=600&h=400&fit=crop', 'Martabak manis dengan topping lengkap', 16, TRUE),

('Paket Nasi Box', 'Paket nasi box untuk acara kantor dan gathering, praktis dan higienis', 'paket-acara', 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=600&h=400&fit=crop', 'Paket nasi box untuk acara', 17, TRUE),

('Es Teh Manis', 'Es teh manis segar, minuman pendamping yang pas untuk semua menu', 'minuman', 'https://images.unsplash.com/photo-1541544181051-e46607bc22a4?w=600&h=400&fit=crop', 'Es teh manis segar', 18, TRUE),

('Capcay Kuah', 'Capcay kuah dengan sayuran segar dan kuah kaldu yang gurih', 'makanan-sehat', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop', 'Capcay kuah dengan sayuran segar', 19, TRUE),

('Paket Prasmanan', 'Paket prasmanan untuk acara besar dengan berbagai pilihan menu', 'paket-acara', 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=600&h=400&fit=crop', 'Paket prasmanan untuk acara besar', 20, TRUE);

-- Show gallery statistics
SELECT 
    category,
    COUNT(*) as total_items,
    COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_items
FROM gallery 
GROUP BY category 
ORDER BY total_items DESC;
