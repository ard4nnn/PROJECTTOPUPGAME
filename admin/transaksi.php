<?php
require_once __DIR__ . '/includes/admin-guard.php';
require_once __DIR__ . '/../config/db.php';

$success_msg = '';
$error_msg = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    if (!csrf_verify()) {
        $error_msg = 'Token CSRF tidak valid. Silakan coba lagi.';
    } else {
        $trx_id = trim($_POST['trx_id'] ?? '');
        $new_status = trim($_POST['status'] ?? '');

        // Validation: only allow valid enum values
        if (!in_array($new_status, ['pending', 'sukses', 'gagal'])) {
            $error_msg = 'Status transaksi tidak valid!';
        } else {
            if ($db_connected && $pdo) {
                try {
                    $stmt = $pdo->prepare("UPDATE transaksi SET status = ? WHERE id = ?");
                    $stmt->execute([$new_status, $trx_id]);
                    
                    if ($stmt->rowCount() > 0) {
                        $success_msg = "Berhasil memperbarui transaksi #{$trx_id} menjadi " . strtoupper($new_status);
                    } else {
                        $error_msg = "Transaksi #{$trx_id} tidak ditemukan atau status tidak berubah.";
                    }
                } catch (PDOException $e) {
                    $error_msg = "Gagal memperbarui database: " . $e->getMessage();
                }
            } else {
                $error_msg = "Koneksi database terputus.";
            }
        }
    }
}

// Filters & Pagination
$f_status  = $_GET['status']   ?? 'all';
$search    = trim($_GET['cari'] ?? '');
$per_page  = max(1, (int)($_GET['per_page'] ?? 10));
$page      = max(1, (int)($_GET['page'] ?? 1));
$offset    = ($page - 1) * $per_page;

$rows = [];
$total_rows = 0;
$total_pages = 1;

if ($db_connected && $pdo) {
    try {
        $where = [];
        $params = [];

        if ($f_status !== 'all') {
            $where[] = "t.status = :status";
            $params[':status'] = $f_status;
        }

        if ($search !== '') {
            $where[] = "(t.id LIKE :s OR u.username LIKE :s OR p.nama_produk LIKE :s OR t.id_game_user LIKE :s)";
            $params[':s'] = '%' . $search . '%';
        }

        $wsql = '';
        if (!empty($where)) {
            $wsql = 'WHERE ' . implode(' AND ', $where);
        }

        // Count total rows
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM transaksi t
            LEFT JOIN users u ON t.user_id = u.id
            LEFT JOIN produk p ON t.produk_id = p.id
            $wsql
        ");
        $stmt->execute($params);
        $total_rows  = (int)$stmt->fetchColumn();
        $total_pages = max(1, ceil($total_rows / $per_page));

        // Fetch items
        $stmt = $pdo->prepare("
            SELECT t.*, p.nama_produk, g.nama_game, m.nama as nama_metode, u.username, u.email
            FROM transaksi t
            LEFT JOIN produk p ON t.produk_id = p.id
            LEFT JOIN games g ON p.game_id = g.id
            LEFT JOIN metode_bayar m ON t.metode_bayar_id = m.id
            LEFT JOIN users u ON t.user_id = u.id
            $wsql
            ORDER BY t.created_at DESC
            LIMIT :lim OFFSET :off
        ");
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':lim', $per_page, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // fail silently
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
      <h1 class="ft-page-title">Kelola Transaksi</h1>
      <p class="ft-page-sub">
          Lihat, cari, dan ubah status transaksi client FUNtopup.
      </p>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($success_msg)): ?>
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg text-xs mb-4">
            ✅ <?= htmlspecialchars($success_msg) ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error_msg)): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg text-xs mb-4">
            ⚠️ <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php endif; ?>

    <!-- Filter Card -->
    <div class="ft-filter-card">
      <form method="GET" action="">
        <div class="ft-filter-grid">
          <div>
            <label class="ft-label" for="filter-status">Status Transaksi</label>
            <select name="status" id="filter-status" class="ft-select">
              <option value="all" <?= $f_status === 'all' ? 'selected' : '' ?>>Semua Status</option>
              <option value="pending" <?= $f_status === 'pending' ? 'selected' : '' ?>>Pending</option>
              <option value="sukses" <?= $f_status === 'sukses' ? 'selected' : '' ?>>Sukses</option>
              <option value="gagal" <?= $f_status === 'gagal' ? 'selected' : '' ?>>Gagal</option>
            </select>
          </div>
          <div>
            <label class="ft-label" for="search-box">Cari Invoice/User/Produk</label>
            <input type="text" name="cari" id="search-box" class="ft-input" placeholder="Keyword..." value="<?= htmlspecialchars($search) ?>">
          </div>
          <div>
            <label class="ft-label" for="per-page-box">Item Per Halaman</label>
            <select name="per_page" id="per-page-box" class="ft-select">
              <option value="10" <?= $per_page === 10 ? 'selected' : '' ?>>10 Item</option>
              <option value="25" <?= $per_page === 25 ? 'selected' : '' ?>>25 Item</option>
              <option value="50" <?= $per_page === 50 ? 'selected' : '' ?>>50 Item</option>
            </select>
          </div>
        </div>
        <div class="ft-filter-actions">
          <button type="submit" class="ft-btn ft-btn-primary">Terapkan Filter</button>
          <a href="transaksi.php" class="ft-btn ft-btn-secondary">Reset</a>
        </div>
      </form>
    </div>

    <!-- Data Table -->
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
            <th>Aksi Update</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $t): ?>
              <tr>
                <td><code style="color:#FBBF24;font-size:11px;">#<?= htmlspecialchars($t['id']) ?></code></td>
                <td>
                  <span style="font-weight:600;"><?= htmlspecialchars($t['username'] ?? '-') ?></span>
                  <div style="font-size:10.5px;color:#888;"><?= htmlspecialchars($t['email'] ?? '-') ?></div>
                </td>
                <td>
                  <span style="font-weight:600;"><?= htmlspecialchars($t['nama_game'] ?? '-') ?></span>
                  <div style="font-size:10.5px;color:#888;"><?= htmlspecialchars($t['nama_produk'] ?? '-') ?></div>
                </td>
                <td><code><?= htmlspecialchars($t['id_game_user'] ?? '-') ?></code></td>
                <td><?= rupiah($t['nominal_transfer']) ?></td>
                <td style="color:#888;"><?= strtoupper(htmlspecialchars($t['nama_metode'] ?? '-')) ?></td>
                <td style="color:#888;"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></td>
                <td><?= statusBadge($t['status']) ?></td>
                <td>
                  <form method="POST" action="" style="display:inline-flex; align-items:center; gap:5px; margin:0;">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="trx_id" value="<?= htmlspecialchars($t['id']) ?>">
                    <select name="status" class="ft-select" style="padding: 2px 5px; font-size:11.5px; width:auto; border-color:#252548;">
                      <option value="pending" <?= $t['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                      <option value="sukses" <?= $t['status'] === 'sukses' ? 'selected' : '' ?>>Sukses</option>
                      <option value="gagal" <?= $t['status'] === 'gagal' ? 'selected' : '' ?>>Gagal</option>
                    </select>
                    <button type="submit" class="ft-btn ft-btn-primary" style="padding: 3px 8px; font-size:11px;" onclick="return confirm('Ubah status transaksi #<?= htmlspecialchars($t['id']) ?>?')">Set</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9">
                <div class="ft-empty">
                  <div class="ft-empty-icon">📊</div>
                  <p class="ft-empty-title">Data tidak ditemukan!</p>
                  <p class="ft-empty-sub">Silakan sesuaikan filter atau pencarian Anda.</p>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
      
      <!-- Pagination -->
      <div class="ft-pagination">
        <span>Menampilkan Halaman <?= $page ?> dari <?= $total_pages ?> (Total: <?= $total_rows ?> item)</span>
        <div style="display:flex; gap:5px;">
          <?php if ($page > 1): ?>
            <a href="?status=<?= urlencode($f_status) ?>&cari=<?= urlencode($search) ?>&per_page=<?= $per_page ?>&page=<?= $page - 1 ?>" class="ft-btn ft-btn-secondary" style="padding:4px 8px;">&larr; Prev</a>
          <?php endif; ?>
          <?php if ($page < $total_pages): ?>
            <a href="?status=<?= urlencode($f_status) ?>&cari=<?= urlencode($search) ?>&per_page=<?= $per_page ?>&page=<?= $page + 1 ?>" class="ft-btn ft-btn-secondary" style="padding:4px 8px;">Next &rarr;</a>
          <?php endif; ?>
        </div>
      </div>

    </div>

  </main>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
