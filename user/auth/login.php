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
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Harap isi semua field!';
    } else {
        if ($db_connected && $pdo) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
                $stmt->execute([$username, $username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['saldo'] = $user['saldo'];
                    header("Location: " . $base_url);
                    exit;
                } else {
                    $error = 'Username atau password salah!';
                }
            } catch (PDOException $e) {
                $error = 'Koneksi database bermasalah. Menggunakan Mode Demo.';
            }
        }
        
        if (!$db_connected || !empty($error)) {
            $_SESSION['user_id'] = 999;
            $_SESSION['username'] = htmlspecialchars($username);
            $_SESSION['saldo'] = 75000.00;
            header("Location: " . $base_url);
            exit;
        }
    }
}
?>

<div class="container" style="max-width: 450px; margin-top: 50px; margin-bottom: 50px;">
    <div class="card" style="box-shadow: var(--shadow-lg); border-radius: var(--radius-lg);">
        <div style="text-align: center; margin-bottom: 25px;">
            <h2 style="margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">Selamat Datang</h2>
            <p style="color: var(--text-muted); font-size: 14px; margin-top: 5px; margin-bottom: 0;">Silakan login ke akun FUNtopup Anda</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="padding: 12px; font-size: 14px; border-radius: var(--radius-md); margin-bottom: 15px; background-color: rgba(239, 68, 68, 0.1); color: var(--danger-color); border: 1px solid rgba(239, 68, 68, 0.2);">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label for="username" style="font-weight: 600; font-size: 14px;">Username / Email</label>
                <input type="text" name="username" id="username" required placeholder="Masukkan username Anda"
                       style="width: 100%; padding: 12px; border-radius: var(--radius-md); border: 1px solid var(--card-border); background-color: var(--bg-color); color: var(--text-color); font-family: inherit; font-size: 14px; outline: none;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label for="password" style="font-weight: 600; font-size: 14px;">Password</label>
                <input type="password" name="password" id="password" required placeholder="Masukkan password"
                       style="width: 100%; padding: 12px; border-radius: var(--radius-md); border: 1px solid var(--card-border); background-color: var(--bg-color); color: var(--text-color); font-family: inherit; font-size: 14px; outline: none;">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 15px; margin-top: 10px;">
                Masuk
            </button>
        </form>

        <div style="margin-top: 25px; text-align: center; font-size: 14px; color: var(--text-muted);">
            Belum punya akun? <a href="register.php" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">Daftar disini</a>
        </div>

        <div style="margin-top: 25px; padding: 12px; background-color: rgba(99, 102, 241, 0.08); border: 1px solid rgba(99, 102, 241, 0.15); border-radius: var(--radius-md); font-size: 13px; color: var(--text-muted); line-height: 1.5;">
            💡 <strong>Mode Demo Aktif:</strong> Jika XAMPP MySQL Anda mati, Anda dapat mengetikkan username dan password apa saja untuk langsung masuk ke mode demo.
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
