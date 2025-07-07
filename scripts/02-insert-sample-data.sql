-- Script untuk insert data sample yang lengkap
-- Jalankan setelah 01-create-database.sql

USE dapoer_aisyah;

-- Insert settings
INSERT INTO settings (key_name, value, description, type) VALUES
('site_name', 'Dapoer Aisyah', 'Nama website', 'string'),
('site_tagline', 'Kelezatan Autentik Indonesia', 'Tagline website', 'string'),
('site_description', 'Layanan catering terbaik dengan cita rasa autentik Indonesia', 'Deskripsi website', 'string'),
('contact_phone', '+62 812-3456-7890', 'Nomor telepon kontak', 'string'),
('contact_email', 'info@dapoerisyah.com', 'Email kontak', 'string'),
('contact_address', 'Jl. Raya Bogor No. 123, Jakarta Timur 13750', 'Alamat lengkap', 'string'),
('business_hours', '{"monday":"08:00-20:00","tuesday":"08:00-20:00","wednesday":"08:00-20:00","thursday":"08:00-20:00","friday":"08:00-20:00","saturday":"08:00-20:00","sunday":"08:00-20:00"}', 'Jam operasional', 'json'),
('delivery_fee', '15000', 'Biaya pengiriman', 'integer'),
('free_delivery_minimum', '100000', 'Minimum pembelian untuk gratis ongkir', 'integer'),
('tax_rate', '10', 'Persentase pajak', 'integer'),
('currency', 'IDR', 'Mata uang', 'string'),
('max_cod_amount', '500000', 'Maksimal COD', 'integer');

-- Insert admin user (password: admin123)
INSERT INTO users (name, email, password, phone, address, city, role, email_verified, is_active) VALUES
('Admin Dapoer Aisyah', 'admin@dapoerisyah.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', '081234567890', 'Jl. Raya Bogor No. 123', 'Jakarta Timur', 'admin', TRUE, TRUE),
('Manager Dapoer Aisyah', 'manager@dapoerisyah.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', '081234567891', 'Jl. Raya Bogor No. 123', 'Jakarta Timur', 'admin', TRUE, TRUE);

-- Insert sample customers (password: password123)
INSERT INTO users (name, email, password, phone, address, city, postal_code, birth_date, gender, email_verified) VALUES
('Budi Santoso', 'budi@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', '081234567892', 'Jl. Sudirman No. 123', 'Jakarta Pusat', '10110', '1985-05-15', 'male', TRUE),
('Siti Nurhaliza', 'siti@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', '081234567893', 'Jl. Thamrin No. 456', 'Jakarta Pusat', '10230', '1990-08-22', 'female', TRUE),
('Ahmad Ridwan', 'ahmad@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', '081234567894', 'Jl. Gatot Subroto No. 789', 'Jakarta Selatan', '12190', '1988-12-03', 'male', TRUE),
('Dewi Sartika', 'dewi@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', '081234567895', 'Jl. Kemang Raya No. 234', 'Jakarta Selatan', '12560', '1992-03-18', 'female', TRUE),
('Reza Pratama', 'reza@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', '081234567896', 'Jl. Menteng No. 567', 'Jakarta Pusat', '10310', '1987-07-28', 'male', TRUE);

-- Insert categories with detailed information
INSERT INTO categories (name, slug, description, icon, sort_order, meta_title, meta_description) VALUES
('Catering', 'catering', 'Layanan catering untuk berbagai acara seperti pernikahan, ulang tahun, gathering kantor, dan acara spesial lainnya dengan menu lengkap dan pelayanan terbaik.', 'fas fa-users', 1, 'Layanan Catering Terbaik - Dapoer Aisyah', 'Layanan catering untuk berbagai acara dengan menu autentik Indonesia dan pelayanan profesional'),
('Nasi Box', 'nasi-box', 'Nasi box praktis dan lezat dengan berbagai pilihan lauk pauk tradisional Indonesia. Cocok untuk meeting, acara kantor, atau makan siang keluarga.', 'fas fa-box', 2, 'Nasi Box Lezat - Dapoer Aisyah', 'Nasi box dengan menu tradisional Indonesia, praktis dan lezat untuk berbagai acara'),
('Snack & Cemilan', 'snack', 'Berbagai pilihan snack dan cemilan tradisional maupun modern. Dari risol, martabak mini, hingga kue-kue tradisional yang cocok untuk berbagai acara.', 'fas fa-cookie-bite', 3, 'Snack dan Cemilan Tradisional - Dapoer Aisyah', 'Aneka snack dan cemilan tradisional Indonesia yang lezat dan berkualitas'),
('Jajanan Pasar', 'jajanan-pasar', 'Jajanan pasar tradisional khas Indonesia seperti klepon, onde-onde, lemper, dan berbagai kue tradisional lainnya dengan cita rasa autentik.', 'fas fa-birthday-cake', 4, 'Jajanan Pasar Tradisional - Dapoer Aisyah', 'Jajanan pasar tradisional Indonesia dengan rasa autentik dan kualitas terjamin'),
('Minuman', 'minuman', 'Berbagai pilihan minuman segar dan tradisional untuk melengkapi hidangan Anda. Dari es teh manis hingga jus buah segar.', 'fas fa-glass-whiskey', 5, 'Minuman Segar dan Tradisional - Dapoer Aisyah', 'Aneka minuman segar dan tradisional untuk melengkapi menu Anda');

-- Insert detailed products
INSERT INTO products (name, slug, description, short_description, price, sku, category_id, stock, weight, is_featured, ingredients, preparation_time, spice_level, serving_size) VALUES
-- Nasi Box Category
('Nasi Box Ayam Bakar', 'nasi-box-ayam-bakar', 'Nasi putih hangat dengan ayam bakar bumbu kecap manis yang meresap sempurna, dilengkapi lalapan segar (timun, tomat, kemangi), sambal terasi pedas, dan kerupuk rambak. Porsi yang mengenyangkan dengan cita rasa autentik Indonesia.', 'Nasi putih dengan ayam bakar bumbu kecap, lalapan, dan sambal', 25000.00, 'NB001', 2, 100, 450.00, TRUE, 'Nasi putih, ayam kampung, kecap manis, bawang merah, bawang putih, kemiri, ketumbar, jahe, kunyit, daun salam, serai, santan, gula merah, garam, timun, tomat, kemangi, cabai rawit, terasi, kerupuk', 45, 'medium', '1 porsi'),

('Nasi Box Rendang Daging', 'nasi-box-rendang-daging', 'Nasi putih dengan rendang daging sapi yang dimasak dengan santan kental dan rempah-rempah pilihan hingga bumbu meresap sempurna. Dilengkapi dengan kerupuk dan sambal andaliman yang menggugah selera.', 'Nasi putih dengan rendang daging sapi autentik', 32000.00, 'NB002', 2, 80, 500.00, TRUE, 'Nasi putih, daging sapi, santan, cabai merah, bawang merah, bawang putih, jahe, lengkuas, kunyit, ketumbar, jintan, pala, cengkeh, kayu manis, daun jeruk, serai, daun kunyit, gula merah, garam, kerupuk', 90, 'medium', '1 porsi'),

('Nasi Box Ayam Geprek', 'nasi-box-ayam-geprek', 'Nasi putih dengan ayam goreng crispy yang dipukul dan dicampur dengan sambal bawang pedas menyengat. Dilengkapi lalapan segar dan kerupuk. Cocok untuk pencinta pedas sejati!', 'Nasi putih dengan ayam crispy geprek pedas', 23000.00, 'NB003', 2, 120, 480.00, TRUE, 'Nasi putih, ayam broiler, tepung terigu, tepung tapioka, cabai rawit, cabai merah, bawang putih, bawang merah, tomat, garam, gula, minyak goreng, timun, kemangi, kerupuk', 35, 'hot', '1 porsi'),

('Nasi Box Ikan Bakar', 'nasi-box-ikan-bakar', 'Nasi putih dengan ikan tongkol bakar yang dibumbui dengan bumbu kuning khas Indonesia. Dilengkapi sambal tomat segar dan lalapan. Pilihan sehat dan lezat.', 'Nasi putih dengan ikan bakar bumbu kuning', 28000.00, 'NB004', 2, 70, 450.00, FALSE, 'Nasi putih, ikan tongkol, kunyit, jahe, bawang merah, bawang putih, kemiri, ketumbar, cabai merah, tomat, timun, kemangi, daun pisang, minyak kelapa', 40, 'mild', '1 porsi'),

('Nasi Box Gudeg Jogja', 'nasi-box-gudeg-jogja', 'Nasi putih dengan gudeg khas Jogja yang manis legit, dilengkapi ayam kampung, telur pindang, krecek, dan sambal krecek. Cita rasa tradisional Jogja yang autentik.', 'Nasi putih dengan gudeg Jogja, ayam, dan telur pindang', 30000.00, 'NB005', 2, 60, 520.00, TRUE, 'Nasi putih, nangka muda, santan, gula merah, daun salam, lengkuas, bawang putih, ketumbar, ayam kampung, telur, kulit sapi, cabai rawit, terasi', 120, 'mild', '1 porsi'),

-- Catering Packages
('Paket Catering Intimate 25 Pax', 'paket-catering-25-pax', 'Paket catering untuk 25 orang dengan menu lengkap: nasi putih, ayam bumbu bali, rendang daging, sayur lodeh, kerupuk, dan es teh manis. Cocok untuk gathering keluarga atau acara intimate.', 'Paket lengkap untuk 25 orang dengan menu pilihan', 450000.00, 'CT001', 1, 20, 12500.00, TRUE, 'Menu lengkap: Nasi putih, ayam bumbu bali, rendang daging, sayur lodeh, sambal, kerupuk, es teh manis', 180, 'medium', '25 porsi'),

('Paket Catering Standard 50 Pax', 'paket-catering-50-pax', 'Paket catering untuk 50 orang dengan menu premium: nasi putih, ayam bakar madu, gulai kambing, tumis kangkung, gado-gado, kerupuk, dan minuman. Ideal untuk acara kantor atau perayaan keluarga besar.', 'Paket premium untuk 50 orang dengan menu spesial', 850000.00, 'CT002', 1, 15, 25000.00, TRUE, 'Menu premium: Nasi putih, ayam bakar madu, gulai kambing, tumis kangkung, gado-gado, sambal, kerupuk, minuman', 240, 'medium', '50 porsi'),

('Paket Catering Deluxe 100 Pax', 'paket-catering-100-pax', 'Paket catering deluxe untuk 100 orang dengan menu mewah: nasi kuning, ayam bakar bumbu rujak, rendang daging, gulai ikan, sayur asem, gado-gado, kerupuk, dan aneka minuman. Perfect untuk wedding atau corporate event.', 'Paket mewah untuk 100 orang dengan menu deluxe', 1650000.00, 'CT003', 1, 8, 50000.00, TRUE, 'Menu deluxe: Nasi kuning, ayam bakar bumbu rujak, rendang daging, gulai ikan, sayur asem, gado-gado, sambal, kerupuk, aneka minuman, buah', 300, 'medium', '100 porsi'),

-- Snacks
('Risol Mayo Special', 'risol-mayo-special', 'Risol crispy dengan kulit yang renyah berisi campuran sayuran segar (wortel, kol, tauge) dan mayonnaise spesial. Digoreng hingga golden brown dan disajikan hangat. Cemilan favorit untuk segala usia.', 'Risol crispy dengan isian sayuran dan mayo spesial', 8000.00, 'SN001', 3, 200, 80.00, TRUE, 'Kulit lumpia, wortel, kol, tauge, mayonnaise, bawang bombay, garam, merica, minyak goreng, tepung terigu', 25, 'mild', '1 buah'),

('Martabak Mini Daging', 'martabak-mini-daging', 'Martabak mini dengan kulit tipis dan crispy berisi daging giling yang dibumbui dengan bawang bombay dan rempah-rempah. Ukuran mini yang pas untuk cemilan atau appetizer.', 'Martabak mini dengan isian daging dan bumbu', 6000.00, 'SN002', 3, 150, 70.00, FALSE, 'Kulit martabak, daging sapi giling, bawang bombay, bawang putih, ketumbar, garam, merica, daun bawang, minyak goreng', 20, 'mild', '1 buah'),

('Pastel Ayam Jamur', 'pastel-ayam-jamur', 'Pastel dengan kulit renyah berisi ayam suwir yang dicampur dengan jamur kancing dan sayuran. Cita rasa gurih dan mengenyangkan, cocok untuk cemilan sore.', 'Pastel dengan isian ayam suwir dan jamur', 7500.00, 'SN003', 3, 120, 85.00, FALSE, 'Kulit pastel, ayam fillet, jamur kancing, wortel, kentang, bawang bombay, bawang putih, daun bawang, garam, merica, kaldu ayam', 30, 'mild', '1 buah'),

-- Traditional Snacks
('Klepon Traditional', 'klepon-traditional', 'Klepon dengan kulit kenyal dari tepung ketan yang diisi gula merah cair, direbus hingga matang dan digulingkan dalam kelapa parut segar. Manisnya pas dan tekstur yang lembut di mulut.', 'Klepon isi gula merah dengan kelapa parut segar', 12000.00, 'JP001', 4, 100, 150.00, TRUE, 'Tepung ketan, gula merah, kelapa parut, pandan, garam, air', 45, NULL, '5 buah'),

('Onde-onde Wijen', 'onde-onde-wijen', 'Onde-onde dengan kulit kenyal dari tepung ketan, diisi kacang hijau manis, dibaluri wijen dan digoreng hingga golden. Tekstur luar crispy dan dalam lembut dengan rasa manis yang pas.', 'Onde-onde isian kacang hijau dengan wijen', 10000.00, 'JP002', 4, 80, 120.00, FALSE, 'Tepung ketan, kacang hijau, gula pasir, kelapa parut, wijen, minyak goreng, garam', 60, NULL, '4 buah'),

('Lemper Ayam', 'lemper-ayam', 'Ketan yang dikukus dengan santan dan diberi isian ayam bumbu kuning yang gurih, dibungkus daun pisang. Aroma daun pisang yang harum menambah kelezatan lemper ini.', 'Lemper dengan isian ayam bumbu kuning', 9000.00, 'JP003', 4, 90, 100.00, FALSE, 'Beras ketan, santan, ayam fillet, kunyit, jahe, bawang merah, bawang putih, ketumbar, daun salam, serai, garam, daun pisang', 90, 'mild', '1 buah'),

('Kue Lapis Legit', 'kue-lapis-legit', 'Kue lapis dengan tekstur lembut dan rasa legit yang khas. Dibuat dengan telur, mentega, dan rempah-rempah pilihan. Setiap lapisan dipanggang dengan teliti untuk hasil yang sempurna.', 'Kue lapis dengan rasa legit dan tekstur lembut', 18000.00, 'JP004', 4, 50, 200.00, TRUE, 'Telur, mentega, tepung terigu, gula halus, susu kental manis, vanili, kayu manis, cengkeh, pala, cardamom', 180, NULL, '1 potong'),

-- Beverages
('Es Teh Manis', 'es-teh-manis', 'Es teh manis segar dengan takaran gula yang pas. Dibuat dari teh pilihan dan disajikan dengan es batu. Minuman klasik yang cocok untuk segala cuaca.', 'Teh manis dingin yang menyegarkan', 5000.00, 'MN001', 5, 300, 250.00, FALSE, 'Teh hitam, gula pasir, air, es batu', 5, NULL, '1 gelas'),

('Es Jeruk Nipis', 'es-jeruk-nipis', 'Minuman segar dari perasan jeruk nipis asli dengan tambahan gula dan es batu. Rasa asam segar yang cocok untuk menghilangkan dahaga.', 'Minuman jeruk nipis segar dengan es', 8000.00, 'MN002', 5, 200, 250.00, FALSE, 'Jeruk nipis, gula pasir, air, es batu, garam', 5, NULL, '1 gelas'),

('Jus Alpukat', 'jus-alpukat', 'Jus alpukat creamy dengan susu kental manis dan sedikit gula. Tekstur creamy dan rasa yang kaya, kaya akan nutrisi dan sangat mengenyangkan.', 'Jus alpukat creamy dengan susu kental manis', 15000.00, 'MN003', 5, 100, 300.00, TRUE, 'Alpukat matang, susu kental manis, gula pasir, es batu, air', 10, NULL, '1 gelas');

-- Insert product variants
INSERT INTO product_variants (product_id, name, sku, price, stock, is_default, attributes) VALUES
-- Nasi Box Ayam Bakar variants
(1, 'Regular', 'NB001-R', 25000.00, 80, TRUE, '{"size": "Regular", "spice": "Medium"}'),
(1, 'Large', 'NB001-L', 32000.00, 50, FALSE, '{"size": "Large", "spice": "Medium"}'),
(1, 'Extra Pedas', 'NB001-EP', 25000.00, 30, FALSE, '{"size": "Regular", "spice": "Extra Hot"}'),

-- Risol variants
(9, 'Original', 'SN001-O', 8000.00, 150, TRUE, '{"flavor": "Original"}'),
(9, 'Keju', 'SN001-K', 9000.00, 80, FALSE, '{"flavor": "Cheese"}'),

-- Jus Alpukat variants
(19, 'Regular', 'MN003-R', 15000.00, 80, TRUE, '{"size": "Regular"}'),
(19, 'Large', 'MN003-L', 20000.00, 40, FALSE, '{"size": "Large"}');

-- Insert coupons
INSERT INTO coupons (code, name, description, type, value, minimum_amount, maximum_discount, usage_limit, valid_from, valid_until) VALUES
('WELCOME10', 'Welcome Discount', 'Diskon 10% untuk pelanggan baru', 'percentage', 10.00, 50000.00, 25000.00, 100, '2024-01-01 00:00:00', '2024-12-31 23:59:59'),
('HEMAT50K', 'Hemat 50K', 'Potongan Rp 50.000 untuk pembelian minimal Rp 500.000', 'fixed', 50000.00, 500000.00, NULL, 50, '2024-01-01 00:00:00', '2024-12-31 23:59:59'),
('FREEONGKIR', 'Gratis Ongkir', 'Gratis ongkos kirim untuk pembelian minimal Rp 100.000', 'fixed', 15000.00, 100000.00, 15000.00, 200, '2024-01-01 00:00:00', '2024-12-31 23:59:59');

-- Update product ratings based on reviews (will be inserted next)
UPDATE products SET rating = 4.8, rating_count = 15 WHERE id = 1;
UPDATE products SET rating = 4.9, rating_count = 12 WHERE id = 2;
UPDATE products SET rating = 4.7, rating_count = 18 WHERE id = 3;
UPDATE products SET rating = 4.6, rating_count = 8 WHERE id = 6;
UPDATE products SET rating = 4.9, rating_count = 22 WHERE id = 7;
UPDATE products SET rating = 4.8, rating_count = 10 WHERE id = 9;
UPDATE products SET rating = 4.7, rating_count = 14 WHERE id = 12;
UPDATE products SET rating = 4.5, rating_count = 6 WHERE id = 13;
