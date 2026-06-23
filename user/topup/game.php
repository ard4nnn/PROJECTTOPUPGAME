<?php
require_once '../../config/db.php';
require_once '../../includes/header.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$error = '';
$game = null;
$produk_list = [];
$metode_list = [];

$mock_games = [
    'mobile-legends' => [
        'id' => 1,
        'nama_game' => 'Mobile Legends',
        'deskripsi' => 'Top up Diamond Mobile Legends termurah dan tercepat hanya dalam hitungan detik.',
        'produk' => [
            ['id' => 1, 'nama_produk' => '86 Diamonds', 'harga' => 20000],
            ['id' => 2, 'nama_produk' => '172 Diamonds', 'harga' => 40000],
            ['id' => 3, 'nama_produk' => '257 Diamonds', 'harga' => 60000],
            ['id' => 4, 'nama_produk' => '706 Diamonds', 'harga' => 150000]
        ]
    ],
    'free-fire' => [
        'id' => 2,
        'nama_game' => 'Free Fire',
        'deskripsi' => 'Top up Diamond Free Fire untuk membeli elite pass dan bundle favoritmu.',
        'produk' => [
            ['id' => 5, 'nama_produk' => '70 Diamonds', 'harga' => 10000],
            ['id' => 6, 'nama_produk' => '140 Diamonds', 'harga' => 20000],
            ['id' => 7, 'nama_produk' => '355 Diamonds', 'harga' => 50000]
        ]
    ],
    'pubg-mobile' => [
        'id' => 3,
        'nama_game' => 'PUBG Mobile',
        'deskripsi' => 'Top up UC PUBG Mobile termurah untuk skin keren and Royale Pass.',
        'produk' => [
            ['id' => 8, 'nama_produk' => '60 UC', 'harga' => 15000],
            ['id' => 9, 'nama_produk' => '325 UC', 'harga' => 75000]
        ]
    ],
    'genshin-impact' => [
        'id' => 4,
        'nama_game' => 'Genshin Impact',
        'deskripsi' => 'Top up Genesis Crystals Genshin Impact untuk gacha karakter impianmu.',
        'produk' => [
            ['id' => 10, 'nama_produk' => '60 Genesis Crystals', 'harga' => 16000],
            ['id' => 11, 'nama_produk' => '300 Genesis Crystals', 'harga' => 79000]
        ]
    ]
];

$mock_payments = [
    ['id' => 1, 'nama' => 'DANA', 'kode' => 'DANA'],
    ['id' => 2, 'nama' => 'GoPay', 'kode' => 'GOPAY'],
    ['id' => 3, 'nama' => 'OVO', 'kode' => 'OVO'],
    ['id' => 4, 'nama' => 'Transfer Bank BCA', 'kode' => 'BCA']
];

if (empty($slug)) {
    $slug = 'mobile-legends';
}

if ($db_connected && $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM games WHERE slug = ? AND status = 'aktif'");
        $stmt->execute([$slug]);
        $game = $stmt->fetch();

        if ($game) {
            $stmtProd = $pdo->prepare("SELECT * FROM produk WHERE game_id = ? AND status = 'aktif' ORDER BY harga ASC");
            $stmtProd->execute([$game['id']]);
            $produk_list = $stmtProd->fetchAll();

            $stmtMetode = $pdo->query("SELECT * FROM metode_bayar WHERE status = 'aktif' ORDER BY nama ASC");
            $metode_list = $stmtMetode->fetchAll();
        }
    } catch (PDOException $e) {
        $db_connected = false;
    }
}

if (!$db_connected || !$game) {
    if (array_key_exists($slug, $mock_games)) {
        $game = [
            'id' => $mock_games[$slug]['id'],
            'nama_game' => $mock_games[$slug]['nama_game'],
            'slug' => $slug,
            'deskripsi' => $mock_games[$slug]['deskripsi']
        ];
        $produk_list = $mock_games[$slug]['produk'];
        $metode_list = $mock_payments;
    } else {
        $error = 'Game tidak ditemukan!';
    }
}
?>

<div class="container topup-container">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <a href="../../index.php" class="btn btn-primary">&larr; <?php echo __('kembali'); ?></a>
    <?php else: ?>
        
        <div class="topup-back-btn-container">
            <a href="../../index.php" class="topup-back-btn">
                &larr; <?php echo __('kembali'); ?>
            </a>
        </div>

        <div class="topup-layout-grid">
            
            <!-- Left inputs panel -->
            <div class="topup-left-col">
                
                <!-- Game Info details -->
                <div class="card game-info-card">
                    <div class="game-category-badge">
                        GAME VOUCHER
                    </div>
                    <h2 class="game-info-title"><?php echo htmlspecialchars($game['nama_game']); ?></h2>
                    <p class="game-info-desc">
                        <?php echo htmlspecialchars($game['deskripsi'] ? $game['deskripsi'] : 'Top up instan termurah.'); ?>
                    </p>
                </div>

                <!-- Step 1: Input Account ID -->
                <div class="card">
                    <h3 class="topup-step-title">
                        <span class="topup-step-number">1</span>
                        <?php echo $current_lang === 'id' ? 'Lengkapi Data Akun' : 'Enter Account Details'; ?>
                    </h3>
                    <div class="topup-form-group">
                        <label for="id_game_user" class="topup-label"><?php echo __('target_id'); ?></label>
                        <input type="text" id="id_game_user" placeholder="<?php echo $current_lang === 'id' ? 'Masukkan ID Game Anda (contoh: 1284759)' : 'Enter Game ID (e.g. 1284759)'; ?>" class="topup-input">
                        <small class="topup-hint">
                            <?php echo $current_lang === 'id' 
                                ? 'Masukkan ID Game Anda dengan benar. Kami tidak bertanggung jawab atas kesalahan input ID.' 
                                : 'Ensure your Game ID is correct. We are not responsible for incorrect inputs.'; ?>
                        </small>
                    </div>
                </div>

                <!-- Step 3: Choose Payment Method -->
                <div class="card">
                    <h3 class="topup-step-title">
                        <span class="topup-step-number">3</span>
                        <?php echo __('metode'); ?>
                    </h3>
                    <div class="payment-options-list" id="payment-options">
                        <?php foreach ($metode_list as $metode): ?>
                            <div class="payment-card" data-id="<?php echo $metode['id']; ?>" data-name="<?php echo htmlspecialchars($metode['nama']); ?>">
                                <div class="payment-card-left">
                                    <div class="radio-indicator payment-radio">
                                        <div class="payment-radio-dot"></div>
                                    </div>
                                    <strong class="payment-name"><?php echo htmlspecialchars($metode['nama']); ?></strong>
                                </div>
                                <span class="badge payment-badge"><?php echo htmlspecialchars($metode['kode']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <!-- Right products panel -->
            <div class="topup-right-col">
                
                <!-- Step 2: Choose Product -->
                <div class="card">
                    <h3 class="topup-step-title">
                        <span class="topup-step-number">2</span>
                        <?php echo $current_lang === 'id' ? 'Pilih Nominal Pembelian' : 'Select Top Up Amount'; ?>
                    </h3>
                    <div class="product-options-grid" id="product-options">
                        <?php foreach ($produk_list as $prod): ?>
                            <div class="product-card" data-id="<?php echo $prod['id']; ?>" data-name="<?php echo htmlspecialchars($prod['nama_produk']); ?>" data-price="<?php echo $prod['harga']; ?>">
                                <span class="product-name"><?php echo htmlspecialchars($prod['nama_produk']); ?></span>
                                <span class="product-price">Rp <?php echo number_format($prod['harga'], 0, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Step 4: Verification receipt -->
                <div class="card receipt-summary-card">
                    <h3 class="receipt-summary-title">
                        <?php echo $current_lang === 'id' ? '4. Verifikasi Pembelian' : '4. Verification'; ?>
                    </h3>
                    <p class="receipt-summary-desc">
                        <?php echo $current_lang === 'id' 
                            ? 'Lengkapi ID Game, nominal top up, dan metode pembayaran di sebelah kiri untuk melihat ringkasan pembayaran.' 
                            : 'Fill your Game ID, top-up nominal, and payment method on the left to see the payment summary.'; ?>
                    </p>
                    
                    <div class="receipt-summary-details">
                        <div class="receipt-summary-row">
                            <span class="receipt-summary-label"><?php echo __('game'); ?>:</span>
                            <strong id="summary-game" class="receipt-summary-value"><?php echo htmlspecialchars($game['nama_game']); ?></strong>
                        </div>
                        <div class="receipt-summary-row">
                            <span class="receipt-summary-label"><?php echo __('target_id'); ?>:</span>
                            <strong id="summary-id" class="receipt-summary-value">-</strong>
                        </div>
                        <div class="receipt-summary-row">
                            <span class="receipt-summary-label"><?php echo __('produk'); ?>:</span>
                            <strong id="summary-product" class="receipt-summary-value">-</strong>
                        </div>
                        <div class="receipt-summary-row">
                            <span class="receipt-summary-label"><?php echo __('metode'); ?>:</span>
                            <strong id="summary-payment" class="receipt-summary-value">-</strong>
                        </div>
                        <div class="receipt-summary-total-row">
                            <strong class="receipt-summary-total-label"><?php echo __('total_tagihan'); ?>:</strong>
                            <strong id="summary-total" class="receipt-summary-total-value">Rp 0</strong>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-calc-topup-zodiac" id="btn-submit" disabled>
                        <?php echo $current_lang === 'id' ? 'Konfirmasi & Beli Sekarang' : 'Confirm & Buy Now'; ?>
                    </button>
                </div>

            </div>

        </div>

    <?php endif; ?>
</div>

<!-- Checkout Successful Modal -->
<div id="checkout-modal" class="checkout-modal-overlay">
    <div class="checkout-modal-body">
        <span class="checkout-modal-icon">🎉</span>
        <h3 class="checkout-modal-title">
            <?php echo $current_lang === 'id' ? 'Pemesanan Berhasil Dikirim!' : 'Order Placed Successfully!'; ?>
        </h3>
        <p class="checkout-modal-subtitle">
            <?php echo $current_lang === 'id' 
                ? 'Pesanan top up Anda berhasil dibuat dengan status <strong class="badge badge-pending">PENDING</strong>.' 
                : 'Your top-up order has been successfully created with <strong class="badge badge-pending">PENDING</strong> status.'; ?>
        </p>

        <div class="checkout-modal-receipt">
            <div class="checkout-modal-receipt-header">
                <?php echo __('tx_detail'); ?>:
            </div>
            <div class="checkout-modal-receipt-row"><span><?php echo __('game'); ?>:</span><span id="modal-game" style="font-weight:600;">-</span></div>
            <div class="checkout-modal-receipt-row"><span><?php echo __('target_id'); ?>:</span><span id="modal-id" style="font-weight:600;">-</span></div>
            <div class="checkout-modal-receipt-row"><span><?php echo __('produk'); ?>:</span><span id="modal-product" style="font-weight:600;">-</span></div>
            <div class="checkout-modal-receipt-row"><span><?php echo __('metode'); ?>:</span><span id="modal-payment" style="font-weight:600;">-</span></div>
            <div class="checkout-modal-receipt-total-row">
                <span><?php echo __('total_tagihan'); ?>:</span><span id="modal-total" style="color: var(--primary-color);">-</span>
            </div>
        </div>

        <div class="checkout-modal-actions">
            <button class="btn btn-outline" id="modal-close" style="flex: 1;"><?php echo $current_lang === 'id' ? 'Tutup' : 'Close'; ?></button>
            <a href="../riwayat.php" class="btn btn-primary" style="flex: 1;"><?php echo __('riwayat'); ?></a>
        </div>
    </div>
</div>

<script src="<?php echo $base_url; ?>assets/js/topup.js"></script>

<?php require_once '../../includes/footer.php'; ?>
