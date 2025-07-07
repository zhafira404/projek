<?php
session_start();
$pageTitle = "Daftar - Dapoer Aisyah";

// Database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=dapoer_aisyah;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch(PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}

$error = '';
$success = '';

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    
    // Debug: tampilkan data yang diterima
    echo "<h3>🔍 Debug - Data yang diterima:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    try {
        // Ambil data dari form
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        echo "<p>📝 Name: '$name'</p>";
        echo "<p>📧 Email: '$email'</p>";
        echo "<p>📱 Phone: '$phone'</p>";
        echo "<p>🔐 Password length: " . strlen($password) . "</p>";
        
        // Validasi basic
        if (empty($name)) {
            throw new Exception('❌ Nama wajib diisi!');
        }
        
        if (empty($email)) {
            throw new Exception('❌ Email wajib diisi!');
        }
        
        if (empty($password)) {
            throw new Exception('❌ Password wajib diisi!');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('❌ Format email tidak valid!');
        }
        
        if (strlen($password) < 6) {
            throw new Exception('❌ Password minimal 6 karakter!');
        }
        
        if ($password !== $confirmPassword) {
            throw new Exception('❌ Konfirmasi password tidak cocok!');
        }
        
        echo "<p>✅ Validasi basic: PASSED</p>";
        
        // Cek email sudah ada atau belum
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            throw new Exception('❌ Email sudah terdaftar! Gunakan email lain.');
        }
        
        echo "<p>✅ Email check: AVAILABLE</p>";
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        echo "<p>✅ Password hashed: " . substr($hashedPassword, 0, 20) . "...</p>";
        
        // Insert ke database
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, phone, password, role, is_active, created_at) 
            VALUES (?, ?, ?, ?, 'customer', 1, NOW())
        ");
        
        $result = $stmt->execute([$name, $email, $phone, $hashedPassword]);
        
        if ($result) {
            $newUserId = $pdo->lastInsertId();
            echo "<p>✅ User inserted with ID: $newUserId</p>";
            
            // Auto login
            $_SESSION['user_id'] = $newUserId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'customer';
            
            $success = "🎉 PENDAFTARAN BERHASIL! Selamat datang $name!";
            
            // Redirect setelah 3 detik
            echo "<script>
                alert('Pendaftaran berhasil! Anda akan diarahkan ke halaman utama.');
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 3000);
            </script>";
            
        } else {
            throw new Exception('❌ Gagal menyimpan data ke database');
        }
        
    } catch(Exception $e) {
        $error = $e->getMessage();
        echo "<p style='color: red;'><strong>$error</strong></p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #2c5530; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
        button:hover { background: #1a3d1f; }
        .alert { padding: 15px; margin: 15px 0; border-radius: 5px; }
        .alert-error { background: #fee; color: #c53030; border: 1px solid #fed7d7; }
        .alert-success { background: #f0fff4; color: #38a169; border: 1px solid #c6f6d5; }
    </style>
</head>
<body>
    <h1>📝 Daftar Akun Baru</h1>
    
    <?php if ($error): ?>
        <div class="alert alert-error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label for="name">Nama Lengkap *</label>
            <input type="text" id="name" name="name" required 
                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required 
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="phone">Nomor Telepon</label>
            <input type="tel" id="phone" name="phone" 
                   value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="password">Password * (min. 6 karakter)</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Konfirmasi Password *</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit" name="register">Daftar Sekarang</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        Sudah punya akun? <a href="login-fixed.php">Login di sini</a>
    </p>
</body>
</html>
