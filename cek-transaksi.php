<?php
require_once 'config/db.php';
require_once 'includes/header.php';

$search_id = isset($_GET['id']) ? trim($_GET['id']) : '';
$transaction = null;
$error_tx = '';
$is_db_tx = false;

if (!empty($search_id)) {
    if ($db_connected && $pdo) {
        try {
            // Clean ID format
            $cleaned_id = preg_replace('/[^0-9]/', '', $search_id);
            $stmt = $pdo->prepare("
                SELECT t.*, p.nama_produk, g.nama_game, m.nama as nama_metode
                FROM transaksi t
                LEFT JOIN produk p ON t.produk_id = p.id
                LEFT JOIN games g ON p.game_id = g.id
                LEFT JOIN metode_bayar m ON t.metode_bayar_id = m.id
                WHERE t.id = ?
            ");
            $stmt->execute([$cleaned_id]);
            $transaction = $stmt->fetch();
            if ($transaction) {
                $is_db_tx = true;
            }
        } catch (PDOException $e) {
            // fallback
        }
    }
}
?>

<div class="container" style="max-width: 600px; margin-top: 40px; margin-bottom: 60px;">
    <div class="card" style="box-shadow: var(--shadow-lg); border-radius: var(--radius-xl);">
        <div style="text-align: center; margin-bottom: 30px;">
            <span style="font-size: 40px; display: block; margin-bottom: 10px;">🔍</span>
            <h2 style="margin: 0; font-size: 26px; font-weight: 800; letter-spacing: -0.5px;"><?php echo __('cek_title'); ?></h2>
            <p style="color: var(--text-muted); font-size: 14.5px; margin-top: 6px; line-height: 1.5;"><?php echo __('cek_desc'); ?></p>
        </div>

        <form action="" method="GET" style="display: flex; gap: 10px; margin-bottom: 20px;">
            <input type="text" name="id" id="input-tx-id" value="<?php echo htmlspecialchars($search_id); ?>" placeholder="<?php echo __('input_invoice'); ?>" required
                   style="flex: 1; padding: 12px 18px; border-radius: var(--radius-md); border: 2px solid var(--card-border); background-color: var(--bg-color); color: var(--text-color); font-family: inherit; font-size: 15px; outline: none; transition: border-color 0.2s;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 15px; font-weight: 700;">
                <?php echo __('btn_cari'); ?>
            </button>
        </form>

        <!-- Container details for Javascript (Local Storage) or PHP (DB) -->
        <div id="tx-result-box" style="display: <?php echo (!empty($search_id) && $transaction) ? 'block' : 'none'; ?>; border-top: 1px dashed var(--card-border); padding-top: 25px; margin-top: 20px;">
            
            <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 15px; text-align: center; color: var(--primary-color);">
                📄 <?php echo __('tx_detail'); ?>
            </h3>

            <div class="receipt-card" style="background-color: var(--bg-color); border: 1px solid var(--card-border); border-radius: var(--radius-lg); padding: 20px; display: flex; flex-direction: column; gap: 12px; font-size: 14.5px; box-shadow: var(--shadow-sm);">
                
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--card-border); padding-bottom: 10px;">
                    <span style="color: var(--text-muted);">No. Transaksi</span>
                    <strong id="res-invoice" style="font-family: monospace; font-size: 15px;">
                        <?php echo $transaction ? '#' . $transaction['id'] : ''; ?>
                    </strong>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);"><?php echo __('game'); ?></span>
                    <strong id="res-game"><?php echo $transaction ? htmlspecialchars($transaction['nama_game']) : ''; ?></strong>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);"><?php echo __('produk'); ?></span>
                    <strong id="res-product"><?php echo $transaction ? htmlspecialchars($transaction['nama_produk']) : ''; ?></strong>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);"><?php echo __('target_id'); ?></span>
                    <code id="res-target"><?php echo $transaction ? htmlspecialchars($transaction['id_game_user']) : ''; ?></code>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);"><?php echo __('metode'); ?></span>
                    <strong id="res-payment"><?php echo $transaction ? htmlspecialchars($transaction['nama_metode']) : ''; ?></strong>
                </div>

                <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--card-border); padding-bottom: 12px;">
                    <span style="color: var(--text-muted);"><?php echo __('status_pembayaran'); ?></span>
                    <span class="badge <?php echo $transaction ? 'badge-' . $transaction['status'] : ''; ?>" id="res-status" style="padding: 4px 10px;">
                        <?php echo $transaction ? htmlspecialchars($transaction['status']) : ''; ?>
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold; padding-top: 8px;">
                    <span style="color: var(--text-color);"><?php echo __('total_tagihan'); ?></span>
                    <strong id="res-total" style="color: var(--primary-color);">
                        <?php echo $transaction ? 'Rp ' . number_format($transaction['nominal_transfer'], 0, ',', '.') : ''; ?>
                    </strong>
                </div>
            </div>
        </div>

        <div id="tx-error-box" style="display: <?php echo (!empty($search_id) && !$transaction && $is_db_tx) ? 'block' : 'none'; ?>; text-align: center; margin-top: 25px; padding: 20px; background-color: rgba(246, 70, 93, 0.1); border: 1px solid rgba(246, 70, 93, 0.2); border-radius: var(--radius-lg); color: var(--danger-color);">
            ⚠️ <?php echo __('tidak_ditemukan'); ?>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <a href="index.php" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 14.5px;">
                &larr; <?php echo __('kembali'); ?>
            </a>
        </div>
    </div>
</div>

<script>
    // If not connected to database or transaction not found in DB, check local storage (Demo Mode)
    const isDbTx = <?php echo $is_db_tx ? 'true' : 'false'; ?>;
    const searchId = "<?php echo htmlspecialchars($search_id); ?>";
    const currentLang = "<?php echo $current_lang; ?>";

    if (searchId && !isDbTx) {
        const orderHistory = JSON.parse(localStorage.getItem('order_history')) || [];
        // Match either raw ID or with hashtag
        const cleanSearch = searchId.replace('#', '').trim();
        const matched = orderHistory.find(order => String(order.id) === cleanSearch);

        const resultBox = document.getElementById('tx-result-box');
        const errorBox = document.getElementById('tx-error-box');

        if (matched) {
            resultBox.style.display = 'block';
            errorBox.style.display = 'none';

            document.getElementById('res-invoice').textContent = '#' + matched.id + ' (Demo)';
            document.getElementById('res-game').textContent = matched.game;
            document.getElementById('res-product').textContent = matched.product;
            document.getElementById('res-target').textContent = matched.targetId;
            document.getElementById('res-payment').textContent = matched.payment;
            
            const statusEl = document.getElementById('res-status');
            statusEl.className = 'badge badge-' + matched.status;
            statusEl.textContent = matched.status.toUpperCase();

            document.getElementById('res-total').textContent = 'Rp ' + matched.price.toLocaleString('id-ID');
        } else {
            resultBox.style.display = 'none';
            errorBox.style.display = 'block';
            errorBox.textContent = currentLang === 'id' ? '⚠️ Transaksi tidak ditemukan di riwayat demo!' : '⚠️ Transaction not found in demo history!';
        }
    }

    const inputTxId = document.getElementById('input-tx-id');
    if (inputTxId) {
        inputTxId.addEventListener('focus', function() {
            this.style.borderColor = 'var(--primary-color)';
        });
        inputTxId.addEventListener('blur', function() {
            this.style.borderColor = 'var(--card-border)';
        });
    }
</script>

<?php require_once 'includes/footer.php'; ?>
