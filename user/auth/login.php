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
                    echo "<script>window.location.href = '" . $base_url . "';</script>";
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
            echo "<script>window.location.href = '" . $base_url . "';</script>";
            exit;
        }
    }
}
?>

<div class="container auth-container">
    <div class="card">
        <div class="auth-header">
            <h2 class="auth-title">Selamat Datang</h2>
            <p class="auth-subtitle">Silakan login ke akun FUNtopup Anda</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-danger-styled">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="auth-form">
            <div class="auth-form-group">
                <label for="username" class="auth-label">Username / Email</label>
                <input type="text" name="username" id="username" class="auth-input" required placeholder="Masukkan username Anda">
            </div>

            <div class="auth-form-group">
                <label for="password" class="auth-label">Password</label>
                <input type="password" name="password" id="password" class="auth-input" required placeholder="Masukkan password">
            </div>

            <button type="submit" class="btn btn-primary btn-auth">
                Masuk
            </button>
        </form>

        <div class="auth-footer">
            Belum punya akun? <a href="register.php" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">Daftar disini</a>
        </div>

        <div class="demo-info-card">
            💡 <strong>Mode Demo Aktif:</strong> Jika XAMPP MySQL Anda mati, Anda dapat mengetikkan username dan password apa saja untuk langsung masuk ke mode demo.
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
