<?php
require_once '../../config/db.php';
require_once '../../includes/header.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$error = '';
$game = null;
$produk_list = [];
$metode_list = [];

$gamelist_data = require '../../data/gamelist.php';
$mock_games = $gamelist_data['mock_games'];
$mock_payments = $gamelist_data['mock_payments'];

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
            
            <div class="topup-left-col">
                
                <?php
                $cover_mapping = [
                    'mobile-legends' => 'MLBB.png',
                    'free-fire' => 'FREEFIRE.png',
                    'pubg-mobile' => 'PUBG.png',
                    'genshin-impact' => 'Genshin Impact.jpg',
                ];
                $dev_mapping = [
                    'mobile-legends' => 'Moonton',
                    'free-fire' => 'Garena',
                    'pubg-mobile' => 'Tencent Games',
                    'genshin-impact' => 'miHoYo',
                ];

                $game_slug = $game['slug'] ?? ($slug ?? 'default');
                $cover_file = isset($cover_mapping[$game_slug]) ? $cover_mapping[$game_slug] : 'MLBB.png';
                $cover_path = $base_url . "assets/images/" . $cover_file;
                $fallback = $base_url . "assets/images/MLBB.png";

                $game_name = $game['nama_game'] ?? 'Game';
                $game_dev = $game['developer'] ?? ($dev_mapping[$game_slug] ?? 'Game Publisher');
                $game_desc = $game['deskripsi'] ?? 'Top up instan termurah.';
                ?>

                <style>
                /* === FUNtopup Game Cover Section === */
                .funtopup-game-cover {
                  display: flex;
                  align-items: flex-start;
                  gap: 1rem;
                  padding: 1.25rem;
                }

                .funtopup-cover-img-wrap {
                  position: relative;
                  flex-shrink: 0;
                  width: 130px;
                  height: 158px;
                  border-radius: 10px;
                  overflow: hidden;
                  border: 2px solid rgba(251, 191, 36, 0.28);
                  box-shadow:
                    0 0 20px rgba(251, 191, 36, 0.10),
                    0 4px 16px rgba(0, 0, 0, 0.55);
                }

                .funtopup-cover-img-wrap img {
                  width: 100%;
                  height: 100%;
                  object-fit: cover;
                  display: block;
                  transition: transform 0.3s ease;
                }

                .funtopup-cover-img-wrap:hover img {
                  transform: scale(1.04);
                }

                /* Badge negara di atas foto */
                .funtopup-cover-badge {
                  position: absolute;
                  bottom: 8px;
                  left: 50%;
                  transform: translateX(-50%);
                  background: rgba(0, 0, 0, 0.78);
                  backdrop-filter: blur(5px);
                  -webkit-backdrop-filter: blur(5px);
                  border: 1px solid rgba(255, 255, 255, 0.12);
                  border-radius: 5px;
                  padding: 3px 8px;
                  font-size: 9.5px;
                  font-weight: 800;
                  color: #fff;
                  white-space: nowrap;
                  letter-spacing: 0.06em;
                  display: flex;
                  align-items: center;
                  gap: 4px;
                }

                /* Panel info game (kanan foto) */
                .funtopup-cover-info {
                  flex: 1;
                  display: flex;
                  flex-direction: column;
                  gap: 0.25rem;
                  padding-top: 2px;
                }

                /* Badge "GAME VOUCHER" kuning */
                .funtopup-voucher-badge {
                  display: inline-block;
                  background: #FBBF24;
                  color: #1a1000;
                  font-size: 9.5px;
                  font-weight: 900;
                  padding: 2px 8px;
                  border-radius: 4px;
                  letter-spacing: 0.12em;
                  width: fit-content;
                  margin-bottom: 2px;
                }

                /* Nama game */
                .funtopup-cover-title {
                  font-size: 1.35rem;
                  font-weight: 900;
                  color: #ffffff;
                  line-height: 1.1;
                  margin-top: 4px;
                }

                /* Developer/Publisher */
                .funtopup-cover-dev {
                  font-size: 0.72rem;
                  color: #777;
                  letter-spacing: 0.02em;
                  margin-top: 1px;
                }

                /* Daftar fitur (Proses Cepat, dll) */
                .funtopup-cover-features {
                  list-style: none;
                  padding: 0;
                  display: flex;
                  flex-direction: column;
                  gap: 4px;
                  margin-top: 8px;
                }

                .funtopup-cover-features li {
                  font-size: 0.75rem;
                  color: #aaa;
                  display: flex;
                  align-items: center;
                  gap: 6px;
                }

                .funtopup-cover-features li .fi {
                  font-size: 12px;
                  flex-shrink: 0;
                }

                /* Deskripsi singkat */
                .funtopup-cover-desc {
                  font-size: 0.77rem;
                  color: #666;
                  margin-top: 8px;
                  line-height: 1.45;
                }

                /* Responsive: layar kecil / mobile */
                @media (max-width: 480px) {
                  .funtopup-game-cover {
                    gap: 0.75rem;
                    padding: 0.75rem;
                  }
                  .funtopup-cover-img-wrap {
                    width: 100px;
                    height: 120px;
                  }
                  .funtopup-cover-title {
                    font-size: 1.1rem;
                  }
                }
                </style>

                <div class="card game-info-card" style="padding: 0; overflow: hidden;">
                  <div class="funtopup-game-cover">
                    <!-- Foto cover game -->
                    <div class="funtopup-cover-img-wrap">
                      <img
                        src="<?php echo htmlspecialchars($cover_path); ?>"
                        alt="<?php echo htmlspecialchars($game_name); ?>"
                        onerror="this.onerror=null; this.src='<?php echo htmlspecialchars($fallback); ?>'"
                      >
                    </div>

                    <!-- Info game -->
                    <div class="funtopup-cover-info">
                      <h1 class="funtopup-cover-title"><?php echo htmlspecialchars($game_name); ?></h1>
                      <p class="funtopup-cover-dev"><?php echo htmlspecialchars($game_dev); ?></p>
                      <ul class="funtopup-cover-features">
                        <li><span class="fi">⚡</span> Proses Cepat</li>
                        <li><span class="fi">✅</span> Aman &amp; Terpercaya</li>
                        <li><span class="fi">🕐</span> 24 Jam Non-Stop</li>
                      </ul>
                      <p class="funtopup-cover-desc">
                        <?php echo htmlspecialchars($game_desc); ?>
                      </p>
                    </div>
                  </div>
                </div>

                <?php
                $is_ml = ($game_slug === 'mobile-legends');
                $is_genshin = ($game_slug === 'genshin-impact');
                $needs_server = ($is_ml || $is_genshin);

                $id_label = $is_genshin ? 'UID' : 'ID';
                
                if ($current_lang === 'id') {
                    $id_placeholder = $is_genshin ? 'Masukkan UID' : 'Masukkan ID';
                    $server_placeholder = 'Masukkan Server';
                    $hint_text = $is_genshin 
                        ? 'Masukkan UID Game Anda dengan benar. Kami tidak bertanggung jawab atas kesalahan input UID.' 
                        : 'Masukkan ID Game Anda dengan benar. Kami tidak bertanggung jawab atas kesalahan input ID.';
                } else {
                    $id_placeholder = $is_genshin ? 'Enter UID' : 'Enter ID';
                    $server_placeholder = 'Enter Server';
                    $hint_text = $is_genshin 
                        ? 'Ensure your Game UID is correct. We are not responsible for incorrect inputs.' 
                        : 'Ensure your Game ID is correct. We are not responsible for incorrect inputs.';
                }
                ?>

                <style>
                /* Account Input Grid */
                .funtopup-account-grid {
                  display: grid;
                  grid-template-columns: 1fr;
                  gap: 16px;
                }
                @media (min-width: 480px) {
                  .funtopup-account-grid.has-server {
                    grid-template-columns: 1fr 1fr;
                  }
                }
                .funtopup-input-wrapper {
                  display: flex;
                  flex-direction: column;
                  gap: 6px;
                  position: relative;
                }
                .funtopup-label-with-icon {
                  display: flex;
                  align-items: center;
                  gap: 4px;
                }
                .funtopup-info-icon {
                  display: inline-flex;
                  align-items: center;
                  justify-content: center;
                  width: 14px;
                  height: 14px;
                  border: 1px solid var(--text-muted);
                  color: var(--text-muted);
                  border-radius: 50%;
                  font-size: 9px;
                  font-weight: bold;
                  cursor: help;
                  user-select: none;
                }
                </style>

                <div class="card">
                    <h3 class="topup-step-title">
                        <span class="topup-step-number">1</span>
                        <?php echo $current_lang === 'id' ? 'Masukkan Data Akun' : 'Enter Account Details'; ?>
                    </h3>
                    
                    <div class="topup-form-group">
                        <div class="funtopup-account-grid <?php echo $needs_server ? 'has-server' : ''; ?>">
                            <!-- Visible ID/UID Field -->
                            <div class="funtopup-input-wrapper">
                                <label for="visible_id_game_user" class="topup-label funtopup-label-with-icon">
                                    <?php echo $id_label; ?>
                                    <span class="funtopup-info-icon" title="Masukkan ID/UID akun game Anda">i</span>
                                </label>
                                <input type="text" id="visible_id_game_user" placeholder="<?php echo $id_placeholder; ?>" class="topup-input">
                            </div>

                            <?php if ($needs_server): ?>
                                <!-- Visible Server Field -->
                                <div class="funtopup-input-wrapper">
                                    <label for="server_game_user" class="topup-label">Server</label>
                                    <input type="text" id="server_game_user" placeholder="<?php echo $server_placeholder; ?>" class="topup-input">
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Proxy Hidden input for existing JS integration -->
                        <input type="hidden" id="id_game_user" name="id_game_user">

                        <small class="topup-hint" style="margin-top: 10px; display: block;">
                            <?php echo $hint_text; ?>
                        </small>
                    </div>
                </div>

                <script>
                (function() {
                    const hiddenInput = document.getElementById('id_game_user');
                    const visibleIdInput = document.getElementById('visible_id_game_user');
                    const serverInput = document.getElementById('server_game_user');

                    if (hiddenInput && visibleIdInput) {
                        function syncInput() {
                            const idVal = visibleIdInput.value.trim();
                            const serverVal = serverInput ? serverInput.value.trim() : '';
                            
                            if (serverVal) {
                                hiddenInput.value = idVal + " (" + serverVal + ")";
                            } else {
                                hiddenInput.value = idVal;
                            }
                            
                            // Trigger validation event
                            hiddenInput.dispatchEvent(new Event('input'));
                        }

                        visibleIdInput.addEventListener('input', syncInput);
                        if (serverInput) {
                            serverInput.addEventListener('input', syncInput);
                        }
                    }
                })();
                </script>

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

            <div class="topup-right-col">
                
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
