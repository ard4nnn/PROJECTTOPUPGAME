<?php
require_once '../../config/db.php';
require_once '../../includes/header.php';

if (isset($_SESSION['user_id'])) {
    header("Location: " . $base_url);
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

<div class="container" style="max-width: 450px; margin-top: 50px; margin-bottom: 50px;">
    <div class="card" style="box-shadow: var(--shadow-lg); border-radius: var(--radius-lg);">
        <div style="text-align: center; margin-bottom: 25px;">
            <h2 style="margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">Daftar Akun</h2>
            <p style="color: var(--text-muted); font-size: 14px; margin-top: 5px; margin-bottom: 0;">Buat akun FUNtopup Anda sekarang</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="padding: 12px; font-size: 14px; border-radius: var(--radius-md); margin-bottom: 15px; background-color: rgba(239, 68, 68, 0.1); color: var(--danger-color); border: 1px solid rgba(239, 68, 68, 0.2);">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success" style="padding: 12px; font-size: 14px; border-radius: var(--radius-md); margin-bottom: 15px; background-color: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16, 185, 129, 0.2);">
                🎉 <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label for="username" style="font-weight: 600; font-size: 14px;">Username</label>
                <input type="text" name="username" id="username" required placeholder="Pilih username unik"
                       style="width: 100%; padding: 12px; border-radius: var(--radius-md); border: 1px solid var(--card-border); background-color: var(--bg-color); color: var(--text-color); font-family: inherit; font-size: 14px; outline: none;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label for="email" style="font-weight: 600; font-size: 14px;">Email</label>
                <input type="email" name="email" id="email" required placeholder="Masukkan alamat email aktif"
                       style="width: 100%; padding: 12px; border-radius: var(--radius-md); border: 1px solid var(--card-border); background-color: var(--bg-color); color: var(--text-color); font-family: inherit; font-size: 14px; outline: none;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label for="no_hp" style="font-weight: 600; font-size: 14px;">No. HP / WhatsApp</label>
                <input type="text" name="no_hp" id="no_hp" required placeholder="Contoh: 0812345678"
                       style="width: 100%; padding: 12px; border-radius: var(--radius-md); border: 1px solid var(--card-border); background-color: var(--bg-color); color: var(--text-color); font-family: inherit; font-size: 14px; outline: none;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label for="password" style="font-weight: 600; font-size: 14px;">Password</label>
                <input type="password" name="password" id="password" required placeholder="Gunakan minimal 6 karakter"
                       style="width: 100%; padding: 12px; border-radius: var(--radius-md); border: 1px solid var(--card-border); background-color: var(--bg-color); color: var(--text-color); font-family: inherit; font-size: 14px; outline: none;">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 15px; margin-top: 10px;">
                Daftar Akun
            </button>
        </form>

        <div style="margin-top: 25px; text-align: center; font-size: 14px; color: var(--text-muted);">
            Sudah memiliki akun? <a href="login.php" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">Login disini</a>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
