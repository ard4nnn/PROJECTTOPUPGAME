<?php
// sidebar.php — di-include oleh setiap halaman dashboard
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// SESUAIKAN nama session key dengan yang ada di project ini
$s_username  = $_SESSION['username'] ?? 'User';
$s_level     = $_SESSION['level']    ?? 'Member';
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
      <span class="ft-nav-icon">⊞</span> Dashboard
    </a>
    <a href="transaksi.php" class="ft-nav-item <?= $current_page==='transaksi' ? 'active' : '' ?>">
      <span class="ft-nav-icon">📋</span> Transaksi
    </a>
    <a href="profil.php" class="ft-nav-item <?= $current_page==='profil' ? 'active' : '' ?>">
      <span class="ft-nav-icon">👤</span> Profil Saya
    </a>
    <!-- Tautan Kembali Ke Beranda Utama -->
    <a href="../../index.php" class="ft-nav-item">
      <span class="ft-nav-icon">🏠</span> Ke Beranda
    </a>
  </nav>
  <div class="ft-nav-keluar">
    <!-- SESUAIKAN path logout dengan yang ada di project ini -->
    <a href="../auth/logout.php" class="ft-nav-item"
       onclick="return confirm('Yakin ingin keluar?')">
      <span class="ft-nav-icon">🚪</span> Keluar
    </a>
  </div>
</aside>
