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
                    'wuthering-waves' => 'Wuthering Waves.jpg',
                ];
                $dev_mapping = [
                    'mobile-legends' => 'Moonton',
                    'free-fire' => 'Garena',
                    'pubg-mobile' => 'Tencent Games',
                    'wuthering-waves' => 'Kuro Games',
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

                /* Custom Product Cards Style */
                .product-options-grid {
                  display: grid;
                  grid-template-columns: repeat(3, 1fr);
                  gap: 12px;
                }

                @media (max-width: 900px) {
                  .product-options-grid {
                    grid-template-columns: repeat(2, 1fr);
                  }
                }

                @media (max-width: 480px) {
                  .product-options-grid {
                    grid-template-columns: 1fr;
                  }
                }

                .product-card {
                  background-color: #1e2329 !important;
                  border: 1.5px solid #2b3139 !important;
                  border-radius: 8px !important;
                  padding: 14px 12px !important;
                  cursor: pointer;
                  display: flex !important;
                  flex-direction: column !important;
                  align-items: flex-start !important;
                  justify-content: space-between !important;
                  min-height: 96px !important;
                  position: relative !important;
                  transition: all 0.2s ease !important;
                  text-align: left !important;
                }

                .product-card .card-left-info {
                  display: flex;
                  flex-direction: column;
                  gap: 8px;
                  width: 100%;
                }

                .product-card .product-name {
                  font-size: 13px !important;
                  font-weight: 700 !important;
                  color: #fff !important;
                  text-align: left !important;
                  margin: 0 !important;
                  white-space: nowrap;
                  overflow: hidden;
                  text-overflow: ellipsis;
                }

                .product-card .price-row {
                  display: flex;
                  align-items: center;
                  gap: 6px;
                }

                .product-card .product-card-img {
                  width: 18px;
                  height: 18px;
                  object-fit: contain;
                  flex-shrink: 0;
                }

                /* Larger icon for PUBG UC & Elite Pass */
                .product-card .product-card-img.pubg-img {
                  width: 40px;
                  height: 40px;
                  object-fit: contain;
                  flex-shrink: 0;
                  border-radius: 6px;
                }

                .product-card .product-price {
                  font-size: 13px !important;
                  font-weight: 700 !important;
                  color: #d97706 !important; /* brown/gold price as requested */
                }

                /* Instan badge at bottom right */
                .product-card .instan-badge {
                  position: absolute;
                  bottom: 8px;
                  right: 8px;
                  background-color: #ffffff;
                  color: #166534; /* dark green text */
                  border: 1px solid #e5e7eb;
                  border-radius: 4px;
                  padding: 2px 6px;
                  display: flex;
                  align-items: center;
                  gap: 3px;
                  font-size: 8px;
                  font-weight: 800;
                  pointer-events: none;
                  box-shadow: 0 1px 2px rgba(0,0,0,0.15);
                }

                .product-card .instan-icon {
                  color: #10b981; /* Green lightning */
                  fill: currentColor;
                  flex-shrink: 0;
                }

                .product-card:hover {
                  border-color: #FBBF24 !important;
                  transform: translateY(-2px);
                  box-shadow: 0 4px 12px rgba(251, 191, 36, 0.15);
                }

                .product-card.selected {
                  border-color: #FBBF24 !important;
                  background-color: rgba(252, 213, 53, 0.04) !important;
                  box-shadow: 0 0 15px rgba(251, 191, 36, 0.2);
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
                $is_wuthering = ($game_slug === 'wuthering-waves');
                $needs_server = ($is_ml || $is_wuthering);

                $id_label = 'ID';
                
                if ($current_lang === 'id') {
                    $id_placeholder = 'Masukkan ID';
                    $server_placeholder = 'Pilih Server';
                    $hint_text = 'Masukkan ID Game Anda dengan benar. Kami tidak bertanggung jawab atas kesalahan input ID.';
                } else {
                    $id_placeholder = 'Enter ID';
                    $server_placeholder = 'Select Server';
                    $hint_text = 'Ensure your Game ID is correct. We are not responsible for incorrect inputs.';
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
                            <!-- Visible ID Field -->
                            <div class="funtopup-input-wrapper">
                                <label for="visible_id_game_user" class="topup-label funtopup-label-with-icon">
                                    <?php echo $id_label; ?>
                                    <span class="funtopup-info-icon" title="Masukkan ID/UID akun game Anda">i</span>
                                </label>
                                <input type="text" id="visible_id_game_user" placeholder="<?php echo $id_placeholder; ?>" class="topup-input">
                            </div>

                            <?php if ($needs_server): ?>
                                <!-- Server Field -->
                                <div class="funtopup-input-wrapper">
                                    <label for="server_game_user" class="topup-label">Server</label>
                                    <?php if ($is_wuthering): ?>
                                        <!-- Dropdown server khusus Wuthering Waves -->
                                        <select id="server_game_user" class="topup-input" style="appearance:none; -webkit-appearance:none; cursor:pointer; background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='%23aaa'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E\"); background-repeat:no-repeat; background-position:right 12px center; padding-right:32px;">
                                            <option value="" disabled selected><?php echo $server_placeholder; ?></option>
                                            <option value="America">America</option>
                                            <option value="Asia">Asia</option>
                                            <option value="Europe">Europe</option>
                                            <option value="TW_HK_MO">TW_HK_MO</option>
                                            <option value="SEA">SEA</option>
                                        </select>
                                    <?php else: ?>
                                        <input type="text" id="server_game_user" placeholder="<?php echo $server_placeholder; ?>" class="topup-input">
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Proxy Hidden input for existing JS integration -->
                        <input type="hidden" id="id_game_user" name="id_game_user">

                        <small class="topup-hint" style="margin-top: 10px; display: block;">
                            <?php echo $hint_text; ?>
                        </small>
                        <?php if ($is_wuthering): ?>
                        <small style="display:block; margin-top:6px; color:#FBBF24; font-size:0.75rem;">
                            Please make sure you fill the correct account data
                        </small>
                        <?php endif; ?>
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
                            serverInput.addEventListener('change', syncInput);
                        }
                    }
                })();
                </script>

                <div class="card">
                    <h3 class="topup-step-title">
                        <span class="topup-step-number">4</span>
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
                    
                    <div id="product-options">
                        <?php
                        $memberships = [];
                        $diamonds = [];
                        foreach ($produk_list as $prod) {
                            $name_lower = strtolower($prod['nama_produk']);
                            // Filter out BP Card if it is present
                            if (strpos($name_lower, 'bp card') !== false) {
                                continue;
                            }
                            if (strpos($name_lower, 'member') !== false || strpos($name_lower, 'membership') !== false || strpos($name_lower, 'pass') !== false || strpos($name_lower, 'elite') !== false || strpos($name_lower, 'subscription') !== false) {
                                $memberships[] = $prod;
                            } else {
                                $diamonds[] = $prod;
                            }
                        }
                        
                        $game_slug = $game['slug'] ?? '';
                        $is_diamond_game = ($game_slug === 'free-fire' || $game_slug === 'mobile-legends');
                        $is_pubg = ($game_slug === 'pubg-mobile');
                        $is_ww = ($game_slug === 'wuthering-waves');
                        $membership_section_label = $is_pubg ? 'Elite Pass' : ($is_ww ? 'Lunite Subscription' : 'Membership');
                        $currency_section_label = $is_pubg ? 'UC' : ($is_diamond_game ? 'Diamonds' : ($is_ww ? 'Lunites' : 'Nominal'));
                        ?>

                        <!-- Membership/Elite Pass Section -->
                        <?php if (!empty($memberships)): ?>
                            <h4 class="topup-subsection-title" style="margin-top: 15px; margin-bottom: 12px; font-size: 15px; font-weight: 700; color: #fff; text-align: left;"><?php echo $membership_section_label; ?></h4>
                            <div class="product-options-grid membership-grid" style="margin-bottom: 24px;">
                                <?php foreach ($memberships as $prod): ?>
                                    <?php
                                    $img_name = 'ffmember.png';
                                    if ($game_slug === 'mobile-legends') {
                                        if (strpos(strtolower($prod['nama_produk']), 'weekly') !== false) {
                                            $img_name = 'WDP ML.png';
                                        } elseif (strpos(strtolower($prod['nama_produk']), 'twilight') !== false) {
                                            $img_name = 'TWILIGHT PASS ML.jpg';
                                        }
                                    } elseif ($game_slug === 'pubg-mobile') {
                                        $img_name = 'PUBG_ELITE_PASS.png';
                                    } elseif ($game_slug === 'wuthering-waves') {
                                        $img_name = 'LUNITE SUBSCRIPTION WUTHERING WAVES.png';
                                    } else {
                                        if (strpos(strtolower($prod['nama_produk']), 'bulanan') !== false) {
                                            $img_name = 'EPEPMMEBER.png';
                                        }
                                    }
                                    $img_path = $base_url . "assets/images/" . $img_name;
                                    ?>
                                    <div class="product-card membership-card" data-id="<?php echo $prod['id']; ?>" data-name="<?php echo htmlspecialchars($prod['nama_produk']); ?>" data-price="<?php echo $prod['harga']; ?>">
                                        <div class="card-left-info">
                                            <span class="product-name"><?php echo htmlspecialchars($prod['nama_produk']); ?></span>
                                            <div class="price-row">
                                                <img src="<?php echo $img_path; ?>" class="product-card-img<?php echo ($game_slug === 'pubg-mobile') ? ' pubg-img' : ''; ?>" alt="membership">
                                                <span class="product-price">Rp <?php echo number_format($prod['harga'], 0, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                        <div class="instan-badge">
                                            <svg class="instan-icon" viewBox="0 0 24 24" width="10" height="10" fill="currentColor">
                                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                                            </svg>
                                            <span>Pengiriman INSTAN</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Diamonds/UC Section -->
                        <?php if (!empty($diamonds)): ?>
                            <?php if (!empty($memberships)): ?>
                                <h4 class="topup-subsection-title" style="margin-top: 15px; margin-bottom: 12px; font-size: 15px; font-weight: 700; color: #fff; text-align: left;"><?php echo $currency_section_label; ?></h4>
                            <?php endif; ?>
                            <div class="product-options-grid diamonds-grid">
                                <?php foreach ($diamonds as $prod): ?>
                                    <?php
                                    $img_path = '';
                                    if ($is_pubg) {
                                        $img_path = $base_url . "assets/images/UC PUBG.png";
                                    } elseif ($is_ww) {
                                        $img_path = $base_url . "assets/images/LUNITE_WUTHERING_WAVES-removebg-preview.png";
                                    } elseif ($is_diamond_game) {
                                        $img_path = $base_url . "assets/images/diamondmlbb.png";
                                    }
                                    ?>
                                    <div class="product-card diamond-card" data-id="<?php echo $prod['id']; ?>" data-name="<?php echo htmlspecialchars($prod['nama_produk']); ?>" data-price="<?php echo $prod['harga']; ?>">
                                        <div class="card-left-info">
                                            <span class="product-name"><?php echo htmlspecialchars($prod['nama_produk']); ?></span>
                                            <div class="price-row">
                                                <?php if (!empty($img_path)): ?>
                                                    <img src="<?php echo $img_path; ?>" class="product-card-img<?php echo $is_pubg ? ' pubg-img' : ''; ?>" alt="diamond">
                                                <?php endif; ?>
                                                <span class="product-price">Rp <?php echo number_format($prod['harga'], 0, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                        <div class="instan-badge">
                                            <svg class="instan-icon" viewBox="0 0 24 24" width="10" height="10" fill="currentColor">
                                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                                            </svg>
                                            <span>Pengiriman INSTAN</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quantity Input Section -->
                <div class="card" id="quantity-card">
                    <h3 class="topup-step-title">
                        <span class="topup-step-number">3</span>
                        <?php echo $current_lang === 'id' ? 'Masukkan Jumlah Pembelian' : 'Enter Purchase Quantity'; ?>
                    </h3>
                    <div class="topup-form-group">
                        <style>
                        .quantity-control-wrapper {
                            display: flex;
                            align-items: center;
                            gap: 0;
                            background: var(--card-bg, #1e2329);
                            border: 1.5px solid var(--card-border, #2b3139);
                            border-radius: 8px;
                            overflow: hidden;
                        }
                        .quantity-control-input {
                            flex: 1;
                            background: transparent;
                            border: none;
                            outline: none;
                            color: #fff;
                            font-size: 15px;
                            font-weight: 600;
                            padding: 12px 16px;
                            min-width: 0;
                            text-align: left;
                        }
                        .quantity-control-input::-webkit-inner-spin-button,
                        .quantity-control-input::-webkit-outer-spin-button { -webkit-appearance: none; }
                        .quantity-control-btn {
                            flex-shrink: 0;
                            width: 44px;
                            height: 44px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background: #FBBF24;
                            color: #1a1000;
                            border: none;
                            cursor: pointer;
                            font-size: 20px;
                            font-weight: 700;
                            transition: background 0.2s ease;
                            line-height: 1;
                            user-select: none;
                        }
                        .quantity-control-btn:hover { background: #f59e0b; }
                        .quantity-control-btn:active { background: #d97706; }
                        .quantity-control-btn.btn-minus { border-left: 1.5px solid var(--card-border, #2b3139); }
                        </style>
                        <div class="quantity-control-wrapper">
                            <input
                                type="number"
                                id="qty-input"
                                class="quantity-control-input topup-input"
                                value="1"
                                min="1"
                                max="99"
                                style="border:none; border-radius:0;"
                            >
                            <button type="button" class="quantity-control-btn btn-plus" id="qty-plus">+</button>
                            <button type="button" class="quantity-control-btn btn-minus" id="qty-minus">&minus;</button>
                        </div>
                    </div>
                </div>

                <div class="card receipt-summary-card" id="verification-card">
                    <h3 class="receipt-summary-title">
                        <?php echo $current_lang === 'id' ? '5. Verifikasi Pembelian' : '5. Verification'; ?>
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
