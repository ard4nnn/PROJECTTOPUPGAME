<?php
require_once __DIR__ . '/includes/admin-guard.php';
require_once __DIR__ . '/../config/db.php';

$total_trx = 0;
$total_revenue = 0;
$statuses = ['pending' => 0, 'sukses' => 0, 'gagal' => 0];
$recent = [];

if ($db_connected && $pdo) {
    try {
        // Total transactions
        $total_trx = (int)$pdo->query("SELECT COUNT(*) FROM transaksi")->fetchColumn();

        // Total revenue
        $total_revenue = (float)$pdo->query("SELECT COALESCE(SUM(nominal_transfer), 0) FROM transaksi WHERE status='sukses'")->fetchColumn();

        // Status counts
        $stmt = $pdo->query("SELECT status, COUNT(*) as cnt FROM transaksi GROUP BY status");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $st = strtolower($row['status']);
            if (isset($statuses[$st])) {
                $statuses[$st] = (int)$row['cnt'];
            }
        }

        // Recent 10 transactions
        $recent = $pdo->query("
            SELECT t.*, p.nama_produk, g.nama_game, m.nama as nama_metode, u.username
            FROM transaksi t
            LEFT JOIN produk p ON t.produk_id = p.id
            LEFT JOIN games g ON p.game_id = g.id
            LEFT JOIN metode_bayar m ON t.metode_bayar_id = m.id
            LEFT JOIN users u ON t.user_id = u.id
            ORDER BY t.created_at DESC LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Fail silently
    }
}

function rupiah($n) {
    return 'Rp ' . number_format($n, 0, ',', '.');
}

function statusBadge($s) {
    $s = strtolower($s);
    $m = [
        'pending' => ['Menunggu', 'pending'],
        'sukses' => ['Sukses', 'success'],
        'gagal' => ['Gagal', 'failed']
    ];
    $d = $m[$s] ?? [ucfirst($s), 'pending'];
    return "<span class=\"ft-badge {$d[1]}\">{$d[0]}</span>";
}

require_once __DIR__ . '/../includes/header.php';
?>
<!-- Link dashboard styling -->
<link rel="stylesheet" href="../user/dashboard/dashboard.css">

<div class="ft-wrapper">
  <?php include __DIR__ . '/includes/admin-sidebar.php'; ?>
  <main class="ft-main">

    <div class="ft-page-header">
      <h1 class="ft-page-title">Dashboard Admin</h1>
      <p class="ft-page-sub">
          Selamat datang kembali di panel kontrol, Administrator <strong><?= htmlspecialchars($_SESSION['admin_username'] ?? '') ?></strong>.
      </p>
    </div>

    <!-- Stats -->
    <p class="ft-section-title">Ringkasan Sistem</p>
    <div class="ft-stats-row">
      <div class="ft-stat-card">
        <span class="ft-stat-number"><?= $total_trx ?></span>
        <p class="ft-stat-label">Total Semua Transaksi</p>
      </div>
      <div class="ft-stat-card">
        <span class="ft-stat-number" style="color: #4ade80;"><?= rupiah($total_revenue) ?></span>
        <p class="ft-stat-label">Total Pendapatan (Sukses)</p>
      </div>
    </div>

    <div class="ft-status-row" style="grid-template-columns: repeat(3, 1fr);">
      <div class="ft-status-card pending">
        <span class="ft-status-number"><?= $statuses['pending'] ?></span>
        <p class="ft-status-label">Menunggu (Pending)</p>
      </div>
      <div class="ft-status-card success">
        <span class="ft-status-number"><?= $statuses['sukses'] ?></span>
        <p class="ft-status-label">Sukses</p>
      </div>
      <div class="ft-status-card failed">
        <span class="ft-status-number"><?= $statuses['gagal'] ?></span>
        <p class="ft-status-label">Gagal</p>
      </div>
    </div>

    <!-- Tabel Transaksi Terbaru -->
    <p class="ft-section-title">Riwayat 10 Transaksi Terbaru</p>
    <div class="ft-table-wrap">
      <table class="ft-table">
        <thead>
          <tr>
            <th>Nomor Invoice</th>
            <th>Pelanggan</th>
            <th>Game / Item</th>
            <th>User Game ID</th>
            <th>Nominal</th>
            <th>Metode</th>
            <th>Tanggal</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($recent)): ?>
            <?php foreach ($recent as $t): ?>
              <tr>
                <td><code style="color:#FBBF24;font-size:11px;">#<?= htmlspecialchars($t['id']) ?></code></td>
                <td><?= htmlspecialchars($t['username'] ?? '-') ?></td>
                <td>
                  <span style="font-weight:600;"><?= htmlspecialchars($t['nama_game'] ?? '-') ?></span>
                  <div style="font-size:10.5px;color:#888;"><?= htmlspecialchars($t['nama_produk'] ?? '-') ?></div>
                </td>
                <td><code><?= htmlspecialchars($t['id_game_user'] ?? '-') ?></code></td>
                <td><?= rupiah($t['nominal_transfer']) ?></td>
                <td style="color:#888;"><?= strtoupper(htmlspecialchars($t['nama_metode'] ?? '-')) ?></td>
                <td style="color:#888;"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></td>
                <td><?= statusBadge($t['status']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8">
                <div class="ft-empty">
                  <div class="ft-empty-icon">📊</div>
                  <p class="ft-empty-title">Data tidak ditemukan!</p>
                  <p class="ft-empty-sub">Belum ada data transaksi di dalam sistem.</p>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div style="text-align:right; margin-top:0.75rem;">
      <a href="transaksi.php" class="ft-btn ft-btn-secondary" style="color: #FBBF24; text-decoration: none; font-weight:600; font-size:13px;">Kelola Semua Transaksi →</a>
    </div>

  </main>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
