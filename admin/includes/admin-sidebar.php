<?php
// admin-sidebar.php — di-include oleh setiap halaman dashboard admin
$current_page = basename($_SERVER['PHP_SELF'], '.php');

$s_username  = $_SESSION['admin_username'] ?? 'Admin';
$s_level     = 'Administrator';
$avatar_char = strtoupper(substr($s_username, 0, 1));
?>
<aside class="ft-sidebar">
  <div class="ft-sidebar-profile">
    <div class="ft-avatar"><?= htmlspecialchars($avatar_char) ?></div>
    <div>
      <p class="ft-sidebar-username"><?= htmlspecialchars($s_username) ?></p>
      <span class="ft-member-badge"><?= htmlspecialchars($s_level) ?></span>
    </div>
  </div>
  <nav style="flex:1; display: flex; flex-direction: column;">
    <a href="index.php" class="ft-nav-item <?= $current_page==='index' ? 'active' : '' ?>">
      <span class="ft-nav-icon"><img src="../assets/images/icons8-dashboard-layout-24.png" alt="Dashboard" style="width:20px;height:20px;object-fit:contain;"></span> Dashboard
    </a>
    <a href="transaksi.php" class="ft-nav-item <?= $current_page==='transaksi' ? 'active' : '' ?>">
      <span class="ft-nav-icon"><img src="../assets/images/icons8-transaction-30.png" alt="Transaksi" style="width:20px;height:20px;object-fit:contain;"></span> Kelola Transaksi
    </a>
    <a href="games.php" class="ft-nav-item <?= $current_page==='games' ? 'active' : '' ?>">
      <span class="ft-nav-icon" style="filter: grayscale(1) brightness(1.5);">🎮</span> Kelola Games
    </a>
    <a href="produk.php" class="ft-nav-item <?= $current_page==='produk' ? 'active' : '' ?>">
      <span class="ft-nav-icon" style="filter: grayscale(1) brightness(1.5);">📦</span> Kelola Produk
    </a>
    <a href="metode-bayar.php" class="ft-nav-item <?= $current_page==='metode-bayar' ? 'active' : '' ?>">
      <span class="ft-nav-icon" style="filter: grayscale(1) brightness(1.5);">💳</span> Metode Bayar
    </a>
    <a href="flash-sale.php" class="ft-nav-item <?= $current_page==='flash-sale' ? 'active' : '' ?>">
      <span class="ft-nav-icon" style="filter: grayscale(1) brightness(1.5);">🔥</span> Flash Sale
    </a>
    <!-- Tautan Kembali Ke Beranda Utama -->
    <a href="../index.php" class="ft-nav-item">
      <span class="ft-nav-icon"><img src="../assets/images/icons8-home-button-24.png" alt="Home" style="width:20px;height:20px;object-fit:contain;"></span> Ke Beranda
    </a>
  </nav>
  <div class="ft-nav-keluar">
    <a href="auth/logout.php" class="ft-nav-item"
       onclick="return confirm('Yakin ingin keluar?')">
      <span class="ft-nav-icon"><img src="../assets/images/icons8-home-button-24.png" alt="Keluar" style="width:20px;height:20px;object-fit:contain;"></span> Keluar
    </a>
  </div>
</aside>
