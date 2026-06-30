<?php
require_once 'config/db.php';
require_once 'includes/header.php';
?>

<div class="container">
    <div class="calc-container-grid">
        
        <div class="calc-sidebar">
            <div class="calc-menu-card active" id="menu-wr" onclick="switchCalcPanel('wr')">
                <div class="calc-menu-icon">📈</div>
                <div class="calc-menu-info">
                    <strong>Win Rate</strong>
                    <p><?php echo $current_lang === 'id' ? 'Hitung total match yang ditempuh untuk mencapai target win rate.' : 'Calculate total matches needed to reach your target win rate.'; ?></p>
                </div>
            </div>
            
            <div class="calc-menu-card" id="menu-mw" onclick="switchCalcPanel('mw')">
                <div class="calc-menu-icon">🎡</div>
                <div class="calc-menu-info">
                    <strong>Magic Wheel</strong>
                    <p><?php echo $current_lang === 'id' ? 'Ketahui estimasi diamond maksimal untuk mendapatkan skin Legends.' : 'Find out estimated maximum diamonds to secure a Legend Skin.'; ?></p>
                </div>
            </div>
            
            <div class="calc-menu-card" id="menu-zodiac" onclick="switchCalcPanel('zodiac')">
                <div class="calc-menu-icon">🌌</div>
                <div class="calc-menu-info">
                    <strong>Zodiac</strong>
                    <p><?php echo $current_lang === 'id' ? 'Ketahui estimasi diamond maksimal untuk mendapatkan skin Zodiac.' : 'Find out estimated maximum diamonds to secure a Zodiac Skin.'; ?></p>
                </div>
            </div>
        </div>

        <div class="card calc-panel-card">
            
            <div class="calc-panel active" id="panel-wr">
                <div class="calc-panel-header">
                    <h2 class="calc-panel-title"><?php echo __('wr_calc'); ?></h2>
                    <p class="calc-panel-desc"><?php echo $current_lang === 'id' ? 'Hitung jumlah kemenangan beruntun untuk mencapai target WR Anda.' : 'Calculate consecutive wins needed to reach your target win rate.'; ?></p>
                </div>

                <div class="calc-form-group">
                    <label for="wr-matches"><?php echo __('current_match'); ?></label>
                    <input type="number" id="wr-matches" placeholder="contoh: 532" min="1">
                </div>
                
                <div class="calc-form-group">
                    <label for="wr-current"><?php echo __('current_wr'); ?></label>
                    <input type="number" id="wr-current" placeholder="contoh: 52.4" min="0" max="100" step="0.01">
                </div>

                <div class="calc-form-group">
                    <label for="wr-target"><?php echo __('target_wr'); ?></label>
                    <input type="number" id="wr-target" placeholder="contoh: 60" min="0" max="100" step="0.01">
                </div>

                <button class="btn btn-primary btn-calc" onclick="calculateWR()">
                    <?php echo __('btn_hitung'); ?>
                </button>

                <div class="calc-result-card" id="result-wr-card">
                    <h4 class="calc-result-title">
                        🎯 <?php echo __('result_wr'); ?>:
                    </h4>
                    <p id="result-wr-text" class="calc-result-text"></p>
                </div>
            </div>

            <div class="calc-panel" id="panel-mw">
                <div class="calc-panel-header">
                    <h2 class="calc-panel-title"><?php echo __('mw_calc'); ?></h2>
                    <p class="calc-panel-desc"><?php echo __('mw_desc'); ?></p>
                </div>

                <div class="calc-form-group">
                    <label for="mw-points"><?php echo __('current_points'); ?></label>
                    <input type="number" id="mw-points" placeholder="contoh: 120" min="0" max="199">
                </div>

                <div class="calc-form-group">
                    <label for="mw-target"><?php echo __('target_points'); ?></label>
                    <input type="number" id="mw-target" value="200" min="1" max="200" class="calc-input-readonly" readonly>
                </div>

                <button class="btn btn-primary btn-calc" onclick="calculateMW()">
                    <?php echo $current_lang === 'id' ? 'Hitung Perkiraan Diamond' : 'Calculate Estimated Diamonds'; ?>
                </button>

                <div class="calc-result-card" id="result-mw-card">
                    <h4 class="calc-result-title">
                        ✨ <?php echo __('result_wr'); ?>:
                    </h4>
                    <p id="result-mw-text" class="calc-result-text"></p>
                    
                    <a href="<?php echo $base_url; ?>user/topup/game.php?slug=mobile-legends" class="btn btn-outline btn-calc-topup">
                        Top Up Diamond Sekarang!
                    </a>
                </div>
            </div>

            <div class="calc-panel" id="panel-zodiac">
                <div class="calc-panel-header">
                    <h2 class="calc-panel-title"><?php echo $current_lang === 'id' ? 'Kalkulator Zodiac' : 'Zodiac Calculator'; ?></h2>
                    <p class="calc-panel-desc">
                        <?php echo $current_lang === 'id' 
                            ? 'Digunakan untuk mengetahui total estimasi diamond yang dibutuhkan untuk mendapatkan skin Zodiac.' 
                            : 'Used to find out the estimated diamonds needed to obtain a Zodiac skin.'; ?>
                    </p>
                </div>

                <div class="calc-form-group">
                    <label for="zodiac-slider"><?php echo $current_lang === 'id' ? 'Geser sesuai dengan Titik Zodiac Kamu' : 'Slide to match your Zodiac Points'; ?></label>
                    <input type="range" id="zodiac-slider" class="zodiac-range" min="0" max="100" value="0" oninput="updateZodiacValue(this.value)">
                </div>

                <div class="zodiac-stats">
                    <div class="zodiac-stat-label">
                        <?php echo $current_lang === 'id' ? 'Poin Bintang Kamu' : 'Your Star Points'; ?> <span id="zodiac-points-display" class="zodiac-stat-val">0</span>
                    </div>
                    <div class="zodiac-stat-label">
                        <?php echo $current_lang === 'id' ? 'Membutuhkan Maksimal' : 'Maximum Required'; ?> <span id="zodiac-diamonds-display" class="zodiac-stat-val">1700</span> Diamond
                    </div>
                </div>

                <a href="<?php echo $base_url; ?>user/topup/game.php?slug=mobile-legends" class="btn btn-primary btn-calc-topup-zodiac">
                    <?php echo $current_lang === 'id' ? 'Top Up Diamond Sekarang!' : 'Top Up Diamonds Now!'; ?>
                </a>
            </div>

        </div>

    </div>
</div>

<script src="<?php echo $base_url; ?>assets/js/calculator.js"></script>

<?php require_once 'includes/footer.php'; ?>
