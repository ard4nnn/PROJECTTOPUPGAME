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

<div class="container" style="margin-top: 30px;">
    <h2>Riwayat Transaksi Anda</h2>
    <p style="color: var(--text-muted); font-size: 15px; margin-bottom: 30px;">
        Pantau status pemesanan voucher dan top up Anda secara berkala.
    </p>

    <div class="card" style="padding: 20px; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
            <thead>
                <tr style="border-bottom: 2px solid var(--card-border); text-align: left;">
                    <th style="padding: 12px; font-weight: 700; color: var(--text-muted);">No. Transaksi</th>
                    <th style="padding: 12px; font-weight: 700; color: var(--text-muted);">Tanggal</th>
                    <th style="padding: 12px; font-weight: 700; color: var(--text-muted);">Game</th>
                    <th style="padding: 12px; font-weight: 700; color: var(--text-muted);">Produk</th>
                    <th style="padding: 12px; font-weight: 700; color: var(--text-muted);">ID Akun</th>
                    <th style="padding: 12px; font-weight: 700; color: var(--text-muted);">Metode</th>
                    <th style="padding: 12px; font-weight: 700; color: var(--text-muted);">Harga</th>
                    <th style="padding: 12px; font-weight: 700; color: var(--text-muted); width: 130px; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody id="transaction-tbody">
                <?php if ($db_connected_and_logged_in): ?>
                    <?php if (count($db_transactions) > 0): ?>
                        <?php foreach ($db_transactions as $tx): ?>
                            <tr style="border-bottom: 1px solid var(--card-border);">
                                <td style="padding: 15px 12px; font-weight: 600;">#<?php echo $tx['id']; ?></td>
                                <td style="padding: 15px 12px; font-size: 14px;"><?php echo date("d-m-Y H:i", strtotime($tx['created_at'])); ?></td>
                                <td style="padding: 15px 12px; font-weight: 600;"><?php echo htmlspecialchars($tx['nama_game']); ?></td>
                                <td style="padding: 15px 12px;"><?php echo htmlspecialchars($tx['nama_produk']); ?></td>
                                <td style="padding: 15px 12px;"><code><?php echo htmlspecialchars($tx['id_game_user']); ?></code></td>
                                <td style="padding: 15px 12px;"><?php echo htmlspecialchars($tx['nama_metode']); ?></td>
                                <td style="padding: 15px 12px; font-weight: 700; color: var(--primary-color);">Rp <?php echo number_format($tx['nominal_transfer'], 0, ',', '.'); ?></td>
                                <td style="padding: 15px 12px; text-align: center;">
                                    <span class="badge badge-<?php echo $tx['status']; ?>"><?php echo htmlspecialchars($tx['status']); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="padding: 30px; text-align: center; color: var(--text-muted);">Belum ada riwayat transaksi di database.</td>
                        </tr>
                    <?php endif; ?>
                <?php else: ?>
                    <tr id="no-history-row">
                        <td colspan="8" style="padding: 30px; text-align: center; color: var(--text-muted);">
                            Belum ada riwayat transaksi. Lakukan pembelian di halaman game untuk memunculkan transaksi demo.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="clear-demo-container" style="display: none; margin-top: 15px; text-align: right;">
        <button id="btn-clear-demo" class="btn btn-outline" style="padding: 8px 16px; font-size: 13px; color: var(--danger-color); border-color: rgba(239,68,68,0.3);">
            🗑️ Bersihkan Riwayat Demo
        </button>
    </div>

    <div class="card" style="margin-top: 30px; background-color: var(--card-bg);">
        <h4 style="margin-top: 0; font-size: 16px; font-weight: 700;">Panduan Pembayaran & Proses Top Up:</h4>
        <ol style="padding-left: 20px; line-height: 1.8; font-size: 14px; color: var(--text-muted); margin-bottom: 0;">
            <li>Selesaikan pembayaran Anda menggunakan e-wallet/bank transfer yang telah dipilih.</li>
            <li>Status pesanan Anda akan terdaftar sebagai <span class="badge badge-pending">PENDING</span> terlebih dahulu.</li>
            <li>Admin akan memverifikasi pembayaran Anda, lalu status pesanan akan berubah menjadi <span class="badge badge-sukses">SUKSES</span> dan produk top up otomatis masuk ke akun Anda.</li>
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
            clearDemoContainer.style.display = 'block';

            orderHistory.forEach(order => {
                const tr = document.createElement('tr');
                tr.style.borderBottom = '1px solid var(--card-border)';
                
                tr.innerHTML = `
                    <td style="padding: 15px 12px; font-weight: 600;">#\${order.id} (Demo)</td>
                    <td style="padding: 15px 12px; font-size: 13px; color: var(--text-muted);">\${order.date}</td>
                    <td style="padding: 15px 12px; font-weight: 600;">\${order.game}</td>
                    <td style="padding: 15px 12px;">\${order.product}</td>
                    <td style="padding: 15px 12px;"><code>\${order.targetId}</code></td>
                    <td style="padding: 15px 12px;">\${order.payment}</td>
                    <td style="padding: 15px 12px; font-weight: 700; color: var(--primary-color);">Rp \${order.price.toLocaleString('id-ID')}</td>
                    <td style="padding: 15px 12px; text-align: center;">
                        <span class="badge badge-\${order.status}">\${order.status}</span>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        document.getElementById('btn-clear-demo').addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus semua riwayat transaksi demo ini?')) {
                localStorage.removeItem('order_history');
                window.location.reload();
            }
        });
    }
</script>

<?php require_once '../includes/footer.php'; ?>
