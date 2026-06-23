<?php
require_once 'config/db.php';
require_once 'includes/header.php';

$games = [];

// Load data files
$gamelist_data = require 'data/gamelist.php';
$flashsale_data = require 'data/flashsale.php';

if ($db_connected && $pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM games WHERE status = 'aktif' ORDER BY nama_game ASC");
        $games = $stmt->fetchAll();
    } catch (PDOException $e) {
        $db_connected = false;
    }
}

if (!$db_connected) {
    $games = $gamelist_data['fallback_games'];
}

$flash_sale_end = $flashsale_data['end_time'];
$flash_sale_title = $flashsale_data['title'];
$flash_sale_subtitle = $flashsale_data['subtitle'];
$flash_sale_items = $flashsale_data['items'];
?>

<!-- Hidden Search input mapped from header search -->
<input type="hidden" id="search-input">

<!-- Promotional Slider -->
<div class="carousel-container">
    <div class="carousel-track" id="carousel-track">
        <!-- Slide 1: Rich HTML Promo tickets -->
        <div class="carousel-slide rich-slide">
            <div class="promo-content">
                <div class="promo-left">
                    <div class="promo-logo-container">
                        <svg viewBox="0 0 24 30" width="20" height="24" fill="#fcd535"><path d="M14 2 L2 15 L10 15 L7 28 L20 13 L12 13 Z"/></svg>
                        <span class="promo-logo-text">FUNtopup</span>
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
                <span class="badge badge-hot">HOT SELLING</span>
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
                        <div class="flash-sale-ribbon-wrapper">
                            <span class="flash-sale-badge-save">
                                <?php echo $current_lang === 'id' ? 'HEMAT Rp ' . number_format($savings, 0, ',', '.') : 'SAVE Rp ' . number_format($savings, 0, ',', '.'); ?>
                            </span>
                        </div>
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
    <div class="section-header">
        <div>
            <h2 class="section-title"><?php echo __('game_populer'); ?></h2>
            <p class="section-subtitle"><?php echo __('game_populer_desc'); ?></p>
        </div>
        
        <?php if (!$db_connected): ?>
            <span class="badge badge-demo-mode">
                🔌 <?php echo __('mode_demo'); ?>
            </span>
        <?php else: ?>
            <span class="badge badge-online-mode">
                🟢 Online Mode
            </span>
        <?php endif; ?>
    </div>

    <!-- Grid Game List -->
    <div id="game-grid" class="game-grid">
        <?php foreach ($games as $game): ?>
            <div class="game-card" data-name="<?php echo strtolower(htmlspecialchars($game['nama_game'])); ?>">
                <div>
                    <div class="card-banner">
                        <span class="card-banner-text"><?php echo htmlspecialchars($game['nama_game']); ?></span>
                        <div class="banner-fade"></div>
                    </div>

                    <div class="game-card-body">
                        <h3 class="game-card-title"><?php echo htmlspecialchars($game['nama_game']); ?></h3>
                        <p class="game-card-desc">
                            <?php echo htmlspecialchars($game['deskripsi'] ? $game['deskripsi'] : 'Top up instan voucher game ' . $game['nama_game'] . ' termurah dan aman.'); ?>
                        </p>
                    </div>
                </div>

                <div class="game-card-footer">
                    <a href="user/topup/game.php?slug=<?php echo $game['slug']; ?>" class="btn btn-primary btn-full-width">
                        <?php echo __('topup_now'); ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="no-game-alert" class="no-game-alert">
        <span class="no-game-alert-icon">🔍</span>
        <h3 class="no-game-alert-title"><?php echo __('game_not_found'); ?></h3>
        <p class="no-game-alert-desc"><?php echo __('game_not_found_desc'); ?></p>
    </div>
</div>

<!-- Toast element -->
<div class="toast" id="toast-notif">
    <span class="toast-icon">📋</span>
    <span id="toast-text">Kode promo disalin!</span>
</div>

<script src="<?php echo $base_url; ?>assets/js/slider.js"></script>
<script src="<?php echo $base_url; ?>assets/js/countdown.js"></script>

<?php require_once 'includes/footer.php'; ?>
