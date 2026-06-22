<?php
require_once 'config/db.php';
require_once 'includes/header.php';

$games = [];

if ($db_connected && $pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM games WHERE status = 'aktif' ORDER BY nama_game ASC");
        $games = $stmt->fetchAll();
    } catch (PDOException $e) {
        $db_connected = false;
    }
}

if (!$db_connected) {
    $games = [
        [
            'id' => 1,
            'nama_game' => 'Mobile Legends',
            'slug' => 'mobile-legends',
            'deskripsi' => 'Top up Diamond Mobile Legends termurah dan tercepat hanya dalam hitungan detik.',
            'status' => 'aktif'
        ],
        [
            'id' => 2,
            'nama_game' => 'Free Fire',
            'slug' => 'free-fire',
            'deskripsi' => 'Top up Diamond Free Fire untuk membeli elite pass dan bundle favoritmu.',
            'status' => 'aktif'
        ],
        [
            'id' => 3,
            'nama_game' => 'PUBG Mobile',
            'slug' => 'pubg-mobile',
            'deskripsi' => 'Top up UC PUBG Mobile termurah untuk skin keren dan Royale Pass.',
            'status' => 'aktif'
        ],
        [
            'id' => 4,
            'nama_game' => 'Genshin Impact',
            'slug' => 'genshin-impact',
            'deskripsi' => 'Top up Genesis Crystals Genshin Impact untuk gacha karakter impianmu.',
            'status' => 'aktif'
        ]
    ];
}


// ============================================================
// 🔥 DATA FLASH SALE - EDIT DI SINI UNTUK MENGUBAH PROMO
// ============================================================
$flash_sale_end = '2026-07-01 23:59:59'; // Tanggal & waktu berakhir flash sale (format: YYYY-MM-DD HH:MM:SS)
$flash_sale_title = 'PASTI TER-MURAAHH';
$flash_sale_subtitle = 'Bandingkan dan Buktikan Sendiri!';

$flash_sale_items = [
    [
        'icon'          => '🔥',          // Emoji atau URL gambar icon game (contoh: 'assets/images/ff.png')
        'nama'          => 'Top Up Free Fire MAX | Membership FF Termurah Gratis Biaya Admin',
        'harga_normal'  => 29000,          // Harga coret (sebelum diskon)
        'harga_promo'   => 25848,          // Harga setelah diskon
        'kategori'      => 'Membership Mingguan - PROMO ✨',
        'slug'          => 'free-fire',    // Slug game untuk link
    ],
    [
        'icon'          => '💎',
        'nama'          => 'Top Up Free Fire MAX | Membership FF Termurah Gratis Biaya Admin',
        'harga_normal'  => 95000,
        'harga_promo'   => 75285,
        'kategori'      => 'Membership Bulanan - LIMITED 🔥',
        'slug'          => 'free-fire',
    ],
    [
        'icon'          => '🎮',
        'nama'          => 'Top Up Free Fire MAX | Membership FF Termurah Gratis Biaya Admin',
        'harga_normal'  => 99000,
        'harga_promo'   => 77488,
        'kategori'      => 'Membership Bulanan - PROMO',
        'slug'          => 'free-fire',
    ],
    [
        'icon'          => '⚠️',
        'nama'          => 'Top Up Free Fire | Membership FF Termurah Gratis Biaya Admin',
        'harga_normal'  => 0,
        'harga_promo'   => 0,
        'kategori'      => '⚠ SYARAT & KETENTUAN [LIMITED] [PROMO] dan [TEBUS MURAH]',
        'slug'          => 'free-fire',
    ],
    [
        'icon'          => '🏆',
        'nama'          => 'Top Up Mobile Legends | Diamond ML Termurah',
        'harga_normal'  => 25000,
        'harga_promo'   => 19285,
        'kategori'      => 'Membership Mingguan ✨ [🎖 450]',
        'slug'          => 'mobile-legends',
    ],
    [
        'icon'          => '⚡',
        'nama'          => 'Top Up PUBG Mobile | UC PUBG Termurah Gratis Biaya Admin',
        'harga_normal'  => 79000,
        'harga_promo'   => 62500,
        'kategori'      => 'Weekly Pass - PROMO 🔥',
        'slug'          => 'pubg-mobile',
    ],
    [
        'icon'          => '✨',
        'nama'          => 'Top Up Genshin Impact | Genesis Crystals Termurah',
        'harga_normal'  => 89000,
        'harga_promo'   => 72000,
        'kategori'      => 'Blessing of Welkin Moon ✨',
        'slug'          => 'genshin-impact',
    ],
];
// ============================================================
?>

<style>
    /* Carousel Slider Styling */
    .carousel-container {
        position: relative;
        max-width: 1300px;
        margin: 20px auto;
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        height: 380px;
        background-color: var(--card-bg);
        border: 1px solid var(--card-border);
    }

    .carousel-track {
        display: flex;
        width: 200%;
        height: 100%;
        transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .carousel-slide {
        width: 50%;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    /* Slide 1: Rich HTML replica of promo code banner */
    .rich-slide {
        background: linear-gradient(135deg, #131b2e 0%, #090d16 100%);
        display: flex;
        align-items: center;
        padding: 40px 60px;
    }

    .promo-content {
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: space-between;
        gap: 30px;
    }

    .promo-left {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .promo-left h2 {
        font-size: 26px;
        color: #eaecef;
        font-weight: 600;
        margin: 0;
        letter-spacing: 1px;
    }

    .promo-left h1 {
        font-size: 42px;
        color: var(--primary-color);
        font-weight: 900;
        font-style: italic;
        margin: 5px 0 10px 0;
        text-shadow: 2px 2px 0px #000;
        -webkit-text-stroke: 1px #000;
        letter-spacing: -1px;
    }

    .promo-note {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: 25px;
        letter-spacing: 0.5px;
    }

    .promo-action {
        display: inline-flex;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px dashed var(--primary-color);
        padding: 8px 16px;
        border-radius: var(--radius-md);
        gap: 8px;
        font-size: 12.5px;
        max-width: fit-content;
    }

    .promo-action strong {
        color: var(--primary-color);
    }

    .promo-action .now-badge {
        background-color: var(--primary-color);
        color: #0b0e11;
        padding: 3px 8px;
        border-radius: 4px;
        font-weight: 800;
        font-size: 11px;
    }

    .promo-right {
        flex: 1.2;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ticket-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        width: 100%;
    }

    .ticket {
        background-color: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 12px 16px;
        display: flex;
        flex-direction: column;
        position: relative;
        cursor: pointer;
        transition: all 0.25s ease;
        overflow: hidden;
        border-left: 4px solid var(--primary-color);
    }

    .ticket::before, .ticket::after {
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: #090d16;
        border-radius: 50%;
        left: -7px;
    }

    .ticket::before {
        top: calc(50% - 10px);
    }

    .ticket::after {
        bottom: calc(50% - 10px);
    }

    .ticket:hover {
        transform: translateY(-3px);
        border-color: var(--primary-color);
        box-shadow: 0 5px 12px rgba(252, 213, 53, 0.15);
    }

    .ticket-label {
        font-size: 10px;
        color: var(--text-muted);
        font-weight: 700;
    }

    .ticket-code {
        font-size: 15px;
        font-weight: 800;
        color: #eaecef;
        margin: 2px 0;
    }

    .ticket-discount {
        font-size: 13px;
        color: var(--primary-color);
        font-weight: 800;
    }

    .ticket-min {
        font-size: 9px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    /* Slide 2: Image Promo */
    .img-slide {
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: flex-end;
    }

    .slide-overlay {
        background: linear-gradient(to top, rgba(11, 14, 17, 0.95) 0%, rgba(11, 14, 17, 0.4) 70%, transparent 100%);
        width: 100%;
        padding: 40px 60px;
    }

    .slide-overlay h2 {
        font-size: 32px;
        color: white;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .slide-overlay p {
        font-size: 15px;
        color: #eaecef;
        opacity: 0.9;
        max-width: 600px;
        line-height: 1.5;
    }

    /* Carousel Nav Buttons */
    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(30, 35, 41, 0.65);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        backdrop-filter: blur(4px);
        transition: all 0.2s;
    }

    .carousel-btn:hover {
        background-color: var(--primary-color);
        color: #0b0e11;
        border-color: var(--primary-color);
    }

    .carousel-btn.prev {
        left: 20px;
    }

    .carousel-btn.next {
        right: 20px;
    }

    .carousel-dots {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 10;
    }

    .dot {
        width: 20px;
        height: 5px;
        background-color: rgba(255,255,255,0.3);
        border-radius: 3px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .dot.active {
        background-color: var(--primary-color);
        width: 30px;
    }

    /* Game Card Grid Styling */
    .game-card {
        background-color: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transform: translateY(0);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .game-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-color);
    }

    .card-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 900;
        color: white;
        position: relative;
        letter-spacing: -0.5px;
    }

    .card-banner-text {
        opacity: 0.9;
        text-shadow: 0 3px 6px rgba(0,0,0,0.6);
        z-index: 2;
    }

    .banner-fade {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50px;
        background: linear-gradient(to top, var(--card-bg), transparent);
        z-index: 1;
    }

    /* Toast Notification */
    .toast {
        position: fixed;
        bottom: 25px;
        right: 25px;
        background-color: var(--card-bg);
        color: var(--text-color);
        border: 1px solid var(--primary-color);
        padding: 12px 20px;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        z-index: 2000;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        font-weight: 600;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }

    @media (max-width: 992px) {
        .carousel-container {
            height: 480px;
        }
        .rich-slide {
            padding: 30px;
        }
        .promo-content {
            flex-direction: column;
            justify-content: center;
            gap: 20px;
        }
        .promo-left {
            text-align: center;
            align-items: center;
        }
    }

    @media (max-width: 768px) {
        .carousel-container {
            height: 420px;
            margin: 10px auto;
        }
        .promo-left h1 {
            font-size: 30px;
        }
        .promo-left h2 {
            font-size: 20px;
        }
        .ticket-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        .slide-overlay {
            padding: 20px;
        }
        .slide-overlay h2 {
            font-size: 22px;
        }
    }

    /* Flash Sale Section Styling */
    .flash-sale-section {
        max-width: 1300px;
        margin: 40px auto;
        padding: 0 20px;
        position: relative;
    }

    .flash-sale-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--card-border);
        padding-bottom: 15px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .flash-sale-title-container {
        display: flex;
        flex-direction: column;
    }

    .flash-sale-title {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: var(--text-color);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .flash-sale-subtitle {
        margin: 5px 0 0 0;
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 500;
    }

    .flash-sale-timer {
        display: flex;
        align-items: center;
        gap: 12px;
        background-color: var(--card-bg);
        padding: 6px 14px;
        border-radius: var(--radius-lg);
        border: 1px solid var(--card-border);
    }

    .flash-sale-timer-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .timer-digits-container {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .timer-box {
        background-color: var(--bg-color);
        border: 1px solid var(--card-border);
        border-radius: 4px;
        min-width: 44px;
        padding: 4px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .timer-val {
        font-size: 15px;
        font-weight: 800;
        color: var(--primary-color);
        line-height: 1.1;
    }

    .timer-unit {
        font-size: 8px;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 600;
        margin-top: 1px;
    }

    .timer-separator {
        font-weight: 800;
        color: var(--primary-color);
        font-size: 15px;
        animation: blinker 1s linear infinite;
    }

    @keyframes blinker {
        50% { opacity: 0.3; }
    }

    .flash-sale-carousel-wrapper {
        position: relative;
        width: 100%;
    }

    .flash-sale-carousel {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 10px 0 20px 0;
        scrollbar-width: none; /* Firefox */
    }

    .flash-sale-carousel::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }

    .flash-sale-card {
        flex: 0 0 280px;
        background-color: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-xl);
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
    }

    .flash-sale-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary-color);
        box-shadow: 0 6px 20px rgba(252, 213, 53, 0.12);
    }

    .flash-sale-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .flash-sale-card-icon-wrapper {
        width: 44px;
        height: 44px;
        border-radius: 9999px;
        background-color: rgba(252, 213, 53, 0.1);
        border: 1px solid rgba(252, 213, 53, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        overflow: hidden;
    }

    .flash-sale-card-icon-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .flash-sale-badge-save {
        position: absolute;
        top: 18px;
        right: -38px;
        background: linear-gradient(135deg, #f6465d 0%, #d63050 100%);
        color: #ffffff;
        font-size: 9px;
        font-weight: 800;
        padding: 5px 45px;
        transform: rotate(45deg);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        z-index: 5;
        box-shadow: 0 2px 6px rgba(246, 70, 93, 0.4);
        text-align: center;
        white-space: nowrap;
        line-height: 1.2;
    }

    .flash-sale-card-body {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex-grow: 1;
    }

    .flash-sale-card-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-color);
        line-height: 1.4;
        height: 38px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .flash-sale-card-category {
        font-size: 11px;
        font-weight: 700;
        color: var(--primary-color);
        background-color: rgba(252, 213, 53, 0.08);
        border: 1px dashed rgba(252, 213, 53, 0.2);
        padding: 4px 8px;
        border-radius: 4px;
        width: fit-content;
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .flash-sale-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: 18px;
        border-top: 1px solid var(--card-border);
        padding-top: 14px;
    }

    .flash-sale-price-wrapper {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .flash-sale-price-old {
        font-size: 11px;
        color: var(--text-muted);
        text-decoration: line-through;
    }

    .flash-sale-price-new {
        font-size: 16px;
        font-weight: 800;
        color: var(--text-color);
    }

    .flash-sale-card-action {
        background-color: var(--primary-color);
        color: #181a20;
        width: 32px;
        height: 32px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 16px;
        transition: all 0.25s ease;
    }

    .flash-sale-card:hover .flash-sale-card-action {
        background-color: var(--primary-hover);
        transform: translateX(3px);
    }

    .flash-sale-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        border-radius: 9999px;
        background-color: rgba(30, 35, 41, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s;
        backdrop-filter: blur(4px);
        box-shadow: var(--shadow-md);
    }

    .flash-sale-nav-btn:hover {
        background-color: var(--primary-color);
        color: #181a20;
        border-color: var(--primary-color);
    }

    .flash-sale-nav-btn.prev {
        left: -20px;
    }

    .flash-sale-nav-btn.next {
        right: -20px;
    }

    @media (max-width: 768px) {
        .flash-sale-nav-btn {
            display: none !important;
        }
        .flash-sale-carousel {
            gap: 15px;
            padding-left: 5px;
            padding-right: 5px;
        }
        .flash-sale-card {
            flex: 0 0 250px;
            padding: 16px;
        }
        .flash-sale-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        .flash-sale-timer {
            width: 100%;
            justify-content: space-between;
            box-sizing: border-box;
        }
    }
</style>

<!-- Hidden Search input mapped from header search -->
<input type="hidden" id="search-input">

<!-- Promotional Slider -->
<div class="carousel-container">
    <div class="carousel-track" id="carousel-track">
        <!-- Slide 1: Rich HTML Promo tickets -->
        <div class="carousel-slide rich-slide">
            <div class="promo-content">
                <div class="promo-left">
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px; color:#fcd535;">
                        <svg viewBox="0 0 24 30" width="20" height="24" fill="#fcd535"><path d="M14 2 L2 15 L10 15 L7 28 L20 13 L12 13 Z"/></svg>
                        <span style="font-weight:900; font-style:italic; font-size:18px; text-transform:uppercase; letter-spacing:-0.5px;">FUNtopup</span>
                    </div>
                    <h2><?php echo __('promo_title_1'); ?></h2>
                    <h1>DISKON UP TO 5K</h1>
                    <p class="promo-note"><?php echo __('promo_subtitle_1'); ?></p>
                    <div class="promo-action">
                        <span>TOP UP DI</span>
                        <strong>WWW.FUNTOPUP.COM</strong>
                        <span class="now-badge">SEKARANG JUGA!</span>
                    </div>
                </div>
                <div class="promo-right">
                    <div class="ticket-grid">
                        <div class="ticket" onclick="copyPromoCode('JUNITOPUP2026')">
                            <span class="ticket-label"><?php echo __('promo_code'); ?> :</span>
                            <span class="ticket-code">JUNITOPUP2026</span>
                            <span class="ticket-discount">DISKON Rp 1.000</span>
                            <span class="ticket-min">*MIN. PEMBELIAN 10K</span>
                        </div>
                        <div class="ticket" onclick="copyPromoCode('JUNICERIA2026')">
                            <span class="ticket-label"><?php echo __('promo_code'); ?> :</span>
                            <span class="ticket-code">JUNICERIA2026</span>
                            <span class="ticket-discount">DISKON Rp 3.000</span>
                            <span class="ticket-min">*MIN. PEMBELIAN 30K</span>
                        </div>
                        <div class="ticket" onclick="copyPromoCode('JUNIMENYALA2026')">
                            <span class="ticket-label"><?php echo __('promo_code'); ?> :</span>
                            <span class="ticket-code">JUNIMENYALA2026</span>
                            <span class="ticket-discount">DISKON Rp 2.000</span>
                            <span class="ticket-min">*MIN. PEMBELIAN 20K</span>
                        </div>
                        <div class="ticket" onclick="copyPromoCode('JUNIBERKAH2026')">
                            <span class="ticket-label"><?php echo __('promo_code'); ?> :</span>
                            <span class="ticket-code">JUNIBERKAH2026</span>
                            <span class="ticket-discount">DISKON Rp 5.000</span>
                            <span class="ticket-min">*MIN. PEMBELIAN 50K</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Slide 2: Image Promo using generated asset -->
        <div class="carousel-slide img-slide" style="background-image: url('<?php echo $base_url; ?>assets/images/promo_banner.png');">
            <div class="slide-overlay">
                <span class="badge" style="background-color: var(--primary-color); color: #0b0e11; margin-bottom: 10px;">HOT SELLING</span>
                <h2><?php echo __('promo_title_2'); ?></h2>
                <p><?php echo __('promo_subtitle_2'); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Panah navigasi -->
    <button class="carousel-btn prev" id="carousel-prev">&larr;</button>
    <button class="carousel-btn next" id="carousel-next">&rarr;</button>
    
    <!-- Indikator bullet -->
    <div class="carousel-dots" id="carousel-dots">
        <span class="dot active" onclick="setSlide(0)"></span>
        <span class="dot" onclick="setSlide(1)"></span>
    </div>
</div>

<!-- Flash Sale Section -->
<div class="flash-sale-section">
    <div class="flash-sale-header">
        <div class="flash-sale-title-container">
            <h2 class="flash-sale-title">
                🔥 <?php echo htmlspecialchars($flash_sale_title); ?>
            </h2>
            <p class="flash-sale-subtitle">
                <?php echo htmlspecialchars($flash_sale_subtitle); ?>
            </p>
        </div>
        <div class="flash-sale-timer">
            <span class="flash-sale-timer-label"><?php echo $current_lang === 'id' ? 'Berakhir dalam:' : 'Ends in:'; ?></span>
            <div class="timer-digits-container" id="flash-sale-countdown" data-endtime="<?php echo htmlspecialchars($flash_sale_end); ?>">
                <div class="timer-box">
                    <span class="timer-val" id="fs-days">00</span>
                    <span class="timer-unit"><?php echo $current_lang === 'id' ? 'Hari' : 'Days'; ?></span>
                </div>
                <span class="timer-separator">:</span>
                <div class="timer-box">
                    <span class="timer-val" id="fs-hours">00</span>
                    <span class="timer-unit"><?php echo $current_lang === 'id' ? 'Jam' : 'Hrs'; ?></span>
                </div>
                <span class="timer-separator">:</span>
                <div class="timer-box">
                    <span class="timer-val" id="fs-mins">00</span>
                    <span class="timer-unit"><?php echo $current_lang === 'id' ? 'Menit' : 'Mins'; ?></span>
                </div>
                <span class="timer-separator">:</span>
                <div class="timer-box">
                    <span class="timer-val" id="fs-secs">00</span>
                    <span class="timer-unit"><?php echo $current_lang === 'id' ? 'Detik' : 'Secs'; ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="flash-sale-carousel-wrapper">
        <!-- Navigation Buttons -->
        <button class="flash-sale-nav-btn prev" id="fs-prev">&larr;</button>
        
        <div class="flash-sale-carousel" id="fs-carousel">
            <?php foreach ($flash_sale_items as $item): 
                $savings = $item['harga_normal'] - $item['harga_promo'];
                $has_savings = ($savings > 0 && $item['harga_normal'] > 0);
                $is_image_icon = (strpos($item['icon'], '/') !== false || strpos($item['icon'], '.') !== false);
            ?>
                <a href="user/topup/game.php?slug=<?php echo htmlspecialchars($item['slug']); ?>" class="flash-sale-card">
                    <?php if ($has_savings): ?>
                        <span class="flash-sale-badge-save">
                            <?php echo $current_lang === 'id' ? 'HEMAT Rp ' . number_format($savings, 0, ',', '.') : 'SAVE Rp ' . number_format($savings, 0, ',', '.'); ?>
                        </span>
                    <?php endif; ?>
                    <div class="flash-sale-card-top">
                        <div class="flash-sale-card-icon-wrapper">
                            <?php if ($is_image_icon): ?>
                                <img src="<?php echo htmlspecialchars($item['icon']); ?>" alt="" class="flash-sale-card-icon-img">
                            <?php else: ?>
                                <?php echo htmlspecialchars($item['icon']); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="flash-sale-card-body">
                        <span class="flash-sale-card-name"><?php echo htmlspecialchars($item['nama']); ?></span>
                        <span class="flash-sale-card-category"><?php echo htmlspecialchars($item['kategori']); ?></span>
                    </div>
                    
                    <div class="flash-sale-card-footer">
                        <div class="flash-sale-price-wrapper">
                            <?php if ($item['harga_normal'] > 0): ?>
                                <span class="flash-sale-price-old">
                                    Rp <?php echo number_format($item['harga_normal'], 0, ',', '.'); ?>
                                </span>
                            <?php else: ?>
                                <span class="flash-sale-price-old" style="visibility: hidden;">Rp 0</span>
                            <?php endif; ?>
                            
                            <span class="flash-sale-price-new">
                                <?php if ($item['harga_promo'] > 0): ?>
                                    Rp <?php echo number_format($item['harga_promo'], 0, ',', '.'); ?>
                                <?php else: ?>
                                    <?php echo $current_lang === 'id' ? 'Lihat Detail' : 'View Details'; ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <div class="flash-sale-card-action">
                            &rarr;
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        
        <button class="flash-sale-nav-btn next" id="fs-next">&rarr;</button>
    </div>
</div>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; gap: 15px; flex-wrap: wrap;">
        <div>
            <h2 style="margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;"><?php echo __('game_populer'); ?></h2>
            <p style="margin: 5px 0 0 0; color: var(--text-muted); font-size: 14px; font-weight: 500;"><?php echo __('game_populer_desc'); ?></p>
        </div>
        
        <?php if (!$db_connected): ?>
            <span class="badge" style="background-color: rgba(246, 70, 93, 0.15); color: var(--danger-color); font-weight: 700; border: 1px solid rgba(246, 70, 93, 0.2);">
                🔌 <?php echo __('mode_demo'); ?>
            </span>
        <?php else: ?>
            <span class="badge" style="background-color: rgba(14, 203, 129, 0.15); color: var(--success-color); font-weight: 700; border: 1px solid rgba(14, 203, 129, 0.2);">
                🟢 Online Mode
            </span>
        <?php endif; ?>
    </div>

    <!-- Grid Game List -->
    <div id="game-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(270px, 1fr)); gap: 24px;">
        <?php foreach ($games as $game): ?>
            <div class="game-card" data-name="<?php echo strtolower(htmlspecialchars($game['nama_game'])); ?>">
                <div>
                    <div class="card-banner">
                        <span class="card-banner-text"><?php echo htmlspecialchars($game['nama_game']); ?></span>
                        <div class="banner-fade"></div>
                    </div>

                    <div style="padding: 20px;">
                        <h3 style="margin: 0 0 10px 0; font-size: 18px; font-weight: 700; color: var(--text-color);"><?php echo htmlspecialchars($game['nama_game']); ?></h3>
                        <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.5; font-weight: 400; height: 60px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                            <?php echo htmlspecialchars($game['deskripsi'] ? $game['deskripsi'] : 'Top up instan voucher game ' . $game['nama_game'] . ' termurah dan aman.'); ?>
                        </p>
                    </div>
                </div>

                <div style="padding: 0 20px 20px 20px;">
                    <a href="user/topup/game.php?slug=<?php echo $game['slug']; ?>" class="btn btn-primary" style="width: 100%; text-align: center;">
                        <?php echo __('topup_now'); ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="no-game-alert" style="display: none; text-align: center; padding: 50px 30px; background-color: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius-xl); margin-top: 20px;">
        <span style="font-size: 44px; display: block; margin-bottom: 10px;">🔍</span>
        <h3 style="margin-bottom: 5px; font-size: 20px; font-weight: 700;"><?php echo __('game_not_found'); ?></h3>
        <p style="color: var(--text-muted); margin: 0; font-size: 14px;"><?php echo __('game_not_found_desc'); ?></p>
    </div>
</div>

<!-- Toast element -->
<div class="toast" id="toast-notif">
    <span style="font-size: 16px;">📋</span>
    <span id="toast-text">Kode promo disalin!</span>
</div>

<script>
    // Slider Logic
    const track = document.getElementById('carousel-track');
    const prevBtn = document.getElementById('carousel-prev');
    const nextBtn = document.getElementById('carousel-next');
    const dots = document.querySelectorAll('.dot');
    
    let currentSlide = 0;
    const totalSlides = 2;
    let autoSlideInterval;

    function setSlide(index) {
        currentSlide = index;
        if (currentSlide < 0) currentSlide = totalSlides - 1;
        if (currentSlide >= totalSlides) currentSlide = 0;

        track.style.transform = `translateX(-${currentSlide * 50}%)`;

        dots.forEach((dot, idx) => {
            if (idx === currentSlide) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
        resetAutoSlide();
    }

    if (prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => {
            setSlide(currentSlide - 1);
        });

        nextBtn.addEventListener('click', () => {
            setSlide(currentSlide + 1);
        });
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            setSlide(currentSlide + 1);
        }, 5000);
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
    }

    startAutoSlide();

    // Copy Promo Voucher
    function copyPromoCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            const toast = document.getElementById('toast-notif');
            const toastText = document.getElementById('toast-text');
            const isEng = '<?php echo $current_lang; ?>' === 'en';
            
            toastText.textContent = isEng ? `Promo code "${code}" copied!` : `Kode promo "${code}" berhasil disalin!`;
            
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }).catch(err => {
            console.error('Gagal menyalin: ', err);
        });
    }

    // Countdown Timer for Flash Sale
    const timerContainer = document.getElementById('flash-sale-countdown');
    if (timerContainer) {
        const endTimeString = timerContainer.getAttribute('data-endtime');
        const endTime = new Date(endTimeString).getTime();

        const daysEl = document.getElementById('fs-days');
        const hoursEl = document.getElementById('fs-hours');
        const minsEl = document.getElementById('fs-mins');
        const secsEl = document.getElementById('fs-secs');

        function updateCountdown() {
            const now = new Date().getTime();
            const difference = endTime - now;

            if (difference <= 0) {
                clearInterval(countdownInterval);
                if (daysEl) daysEl.textContent = '00';
                if (hoursEl) hoursEl.textContent = '00';
                if (minsEl) minsEl.textContent = '00';
                if (secsEl) secsEl.textContent = '00';
                return;
            }

            const days = Math.floor(difference / (1000 * 60 * 60 * 24));
            const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((difference % (1000 * 60)) / 1000);

            if (daysEl) daysEl.textContent = days.toString().padStart(2, '0');
            if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
            if (minsEl) minsEl.textContent = minutes.toString().padStart(2, '0');
            if (secsEl) secsEl.textContent = seconds.toString().padStart(2, '0');
        }

        updateCountdown();
        const countdownInterval = setInterval(updateCountdown, 1000);
    }

    // Flash Sale Horizontal Scrolling
    const fsCarousel = document.getElementById('fs-carousel');
    const fsPrevBtn = document.getElementById('fs-prev');
    const fsNextBtn = document.getElementById('fs-next');

    if (fsCarousel) {
        const scrollStep = 300; // scroll by card width + gap
        let fsAutoScrollInterval;

        if (fsPrevBtn && fsNextBtn) {
            fsPrevBtn.addEventListener('click', () => {
                fsCarousel.scrollBy({ left: -scrollStep, behavior: 'smooth' });
                resetFSAutoScroll();
            });

            fsNextBtn.addEventListener('click', () => {
                fsCarousel.scrollBy({ left: scrollStep, behavior: 'smooth' });
                resetFSAutoScroll();
            });
        }

        function startFSAutoScroll() {
            fsAutoScrollInterval = setInterval(() => {
                const maxScrollLeft = fsCarousel.scrollWidth - fsCarousel.clientWidth;
                if (fsCarousel.scrollLeft >= maxScrollLeft - 5) {
                    fsCarousel.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    fsCarousel.scrollBy({ left: scrollStep, behavior: 'smooth' });
                }
            }, 4000);
        }

        function resetFSAutoScroll() {
            clearInterval(fsAutoScrollInterval);
            startFSAutoScroll();
        }

        fsCarousel.addEventListener('mouseenter', () => {
            clearInterval(fsAutoScrollInterval);
        });

        fsCarousel.addEventListener('mouseleave', () => {
            fsAutoScrollInterval = setInterval(() => {
                const maxScrollLeft = fsCarousel.scrollWidth - fsCarousel.clientWidth;
                if (fsCarousel.scrollLeft >= maxScrollLeft - 5) {
                    fsCarousel.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    fsCarousel.scrollBy({ left: scrollStep, behavior: 'smooth' });
                }
            }, 4000);
        });

        startFSAutoScroll();
    }

    // Dynamic Filter Game (synchronized from header search)
    const hiddenSearchInput = document.getElementById('search-input');
    const gameGrid = document.getElementById('game-grid');
    const gameCards = document.querySelectorAll('.game-card');
    const noGameAlert = document.getElementById('no-game-alert');

    if (hiddenSearchInput) {
        hiddenSearchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let visibleCount = 0;

            gameCards.forEach(card => {
                const gameName = card.getAttribute('data-name');
                if (gameName.includes(query)) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                gameGrid.style.display = 'none';
                noGameAlert.style.display = 'block';
            } else {
                gameGrid.style.display = 'grid';
                noGameAlert.style.display = 'none';
            }
        });
    }
</script>

<?php require_once 'includes/footer.php'; ?>
