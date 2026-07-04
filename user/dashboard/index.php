<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Load koneksi database
require_once '../../config/db.php';

$user_id = $_SESSION['user_id'];
$today   = date('Y-m-d');

$user = null;
$total_trx = 0;
$total_nominal = 0;
$statuses = ['pending'=>0, 'process'=>0, 'success'=>0, 'failed'=>0];
$recent = [];

if ($db_connected && $pdo) {
    try {
        // Ambil data user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Stats hari ini
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM transaksi WHERE user_id=? AND DATE(created_at)=?");
        $stmt->execute([$user_id, $today]);
        $total_trx = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COALESCE(SUM(nominal_transfer),0) FROM transaksi WHERE user_id=? AND DATE(created_at)=? AND status='sukses'");
        $stmt->execute([$user_id, $today]);
        $total_nominal = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT status, COUNT(*) as cnt FROM transaksi WHERE user_id=? AND DATE(created_at)=? GROUP BY status");
        $stmt->execute([$user_id, $today]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $st = strtolower($row['status']);
            if ($st === 'sukses' || $st === 'success') $st = 'success';
            if ($st === 'proses' || $st === 'process') $st = 'process';
            if ($st === 'gagal' || $st === 'failed') $st = 'failed';
            if (isset($statuses[$st])) $statuses[$st] = (int)$row['cnt'];
        }

        $stmt = $pdo->prepare("
            SELECT t.*, p.nama_produk, g.nama_game, m.nama as nama_metode
            FROM transaksi t
            LEFT JOIN produk p ON t.produk_id = p.id
            LEFT JOIN games g ON p.game_id = g.id
            LEFT JOIN metode_bayar m ON t.metode_bayar_id = m.id
            WHERE t.user_id=? AND DATE(t.created_at)=?
            ORDER BY t.created_at DESC LIMIT 10
        ");
        $stmt->execute([$user_id, $today]);
        $recent = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Fallback ke empty values jika terjadi error query
    }
}

// Fallback data profil jika MySQL offline
if (!$user) {
    $user = [
        'username' => $_SESSION['username'] ?? 'User',
        'email' => $_SESSION['email'] ?? 'demo@example.com',
        'no_hp' => $_SESSION['no_hp'] ?? $_SESSION['phone'] ?? '-',
        'created_at' => date('Y-m-d H:i:s')
    ];
}

function rupiah($n) { return 'Rp '.number_format($n,0,',','.'); }
function statusBadge($s) {
    $s = strtolower($s);
    $m = [
        'pending'=>['Menunggu','pending'],
        'proses'=>['Dalam Proses','process'],
        'process'=>['Dalam Proses','process'],
        'sukses'=>['Sukses','success'],
        'success'=>['Sukses','success'],
        'gagal'=>['Gagal','failed'],
        'failed'=>['Gagal','failed']
    ];
    $d = $m[$s] ?? [ucfirst($s),'pending'];
    return "<span class=\"ft-badge {$d[1]}\">{$d[0]}</span>";
}
$avatar_char = strtoupper(substr($user['username'] ?? 'U', 0, 1));

// Load header website FUNtopup
require_once '../../includes/header.php';
?>
<!-- Link dashboard styling -->
<link rel="stylesheet" href="dashboard.css">

<div class="ft-wrapper">
<?php include 'sidebar.php'; ?>
<main class="ft-main">

  <div class="ft-page-header">
    <h1 class="ft-page-title">Dashboard</h1>
    <p class="ft-page-sub">
        Selamat datang, <strong><?= htmlspecialchars($user['username'] ?? '') ?></strong> 👋 
        <?php if (!$db_connected): ?>
            <span style="color:#FBBF24; font-weight:800; margin-left:10px;">[🔌 Mode Demo Aktif]</span>
        <?php endif; ?>
    </p>
  </div>

  <!-- Profil Card -->
  <div class="ft-card">
    <div class="ft-profile-card">
      <div class="ft-profile-left">
        <div class="ft-profile-avatar"><?= $avatar_char ?></div>
        <div>
          <p class="ft-profile-name"><?= htmlspecialchars($user['username'] ?? '') ?></p>
          <span class="ft-member-badge"><?= htmlspecialchars($_SESSION['level'] ?? 'Member') ?></span>
          <?php if (!empty($user['email'])): ?>
            <p class="ft-profile-phone">✉ <?= htmlspecialchars($user['email']) ?></p>
          <?php endif; ?>
          <?php if (!empty($user['no_hp'])): ?>
            <p class="ft-profile-phone">📞 <?= htmlspecialchars($user['no_hp']) ?></p>
          <?php endif; ?>
        </div>
      </div>
      <a href="profil.php" class="ft-settings-link" title="Edit Profil">⚙</a>
    </div>
  </div>

  <!-- Stats -->
  <p class="ft-section-title">Transaksi Hari Ini</p>
  <div class="ft-stats-row">
    <div class="ft-stat-card">
      <span class="ft-stat-number" id="stat-total-trx"><?= $total_trx ?></span>
      <p class="ft-stat-label">Total Transaksi</p>
    </div>
    <div class="ft-stat-card">
      <span class="ft-stat-number" id="stat-total-nominal"><?= rupiah($total_nominal) ?></span>
      <p class="ft-stat-label">Total Pengeluaran</p>
    </div>
  </div>

  <div class="ft-status-row">
    <div class="ft-status-card pending">
      <span class="ft-status-number" id="stat-pending"><?= $statuses['pending'] ?></span>
      <p class="ft-status-label">Menunggu</p>
    </div>
    <div class="ft-status-card process">
      <span class="ft-status-number" id="stat-process"><?= $statuses['process'] ?></span>
      <p class="ft-status-label">Dalam Proses</p>
    </div>
    <div class="ft-status-card success">
      <span class="ft-status-number" id="stat-success"><?= $statuses['success'] ?></span>
      <p class="ft-status-label">Sukses</p>
    </div>
    <div class="ft-status-card failed">
      <span class="ft-status-number" id="stat-failed"><?= $statuses['failed'] ?></span>
      <p class="ft-status-label">Gagal</p>
    </div>
  </div>

  <!-- Tabel Transaksi Terbaru -->
  <p class="ft-section-title">Riwayat Transaksi Terbaru Hari Ini</p>
  <div class="ft-table-wrap">
    <table class="ft-table">
      <thead>
        <tr>
          <th>Nomor Invoice</th><th>Item</th><th>User Input</th>
          <th>Harga</th><th>Metode</th><th>Tanggal</th><th>Status</th>
        </tr>
      </thead>
      <tbody id="recent-transactions-tbody">
        <?php if ($db_connected && !empty($recent)): ?>
          <?php foreach ($recent as $t): ?>
            <tr>
              <td><code style="color:#FBBF24;font-size:11px;">#<?= htmlspecialchars($t['id']) ?></code></td>
              <td><?= htmlspecialchars($t['nama_produk'] ?? $t['game_slug'] ?? '-') ?></td>
              <td><?= htmlspecialchars($t['id_game_user'] ?? '-') ?></td>
              <td><?= rupiah($t['nominal_transfer']) ?></td>
              <td style="color:#888;"><?= strtoupper(htmlspecialchars($t['nama_metode'] ?? '-')) ?></td>
              <td style="color:#888;"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></td>
              <td><?= statusBadge($t['status']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7">
            <div class="ft-empty">
              <div class="ft-empty-icon">📊</div>
              <p class="ft-empty-title">Data tidak ditemukan!</p>
              <p class="ft-empty-sub">Tidak ada transaksi hari ini.</p>
            </div>
          </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  
  <div id="view-all-container" style="text-align:right; margin-top:0.75rem; display: <?= ($db_connected && !empty($recent)) ? 'block' : 'none' ?>;">
    <a href="transaksi.php" class="ft-btn ft-btn-secondary">Lihat Semua →</a>
  </div>

</main>
</div>

<!-- Script pemrosesan client-side jika MySQL offline -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var dbConnected = <?= ($db_connected && $pdo) ? 'true' : 'false'; ?>;
    if (!dbConnected) {
        const orderHistory = JSON.parse(localStorage.getItem('order_history')) || [];
        
        // Buat filter tanggal hari ini (Format local topup.js: "DD/MM/YYYY")
        const todayDate = new Date();
        const todayDay = String(todayDate.getDate()).padStart(2, '0');
        const todayMonth = String(todayDate.getMonth() + 1).padStart(2, '0');
        const todayYear = todayDate.getFullYear();
        const todayPrefix1 = `${todayDay}/${todayMonth}/${todayYear}`;
        const todayPrefix2 = `${todayDay}-${todayMonth}-${todayYear}`;
        
        let totalTrx = 0;
        let totalNominal = 0;
        let statuses = { pending: 0, process: 0, success: 0, failed: 0 };
        let recentRows = [];
        
        orderHistory.forEach(order => {
            const orderDateStr = order.date || '';
            const isToday = orderDateStr.includes(todayPrefix1) || orderDateStr.includes(todayPrefix2);
            
            if (isToday) {
                totalTrx++;
                const status = order.status ? order.status.toLowerCase() : 'pending';
                let mappedStatus = 'pending';
                if (status === 'success' || status === 'sukses') mappedStatus = 'success';
                else if (status === 'process' || status === 'proses') mappedStatus = 'process';
                else if (status === 'failed' || status === 'gagal') mappedStatus = 'failed';
                
                if (mappedStatus in statuses) {
                    statuses[mappedStatus]++;
                }
                
                if (mappedStatus === 'success') {
                    totalNominal += parseFloat(order.price || 0);
                }
                
                recentRows.push(order);
            }
        });
        
        // Tulis stats ke DOM
        document.getElementById('stat-total-trx').textContent = totalTrx;
        document.getElementById('stat-total-nominal').textContent = 'Rp ' + totalNominal.toLocaleString('id-ID');
        document.getElementById('stat-pending').textContent = statuses.pending;
        document.getElementById('stat-process').textContent = statuses.process;
        document.getElementById('stat-success').textContent = statuses.success;
        document.getElementById('stat-failed').textContent = statuses.failed;
        
        // Render tabel transaksi terbaru hari ini
        const tbody = document.getElementById('recent-transactions-tbody');
        if (recentRows.length > 0) {
            tbody.innerHTML = ''; // bersihkan kosong placeholder
            
            recentRows.slice(0, 10).forEach(order => {
                const tr = document.createElement('tr');
                
                const status = order.status ? order.status.toLowerCase() : 'pending';
                let badgeClass = 'pending';
                let badgeText = 'Menunggu';
                if (status === 'success' || status === 'sukses') {
                    badgeClass = 'success';
                    badgeText = 'Sukses';
                } else if (status === 'process' || status === 'proses') {
                    badgeClass = 'process';
                    badgeText = 'Dalam Proses';
                } else if (status === 'failed' || status === 'gagal') {
                    badgeClass = 'failed';
                    badgeText = 'Gagal';
                }
                
                const formattedPrice = 'Rp ' + parseFloat(order.price || 0).toLocaleString('id-ID');
                
                tr.innerHTML = `
                    <td><code style="color:#FBBF24;font-size:11px;">#${order.id} (Demo)</code></td>
                    <td>${order.product || order.game || '-'}</td>
                    <td>${order.targetId || '-'}</td>
                    <td>${formattedPrice}</td>
                    <td style="color:#888;">${(order.payment || '-').toUpperCase()}</td>
                    <td style="color:#888;">${order.date}</td>
                    <td><span class="ft-badge ${badgeClass}">${badgeText}</span></td>
                `;
                tbody.appendChild(tr);
            });
            
            // Tampilkan tombol lihat semua
            const viewAllContainer = document.getElementById('view-all-container');
            if (viewAllContainer) {
                viewAllContainer.style.display = 'block';
            }
        }
    }
});
</script>

<?php
// Load footer website FUNtopup
require_once '../../includes/footer.php';
?>
