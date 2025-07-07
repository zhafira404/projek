 <?php
 $pageTitle = "Masuk - Dapoer Aisyah";
 require_once '../config/database.php';
 
 // Start session
 if (session_status() === PHP_SESSION_NONE) {
     session_start();
 }
 
 // Handle AJAX login requests
 if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
     (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ||
      strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
     
     header('Content-Type: application/json');
     
     try {
         $pdo = getConnection();
         
         $email = trim($_POST['email'] ?? '');
         $password = $_POST['password'] ?? '';
         
         if (empty($email) || empty($password)) {
             echo json_encode(['success' => false, 'message' => 'Email dan password wajib diisi!']);
             exit;
         }
         
         $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
         $stmt->execute([$email]);
         $user = $stmt->fetch(PDO::FETCH_ASSOC);
         
         if ($user && password_verify($password, $user['password'])) {
             $_SESSION['user_id'] = $user['id'];
             $_SESSION['user_name'] = $user['name'];
             $_SESSION['user_email'] = $user['email'];
             $_SESSION['user_role'] = $user['role'];
             
             // Update last login
             $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
             $stmt->execute([$user['id']]);
             
             echo json_encode([
                 'success' => true, 
                 'message' => 'Login berhasil!',
                 'user' => [
                     'name' => $user['name'],
                     'email' => $user['email'],
                     'role' => $user['role']
                 ]
             ]);
             exit;
         } else {
             echo json_encode(['success' => false, 'message' => 'Email atau password salah!']);
             exit;
         }
         
     } catch(Exception $e) {
         echo json_encode(['success' => false, 'message' => $e->getMessage()]);
         exit;
     }
 }
 
 // Handle regular form submission
 $error = '';
 $success = '';
 
 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
     try {
         $pdo = getConnection();
         
         $email = trim($_POST['email']);
         $password = $_POST['password'];
         
         if (empty($email) || empty($password)) {
             throw new Exception('Email dan password wajib diisi!');
         }
         
         $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
         $stmt->execute([$email]);
         $user = $stmt->fetch();
         
         if ($user && password_verify($password, $user['password'])) {
             $_SESSION['user_id'] = $user['id'];
             $_SESSION['user_name'] = $user['name'];
             $_SESSION['user_email'] = $user['email'];
             $_SESSION['user_role'] = $user['role'];
             
             // Update last login
             $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
             $stmt->execute([$user['id']]);
             
             // Redirect ke halaman yang diminta atau dashboard
             $redirect = $_GET['redirect'] ?? '../index.php';
             header("Location: " . $redirect);
             exit;
         } else {
             throw new Exception('Email atau password salah!');
         }
         
     } catch(Exception $e) {
         $error = $e->getMessage();
     }
 }
 
 require_once 'includes/header.php';
 ?>
 
 <main>
     <!-- Login Hero -->
     <section class="auth-hero">
         <div class="container">
             <div class="auth-wrapper">
                 <div class="auth-info">
                     <div class="auth-brand">
                         <h1>Selamat Datang Kembali!</h1>
                         <p>Masuk ke akun Anda untuk melanjutkan pemesanan dan menikmati pengalaman berbelanja yang lebih personal</p>
                     </div>
                     
                     <div class="auth-features">
                         <div class="feature-item">
                             <div class="feature-icon">
                                 <i class="fas fa-shopping-cart"></i>
                             </div>
                             <div class="feature-content">
                                 <h4>Keranjang Tersimpan</h4>
                                 <p>Lanjutkan belanja dari terakhir kali</p>
                             </div>
                         </div>
                         
                         <div class="feature-item">
                             <div class="feature-icon">
                                 <i class="fas fa-history"></i>
                             </div>
                             <div class="feature-content">
                                 <h4>Riwayat Pesanan</h4>
                                 <p>Lihat dan pesan ulang menu favorit</p>
                             </div>
                         </div>
                         
                         <div class="feature-item">
                             <div class="feature-icon">
                                 <i class="fas fa-star"></i>
                             </div>
                             <div class="feature-content">
                                 <h4>Poin Reward</h4>
                                 <p>Kumpulkan poin setiap pembelian</p>
                             </div>
                         </div>
                     </div>
                 </div>
                 
                 <div class="auth-form-container">
                     <div class="auth-form-header">
                         <h2>Masuk ke Akun</h2>
                         <p>Gunakan email dan password Anda</p>
                     </div>
                     
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
                     <?php endif; ?>
                     
                     <form method="POST" class="auth-form">
                         <div class="form-group">
                             <label for="email">
                                 <i class="fas fa-envelope"></i>
                                 Email
                             </label>
                             <input type="email" id="email" name="email" required
                                    placeholder="nama@email.com"
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                         </div>
                         
                         <div class="form-group">
                             <label for="password">
                                 <i class="fas fa-lock"></i>
                                 Password
                             </label>
                             <div class="password-input-wrapper">
                                 <input type="password" id="password" name="password" required
                                        placeholder="Masukkan password Anda">
                                 <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                     <i class="fas fa-eye"></i>
                                 </button>
                             </div>
                         </div>
                         
                         <div class="form-options">
                             <label class="checkbox-wrapper">
                                 <input type="checkbox" name="remember">
                                 <span class="checkmark"></span>
                                 Ingat saya
                             </label>
                             <a href="forgot-password.php" class="forgot-link">Lupa password?</a>
                         </div>
                         
                         <button type="submit" name="login" class="btn btn-primary btn-full">
                             <span>Masuk</span>
                             <i class="fas fa-arrow-right"></i>
                         </button>
                     </form>
                     
                     <div class="auth-divider">
                         <span>atau</span>
                     </div>
                     
                     <div class="social-login">
                         <button class="btn btn-social btn-google">
                             <i class="fab fa-google"></i>
                             <span>Masuk dengan Google</span>
                         </button>
                         <button class="btn btn-social btn-facebook">
                             <i class="fab fa-facebook"></i>
                             <span>Masuk dengan Facebook</span>
                         </button>
                     </div>
                     
                     <div class="auth-footer">
                         <p>Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
                     </div>
                 </div>
             </div>
         </div>
     </section>
 </main>
 
 <style>
 /* Auth Page Styles */
 .auth-hero {
     min-height: 100vh;
     background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
     display: flex;
     align-items: center;
     padding: 2rem 0;
 }
 
 .auth-wrapper {
     display: grid;
     grid-template-columns: 1fr 1fr;
     gap: 4rem;
     align-items: center;
     max-width: 1200px;
     margin: 0 auto;
 }
 
 .auth-info {
     padding: 2rem;
 }
 
 .auth-brand h1 {
     font-size: 3rem;
     font-weight: 700;
     margin-bottom: 1.5rem;
     color: #2c5530;
     line-height: 1.2;
 }
 
 .auth-brand p {
     font-size: 1.2rem;
     color: #666;
     line-height: 1.6;
     margin-bottom: 3rem;
 }
 
 .auth-features {
     display: flex;
     flex-direction: column;
     gap: 1.5rem;
 }
 
 .feature-item {
     display: flex;
     align-items: center;
     gap: 1rem;
     padding: 1.5rem;
     background: white;
     border-radius: 15px;
     box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
     transition: all 0.3s ease;
 }
 
 .feature-item:hover {
     transform: translateY(-5px);
     box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
 }
 
 .feature-icon {
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
 
 .feature-content h4 {
     font-size: 1.2rem;
     font-weight: 600;
     margin-bottom: 0.5rem;
     color: #333;
 }
 
 .feature-content p {
     color: #666;
     font-size: 0.95rem;
 }
 
 .auth-form-container {
     background: white;
     padding: 3rem;
     border-radius: 25px;
     box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
 }
 
 .auth-form-header {
     text-align: center;
     margin-bottom: 2rem;
 }
 
 .auth-form-header h2 {
     font-size: 2rem;
     font-weight: 600;
     margin-bottom: 0.5rem;
     color: #333;
 }
 
 .auth-form-header p {
     color: #666;
     font-size: 1rem;
 }
 
 .alert {
     display: flex;
     align-items: center;
     gap: 0.75rem;
     padding: 1rem 1.5rem;
     border-radius: 12px;
     margin-bottom: 1.5rem;
     font-weight: 500;
 }
 
 .alert-error {
     background: #fee;
     color: #c53030;
     border: 1px solid #fed7d7;
 }
 
 .alert-success {
     background: #f0fff4;
     color: #38a169;
     border: 1px solid #c6f6d5;
 }
 
 .auth-form {
     display: flex;
     flex-direction: column;
     gap: 1.5rem;
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
     margin-bottom: 0.75rem;
     color: #333;
 }
 
 .form-group input {
     padding: 1rem 1.25rem;
     border: 2px solid #e1e5e9;
     border-radius: 12px;
     outline: none;
     font-size: 1rem;
     transition: all 0.3s ease;
     background: #fafafa;
 }
 
 .form-group input:focus {
     border-color: #2c5530;
     box-shadow: 0 0 0 3px rgba(44, 85, 48, 0.1);
     background: white;
 }
 
 .password-input-wrapper {
     position: relative;
 }
 
 .password-toggle {
     position: absolute;
     right: 1rem;
     top: 50%;
     transform: translateY(-50%);
     background: none;
     border: none;
     color: #666;
     cursor: pointer;
     padding: 0.5rem;
     transition: color 0.3s ease;
 }
 
 .password-toggle:hover {
     color: #2c5530;
 }
 
 .form-options {
     display: flex;
     justify-content: space-between;
     align-items: center;
 }
 
 .checkbox-wrapper {
     display: flex;
     align-items: center;
     gap: 0.5rem;
     cursor: pointer;
     font-size: 0.95rem;
     color: #666;
 }
 
 .checkbox-wrapper input {
     margin: 0;
     width: auto;
 }
 
 .forgot-link {
     color: #2c5530;
     text-decoration: none;
     font-size: 0.95rem;
     font-weight: 500;
     transition: color 0.3s ease;
 }
 
 .forgot-link:hover {
     color: #1a3d1f;
 }
 
 .btn-full {
     width: 100%;
     padding: 1.25rem;
     font-size: 1.1rem;
     font-weight: 600;
     display: flex;
     align-items: center;
     justify-content: center;
     gap: 0.75rem;
     transition: all 0.3s ease;
 }
 
 .btn-full:hover {
     transform: translateY(-2px);
     box-shadow: 0 10px 25px rgba(44, 85, 48, 0.3);
 }
 
 .auth-divider {
     text-align: center;
     margin: 2rem 0;
     position: relative;
 }
 
 .auth-divider::before {
     content: '';
     position: absolute;
     top: 50%;
     left: 0;
     right: 0;
     height: 1px;
     background: #e1e5e9;
 }
 
 .auth-divider span {
     background: white;
     padding: 0 1rem;
     color: #666;
     font-size: 0.9rem;
 }
 
 .social-login {
     display: flex;
     flex-direction: column;
     gap: 1rem;
 }
 
 .btn-social {
     display: flex;
     align-items: center;
     justify-content: center;
     gap: 0.75rem;
     padding: 1rem;
     border: 2px solid #e1e5e9;
     border-radius: 12px;
     background: white;
     color: #333;
     text-decoration: none;
     font-weight: 500;
     transition: all 0.3s ease;
 }
 
 .btn-google:hover {
     border-color: #db4437;
     background: #db4437;
     color: white;
 }
 
 .btn-facebook:hover {
     border-color: #4267b2;
     background: #4267b2;
     color: white;
 }
 
 .auth-footer {
     text-align: center;
     margin-top: 2rem;
     padding-top: 2rem;
     border-top: 1px solid #e1e5e9;
 }
 
 .auth-footer p {
     color: #666;
     font-size: 0.95rem;
 }
 
 .auth-footer a {
     color: #2c5530;
     text-decoration: none;
     font-weight: 600;
     transition: color 0.3s ease;
 }
 
 .auth-footer a:hover {
     color: #1a3d1f;
 }
 
 /* Responsive */
 @media (max-width: 768px) {
     .auth-wrapper {
         grid-template-columns: 1fr;
         gap: 2rem;
     }
     
     .auth-brand h1 {
         font-size: 2.5rem;
     }
     
     .auth-form-container {
         padding: 2rem;
     }
     
     .feature-item {
         padding: 1rem;
     }
 }
 
 @media (max-width: 480px) {
     .auth-form-container {
         padding: 1.5rem;
         margin: 1rem;
     }
     
     .auth-brand h1 {
         font-size: 2rem;
     }
 }
 </style>
 
 <script>
 function togglePassword(inputId) {
     const input = document.getElementById(inputId);
     const toggle = input.parentElement.querySelector('.password-toggle i');
     
     if (input.type === 'password') {
         input.type = 'text';
         toggle.classList.remove('fa-eye');
         toggle.classList.add('fa-eye-slash');
     } else {
         input.type = 'password';
         toggle.classList.remove('fa-eye-slash');
         toggle.classList.add('fa-eye');
     }
 }
 </script>
 
 <?php require_once 'includes/footer.php'; ?>
