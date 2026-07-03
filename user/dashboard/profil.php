<?php
session_start();

// 1. KEAMANAN & SESSION
// Cek jika $_SESSION['user_id'] kosong, redirect ke '../auth/login.php'
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Hubungkan database menggunakan require_once '../../config/db.php'
require_once '../../config/db.php';

// Inisialisasi variabel pesan
$success_msg = '';
$error_msg = '';

$user_id = $_SESSION['user_id'];

// Deteksi dinamis nama kolom no hp/telepon di tabel 'users' untuk mencegah SQL error
$db_phone_col = 'phone'; // default
if ($db_connected && $pdo) {
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'phone'");
        if ($stmt->rowCount() == 0) {
            $stmt2 = $pdo->query("SHOW COLUMNS FROM users LIKE 'no_hp'");
            if ($stmt2->rowCount() > 0) {
                $db_phone_col = 'no_hp';
            }
        }
    } catch (PDOException $e) {
        $db_phone_col = 'no_hp'; // fallback aman
    }
}

// Ambil data user terbaru dari database (jika database terkoneksi)
$current_username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$current_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$current_phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : '';

if ($db_connected && $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        if ($user) {
            $current_username = $user['username'];
            $current_email = $user['email'];
            $current_phone = isset($user[$db_phone_col]) ? $user[$db_phone_col] : '';
            
            // Sinkronisasi session dengan data database terbaru
            $_SESSION['username'] = $current_username;
            $_SESSION['email'] = $current_email;
            $_SESSION['phone'] = $current_phone;
        }
    } catch (PDOException $e) {
        // Fallback menggunakan session jika query gagal
    }
}

// 2. PROSES UPDATE DATA (PHP LOGIC)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // FORM 1: UPDATE BIODATA PROFIL
    if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        
        // Validasi input sederhana
        if (empty($username) || empty($email) || empty($phone)) {
            $error_msg = "Semua field biodata wajib diisi!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_msg = "Format email tidak valid!";
        } else {
            if ($db_connected && $pdo) {
                try {
                    // Pastikan username atau email tidak duplikat dengan user lain
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
                    $stmt->execute([$username, $email, $user_id]);
                    
                    if ($stmt->rowCount() > 0) {
                        $error_msg = "Username atau email sudah digunakan oleh pengguna lain!";
                    } else {
                        // Update ke database
                        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, {$db_phone_col} = ? WHERE id = ?");
                        $stmt->execute([$username, $email, $phone, $user_id]);
                        
                        // Perbarui $_SESSION agar langsung berubah real-time di sidebar/navbar
                        $_SESSION['username'] = $username;
                        $_SESSION['email'] = $email;
                        $_SESSION['phone'] = $phone;
                        
                        // Perbarui variabel tampilan
                        $current_username = $username;
                        $current_email = $email;
                        $current_phone = $phone;
                        
                        $success_msg = "Biodata profil Anda berhasil diperbarui!";
                    }
                } catch (PDOException $e) {
                    $error_msg = "Gagal memperbarui data: " . $e->getMessage();
                }
            } else {
                // Mode Demo (Jika MySQL mati)
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['phone'] = $phone;
                
                $current_username = $username;
                $current_email = $email;
                $current_phone = $phone;
                
                $success_msg = "Biodata profil berhasil diperbarui! (Mode Demo Aktif)";
            }
        }
    }
    
    // FORM 2: UPDATE PASSWORD
    if (isset($_POST['action']) && $_POST['action'] === 'update_password') {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Validasi input
        if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
            $error_msg = "Semua field password wajib diisi!";
        } elseif (strlen($new_password) < 6) {
            $error_msg = "Password baru minimal terdiri dari 6 karakter!";
        } elseif ($new_password !== $confirm_password) {
            $error_msg = "Konfirmasi password baru tidak cocok!";
        } else {
            if ($db_connected && $pdo) {
                try {
                    // Ambil password lama dari database
                    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);
                    $user_db = $stmt->fetch();
                    
                    if ($user_db && password_verify($old_password, $user_db['password'])) {
                        // Hash password baru wajib menggunakan password_hash($password, PASSWORD_DEFAULT)
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                        
                        // Update ke database
                        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $stmt->execute([$hashed_password, $user_id]);
                        
                        $success_msg = "Password Anda berhasil diperbarui!";
                    } else {
                        $error_msg = "Password lama yang Anda masukkan salah!";
                    }
                } catch (PDOException $e) {
                    $error_msg = "Gagal memperbarui password: " . $e->getMessage();
                }
            } else {
                // Mode Demo (Jika MySQL mati)
                if (strlen($old_password) >= 4) {
                    $success_msg = "Password berhasil diperbarui! (Simulasi Mode Demo)";
                } else {
                    $error_msg = "Simulasi Gagal: Password lama minimal 4 karakter untuk demo.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - FUNtopup</title>
    <!-- Google Fonts Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Load dashboard.css -->
    <link rel="stylesheet" href="dashboard.css">
    <style>
        /* CSS tambahan untuk memastikan visual premium & grid responsif jika dashboard.css minimal */
        :root {
            --ft-bg-dark: #0b0e11;
            --ft-bg-card: #1e2329;
            --ft-yellow: #fcd535;
            --ft-yellow-hover: #f0b90b;
            --ft-border: #2b3139;
            --ft-text: #eaecef;
            --ft-text-muted: #707a8a;
            --ft-danger: #f6465d;
            --ft-success: #0ecb81;
        }
        
        body {
            background-color: var(--ft-bg-dark);
            color: var(--ft-text);
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
        }

        .ft-grid-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
            margin-top: 24px;
        }

        @media (min-width: 992px) {
            .ft-grid-container {
                grid-template-columns: 1.2fr 0.8fr;
            }
        }

        /* Alert Styling */
        .ft-alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
            animation: slideIn 0.3s ease-out;
        }
        
        .ft-alert-success {
            background-color: rgba(14, 203, 129, 0.1);
            border: 1px solid rgba(14, 203, 129, 0.25);
            color: var(--ft-success);
        }
        
        .ft-alert-error {
            background-color: rgba(246, 70, 93, 0.1);
            border: 1px solid rgba(246, 70, 93, 0.25);
            color: var(--ft-danger);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Form Controls styling */
        .ft-form-group {
            margin-bottom: 20px;
        }

        .ft-form-group:last-child {
            margin-bottom: 0;
        }

        .ft-input-desc {
            font-size: 12px;
            color: var(--ft-text-muted);
            margin-top: 6px;
        }

        /* Demo Mode Badge styling */
        .ft-badge-demo {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: rgba(252, 213, 53, 0.1);
            border: 1px solid rgba(252, 213, 53, 0.3);
            color: var(--ft-yellow);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <!-- 3. DESAIN & TAMPILAN (HTML/UI) -->
    <!-- Struktur layout pembungkus dashboard -->
    <div class="ft-wrapper">
        
        <!-- Include sidebar.php -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Main Content Area -->
        <main class="ft-main">
            
            <!-- Page Header -->
            <div class="ft-page-header">
                <h1 class="ft-page-title">Pengaturan Profil</h1>
                <p class="ft-page-sub">Kelola informasi akun Anda dan perbarui kata sandi keamanan Anda di sini.</p>
                
                <?php if (!$db_connected): ?>
                    <div class="ft-badge-demo">
                        <span>🔌 Mode Demo Aktif (Database Offline)</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tampilkan Pesan Sukses / Error di bagian atas form -->
            <?php if (!empty($success_msg)): ?>
                <div class="ft-alert ft-alert-success" id="alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span><strong>Sukses!</strong> <?php echo htmlspecialchars($success_msg); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div class="ft-alert ft-alert-error" id="alert-error">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span><strong>Gagal!</strong> <?php echo htmlspecialchars($error_msg); ?></span>
                </div>
            <?php endif; ?>

            <!-- Grid Layout untuk Form -->
            <div class="ft-grid-container">
                
                <!-- Left Side: Form 1 - Biodata Profil -->
                <div class="ft-card">
                    <div style="border-bottom: 1px solid var(--ft-border); padding-bottom: 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fcd535" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <h3 style="margin: 0; font-size: 18px; font-weight: 700; text-transform: uppercase; tracking-wide: 1px;">Biodata Profil</h3>
                    </div>
                    
                    <form action="" method="POST">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <!-- Input Username -->
                        <div class="ft-form-group">
                            <label for="username" class="ft-label">Username</label>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="ft-input" 
                                value="<?php echo htmlspecialchars($current_username); ?>" 
                                required
                                placeholder="Masukkan username baru"
                            >
                            <div class="ft-input-desc">Digunakan untuk masuk ke akun Anda.</div>
                        </div>
                        
                        <!-- Input Email -->
                        <div class="ft-form-group">
                            <label for="email" class="ft-label">Alamat Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="ft-input" 
                                value="<?php echo htmlspecialchars($current_email); ?>" 
                                required
                                placeholder="nama@email.com"
                            >
                            <div class="ft-input-desc">Pastikan email aktif untuk kebutuhan verifikasi.</div>
                        </div>
                        
                        <!-- Input No HP -->
                        <div class="ft-form-group">
                            <label for="phone" class="ft-label">Nomor WhatsApp / HP</label>
                            <input 
                                type="text" 
                                id="phone" 
                                name="phone" 
                                class="ft-input" 
                                value="<?php echo htmlspecialchars($current_phone); ?>" 
                                required
                                placeholder="Contoh: 081234567890"
                            >
                            <div class="ft-input-desc">Nomor handphone yang dapat dihubungi.</div>
                        </div>
                        
                        <!-- Tombol Simpan Perubahan -->
                        <div style="margin-top: 32px;">
                            <button type="submit" class="ft-btn ft-btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Right Side: Form 2 - Keamanan / Ganti Password -->
                <div class="ft-card">
                    <div style="border-bottom: 1px solid var(--ft-border); padding-bottom: 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fcd535" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <h3 style="margin: 0; font-size: 18px; font-weight: 700; text-transform: uppercase; tracking-wide: 1px;">Keamanan / Password</h3>
                    </div>
                    
                    <form action="" method="POST">
                        <input type="hidden" name="action" value="update_password">
                        
                        <!-- Input Password Lama -->
                        <div class="ft-form-group">
                            <label for="old_password" class="ft-label">Password Lama</label>
                            <input 
                                type="password" 
                                id="old_password" 
                                name="old_password" 
                                class="ft-input" 
                                required
                                placeholder="Masukkan password saat ini"
                            >
                        </div>
                        
                        <!-- Input Password Baru -->
                        <div class="ft-form-group">
                            <label for="new_password" class="ft-label">Password Baru</label>
                            <input 
                                type="password" 
                                id="new_password" 
                                name="new_password" 
                                class="ft-input" 
                                required
                                placeholder="Minimal 6 karakter"
                            >
                        </div>
                        
                        <!-- Input Konfirmasi Password Baru -->
                        <div class="ft-form-group">
                            <label for="confirm_password" class="ft-label">Konfirmasi Password Baru</label>
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                class="ft-input" 
                                required
                                placeholder="Ulangi password baru"
                            >
                        </div>
                        
                        <!-- Tombol Perbarui Password -->
                        <div style="margin-top: 32px;">
                            <button type="submit" class="ft-btn ft-btn-secondary" style="width: 100%; border: 1px solid var(--ft-yellow); color: var(--ft-yellow); background: transparent;">
                                Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>
                
            </div>
            
        </main>
        
    </div>

    <!-- Script auto fade out alert after 5 seconds for luxury UX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const successAlert = document.getElementById('alert-success');
                const errorAlert = document.getElementById('alert-error');
                if (successAlert) {
                    successAlert.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    successAlert.style.opacity = '0';
                    successAlert.style.transform = 'translateY(-10px)';
                    setTimeout(() => successAlert.remove(), 500);
                }
                if (errorAlert) {
                    errorAlert.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    errorAlert.style.opacity = '0';
                    errorAlert.style.transform = 'translateY(-10px)';
                    setTimeout(() => errorAlert.remove(), 500);
                }
            }, 5000);
        });
    </script>
</body>
</html>
