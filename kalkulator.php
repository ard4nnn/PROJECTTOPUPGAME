<?php
require_once 'config/db.php';
require_once 'includes/header.php';
?>

<style>
    .calc-container-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
        margin-top: 30px;
        margin-bottom: 60px;
    }

    @media (min-width: 768px) {
        .calc-container-grid {
            grid-template-columns: 300px 1fr;
        }
    }

    /* Sidebar Navigation */
    .calc-sidebar {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .calc-menu-card {
        background-color: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-xl);
        padding: 20px;
        cursor: pointer;
        display: flex;
        align-items: flex-start;
        gap: 15px;
        transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
        text-align: left;
        user-select: none;
    }

    .calc-menu-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .calc-menu-card.active {
        border-color: var(--primary-color);
        background-color: rgba(252, 213, 53, 0.05);
        box-shadow: 0 4px 15px rgba(252, 213, 53, 0.08);
    }

    .calc-menu-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(252, 213, 53, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: var(--primary-color);
        flex-shrink: 0;
        border: 1px solid rgba(252, 213, 53, 0.15);
    }

    .calc-menu-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .calc-menu-info strong {
        font-size: 15px;
        font-weight: 800;
        color: var(--text-color);
    }

    .calc-menu-info p {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.4;
        margin: 0;
    }

    /* Right active panel content */
    .calc-panel {
        display: none;
        animation: panelFadeIn 0.35s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .calc-panel.active {
        display: block;
    }

    @keyframes panelFadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .calc-form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 18px;
    }

    .calc-form-group label {
        font-weight: 700;
        font-size: 14px;
        color: var(--text-color);
    }

    .calc-form-group input {
        width: 100%;
        padding: 12px 16px;
        border-radius: var(--radius-md);
        border: 2px solid var(--card-border);
        background-color: var(--bg-color);
        color: var(--text-color);
        font-family: inherit;
        font-size: 14.5px;
        outline: none;
        transition: border-color 0.2s;
    }

    .calc-form-group input:focus {
        border-color: var(--primary-color);
    }

    .calc-result-card {
        margin-top: 25px;
        padding: 20px;
        border-radius: var(--radius-lg);
        background: linear-gradient(135deg, rgba(252, 213, 53, 0.04) 0%, rgba(11, 14, 17, 0.02) 100%);
        border: 1px solid rgba(252, 213, 53, 0.2);
        display: none;
    }

    /* Slider styling for Zodiac */
    .zodiac-range {
        -webkit-appearance: none;
        width: 100%;
        height: 8px;
        border-radius: 4px;
        background: var(--card-border);
        outline: none;
        padding: 0 !important;
        margin: 10px 0;
    }

    .zodiac-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--primary-color);
        cursor: pointer;
        box-shadow: 0 0 8px rgba(252, 213, 53, 0.6);
        transition: transform 0.1s;
    }

    .zodiac-range::-webkit-slider-thumb:hover {
        transform: scale(1.2);
    }
</style>

<div class="container">
    <!-- Grid Layout -->
    <div class="calc-container-grid">
        
        <!-- Left Sidebar Navigation -->
        <div class="calc-sidebar">
            <!-- Win Rate Menu -->
            <div class="calc-menu-card active" id="menu-wr" onclick="switchCalcPanel('wr')">
                <div class="calc-menu-icon">📈</div>
                <div class="calc-menu-info">
                    <strong>Win Rate</strong>
                    <p><?php echo $current_lang === 'id' ? 'Hitung total match yang ditempuh untuk mencapai target win rate.' : 'Calculate total matches needed to reach your target win rate.'; ?></p>
                </div>
            </div>
            
            <!-- Magic Wheel Menu -->
            <div class="calc-menu-card" id="menu-mw" onclick="switchCalcPanel('mw')">
                <div class="calc-menu-icon">🎡</div>
                <div class="calc-menu-info">
                    <strong>Magic Wheel</strong>
                    <p><?php echo $current_lang === 'id' ? 'Ketahui estimasi diamond maksimal untuk mendapatkan skin Legends.' : 'Find out estimated maximum diamonds to secure a Legend Skin.'; ?></p>
                </div>
            </div>
            
            <!-- Zodiac Menu -->
            <div class="calc-menu-card" id="menu-zodiac" onclick="switchCalcPanel('zodiac')">
                <div class="calc-menu-icon">🌌</div>
                <div class="calc-menu-info">
                    <strong>Zodiac</strong>
                    <p><?php echo $current_lang === 'id' ? 'Ketahui estimasi diamond maksimal untuk mendapatkan skin Zodiac.' : 'Find out estimated maximum diamonds to secure a Zodiac Skin.'; ?></p>
                </div>
            </div>
        </div>

        <!-- Right Content Panels -->
        <div class="card" style="box-shadow: var(--shadow-lg); border-radius: var(--radius-xl); padding: 32px;">
            
            <!-- PANEL 1: Win Rate Calculator -->
            <div class="calc-panel active" id="panel-wr">
                <div style="margin-bottom: 25px;">
                    <h2 style="font-size: 22px; font-weight: 800; color: var(--text-color); margin: 0;"><?php echo __('wr_calc'); ?></h2>
                    <p style="color: var(--text-muted); font-size: 13.5px; margin-top: 5px;"><?php echo $current_lang === 'id' ? 'Hitung jumlah kemenangan beruntun untuk mencapai target WR Anda.' : 'Calculate consecutive wins needed to reach your target win rate.'; ?></p>
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

                <button class="btn btn-primary" onclick="calculateWR()" style="width: 100%; padding: 13px; font-size: 15px; font-weight: 800; margin-top: 10px;">
                    <?php echo __('btn_hitung'); ?>
                </button>

                <div class="calc-result-card" id="result-wr-card">
                    <h4 style="margin: 0 0 8px 0; font-size: 15.5px; font-weight: 800; color: var(--primary-color);">
                        🎯 <?php echo __('result_wr'); ?>:
                    </h4>
                    <p id="result-wr-text" style="margin: 0; font-size: 14px; line-height: 1.6; color: var(--text-color);"></p>
                </div>
            </div>

            <!-- PANEL 2: Magic Wheel Calculator -->
            <div class="calc-panel" id="panel-mw">
                <div style="margin-bottom: 25px;">
                    <h2 style="font-size: 22px; font-weight: 800; color: var(--text-color); margin: 0;"><?php echo __('mw_calc'); ?></h2>
                    <p style="color: var(--text-muted); font-size: 13.5px; margin-top: 5px;"><?php echo __('mw_desc'); ?></p>
                </div>

                <div class="calc-form-group">
                    <label for="mw-points"><?php echo __('current_points'); ?></label>
                    <input type="number" id="mw-points" placeholder="contoh: 120" min="0" max="199">
                </div>

                <div class="calc-form-group">
                    <label for="mw-target"><?php echo __('target_points'); ?></label>
                    <input type="number" id="mw-target" value="200" min="1" max="200" readonly style="opacity: 0.65; cursor: not-allowed; background-color: var(--bg-color);">
                </div>

                <button class="btn btn-primary" onclick="calculateMW()" style="width: 100%; padding: 13px; font-size: 15px; font-weight: 800; margin-top: 10px;">
                    <?php echo $current_lang === 'id' ? 'Hitung Perkiraan Diamond' : 'Calculate Estimated Diamonds'; ?>
                </button>

                <div class="calc-result-card" id="result-mw-card">
                    <h4 style="margin: 0 0 8px 0; font-size: 15.5px; font-weight: 800; color: var(--primary-color);">
                        ✨ <?php echo __('result_wr'); ?>:
                    </h4>
                    <p id="result-mw-text" style="margin: 0; font-size: 14px; line-height: 1.6; color: var(--text-color);"></p>
                    
                    <a href="<?php echo $base_url; ?>user/topup/game.php?slug=mobile-legends" class="btn btn-outline" style="width: 100%; margin-top: 15px; font-size: 13.5px; padding: 10px; display: inline-flex;">
                        Top Up Diamond Sekarang!
                    </a>
                </div>
            </div>

            <!-- PANEL 3: Zodiac Calculator -->
            <div class="calc-panel" id="panel-zodiac">
                <div style="margin-bottom: 25px;">
                    <h2 style="font-size: 22px; font-weight: 800; color: var(--text-color); margin: 0;"><?php echo $current_lang === 'id' ? 'Kalkulator Zodiac' : 'Zodiac Calculator'; ?></h2>
                    <p style="color: var(--text-muted); font-size: 13.5px; margin-top: 5px;">
                        <?php echo $current_lang === 'id' 
                            ? 'Digunakan untuk mengetahui total estimasi diamond yang dibutuhkan untuk mendapatkan skin Zodiac.' 
                            : 'Used to find out the estimated diamonds needed to obtain a Zodiac skin.'; ?>
                    </p>
                </div>

                <div class="calc-form-group">
                    <label for="zodiac-slider"><?php echo $current_lang === 'id' ? 'Geser sesuai dengan Titik Zodiac Kamu' : 'Slide to match your Zodiac Points'; ?></label>
                    <input type="range" id="zodiac-slider" class="zodiac-range" min="0" max="100" value="0" oninput="updateZodiacValue(this.value)">
                </div>

                <!-- Dynamic display under slider -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; margin-bottom: 30px; font-size: 16px; font-weight: 800; flex-wrap: wrap; gap: 10px;">
                    <div style="color: var(--text-color);">
                        <?php echo $current_lang === 'id' ? 'Poin Bintang Kamu' : 'Your Star Points'; ?> <span id="zodiac-points-display" style="color: var(--primary-color);">0</span>
                    </div>
                    <div style="color: var(--text-color);">
                        <?php echo $current_lang === 'id' ? 'Membutuhkan Maksimal' : 'Maximum Required'; ?> <span id="zodiac-diamonds-display" style="color: var(--primary-color);">1700</span> Diamond
                    </div>
                </div>

                <a href="<?php echo $base_url; ?>user/topup/game.php?slug=mobile-legends" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px; font-weight: 800; text-align: center; display: block; border-radius: var(--radius-md);">
                    <?php echo $current_lang === 'id' ? 'Top Up Diamond Sekarang!' : 'Top Up Diamonds Now!'; ?>
                </a>
            </div>

        </div>

    </div>
</div>

<script>
    const currentLang = "<?php echo $current_lang; ?>";

    // Panel switcher logic
    function switchCalcPanel(panelName) {
        // Toggle side menu active class
        document.querySelectorAll('.calc-menu-card').forEach(card => {
            card.classList.remove('active');
        });
        document.getElementById('menu-' + panelName).classList.add('active');

        // Toggle panel display
        document.querySelectorAll('.calc-panel').forEach(panel => {
            panel.classList.remove('active');
        });
        document.getElementById('panel-' + panelName).classList.add('active');
    }

    // Win Rate Calculator
    function calculateWR() {
        const matches = parseInt(document.getElementById('wr-matches').value);
        const currentWr = parseFloat(document.getElementById('wr-current').value);
        const targetWr = parseFloat(document.getElementById('wr-target').value);
        
        const resultCard = document.getElementById('result-wr-card');
        const resultText = document.getElementById('result-wr-text');

        if (isNaN(matches) || isNaN(currentWr) || isNaN(targetWr) || matches <= 0) {
            alert(currentLang === 'id' ? 'Harap isi semua field dengan angka valid!' : 'Please fill all fields with valid numbers!');
            return;
        }

        if (currentWr >= 100 || targetWr >= 100 || currentWr <= 0 || targetWr <= 0) {
            alert(currentLang === 'id' ? 'Win rate harus berada di antara 0% dan 100%!' : 'Win rate must be between 0% and 100%!');
            return;
        }

        if (targetWr <= currentWr) {
            alert(currentLang === 'id' ? 'Target Win Rate harus lebih besar dari Win Rate saat ini!' : 'Target Win Rate must be greater than current Win Rate!');
            return;
        }

        const m = matches;
        const w = currentWr / 100;
        const t = targetWr / 100;

        const x = Math.ceil((m * (t - w)) / (1 - t));

        if (x === Infinity || isNaN(x)) {
            resultText.innerHTML = currentLang === 'id' ? 'Perhitungan tidak valid.' : 'Invalid calculation.';
        } else {
            let template = currentLang === 'id' 
                ? 'Anda memerlukan sekitar <strong>{matches}</strong> kemenangan beruntun (tanpa kalah) untuk mencapai target win rate <strong>{target}%</strong>.'
                : 'You need around <strong>{matches}</strong> consecutive wins (without losing) to reach target win rate of <strong>{target}%</strong>.';
            
            resultText.innerHTML = template.replace('{matches}', x).replace('{target}', targetWr);
        }

        resultCard.style.display = 'block';
    }

    // Magic Wheel Calculator
    function calculateMW() {
        const points = parseInt(document.getElementById('mw-points').value);
        const resultCard = document.getElementById('result-mw-card');
        const resultText = document.getElementById('result-mw-text');

        if (isNaN(points) || points < 0 || points >= 200) {
            alert(currentLang === 'id' ? 'Poin Magic Wheel harus berada di antara 0 dan 199!' : 'Magic Wheel points must be between 0 and 199!');
            return;
        }

        const remainingPoints = 200 - points;
        const drawsOfFive = Math.ceil(remainingPoints / 5);
        const diamondsNeeded = drawsOfFive * 270;
        const estCost = diamondsNeeded * 270;

        let template = currentLang === 'id'
            ? 'Sisa poin yang dibutuhkan: <strong>{points}</strong> poin.<br>Estimasi diamond yang dibutuhkan: sekitar <strong>{diamonds}</strong> Diamond (menggunakan opsi 5x draw seharga 270 diamond).<br>Estimasi biaya: <strong>Rp {cost}</strong> (kurs estimasi Rp 270/Diamond).'
            : 'Remaining points needed: <strong>{points}</strong> points.<br>Estimated diamonds needed: around <strong>{diamonds}</strong> Diamonds (using 5x draw option for 270 diamonds).<br>Estimated cost: <strong>IDR {cost}</strong> (estimated rate IDR 270/Diamond).';

        resultText.innerHTML = template
            .replace('{points}', remainingPoints)
            .replace('{diamonds}', diamondsNeeded.toLocaleString('id-ID'))
            .replace('{cost}', estCost.toLocaleString('id-ID'));

        resultCard.style.display = 'block';
    }

    // Zodiac Slider real-time calculation
    function updateZodiacValue(val) {
        document.getElementById('zodiac-points-display').textContent = val;
        const remaining = 100 - parseInt(val);
        const maxDiamonds = remaining * 17;
        document.getElementById('zodiac-diamonds-display').textContent = maxDiamonds;
    }
</script>

<?php require_once 'includes/footer.php'; ?>
