<?php
session_start();
$pageTitle = "Login - Dapoer Aisyah";

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

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    
    // Debug: tampilkan data yang diterima
    echo "<h3>🔍 Debug - Login attempt:</h3>";
    
    try {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        echo "<p>📧 Email: '$email'</p>";
        echo "<p>🔐 Password length: " . strlen($password) . "</p>";
        
        if (empty($email)) {
            throw new Exception('❌ Email wajib diisi!');
        }
        
        if (empty($password)) {
            throw new Exception('❌ Password wajib diisi!');
        }
        
        // Cari user di database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            echo "<p>❌ User not found in database</p>";
            throw new Exception('❌ Email tidak ditemukan atau akun tidak aktif!');
        }
        
        echo "<p>✅ User found: {$user['name']} (ID: {$user['id']})</p>";
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            echo "<p>✅ Password verification: SUCCESS</p>";
            
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            
            // Update last login
            $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            $success = "🎉 LOGIN BERHASIL! Selamat datang {$user['name']}!";
            
            echo "<script>
                alert('Login berhasil! Selamat datang {$user['name']}');
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 2000);
            </script>";
            
        } else {
            echo "<p>❌ Password verification: FAILED</p>";
            throw new Exception('❌ Password salah!');
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
        .test-accounts { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <h1>🔐 Login</h1>
    
    <div class="test-accounts">
        <h3>🔑 Test Accounts:</h3>
        <p><strong>Admin:</strong> admin@dapoerisyah.com / admin123</p>
        <p><strong>Customer:</strong> customer@test.com / admin123</p>
    </div>
    
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
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required 
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" name="login">Login</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        Belum punya akun? <a href="register-fixed.php">Daftar di sini</a>
    </p>
</body>
</html>
