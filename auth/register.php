<?php
// Set JSON header first
header('Content-Type: application/json');

// Start output buffering to prevent header issues
ob_start();

try {
    require_once '../config/database.php';
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database configuration error: ' . $e->getMessage()
    ]);
    exit;
}

$response = ['success' => false, 'message' => ''];

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = getConnection();
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($name)) {
            throw new Exception('Nama lengkap wajib diisi!');
        }
        
        if (empty($email)) {
            throw new Exception('Email wajib diisi!');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Format email tidak valid!');
        }
        
        if (empty($password)) {
            throw new Exception('Password wajib diisi!');
        }
        
        if (strlen($password) < 6) {
            throw new Exception('Password minimal 6 karakter!');
        }
        
        if ($password !== $confirmPassword) {
            throw new Exception('Konfirmasi password tidak cocok!');
        }
        
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            throw new Exception('Email sudah terdaftar! Silakan gunakan email lain.');
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, phone, password, role, is_active, created_at) 
            VALUES (?, ?, ?, ?, 'customer', 1, NOW())
        ");
        $result = $stmt->execute([$name, $email, $phone, $hashedPassword]);
        
        if (!$result) {
            throw new Exception('Gagal menyimpan data user. Silakan coba lagi.');
        }
        
        // Auto login after registration
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $userId = $pdo->lastInsertId();
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = 'customer';
        
        $response['success'] = true;
        $response['message'] = 'Pendaftaran berhasil! Selamat datang di Dapoer Aisyah!';
        $response['redirect'] = '../index.php';
        
    } catch(PDOException $e) {
        $response['message'] = 'Database Error: ' . $e->getMessage();
        error_log("Registration Error: " . $e->getMessage());
    } catch(Exception $e) {
        $response['message'] = $e->getMessage();
    }
} else {
    $response['message'] = 'Method not allowed. Please use POST request.';
}

// Clean output buffer and send JSON response
ob_clean();
echo json_encode($response);
exit;
?>
