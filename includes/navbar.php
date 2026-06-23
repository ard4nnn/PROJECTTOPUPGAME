<?php
// Navbar Component template. Assumes init.php is loaded.
?>
<header>
    <!-- Top Bar -->
    <div class="header-top-row">
        <!-- Logo -->
        <a href="<?php echo $base_url; ?>" class="logo-wrapper">
            <svg class="lightning-svg" viewBox="0 0 24 30" width="22" height="28">
                <path d="M14 2 L2 15 L10 15 L7 28 L20 13 L12 13 Z" class="lightning-path" />
            </svg>
            <span class="logo-text">FUN<span>topup</span></span>
        </a>

        <!-- Search Bar with suggesting autocomplete -->
        <div class="search-container">
            <div class="search-input-wrapper">
                <svg class="search-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" id="header-search" placeholder="<?php echo __('search_placeholder'); ?>" autocomplete="off">
            </div>
            <div class="search-suggest-box" id="search-suggest"></div>
        </div>

        <!-- Language Switcher -->
        <div class="lang-switcher">
            <button class="lang-btn" id="lang-toggle">
                <?php if ($current_lang === 'id'): ?>
                    <img src="https://flagcdn.com/w40/id.png" class="flag-icon" alt="Indonesian">
                    <span>ID / IDR</span>
                <?php else: ?>
                    <img src="https://flagcdn.com/w40/gb.png" class="flag-icon" alt="English">
                    <span>EN / USD</span>
                <?php endif; ?>
                <svg width="10" height="6" viewBox="0 0 10 6" fill="none" class="lang-arrow">
                    <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="lang-dropdown" id="lang-menu">
                <div class="lang-option" onclick="changeLanguage('id')">
                    <img src="https://flagcdn.com/w40/id.png" class="flag-icon" alt="Indonesian">
                    <span>Indonesia (IDR)</span>
                </div>
                <div class="lang-option" onclick="changeLanguage('en')">
                    <img src="https://flagcdn.com/w40/gb.png" class="flag-icon" alt="English">
                    <span>English (USD)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu Bar -->
    <div class="header-menu-row">
        <div class="menu-container">
            <!-- Left nav links -->
            <nav class="nav-left-links">
                <?php
                $current_page = basename($_SERVER['PHP_SELF']);
                ?>
                <a href="<?php echo $base_url; ?>" class="nav-item <?php echo ($current_page == 'index.php' || $current_page == '') ? 'active' : ''; ?>">
                    <!-- Topup Icon -->
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span><?php echo __('topup'); ?></span>
                </a>
                <a href="<?php echo $base_url; ?>cek-transaksi.php" class="nav-item <?php echo ($current_page == 'cek-transaksi.php') ? 'active' : ''; ?>">
                    <!-- Cek Transaksi Icon -->
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span><?php echo __('cek_transaksi'); ?></span>
                </a>
                <a href="<?php echo $base_url; ?>leaderboard.php" class="nav-item <?php echo ($current_page == 'leaderboard.php') ? 'active' : ''; ?>">
                    <!-- Leaderboard Icon -->
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span><?php echo __('leaderboard'); ?></span>
                </a>
                <a href="<?php echo $base_url; ?>kalkulator.php" class="nav-item <?php echo ($current_page == 'kalkulator.php') ? 'active' : ''; ?>">
                    <!-- Kalkulator Icon -->
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 11h.01M12 14h.01M9 11h.01M12 17h.01M15 11h.01M15 14h.01M15 17h.01M5 19V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2z"></path>
                    </svg>
                    <span><?php echo __('kalkulator'); ?></span>
                </a>
            </nav>

            <!-- Right nav links -->
            <nav class="nav-right-links">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo $base_url; ?>user/riwayat.php" class="nav-item <?php echo ($current_page == 'riwayat.php') ? 'active' : ''; ?>">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span><?php echo __('riwayat'); ?></span>
                    </a>
                    <span class="nav-balance">
                        🟡 Rp <?php echo number_format($_SESSION['saldo'] ?? 100000, 0, ',', '.'); ?>
                    </span>
                    <span class="nav-username">
                        <?php echo __('halo'); ?>, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
                    </span>
                    <a href="<?php echo $base_url; ?>user/auth/logout.php" class="btn-masuk btn-logout">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="btn-logout-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span><?php echo __('logout'); ?></span>
                    </a>
                <?php else: ?>
                    <a href="<?php echo $base_url; ?>user/riwayat.php" class="nav-item <?php echo ($current_page == 'riwayat.php') ? 'active' : ''; ?>">
                        <span><?php echo __('riwayat'); ?> (Demo)</span>
                    </a>
                    <a href="<?php echo $base_url; ?>user/auth/login.php" class="btn-masuk">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        <span><?php echo __('masuk'); ?></span>
                    </a>
                    <a href="<?php echo $base_url; ?>user/auth/register.php" class="btn-daftar">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="btn-daftar-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <span><?php echo __('daftar'); ?></span>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>
