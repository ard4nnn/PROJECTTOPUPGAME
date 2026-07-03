<?php
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../../config/db.php';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
$phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : '-';
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utama - FUNtopup</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="ft-wrapper">
        <?php include 'sidebar.php'; ?>
        
        <main class="ft-main">
            <!-- Header -->
            <div class="ft-page-header">
                <h1 class="ft-page-title">Selamat Datang, <?php echo htmlspecialchars($username); ?>!</h1>
                <p class="ft-page-sub">Kelola akun, pantau transaksi, dan perbarui profil FUNtopup Anda secara instan.</p>
            </div>
            
            <!-- Cards Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
                <!-- Ringkasan Profil Card -->
                <div class="ft-card">
                    <div style="font-size: 12px; color: var(--ft-text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Informasi Pengguna</div>
                    <div style="margin-top: 16px; display: flex; flex-direction: column; gap: 8px; font-size: 14px;">
                        <div><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></div>
                        <div><strong>No HP:</strong> <?php echo htmlspecialchars($phone); ?></div>
                        <div><strong>Keanggotaan:</strong> <span style="color: var(--ft-yellow); font-weight: bold;">Member</span></div>
                    </div>
                </div>
            </div>
            
            <!-- Keamanan Card -->
            <div class="ft-card" style="margin-top: 24px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--ft-yellow)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 700;">Keamanan Akun</h3>
                </div>
                <p style="font-size: 14px; color: var(--ft-text-muted); line-height: 1.6; margin: 0 0 16px 0;">
                    Amankan akun Anda dengan melakukan penggantian kata sandi secara berkala melalui menu Edit Profil. Jangan pernah membagikan password Anda kepada siapa pun termasuk pihak FUNtopup.
                </p>
                <a href="profil.php" class="ft-btn ft-btn-primary" style="text-decoration: none;">
                    Pengaturan Profil & Keamanan
                </a>
            </div>
        </main>
    </div>
</body>
</html>
