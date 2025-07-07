-- Create contact_messages table for contact form
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_email (email)
);

-- Insert some sample contact messages for testing
INSERT INTO contact_messages (name, email, phone, subject, message, status, created_at) VALUES
('Budi Santoso', 'budi@example.com', '081234567890', 'Pemesanan Catering', 'Halo, saya ingin memesan catering untuk acara pernikahan tanggal 15 Desember 2024. Estimasi 200 tamu. Mohon info paket dan harganya. Terima kasih.', 'unread', '2024-01-15 10:30:00'),

('Sari Dewi', 'sari.dewi@gmail.com', '087654321098', 'Konsultasi Menu', 'Selamat pagi, saya berencana mengadakan gathering kantor untuk 50 orang. Apakah bisa konsultasi menu yang cocok dan budget-friendly? Acaranya indoor di hotel.', 'read', '2024-01-14 14:20:00'),

('Ahmad Rahman', 'ahmad.rahman@company.com', '081987654321', 'Pertanyaan Harga', 'Mohon info harga paket catering untuk acara corporate lunch 100 pax. Butuh yang praktis tapi tetap berkualitas. Area Jakarta Selatan.', 'replied', '2024-01-13 09:15:00'),

('Maya Putri', 'maya.putri@yahoo.com', '085123456789', 'Kerjasama', 'Halo Dapoer Aisyah, saya dari event organizer. Tertarik untuk kerjasama catering untuk klien-klien kami. Bisa diskusi lebih lanjut?', 'unread', '2024-01-12 16:45:00'),

('Doni Prasetyo', 'doni.prasetyo@gmail.com', '082345678901', 'Pemesanan Catering', 'Saya mau pesan nasi box untuk acara arisan RT, sekitar 30 porsi. Tanggal 20 Januari 2024. Lokasi di Bekasi. Bisa delivery tidak?', 'read', '2024-01-11 11:30:00'),

('Linda Sari', 'linda.sari@outlook.com', '089876543210', 'Keluhan', 'Kemarin saya pesan catering untuk acara keluarga, tapi ada beberapa menu yang kurang sesuai ekspektasi. Bisa dibantu follow up?', 'replied', '2024-01-10 13:20:00'),

('Rudi Hartono', 'rudi.hartono@gmail.com', '081122334455', 'Konsultasi Menu', 'Mau tanya, untuk acara syukuran rumah baru kira-kira menu apa yang cocok ya? Budget sekitar 2-3 juta untuk 80 orang.', 'unread', '2024-01-09 08:45:00'),

('Fitri Handayani', 'fitri.handayani@gmail.com', '087788990011', 'Lainnya', 'Apakah Dapoer Aisyah menerima pesanan untuk acara di luar Jakarta? Saya butuh catering untuk acara di Bogor, sekitar 150 porsi.', 'read', '2024-01-08 15:10:00');

-- Show the created table structure
DESCRIBE contact_messages;

-- Show sample data
SELECT 
    id, 
    name, 
    email, 
    subject, 
    status,
    DATE_FORMAT(created_at, '%d %M %Y %H:%i') as tanggal_pesan
FROM contact_messages 
ORDER BY created_at DESC 
LIMIT 5;
