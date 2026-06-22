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

<div class="container" style="margin-top: 30px; margin-bottom: 60px;">
    
    <div style="margin-bottom: 25px;">
        <h2 style="font-size: 24px; font-weight: 800; letter-spacing: -0.5px; margin: 0; color: var(--text-color);">
            <?php echo $current_lang === 'id' ? 'Riwayat Transaksi Anda' : 'Your Transaction History'; ?>
        </h2>
        <p style="color: var(--text-muted); font-size: 14.5px; margin-top: 5px; margin-bottom: 0;">
            <?php echo $current_lang === 'id' 
                ? 'Pantau status pemesanan voucher dan top up Anda secara berkala.' 
                : 'Monitor your top-up and game voucher order status regularly.'; ?>
        </p>
    </div>

    <!-- Table Card Container -->
    <div class="card" style="padding: 10px; overflow-x: auto; border-radius: var(--radius-xl); box-shadow: var(--shadow-md);">
        <table style="width: 100%; border-collapse: collapse; min-width: 700px; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--card-border);">
                    <th style="padding: 14px 16px; font-weight: 700; color: var(--text-muted); font-size: 13px; text-transform: uppercase;">
                        <?php echo $current_lang === 'id' ? 'No. Transaksi' : 'Tx Invoice'; ?>
                    </th>
                    <th style="padding: 14px 16px; font-weight: 700; color: var(--text-muted); font-size: 13px; text-transform: uppercase;">
                        <?php echo $current_lang === 'id' ? 'Tanggal' : 'Date'; ?>
                    </th>
                    <th style="padding: 14px 16px; font-weight: 700; color: var(--text-muted); font-size: 13px; text-transform: uppercase;"><?php echo __('game'); ?></th>
                    <th style="padding: 14px 16px; font-weight: 700; color: var(--text-muted); font-size: 13px; text-transform: uppercase;"><?php echo __('produk'); ?></th>
                    <th style="padding: 14px 16px; font-weight: 700; color: var(--text-muted); font-size: 13px; text-transform: uppercase;"><?php echo __('target_id'); ?></th>
                    <th style="padding: 14px 16px; font-weight: 700; color: var(--text-muted); font-size: 13px; text-transform: uppercase;"><?php echo __('metode'); ?></th>
                    <th style="padding: 14px 16px; font-weight: 700; color: var(--text-muted); font-size: 13px; text-transform: uppercase;"><?php echo $current_lang === 'id' ? 'Harga' : 'Price'; ?></th>
                    <th style="padding: 14px 16px; font-weight: 700; color: var(--text-muted); font-size: 13px; text-transform: uppercase; width: 130px; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody id="transaction-tbody">
                <?php if ($db_connected_and_logged_in): ?>
                    <?php if (count($db_transactions) > 0): ?>
                        <?php foreach ($db_transactions as $tx): ?>
                            <tr style="border-bottom: 1px solid var(--card-border);">
                                <td style="padding: 16px; font-weight: 700; color: var(--text-color);">#<?php echo $tx['id']; ?></td>
                                <td style="padding: 16px; font-size: 13.5px; color: var(--text-muted);"><?php echo date("d-m-Y H:i", strtotime($tx['created_at'])); ?></td>
                                <td style="padding: 16px; font-weight: 700; color: var(--text-color);"><?php echo htmlspecialchars($tx['nama_game']); ?></td>
                                <td style="padding: 16px; color: var(--text-color);"><?php echo htmlspecialchars($tx['nama_produk']); ?></td>
                                <td style="padding: 16px;"><code style="background-color: var(--bg-color); padding: 4px 8px; border-radius: 4px; border: 1px solid var(--card-border);"><?php echo htmlspecialchars($tx['id_game_user']); ?></code></td>
                                <td style="padding: 16px; color: var(--text-color);"><?php echo htmlspecialchars($tx['nama_metode']); ?></td>
                                <td style="padding: 16px; font-weight: 800; color: var(--primary-color);">Rp <?php echo number_format($tx['nominal_transfer'], 0, ',', '.'); ?></td>
                                <td style="padding: 16px; text-align: center;">
                                    <span class="badge badge-<?php echo $tx['status']; ?>"><?php echo htmlspecialchars($tx['status']); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="padding: 40px; text-align: center; color: var(--text-muted); font-size: 14.5px;">
                                <?php echo $current_lang === 'id' ? 'Belum ada riwayat transaksi di database.' : 'No transactions recorded in database.'; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php else: ?>
                    <tr id="no-history-row">
                        <td colspan="8" style="padding: 40px; text-align: center; color: var(--text-muted); font-size: 14.5px;">
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
    <div id="clear-demo-container" style="display: none; margin-top: 20px; text-align: right;">
        <button id="btn-clear-demo" class="btn btn-outline" style="padding: 8px 16px; font-size: 13px; color: var(--danger-color); border-color: rgba(246,70,93,0.3); font-weight: 700;">
            🗑️ <?php echo $current_lang === 'id' ? 'Bersihkan Riwayat Demo' : 'Clear Demo History'; ?>
        </button>
    </div>

    <!-- Guide block -->
    <div class="card" style="margin-top: 40px; background-color: var(--card-bg);">
        <h4 style="margin-top: 0; font-size: 16px; font-weight: 800; color: var(--text-color); margin-bottom: 12px;">
            <?php echo $current_lang === 'id' ? 'Panduan Pembayaran & Proses Top Up:' : 'Payment & Top Up Guide:'; ?>
        </h4>
        <ol style="padding-left: 20px; line-height: 1.8; font-size: 13.5px; color: var(--text-muted); margin-bottom: 0;">
            <li><?php echo $current_lang === 'id' ? 'Selesaikan pembayaran Anda menggunakan e-wallet/bank transfer yang telah dipilih.' : 'Complete your payment using the chosen e-wallet or bank transfer.'; ?></li>
            <li><?php echo $current_lang === 'id' ? 'Status pesanan Anda akan terdaftar sebagai <span class="badge badge-pending">PENDING</span> terlebih dahulu.' : 'Your order status will initially be recorded as <span class="badge badge-pending">PENDING</span>.'; ?></li>
            <li><?php echo $current_lang === 'id' ? 'Admin akan memverifikasi pembayaran Anda, lalu status pesanan akan berubah menjadi <span class="badge badge-sukses">SUKSES</span> dan produk top up otomatis masuk ke akun Anda.' : 'Admin will verify your payment, then the status will change to <span class="badge badge-sukses">SUCCESS</span> and the game credits will automatically enter your account.'; ?></li>
        </ol>
    </div>
</div>

<script>
    const isDbConnected = <?php echo $db_connected_and_logged_in ? 'true' : 'false'; ?>;

    if (!isDbConnected) {
        const orderHistory = JSON.parse(localStorage.getItem('order_history')) || [];
        const tbody = document.getElementById('transaction-tbody');
        const noHistoryRow = document.getElementById('no-history-row');
        const clearDemoContainer = document.getElementById('clear-demo-container');

        if (orderHistory.length > 0) {
            if (noHistoryRow) noHistoryRow.remove();
            if (clearDemoContainer) clearDemoContainer.style.display = 'block';

            orderHistory.forEach(order => {
                const tr = document.createElement('tr');
                tr.style.borderBottom = '1px solid var(--card-border)';
                
                tr.innerHTML = `
                    <td style="padding: 16px; font-weight: 700; color: var(--text-color)">#\${order.id} (Demo)</td>
                    <td style="padding: 16px; font-size: 13px; color: var(--text-muted);">\${order.date}</td>
                    <td style="padding: 16px; font-weight: 700; color: var(--text-color)">\${order.game}</td>
                    <td style="padding: 16px; color: var(--text-color)">\${order.product}</td>
                    <td style="padding: 16px;">
                        <code style="background-color: var(--bg-color); padding: 4px 8px; border-radius: 4px; border: 1px solid var(--card-border);">\${order.targetId}</code>
                    </td>
                    <td style="padding: 16px; color: var(--text-color)">\${order.payment}</td>
                    <td style="padding: 16px; font-weight: 800; color: var(--primary-color);">Rp \${order.price.toLocaleString('id-ID')}</td>
                    <td style="padding: 16px; text-align: center;">
                        <span class="badge badge-\${order.status}">\${order.status.toUpperCase()}</span>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        const btnClearDemo = document.getElementById('btn-clear-demo');
        if (btnClearDemo) {
            btnClearDemo.addEventListener('click', function() {
                const confirmMsg = "<?php echo $current_lang === 'id'; ?>" === 'true' 
                    ? 'Apakah Anda yakin ingin menghapus semua riwayat transaksi demo ini?' 
                    : 'Are you sure you want to clear all demo transaction history?';
                
                if (confirm(confirmMsg)) {
                    localStorage.removeItem('order_history');
                    window.location.reload();
                }
            });
        }
    }
</script>

<?php require_once '../includes/footer.php'; ?>
