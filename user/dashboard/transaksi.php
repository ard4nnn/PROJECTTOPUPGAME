<?php
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../../config/db.php';

$user_id = $_SESSION['user_id'];
$transactions = [];

if ($db_connected && $pdo) {
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
        $stmt->execute([$user_id]);
        $transactions = $stmt->fetchAll();
    } catch (PDOException $e) {
        // Fallback ke array kosong jika query error
    }
}
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - FUNtopup</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .ft-table-wrapper {
            width: 100%;
            overflow-x: auto;
            margin-top: 16px;
        }

        .ft-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        .ft-table th, .ft-table td {
            padding: 16px;
            border-bottom: 1px solid var(--ft-border);
        }

        .ft-table th {
            color: var(--ft-text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .ft-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .ft-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .ft-badge-pending {
            background-color: rgba(252, 213, 53, 0.1);
            color: var(--ft-yellow);
            border: 1px solid rgba(252, 213, 53, 0.25);
        }

        .ft-badge-sukses {
            background-color: rgba(14, 203, 129, 0.1);
            color: var(--ft-success);
            border: 1px solid rgba(14, 203, 129, 0.25);
        }

        .ft-badge-gagal {
            background-color: rgba(246, 70, 93, 0.1);
            color: var(--ft-danger);
            border: 1px solid rgba(246, 70, 93, 0.25);
        }
    </style>
</head>
<body>
    <div class="ft-wrapper">
        <?php include 'sidebar.php'; ?>
        
        <main class="ft-main">
            <!-- Header -->
            <div class="ft-page-header">
                <h1 class="ft-page-title">Riwayat Transaksi</h1>
                <p class="ft-page-sub">Pantau status seluruh pembelian voucher dan top up Anda di FUNtopup.</p>
            </div>
            
            <!-- Table Card -->
            <div class="ft-card">
                <div class="ft-table-wrapper">
                    <table class="ft-table">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Game</th>
                                <th>Produk</th>
                                <th>ID Player</th>
                                <th>Pembayaran</th>
                                <th>Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($transactions) > 0): ?>
                                <?php foreach ($transactions as $tx): ?>
                                    <tr>
                                        <td><strong>#<?php echo htmlspecialchars($tx['id']); ?></strong></td>
                                        <td><?php echo date("d-m-Y H:i", strtotime($tx['created_at'])); ?></td>
                                        <td><?php echo htmlspecialchars($tx['nama_game']); ?></td>
                                        <td><?php echo htmlspecialchars($tx['nama_produk']); ?></td>
                                        <td><code><?php echo htmlspecialchars($tx['id_game_user']); ?></code></td>
                                        <td><?php echo htmlspecialchars($tx['nama_metode']); ?></td>
                                        <td>Rp <?php echo number_format($tx['nominal_transfer'], 0, ',', '.'); ?></td>
                                        <td>
                                            <?php 
                                                $status = strtolower($tx['status']);
                                                $badge_class = 'ft-badge-pending';
                                                if ($status === 'sukses' || $status === 'success') $badge_class = 'ft-badge-sukses';
                                                if ($status === 'gagal' || $status === 'failed') $badge_class = 'ft-badge-gagal';
                                            ?>
                                            <span class="ft-badge <?php echo $badge_class; ?>">
                                                <?php echo htmlspecialchars($tx['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; color: var(--ft-text-muted); padding: 40px;">
                                        Belum ada riwayat transaksi terdaftar.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
