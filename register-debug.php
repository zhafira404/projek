<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$pageTitle = "Daftar - Dapoer Aisyah";

// Debug info
$debug_info = [];
$debug_info[] = "✅ File register-debug-real.php loaded";

// Try to include database config
try {
    require_once 'config/database.php';
    $debug_info[] = "✅ Database config loaded successfully";
} catch (Exception $e) {
    $debug_info[] = "❌ Database config failed: " . $e->getMessage();
}

$error = '';
$success = '';

// Debug: Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $debug_info[] = "✅ Form submitted via POST";
    
    if (isset($_POST['register'])) {
        $debug_info[] = "✅ Register button clicked";
        
        // Debug: Show all POST data
        $debug_info[] = "📝 POST data received: " . print_r($_POST, true);
        
        try {
            $debug_info[] = "🔄 Starting registration process...";
            
            // Test database connection
            try {
                $pdo = getConnection();
                $debug_info[] = "✅ Database connection successful";
            } catch (Exception $e) {
                $debug_info[] = "❌ Database connection failed: " . $e->getMessage();
                throw new Exception('Database connection failed: ' . $e->getMessage());
            }
            
            // Get form data
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
            
            $debug_info[] = "📝 Form data extracted - Name: $name, Email: $email, Phone: $phone";
            
            // Validation
            if (empty($name)) {
                throw new Exception('Nama wajib diisi!');
            }
            $debug_info[] = "✅ Name validation passed";
            
            if (empty($email)) {
                throw new Exception('Email wajib diisi!');
            }
            $debug_info[] = "✅ Email required validation passed";
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Format email tidak valid!');
            }
            $debug_info[] = "✅ Email format validation passed";
            
            if (empty($password)) {
                throw new Exception('Password wajib diisi!');
            }
            $debug_info[] = "✅ Password required validation passed";
            
            if (strlen($password) < 6) {
                throw new Exception('Password minimal 6 karakter!');
            }
            $debug_info[] = "✅ Password length validation passed";
            
            if ($password !== $confirmPassword) {
                throw new Exception('Konfirmasi password tidak cocok!');
            }
            $debug_info[] = "✅ Password confirmation validation passed";
            
            // Check if email already exists
            try {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    throw new Exception('Email sudah terdaftar! Silakan gunakan email lain.');
                }
                $debug_info[] = "✅ Email uniqueness check passed";
            } catch (PDOException $e) {
                $debug_info[] = "❌ Email check query failed: " . $e->getMessage();
                throw new Exception('Database error during email check: ' . $e->getMessage());
            }
            
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $debug_info[] = "✅ Password hashed successfully";
            
            // Insert new user
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO users (name, email, phone, password, role, is_active, created_at) 
                    VALUES (?, ?, ?, ?, 'customer', 1, NOW())
                ");
                $result = $stmt->execute([$name, $email, $phone, $hashedPassword]);
                
                if ($result) {
                    $debug_info[] = "✅ User inserted successfully";
                    $userId = $pdo->lastInsertId();
                    $debug_info[] = "✅ New user ID: " . $userId;
                    
                    $success = "Pendaftaran berhasil! Silakan login dengan akun Anda.";
                    
                    // Start session and auto login
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_role'] = 'customer';
                    
                    $debug_info[] = "✅ Session created successfully";
                    
                } else {
                    throw new Exception('Failed to insert user data');
                }
                
            } catch (PDOException $e) {
                $debug_info[] = "❌ Insert query failed: " . $e->getMessage();
                throw new Exception('Database error during user creation: ' . $e->getMessage());
            }
            
        } catch(Exception $e) {
            $error = $e->getMessage();
            $debug_info[] = "❌ Registration failed: " . $e->getMessage();
        }
    } else {
        $debug_info[] = "❌ Register button not found in POST data";
    }
} else {
    $debug_info[] = "ℹ️ Page loaded (not a POST request)";
}

// Try to include header
try {
    require_once 'includes/header.php';
    $debug_info[] = "✅ Header included successfully";
} catch (Exception $e) {
    $debug_info[] = "❌ Header include failed: " . $e->getMessage();
    echo "<!DOCTYPE html><html><head><title>$pageTitle</title></head><body>";
}
?>

<style>
.debug-panel {
    background: #f8f9fa;
    border: 2px solid #007bff;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
    font-family: monospace;
    font-size: 14px;
    max-height: 400px;
    overflow-y: auto;
}

.debug-panel h3 {
    color: #007bff;
    margin-top: 0;
}

.debug-panel pre {
    background: white;
    padding: 10px;
    border-radius: 4px;
    border-left: 4px solid #007bff;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.alert {
    padding: 15px;
    margin: 20px 0;
    border-radius: 4px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.form-container {
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    box-sizing: border-box;
}

.form-group input:focus {
    border-color: #007bff;
    outline: none;
}

.btn {
    width: 100%;
    padding: 15px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #218838;
}

.checkbox-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 15px 0;
}

.auth-footer {
    text-align: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.auth-footer a {
    color: #007bff;
    text-decoration: none;
}

.auth-footer a:hover {
    text-decoration: underline;
}
</style>

<main>
    <div class="container">
        <!-- DEBUG PANEL -->
        <div class="debug-panel">
            <h3>🔍 Debug Information</h3>
            <pre><?php echo implode("\n", $debug_info); ?></pre>
        </div>
        
        <div class="form-container">
            <h2 style="text-align: center; color: #333; margin-bottom: 30px;">Buat Akun Baru</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 3000);
                </script>
            <?php endif; ?>
            
            <form method="POST" novalidate>
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" 
                           placeholder="Masukkan nama lengkap Anda"
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           placeholder="nama@email.com"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone"
                           placeholder="08xx-xxxx-xxxx"
                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Minimal 6 karakter">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           placeholder="Ulangi password Anda">
                </div>
                
                <div class="checkbox-wrapper">
                    <input type="checkbox" name="agree_terms" id="agree_terms">
                    <label for="agree_terms">Saya setuju dengan Syarat & Ketentuan</label>
                </div>
                
                <button type="submit" name="register" class="btn">
                    Daftar Sekarang
                </button>
            </form>
            
            <div class="auth-footer">
                <p>Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
            </div>
        </div>
    </div>
</main>

<?php
try {
    require_once 'includes/footer.php';
} catch (Exception $e) {
    echo "</body></html>";
}
?>
