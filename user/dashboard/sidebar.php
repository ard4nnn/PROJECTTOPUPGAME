<?php
// sidebar.php
// Pastikan session sudah terinisialisasi
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
$phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : '-';
$level = isset($_SESSION['level']) ? $_SESSION['level'] : 'Member';

$current_file = basename($_SERVER['PHP_SELF']);
?>
<aside class="ft-sidebar">
    <!-- Brand Logo -->
    <a href="../../" class="ft-sidebar-brand" style="text-decoration: none;">
        <svg viewBox="0 0 24 30" class="ft-sidebar-logo-icon">
            <path d="M14 2 L2 15 L10 15 L7 28 L20 13 L12 13 Z" />
        </svg>
        <span class="ft-sidebar-logo-text">FUN<span>topup</span></span>
    </a>
    
    <!-- User Info Card -->
    <div class="ft-sidebar-user">
        <div class="ft-user-avatar">
            <?php echo htmlspecialchars(strtoupper(substr($username, 0, 1))); ?>
        </div>
        <div class="ft-user-info">
            <div class="ft-user-name"><?php echo htmlspecialchars($username); ?></div>
            <div class="ft-user-level"><?php echo htmlspecialchars($level); ?></div>
        </div>
    </div>
    
    <!-- Navigation Links -->
    <nav class="ft-sidebar-nav">
        <!-- Dashboard Utama -->
        <a href="index.php" class="ft-nav-link <?php echo $current_file === 'index.php' ? 'active' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="9" rx="1"></rect>
                <rect x="14" y="3" width="7" height="5" rx="1"></rect>
                <rect x="14" y="12" width="7" height="9" rx="1"></rect>
                <rect x="3" y="16" width="7" height="5" rx="1"></rect>
            </svg>
            <span>Dashboard</span>
        </a>
        
        <!-- Riwayat Transaksi -->
        <a href="transaksi.php" class="ft-nav-link <?php echo $current_file === 'transaksi.php' ? 'active' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
            <span>Transaksi</span>
        </a>
        
        <!-- Edit Profil -->
        <a href="profil.php" class="ft-nav-link <?php echo $current_file === 'profil.php' ? 'active' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>Edit Profil</span>
        </a>
        
        <!-- Kembali Ke Beranda -->
        <a href="../../" class="ft-nav-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span>Ke Beranda</span>
        </a>
        
        <!-- Keluar / Logout -->
        <a href="../auth/logout.php" class="ft-nav-link ft-nav-logout">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
            </svg>
            <span>Keluar</span>
        </a>
    </nav>
</aside>
