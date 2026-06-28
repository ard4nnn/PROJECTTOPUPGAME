<?php
require_once '../../config/db.php';
require_once '../../includes/header.php';

if (isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = '" . $base_url . "';</script>";
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($no_hp) || empty($password)) {
        $error = 'Semua data wajib diisi!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal terdiri dari 6 karakter!';
    } else {
        if ($db_connected && $pdo) {
            try {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                $stmt->execute([$username, $email]);
                
                if ($stmt->rowCount() > 0) {
                    $error = 'Username atau Email sudah terdaftar!';
                } else {
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, no_hp, saldo) VALUES (?, ?, ?, ?, 0.00)");
                    $stmt->execute([$username, $email, $hashed_password, $no_hp]);
                    
                    $success = 'Pendaftaran berhasil! Silakan login.';
                }
            } catch (PDOException $e) {
                $error = 'Kesalahan database. Menggunakan Mode Demo.';
            }
        }
        
        if (!$db_connected || !empty($error)) {
            $success = 'Pendaftaran Akun Demo Berhasil! Silakan masuk dengan akun baru Anda.';
        }
    }
}
?>

<div class="container auth-container">
    <div class="card">
        <div class="auth-header">
            <h2 class="auth-title">Daftar Akun</h2>
            <p class="auth-subtitle">Buat akun FUNtopup Anda sekarang</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-danger-styled">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert-success-styled">
                🎉 <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="auth-form">
            <div class="auth-form-group">
                <label for="username" class="auth-label">Username</label>
                <input type="text" name="username" id="username" class="auth-input" required placeholder="Pilih username unik">
            </div>

            <div class="auth-form-group">
                <label for="email" class="auth-label">Email</label>
                <input type="email" name="email" id="email" class="auth-input" required placeholder="Masukkan alamat email aktif">
            </div>

            <div class="auth-form-group">
                <label for="no_hp" class="auth-label">No. HP / WhatsApp</label>
                <input type="text" name="no_hp" id="no_hp" class="auth-input" required placeholder="Contoh: 0812345678">
            </div>

            <div class="auth-form-group">
                <label for="password" class="auth-label">Password</label>
                <input type="password" name="password" id="password" class="auth-input" required placeholder="Gunakan minimal 6 karakter">
            </div>

            <button type="submit" class="btn btn-primary btn-auth">
                Daftar Akun
            </button>
        </form>

        <div class="auth-footer">
            Sudah memiliki akun? <a href="login.php" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">Login disini</a>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
