-- Script untuk insert data orders dan reviews
-- Jalankan setelah 02-insert-sample-data.sql

USE dapoer_aisyah;

-- Insert sample orders
INSERT INTO orders (
    user_id, order_number, status, payment_status, payment_method, delivery_method,
    customer_name, customer_phone, customer_email,
    delivery_address, delivery_city, delivery_postal_code,
    subtotal, tax_amount, delivery_fee, total_amount,
    notes, confirmed_at, created_at
) VALUES
-- Order 1 - Completed
(3, 'DA-2024-0001', 'delivered', 'paid', 'transfer', 'delivery',
'Budi Santoso', '081234567892', 'budi@example.com',
'Jl. Sudirman No. 123, Menteng', 'Jakarta Pusat', '10110',
110000.00, 11000.00, 15000.00, 136000.00,
'Tolong diantar sebelum jam 12 siang',
'2024-01-15 10:30:00', '2024-01-15 09:15:00'),

-- Order 2 - Processing
(4, 'DA-2024-0002', 'preparing', 'paid', 'ewallet', 'pickup',
'Siti Nurhaliza', '081234567893', 'siti@example.com',
NULL, NULL, NULL,
75000.00, 7500.00, 0.00, 82500.00,
'Pesanan untuk acara arisan',
'2024-01-16 14:20:00', '2024-01-16 13:45:00'),

-- Order 3 - Pending
(5, 'DA-2024-0003', 'confirmed', 'pending', 'cod', 'delivery',
'Ahmad Ridwan', '081234567894', 'ahmad@example.com',
'Jl. Gatot Subroto No. 789, Setiabudi', 'Jakarta Selatan', '12190',
850000.00, 85000.00, 0.00, 935000.00,
'Catering untuk meeting kantor, mohon tepat waktu',
'2024-01-17 08:00:00', '2024-01-16 16:30:00'),

-- Order 4 - Cancelled
(6, 'DA-2024-0004', 'cancelled', 'refunded', 'transfer', 'delivery',
'Dewi Sartika', '081234567895', 'dewi@example.com',
'Jl. Kemang Raya No. 234', 'Jakarta Selatan', '12560',
56000.00, 5600.00, 15000.00, 76600.00,
'Dibatalkan karena perubahan jadwal acara',
NULL, '2024-01-14 11:20:00'),

-- Order 5 - Completed
(7, 'DA-2024-0005', 'delivered', 'paid', 'cod', 'delivery',
'Reza Pratama', '081234567896', 'reza@example.com',
'Jl. Menteng No. 567', 'Jakarta Pusat', '10310',
180000.00, 18000.00, 15000.00, 213000.00,
'Pesanan untuk ulang tahun',
'2024-01-18 15:45:00', '2024-01-18 12:30:00');

-- Insert order items
INSERT INTO order_items (order_id, product_id, variant_id, quantity, unit_price, total_price, product_name, product_sku) VALUES
-- Order 1 items
(1, 1, 1, 3, 25000.00, 75000.00, 'Nasi Box Ayam Bakar - Regular', 'NB001-R'),
(1, 9, 1, 5, 8000.00, 40000.00, 'Risol Mayo Special - Original', 'SN001-O'),

-- Order 2 items
(2, 2, NULL, 2, 32000.00, 64000.00, 'Nasi Box Rendang Daging', 'NB002'),
(2, 12, NULL, 1, 12000.00, 12000.00, 'Klepon Traditional', 'JP001'),

-- Order 3 items (Catering package)
(3, 7, NULL, 1, 850000.00, 850000.00, 'Paket Catering Standard 50 Pax', 'CT002'),

-- Order 4 items (Cancelled)
(4, 3, 1, 2, 23000.00, 46000.00, 'Nasi Box Ayam Geprek - Regular', 'NB003-R'),
(4, 13, NULL, 1, 10000.00, 10000.00, 'Onde-onde Wijen', 'JP002'),

-- Order 5 items
(5, 15, NULL, 10, 18000.00, 180000.00, 'Kue Lapis Legit', 'JP004');

-- Insert detailed reviews
INSERT INTO reviews (user_id, product_id, order_id, rating, title, comment, is_verified, created_at) VALUES
-- Reviews for Order 1
(3, 1, 1, 5, 'Ayam bakarnya juara!', 'Nasi box ayam bakarnya enak banget! Ayamnya empuk, bumbu meresap sempurna. Porsi juga pas mengenyangkan. Sambalnya mantap, tidak terlalu pedas tapi berasa. Lalapannya segar. Pasti pesan lagi!', TRUE, '2024-01-16 19:30:00'),

(3, 9, 1, 5, 'Risol crispy yang menggugah selera', 'Risolnya crispy di luar tapi lembut di dalam. Isian sayurannya fresh dan mayonya pas. Jadi cemilan favorit keluarga nih. Anak-anak suka banget. Packaging juga rapi.', TRUE, '2024-01-16 20:15:00'),

-- Reviews for Order 2
(4, 2, 2, 5, 'Rendang terenak yang pernah saya coba!', 'MasyaAllah rendangnya authentic banget! Dagingnya empuk, bumbu meresap sampai dalam. Rasa rempah-rempahnya pas, tidak terlalu asin. Benar-benar seperti buatan nenek. Highly recommended!', TRUE, '2024-01-17 14:20:00'),

(4, 12, 2, 5, 'Klepon seperti buatan mama', 'Kleponnya soft, gula merahnya meleleh di mulut. Kelapa parutnya segar. Rasanya persis seperti yang bikin mama dulu. Nostalgic banget! Packaging juga hygienic.', TRUE, '2024-01-17 14:45:00'),

-- Reviews for Order 5
(7, 15, 5, 4, 'Kue lapis yang lembut', 'Kue lapisnya lembut dan wangi rempah. Teksturnya pas tidak terlalu manis. Cocok untuk acara ulang tahun. Cuma agak tipis sih lapisannya, tapi overall enak. Akan pesan lagi untuk acara berikutnya.', TRUE, '2024-01-19 10:30:00'),

-- Additional reviews from other customers
(5, 1, NULL, 4, 'Enak tapi agak asin', 'Ayam bakarnya enak, cuma agak asin menurut saya. Mungkin bisa dikurangi garamnya. Tapi overall masih recommended. Pelayanannya juga cepat.', FALSE, '2024-01-10 16:20:00'),

(6, 7, NULL, 5, 'Catering terbaik untuk acara kantor', 'Paket catering 50 paxnya sempurna untuk meeting kantor kami. Menu lengkap, rasa enak semua, porsi pas. Tim Dapoer Aisyah juga professional dan tepat waktu. Definitely akan pakai lagi untuk acara selanjutnya.', FALSE, '2024-01-12 09:45:00'),

(3, 9, NULL, 5, 'Cemilan favorit keluarga', 'Udah langganan beli risol di sini. Selalu consistent rasanya. Anak-anak suka banget. Harga juga reasonable. Kadang beli untuk bekel kerja juga.', FALSE, '2024-01-08 18:30:00'),

(4, 3, NULL, 4, 'Pedas yang pas', 'Suka banget sama ayam gepreknya. Pedasnya pas di lidah, tidak terlalu menyengat. Ayamnya crispy. Cuma kadang suka kelamaan nunggu pas jam makan siang.', FALSE, '2024-01-11 13:15:00'),

(5, 12, NULL, 5, 'Jajanan tradisional yang otentik', 'Kleponnya benar-benar traditional. Manis gula merahnya pas, tidak berlebihan. Tekstur ketan juga perfect. Bikin kangen kampung halaman.', FALSE, '2024-01-13 15:40:00'),

(6, 13, NULL, 4, 'Onde-onde yang crispy', 'Onde-ondenya crispy di luar, lembut di dalam. Isian kacang hijau manis. Cuma agak berminyak sih, mungkin bisa dikurangi minyaknya.', FALSE, '2024-01-09 17:25:00'),

(7, 2, NULL, 5, 'Rendang juara!', 'Rendangnya enak banget! Daging empuk, bumbu meresap. Bisa jadi rekomendasi untuk teman-teman. Packaging juga bagus, tidak bocor.', FALSE, '2024-01-14 12:50:00'),

(3, 15, NULL, 5, 'Kue lapis premium', 'Kue lapisnya soft, rich flavor. Bisa jadi oleh-oleh yang bagus. Tahan lama juga, tidak mudah basi. Worth the price!', FALSE, '2024-01-07 20:10:00');

-- Insert some wishlist items
INSERT INTO wishlists (user_id, product_id) VALUES
(3, 2), (3, 7), (3, 15),
(4, 1), (4, 6), (4, 9),
(5, 7), (5, 8), (5, 12),
(6, 3), (6, 13), (6, 14),
(7, 2), (7, 6), (7, 11);

-- Insert some cart items for active users
INSERT INTO cart (user_id, product_id, variant_id, quantity, options) VALUES
(3, 1, 1, 2, '{"spice_level": "medium", "notes": "Tidak terlalu pedas"}'),
(3, 9, 1, 3, '{"notes": "Untuk cemilan"}'),
(4, 2, NULL, 1, '{"notes": "Untuk makan siang"}'),
(5, 7, NULL, 1, '{"delivery_date": "2024-01-25", "notes": "Untuk acara meeting"}');

-- Insert contact messages
INSERT INTO contact_messages (name, email, phone, subject, service, message, status, created_at) VALUES
('Rina Sari', 'rina@example.com', '081234567897', 'Pertanyaan tentang catering', 'catering', 'Halo, saya ingin tanya untuk paket catering 100 orang apakah bisa custom menu? Dan berapa harga untuk tambahan dessert?', 'new', '2024-01-18 10:30:00'),

('Bambang Wijaya', 'bambang@example.com', '081234567898', 'Keluhan pesanan', 'nasi-box', 'Kemarin saya pesan nasi box 20 kotak tapi yang datang cuma 18 kotak. Mohon penjelasannya. Terima kasih.', 'read', '2024-01-17 14:20:00'),

('Lisa Putri', 'lisa@example.com', '081234567899', 'Request menu baru', 'snack', 'Hai, bisa tidak request menu snack baru seperti tahu crispy atau tempe mendoan? Saya yakin akan laris. Terima kasih.', 'new', '2024-01-16 16:45:00'),

('David Chen', 'david@example.com', '081234567800', 'Kerjasama catering', 'catering', 'Selamat siang, saya dari perusahaan XYZ ingin diskusi kerjasama untuk catering harian karyawan. Mohon bisa dikontak balik. Terima kasih.', 'replied', '2024-01-15 09:15:00');

-- Update contact message with reply
UPDATE contact_messages 
SET reply = 'Terima kasih atas pertanyaannya. Tim kami akan segera menghubungi Anda untuk diskusi lebih lanjut mengenai kerjasama catering harian.', 
    replied_at = '2024-01-15 15:30:00', 
    replied_by = 1, 
    status = 'replied' 
WHERE id = 4;

-- Insert notifications for users
INSERT INTO notifications (user_id, type, title, message, data) VALUES
(3, 'order_confirmed', 'Pesanan Dikonfirmasi', 'Pesanan #DA-2024-0001 telah dikonfirmasi dan sedang diproses.', '{"order_id": 1, "order_number": "DA-2024-0001"}'),
(3, 'order_delivered', 'Pesanan Diantar', 'Pesanan #DA-2024-0001 telah berhasil diantar. Terima kasih!', '{"order_id": 1, "order_number": "DA-2024-0001"}'),
(4, 'order_confirmed', 'Pesanan Dikonfirmasi', 'Pesanan #DA-2024-0002 telah dikonfirmasi dan sedang diproses.', '{"order_id": 2, "order_number": "DA-2024-0002"}'),
(5, 'order_confirmed', 'Pesanan Dikonfirmasi', 'Pesanan #DA-2024-0003 telah dikonfirmasi dan sedang diproses.', '{"order_id": 3, "order_number": "DA-2024-0003"}'),
(6, 'order_cancelled', 'Pesanan Dibatalkan', 'Pesanan #DA-2024-0004 telah dibatalkan sesuai permintaan Anda.', '{"order_id": 4, "order_number": "DA-2024-0004"}'),
(7, 'order_delivered', 'Pesanan Diantar', 'Pesanan #DA-2024-0005 telah berhasil diantar. Terima kasih!', '{"order_id": 5, "order_number": "DA-2024-0005"}');

-- Insert inventory movements
INSERT INTO inventory_movements (product_id, variant_id, movement_type, quantity, previous_stock, new_stock, reference_type, reference_id, notes, created_by) VALUES
-- Movements for Order 1
(1, 1, 'out', 3, 83, 80, 'order', 1, 'Penjualan untuk order DA-2024-0001', 1),
(9, 1, 'out', 5, 155, 150, 'order', 1, 'Penjualan untuk order DA-2024-0001', 1),

-- Movements for Order 2
(2, NULL, 'out', 2, 82, 80, 'order', 2, 'Penjualan untuk order DA-2024-0002', 1),
(12, NULL, 'out', 1, 101, 100, 'order', 2, 'Penjualan untuk order DA-2024-0002', 1),

-- Stock adjustment
(1, 1, 'in', 20, 80, 100, 'adjustment', NULL, 'Penambahan stok dari produksi', 1),
(9, 1, 'in', 50, 150, 200, 'adjustment', NULL, 'Penambahan stok dari produksi', 1);

-- Insert activity logs
INSERT INTO activity_logs (user_id, activity_type, description, ip_address, user_agent, data) VALUES
(1, 'login', 'Admin login ke sistem', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '{"login_time": "2024-01-18 08:00:00"}'),
(3, 'register', 'User registrasi baru', '192.168.1.101', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X)', '{"registration_time": "2024-01-10 10:30:00"}'),
(3, 'order_placed', 'User melakukan pemesanan', '192.168.1.101', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X)', '{"order_id": 1, "total_amount": 136000}'),
(4, 'order_placed', 'User melakukan pemesanan', '192.168.1.102', 'Mozilla/5.0 (Android 11; Mobile; rv:68.0)', '{"order_id": 2, "total_amount": 82500}'),
(1, 'product_updated', 'Admin update produk', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '{"product_id": 1, "changes": "Updated stock quantity"}');
