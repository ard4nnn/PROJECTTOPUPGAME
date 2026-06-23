<?php
require_once '../config/db.php';
require_once '../includes/header.php';

$db_transactions = [];
$db_connected_and_logged_in = false;

if ($db_connected && isset($_SESSION['user_id'])) {
    $db_connected_and_logged_in = true;
    try {
        $stmt = $pdo->prepare("
            SELECT t.*, p.nama_produk, g.nama_game, m.nama as nama_metode
            FROM transaksi t
            LEFT JOIN produk p ON t.produk_id = p.id
            LEFT JOIN games g ON p.game_id = g.id
            LEFT JOIN metode_bayar m ON t.metode_bayar_id = m.id
            WHERE t.user_id = ?
            ORDER BY t.created_at DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $db_transactions = $stmt->fetchAll();
    } catch (PDOException $e) {
        $db_connected_and_logged_in = false;
    }
}
?>

<div class="container riwayat-container">
    
    <div class="riwayat-header">
        <h2 class="riwayat-title">
            <?php echo $current_lang === 'id' ? 'Riwayat Transaksi Anda' : 'Your Transaction History'; ?>
        </h2>
        <p class="riwayat-subtitle">
            <?php echo $current_lang === 'id' 
                ? 'Pantau status pemesanan voucher dan top up Anda secara berkala.' 
                : 'Monitor your top-up and game voucher order status regularly.'; ?>
        </p>
    </div>

    <!-- Table Card Container -->
    <div class="card table-wrapper-card">
        <table class="riwayat-table">
            <thead>
                <tr>
                    <th>
                        <?php echo $current_lang === 'id' ? 'No. Transaksi' : 'Tx Invoice'; ?>
                    </th>
                    <th>
                        <?php echo $current_lang === 'id' ? 'Tanggal' : 'Date'; ?>
                    </th>
                    <th><?php echo __('game'); ?></th>
                    <th><?php echo __('produk'); ?></th>
                    <th><?php echo __('target_id'); ?></th>
                    <th><?php echo __('metode'); ?></th>
                    <th><?php echo $current_lang === 'id' ? 'Harga' : 'Price'; ?></th>
                    <th class="col-status">Status</th>
                </tr>
            </thead>
            <tbody id="transaction-tbody">
                <?php if ($db_connected_and_logged_in): ?>
                    <?php if (count($db_transactions) > 0): ?>
                        <?php foreach ($db_transactions as $tx): ?>
                            <tr>
                                <td class="col-invoice">#<?php echo $tx['id']; ?></td>
                                <td class="col-date"><?php echo date("d-m-Y H:i", strtotime($tx['created_at'])); ?></td>
                                <td class="col-game"><?php echo htmlspecialchars($tx['nama_game']); ?></td>
                                <td class="col-product"><?php echo htmlspecialchars($tx['nama_produk']); ?></td>
                                <td><code><?php echo htmlspecialchars($tx['id_game_user']); ?></code></td>
                                <td class="col-payment"><?php echo htmlspecialchars($tx['nama_metode']); ?></td>
                                <td class="col-price">Rp <?php echo number_format($tx['nominal_transfer'], 0, ',', '.'); ?></td>
                                <td class="col-status">
                                    <span class="badge badge-<?php echo $tx['status']; ?>"><?php echo htmlspecialchars($tx['status']); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="table-empty-cell">
                                <?php echo $current_lang === 'id' ? 'Belum ada riwayat transaksi di database.' : 'No transactions recorded in database.'; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php else: ?>
                    <tr id="no-history-row">
                        <td colspan="8" class="table-empty-cell">
                            <?php echo $current_lang === 'id' 
                                ? 'Belum ada riwayat transaksi. Lakukan pembelian di halaman game untuk memunculkan transaksi demo.' 
                                : 'No history found. Complete a purchase on a game top-up page to generate demo transactions.'; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Demo clearing panel -->
    <div id="clear-demo-container" class="clear-demo-container">
        <button id="btn-clear-demo" class="btn btn-outline btn-clear-demo">
            🗑️ <?php echo $current_lang === 'id' ? 'Bersihkan Riwayat Demo' : 'Clear Demo History'; ?>
        </button>
    </div>

    <!-- Guide block -->
    <div class="card guide-card">
        <h4 class="guide-title">
            <?php echo $current_lang === 'id' ? 'Panduan Pembayaran & Proses Top Up:' : 'Payment & Top Up Guide:'; ?>
        </h4>
        <ol class="guide-list">
            <li><?php echo $current_lang === 'id' ? 'Selesaikan pembayaran Anda menggunakan e-wallet/bank transfer yang telah dipilih.' : 'Complete your payment using the chosen e-wallet or bank transfer.'; ?></li>
            <li><?php echo $current_lang === 'id' ? 'Status pesanan Anda akan terdaftar sebagai <span class="badge badge-pending">PENDING</span> terlebih dahulu.' : 'Your order status will initially be recorded as <span class="badge badge-pending">PENDING</span>.'; ?></li>
            <li><?php echo $current_lang === 'id' ? 'Admin akan memverifikasi pembayaran Anda, lalu status pesanan akan berubah menjadi <span class="badge badge-sukses">SUKSES</span> dan produk top up otomatis masuk..."' : 'Admin will verify your payment, then the status will change to <span class="badge badge-sukses">SUCCESS</span> and the game credits will automatically enter your account.'; ?></li>
        </ol>
    </div>
</div>

<script>
    window.isDbConnected = <?php echo $db_connected_and_logged_in ? 'true' : 'false'; ?>;
</script>
<script src="<?php echo $base_url; ?>assets/js/riwayat.js"></script>

<?php require_once '../includes/footer.php'; ?>
