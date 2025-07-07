-- Sample data for Dapoer Aisyah
USE dapoer_aisyah;

-- Insert categories
INSERT INTO categories (name, slug, description) VALUES
('Catering', 'catering', 'Layanan catering untuk berbagai acara'),
('Nasi Box', 'nasi-box', 'Nasi box praktis dan lezat'),
('Snack', 'snack', 'Berbagai pilihan snack dan cemilan'),
('Jajanan Pasar', 'jajanan-pasar', 'Jajanan tradisional khas Indonesia');

-- Insert sample admin user
INSERT INTO users (name, email, password, phone, role) VALUES
('Admin Dapoer Aisyah', 'admin@dapoerisyah.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', 'admin');

-- Insert sample customer users
INSERT INTO users (name, email, password, phone, address) VALUES
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567891', 'Jl. Sudirman No. 123, Jakarta'),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567892', 'Jl. Thamrin No. 456, Jakarta'),
('Bob Wilson', 'bob@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567893', 'Jl. Gatot Subroto No. 789, Jakarta');

-- Insert products
INSERT INTO products (name, slug, description, price, category_id, stock, rating) VALUES
('Nasi Box Ayam Bakar', 'nasi-box-ayam-bakar', 'Nasi putih dengan ayam bakar bumbu kecap, lalapan, dan sambal', 25000.00, 2, 50, 4.8),
('Nasi Box Rendang', 'nasi-box-rendang', 'Nasi putih dengan rendang daging sapi dan pelengkap', 28000.00, 2, 40, 4.8),
('Nasi Box Ayam Geprek', 'nasi-box-ayam-geprek', 'Nasi putih dengan ayam geprek pedas dan lalapan', 23000.00, 2, 60, 4.7),
('Paket Catering 50 Pax', 'paket-catering-50-pax', 'Paket lengkap untuk 50 orang dengan menu pilihan', 750000.00, 1, 10, 4.7),
('Paket Catering 100 Pax', 'paket-catering-100-pax', 'Paket catering untuk 100 orang dengan menu premium', 1400000.00, 1, 5, 4.9),
('Paket Catering 25 Pax', 'paket-catering-25-pax', 'Paket catering untuk 25 orang cocok untuk gathering kecil', 400000.00, 1, 15, 4.6),
('Risol Mayo Special', 'risol-mayo-special', 'Risol crispy dengan isian sayuran dan mayo spesial', 8000.00, 3, 100, 4.9),
('Martabak Mini', 'martabak-mini', 'Martabak mini dengan isian telur dan daging', 6000.00, 3, 80, 4.5),
('Pastel Ayam', 'pastel-ayam', 'Pastel dengan isian ayam dan sayuran', 7000.00, 3, 90, 4.6),
('Jajanan Pasar Mix', 'jajanan-pasar-mix', 'Kombinasi klepon, onde-onde, dan kue tradisional', 15000.00, 4, 30, 4.6),
('Klepon Traditional', 'klepon-traditional', 'Klepon isi gula merah dengan kelapa parut segar', 12000.00, 4, 50, 4.7),
('Onde-onde Wijen', 'onde-onde-wijen', 'Onde-onde dengan isian kacang hijau dan wijen', 10000.00, 4, 60, 4.5),
('Kue Lapis Legit', 'kue-lapis-legit', 'Kue lapis dengan rasa legit dan tekstur lembut', 18000.00, 4, 25, 4.8),
('Lemper Ayam', 'lemper-ayam', 'Lemper dengan isian ayam bumbu kuning', 9000.00, 4, 70, 4.4);

-- Insert sample orders
INSERT INTO orders (user_id, order_number, total_amount, delivery_fee, status, payment_method, delivery_method, customer_name, customer_phone, customer_email, delivery_address) VALUES
(2, 'DA2024001', 125000.00, 15000.00, 'completed', 'transfer', 'delivery', 'John Doe', '081234567891', 'john@example.com', 'Jl. Sudirman No. 123, Jakarta'),
(3, 'DA2024002', 75000.00, 0.00, 'processing', 'cod', 'pickup', 'Jane Smith', '081234567892', 'jane@example.com', NULL),
(4, 'DA2024003', 200000.00, 15000.00, 'pending', 'ewallet', 'delivery', 'Bob Wilson', '081234567893', 'bob@example.com', 'Jl. Gatot Subroto No. 789, Jakarta');

-- Insert order items
INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 3, 25000.00),
(1, 7, 5, 8000.00),
(1, 10, 2, 15000.00),
(2, 2, 2, 28000.00),
(2, 8, 3, 6000.00),
(3, 4, 1, 750000.00),
(3, 11, 4, 12000.00);

-- Insert sample reviews
INSERT INTO reviews (user_id, product_id, rating, comment) VALUES
(2, 1, 5, 'Nasi box ayam bakarnya enak banget! Porsi pas dan bumbu meresap'),
(3, 2, 5, 'Rendangnya authentic, rasanya seperti buatan nenek'),
(4, 4, 4, 'Pelayanan catering bagus, makanan fresh dan tepat waktu'),
(2, 7, 5, 'Risol mayo favorit keluarga, crispy dan isian melimpah'),
(3, 10, 4, 'Jajanan pasar mix lengkap, rasa tradisional yang otentik');
