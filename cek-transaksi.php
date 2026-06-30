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
        }
    }
}
?>

<div class="container tx-search-container">
    <div class="card">
        <div class="card-header-center">
            <span class="center-icon">🔍</span>
            <h2 class="card-title-lg"><?php echo __('cek_title'); ?></h2>
            <p class="card-desc"><?php echo __('cek_desc'); ?></p>
        </div>

        <form action="" method="GET" class="search-form-row">
            <input type="text" name="id" id="input-tx-id" value="<?php echo htmlspecialchars($search_id); ?>" placeholder="<?php echo __('input_invoice'); ?>" class="search-form-input" required>
            <button type="submit" class="btn btn-primary search-form-btn">
                <?php echo __('btn_cari'); ?>
            </button>
        </form>

        <div id="tx-result-box" class="tx-result-box" style="display: <?php echo (!empty($search_id) && $transaction) ? 'block' : 'none'; ?>;">
            <h3 class="result-title">
                📄 <?php echo __('tx_detail'); ?>
            </h3>

            <div class="receipt-card">
                <div class="receipt-row-border">
                    <span class="receipt-label">No. Transaksi</span>
                    <strong id="res-invoice" class="receipt-val-mono">
                        <?php echo $transaction ? '#' . $transaction['id'] : ''; ?>
                    </strong>
                </div>

                <div class="receipt-row">
                    <span class="receipt-label"><?php echo __('game'); ?></span>
                    <strong id="res-game"><?php echo $transaction ? htmlspecialchars($transaction['nama_game']) : ''; ?></strong>
                </div>

                <div class="receipt-row">
                    <span class="receipt-label"><?php echo __('produk'); ?></span>
                    <strong id="res-product"><?php echo $transaction ? htmlspecialchars($transaction['nama_produk']) : ''; ?></strong>
                </div>

                <div class="receipt-row">
                    <span class="receipt-label"><?php echo __('target_id'); ?></span>
                    <code id="res-target"><?php echo $transaction ? htmlspecialchars($transaction['id_game_user']) : ''; ?></code>
                </div>

                <div class="receipt-row">
                    <span class="receipt-label"><?php echo __('metode'); ?></span>
                    <strong id="res-payment"><?php echo $transaction ? htmlspecialchars($transaction['nama_metode']) : ''; ?></strong>
                </div>

                <div class="receipt-row-dashed">
                    <span class="receipt-label"><?php echo __('status_pembayaran'); ?></span>
                    <span class="badge <?php echo $transaction ? 'badge-' . $transaction['status'] : ''; ?> receipt-badge" id="res-status">
                        <?php echo $transaction ? htmlspecialchars($transaction['status']) : ''; ?>
                    </span>
                </div>

                <div class="receipt-total-row">
                    <span class="receipt-total-label"><?php echo __('total_tagihan'); ?></span>
                    <strong id="res-total" class="receipt-total-val">
                        <?php echo $transaction ? 'Rp ' . number_format($transaction['nominal_transfer'], 0, ',', '.') : ''; ?>
                    </strong>
                </div>
            </div>
        </div>

        <div id="tx-error-box" class="tx-error-box" style="display: <?php echo (!empty($search_id) && !$transaction && $is_db_tx) ? 'block' : 'none'; ?>;">
            ⚠️ <?php echo __('tidak_ditemukan'); ?>
        </div>

        <div class="back-home-container">
            <a href="index.php" class="btn-back-home">
                &larr; <?php echo __('kembali'); ?>
            </a>
        </div>
    </div>
</div>

<script>
    window.isDbTx = <?php echo $is_db_tx ? 'true' : 'false'; ?>;
    window.searchId = "<?php echo htmlspecialchars($search_id); ?>";
</script>
<script src="<?php echo $base_url; ?>assets/js/cek-transaksi.js"></script>

<?php require_once 'includes/footer.php'; ?>
