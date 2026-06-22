<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$base_url = "/PROJECTTOPUPGAME/";

if (!isset($_SESSION['user_id'])) {
    $_SESSION['mock_user'] = true;
}

// Language Logic
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'id';
    $_SESSION['lang'] = $lang;
}
$current_lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id';

$translations = [
    'id' => [
        'search_placeholder' => 'Cari Game atau Voucher...',
        'topup' => 'Topup',
        'cek_transaksi' => 'Cek Transaksi',
        'leaderboard' => 'Leaderboard',
        'kalkulator' => 'Kalkulator',
        'masuk' => 'Masuk',
        'daftar' => 'Daftar',
        'riwayat' => 'Riwayat',
        'saldo' => 'Saldo',
        'halo' => 'Halo',
        'logout' => 'Keluar',
        'beranda' => 'Beranda',
        'judul_web' => 'FUNtopup - Top Up Game Instan, Murah & Aman',
        // Cek Transaksi Page
        'cek_title' => 'Cari Transaksi Anda',
        'cek_desc' => 'Masukkan ID transaksi Anda untuk melacak status pembelian secara real-time.',
        'input_invoice' => 'Masukkan ID Transaksi (contoh: 1234)',
        'btn_cari' => 'Cari Transaksi',
        'tx_detail' => 'Detail Transaksi',
        'status_pembayaran' => 'Status Pembayaran',
        'total_tagihan' => 'Total Tagihan',
        'game' => 'Game',
        'produk' => 'Produk',
        'target_id' => 'Target ID',
        'metode' => 'Metode',
        'kembali' => 'Kembali ke Beranda',
        'tidak_ditemukan' => 'Transaksi tidak ditemukan!',
        'panduan' => 'Panduan Pembayaran',
        // Kalkulator Page
        'calc_title' => 'Kalkulator WR & Magic Wheel',
        'calc_desc' => 'Kalkulator game interaktif untuk membantu menghitung strategi bermain Anda.',
        'wr_calc' => 'Kalkulator Win Rate Mobile Legends',
        'current_match' => 'Total Pertandingan Saat Ini',
        'current_wr' => 'Win Rate Saat Ini (%)',
        'target_wr' => 'Target Win Rate (%)',
        'btn_hitung' => 'Hitung Pertandingan',
        'result_wr' => 'Hasil Perhitungan',
        'result_wr_desc' => 'Anda memerlukan sekitar <strong>{matches}</strong> kemenangan beruntun (tanpa kalah) untuk mencapai target win rate <strong>{target}%</strong>.',
        'mw_calc' => 'Estimasi Magic Wheel',
        'current_points' => 'Poin Magic Wheel Saat Ini',
        'target_points' => 'Target Poin (Maks 200)',
        'mw_desc' => 'Hitung sisa diamond yang dibutuhkan untuk mendapatkan Legend Skin.',
        'result_mw_desc' => 'Sisa poin yang dibutuhkan: <strong>{points}</strong> poin. Estimasi diamond yang dibutuhkan: sekitar <strong>{diamonds}</strong> Diamond (menggunakan opsi 5x draw seharga 270 diamond).',
        // Leaderboard Page
        'leaderboard_title' => 'Top Spender Leaderboard',
        'leaderboard_desc' => 'Daftar pengguna dengan akumulasi top up terbanyak bulan ini. Dapatkan reward khusus bagi top 3!',
        'rank' => 'Peringkat',
        'user' => 'Pengguna',
        'total_spent' => 'Total Belanja',
        'level' => 'Tingkat VIP',
        // Slider / General
        'promo_title_1' => 'PROMO JUNI SUPER',
        'promo_subtitle_1' => 'Diskon hingga Rp 5.000 untuk semua game!',
        'promo_code' => 'Kode Promo',
        'promo_title_2' => 'TOPUP INSTAN 24/7',
        'promo_subtitle_2' => 'Proses otomatis hitungan detik, aman, dan bergaransi.',
        'promo_title_3' => 'VIP SPENDER REWARD',
        'promo_subtitle_3' => 'Dapatkan cashback bulanan untuk peringkat teratas leaderboard!',
        'mode_demo' => 'Mode Demo Aktif',
        'placeholder_login' => 'Masukkan username Anda',
        'placeholder_pass' => 'Masukkan password',
        'opsi_baca' => 'Mode offline (data simulasi) aktif karena MySQL database tidak terkoneksi.',
        'game_populer' => 'Daftar Game',
        'game_populer_desc' => 'Pilih game favoritmu untuk top up instan',
        'topup_now' => 'Top Up Sekarang',
        'game_not_found' => 'Game Tidak Ditemukan',
        'game_not_found_desc' => 'Silakan cari dengan kata kunci lain.'
    ],
    'en' => [
        'search_placeholder' => 'Search Games or Vouchers...',
        'topup' => 'Topup',
        'cek_transaksi' => 'Check Transaction',
        'leaderboard' => 'Leaderboard',
        'kalkulator' => 'Calculator',
        'masuk' => 'Login',
        'daftar' => 'Register',
        'riwayat' => 'History',
        'saldo' => 'Balance',
        'halo' => 'Hello',
        'logout' => 'Logout',
        'beranda' => 'Home',
        'judul_web' => 'FUNtopup - Instant, Cheap & Safe Game Top Up',
        // Cek Transaksi Page
        'cek_title' => 'Track Your Transaction',
        'cek_desc' => 'Enter your transaction ID to track your purchase status in real-time.',
        'input_invoice' => 'Enter Transaction ID (e.g., 1234)',
        'btn_cari' => 'Search Transaction',
        'tx_detail' => 'Transaction Details',
        'status_pembayaran' => 'Payment Status',
        'total_tagihan' => 'Total Bill',
        'game' => 'Game',
        'produk' => 'Product',
        'target_id' => 'Target ID',
        'metode' => 'Method',
        'kembali' => 'Back to Home',
        'tidak_ditemukan' => 'Transaction not found!',
        'panduan' => 'Payment Guide',
        // Kalkulator Page
        'calc_title' => 'WR & Magic Wheel Calculator',
        'calc_desc' => 'Interactive gaming calculators to help you plan your play strategy.',
        'wr_calc' => 'Mobile Legends Win Rate Calculator',
        'current_match' => 'Current Total Matches',
        'current_wr' => 'Current Win Rate (%)',
        'target_wr' => 'Target Win Rate (%)',
        'btn_hitung' => 'Calculate Matches',
        'result_wr' => 'Calculation Result',
        'result_wr_desc' => 'You need around <strong>{matches}</strong> consecutive wins (without losing) to reach target win rate of <strong>{target}%</strong>.',
        'mw_calc' => 'Magic Wheel Estimation',
        'current_points' => 'Current Magic Wheel Points',
        'target_points' => 'Target Points (Max 200)',
        'mw_desc' => 'Calculate remaining diamonds needed to secure a Legend Skin.',
        'result_mw_desc' => 'Remaining points needed: <strong>{points}</strong> points. Estimated diamonds needed: around <strong>{diamonds}</strong> Diamonds (using 5x draw option for 270 diamonds).',
        // Leaderboard Page
        'leaderboard_title' => 'Top Spender Leaderboard',
        'leaderboard_desc' => 'Spenders with the highest accumulated top-up this month. Special rewards for the top 3!',
        'rank' => 'Rank',
        'user' => 'User',
        'total_spent' => 'Total Spent',
        'level' => 'VIP Level',
        // Slider / General
        'promo_title_1' => 'SUPER JUNE PROMO',
        'promo_subtitle_1' => 'Discount up to IDR 5,000 for all games!',
        'promo_code' => 'Promo Code',
        'promo_title_2' => 'INSTANT TOPUP 24/7',
        'promo_subtitle_2' => 'Automated seconds process, safe, and guaranteed.',
        'promo_title_3' => 'VIP SPENDER REWARD',
        'promo_subtitle_3' => 'Earn monthly cashback for top spender rankings!',
        'mode_demo' => 'Demo Mode Active',
        'placeholder_login' => 'Enter your username',
        'placeholder_pass' => 'Enter password',
        'opsi_baca' => 'Offline mode (simulated data) active due to database MySQL not connected.',
        'game_populer' => 'Game List',
        'game_populer_desc' => 'Choose your favorite game for instant top up',
        'topup_now' => 'Top Up Now',
        'game_not_found' => 'Game Not Found',
        'game_not_found_desc' => 'Please search with other keywords.'
    ]
];

function __($key) {
    global $translations, $current_lang;
    return isset($translations[$current_lang][$key]) ? $translations[$current_lang][$key] : $key;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('judul_web'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Light Theme Spec from DESIGN.md */
            --bg-color: #ffffff;
            --text-color: #181a20;
            --text-muted: #707a8a;
            --card-bg: #ffffff;
            --card-border: #eaecef;
            --nav-bg: #ffffff;
            --nav-text: #181a20;
            --primary-color: #fcd535; /* Binance Yellow */
            --primary-hover: #f0b90b;
            --primary-disabled: #3a3a1f;
            --accent-color: #2dbdb6; /* Turquoise */
            --success-color: #0ecb81; /* Trading Up Green */
            --danger-color: #f6465d; /* Trading Down Red */
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 12px 24px rgba(0,0,0,0.12);
            --radius-md: 6px;
            --radius-lg: 8px;
            --radius-xl: 12px;
            --header-search-bg: #fafafa;
        }

        [data-theme="dark"] {
            /* Dark Theme Spec from DESIGN.md */
            --bg-color: #0b0e11;
            --text-color: #eaecef;
            --text-muted: #707a8a;
            --card-bg: #1e2329;
            --card-border: #2b3139;
            --nav-bg: #0b0e11;
            --nav-text: #ffffff;
            --primary-color: #fcd535;
            --primary-hover: #f0b90b;
            --header-search-bg: #2b3139;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            transition: background-color 0.25s ease, border-color 0.25s ease;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Dual-Row Header */
        header {
            background-color: var(--nav-bg);
            border-bottom: 1px solid var(--card-border);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
        }

        .header-top-row {
            max-width: 1300px;
            margin: 0 auto;
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        /* Lightning Logo styling */
        .logo-wrapper {
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            cursor: pointer;
            user-select: none;
        }
        
        .lightning-svg {
            transform: rotate(-12deg);
            filter: drop-shadow(0 0 3px rgba(252, 213, 53, 0.7));
        }

        .lightning-path {
            fill: #fcd535;
            stroke: #0b0e11;
            stroke-width: 2.5px;
            stroke-linejoin: round;
            animation: lightning-flicker 3s infinite;
        }

        @keyframes lightning-flicker {
            0%, 90%, 94%, 98%, 100% {
                filter: drop-shadow(0 0 4px rgba(252, 213, 53, 0.8));
                fill: #fcd535;
                opacity: 1;
            }
            92% {
                filter: drop-shadow(0 0 1px rgba(252, 213, 53, 0.2));
                fill: #dfb70a;
                opacity: 0.6;
            }
            96% {
                filter: drop-shadow(0 0 8px rgba(252, 213, 53, 1));
                fill: #fffb8f;
                opacity: 0.9;
            }
        }

        .logo-wrapper:hover .lightning-path {
            animation: lightning-strike-fast 0.4s ease-out forwards;
        }

        @keyframes lightning-strike-fast {
            0% { transform: scale(1); filter: drop-shadow(0 0 2px #fcd535); }
            30% { transform: scale(1.2) skewX(-6deg); filter: drop-shadow(0 0 15px #fcd535) brightness(1.4); }
            50% { transform: scale(0.9) skewX(6deg); filter: drop-shadow(0 0 4px #fcd535); }
            100% { transform: scale(1) skewX(0); filter: drop-shadow(0 0 8px #fcd535); }
        }

        .logo-text {
            font-size: 26px;
            font-weight: 900;
            font-style: italic;
            letter-spacing: -1.2px;
            color: #fcd535;
            text-transform: uppercase;
            text-shadow: 2px 2px 0px #0b0e11, 0 0 8px rgba(252, 213, 53, 0.3);
            -webkit-text-stroke: 1px #0b0e11;
            display: flex;
            align-items: center;
        }

        .logo-text span {
            color: var(--text-color);
            font-style: normal;
            font-weight: 500;
            margin-left: 2px;
            text-transform: lowercase;
            -webkit-text-stroke: 0px;
            text-shadow: none;
        }

        /* Search input header */
        .search-container {
            flex: 1;
            max-width: 480px;
            position: relative;
        }

        .search-input-wrapper {
            position: relative;
            width: 100%;
        }

        .search-input-wrapper input {
            width: 100%;
            background-color: var(--header-search-bg);
            border: 1px solid var(--card-border);
            color: var(--text-color);
            font-size: 14px;
            font-family: inherit;
            padding: 10px 16px 10px 42px;
            border-radius: 20px;
            outline: none;
            transition: all 0.2s ease;
        }

        .search-input-wrapper input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(252, 213, 53, 0.15);
            background-color: var(--bg-color);
        }

        .search-icon-svg {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            width: 16px;
            height: 16px;
        }

        /* Autocomplete dropdown suggest box */
        .search-suggest-box {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            z-index: 1050;
            max-height: 280px;
            overflow-y: auto;
            display: none;
        }

        .suggest-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: var(--text-color);
            text-decoration: none;
            border-bottom: 1px solid var(--card-border);
            transition: background-color 0.2s;
        }

        .suggest-item:last-child {
            border-bottom: none;
        }

        .suggest-item:hover {
            background-color: rgba(252, 213, 53, 0.08);
        }

        .suggest-banner {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            color: white;
            flex-shrink: 0;
            border: 1px solid var(--card-border);
        }

        .suggest-info {
            display: flex;
            flex-direction: column;
        }

        .suggest-name {
            font-weight: 700;
            font-size: 14px;
        }

        .suggest-desc {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Language Switcher Dropdown */
        .lang-switcher {
            position: relative;
        }

        .lang-btn {
            background-color: var(--header-search-bg);
            border: 1px solid var(--card-border);
            color: var(--text-color);
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            user-select: none;
        }

        .lang-btn:hover {
            border-color: var(--primary-color);
        }

        .flag-icon {
            width: 18px;
            height: 12px;
            border-radius: 2px;
            object-fit: cover;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .lang-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            z-index: 1050;
            width: 170px;
            padding: 6px;
            display: none;
        }

        .lang-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            color: var(--text-color);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            border-radius: var(--radius-md);
            cursor: pointer;
        }

        .lang-option:hover {
            background-color: rgba(252, 213, 53, 0.08);
            color: var(--primary-hover);
        }

        /* Navigation Menu Row */
        .header-menu-row {
            background-color: var(--card-bg);
            border-top: 1px solid var(--card-border);
        }

        .menu-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 8px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-left-links, .nav-right-links {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .nav-item {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 0;
            position: relative;
            cursor: pointer;
        }

        .nav-item:hover, .nav-item.active {
            color: var(--primary-hover);
        }

        .nav-item.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: var(--primary-color);
        }

        .nav-item svg {
            width: 16px;
            height: 16px;
        }

        /* Buttons and General */
        .btn-masuk {
            color: var(--text-color);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-masuk:hover {
            color: var(--primary-hover);
        }

        .btn-daftar {
            background-color: var(--primary-color);
            color: #0b0e11 !important;
            padding: 6px 14px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 5px rgba(252, 213, 53, 0.2);
            transition: all 0.2s;
        }

        .btn-daftar:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .theme-toggle-inline {
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
        }

        .theme-toggle-inline:hover {
            color: var(--primary-color);
        }

        /* Container & main layout */
        .container {
            max-width: 1300px;
            width: 100%;
            margin: 0 auto;
            padding: 24px;
            flex: 1;
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-xl);
            padding: 24px;
            box-shadow: var(--shadow-sm);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 700;
            border-radius: var(--radius-md);
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: #0b0e11;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(252, 213, 53, 0.25);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--card-border);
            color: var(--text-color);
        }

        .btn-outline:hover {
            background-color: rgba(255,255,255,0.05);
            border-color: var(--text-muted);
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 20px;
            text-transform: uppercase;
        }

        .badge-pending {
            background-color: rgba(240, 185, 11, 0.15);
            color: var(--primary-hover);
        }

        .badge-sukses {
            background-color: rgba(14, 203, 129, 0.15);
            color: var(--success-color);
        }

        .badge-gagal {
            background-color: rgba(246, 70, 93, 0.15);
            color: var(--danger-color);
        }

        /* Alert notifications */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-lg);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background-color: rgba(246, 70, 93, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(246, 70, 93, 0.2);
        }

        /* Mobile layout styling adjustments */
        @media (max-width: 768px) {
            .header-top-row {
                flex-wrap: wrap;
                padding: 10px 16px;
            }
            .search-container {
                order: 3;
                max-width: 100%;
                width: 100%;
            }
            .header-menu-row {
                overflow-x: auto;
            }
            .menu-container {
                padding: 8px 16px;
                gap: 20px;
            }
            .nav-left-links, .nav-right-links {
                gap: 15px;
            }
        }
    </style>
</head>
<body>

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
                <svg width="10" height="6" viewBox="0 0 10 6" fill="none" style="margin-left: 2px;">
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
                    <span style="color: var(--primary-color); font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 4px;">
                        🟡 Rp <?php echo number_format($_SESSION['saldo'] ?? 100000, 0, ',', '.'); ?>
                    </span>
                    <span style="font-weight: 600; font-size: 14px; color: var(--text-color);">
                        <?php echo __('halo'); ?>, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
                    </span>
                    <a href="<?php echo $base_url; ?>user/auth/logout.php" class="btn-masuk" style="color: var(--danger-color);">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
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
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <span><?php echo __('daftar'); ?></span>
                    </a>
                <?php endif; ?>

                <button class="theme-toggle-inline" id="theme-toggle" title="Toggle Theme">
                    <span id="theme-icon">🌙</span>
                </button>
            </nav>
        </div>
    </div>
</header>
<main style="flex: 1; display: flex; flex-direction: column;">
